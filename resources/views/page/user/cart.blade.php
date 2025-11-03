@extends('layouts/user/layoutmaster')
@section('page_title', 'Giỏ Hàng')
@section('content')
<head>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
   <meta name="csrf-token" content="{{ csrf_token() }}">
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
                  <tbody id="body-cart"></tbody>
               </table>
            </section>
            <div class="cart-summary">
               <div class="total" style="margin-top: 10px">Tổng tiền: <span style="color: #e8b34f" id="total-price-cart">0</span></div>
               <a href="{{ route('cart.checkout') }}">
                  <button class="checkout-button" style="background-color: #e8b34f; color: #fff; border-radius: 3px; height: 50px; line-height: 35px; padding: 0 50px; font-size: 16px;">
                      Tiến hành thanh toán
                  </button>
              </a>
            </div>
         @else
            <p id="non-cart">Không có sản phẩm nào trong giỏ hàng.</p>
         @endif
      </div>
   </div>
   @include('page/user/list_brands');
</div>
@endsection
@section('scripts')
   <script>
      $(document).ready(function() {
         loadCart();

         function loadCart() {
            const nonCart = $('#non-cart');
            if(nonCart.length > 0) {
               return;
            }
            const getCart = "{{ route('cart.get-cart') }}";
            $.ajax({
               url: getCart,
               method: 'GET',
               success: function(response) {
                  if(response.success) {
                     products = response.data;
                     let html =  renderDataTable(products);
                     $('#body-cart').html('');
                     $('#body-cart').html(html);
                  }
               },
               error: function(xhr) {
                  alert(xhr.responseJSON.error);
               }
            });
         }

         function renderDataTable(products) {
            let total = 0;
            let html = '';
            products.forEach(function(item) {
               html += `<tr>
                           <td><img src="/storage/${item.product.images[0].image_url}" alt="${item.product.name}" width="100"></td>
                           <td>
                              ${item.product.name}
                           </td>
                           <td style="text-align: center; font-size: 15px;">
                              ${item.size ? item.size.name : ''}
                           </td>
                           <td style="width: 120px;">
                              <input type="number" value="${item.quantity}" min="1" onchange="updateCartQuantity(event, ${item.id})"/>
                           </td>
                           <td>
                              ${(item.quantity * item.price).toLocaleString('vi-VN')}₫
                           </td>
                           <td>
                              <button class="btn btn-danger" style="border: none; background: none;" onclick="removeItem(event, ${item.id})">
                                 <i class="fa fa-times" style="font-size: 20px; color: #e8b34f; font-weight: bold;"></i>
                              </button>
                           </td>
                     </tr>`;
                  total += item.quantity * item.price;
            });
            $('#total-price').html(total.toLocaleString('vi-VN') + '₫');
            $('#total-price-cart').text(total.toLocaleString('vi-VN') + '₫');
            $('#cart-total').html(products.length);
            return html;
         }

         window.removeItem = function(event, itemId) {
            event.preventDefault();

            const apiRemoveItem = `/remove-item/${itemId}`;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            $.ajax({
               url: apiRemoveItem,
               method: 'DELETE',
               headers: {
                  'X-CSRF-TOKEN': csrfToken
               },
               success: function(response) {
                  if(response.success) {
                     loadCart();
                     toastr.success(response.message);
                  }
               },
              error: function(xhr) {
                  alert(xhr);
               }
            });
         }

         window.updateCartQuantity = function(event, itemId) {
            event.preventDefault();
            const quantity = parseInt(event.target.value);
            if(quantity <= 0) {
               toastr.warning('Số lượng không hợp lệ !');
               loadCart();
               return;
            }
            const apiUpdateQuantity = `/update-quantity/${itemId}/${quantity}`;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')

            $.ajax({
               url: apiUpdateQuantity,
               method: 'POST',
               headers: {
                  'X-CSRF-TOKEN': token
               },
               success: function(response) {
                  if(response.success) {
                     loadCart();
                     toastr.success(response.message);
                  }
               },
              error: function(xhr) {
                  alert(xhr);
               }
            });
         }
      })
   </script>
@endsection
