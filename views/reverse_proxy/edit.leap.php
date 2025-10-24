<!-- edit view for Reverse Proxy in Bootstrap -->
<form method="post" action="">
    <div class="form-group">
        <label for="server_name">URL (inbound)</label>
        <input type="text" class="form-control" id="server_name" name="server_name" value="<?php echo $this->data->server_name; ?>" required>
    </div>
    <div class="form-group">
        <label for="target">Target IP</label>
        <input type="text" class="form-control" id="target" name="target_address" value="<?php echo $this->data->target_address; ?>" required>
    </div>
    <div class="form-group">
        <label for="target">Target Port</label>
        <input type="text" class="form-control" id="target" name="target_port" value="<?php echo $this->data->target_port; ?>" required>
    </div>
    <div class="form-group">
        <label for="ssl">Generate SSL?</label>
        <select class="form-control" id="ssl" name="generate_ssl">
            <option value="true" <?php if ($this->data->generate_ssl == 'true') echo 'selected'; ?>>Yes</option>
            <option value="false" <?php if (!$this->data->generate_ssl == 'false') echo 'selected'; ?>>No</option>
        </select>
    </div>
    <div class="form-group">
        <label for="websocket">Is Websocket?</label>
        <select class="form-control" id="websocket" name="is_websocket">
            <option value="true" <?php if ($this->data->is_websocket == 'true') echo 'selected'; ?>>Yes</option>
            <option value="false" <?php if ($this->data->is_websocket == 'false') echo 'selected'; ?>>No</option>
        </select>
    </div>
    <div class="form-group">
        <label for="custom_configs">Custom Configurations</label>
        <textarea class="form-control" id="custom_configs" name="custom_configs"><?php echo $this->data->custom_configs; ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>