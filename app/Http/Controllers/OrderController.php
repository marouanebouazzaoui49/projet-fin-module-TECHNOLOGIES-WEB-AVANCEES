<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        $order->load(['items', 'items.product']);
        
        return view('orders.show', compact('order'));
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect('/products')->with('error', 'Le panier est vide !');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('orders.checkout', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect('/products')->with('error', 'Le panier est vide !');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Créer la commande
        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $total,
            'status' => 'pending',
        ]);

        // Créer les articles de la commande
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Vider le panier
        session()->forget('cart');

        return redirect()->route('orders.show', $order)->with('success', 'Commande créée avec succès !');
    }

    public function payment(Order $order, Request $request)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($request->isMethod('post')) {
            // Intégration Stripe ici (optionnelle)
            $order->update([
                'status' => 'completed',
                'stripe_payment_id' => 'mock_' . time(),
            ]);

            return redirect()->route('orders.show', $order)->with('success', 'Paiement effectué avec succès !');
        }

        return view('orders.payment', compact('order'));
    }
}
