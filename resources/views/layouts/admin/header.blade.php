<div class="header-left">
    <button class="menu-toggle" id="menuToggle" aria-label="Toggle sidebar">&#9776;</button>
    <div class="header-title">@yield('header-title', 'Dashboard')</div>
</div>
<div class="header-right">
    <button aria-label="Notifications">
        🔔<span class="badge">3</span>
    </button>
    <div class="notification-dropdown">
        <ul>
            <li>
                Thông báo 1
                <span class="new-label">New</span>
            </li>
            <li>Thông báo 2</li>
            <li>Thông báo 3</li>
        </ul>
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