@extends('layouts.admin.app')

@section('title', 'Products')
@section('header-title', 'Products')
@section('content')
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
    </style>

    <h1>Create New Product</h1>
    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-columns">
            <div class="form-column">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter product name" required>

                <label for="price">Price</label>
                <input type="text" id="price" name="price" placeholder="Enter price" required>

                <label for="gender">Gender</label>
                <select name="gender">
                    <option value="" hidden selected>--Chọn giới tính--</option>
                    <option value="1">Nam</option>
                    <option value="0">Nữ</option>
                    <option value="2">Unisex</option>
                </select>

                <label for="category_name">Category</label>
                <select name="category_id">
                    <option value="" hidden selected>--Chọn loại--</option>
                    @foreach($category as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-column">
                <label for="material">Material</label>
                <input type="text" id="material" name="material" placeholder="Enter material" required>

                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Enter description" rows="12" required style="margin-left: 5px;"></textarea>
            </div>
        </div>
        <hr>
        <div>
            <p><strong>Sizes</strong></p>
            <div id="size-container">
                <div class="size-form">
                    <div class="size-row">
                        <div class="size-input">
                            <label>Size</label>
                            <input type="text" name="sizes[0][size_name]" placeholder="e.g., S, M, L" required>
                        </div>
                        <div class="size-input">
                            <label>Stock Quantity</label>
                            <input type="number" name="sizes[0][stock_quantity]" placeholder="e.g., 100" min="0" required>
                        </div>
                        <button type="button" class="remove-size">Remove</button>
                    </div>
                </div>
            </div>
            <button type="button" id="add-size">Add Size</button>
        </div>
        <hr>
        <div>
            <p><strong>Images</strong></p>
            <div id="image-container">
                <div class="image-form">
                    <div class="image-left">
                        <label>Image 1</label>
                        <input type="file" name="images[0][file]" accept="image/*" required>
                    </div>
                    <div class="image-right">
                        <label class="radio-label">
                        <input type="radio" name="main_image" value="0" required>
                        Set as Main Image
                        </label>
                        <button type="button" class="remove-image">Remove</button>
                    </div>
                </div>
            </div>
            <button type="button" id="add-image" style="margin-top: 10px;">Add Image</button><br><br>
        </div>

        <br><br>
        <div style="text-align: center;">
            <button type="submit" style="background-color: #28a745; color: white;  padding: 15px 27px; border-radius: 4px;font-size: 14px;cursor: pointer;transition: background-color 0.2s ease; font-weight: bold;">Create Product</button>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sizeContainer = document.getElementById('size-container'); 
            const addSizeButton = document.getElementById('add-size');
            let sizeIndex = 1; 

            addSizeButton.addEventListener('click', function () {
                const newSizeForm = document.createElement('div');
                newSizeForm.classList.add('size-form');
                newSizeForm.style.marginBottom = "15px"; 

                newSizeForm.innerHTML = `
                    <div class="size-row">
                        <div class="size-input">
                            <label>Size</label>
                            <input 
                                type="text" 
                                name="sizes[${sizeIndex}][size_name]" 
                                placeholder="e.g., S, M, L" 
                                required>
                        </div>
                        <div class="size-input">
                            <label>Stock Quantity</label>
                            <input 
                                type="number" 
                                name="sizes[${sizeIndex}][stock_quantity]" 
                                placeholder="e.g., 100" 
                                min="0" 
                                required>
                        </div>
                        <button type="button" class="remove-size">Remove</button>
                    </div>
                `;

                sizeContainer.appendChild(newSizeForm);
                sizeIndex++;
            });

            sizeContainer.addEventListener('click', function (e) {
                if (e.target && e.target.classList.contains('remove-size')) {
                    e.target.parentElement.remove();
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const imageContainer = document.getElementById('image-container');
            const addImageButton = document.getElementById('add-image');
            let imageIndex = 1;

            addImageButton.addEventListener('click', function () {
                const newImageForm = document.createElement('div');
                newImageForm.classList.add('image-form');
                newImageForm.style.marginBottom = "10px";

               newImageForm.innerHTML = `
                    <div class="image-left">
                        <label for="image_${imageIndex}">Image ${imageIndex + 1}:</label>
                        <input type="file" name="images[${imageIndex}][file]" accept="image/jpeg, image/jpg, image/png" required>
                    </div>
                    <div class="image-right">
                        <label class="radio-label">
                            <input type="radio" name="main_image" value="${imageIndex}" required>
                            Set as Main Image
                        </label>
                        <button type="button" class="remove-image">Remove</button>
                    </div>
                `;

                imageContainer.appendChild(newImageForm);
                imageIndex++;
            });

            imageContainer.addEventListener('click', function (e) {
                if (e.target && e.target.classList.contains('remove-image')) {
                    const imageForm = e.target.closest('.image-form');
                    if (imageForm) {
                        imageForm.remove();
                    }
                }
            });
        });
    </script>
@endpush