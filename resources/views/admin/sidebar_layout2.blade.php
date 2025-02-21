<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="index.html">
            <span class="align-middle">Admin Dashboard</span>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header">
                Mục Lục
            </li>

            <li class="sidebar-item active">
                <a class="sidebar-link" href="{{ route('products.index') }}">
                    <i class="align-middle" data-feather="sliders"></i> 
                    <span class="align-middle">Product</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('categories.index') }}">
                    <i class="align-middle" data-feather="user"></i> 
                    <span class="align-middle">Category</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('admin.users') }}">
                    <i class="align-middle" data-feather="log-in"></i> 
                    <span class="align-middle">User</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="pages-sign-up.html">
                    <i class="align-middle" data-feather="user-plus"></i> 
                    <span class="align-middle">Comment</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="pages-blank.html">
                    <i class="align-middle" data-feather="book"></i> 
                    <span class="align-middle">Orders</span>
                </a>
            </li>

            
        </ul>

        
    </div>
</nav>