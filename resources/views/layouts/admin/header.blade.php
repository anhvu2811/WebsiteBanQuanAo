<div class="header-left">
    <button class="menu-toggle" id="menuToggle" aria-label="Toggle sidebar">&#9776;</button>
    <div class="header-title">@yield('header-title', 'Dashboard')</div>
</div>
<div class="header-right">
    <div class="notification-wrapper" tabindex="0">
        <div class="notification-icon" aria-label="Thông báo">
            🔔
            <span class="notification-badge">0</span>
        </div>
        <div class="notification-dropdown" role="menu" aria-labelledby="notificationDropdown">
            <div class="notification-dropdown-header">Thông báo</div>
            <div class="notification-list">
                {{-- <div class="notification-item unread" role="menuitem" tabindex="0">
                    <div class="notification-title">
                    Bạn có đơn hàng mới
                    <span class="badge hot">Hot</span>
                    </div>
                    <div class="notification-content">Khách hàng Nguyễn Văn A vừa đặt đơn hàng #ORD12345.</div>
                </div>
                <div class="notification-item unread" role="menuitem" tabindex="0">
                    <div class="notification-title">
                    Sản phẩm sắp hết hàng
                    <span class="badge hot">Hot</span>
                    </div>
                    <div class="notification-content">Áo thun trắng chỉ còn 2 sản phẩm trong kho.</div>
                </div>
                <div class="notification-item" role="menuitem" tabindex="0">
                    <div class="notification-title">
                    Đơn hàng đã giao thành công
                    <span class="badge read">Đã đọc</span>
                    </div>
                    <div class="notification-content">Đơn hàng #ORD12222 đã được giao đến khách hàng Lê Thị B.</div>
                </div> --}}
            </div>
        </div>
    </div>
    <div class="profile" tabindex="0">
        <img src="https://i.pravatar.cc/36?img=58" alt="Admin Profile" />
        <?php 
            $user = auth()->user();
        ?>
        <span>{{ $user->name }}</span>
        <div class="dropdown-content" id="dropdownMenu">
            <a href="#">Thông tin chi tiết</a>
            <a href="#">Đổi mật khẩu</a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Đăng xuất
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // document.getElementById('profileMenu').addEventListener('click', function() {
    //     const dropdown = document.getElementById('dropdownMenu');
    //     dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    // });

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
    
    $(document).ready(function() {
        let count = 0;
        const urlGetNotiApi = "{{ route('noti.get-notifications') }}"
        $.ajax({
            url: urlGetNotiApi,
            method: 'GET',
            success: function(response) {
                const data = response.data;
                if (response.success) {
                    let html = '';
                    let count = data.length;

                    data.forEach(function(item) {
                        html += loadNoti(item);
                    });

                    $('.notification-list').html(html);
                    $('.notification-badge').html(count);
                   
                    console.log(data);
                }
            },
            error: function(xhr) {
                alert(xhr.responseJSON.error);
            }
        });

        function loadNoti(data) {
            let html = '';
            html += `<div class="notification-item unread" role="menuitem" tabindex="0">
                            <div class="notification-title">
                            ${data.title}
                            ${data.is_read ? '<span class="badge read">Đã đọc</span>' : '<span class="badge hot">Hot</span>'}
                            </div>
                            <div class="notification-content">${data.content}</div>
                        </div>
                        `;
            return html;
        }
    });

</script>