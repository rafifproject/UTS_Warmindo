<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'user'])->latest();

        if ($request->filled('supplier_id') && $request->supplier_id !== 'all') {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('purchase_date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('purchase_number', 'like', "%{$s}%");
        }

        $purchases = $query->paginate(10)->withQueryString();
        $suppliers = Supplier::where('is_active', true)->get();

        return view('admin.purchases.index', compact('purchases', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->get();
        $products = Product::orderBy('name')->get();

        // Generate next purchase number like PB-005
        $count = Purchase::count();
        $nextNumber = 'PB-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return view('admin.purchases.create', compact('suppliers', 'products', 'nextNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'purchase_number' => 'required|string|unique:purchases,purchase_number',
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ], [
            'items.required' => 'Minimal pilih 1 item untuk transaksi pembelian.',
            'items.*.quantity.min' => 'Kuantitas produk minimal 1.',
        ]);

        DB::transaction(function () use ($request) {
            $totalAmount = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                $totalAmount += $subtotal;
                $itemsData[] = [
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $subtotal,
                ];
            }

            $purchase = Purchase::create([
                'purchase_number' => $request->purchase_number,
                'supplier_id' => $request->supplier_id,
                'user_id' => auth()->id(),
                'purchase_date' => $request->purchase_date,
                'total_amount' => $totalAmount,
                'status' => 'completed',
                'notes' => $request->notes,
            ]);

            foreach ($itemsData as $data) {
                $data['purchase_id'] = $purchase->id;
                PurchaseItem::create($data);

                // Atomically update product stock & record mutation
                $product = Product::lockForUpdate()->find($data['product_id']);
                $beforeStock = $product->stock;
                $product->increment('stock', $data['quantity']);
                $afterStock = $product->fresh()->stock;

                StockMutation::create([
                    'product_id' => $product->id,
                    'type' => 'in',
                    'reference_type' => 'purchase',
                    'reference_id' => $purchase->id,
                    'reference_number' => $purchase->purchase_number,
                    'quantity' => $data['quantity'],
                    'before_stock' => $beforeStock,
                    'after_stock' => $afterStock,
                    'notes' => 'Pembelian dari supplier: ' . ($purchase->supplier->name ?? 'Supplier'),
                    'user_id' => auth()->id(),
                ]);
            }
        });

        return redirect()->route('admin.purchases.index')->with('success', 'Transaksi pembelian berhasil disimpan dan stok otomatis bertambah!');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'user', 'items.product']);
        return view('admin.purchases.show', compact('purchase'));
    }
}
