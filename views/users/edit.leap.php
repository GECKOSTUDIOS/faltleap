<!-- edit view for Reverse Proxy in Bootstrap -->
<form method="post" action="">
    <div class="form-group">
        <label for="server_name">Username</label>
        <input type="text" class="form-control" id="server_name" name="username" value="<?php echo $this->data->username; ?>" required>
    </div>
    <div class="form-group">
        <label for="target">Email</label>
        <input type="email" class="form-control" id="target" name="email" value="<?php echo $this->data->email; ?>" required>
    </div>
    <div class="form-group">
        <label for="target">Password (leave blank to keep current)</label>
        <input type="password" class="form-control" id="target" name="password" value="">
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>