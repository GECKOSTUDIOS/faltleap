<div class="row">
    <div class="col-12 mb-4">
        <h2 class="h4">Dashboard Overview</h2>
        <p class="text-gray-600">Welcome to the <span style="color:#ff6600">FlatLeap</span> Admin Panel</p>
    </div>
</div>

<div class="row">
    <!-- Reverse Proxies Card -->
    <div class="col-12 col-sm-6 col-xl-4 mb-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <div class="row d-block d-xl-flex align-items-center">
                    <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                        <div class="icon-shape icon-shape-primary rounded me-4 me-sm-0">
                            <i class="bi bi-server fs-2"></i>
                        </div>
                    </div>
                    <div class="col-12 col-xl-7 px-xl-0">
                        <div class="d-none d-sm-block">
                            <h2 class="h5">Reverse Proxies</h2>
                            <h3 class="fw-extrabold mb-1">-</h3>
                            <div class="small mt-2">
                                <a href="/manage" class="text-primary">Manage Proxies</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Card -->
    <div class="col-12 col-sm-6 col-xl-4 mb-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <div class="row d-block d-xl-flex align-items-center">
                    <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                        <div class="icon-shape icon-shape-secondary rounded me-4 me-sm-0">
                            <i class="bi bi-people fs-2"></i>
                        </div>
                    </div>
                    <div class="col-12 col-xl-7 px-xl-0">
                        <div class="d-none d-sm-block">
                            <h2 class="h5">Users</h2>
                            <h3 class="fw-extrabold mb-1">-</h3>
                            <div class="small mt-2">
                                <a href="/users" class="text-secondary">Manage Users</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-xl-8 mb-4">
        <div class="card border-0 shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="fs-5 fw-bold mb-0">Quick Actions</h2>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="/users/edit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center">
                            <i class="bi bi-person-plus me-2"></i>
                            Add New User
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="/manage/edit" class="btn btn-secondary w-100 d-flex align-items-center justify-content-center">
                            <i class="bi bi-plus-circle me-2"></i>
                            Add Reverse Proxy
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="/users" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
                            <i class="bi bi-list-ul me-2"></i>
                            View All Users
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="/manage" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center">
                            <i class="bi bi-list-ul me-2"></i>
                            View All Proxies
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
