<nav
class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
id="layout-navbar">
<div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
  <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
    <i class="mdi mdi-menu mdi-24px"></i>
  </a>
</div>

<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

  <ul class="navbar-nav flex-row align-items-center ms-auto">


    <!-- Style Switcher -->
    <li class="nav-item dropdown-style-switcher dropdown me-1 me-xl-0">
      <a
        class="nav-link btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
        href="javascript:void(0);"
        data-bs-toggle="dropdown">
        <i class="mdi mdi-24px"></i>
      </a>
      <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
        <li>
          <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
            <span class="align-middle"><i class="mdi mdi-weather-sunny me-2"></i>Light</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
            <span class="align-middle"><i class="mdi mdi-weather-night me-2"></i>Dark</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
            <span class="align-middle"><i class="mdi mdi-monitor me-2"></i>System</span>
          </a>
        </li>
      </ul>
    </li>
    <!-- / Style Switcher-->
    <!-- User -->
    <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
            <div class="avatar">
                <i class="mdi mdi-account-circle mdi-36px"></i>
            </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <a class="dropdown-item" href="">
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar">
                                <i class="mdi mdi-account-circle mdi-36px"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <span class="fw-medium d-block">{{Auth::user()->name}}</span>
                            <small class="text-muted">{{Auth::user()->email}}</small>
                        </div>
                    </div>
                </a>
            </li>
            <li>
                <div class="dropdown-divider"></div>
            </li>
            <li>
                <a class="dropdown-item" href="">
                    <i class="mdi mdi-account-outline me-2"></i>
                    <span class="align-middle">Profil Saya</span>
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="">
                    <i class="mdi mdi-cog-outline me-2"></i>
                    <span class="align-middle">Pengaturan</span>
                </a>
            </li>
            <li>
                <div class="dropdown-divider"></div>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="mdi mdi-logout me-2"></i>
                        <span class="align-middle">Keluar</span>
                    </a>
                </form>
            </li>
        </ul>
    </li>
    <!--/ User -->
  </ul>
</div>


</nav>
