@extends('layouts/user/resources')
<style>
   .user-name-dropdown {
      position: relative;
   }

   .user-name-dropdown:hover .dropdown-menu-user {
      display: block;
      cursor: pointer;
   }

   .dropdown-menu-user {
      display: none;
      position: absolute;
      top: 100%;
      left: 0;
      background-color: black;
      color: #fff;
      border: 1px solid black;
      min-width: 130px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
      z-index: 1000;
      margin-top: -2px;
      border-radius: 5px;
   }

   .dropdown-menu-user li {
      list-style: none;
      height: 50px;
   }

   .dropdown-menu-user li a {
      text-decoration: none;
      color: #fff;
   }

   .dropdown-menu-user li a:hover {
      color: #007bff;
   }

   .dropdown-menu-user li button {
      background-color: #333;
      color: #fff;
      padding: 3px 7px;
      border: none;
      font-size: 14px;
      border-radius: 5px;
      transition: background-color 0.3s, transform 0.3s;
      cursor: pointer;
      width: 100%;
      text-align: left;
      margin-top: 10px;
   }

   .dropdown-menu-user li button:hover {
      background-color: #e8b34f;
      transform: scale(1.05);
   }

   .dropdown-menu-user li button:focus {
      outline: none;
   }

   /* The Modal (background) */
   .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
   }

   /* Modal Content (The Form) */
   .modal-content {
      background-color: #fff;
      margin: 15% auto;
      padding: 20px;
      border: 1px solid #ccc;
      width: 350px;
      border-radius: 8px;
   }

   /* The Close Button */
   .close {
      color: #aaa;
      font-size: 28px;
      font-weight: bold;
      position: absolute;
      top: 5px;
      right: 15px;
   }

   .close:hover,
   .close:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
   }

   /* Style for form inputs */
   input[type="password"] {
      width: 100%;
      padding: 8px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 4px;
   }

   /* Style for the submit button */
   button[type="submit"] {
      background-color: #e8b34f;
      color: #fff;
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
   }

   button[type="submit"]:hover {
      background-color: #e8b34f;
   }

   .password-wrapper {
      position: relative;
   }

   .toggle-password {
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 18px;
   }

   .toggle-password i {
      color: #aaa;
   }

   /* Thêm một số hiệu ứng khi hover */
   .toggle-password:hover i {
      color: #333;
   }


