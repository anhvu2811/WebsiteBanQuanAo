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
            ->where('status', 'pending')
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

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm vào giỏ hàng'
        ]);
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
        $user = auth()->user();
        return view('page.user.checkout', compact('user'));
    }

    public function getCartItemByUser(Request $request)
    {
        $user = auth()->user();
        if(!$user) return;

        $cartItem = CartItem::where('user_id', $user->id)->where('status', 'pending')->get();

        $totalItem = $cartItem->count();
        $totalPrice = 0;
        foreach($cartItem as $item) {
            $totalPrice += $item->price * $item->quantity;
        }

        return response()->json([
            'success' => true,
            'data' => $cartItem,
            'totalItem' => $totalItem,
            'totalPrice' => $totalPrice
        ]);
    }
}
