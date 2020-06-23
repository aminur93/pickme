<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('assets/admin/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('assets/admin/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ \Illuminate\Support\Facades\Auth::user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('employee') }}" class="nav-link {{ Request::routeIs('employee','employee.create','employee.edit') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Employee</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('attendance') }}" class="nav-link {{ Request::routeIs('attendance','attendance.create','attendance.edit') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clock"></i>
                        <p>Employee Attendance</p>
                    </a>
                </li>

                @hasrole('Admin|Manager')

                <li class="nav-item has-treeview {{ Request::routeIs('permission','permission.create','permission.edit','role','role,create','role.edit','user','user.create','user.edit') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::routeIs('permission','permission.create','permission.edit','role','role,create','role.edit','user','user.create','user.edit') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            User Management
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">

                            <a href="{{ route('user') }}" class="nav-link {{ Request::routeIs('user','user.create','user.edit') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>User</p>
                            </a>

                            <a href="{{ route('role') }}" class="nav-link {{ Request::routeIs('role','role,create','role.edit') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Roles</p>
                            </a>

                            <a href="{{ route('permission') }}" class="nav-link {{ Request::routeIs('permission','permission.create','permission.edit') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Permissions</p>
                            </a>

                        </li>
                    </ul>
                </li>

                @endhasrole

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>