</style>
<header class="header">
    <div class="topbar">
       <div class="container">
          <div class="row">
             <div class="col-sm-6 col-md-7 a-left">
                <span class="header-contact-item"><i class="fa fa-map-marker"></i> 
                {{ $setting->location ?? 'NULL' }}
                </span>
                <span class="header-contact-item hidden-sm"><i class="fa fa-mobile-alt"></i>
                  <a href="tel:19006750" style="color: #fff;">{{ $setting->hotline ?? 'NULL' }}</a>
                </span>
                <span class="header-contact-item hidden-sm hidden-xs hidden-md"><i class="fa fa-clock"></i>
                  {{ $setting->time_active ?? 'NULL'}}
                </span>
             </div>
               <div class="col-sm-6 col-md-5 col-xs-12">
                  <ul class="list-inline f-right">
                     {{-- <li><i class="fa fa-unlock-alt"></i> <a href="/account/register">Đăng ký</a></li> --}}
                     <?php 
                        $user = auth()->user();
                     ?>
                     @if($user)
                        <li class="user-name-dropdown">
                           <i class="fa fa-user"></i> {{ $user->name }}
                           <ul class="dropdown-menu-user">
                                 <li>
                                    <button type="button" id="change-password-btn">Đổi mật khẩu</button>
                                 </li>
                                 <li>
                                    <form action="{{ route('logout') }}" method="post">
                                       @csrf
                                       <button type="submit">Đăng xuất</button>
                                    </form>
                                 </li>
                           </ul>
                        </li>
                     @else
                        <li><i class="fa fa-user"></i> <a href="/account/login">Đăng nhập</a></li>
                     @endif
                     <li class="search">
                        <a href="javascript:;"><i class="fa fa-search"></i></a>
                        <div class="header_search search_form">
                           <form class="input-group search-bar search_form" action="/product/search" method="get" role="search">
                                 <input type="search" name="q" value="" placeholder="Tìm kiếm sản phẩm..." class="input-group-field st-default-search-input search-text" autocomplete="off">
                                 <span class="input-group-btn">
                                    <button class="btn icon-fallback-text">
                                       <i class="fa fa-search"></i>
                                    </button>
                                 </span>
                           </form>
                        </div>
                     </li>
                  </ul>
               </div>
          </div>
       </div>
    </div>
    <nav>
       <ul id="nav" class="nav container">
          <li class="menu-bar hidden-md hidden-lg">
             <img src="{{ asset('images/menu-bar.png') }}" alt="menu bar">
          </li>
          <li class="nav-item hidden-sm hidden-xs active"><a class="nav-link" href="/">Trang chủ</a></li>
          <li class="nav-item hidden-sm hidden-xs "><a class="nav-link" href="/about">Giới thiệu</a></li>
          <li class="nav-item logo inline-block">
             <a href="/" class="logo-wrapper ">					
             <img src="{{ asset('images/logo.png') }}" alt="logo ">					
             </a>
          </li>
          <li class="nav-item hidden-sm hidden-xs ">
             <a href="/collections/all" class="nav-link">Sản phẩm</a>
          </li>
          <li class="nav-item hidden-sm hidden-xs "><a class="nav-link" href="/news">Tin tức</a></li>
          <li class="top-cart-contain f-right">
             <div class="mini-cart text-xs-center">
                <div class="heading-cart">
                   <a class="cart-icon" href="/cart">
                     <i class="fa fa-shopping-bag"></i>
                     @if(session('cart') && count(session('cart')) > 0)
                        <span class="cartCount count_item_pr" id="cart-total">{{ count(session('cart')) }}</span>
                     @endif
                   </a>
                </div>
                <div class="top-cart-content">
                   <ul id="cart-sidebar" class="mini-products-list count_li">
                      <li class="list-item">
                         <ul></ul>
                      </li>
                      <li class="action">
                         <ul>
                            <li class="li-fix-1">
                               <div class="top-subtotal">
                                  Tổng tiền thanh toán: 
                                  @if(session('cart'))
                                    @php
                                       $total = 0;
                                    @endphp
                                    @foreach(session('cart') as $cart)
                                       @php
                                          $itemTotal = $cart['quantity'] * $cart['price'];
                                          $total += $itemTotal;
                                       @endphp
                                    @endforeach
                                    <span class="price">{{ number_format($total , 0, ',', '.') }}₫</span>
                                  @endif
                               </div>
                            </li>
                            <li class="li-fix-2" style="">
                               <div class="actions">
                                  <a href="{{ route('cart.index') }}" class="btn btn-primary">
                                  <span>Giỏ hàng</span>
                                  </a>
                                  <a href="{{ route('cart.checkout') }}" class="btn btn-checkout btn-gray">
                                  <span>Thanh toán</span>
                                  </a>
                               </div>
                            </li>
                         </ul>
                      </li>
                   </ul>
                </div>
             </div>
          </li>
       </ul>
    </nav>
    <nav>
       <ul id="nav-mobile" class="nav hidden-md hidden-lg container">
          <li class="nav-item active"><a class="nav-link" href="/">Trang chủ</a></li>
          <li class="nav-item "><a class="nav-link" href="/about">Giới thiệu</a></li>
          <li class="nav-item ">
             <a href="/collections/all" class="nav-link">Sản phẩm</a>
          </li>
          <li class="nav-item "><a class="nav-link" href="/news">Tin tức</a></li>
       </ul>
    </nav>

   <div id="change-password-modal" id="change-password-form" class="modal">
      <div class="modal-content">
         <span id="close-modal" class="close">&times;</span>
         <h2 style="text-align: center; margin-bottom: 20px; margin-top: -10px;">Đổi mật khẩu</h2>
         <form action="{{ route('change-password') }}" method="POST">
               @csrf
               <div>
                  <label for="current-password">Mật khẩu hiện tại</label>
                  <div class="password-wrapper">
                     <input type="password" id="current-password" name="current-password" required>
                     <span class="toggle-password" id="toggle-current-password">
                         <i class="fa fa-eye"></i> <!-- Icon "show" -->
                     </span>
                 </div>
               </div>
               <div>
                  <label for="new-password">Mật khẩu mới</label>
                  <div class="password-wrapper">
                    <input type="password" id="new-password" name="new-password" required>
                    <span class="toggle-password" id="toggle-new-password">
                        <i class="fa fa-eye"></i> <!-- Icon "show" -->
                    </span>
                </div>
               </div>
               <div>
                  <label for="confirm-password">Xác nhận mật khẩu</label>
                  <div class="password-wrapper">
                     <input type="password" id="confirm-password" name="confirm-password" required>
                     <span class="toggle-password" id="toggle-confirm-password">
                         <i class="fa fa-eye"></i> <!-- Icon "show" -->
                     </span>
                 </div>
               </div>
               <div id="response-message"></div>
               <center><button type="submit">Lưu thay đổi</button></center>
         </form>
      </div>
   </div>

    <script>
      document.addEventListener("DOMContentLoaded", function() {
         const menuBar = document.querySelector('.menu-bar');
         const navMobile = document.getElementById('nav-mobile');

         menuBar.addEventListener('click', function() {
            navMobile.classList.toggle('show');
         });
      });
      document.addEventListener("DOMContentLoaded", function() {
         var modal = document.getElementById("change-password-modal");
         var btn = document.getElementById("change-password-btn");
         var closeModal = document.getElementById("close-modal");

         btn.onclick = function() {
            modal.style.display = "block";
         }

         closeModal.onclick = function() {
            modal.style.display = "none";
         }

         window.onclick = function(event) {
            if (event.target == modal) {
                  modal.style.display = "none";
            }
         }

         const toggleCurrentPassword = document.getElementById('toggle-current-password');
         const toggleNewPassword = document.getElementById('toggle-new-password');
         const toggleConfirmPassword = document.getElementById('toggle-confirm-password');

         const currentPasswordInput = document.getElementById('current-password');
         const newPasswordInput = document.getElementById('new-password');
         const confirmPasswordInput = document.getElementById('confirm-password');

         // Toggle mật khẩu hiển thị/ẩn
         togglePasswordVisibility(toggleCurrentPassword, currentPasswordInput);
         togglePasswordVisibility(toggleNewPassword, newPasswordInput);
         togglePasswordVisibility(toggleConfirmPassword, confirmPasswordInput);

         // Hàm để chuyển đổi giữa show và hide mật khẩu
         function togglePasswordVisibility(toggleButton, passwordInput) {
            toggleButton.addEventListener('click', function () {
               if (passwordInput.type === 'password') {
                     passwordInput.type = 'text';
                     toggleButton.innerHTML = '<i class="fa fa-eye-slash"></i>';  // Thay đổi icon thành "ẩn"
               } else {
                     passwordInput.type = 'password';
                     toggleButton.innerHTML = '<i class="fa fa-eye"></i>';  // Thay đổi icon thành "hiển thị"
               }
            });
         }
      });

    </script>
 </header>