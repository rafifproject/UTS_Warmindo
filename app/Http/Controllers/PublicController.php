<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        $favorites = Product::with('category')
            ->where('is_available', true)
            ->where('is_favorite', true)
            ->take(4)
            ->get();

        if ($favorites->isEmpty()) {
            $favorites = Product::with('category')
                ->where('is_available', true)
                ->take(4)
                ->get();
        }

        return view('public.home', compact('favorites'));
    }

    public function menu(Request $request)
    {
        $categories = Category::where('is_active', true)->get();
        $selectedCategory = $request->query('category', 'all');
        $search = $request->query('search');

        $query = Product::with('category')->where('is_available', true);

        if ($selectedCategory && $selectedCategory !== 'all') {
            $query->whereHas('category', function ($q) use ($selectedCategory) {
                $q->where('slug', $selectedCategory);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate(12)->withQueryString();

        return view('public.menu', compact('categories', 'selectedCategory', 'search', 'products'));
    }

    public function menuDetail($slug)
    {
        $product = Product::with('category')
            ->where('slug', $slug)
            ->where('is_available', true)
            ->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_available', true)
            ->take(3)
            ->get();

        return view('public.menu-detail', compact('product', 'relatedProducts'));
    }
}
