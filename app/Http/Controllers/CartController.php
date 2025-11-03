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
        return view('page.user.cart');
    }

    public function getCart(Request $request) 
    {
        $user = auth()->user();
        if(!$user) return;

        $data = CartItem::where('user_id', $user->id)->where('status', 'pending')->with('product.images', 'size')->get();
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
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
        if (!$cartItem) {
            return redirect()->back()->with('error', 'Không tìm thấy sản phẩm trong giỏ hàng !');
        }
        $cartItem->delete();
        return redirect()->back()->with('success', 'Xóa thành công !');
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

    public function removeItem($itemId)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        CartItem::where('id', $itemId)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Xóa thành công',
        ]);
    }

    public function updateQuantity($itemId, $quantity)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $cartItem = CartItem::find($itemId);
        if (!$cartItem) {
            return redirect()->back()->with('error', 'Không tìm thấy sản phẩm trong giỏ hàng !');
        }
        $cartItem->update(['quantity' => $quantity]);
        
        return response()->json([
            'success' => true,
            'message' => 'Cập nhập thành công',
        ]);
    }
}
