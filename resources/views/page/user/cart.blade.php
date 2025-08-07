@extends('layouts/user/layoutmaster')
@section('page_title', 'Giỏ Hàng')
@section('content')
<head>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
   <style>
      .box-heading {
         margin-bottom: 30px;
         margin-top: 30px;
      }

      table {
         width: 100%;
         border-collapse: collapse;
         margin: 20px 0;
      }

      table th, table td {
         padding: 15px;
         text-align: left;
         border-bottom: 1px solid #ddd;
      }

      table th {
         background-color: #f7f7f7;
         color: #333;
      }

      table td img {
         width: 80px;
         height: 80px;
         object-fit: cover;
      }

      table td.product-name {
         font-weight: bold;
      }

      table td.price, table td.quantity, table td.total {
         text-align: center;
      }

      .cart-summary {
         background-color: #fff;
         padding: 20px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
         margin-top: 30px;
         display: flex;
         justify-content: space-between;
      }

      .cart-summary .total {
         font-size: 24px;
         font-weight: bold;
         color: #333;
      }

      .cart-summary .checkout-button {
         background-color: #007bff;
         color: #fff;
         padding: 12px 20px;
         border: none;
         cursor: pointer;
         font-size: 18px;
         border-radius: 5px;
         text-align: center;
      }

      .cart-summary .checkout-button:hover {
         background-color: #0056b3;
      }

      @media (max-width: 768px) {
         .container {
            padding: 10px;
         }

         table {
            width: 100%;
            overflow-x: auto;
            display: block;
            white-space: nowrap;
         }

         table th, table td {
            padding: 10px;
            font-size: 14px;
         }

         table td img {
            width: 60px;
            height: 60px;
         }

         .cart-summary {
            flex-direction: column;
            align-items: center;
         }

         .cart-summary .total {
            margin-bottom: 15px;
            font-size: 20px;
         }

         .cart-summary .checkout-button {
            width: 100%;
            font-size: 16px;
         }

         .cart-summary .checkout-button:hover {
            background-color: #e8b34f;
         }
      }
   </style>
</head>
<div>
   <div class="container">
      <div class="box-heading">
         <h1 class="title-head page-title">Giỏ hàng</h1>
         @if(auth()->check() && $cartCount > 0)
            <section style="background-color: white;">
               <table>
                  <thead>
                     <tr>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Size</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                        <th>Thao tác</th>
                     </tr>
                  </thead>
                  <tbody id="body-cart">
                     @foreach($listCart as $cart)
                     <tr>
                           @php
                              $product = \App\Models\Product::find($cart->product_id);
                           @endphp
                           <td><img src="{{ asset('storage/' . $product->images->first()->image_url) }}" alt="{{ $product->name }}" width="100"></td>
                           <td>
                              {{ $product->name }}
                           </td>
                           <td style="text-align: center; font-size: 15px;">
                              @php
                                 $size = \App\Models\Size::find($cart['size_id']);
                              @endphp
                              {{ $size ? $size->name : '' }}
                           </td>
                           <td>
                              <input type="number" value="{{ $cart['quantity'] }}" min="1" max="10" />
                           </td>
                           <td>
                              {{ number_format($cart['quantity'] * $cart['price'], 0, ',', '.') }}₫
                           </td>
                           <td>
                              <form action="{{ route('cart.remove', $cart->id) }}" method="POST" style="text-align: center; margin-top: 30px;">
                                 @csrf
                                 @method('DELETE')
                                 <button type="submit" class="btn btn-danger" style="border: none; background: none;">
                                    <i class="fa fa-times" style="font-size: 20px; color: #e8b34f; font-weight: bold;"></i>
                              </button>
                              </form>
                           </td>
                     </tr>
                     @endforeach
               </tbody>
               </table>
            </section>

            <div class="cart-summary">
               <div class="total" style="margin-top: 10px">Tổng tiền: <span style="color: #e8b34f">{{ number_format($total , 0, ',', '.') }}₫</span></div>
               <a href="{{ route('cart.checkout') }}">
                  <button class="checkout-button" style="background-color: #e8b34f; color: #fff; border-radius: 3px; height: 50px; line-height: 35px; padding: 0 50px; font-size: 16px;">
                      Tiến hành thanh toán
                  </button>
              </a>
            </div>
         @else
            <p>Không có sản phẩm nào trong giỏ hàng.</p>
         @endif
      </div>
   </div>
   @include('page/user/list_brands');
</div>
@endsection
@section('script')
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script>
      $(document).ready(function() {
         if($('#body-cart')) {
            console.log('Body-cart');
         }
      })
   </script>
@endsection
