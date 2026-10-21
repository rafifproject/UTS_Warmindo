<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $serviceFee = 0;
        $total = $subtotal + $serviceFee;

        return view('public.cart', compact('cart', 'subtotal', 'serviceFee', 'total'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if (!$product->is_available || $product->stock <= 0) {
            return back()->with('error', 'Maaf, menu "' . $product->name . '" saat ini sedang tidak tersedia atau stok habis.');
        }

        $quantity = max(1, (int)$request->input('quantity', 1));
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $newQty = $cart[$id]['quantity'] + $quantity;
            if ($newQty > $product->stock) {
                return back()->with('error', 'Stok tidak mencukupi. Stok ' . $product->name . ' hanya tersisa ' . $product->stock . '.');
            }
            $cart[$id]['quantity'] = $newQty;
        } else {
            if ($quantity > $product->stock) {
                return back()->with('error', 'Stok tidak mencukupi. Stok ' . $product->name . ' hanya tersisa ' . $product->stock . '.');
            }
            $cart[$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->sell_price,
                'quantity' => $quantity,
                'image' => $product->image_url,
                'sku' => $product->sku,
            ];
        }

        session()->put('cart', $cart);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'cartCount' => array_sum(array_column($cart, 'quantity')),
                'message' => 'Menu berhasil ditambahkan ke keranjang!',
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Menu berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantity = (int)$request->input('quantity', 1);
        $cart = session()->get('cart', []);

        if ($quantity <= 0) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            return back()->with('success', 'Menu dihapus dari keranjang.');
        }

        if ($quantity > $product->stock) {
            return back()->with('error', 'Stok tidak mencukupi! Stok ' . $product->name . ' hanya tersisa ' . $product->stock . '.');
        }

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $quantity;
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Jumlah menu diperbarui.');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return back()->with('success', 'Menu berhasil dihapus dari keranjang.');
    }

    public function service()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('menu')->with('error', 'Keranjang Anda masih kosong. Silakan pilih menu terlebih dahulu.');
        }

        $serviceData = session()->get('checkout_service', [
            'order_type' => 'dine_in',
            'table_number' => '',
        ]);

        return view('public.service', compact('serviceData'));
    }

    public function saveService(Request $request)
    {
        $validated = $request->validate([
            'order_type' => 'required|in:dine_in,take_away',
            'table_number' => 'nullable|string|max:10',
        ]);

        if ($validated['order_type'] === 'dine_in' && empty(trim($validated['table_number'] ?? ''))) {
            return back()->withErrors(['table_number' => 'Nomor meja wajib diisi untuk pesanan makan di tempat (Dine In).'])->withInput();
        }

        if ($validated['order_type'] === 'take_away') {
            $validated['table_number'] = null;
        }

        session()->put('checkout_service', $validated);

        return redirect()->route('cart.checkout');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('menu')->with('error', 'Keranjang Anda masih kosong.');
        }

        $service = session()->get('checkout_service', [
            'order_type' => 'dine_in',
            'table_number' => '01',
        ]);

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $serviceFee = 0;
        $total = $subtotal + $serviceFee;

        return view('public.checkout', compact('cart', 'service', 'subtotal', 'serviceFee', 'total'));
    }

    public function storeOrder(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('menu')->with('error', 'Keranjang kosong.');
        }

        $service = session()->get('checkout_service', [
            'order_type' => 'dine_in',
            'table_number' => null,
        ]);

        if ($service['order_type'] === 'dine_in' && empty($service['table_number'])) {
            return redirect()->route('cart.service')->withErrors(['table_number' => 'Nomor meja wajib diisi untuk Dine In.']);
        }

        $notes = $request->input('notes');

        // Execute atomic database transaction
        $sale = DB::transaction(function () use ($cart, $service, $notes) {
            // Re-validate all items against fresh database and lock
            $subtotal = 0;
            $itemsToCreate = [];

            foreach ($cart as $productId => $cartItem) {
                $product = Product::lockForUpdate()->find($productId);

                if (!$product || !$product->is_available) {
                    throw new \Exception('Menu "' . $cartItem['name'] . '" sudah tidak tersedia.');
                }

                if ($product->stock < $cartItem['quantity']) {
                    throw new \Exception('Stok tidak mencukupi untuk "' . $product->name . '". Stok tersedia hanya ' . $product->stock . '.');
                }

                $itemSubtotal = $product->sell_price * $cartItem['quantity'];
                $subtotal += $itemSubtotal;

                $itemsToCreate[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->sell_price,
                    'quantity' => $cartItem['quantity'],
                    'subtotal' => $itemSubtotal,
                    'status' => 'pending',
                ];
            }

            // Generate unique Order Number like #PSN-0128
            $lastId = Sale::max('id') ?? 0;
            $orderNumber = 'PSN-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

            $sale = Sale::create([
                'sale_number' => $orderNumber,
                'user_id' => null,
                'customer_name' => $service['order_type'] === 'dine_in' ? 'Meja ' . $service['table_number'] : 'Take Away',
                'order_type' => $service['order_type'],
                'table_number' => $service['order_type'] === 'dine_in' ? $service['table_number'] : null,
                'subtotal' => $subtotal,
                'service_fee' => 0,
                'total_amount' => $subtotal,
                'payment_status' => 'pending',
                'order_status' => 'waiting_payment',
                'notes' => $notes,
            ]);

            foreach ($itemsToCreate as $item) {
                $item['sale_id'] = $sale->id;
                SaleItem::create($item);
            }

            return $sale;
        });

        session()->forget(['cart', 'checkout_service']);

        return redirect()->route('cart.success', $sale->sale_number)->with('success', 'Pesanan Anda berhasil dibuat!');
    }

    public function success($saleNumber)
    {
        $sale = Sale::with('items.product')->where('sale_number', $saleNumber)->firstOrFail();
        return view('public.success', compact('sale'));
    }
}
