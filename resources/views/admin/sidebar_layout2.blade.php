<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="index.html">
            <span class="align-middle">Trang Quản Trị</span>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header">
                Mục Lục
            </li>

            <li class="sidebar-item active">
                <a class="sidebar-link" href="{{ route('products.index') }}">
                    <i class="align-middle" data-feather="sliders"></i> 
                    <span class="align-middle">Sản Phẩm</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('categories.index') }}">
                    <i class="align-middle" data-feather="user"></i> 
                    <span class="align-middle">Danh Mục</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('types.index') }}">
                    <i class="align-middle" data-feather="log-in"></i> 
                    <span class="align-middle">Loại Sản Phẩm</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('admin.users') }}">
                    <i class="align-middle" data-feather="log-in"></i> 
                    <span class="align-middle">Người Dùng</span>
                </a>
            </li>
            

            <li class="sidebar-item">
                <a class="sidebar-link" href="pages-sign-up.html">
                    <i class="align-middle" data-feather="user-plus"></i> 
                    <span class="align-middle">Đơn Hàng</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="pages-blank.html">
                    <i class="align-middle" data-feather="book"></i> 
                    <span class="align-middle">Bình Luận</span>
                </a>
            </li>

            
        </ul>

        
    </div>
</nav>