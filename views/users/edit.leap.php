<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-0"><?php echo $this->data->idusers ? 'Edit User' : 'Add New User'; ?></h2>
                <p class="text-muted mb-0">
                    <?php echo $this->data->idusers ? 'Update the user information below' : 'Create a new user account'; ?>
                </p>
            </div>
            <a href="/users" class="btn btn-secondary d-inline-flex align-items-center">
                <i class="bi bi-arrow-left me-2"></i>
                Back to Users
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow">
            <div class="card-header">
                <h5 class="mb-0">User Information</h5>
            </div>
            <div class="card-body">
                <form method="post" action="/users/edit/<?php echo $this->data->idusers ?? ''; ?>">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text"
                                       class="form-control"
                                       id="username"
                                       name="username"
                                       value="<?php echo htmlspecialchars($this->data->username ?? ''); ?>"
                                       placeholder="Enter username"
                                       required>
                            </div>
                            <small class="form-text text-muted">Choose a unique username for this user.</small>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email"
                                       class="form-control"
                                       id="email"
                                       name="email"
                                       value="<?php echo htmlspecialchars($this->data->email ?? ''); ?>"
                                       placeholder="user@example.com"
                                       required>
                            </div>
                            <small class="form-text text-muted">We'll use this for account notifications.</small>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password"
                                       class="form-control"
                                       id="password"
                                       name="password"
                                       value=""
                                       placeholder="<?php echo $this->data->idusers ? 'Leave blank to keep current password' : 'Enter password'; ?>"
                                       <?php echo $this->data->idusers ? '' : 'required'; ?>>
                            </div>
                            <?php if ($this->data->idusers) { ?>
                                <small class="form-text text-muted">Leave blank to keep the current password.</small>
                            <?php } else { ?>
                                <small class="form-text text-muted">Choose a strong password for this account.</small>
                            <?php } ?>
                        </div>

                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Save User
                                </button>
                                <a href="/users" class="btn btn-outline-secondary">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow">
            <div class="card-header">
                <h5 class="mb-0">Tips</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Username:</strong> Must be unique
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Email:</strong> Valid email format required
                    </li>
                    <li class="mb-0">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Password:</strong> Use a strong password
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
