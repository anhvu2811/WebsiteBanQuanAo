<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Setting;
use App\Models\CartItem;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function cart()
    {
        $setting = Setting::first();
        return view('page.user.cart', compact('setting'));
    }

    public function addToCart(Request $request)
    {
        if(!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();
        $product = Product::findOrFail($request->product_id);

        $cartItem = CartItem::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->where('size_id', intval($request->size_id))
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id'    => $userId,
                'product_id' => $product->id,
                'size_id'    => $request->size_id,
                'quantity'   => $request->quantity,
                'price'      => $request->price,
            ]);
        }

        return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng!');
    }

    public function remove($id)
    {
        $cartItem = CartItem::find($id);
        if ($cartItem) {
            $cartItem->delete();
            return redirect()->back()->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng!');
        }
        return redirect()->back()->with('error', 'Không tìm thấy sản phẩm trong giỏ hàng!');
    }

    public function checkout()
    {
        $user = Auth()->user();
        return view('page.user.checkout', compact('user'));
    }


}
