<form method="post" action="/sensors/edit/<?php echo $this->data->sensor->idsensors ?? ''; ?>">
    <div class="form-group mb-3">
        <label for="name">Sensorname</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo $this->data->sensor->name ?? ''; ?>" required>
    </div>
    <div class="form-group mb-3">
        <label for="name">LORA-Kennung</label>
        <input type="text" class="form-control" id="name" name="loraidentifier" value="<?php echo $this->data->sensor->loraidentifier ?? ''; ?>" required>
    </div>
    <div class="form-group mb-3">
        <label for="idsensortypes">Sensortyp</label>
        <select class="form-control" id="idsensortypes" name="idsensortypes" required>
            <option value="">Wählen Sie einen Sensortyp</option>
            <?php foreach ($this->data->sensortypes as $type) { ?>
                <option value="<?php echo $type->idsensortypes; ?>"
                    <?php echo ($this->data->sensor->idsensortypes ?? '') == $type->idsensortypes ? 'selected' : ''; ?>>
                    <?php echo $type->sensortype; ?>
                </option>
            <?php } ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Speichern</button>
    <a href="/sensors" class="btn btn-secondary">Abbrechen</a>
</form>
