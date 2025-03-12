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
                <a class="sidebar-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#ordersMenu"
                    aria-expanded="false">
                    <i class="align-middle" data-feather="shopping-cart"></i>
                    <span class="align-middle">Đơn Hàng</span>
                </a>
                {{-- <ul id="ordersMenu" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar"> --}}
                <ul id="ordersMenu" class="collapse list-unstyled">
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="  {{ route('orders.index') }}  ">
                            <i class="align-middle" data-feather="clock"></i>
                            <span class="ms-3 align-middle">Danh Sách</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{--  {{ route('admin.orders', ['status' => 'pending']) }}  --}}">
                            <i class="align-middle" data-feather="clock"></i>
                            <span class="ms-3 align-middle">Chờ Xác Nhận</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{--  {{ route('admin.orders', ['status' => 'shipped']) }}  --}}">
                            <i class="align-middle" data-feather="check-circle"></i>
                            <span class="ms-3 align-middle">Đã Giao Hàng</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{-- {{ route('admin.orders', ['status' => 'cancelled']) }}  --}}">
                            <i class="align-middle" data-feather="x-circle"></i>
                            <span class="ms-3 align-middle">Đã Hủy</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('promotions.index') }}">
                    <i class="align-middle" data-feather="book"></i>
                    <span class="align-middle">Mã Khuyến Mãi</span>
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
