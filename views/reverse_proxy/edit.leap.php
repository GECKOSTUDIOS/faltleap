<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-0"><?php echo $this->data->idreverseproxies ? 'Edit Reverse Proxy' : 'Add New Reverse Proxy'; ?></h2>
                <p class="text-muted mb-0">
                    <?php echo $this->data->idreverseproxies ? 'Update the proxy configuration below' : 'Create a new reverse proxy configuration'; ?>
                </p>
            </div>
            <a href="/manage" class="btn btn-secondary d-inline-flex align-items-center">
                <i class="bi bi-arrow-left me-2"></i>
                Back to Proxies
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow">
            <div class="card-header">
                <h5 class="mb-0">Proxy Configuration</h5>
            </div>
            <div class="card-body">
                <form method="post" action="/manage/edit/<?php echo $this->data->idreverseproxies ?? ''; ?>">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="server_name" class="form-label">Server Name (URL)</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-globe"></i>
                                </span>
                                <input type="text"
                                       class="form-control"
                                       id="server_name"
                                       name="server_name"
                                       value="<?php echo $this->data->server_name ?? ''; ?>"
                                       placeholder="example.com"
                                       required>
                            </div>
                            <small class="form-text text-muted">The domain name that will be used to access this proxy.</small>
                        </div>

                        <div class="col-md-8 mb-3">
                            <label for="target_address" class="form-label">Target IP Address</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-hdd-network"></i>
                                </span>
                                <input type="text"
                                       class="form-control"
                                       id="target_address"
                                       name="target_address"
                                       value="<?php echo $this->data->target_address ?? ''; ?>"
                                       placeholder="192.168.1.100"
                                       required>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="target_port" class="form-label">Target Port</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-plug"></i>
                                </span>
                                <input type="number"
                                       class="form-control"
                                       id="target_port"
                                       name="target_port"
                                       value="<?php echo $this->data->target_port ?? ''; ?>"
                                       placeholder="8080"
                                       required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="generate_ssl" class="form-label">Generate SSL Certificate</label>
                            <select class="form-control" id="generate_ssl" name="generate_ssl">
                                <option value="true" <?php if (isset($this->data->generate_ssl) && $this->data->generate_ssl == true) echo 'selected'; ?>>Yes</option>
                                <option value="false" <?php if (isset($this->data->generate_ssl) && $this->data->generate_ssl == false) echo 'selected'; ?>>No</option>
                            </select>
                            <small class="form-text text-muted">Automatically obtain and configure SSL certificate.</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="is_websocket" class="form-label">WebSocket Support</label>
                            <select class="form-control" id="is_websocket" name="is_websocket">
                                <option value="true" <?php if (isset($this->data->is_websocket) && $this->data->is_websocket == true) echo 'selected'; ?>>Yes</option>
                                <option value="false" <?php if (isset($this->data->is_websocket) && $this->data->is_websocket == false) echo 'selected'; ?>>No</option>
                            </select>
                            <small class="form-text text-muted">Enable WebSocket protocol support.</small>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="custom_configs" class="form-label">Custom Configurations</label>
                            <textarea class="form-control"
                                      id="custom_configs"
                                      name="custom_configs"
                                      rows="5"
                                      placeholder="Add custom nginx configuration here..."><?php echo $this->data->custom_configs ?? ''; ?></textarea>
                            <small class="form-text text-muted">Optional custom nginx directives for advanced configuration.</small>
                        </div>

                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Save Proxy
                                </button>
                                <a href="/manage" class="btn btn-outline-secondary">
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
        <div class="card border-0 shadow mb-3">
            <div class="card-header">
                <h5 class="mb-0">Configuration Tips</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Server Name:</strong> Use a valid domain name
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Target:</strong> IP address of your backend service
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>SSL:</strong> Recommended for production
                    </li>
                    <li class="mb-0">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>WebSocket:</strong> Enable for real-time apps
                    </li>
                </ul>
            </div>
        </div>

        <div class="card border-0 shadow border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Important</h5>
            </div>
            <div class="card-body">
                <p class="mb-0 small">
                    After saving, you need to deploy the configuration for it to take effect.
                    Click the deploy button from the proxy list.
                </p>
            </div>
        </div>
    </div>
</div>
