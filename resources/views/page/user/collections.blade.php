@extends('layouts/user/layoutmaster')
@section('page_title', 'Tất Cả Sản Phẩm')
@section('content')
<style>
   .zoom-hover {
      transition: transform 0.3s ease;
   }

   .zoom-hover:hover {
      transform: scale(1.10);
   }
   @media (max-width: 991px) {
      .left-content {
         display: none !important;
      }
   }
   /* Container skeleton */
   .skeleton { 
      background: #f3f3f3; 
      border-radius: 5px; 
      overflow: hidden; 
      position: relative; 
   }

   /* Shimmer animation */
   .skeleton::after {
      content: '';
      position: absolute;
      top: 0; left: -150px;
      width: 150px; height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
      animation: shimmer 1.5s infinite;
   }
   @keyframes shimmer {
      0% { transform: translateX(0); }
      100% { transform: translateX(300px); }
   }

   /* Fake image */
   .skeleton-img {
      width: 100%;
      height: 240px;
      background: #ddd;
   }

   /* Fake text lines */
   .skeleton-line {
      height: 16px;
      background: #ddd;
      margin: 8px 0;
      border-radius: 3px;
   }
   .skeleton-title { width: 70%; }
   .skeleton-price { width: 40%; }

</style>
<div>
   <section class="bread-crumb">
      <div class="container">
         <div class="row">
            <div class="col-xs-12 a-left">
               <ul class="breadcrumb">
                  <li class="home">
                     <a href="/"><span>Trang chủ</span></a>						
                     <span class="br-line">|</span>
                  </li>
                  <li><strong><span> Tất cả sản phẩm</span></strong></li>
               </ul>
            </div>
         </div>
      </div>
   </section>
   <div class="container border-bottom-col">
      <div class="row">
         <div id="result-search-text"></div>
      </div>
      <div class="row">
         <section class="main_container collection col-lg-10 col-lg-push-2">
            <div class="category-products products">
               <div class="sortPagiBar">
                  <div class="row">
                     <div class="col-xs-5 col-sm-6 fix-xss-12">
                        <h1 class=" title-head margin-top-0 margin-bottom-0">Tất cả sản phẩm</h1>
                     </div>
                     <div class="col-xs-7 col-sm-6 text-xs-left text-sm-right fix-xss-12">
                        <div id="sort-by">
                           <ul>
                              <li>
                                 <span class="fixtt">Thứ tự</span>
                                 <ul id="sort-filter">
                                    <li><a href="">Mặc định</a></li>
                                    <li><a href="">A → Z</a></li>
                                    <li><a href="">Z → A</a></li>
                                    <li><a href="">Giá tăng dần</a></li>
                                    <li><a href="">Giá giảm dần</a></li>
                                    <li><a href="">Hàng mới nhất</a></li>
                                    <li><a href="">Hàng cũ nhất</a></li>
                                 </ul>
                              </li>
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
               @if(session()->has('success'))
                  <div class="alert alert-success">
                     {{ session()->get('success') }}
                  </div>
               @endif
               <section class="products-view products-view-grid">
                     <p style="text-align: center; font-style: italic; color: gray; display: none;" id="no-product-found">No products found</p>
                     <div class="row" id="cart-product"></div>
                     <div class="text-xs-center">
                     </div>
               </section>
            </div>
         </section>
         <aside class="sidebar left left-content col-lg-2 col-lg-pull-10">
            <aside class="aside-item sidebar-category collection-category">
               <div class="aside-title">
                  <h2 class="title-head margin-top-0"><span>Sản phẩm Nam</span></h2>
               </div>
               <div class="aside-content">
                  <nav class="nav-category navbar-toggleable-md">
                     <ul class="nav navbar-pills" id="male-cate"></ul>
                  </nav>
               </div>
            </aside>
            <aside class="aside-item sidebar-category collection-category">
               <div class="aside-title">
                  <h2 class="title-head margin-top-0"><span>Sản phẩm Nữ</span></h2>
               </div>
               <div class="aside-content">
                  <nav class="nav-category navbar-toggleable-md">
                     <ul class="nav navbar-pills" id="famale-cate"></ul>
                  </nav>
               </div>
            </aside>
         </aside>
      </div>
   </div>
     @include('page/user/list_brands')
