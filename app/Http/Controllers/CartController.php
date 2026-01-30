<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = $this->calculateTotal($cart);
        $itemCount = array_sum(array_column($cart, 'quantity'));
        
        return view('cart.index', compact('cart', 'total', 'itemCount'));
    }

    public function add(Product $product, Request $request)
    {
        $quantity = $request->get('quantity', 1);
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'image' => $product->image,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produit ajouté au panier !');
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        $quantities = $request->get('quantity', []);

        foreach ($quantities as $productId => $quantity) {
            if (isset($cart[$productId])) {
                if ($quantity > 0) {
                    $cart[$productId]['quantity'] = $quantity;
                } else {
                    unset($cart[$productId]);
                }
            }
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Panier mis à jour !');
    }

    public function remove($productId)
    {
        $cart = session()->get('cart', []);
        unset($cart[$productId]);
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produit supprimé du panier !');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Panier vidé !');
    }

    public function getCart()
    {
        $cart = session()->get('cart', []);
        $itemCount = array_sum(array_column($cart, 'quantity'));
        $total = $this->calculateTotal($cart);

        return response()->json([
            'cart' => $cart,
            'itemCount' => $itemCount,
            'total' => $total,
        ]);
    }

    private function calculateTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}
