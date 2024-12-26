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

      <!-- Transaksi -->
      <li class="menu-item {{ Request::is('transactions*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons mdi mdi-cash-multiple"></i>
          <div>Transaksi</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item {{ Request::is('transactions/create') ? 'active' : '' }}">
            <a href="/transactions/create" class="menu-link">
              <div>Input Transaksi</div>
            </a>
          </li>
          <li class="menu-item {{ Request::is('transactions') ? 'active' : '' }}">
            <a href="/transactions" class="menu-link">
              <div>Lihat Transaksi</div>
            </a>
          </li>
        </ul>
      </li>

      <!-- Kategori -->
      <li class="menu-item {{ Request::is('categories*') ? 'active' : '' }}">
        <a href="/categories" class="menu-link">
          <i class="menu-icon tf-icons mdi mdi-folder-outline"></i>
          <div>Kategori</div>
        </a>
      </li>

      <!-- Laporan -->
      <li class="menu-item {{ Request::is('reports*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons mdi mdi-file-document-outline"></i>
          <div>Laporan</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item {{ Request::is('reports/income') ? 'active' : '' }}">
            <a href="/reports/income" class="menu-link">
              <div>Laporan Pemasukan</div>
            </a>
          </li>
          <li class="menu-item {{ Request::is('reports/expense') ? 'active' : '' }}">
            <a href="/reports/expense" class="menu-link">
              <div>Laporan Pengeluaran</div>
            </a>
          </li>
          <li class="menu-item {{ Request::is('reports/summary') ? 'active' : '' }}">
            <a href="/reports/summary" class="menu-link">
              <div>Ringkasan</div>
            </a>
          </li>
        </ul>
      </li>

      <!-- Pengaturan -->
      <li class="menu-item {{ Request::is('settings*') ? 'active' : '' }}">
        <a href="/settings" class="menu-link">
          <i class="menu-icon tf-icons mdi mdi-cog-outline"></i>
          <div>Pengaturan</div>
        </a>
      </li>
    </ul>
  </aside>
