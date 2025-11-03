<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">User Management</h2>
            <a href="/users/edit" class="btn btn-primary d-inline-flex align-items-center">
                <i class="bi bi-person-plus me-2"></i>
                Add New User
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
                                <th class="border-0">Username</th>
                                <th class="border-0">Email</th>
                                <th class="border-0 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->data as $r) { ?>
                                <tr>
                                    <td class="fw-bold"><?php echo $r->idusers; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                <i class="bi bi-person text-white"></i>
                                            </div>
                                            <span class="fw-normal"><?php echo htmlspecialchars($r->username); ?></span>
                                        </div>
                                    </td>
                                    <td class="fw-normal">
                                        <?php echo htmlspecialchars($r->email ?? 'N/A'); ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <a class="btn btn-sm btn-primary" href="/users/edit/<?php echo $r->idusers; ?>" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="#"
                                                class="btn btn-sm btn-danger"
                                                onclick="if(confirm('Are you sure you want to delete this user?')) { window.location='/users/delete/<?php echo $r->idusers; ?>'; } return false;"
                                                title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if (empty($this->data)) { ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                        No users found. Click "Add New User" to create one.
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

<style>
.avatar-sm {
    width: 35px;
    height: 35px;
}
</style>
