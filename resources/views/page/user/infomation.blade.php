@extends('layouts/user/layoutmaster')
@section('page_title', 'Thông tin cá nhân')
@section('content')
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --accent: #ff5722;
            --muted: #9aa0a6;
            --border: #e6e6e6;
            --bg: #f7f7f8;
            --white: #fff;
            --danger: #d9534f;
            --success: #2b9d6f;
            --radius: 6px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        }
        body {
            background: var(--bg);
            margin: 0;
            color: #222;
            padding: 28px;
            -webkit-font-smoothing: antialiased;
        }
        .container_infor {
            max-width: 1280px;
            margin: 0 auto;
            background: var(--white);
            border-radius: 8px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            margin-top: 10px;
            min-height: 400px;
            margin-bottom: 40px;
            padding: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            font-size: 35px;
            color: var(--accent);
            margin-bottom: 50px;
        }
        .user-info {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }
        .user-info .field {
            display: flex;
            justify-content: space-between;
            width: 48%;
            margin-bottom: 10px;
            align-items: center;
        }
        .user-info .label {
            font-weight: bold;
            color: var(--muted);
            flex: 0 0 30%;
            text-align: left;
            font-size: 16px;
        }
        .user-info .value {
            flex: 1;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background-color: #f9f9f9;
            height: 40px;
        }
        .user-info .value input {
            width: 100%;
            border: none;
            background-color: transparent;
            /* padding: 8px; */
            font-size: 16px;
            height: 100%;
            border-radius: var(--radius);
        }
        .user-info .value input:disabled {
            color: var(--muted);
            color: #333 !important;
        }
        .btn-wrapper {
            width: 100%;
            text-align: center;
            margin-top: 20px;
        }
        .btn-wrapper .btn {
            /* background-color: var(--accent); */
            color: var(--white);
            padding: 12px;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            text-align: center;
            width: 200px;
            margin: 0 auto;
            height: 60px;
        }
        
        .btn-wrapper .btn-edit:hover {
            background-color: #e64a19;
            color: #fff;
        }
        .btn-wrapper .btn-update:hover {
            background-color: #e64a19;
            color: #fff;
        }
        .btn-wrapper .btn-cancel:hover {
            color: #fff;
        }

        .btn-edit {
            background-color: var(--accent);
        }

        .btn-update {
            background-color: var(--accent);
        }
        .btn-cancel {
            background-color: gray;
        }
    </style>
</head>
<div class="container_infor" role="main" aria-label="Thông tin cá nhân">
    <div class="header">
        <h2>Thông tin cá nhân</h2>
    </div>
    <div class="user-info">
        <div class="field">
            <div class="label">ID:</div>
            <div class="value"><input type="text" id="id" class="no-edit" value="{{ $user->id }}" disabled></div>
        </div>
        <div class="field">
            <div class="label">Họ và tên:</div>
            <div class="value"><input type="text" name="name" value="{{ $user->name }}" disabled></div>
        </div>

        <div class="field">
            <div class="label">Email:</div>
            <div class="value"><input type="email" name="email" value="{{ $user->email }}" disabled></div>
        </div>
        <div class="field">
            <div class="label">Ngày xác thực email:</div>
            <div class="value"><input type="text" class="no-edit" value="{{ $user->email_verified_at }}" disabled></div>
        </div>

        <div class="field">
            <div class="label">Số điện thoại:</div>
            <div class="value"><input type="text" name="phone" value="{{ $user->phone }}" disabled></div>
        </div>
        <div class="field">
            <div class="label">Địa chỉ giao hàng:</div>
            <div class="value"><input type="text" name="delivery_address" value="{{ $user->delivery_address }}" disabled></div>
        </div>

        <div class="field">
            <div class="label">Vai trò:</div>
            <?php
                $role = '';
                if ($user->role == '2') {
                    $role = 'Quản trị viên';
                } elseif ($user->role == '1') {
                    $role = 'Người bán hàng';
                } else {
                     $role = 'Khách hàng';
                }
            ?>
            <div class="value"><input type="text" class="no-edit" value="{{ $role }}" disabled></div>
        </div>
        <div class="field">
            <div class="label">Ngày tạo tài khoản:</div>
            <div class="value"><input type="text" class="no-edit" value="{{ $user->created_at }}" disabled></div>
        </div>
    </div>
    <div class="btn-wrapper">
        <button class="btn btn-edit">Chỉnh sửa thông tin</button>
        <button class="btn btn-update hide">Cập nhập</button>
        <button class="btn btn-cancel hide">Hủy</button>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('.value input').each(function () {
            $(this).attr('data-original-value', $(this).val());
        });
        $('.btn-edit').click(function() {
            $('.btn-update').removeClass('hide');
            $('.btn-cancel').removeClass('hide');
            $('.btn-edit').addClass('hide');

            // Enable inputs
            $('.value input').not('.no-edit').prop('disabled', false);
        });
        $('.btn-cancel').click(function() {
            $('.btn-update').addClass('hide');
            $('.btn-cancel').addClass('hide');
            $('.btn-edit').removeClass('hide');

            // Disable inputs
            $('.value input').not('.no-edit').prop('disabled', true);

            // Reset data
            $('.value input').each(function() {
                var originalValue = $(this).attr('data-original-value');
                $(this).val(originalValue);
            })
        });
        $('.btn-update').click(function() {
            var updatedData = {
                name: $('input[name="name"]').val(),
                email: $('input[name="email"]').val(),
                phone: $('input[name="phone"]').val(),
                delivery_address: $('input[name="delivery_address"]').val()
            };

            $.ajax({
                url: '{{ route("page.update-infor") }}',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    ...updatedData
                },
                success: function(response) {
                    if(response.success) {
                        toastr.success(response.message);
                        $('.value input').each(function () {
                            var inputName = $(this).attr('name');
                            if (updatedData[inputName]) {
                                $(this).val(updatedData[inputName]);
                            }
                        });
                        $('.btn-update').addClass('hide');
                        $('.btn-cancel').addClass('hide');
                        $('.btn-edit').removeClass('hide');

                        $('.value input').not('.no-edit').prop('disabled', true);
                    }
                },
                error: function(error) {
                    alert('Có lỗi xảy ra, vui lòng thử lại!');
                }
            });
        });
    });
</script>
@endsection
