<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
  <title>FaltLeap - Admin Panel</title>

  <!-- Volt CSS -->
  <link type="text/css" href="/volt/css/volt.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<body>
  <!-- Mobile Navigation -->
  <nav class="navbar navbar-dark navbar-theme-primary px-4 col-12 d-lg-none">
    <a class="navbar-brand me-lg-5" href="/">
      <span style="color:#ff6600">FaltLeap</span> Dashboard
    </a>
    <div class="d-flex align-items-center">
      <button class="navbar-toggler d-lg-none collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
              aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </nav>

  <!-- Sidebar Navigation -->
  <nav id="sidebarMenu" class="sidebar d-lg-block bg-gray-800 text-white collapse" data-simplebar>
    <div class="sidebar-inner px-4 pt-3">
      <div class="user-card d-flex d-md-none align-items-center justify-content-between justify-content-md-center pb-4">
        <div class="d-flex align-items-center">
          <div class="d-block">
            <h2 class="h5 mb-3">Hello, <?php echo $this->e($_SESSION['auth']['username'] ?? 'User'); ?></h2>
            <a href="/logout" class="btn btn-secondary btn-sm d-inline-flex align-items-center">
              <i class="bi bi-box-arrow-right me-1"></i> Logout
            </a>
          </div>
        </div>
        <div class="collapse-close d-md-none">
          <a href="#sidebarMenu" data-bs-toggle="collapse" data-bs-target="#sidebarMenu"
             aria-controls="sidebarMenu" aria-expanded="true" aria-label="Toggle navigation">
            <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
          </a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <ul class="nav flex-column pt-3 pt-md-0">
        <li class="nav-item">
          <a href="/dashboard" class="nav-link d-flex align-items-center">
            <span class="sidebar-icon">
              <i class="bi bi-speedometer2"></i>
            </span>
            <span class="mt-1 ms-1 sidebar-text"><span style="color:#ff6600">FaltLeap</span> Dashboard</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="/manage" class="nav-link d-flex align-items-center">
            <span class="sidebar-icon">
              <i class="bi bi-server"></i>
            </span>
            <span class="sidebar-text">Reverse Proxies</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="/users" class="nav-link d-flex align-items-center">
            <span class="sidebar-icon">
              <i class="bi bi-people"></i>
            </span>
            <span class="sidebar-text">Users</span>
          </a>
        </li>

        <li role="separator" class="dropdown-divider mt-4 mb-3 border-gray-700"></li>

        <li class="nav-item">
          <a href="/logout" class="nav-link d-flex align-items-center text-danger">
            <span class="sidebar-icon">
              <i class="bi bi-box-arrow-right"></i>
            </span>
            <span class="sidebar-text">Logout</span>
          </a>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="content">
    <!-- Top Navbar -->
    <nav class="navbar navbar-top navbar-expand navbar-dashboard navbar-dark ps-0 pe-2 pb-0">
      <div class="container-fluid px-0">
        <div class="d-flex justify-content-between w-100" id="navbarSupportedContent">
          <div class="d-flex align-items-center">
            <h1 class="h4 mb-0 text-white"></h1>
          </div>
          <ul class="navbar-nav align-items-center">
            <li class="nav-item dropdown ms-lg-3">
              <a class="nav-link dropdown-toggle pt-1 px-0" href="#" role="button"
                 data-bs-toggle="dropdown" aria-expanded="false">
                <div class="media d-flex align-items-center">
                  <div class="avatar rounded-circle bg-secondary d-flex align-items-center justify-content-center"
                       style="width: 40px; height: 40px;">
                    <i class="bi bi-person-circle text-white"></i>
                  </div>
                  <div class="media-body ms-2 text-dark align-items-center d-none d-lg-block">
                    <span class="mb-0 font-small fw-bold text-gray-900"><?php echo $this->e($_SESSION['auth']['username'] ?? 'User'); ?></span>
                  </div>
                </div>
              </a>
              <div class="dropdown-menu dashboard-dropdown dropdown-menu-end mt-2 py-1">
                <a class="dropdown-item d-flex align-items-center" href="/logout">
                  <i class="bi bi-box-arrow-right text-danger me-2"></i>
                  Logout
                </a>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <div class="py-4">
      <div class="container-fluid">
        {{flash}}
        {{content}}
      </div>
    </div>
  </main>

  <!-- Core -->
  <script src="/volt/vendor/@popperjs/core/dist/umd/popper.min.js"></script>
  <script src="/volt/vendor/bootstrap/dist/js/bootstrap.min.js"></script>

  <!-- Vendor JS -->
  <script src="/volt/vendor/onscreen/dist/on-screen.umd.min.js"></script>

  <!-- Slider -->
  <script src="/volt/vendor/nouislider/dist/nouislider.min.js"></script>

  <!-- Smooth scroll -->
  <script src="/volt/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

  <!-- Charts -->
  <script src="/volt/vendor/chartist/dist/chartist.min.js"></script>
  <script src="/volt/vendor/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>

  <!-- Datepicker -->
  <script src="/volt/vendor/vanillajs-datepicker/dist/js/datepicker.min.js"></script>

  <!-- Sweet Alerts 2 -->
  <script src="/volt/vendor/sweetalert2/dist/sweetalert2.all.min.js"></script>

  <!-- Moment JS -->
  <script src="https://cdn.jsdelivr.net/npm/moment@2.27.0/min/moment.min.js"></script>

  <!-- Notyf -->
  <script src="/volt/vendor/notyf/notyf.min.js"></script>

  <!-- Simplebar -->
  <script src="/volt/vendor/simplebar/dist/simplebar.min.js"></script>

  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>

  <!-- Volt JS -->
  <script src="/volt/assets/js/volt.js"></script>

</body>

</html>