</div>
@endsection

@section('scripts')
   <script>
      $(document).ready(function () {
         let page = 1;
         let loading = false;
         let hasMore = true;
         let cate_id, gender, sortBy, search = null;

         getProducts();
         getCategories();

         $(window).on('scroll', function() {
            if($(window).scrollTop() + $(window).height() >= $(document).height() - 200) {
               getProducts();
            }
         });

         //render product
         function getProducts() {
            const getProductUrl = "{{ route('product.get-products') }}?page=" + page + (search ? "&search=" + encodeURIComponent(search) : "") +
                                                                                       (cate_id ? "&category=" + cate_id : "") + 
                                                                                       (gender ? "&gender=" + gender : "") + 
                                                                                       (sortBy ? "&sort=" + sortBy : "");

            if(loading || !hasMore) return;  
            loading = true;
            showSkeleton();
            setTimeout(() => {
               $.ajax({
                  url: getProductUrl,
                  method: 'GET',
                  success: function(response) {
                     const products = Array.isArray(response.data) ? response.data : response.data.data;
                     const totalProducts = response.total;
                     checkExistsProduct(products.length);
                     if(response.success) {
                        let html = '';
                        hideSkeleton(); 
                        if(search) {
                           showResultSearchText(totalProducts, search)
                        }
                        products.forEach(function(item) {
                           html += renderProduct(item)
                        });
                        $('#cart-product').append(html);

                        hasMore = !!response.data?.next_page_url;
                        page++;
                        loading = false;
                     }
                  },
                  error: function(xhr) {
                     alert(xhr.responseJSON.error);
                  }
               })
            }, 600);
         }

         function showSkeleton(count = 10) {
            let skeletonHtml = '';
            for (let i = 0; i < count; i++) {
               skeletonHtml += `
               <div class="col-xs-6 col-sm-4 col-md-4 col-lg-5-fix">
                     <div class="product-box skeleton">
                        <div class="product-thumbnail skeleton-img"></div>
                        <div class="product-info">
                           <div class="skeleton-line skeleton-title"></div>
                           <div class="skeleton-line skeleton-price"></div>
                        </div>
                     </div>
               </div>`;
            }
            $('#cart-product').append(skeletonHtml);
         }

         function hideSkeleton() {
            $('.skeleton').remove();
         }

         function renderProduct(item) {
            let html = '';
            const productUrl = `/collections/${item.id}`;
            const hasDiscount = item.discount !== null;
            const imageUrl = (item.images?.length > 0) ? `/storage/${item.images[0].image_url}` : '/images/no-image.png';
            html += `<div class="col-xs-6 col-sm-4 col-md-4 col-lg-5-fix">
                        <div class="product-box a-center">
                           <div class="product-thumbnail">
                              ${hasDiscount ? 
                                 `<div class="sale-flash"> 
                                    ${item.discount.discount_level}% 
                                 </div>` : ''
                              }
                              <a href="${productUrl}" title="${item.name}">
                                 <picture>
                                    <source media="(min-width: 1200px)" srcset="${imageUrl}">
                                    <source media="(min-width: 992px)" srcset="${imageUrl}">
                                    <source media="(min-width: 569px)" srcset="${imageUrl}">
                                    <source media="(max-width: 480px)" srcset="${imageUrl}">
                                    <source media="(max-width: 375px)" srcset="${imageUrl}">

                                    <img width="240" height="240" data-src="${imageUrl}" alt="${item.name}" class="lazyload img-responsive center-block zoom-hover">
                                 </picture>
                              </a>
                           </div>
                           <div class="product-info">
                              <h3 class="product-name"><a href="${productUrl}" title="${item.name}">${item.name}</a></h3>
                              <div class="price-box clearfix">
                                 ${hasDiscount ? `
                                    <div class="special-price inline-block">
                                       <span class="price product-price"> ${formatCurrency(item.discount.new_price, 0, ',', '.')}</span>
                                    </div>
                                    <div class="old-price inline-block">															 
                                       <span class="price product-price-old">
                                          ${formatCurrency(item.price, 0, ',', '.')}
                                       </span>
                                    </div>`
                                 : `
                                    <div class="special-price inline-block">
                                       <span class="price product-price">${formatCurrency(item.price, 0, ',', '.')}</span>
                                    </div>
                                    `
                                 }
                              </div>
                           </div>
                        </div>
                     </div>`;
               return html;
         }

         function checkExistsProduct(data) {
            if(data <= 0) {
               $('no-product-found').show();
            } else {
               $('no-product-found').hide();
            }
         }

         function formatCurrency(value) {
            return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
         }

          //api get categories
         function getCategories() {
            const getCateUrl = "{{ route('product.getCategories') }}"
            $.ajax({
               url: getCateUrl,
               method: 'GET',
               success: function(response) {
                  if(response.success) {
                     const maleCate = response.data.maleCate;
                     const famaleCate = response.data.famaleCate;

                     let male_html = '';
                     let famale_html = '';
                     maleCate.forEach(function(category) {
                        const maleCateUrl = `/collections/male/${category.name}`;
                        male_html += `
                           <li class="nav-item">
                              <i class="fa fa-caret-right"></i>
                                    <a class="nav-link" href="" style="text-transform: uppercase;" data-id="${category.id}" data-gender="1">
                                       ${category.name} <small style="color: #808080">( ${category.product_count})</small>
                                 </a>
                           </li>
                           `;
                     });
                     famaleCate.forEach(function(category) {
                        const famaleCateUrl = `/collections/famale/${category.name}`;
                        famale_html += `
                           <li class="nav-item">
                              <i class="fa fa-caret-right"></i>
                                    <a class="nav-link" href="" style="text-transform: uppercase;" data-id="${category.id}" data-gender="2">
                                       ${category.name} <small style="color: #808080">( ${category.product_count})</small>
                                 </a>
                           </li>
                           `;
                     });
                     $('#male-cate').html(male_html);
                     $('#famale-cate').html(famale_html);
                  }
               },
               error: function(xhr) {
                  alert(xhr.responseJSON.error);
               }
            })
         }

         function showResultSearchText(totalProducts, keyword) {
            let html_result = '';
            if(totalProducts) {
               html_result += `<p style="text-align:center; font-size: 20px; font-style: italic;">
                                 Tìm thấy <b style="color:#e8b34f; font-size: 22px;">${totalProducts}</b> sản phẩm với từ khóa 
                                 <b style="color:#e8b34f;">"${keyword}"</b>
                              </p>`;
            } else {
               html_result = `<p style="text-align:center; font-size: 20px; font-style: italic;">
                                 Không tìm thấy sản phẩm nào với từ khóa 
                                 <b style="color:#e8b34f;">"${keyword}"</b>.
                              </p>`;
            }
            $('#result-search-text').html('');
            $('#result-search-text').html(html_result);
         }

         //search
         $('.search_form').on('submit', function(e) {
            e.preventDefault();  
            search  = $('#search-input').val().trim();
            if(search.length === 0) return;
            cate_id = null;
            gender = null;
            sortBy = null;
            page = 1;
            hasMore = true;
            $('#cart-product').html('');
            showSkeleton();
            getProducts();
         })

         //filter category
         $('#male-cate, #famale-cate').on('click', 'li a', function(e) {
            e.preventDefault();
            cate_id = $(this).data('id');
            gender = $(this).data('gender');
            search = null;
            sortBy = null;
            page = 1;
            hasMore = true;
            $('#result-search-text').html('');
            $('#cart-product').html('');
            getProducts();
         });

         //sort by
         $('#sort-filter').on('click', 'li a', function(e) {
            e.preventDefault();
            const filterText = $(this).text().trim();
            switch(filterText) {
               case 'A → Z': sortBy = 'name_asc'; break;
               case 'Z → A': sortBy = 'name_desc'; break;
               case 'Giá tăng dần': sortBy = 'price_asc'; break;
               case 'Giá giảm dần': sortBy = 'price_desc'; break;
               case 'Hàng mới nhất': sortBy = 'newest'; break;
               case 'Hàng cũ nhất': sortBy = 'oldest'; break;
               default: sortBy = 'default'; break;
            }

            page = 1;
            hasMore = true;
            $('#cart-product').html('');
            getProducts();
         });
      })
   </script>
@endsection