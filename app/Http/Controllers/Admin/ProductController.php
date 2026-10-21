<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\StockMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Product::with('category');

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('sku', 'like', "%{$s}%");
            });
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'sku' => 'required|string|max:50|unique:products,sku',
            'name' => 'required|string|max:255',
            'buy_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:1000',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'boolean',
            'is_favorite' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::lower(Str::random(4));
        $validated['is_available'] = $request->has('is_available');
        $validated['is_favorite'] = $request->has('is_favorite');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug($validated['name']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/menu'), $filename);
            $validated['image'] = 'images/menu/' . $filename;
        }

        $product = Product::create($validated);

        // Record initial stock mutation if stock > 0
        if ($product->stock > 0) {
            StockMutation::create([
                'product_id' => $product->id,
                'type' => 'in',
                'reference_type' => 'adjustment',
                'reference_id' => null,
                'reference_number' => 'STOK-AWAL',
                'quantity' => $product->stock,
                'before_stock' => 0,
                'after_stock' => $product->stock,
                'notes' => 'Stok awal produk baru',
                'user_id' => auth()->id(),
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk baru berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'stockMutations.user']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'sku' => ['required', 'string', 'max:50', Rule::unique('products')->ignore($product->id)],
            'name' => 'required|string|max:255',
            'buy_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:1000',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'boolean',
            'is_favorite' => 'boolean',
        ]);

        $validated['is_available'] = $request->has('is_available');
        $validated['is_favorite'] = $request->has('is_favorite');

        // Check if stock was adjusted manually
        $oldStock = $product->stock;
        $newStock = (int)$validated['stock'];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug($validated['name']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/menu'), $filename);
            $validated['image'] = 'images/menu/' . $filename;
        }

        $product->update($validated);

        if ($oldStock !== $newStock) {
            StockMutation::create([
                'product_id' => $product->id,
                'type' => $newStock > $oldStock ? 'in' : 'out',
                'reference_type' => 'adjustment',
                'reference_id' => null,
                'reference_number' => 'PENYESUAIAN',
                'quantity' => abs($newStock - $oldStock),
                'before_stock' => $oldStock,
                'after_stock' => $newStock,
                'notes' => 'Penyesuaian manual oleh ' . (auth()->user()->name ?? 'Admin'),
                'user_id' => auth()->id(),
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $productName = $product->name;
        // Soft delete handles safe deletion without breaking past sales/purchases
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk "' . $productName . '" berhasil dinonaktifkan/dihapus.');
    }

    public function lowStock()
    {
        $products = Product::with('category')
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock', 'asc')
            ->paginate(15);

        return view('admin.products.low-stock', compact('products'));
    }

    public function stockMutations(Request $request)
    {
        $query = StockMutation::with(['product', 'user'])->latest();

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('product', function ($pq) use ($s) {
                $pq->where('name', 'like', "%{$s}%")->orWhere('sku', 'like', "%{$s}%");
            });
        }

        $mutations = $query->paginate(15)->withQueryString();

        return view('admin.products.stock-mutations', compact('mutations'));
    }
}
