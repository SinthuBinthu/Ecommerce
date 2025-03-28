<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    // Warenkorb anzeigen
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    // Produkt zum Warenkorb hinzufügen
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produkt wurde dem Warenkorb hinzugefügt!');
    }

    // Produkt aus dem Warenkorb entfernen
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produkt wurde aus dem Warenkorb entfernt.');
    }

    // Warenkorb leeren
    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Warenkorb wurde geleert.');
    }
}
