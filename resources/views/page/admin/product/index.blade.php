@extends('layouts.admin.app')
@section('title', 'Products')
@section('header-title', 'Products')
@section('content')
    <a href="/product/create" class="add-btn">✚ Thêm mới</a>
    <form method="GET" action="{{ route('product.index') }}">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 20px; margin-top: 20px; margin-bottom: 20px;">
            <div style="flex: 0 0 auto; display: flex; align-items: center; gap: 5px;">
                <label for="perPage">Hiển thị</label>
                <select name="perPage" id="perPage" onchange="this.form.submit()">
                    <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ request('perPage') == 20 ? 'selected' : '' }}>20</option>
                    <option value="30" {{ request('perPage') == 30 ? 'selected' : '' }}>30</option>
                </select>
            </div>

            <div style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 5px;">
                <label>Tìm kiếm</label>
                <div style="position: relative; max-width: 500px; width: 100%;">
                    <input type="text" name="search" id="search"
                        value="{{ request('search') }}"
                        placeholder="Tìm sản phẩm..."
                        style="width: 100%; padding-right: 35px; height: 36px;" />
                    
                    <button type="submit" style="position: absolute; top: 50%; right: 8px; transform: translateY(-50%); background: transparent; border: none; cursor: pointer; color: #888;">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </div>

            <div style="flex: 0 0 auto; display: flex; align-items: center; gap: 5px; justify-content: flex-end;">
                <select name="sortPrice" id="sortPrice" onchange="this.form.submit()" style="padding: 10px;">
                    <option value="">-- Sắp xếp theo giá --</option>
                    <option value="asc" {{ request('sortPrice') == 'asc' ? 'selected' : '' }}>Giá tăng dần</option>
                    <option value="desc" {{ request('sortPrice') == 'desc' ? 'selected' : '' }}>Giá giảm dần</option>
                </select>

                <select name="sortName" id="sortName" onchange="this.form.submit()" style="padding: 10px;">
                    <option value="">-- Sắp xếp theo tên --</option>
                    <option value="asc" {{ request('sortName') == 'asc' ? 'selected' : '' }}>Từ A đến Z</option>
                    <option value="desc" {{ request('sortName') == 'desc' ? 'selected' : '' }}>Từ Z đến A</option>
                </select>
            </div>
            
        </div>
    </form>

    <!-- Hiển thị danh sách sản phẩm -->
    <div id="productList">
        @include('page.admin.product.product_table', ['products' => $products])
    </div>

    <!-- Hiển thị phân trang -->
    <div id="pagination">
        @include('page.admin.product.pagination', ['products' => $products])
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('profileMenu').addEventListener('click', function() {
        const dropdown = document.getElementById('dropdownMenu');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });

    window.onclick = function(event) {
        if (!event.target.matches('.profile')) {
            const dropdowns = document.getElementsByClassName("dropdown-content");
            for (let i = 0; i < dropdowns.length; i++) {
                const openDropdown = dropdowns[i];
                if (openDropdown.style.display === 'block') {
                    openDropdown.style.display = 'none';
                }
            }
        }
    }
</script>
@endpush