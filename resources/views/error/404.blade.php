@extends('layouts/user/layoutmaster')
@section('content')
<head>
   <title> Không tìm thấy trang </title>
</head>
<div>
    <section class="page">
        <div class="container">
           <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-12">
                 <div class="content-page rte">
                    {{-- <h1>Oops!</h1> --}}
                    <div style="display: flex; justify-content: center; align-items: center;">
                        <img src="{{ asset('images/404.png') }}" 
                        style="max-width: 700px; width: 100%; height: auto;" alt="404 Error" />
                    </div>
                    <div style="text-align: center;">
                        <p>Trang bạn đang tìm kiếm không tồn tại hoặc bạn không có quyền truy cập.</p>
                        <a href="{{ url('/') }}" style="color: #e8b34f">Quay lại trang chủ</a>
                    </div>
                 </div>
              </div>
           </div>
        </div>
     </section>
     @include('page/user/list_brands');
</div>
@endsection