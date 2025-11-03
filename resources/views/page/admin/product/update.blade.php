
@extends('layouts.admin.app')

@section('title', 'Product')
@section('header-title', 'Product')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        form {
            max-width: auto;
            margin: auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button[type="submit"],
        button[type="button"] {
            background-color: #3490dc;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        button[type="submit"]:hover,
        button[type="button"]:hover {
            background-color: #2779bd;
        }

        .remove-size,
        .remove-image {
            background: none;
            border: none;
            color: red;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
        }

        hr {
            margin-top: 30px;
            margin-bottom: 20px;
        }

        /* 2 cột chính */
        .form-columns {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .form-column {
            flex: 1;
            min-width: 300px;
        }

        /* Size */
        .size-form {
            border: 1px dashed #ccc;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            background: #fff;
        }

        .size-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
        }

        .size-input {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Image */
        .image-form {
            border: 1px dashed #ccc;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            background: #fff;
            display: flex;
            align-items: center;
        }

        .radio-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 20px;
            margin-right: 30px;
        }

        .image-form {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 15px;
        }

        .image-left {
            flex: 1 1 60%;
            display: flex;
            flex-direction: column;
        }

        .image-right {
            flex: 1 1 40%;
            display: flex;
            align-items: center; /* canh giữa hàng ngang */
            gap: 15px;
        }

        /* giữ label radio với input thẳng hàng */
        .image-right .radio-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
        }

        /* nút remove */
        .image-right button.remove-image {
            cursor: pointer;
        }

        /* Responsive */
        @media screen and (max-width: 768px) {
            .form-columns {
                flex-direction: column;
            }

            button[type="submit"],
            button[type="button"] {
                width: 100%;
            }

            .remove-size,
            .remove-image {
                display: block;
                margin-left: 0;
                margin-top: 20px;
            }
        }

        /* Tạo hình dáng custom cho radio button */
        input[type="radio"] + label {
            position: relative;
            padding-top: 40px;
            cursor: pointer;
            font-size: 16px;
            color: #333;
            display: inline-block;
            line-height: 20px;
            font-family: Arial, sans-serif;
        }

    </style>
@endsection
@section('content')
    <h1>Update Product</h1>
    <form action="{{ route('product.update', ['id' => $product->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
            <div class="form-columns">
                <div class="form-column">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter product name" value="{{ $product->name }}" required>

                    <label for="price">Price</label>
                    <input type="text" id="price" name="price" placeholder="Enter price" value="{{ $product->price }}" required>

                    <label for="category_name">Category name</label><br>
                    <select name="category_id" style="margin-top:-60px;">
                        @foreach($category as $category)
                            <option value="{{ $category->id }}" 
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <label for="gender">Gender</label>
                    <select name="gender">
                        <option value="1" {{ $product->gender == 1 ? 'selected' : '' }}>Nam</option>
                        <option value="0" {{ $product->gender == 0 ? 'selected' : '' }}>Nữ</option>
                        <option value="2" {{ $product->gender == 2 ? 'selected' : '' }}>Unisex</option>
                    </select>
                </div>

                <div class="form-column">
                    <label for="material">Material</label>
                    <input type="text" id="material" name="material" placeholder="Enter material" value="{{ $product->material }}" required>

                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Enter description" rows="13" required style="margin-left: 5px;">{{ $product->description }}</textarea>
                </div>
            </div>
            <hr>
            <div>
                <p><strong>Sizes</strong></p>
                <div id="size-container">
                    <?php 
                        if(count($productSizes) > 0) {
                    ?>
                    <div class="size-form">
                        @foreach($productSizes as $index => $productSize) 
                            <div class="size-row">
                                <div class="size-input">
                                    <label>Size</label>
                                    <input type="text" name="sizes[{{$index}}][size_name]" value="{{ $productSize->size->name }}" required/>
                                </div>
                                <div class="size-input">
                                    <label>Stock Quantity</label>
                                    <input type="number" name="sizes[{{$index}}][stock_quantity]" value="{{ $productSize->stock_quantity }}" required/>
                                </div>
                                <button type="button" class="remove-size">Remove</button>
                            </div>
                        @endforeach
                    </div>
                    <?php 
                        }
                    ?>
                </div>
                <button type="button" id="add-size">Add Size</button>
            </div>
            <hr>
            <div id="image-container">
                @foreach($productImages as $index => $productImage) 
                    <div id="image-container-{{ $index }}" style="display: flex; align-items: center; margin-bottom: 10px;">
                        <div style="position: relative; display: inline-block;">
                            <label for="image_{{ $index }}">Image {{ $index + 1 }}:</label><br>
                            
                            @if ($productImage->image_url)
                                <div style="position: relative; display: inline-block;">
                                    <!-- Icon chỉnh sửa -->
                                    <a href="javascript:void(0)" onclick="document.getElementById('image_{{ $index }}').click();" 
                                    style="position: absolute; top: 5px; right: 40px;">
                                        <i class="fa fa-pencil" style="color: white; font-size: 18px; background-color: rgba(0, 0, 0, 0.5); border-radius: 50%; padding: 5px;"></i>
                                    </a>
                                    <input type="file" id="image_{{ $index }}" style="display: none;" onchange="updateImage('{{ $productImage->id }}', this)">
                                
                                    <!-- Nút xóa hình ảnh -->
                                    <button type="button" class="delete-image-btn" data-id="{{ $productImage->id }}" style="position: absolute; top: 5px; right: 2px; color: white; font-size: 18px; background-color: rgba(255, 0, 0, 0.5); border-radius: 50%; padding: 5px; border: none; cursor: pointer;">
                                        <i class="fa fa-trash"></i>
                                    </button>

                                    <img id="currentImage{{ $index }}" src="{{ asset('storage/' . $productImage->image_url) }}" alt="Ảnh sản phẩm" height="70" width="70" data-id="{{ $productImage->id }}" style="max-width: 100%; max-height: 100px; object-fit: contain;">
                                </div>
                            @else
                                <input type="file" name="images[{{ $index }}][file]" accept="image/jpeg, image/jpg, image/png"><br><br>
                            @endif
                            <input type="file" name="images[{{ $index }}][file]" accept="image/jpeg, image/jpg, image/png" id="image_{{ $index }}" style="display: none;" onchange="previewImage({{ $index }}, event)">
                        </div>
                        
                        <!-- Radio button để chọn ảnh chính, nằm bên phải -->
                        <div style="margin-left: 15px;">
                            <input type="radio" name="main_image" value="{{ $index }}" required
                                @if($productImage->is_main)
                                    checked
                                @endif
                            >
                            <label for="main_image">Set as Main Image</label>
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="button" id="add-image" style="margin-top: 10px;">Add Image</button><br><br>

            <div style="text-align: center;">
                <button type="submit" style="background-color: #28a745; color: white;  padding: 15px 27px; border-radius: 4px;font-size: 14px;cursor: pointer;transition: background-color 0.2s ease; font-weight: bold;">Update Product</button>
            </div>
    </form>
@endsection

@push('scripts')
<script>
    function previewImage(index, event) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var imgElement = document.getElementById('currentImage' + index);
            if (imgElement) {
                imgElement.src = e.target.result; 
            }
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function removeImage(index) {
        var container = document.getElementById('image-container-' + index);
        container.parentNode.removeChild(container);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const sizeContainer = document.getElementById('size-container');
        const addSizeButton = document.getElementById('add-size');
        const addImageButton = document.getElementById('add-image');
        const imageContainer = document.getElementById('image-container');
        let sizeIndex = {{ count($productSizes) }};
        let imageIndex = {{ count($productImages) }};

        // Lắng nghe khi nhấn vào nút "Add Size"
        addSizeButton.addEventListener('click', function () {
            const newSizeForm = document.createElement('div');
            newSizeForm.classList.add('size-form');
            newSizeForm.style.marginBottom = "15px";

            newSizeForm.innerHTML = `
                <label for="size_name">Size:</label>
                <input 
                    type="text" 
                    name="sizes[${sizeIndex}][size_name]" 
                    placeholder="Enter size (e.g., S, M, L)" 
                    required 
                    style="margin-right: 10px;">

                <label for="stock_quantity">Stock Quantity:</label>
                <input 
                    type="number" 
                    name="sizes[${sizeIndex}][stock_quantity]" 
                    placeholder="Enter stock quantity" 
                    min="0" 
                    required 
                    style="margin-right: 10px;">

                <button 
                    type="button" 
                    class="remove-size" 
                    style="color: red; border: none; background: none; cursor: pointer;">Remove</button>
            `;
            
            sizeContainer.appendChild(newSizeForm);
            sizeIndex++;
        });
        sizeContainer.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('remove-size')) {
                e.target.closest('.size-form').remove();
            }
        });

        addImageButton.addEventListener('click', function () {
            const newImageDiv = document.createElement('div');
            newImageDiv.id = 'image-container-' + imageIndex;
            newImageDiv.style.display = "flex";
            newImageDiv.style.alignItems = "center";
            newImageDiv.style.marginBottom = "10px";

            newImageDiv.innerHTML = `
                <div style="position: relative; display: inline-block;">
                    <label for="image_${imageIndex}">Image ${imageIndex + 1}:</label><br>
                    <input type="file" name="images[${imageIndex}][file]" accept="image/jpeg, image/jpg, image/png" id="image_${imageIndex}" onchange="previewImage(${imageIndex}, event)"><br><br>
                </div>

                <div style="margin-left: 15px;">
                    <input type="radio" name="main_image" value="${imageIndex}">
                    <label for="main_image">Set as Main Image</label>
                </div>
            `;
            imageContainer.appendChild(newImageDiv);
            imageIndex++;
        });
    });

    // Ajax delete image
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-image-btn');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const imageId = this.getAttribute('data-id');
                const button = this;
                
                fetch(`/product/delete-image/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to delete image');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) { 
                        location.reload();
                    } else {
                        alert('Error deleting image: ' + (data.message || 'Unknown error'));
                    }
                }).catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the image');
                });
            });
        });
    });

    function updateImage(imageId, inputElement) {
        const file = inputElement.files[0]; 

        if (!file) {
            alert('Vui lòng chọn hình ảnh!');
            return;
        }

        const formData = new FormData();
        formData.append('image', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        fetch(`/product/update-image/${imageId}`, { 
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to update image');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                location.reload();  
            } else {
                alert('Có lỗi xảy ra: ' + (data.message || 'Không xác định'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra trong quá trình cập nhật hình ảnh');
        });
    }
</script>
@endpush

{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<h1>Update Product</h1>
<form action="{{ route('product.update', ['id' => $product->id]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" placeholder="Enter category name" value="{{ $product->name }}" required><br><br>

        <label for="description">Description:</label><br>
        <input type="text" id="description" name="description" placeholder="Enter description" value="{{ $product->description }}" required><br><br>

        <label for="material">Material:</label><br>
        <input type="text" id="material" name="material" placeholder="Enter material" value="{{ $product->material }}" required><br><br>

        <label for="price">Price:</label><br>
        <input type="text" id="price" name="price" placeholder="Enter price" value="{{ $product->price }}" required><br><br>

        <label for="category_name">Category name:</label><br>
        <select name="category_id">
            @foreach($category as $category)
                <option value="{{ $category->id }}" 
                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select><br><br>

        <div id="size-container">
            @foreach($productSizes as $index => $productSize) 
                <div class="size-form" id="size-container-{{$index}}" style="margin-bottom: 15px;">
                    <label>Size:</label> 
                    <input type="text" name="sizes[{{$index}}][size_name]" value="{{ $productSize->size->name }}" style="margin-right: 10px;"/>
                    <label>Stock Quantity:</label> 
                    <input type="number" name="sizes[{{$index}}][stock_quantity]" value="{{ $productSize->stock_quantity }}" style="margin-right: 10px;"/>

                    <button type="button" class="remove-size" style="color: red; border: none; background: none; cursor: pointer;">Remove</button>
                </div>
            @endforeach
        </div>
        <button type="button" id="add-size">Add Size</button><br><br>

        <div id="image-container">
            @foreach($productImages as $index => $productImage) 
                <div id="image-container-{{ $index }}" style="display: flex; align-items: center; margin-bottom: 10px;">
                    <div style="position: relative; display: inline-block;">
                        <label for="image_{{ $index }}">Image {{ $index + 1 }}:</label><br>
                        
                        @if ($productImage->image_url)
                            <div style="position: relative; display: inline-block;">
                                <!-- Icon chỉnh sửa -->
                                <a href="javascript:void(0)" onclick="document.getElementById('image_{{ $index }}').click();" 
                                   style="position: absolute; top: 5px; right: 40px;">
                                    <i class="fa fa-pencil" style="color: white; font-size: 18px; background-color: rgba(0, 0, 0, 0.5); border-radius: 50%; padding: 5px;"></i>
                                </a>
                                <input type="file" id="image_{{ $index }}" style="display: none;" onchange="updateImage('{{ $productImage->id }}', this)">
                            
                                <!-- Nút xóa hình ảnh -->
                                <button type="button" class="delete-image-btn" data-id="{{ $productImage->id }}" style="position: absolute; top: 5px; right: 2px; color: white; font-size: 18px; background-color: rgba(255, 0, 0, 0.5); border-radius: 50%; padding: 5px; border: none; cursor: pointer;">
                                    <i class="fa fa-trash"></i>
                                </button>

                                <img id="currentImage{{ $index }}" src="{{ asset('storage/' . $productImage->image_url) }}" alt="Ảnh sản phẩm" height="70" width="70" data-id="{{ $productImage->id }}" style="max-width: 100%; max-height: 100px; object-fit: contain;">
                            </div>
                        @else
                            <input type="file" name="images[{{ $index }}][file]" accept="image/jpeg, image/jpg, image/png"><br><br>
                        @endif
                        <input type="file" name="images[{{ $index }}][file]" accept="image/jpeg, image/jpg, image/png" id="image_{{ $index }}" style="display: none;" onchange="previewImage({{ $index }}, event)">
                    </div>
                    
                    <!-- Radio button để chọn ảnh chính, nằm bên phải -->
                    <div style="margin-left: 15px;">
                        <input type="radio" name="main_image" value="{{ $index }}" required
                            @if($productImage->is_main)
                                checked
                            @endif
                        >
                        <label for="main_image">Set as Main Image</label>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="button" id="add-image" style="margin-top: 10px;">Add Image</button><br><br>

        <button type="submit">Update Product</button>
</form>
<script>
    function previewImage(index, event) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var imgElement = document.getElementById('currentImage' + index);
            if (imgElement) {
                imgElement.src = e.target.result; 
            }
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function removeImage(index) {
        var container = document.getElementById('image-container-' + index);
        container.parentNode.removeChild(container);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const sizeContainer = document.getElementById('size-container');
        const addSizeButton = document.getElementById('add-size');
        const addImageButton = document.getElementById('add-image');
        const imageContainer = document.getElementById('image-container');
        let sizeIndex = {{ count($productSizes) }};
        let imageIndex = {{ count($productImages) }};

        // Lắng nghe khi nhấn vào nút "Add Size"
        addSizeButton.addEventListener('click', function () {
            const newSizeForm = document.createElement('div');
            newSizeForm.classList.add('size-form');
            newSizeForm.style.marginBottom = "15px";

            newSizeForm.innerHTML = `
                <label for="size_name">Size:</label>
                <input 
                    type="text" 
                    name="sizes[${sizeIndex}][size_name]" 
                    placeholder="Enter size (e.g., S, M, L)" 
                    required 
                    style="margin-right: 10px;">

                <label for="stock_quantity">Stock Quantity:</label>
                <input 
                    type="number" 
                    name="sizes[${sizeIndex}][stock_quantity]" 
                    placeholder="Enter stock quantity" 
                    min="0" 
                    required 
                    style="margin-right: 10px;">

                <button 
                    type="button" 
                    class="remove-size" 
                    style="color: red; border: none; background: none; cursor: pointer;">Remove</button>
            `;
            
            sizeContainer.appendChild(newSizeForm);
            sizeIndex++;
        });
        sizeContainer.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('remove-size')) {
                e.target.closest('.size-form').remove();
            }
        });

        addImageButton.addEventListener('click', function () {
            const newImageDiv = document.createElement('div');
            newImageDiv.id = 'image-container-' + imageIndex;
            newImageDiv.style.display = "flex";
            newImageDiv.style.alignItems = "center";
            newImageDiv.style.marginBottom = "10px";

            newImageDiv.innerHTML = `
                <div style="position: relative; display: inline-block;">
                    <label for="image_${imageIndex}">Image ${imageIndex + 1}:</label><br>
                    <input type="file" name="images[${imageIndex}][file]" accept="image/jpeg, image/jpg, image/png" id="image_${imageIndex}" onchange="previewImage(${imageIndex}, event)"><br><br>
                </div>

                <div style="margin-left: 15px;">
                    <input type="radio" name="main_image" value="${imageIndex}">
                    <label for="main_image">Set as Main Image</label>
                </div>
            `;
            imageContainer.appendChild(newImageDiv);
            imageIndex++;
        });
    });

    // Ajax delete image
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-image-btn');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const imageId = this.getAttribute('data-id');
                const button = this;
                
                fetch(`/product/delete-image/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to delete image');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) { 
                        location.reload();
                    } else {
                        alert('Error deleting image: ' + (data.message || 'Unknown error'));
                    }
                }).catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the image');
                });
            });
        });
    });

    function updateImage(imageId, inputElement) {
        const file = inputElement.files[0]; 

        if (!file) {
            alert('Vui lòng chọn hình ảnh!');
            return;
        }

        const formData = new FormData();
        formData.append('image', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        fetch(`/product/update-image/${imageId}`, { 
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to update image');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                location.reload();  
            } else {
                alert('Có lỗi xảy ra: ' + (data.message || 'Không xác định'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra trong quá trình cập nhật hình ảnh');
        });
    }

</script>

 --}}
