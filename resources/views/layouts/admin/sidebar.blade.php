<aside class="sidebar" id="sidebar">
    <div class="logo">WineHouse</div>
    <nav>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}"><i>🏠</i><span>Dashboard</span></a>
        <a href="{{ route('product.index') }}" class="{{ request()->is('product') ? 'active' : '' }}"><i>👗</i><span>Products</span></a>
        <a href="#"><i>📊</i><span>Category</span></a>
        <a href="{{ route('order.index') }}" class="{{ request()->is('order') ? 'active' : '' }}"><i>🛒</i><span>Orders</span></a>
        <a href="#"><i>👥</i><span>Customers</span></a>
        <a href="#"><i>⚙️</i><span>Settings</span></a>
        <a href="/"><i>⬅️</i><span>Home page</span></a>
    </nav>
</aside>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const navLinks = document.querySelectorAll("#sidebar nav a");
        navLinks.forEach(link => {
            link.addEventListener("click", function () {
                navLinks.forEach(l => l.classList.remove("active"));
                this.classList.add("active");
            });
        });
    });
</script>
