<!-- resources/views/categories/index.blade.php -->
@extends('layouts/layoutmaster')

@section('title', 'Danh sách loại sản phẩm')

@section('content')
    <h2>Danh sách loại sản phẩm</h2>
    <button><a>Add</a></button>
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Mã sản phẩm</th>
                <th>Tên sản phẩm</th>
                <th>Mô tả</th>
                <th>Ngày tạo</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($danhsachloai as $ds)
                <tr>
                    <td>{{ $ds->id }}</td>
                    <td>{{ $ds->category_name }}</td>
                    <td>{{ $ds->category_description }}</td>
                    <td>{{ $ds->created_at->format('d/m/Y H:i:s') }}</td>
                    <td>Update</td>
                    <td>Delete</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
