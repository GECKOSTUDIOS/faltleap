<div class="row">
    <div class="col-12 col-xl-8">
        <div class="card border-0 shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-pin-map me-2"></i>
                    <?php echo $this->data->idcleaning_checkpoints ? 'Checkpoint bearbeiten' : 'Neuer Checkpoint'; ?>
                </h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <?php if (isset($this->data->idcleaning_checkpoints) && $this->data->idcleaning_checkpoints): ?>
                    <input type="hidden" name="idcleaning_checkpoints" value="<?php echo htmlspecialchars($this->data->idcleaning_checkpoints); ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="<?php echo htmlspecialchars($this->data->name ?? ''); ?>" required
                            placeholder="z.B. Eingangsbereich">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Beschreibung</label>
                        <textarea class="form-control" id="description" name="description" rows="3"
                            placeholder="Beschreiben Sie den Reinigungspunkt..."><?php echo htmlspecialchars($this->data->description ?? ''); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="qr_code" class="form-label">QR-Code Identifier</label>
                        <input type="text" class="form-control" id="qr_code" name="qr_code"
                            value="<?php echo htmlspecialchars($this->data->qr_code ?? ''); ?>" required
                            placeholder="z.B. CP001">
                        <small class="form-text text-muted">Eindeutiger Code für den QR-Code</small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="active" name="active" value="1"
                                <?php echo (!isset($this->data->active) || $this->data->active) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="active">
                                Aktiv
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="/cleaning/checkpoint" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            Zurück
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>
                            Speichern
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
