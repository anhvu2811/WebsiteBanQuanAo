@extends('layouts/user/layoutmaster')
@section('page_title', 'Đơn mua')
@section('content')
<head>
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <style>
      :root{
         --accent:#ff5722;
         --muted:#9aa0a6;
         --border:#e6e6e6;
         --bg:#f7f7f8;
         --white:#fff;
         --danger:#d9534f;
         --success:#2b9d6f;
         --radius:6px;
         font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial;
      }
      body{
         background:var(--bg);
         margin:0;
         color:#222;
         padding:28px;
         -webkit-font-smoothing:antialiased;
      }

      .container_order{
         max-width:1280px;
         margin:0 auto;
         background:var(--white);
         border-radius:8px;
         box-shadow:0 4px 18px rgba(0,0,0,0.06);
         overflow:hidden;
         margin-top:10px;
         min-height: 400px;
         margin-bottom: 40px;
      }

      /* Tabs */
      .tabs{
         display:flex;
         gap:18px;
         padding:18px 22px;
         border-bottom:1px solid var(--border);
         background:linear-gradient(180deg, rgba(255,255,255,0.85), rgba(255,255,255,0.95));
      }
      .tab{
         padding:10px 12px;
         cursor:pointer;
         color:var(--muted);
         border-bottom:3px solid transparent;
         font-weight:600;
      }
      .tab.active{
         color:var(--accent);
         border-bottom-color:var(--accent);
      }

      /* order group (shop) */
      .shop{
         padding:18px 22px;
         border-bottom:1px solid var(--border);
      }
      .shop-header{
         display:flex;
         align-items:center;
         gap:12px;
         margin-bottom:12px;
      }
      .shop-name{
         display:flex;
         align-items:center;
         gap:8px;
         font-weight:700;
         color:#333;
      }
      .btn-order-date{
         background:#ff4f3b;
         color:white;
         padding:6px 9px;
         border-radius:6px;
         font-size:13px;
         cursor:pointer;
         border:0;
      }

      /* product row */
      .product{
         display:flex;
         gap:16px;
         padding:16px 0;
         align-items:flex-start;
      }
      .thumb{
         width:84px;
         height:84px;
         background:#fafafa;
         border:1px solid #f0f0f0;
         border-radius:6px;
         display:flex;
         align-items:center;
         justify-content:center;
         overflow:hidden;
         flex-shrink:0;
      }
      .thumb img{width:100%;height:100%;object-fit:cover}

      .product-info{
         flex:1;
         min-width:0;
      }
      .product-title{
         font-weight:600;
         font-size:15px;
         margin-bottom:8px;
         color:#222;
      }
      .variant{
         color:var(--muted);
         font-size:13px;
         margin-bottom:6px;
      }
      .qty{color:var(--muted); font-size:13px;}

      /* right side: status + price + buttons */
      .product-right{
         width:360px;
         text-align:right;
         display:flex;
         flex-direction:column;
         gap:12px;
         align-items:flex-end;
      }

      .status{
         display:flex;
         gap:8px;
         align-items:center;
         font-size:13px;
         color:var(--muted);
      }
      .status .done{color:#2b9d6f;font-weight:600;}

      .price{
         font-size:20px;
         font-weight:700;
         color:var(--accent);
      }
      .old-price{
         color:#b0b0b0;
         text-decoration:line-through;
         margin-left:8px;
         font-weight:600;
         font-size:13px;
      }

      .actions{
         display:flex;
         gap:10px;
      }
      .btn-primary{
         background:var(--accent);
         color:white;
         border:0;
         padding:10px 16px;
         border-radius:6px;
         cursor:pointer;
         font-weight:700;
      }
      .btn-outline{
         background:transparent;
         border:1px solid #ddd;
         padding:10px 14px;
         border-radius:6px;
         color:#666;
         cursor:pointer;
      }

      /* group separators */
      .shop + .shop{ border-top:1px solid var(--border); }

      /* responsive */
      @media (max-width:700px){
         .product{flex-direction:column;align-items:flex-start}
         .product-right{width:100%;text-align:left;align-items:flex-start}
      }
   </style>
</head>
<div class="container_order" role="main" aria-label="Danh sách đơn hàng">
  <div class="tabs" role="tablist">
    <div class="tab active" data-tab="all">Tất cả</div>
    <div class="tab" data-tab="pending">Chờ xác nhận</div>
    <div class="tab" data-tab="shipping">Vận chuyển</div>
    <div class="tab" data-tab="delivered">Hoàn thành</div>
    <div class="tab" data-tab="cancel">Đã hủy</div>
    <div class="tab" data-tab="refund">Trả hàng/Hoàn tiền</div>
  </div>

  <!-- Shop All -->
   @if(count($orderAll))
      @foreach($orderAll as $order)
         <div class="shop" data-status="all">
            <div class="shop-header">
               <div class="shop-name">
               <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="color:#555"><path d="M3 6h18v2H3zM4 22h16v-2H4zM6 10h12v8H6z"/></svg>
               <span style="margin-top: 5px;">Đơn hàng #{{$order->id}}</span>
               </div>
               <div style="flex:1"></div>
               @php
                  $date = \Carbon\Carbon::parse($order->order_date);
               @endphp
               <button class="btn-order-date">
                  {{ $date->format('d/m/Y') }} <br>
                  {{ $date->format('H:i:s') }}
               </button>
            </div>
            @foreach($order->orderItem as $index => $item)
               <div class="product" aria-label="{{ $item->product->name }}">
                  <div class="thumb">
                     <?php 
                        $image = $item->product->images->first();
                        $imageUrl = $image ? asset('storage/'.$image->image_url) : asset('images/no-image.png');
                     ?>
                     <img src="{{ $imageUrl }}" alt="thumbnail" /></div>
                     <div class="product-info">
                     <div class="product-title">{{ $item->product->name }}</div>
                     @php
                        $sizes = $item->product->productSize->pluck('size.name')->unique()->toArray();
                        $listSize = implode(', ', $sizes);
                     @endphp
                     <div class="variant">Phân loại hàng: Size {{ $item->size->name }}</div>
                     <div class="qty">x{{ $item->quantity }}</div>
                  </div>
                  @if(isset($order->orderItem) && $index == 0)
                     <div class="product-right">
                        <div class="status">
                           <?php
                              $delivery = '';
                              $status = '';
                              if($order->payment_status == 'Pending') {
                                 $status = 'CHỜ XÁC NHẬN';
                                 $color = '#e8b34f';
                              } else if($order->payment_status == 'Completed') {
                                 $status = 'HOÀN THÀNH';
                                 $delivery = 'Giao hàng thành công';
                                 $color = '#ff6b6b';
                              } else if($order->payment_status == 'Shipping') {
                                 $status = 'ĐANG GIAO HÀNG';
                                 $delivery = 'Đơn hàng đang đến với bạn';
                                 $color = '#e8b34f';
                              } else {
                                 $status = 'ĐÃ HỦY';
                                 $color = '#ff6b6b';
                              }
                           ?>
                           <span class="done">{{ $delivery ?? ''}}</span>
                           <span style="color: {{$color}};font-weight:700;margin-left:2px">{{ $status }}</span>
                        </div>

                        <div>
                           {{-- <span class="old-price">{{ number_format($order->total_price, 0, ',', '.') }}₫</span> --}}
                           <div class="price">{{ number_format($order->total_price, 0, ',', '.') }}₫</div>
                        </div>

                        <div class="actions">
                           @if($order->payment_status == 'Completed' || $order->payment_status == 'Canceled')
                              <button class="btn-primary" style="background-color:#ff4f3b">Mua Lại</button>
                           @elseif($order->payment_status == 'Pending')
                              <button class="btn-primary" style="background-color:#CC0000;">Hủy đơn</button>
                           @endif
                           <button class="btn-outline">Liên Hệ Người Bán</button>
                        </div>
                     </div>
                  @endif
               </div>
            @endforeach
         </div>
      @endforeach
   @endif

   <!-- Shop Pending -->
   @if(count($orderPending))
      @foreach($orderPending as $order)
            <div class="shop" data-status="pending">
               <div class="shop-header">
                  <div class="shop-name">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="color:#555"><path d="M3 6h18v2H3zM4 22h16v-2H4zM6 10h12v8H6z"/></svg>
                  <span style="margin-top: 5px;">Đơn hàng #{{$order->id}}</span>
                  </div>
                  <div style="flex:1"></div>
                  @php
                     $date = \Carbon\Carbon::parse($order->order_date);
                  @endphp
                  <button class="btn-order-date">
                     {{ $date->format('d/m/Y') }} <br>
                     {{ $date->format('H:i:s') }}
                  </button>
               </div>
               @foreach($order->orderItem as $index => $item)
                  <div class="product" aria-label="{{ $item->product->name }}">
                     <div class="thumb">
                        <?php 
                           $image = $item->product->images->first();
                           $imageUrl = $image ? asset('storage/'.$image->image_url) : asset('images/no-image.png');
                        ?>
                        <img src="{{ $imageUrl }}" alt="thumbnail" /></div>
                        <div class="product-info">
                        <div class="product-title">{{ $item->product->name }}</div>
                        @php
                           $sizes = $item->product->productSize->pluck('size.name')->unique()->toArray();
                           $listSize = implode(', ', $sizes);
                        @endphp
                        <div class="variant">Phân loại hàng: Size {{ $item->size->name }}</div>
                        <div class="qty">x{{ $item->quantity }}</div>
                     </div>
                     @if(isset($order->orderItem) && $index == 0)
                        <div class="product-right">
                           <div class="status">
                              <?php
                                 $delivery = '';
                                 $status = '';
                                 if($order->payment_status == 'Pending') {
                                    $status = 'CHỜ XÁC NHẬN';
                                    $color = '#e8b34f';
                                 } else if($order->payment_status == 'Completed') {
                                    $status = 'HOÀN THÀNH';
                                    $delivery = 'Giao hàng thành công';
                                    $color = '#ff6b6b';
                                 }  else if($order->payment_status == 'Shipping') {
                                    $status = 'ĐANG GIAO HÀNG';
                                    $delivery = 'Đơn hàng đang đến với bạn';
                                    $color = '#e8b34f';
                                 } else {
                                    $status = 'ĐÃ HỦY';
                                    $color = '#ff6b6b';
                                 }
                              ?>
                              <span class="done">{{ $delivery ?? ''}}</span>
                              <span style="color: {{$color}};font-weight:700;margin-left:2px">{{ $status }}</span>
                           </div>

                           <div>
                              {{-- <span class="old-price">{{ number_format($order->total_price, 0, ',', '.') }}₫</span> --}}
                              <div class="price">{{ number_format($order->total_price, 0, ',', '.') }}₫</div>
                           </div>

                           <div class="actions">
                              @if($order->payment_status == 'Completed' || $order->payment_status == 'Canceled')
                                 <button class="btn-primary" style="background-color:#ff4f3b">Mua Lại</button>
                              @elseif($order->payment_status == 'Pending')
                                 <button class="btn-primary" style="background-color:#CC0000;">Hủy đơn</button>
                              @endif
                              <button class="btn-outline">Liên Hệ Người Bán</button>
                           </div>
                        </div>
                     @endif
                  </div>
               @endforeach
         </div>
      @endforeach
   @endif
   
   <!-- Shop Shipping -->
   @if(count($orderShipping))
      @foreach($orderShipping as $order)
         <div class="shop" data-status="shipping">
            <div class="shop-header">
               <div class="shop-name">
               <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="color:#555"><path d="M3 6h18v2H3zM4 22h16v-2H4zM6 10h12v8H6z"/></svg>
               <span style="margin-top: 5px;">Đơn hàng #{{$order->id}}</span>
               </div>
               <div style="flex:1"></div>
               @php
                  $date = \Carbon\Carbon::parse($order->order_date);
               @endphp
               <button class="btn-order-date">
                  {{ $date->format('d/m/Y') }} <br>
                  {{ $date->format('H:i:s') }}
               </button>
            </div>
            @foreach($order->orderItem as $index => $item)
               <div class="product" aria-label="{{ $item->product->name }}">
                  <div class="thumb">
                     <?php 
                        $image = $item->product->images->first();
                        $imageUrl = $image ? asset('storage/'.$image->image_url) : asset('images/no-image.png');
                     ?>
                     <img src="{{ $imageUrl }}" alt="thumbnail" /></div>
                     <div class="product-info">
                     <div class="product-title">{{ $item->product->name }}</div>
                     @php
                        $sizes = $item->product->productSize->pluck('size.name')->unique()->toArray();
                        $listSize = implode(', ', $sizes);
                     @endphp
                     <div class="variant">Phân loại hàng: Size {{ $item->size->name }}</div>
                     <div class="qty">x{{ $item->quantity }}</div>
                  </div>
                  @if(isset($order->orderItem) && $index == 0)
                     <div class="product-right">
                        <div class="status">
                           <?php
                              $delivery = '';
                              $status = '';
                              if($order->payment_status == 'Pending') {
                                 $status = 'CHỜ XÁC NHẬN';
                                 $color = '#e8b34f';
                              } else if($order->payment_status == 'Completed') {
                                 $status = 'HOÀN THÀNH';
                                 $delivery = 'Giao hàng thành công';
                                 $color = '#ff6b6b';
                              }  else if($order->payment_status == 'Shipping') {
                                 $status = 'ĐANG GIAO HÀNG';
                                 $delivery = 'Đơn hàng đang đến với bạn';
                                 $color = '#e8b34f';
                              } else {
                                 $status = 'ĐÃ HỦY';
                                 $color = '#ff6b6b';
                              }
                           ?>
                           <span class="done">{{ $delivery ?? ''}}</span>
                           <span style="color: {{$color}};font-weight:700;margin-left:2px">{{ $status }}</span>
                        </div>

                        <div>
                           {{-- <span class="old-price">{{ number_format($order->total_price, 0, ',', '.') }}₫</span> --}}
                           <div class="price">{{ number_format($order->total_price, 0, ',', '.') }}₫</div>
                        </div>

                        <div class="actions">
                           @if($order->payment_status == 'Completed' || $order->payment_status == 'Canceled')
                              <button class="btn-primary" style="background-color:#ff4f3b">Mua Lại</button>
                           @endif
                           <button class="btn-outline">Liên Hệ Người Bán</button>
                        </div>
                     </div>
                  @endif
               </div>
            @endforeach
         </div>
      @endforeach
   @endif

   <!-- Shop Delivered -->
   @if(count($orderDelivered))
      @foreach($orderDelivered as $order)
         <div class="shop" data-status="delivered">
            <div class="shop-header">
               <div class="shop-name">
               <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="color:#555"><path d="M3 6h18v2H3zM4 22h16v-2H4zM6 10h12v8H6z"/></svg>
               <span style="margin-top: 5px;">Đơn hàng #{{$order->id}}</span>
               </div>
               <div style="flex:1"></div>
               @php
                  $date = \Carbon\Carbon::parse($order->order_date);
               @endphp
               <button class="btn-order-date">
                  {{ $date->format('d/m/Y') }} <br>
                  {{ $date->format('H:i:s') }}
               </button>
            </div>
            @foreach($order->orderItem as $index => $item)
               <div class="product" aria-label="{{ $item->product->name }}">
                  <div class="thumb">
                     <?php 
                        $image = $item->product->images->first();
                        $imageUrl = $image ? asset('storage/'.$image->image_url) : asset('images/no-image.png');
                     ?>
                     <img src="{{ $imageUrl }}" alt="thumbnail" /></div>
                     <div class="product-info">
                     <div class="product-title">{{ $item->product->name }}</div>
                     @php
                        $sizes = $item->product->productSize->pluck('size.name')->unique()->toArray();
                        $listSize = implode(', ', $sizes);
                     @endphp
                     <div class="variant">Phân loại hàng: Size {{ $item->size->name }}</div>
                     <div class="qty">x{{ $item->quantity }}</div>
                  </div>
                  @if(isset($order->orderItem) && $index == 0)
                     <div class="product-right">
                        <div class="status">
                           <?php
                              $delivery = '';
                              $status = '';
                              if($order->payment_status == 'Pending') {
                                 $status = 'CHỜ XÁC NHẬN';
                                 $color = '#e8b34f';
                              } else if($order->payment_status == 'Completed') {
                                 $status = 'HOÀN THÀNH';
                                 $delivery = 'Giao hàng thành công';
                                 $color = '#ff6b6b';
                              }  else if($order->payment_status == 'Shipping') {
                                 $status = 'ĐANG GIAO HÀNG';
                                 $delivery = 'Đơn hàng đang đến với bạn';
                                 $color = '#e8b34f';
                              } else {
                                 $status = 'ĐÃ HỦY';
                                 $color = '#ff6b6b';
                              }
                           ?>
                           <span class="done">{{ $delivery ?? ''}}</span>
                           <span style="color: {{$color}};font-weight:700;margin-left:2px">{{ $status }}</span>
                        </div>

                        <div>
                           {{-- <span class="old-price">{{ number_format($order->total_price, 0, ',', '.') }}₫</span> --}}
                           <div class="price">{{ number_format($order->total_price, 0, ',', '.') }}₫</div>
                        </div>

                        <div class="actions">
                           @if($order->payment_status == 'Completed' || $order->payment_status == 'Canceled')
                              <button class="btn-primary" style="background-color:#ff4f3b">Mua Lại</button>
                           @endif
                           <button class="btn-outline">Liên Hệ Người Bán</button>
                        </div>
                     </div>
                  @endif
               </div>
            @endforeach
         </div>
      @endforeach
   @endif

   <!-- Shop Canceled -->
   @if(count($orderCanceled))
      @foreach($orderCanceled as $order)
         <div class="shop" data-status="cancel">
            <div class="shop-header">
               <div class="shop-name">
               <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="color:#555"><path d="M3 6h18v2H3zM4 22h16v-2H4zM6 10h12v8H6z"/></svg>
               <span style="margin-top: 5px;">Đơn hàng #{{$order->id}}</span>
               </div>
               <div style="flex:1"></div>
               @php
                  $date = \Carbon\Carbon::parse($order->order_date);
               @endphp
               <button class="btn-order-date">
                  {{ $date->format('d/m/Y') }} <br>
                  {{ $date->format('H:i:s') }}
               </button>
            </div>
            @foreach($order->orderItem as $index => $item)
               <div class="product" aria-label="{{ $item->product->name }}">
                  <div class="thumb">
                     <?php 
                        $image = $item->product->images->first();
                        $imageUrl = $image ? asset('storage/'.$image->image_url) : asset('images/no-image.png');
                     ?>
                     <img src="{{ $imageUrl }}" alt="thumbnail" /></div>
                     <div class="product-info">
                     <div class="product-title">{{ $item->product->name }}</div>
                     @php
                        $sizes = $item->product->productSize->pluck('size.name')->unique()->toArray();
                        $listSize = implode(', ', $sizes);
                     @endphp
                     <div class="variant">Phân loại hàng: Size {{ $item->size->name }}</div>
                     <div class="qty">x{{ $item->quantity }}</div>
                  </div>
                  @if(isset($order->orderItem) && $index == 0)
                     <div class="product-right">
                        <div class="status">
                           <?php
                              $delivery = '';
                              $status = '';
                              if($order->payment_status == 'Pending') {
                                 $status = 'CHỜ XÁC NHẬN';
                                 $color = '#e8b34f';
                              } else if($order->payment_status == 'Completed') {
                                 $status = 'HOÀN THÀNH';
                                 $delivery = 'Giao hàng thành công';
                                 $color = '#ff6b6b';
                              }  else if($order->payment_status == 'Shipping') {
                                 $status = 'ĐANG GIAO HÀNG';
                                 $delivery = 'Đơn hàng đang đến với bạn';
                                 $color = '#e8b34f';
                              } else {
                                 $status = 'ĐÃ HỦY';
                                 $color = '#ff6b6b';
                              }
                           ?>
                           <span class="done">{{ $delivery ?? ''}}</span>
                           <span style="color: {{$color}};font-weight:700;margin-left:2px">{{ $status }}</span>
                        </div>

                        <div>
                           {{-- <span class="old-price">{{ number_format($order->total_price, 0, ',', '.') }}₫</span> --}}
                           <div class="price">{{ number_format($order->total_price, 0, ',', '.') }}₫</div>
                        </div>

                        <div class="actions">
                           @if($order->payment_status == 'Completed' || $order->payment_status == 'Canceled')
                              <button class="btn-primary" style="background-color:#ff4f3b">Mua Lại</button>
                           @endif
                           <button class="btn-outline">Liên Hệ Người Bán</button>
                        </div>
                     </div>
                  @endif
               </div>
            @endforeach
         </div>
      @endforeach
   @endif
</div>
@endsection
@section('scripts')
<script>
   const container = document.querySelector('.container_order');

   // Tạo khung hiển thị trống
   const emptyBox = document.createElement('div');
   emptyBox.style.display = 'none';
   emptyBox.style.display = 'flex';
   emptyBox.style.flexDirection = 'column';
   emptyBox.style.alignItems = 'center';
   emptyBox.style.justifyContent = 'center';
   emptyBox.style.padding = '40px';
   emptyBox.style.color = '#555';
   emptyBox.style.height = '60vh';

   // Thêm hình ảnh
   const img = document.createElement('img');
   img.src = "{{ asset('images/order-not-found.png') }}";
   img.alt = 'Không có đơn hàng';
   img.style.width = '100px';
   img.style.marginBottom = '10px';
   img.style.display = 'none';
   emptyBox.appendChild(img);

   // Thêm đoạn văn
   const emptyMsg = document.createElement('p');
   emptyMsg.textContent = 'Chưa có đơn hàng';
   emptyMsg.style.textAlign = 'center';
   emptyMsg.style.fontSize = '16px';
   emptyMsg.style.display = 'none';
   emptyBox.appendChild(emptyMsg);

   // Thêm khung này vào container
   container.appendChild(emptyBox);


   // Xác định logic lọc từng tab
   const tabFilters = {
      all: ['all'],
      delivered: ['delivered'],
      pending: ['pending'],
      shipping: ['shipping'],
      cancel: ['cancel']
   };

   function updateVisibility() {
      const visible = Array.from(document.querySelectorAll('.shop'))
         .some(s => s.style.display !== 'none');
      img.style.display = visible ? 'none' : 'block';
      emptyMsg.style.display = visible ? 'none' : 'block';
   }

   document.querySelectorAll('.tab').forEach(tab => {
      tab.addEventListener('click', () => {
         document.querySelectorAll('.tab').forEach(x => x.classList.remove('active'));
         tab.classList.add('active');

         const key = tab.dataset.tab;
         const acceptedStatuses = tabFilters[key] || [];

         document.querySelectorAll('.shop').forEach(shop => {
            const status = shop.dataset.status;
            shop.style.display = acceptedStatuses.includes(status) ? 'block' : 'none';
         });

         updateVisibility();
      });
   });
   document.querySelector('.tab.active')?.click();

</script>
@endsection
