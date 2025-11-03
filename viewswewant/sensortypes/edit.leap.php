<form method="post" action="/sensortypes/edit/<?php echo $this->data->idsensortypes ?? ''; ?>">
    <div class="form-group mb-3">
        <label for="sensortype">Sensortyp</label>
        <input type="text" class="form-control" id="sensortype" name="sensortype" value="<?php echo $this->data->sensortype ?? ''; ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Speichern</button>
    <a href="/sensortypes" class="btn btn-secondary">Abbrechen</a>
</form>
