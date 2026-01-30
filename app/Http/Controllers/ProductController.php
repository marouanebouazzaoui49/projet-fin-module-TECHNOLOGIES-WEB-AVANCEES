<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->paginate(12);
        $categories = Category::all();
        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load('category');
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();
        
        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function byCategory(Category $category)
    {
        $products = $category->products()->paginate(12);
        $categories = Category::all();
        
        return view('products.index', compact('products', 'categories', 'category'));
    }

    public function search(Request $request)
    {
        $search = $request->get('q');
        $products = Product::where('name', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%")
            ->paginate(12);
        $categories = Category::all();
        
        return view('products.index', compact('products', 'categories', 'search'));
    }
}
