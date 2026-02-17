<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Reverse Proxy Management</h2>
            <a href="/manage/edit" class="btn btn-primary d-inline-flex align-items-center">
                <i class="bi bi-plus-circle me-2"></i>
                Add New Proxy
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0 rounded">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">#</th>
                                <th class="border-0">URL</th>
                                <th class="border-0">Target</th>
                                <th class="border-0">Type</th>
                                <th class="border-0">SSL Status</th>
                                <th class="border-0">Owner</th>
                                <th class="border-0 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->data as $r) { ?>
                                <tr>
                                    <td class="fw-bold"><?php echo $r->idreverseproxies; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-globe text-primary me-2"></i>
                                            <span class="fw-normal"><?php echo $r->server_name; ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted"><?php echo $r->target_address; ?>:<?php echo $r->target_port; ?></span>
                                    </td>
                                    <td>
                                        <?php if ($r->is_websocket) { ?>
                                            <span class="badge bg-info">
                                                <i class="bi bi-arrow-left-right me-1"></i>WebSocket
                                            </span>
                                        <?php } else { ?>
                                            <span class="badge bg-secondary">HTTP</span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if ($r->generate_ssl) { ?>
                                            <span class="badge bg-success">
                                                <i class="bi bi-shield-check me-1"></i>Valid
                                            </span>
                                            <?php if ($r->acme_valid_until) { ?>
                                                <br><small class="text-muted">Until: <?php echo $r->acme_valid_until; ?></small>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <span class="badge bg-danger">
                                                <i class="bi bi-shield-x me-1"></i>No SSL
                                            </span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php
                                        // Handle joined user data
                                        $username = 'N/A';
                                        if (isset($r->users) && isset($r->users->username)) {
                                            $username = $r->users->username;
                                        } elseif (isset($r->username)) {
                                            $username = $r->username;
                                        }
                                        ?>
                                        <span class="badge bg-primary"><?php echo $username; ?></span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <a class="btn btn-sm btn-success" href="/manage/deploy/<?php echo $r->idreverseproxies; ?>" title="Deploy">
                                                <i class="bi bi-rocket-takeoff"></i>
                                            </a>
                                            <a class="btn btn-sm btn-primary" href="/manage/edit/<?php echo $r->idreverseproxies; ?>" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="#"
                                                class="btn btn-sm btn-danger"
                                                onclick="if(confirm('Are you sure you want to delete this proxy?')) { window.location='/manage/delete/<?php echo $r->idreverseproxies; ?>'; } return false;"
                                                title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if (empty($this->data)) { ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                        No reverse proxies found. Click "Add New Proxy" to create one.
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
