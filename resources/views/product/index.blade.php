<!-- resources/views/categories/index.blade.php -->
@extends('layouts/layoutmaster')

@section('title', 'Danh sách sản phẩm')

@section('content')
    <h2>Danh sách sản phẩm</h2>
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Mã sản phẩm</th>
                <th>Tên sản phẩm</th>
                <th>Mô tả</th>
                <th>Ngày tạo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($danhsachsanpham as $ds)
                <tr>
                    <td>{{ $ds->id }}</td>
                    <td>{{ $ds->product_name }}</td>
                    <td>{{ $ds->product_description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
