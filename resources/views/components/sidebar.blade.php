<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="/" class="app-brand-link">
            <span class="app-brand-logo demo">
                <i class="menu-icon tf-icons mdi mdi-wallet-outline" style="font-size: 2rem;"></i>
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2">Finance App</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="menu-icon tf-icons mdi mdi-chevron-left"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ Request::is('dashboard') ? 'active' : '' }}">
            <a href="/dashboard" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                <div>Dashboard</div>
            </a>
        </li>
        @if (auth()->user()->can('view-categories') ||
                auth()->user()->can('view-pakets') ||
                auth()->user()->can('view-users') ||
                auth()->user()->can('view-roles') ||
                auth()->user()->can('view-permissions'))
            <li class="menu-item {{ Request::is('master*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-database"></i>
                    <div>Master</div>
                </a>
                <ul class="menu-sub">
                    @can('view-categories')
                        <li class="menu-item {{ Request::is('master/categories') ? 'active' : '' }}">
                            <a href="/master/categories" class="menu-link">
                                <div>Kategori</div>
                            </a>
                        </li>
                    @endcan
                    @can('view-pakets')
                        <li class="menu-item {{ Request::is('master/pakets') ? 'active' : '' }}">
                            <a href="/master/pakets" class="menu-link">
                                <div>Paket</div>
                            </a>
                        </li>
                    @endcan
                    @can('view-users')
                        {{-- users --}}
                        <li class="menu-item {{ Request::is('master/users*') ? 'active' : '' }}">
                            <a href="/master/users" class="menu-link">
                                <div>Users</div>
                            </a>
                        </li>
                    @endcan
                    {{-- Roles --}}
                    @can('view-roles')
                        <li class="menu-item {{ Request::is('master/roles*') ? 'active' : '' }}">
                            <a href="/master/roles" class="menu-link">
                                <div>Roles</div>
                            </a>
                        </li>
                    @endcan
                    @can('view-permissions')
                        {{-- Permission --}}
                        <li class="menu-item {{ Request::is('master/permissions*') ? 'active' : '' }}">
                            <a href="/master/permissions" class="menu-link">
                                <div>Permissions</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endif


        <!-- Transaksi -->
        @if (auth()->user()->can('view-transactions') || auth()->user()->can('create-transactions'))
            <li class="menu-item {{ Request::is('transactions*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-cash-multiple"></i>
                    <div>Transaksi</div>
                </a>
                <ul class="menu-sub">
                    @can('create-transactions')
                        <li class="menu-item {{ Request::is('transactions/create') ? 'active' : '' }}">
                            <a href="/transactions/create" class="menu-link">
                                <div>Input Transaksi</div>
                            </a>
                        </li>
                    @endcan
                    @can('view-transactions')
                        <li class="menu-item {{ Request::is('transactions') ? 'active' : '' }}">
                            <a href="/transactions" class="menu-link">
                                <div>Lihat Transaksi</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endif



        @if (auth()->user()->can('view-income-report') ||
                auth()->user()->can('view-expense-report') ||
                auth()->user()->can('view-summary'))
            <!-- Laporan -->
            <li class="menu-item {{ Request::is('reports*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-file-document-outline"></i>
                    <div>Laporan</div>
                </a>
                <ul class="menu-sub">
                    @can('view-income-report')
                        <li class="menu-item {{ Request::is('reports/income') ? 'active' : '' }}">
                            <a href="/reports/income" class="menu-link">
                                <div>Laporan Pemasukan</div>
                            </a>
                        </li>
                    @endcan
                    @can('view-expense-report')
                        <li class="menu-item {{ Request::is('reports/expense') ? 'active' : '' }}">
                            <a href="/reports/expense" class="menu-link">
                                <div>Laporan Pengeluaran</div>
                            </a>
                        </li>
                    @endcan
                    @can('view-summary')
                        <li class="menu-item {{ Request::is('reports/summary') ? 'active' : '' }}">
                            <a href="/reports/summary" class="menu-link">
                                <div>Ringkasan</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endif





        <!-- Pengaturan -->
        <li class="menu-item {{ Request::is('settings*') ? 'active' : '' }}">
            <a href="/settings/security" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-cog-outline"></i>
                <div>Pengaturan</div>
            </a>
        </li>
    </ul>
</aside>
