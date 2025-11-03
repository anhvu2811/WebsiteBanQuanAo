<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Mail\OrderEmail;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductSize;
use App\Models\CartItem;
use App\Jobs\SendOrderEmail;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('perPage', 10);
        $orders = Order::paginate($perPage);
        return view('page.admin.order.index')->with('orders', $orders);
    }


    // public function destroy(string $id)
    // {
    //     $Order = Order::find($id);
    //     if($Order) {
    //         $Order->delete();
    //         return redirect()->route('Order.index');
    //     }
    //     return redirect()->route('Order.index');
    // }


    public function createOrder(Request $request)
    {
        $order = new Order();
        $order->order_date = Carbon::now()->format('Y-m-d H:i:s');
        $order->customer_id = Auth::id();
        $order->total_price = str_replace('.', '', $request->input('total_price')) ?? 0;
        $order->coupon_code = '';

        $method = $request->input('paymentMethod');
        if($method == 'COD') {
            $status = 'Pending';
        } else if($method == 'Credit Card' || $method == 'QR Code') {
            $status = 'Completed';
        } else {
            $status = 'Canceled';
        }
        $order->payment_method = $method;
        $order->payment_status = $status;
        $order->shipping_address = $request->input('billingAddress') ?? '';
        $order->notes = $request->input('note') ?? '';
        $order->save();

        $userId = Auth::id();
        $cart = CartItem::where('user_id', $userId)->where('status', 'pending')->get();

        foreach ($cart as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item['product_id'];
            $orderItem->size_id = $item['size_id'];
            $orderItem->quantity = $item['quantity'];
            $orderItem->price = $item['price'];
            $orderItem->save();

            // update stock quantity
            $productSize = ProductSize::where('product_id', $item['product_id'])
                                      ->where('size_id', $item['size_id'])
                                      ->first();
            if($productSize && $productSize->stock_quantity >= $item['quantity']) {
                $productSize->stock_quantity -= $item['quantity'];
                $productSize->save();
            }
        }
        
        SendOrderEmail::dispatch($request->input('email'), $cart, $order);

        CartItem::where('user_id', $userId)->update(['status' => 'purchased']);
        $orderId = $order->id;
        
        return view('page.user.thankyou', compact('orderId'));
    }

    public function orderPage(Request $request) 
    {
        $orderAll = Order::where('customer_id', Auth::id())->orderBy('order_date', 'desc')->get();
        $orderPending = Order::where('customer_id', Auth::id())->where('payment_status', Order::PENDING)->orderBy('order_date', 'desc')->get();
        $orderDelivered = Order::where('customer_id', Auth::id())->where('payment_status', Order::COMLETED)->orderBy('order_date', 'desc')->get();
        $orderShipping = Order::where('customer_id', Auth::id())->where('payment_status', Order::SHIPPING)->orderBy('order_date', 'desc')->get();
        $orderCanceled = Order::where('customer_id', Auth::id())->where('payment_status', Order::CANCELED)->orderBy('order_date', 'desc')->get();
        return view('page.user.order', compact('orderAll', 'orderPending', 'orderDelivered', 'orderShipping', 'orderCanceled'));
    }
}
