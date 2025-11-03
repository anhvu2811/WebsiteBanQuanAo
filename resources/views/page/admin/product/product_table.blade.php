<?php 
    use Carbon\Carbon;
?>
<style>
    /* Lớp overlay nền tối */
    .popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Nền tối với độ trong suốt */
        z-index: 999; /* Đảm bảo lớp này nằm dưới popup */
        display: none; /* Mặc định ẩn */
    }

    /* Style cho popup */
    .popup {
        position: fixed;
        top: 20%;
        left: 50%;
        transform: translate(-50%, -50%); /* Căn giữa */
        width: 50%; /* Đặt chiều rộng của popup */
        max-width: 900px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        z-index: 1000; /* Đảm bảo popup ở trên overlay */
        display: none;
    }

    /* Style cho phần popup content */
    .popup-content {
        position: relative;
    }

    /* Nút đóng (×) */
    #close-popup {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 20px;
        cursor: pointer;
    }

    /* Đảm bảo popup hiển thị khi cần */
    .popup.show {
        display: block; /* Hiển thị popup khi thêm class "show" */
    }

    /* Đảm bảo overlay hiển thị khi cần */
    .popup-overlay.show {
        display: block; /* Hiển thị overlay khi thêm class "show" */
    }

    .title {
        text-align: center;
    }


    /* confirm model delete */
    .model {
        display: none;
        position: fixed;
        z-index: 1000;
        inset: 0;
        background: rgba(0,0,0,0.5);
        justify-content: center;
        align-items: center;
        width: 100vw;
        height: 100vh;
    }
    .model.show {
        display: flex;
    }
    .model-content {
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        text-align: center;
        width: 320px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        animation: fadeIn 0.3s ease;
    }
    .model-actions {
        margin-top: 20px;
        display: flex;
        justify-content: space-around;
    }
    .btn-cancel, .btn-confirm {
        padding: 8px 18px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }
    .btn-cancel {
        background: #ccc;
    }
    .btn-confirm {
        background: #e74c3c;
        color: white;
    }
    .btn-delete {
        background: #e74c3c;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 6px 12px;
        margin-left: 10px;
        cursor: pointer;
    }
    .btn-update {
        background: #3498db;
        color: white;
        border-radius: 6px;
        padding: 6px 12px;
        text-decoration: none;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }

    .btn {
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        font-size: 14px;
        line-height: 1;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid transparent;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 140ms ease;
        user-select: none;
    }

    .btn-primary {
        background: linear-gradient(180deg, #3aa0ff, #2b85d6);
        color: #fff;
        box-shadow: 0 6px 18px rgba(43,133,214,0.18);
    }

    .btn-danger {
        background: linear-gradient(180deg, #ff6b6b, #e04646);
        color: #fff;
        box-shadow: 0 6px 18px rgba(224,70,70,0.18);
        border-radius: 8px;
    }

    .btn-ghost {
        background: transparent;
        color: #2b6cb0;
        border: 1px dashed transparent;
        padding: 6px 8px;
    }

    .btn-sm { padding: 6px 10px; font-size: 13px; border-radius: 6px; }

    .row { display:flex; gap:10px; align-items:center; }

    @media (max-width: 480px) {
    .row { flex-direction: column; align-items: stretch; }
    .btn { width: 100%; justify-content: center; }
    }

    .btn svg { width: 16px; height: 16px; display:inline-block; }
    .model-actions { display:flex; gap:12px; justify-content:center; margin-top:18px; }

    .show-list-sizes-btn {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 10px 0;
        border: solid thin #339933;
        background-color: #339933;
        border-radius: 15px;
    }
    .show-list-sizes-btn a {
        color: white;
        font-weight: bold;

    }

</style>
<table class="table table-striped table-bordered" style="text-align:center;">
    <thead class="thead-dark">
        <tr>
            <th>STT</th>
            <th>Image</th>
            <th>Product name</th>
            <th>Description</th>
            <th>Material</th>
            <th>Price</th>
            <th>Loại</th>
            <th>Created at</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
        @if($products->isEmpty())
            <tr>
                <td colspan="9" style="text-align: center; font-style: oblique; color: gray;">No products found</td>
            </tr>
        @endif

        @foreach($products as $index => $product) 
            <tr>
                <td>
                    {{ '#'.$products->firstItem() + $index }}
                </td>
                <td style="max-width: 150px;">
                    @if ($product->images->isNotEmpty())
                        <img src="{{ asset('storage/' . $product->images->first()->image_url) }}" alt="Ảnh sản phẩm" style="max-width: 100%; max-height: 100px; object-fit: contain;">
                    @else
                        null
                    @endif
                </td>
                <td>{{ $product->name }}</td>
                <td>{!! $product->description !!}</td>
                {{-- <td>{{ $product->description }}</td> --}}
                <td>{{ $product->material }}</td>
                <td>{{ number_format($product->price, 0, ',', '.') }}</td>
                <td>{{ $product->category->name }}</td>
                <td style="max-width: 50px;">{{ Carbon::parse($product->created_at)->format('d/m/Y H:i:s') }}</td>
                <td>
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <div class="show-list-sizes-btn">
                            <a href="javascript:void(0);" class="btn btn-ghost show-sizes" data-product-id="{{ $product->id }}">Sizes</a>
                        </div>
                        <div style="display: flex;">
                            <a href="{{ route('product.edit', ['id' => $product->id]) }}" class="btn btn-primary">Update</a>
                            <form action="{{ route('product.destroy', ['id' => $product->id]) }}" method="POST" style="display: inline; margin-left: 10px;" id="deleteForm">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-delete">Delete</button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
        <div id="confirmModel" class="model">
            <div class="model-content">
                <h3>Xác nhận xóa</h3>
                <p>Bạn có chắc chắn muốn xóa sản phẩm này không?</p>
                <div class="model-actions">
                    <button id="cancelBtn" class="btn-cancel">Cancel</button>
                    <button id="confirmBtn" class="btn-confirm">Delete</button>
                </div>
            </div>
        </div>
    </tbody>
</table>

<div id="popup-overlay" class="popup-overlay"></div>

<div id="popup" class="popup" style="display: none;">
    <div class="popup-content">
        <span id="close-popup" style="cursor: pointer;">&times;</span>
        <h3 class="title">List sizes</h3>
        <table class="table table-striped table-bordered" style="text-align:center;">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Size</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody id="size-list"></tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const showSizesButtons = document.querySelectorAll('.show-sizes');
        showSizesButtons.forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.getAttribute('data-product-id');

                // Xóa danh sách kích thước cũ trong popup
                document.getElementById('size-list').innerHTML = '';

                // Gửi yêu cầu AJAX để lấy danh sách kích thước của sản phẩm
                fetch(`/product/${productId}/sizes`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.sizes && data.sizes.length > 0) {
                            let sizeList = document.getElementById('size-list');
                            data.sizes.forEach((size, index) => {
                                const row = `
                                    <tr>
                                        <td>#${index + 1}</td>
                                        <td><b>${size.size_name}</b></td>
                                        <td>${size.stock_quantity}</td>
                                    </tr>
                                `;
                                sizeList.innerHTML += row;
                            });
                        } else {
                            document.getElementById('size-list').innerHTML = '<tr><td colspan="4" style="text-align: center; font-style: italic;">No sizes found</td></tr>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('size-list').innerHTML = `<tr><td colspan="4">${error.message}</td></tr>`;
                    });

                // Hiển thị popup
                document.getElementById('popup').style.display = 'block';
                document.getElementById('popup-overlay').classList.add('show');
            });
        });

         // Đóng popup khi nhấn vào nút đóng
        document.getElementById('close-popup').addEventListener('click', function () {
            document.getElementById('popup').style.display = 'none';
            document.getElementById('popup-overlay').classList.remove('show');
        });

        // Đóng popup khi nhấn vào overlay
        document.getElementById('popup-overlay').addEventListener('click', function () {
            document.getElementById('popup').style.display = 'none';
            document.getElementById('popup-overlay').classList.remove('show');
        });

        // Confirm model delete
        const deleteButtons = document.querySelectorAll('.btn-delete');
        const model = document.getElementById('confirmModel');
        const cancelBtn = document.getElementById('cancelBtn');
        const confirmBtn = document.getElementById('confirmBtn');
        const deleteBtn = document.getElementById('deleteForm');

        deleteButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                model.classList.add('show');
            });

        })

        cancelBtn.addEventListener('click', function() {
            model.classList.remove('show');
        });

        confirmBtn.addEventListener('click', function() {
            deleteBtn.submit();
            model.classList.remove('show');
        });
        window.addEventListener('click', function(e) {
            if (e.target === model) {
                model.classList.remove('show');
            }
        });
    });

</script>
