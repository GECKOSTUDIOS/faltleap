<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Reinigungspunkte / Checkpoints</h2>
            <a href="/cleaning/checkpoint/edit" class="btn btn-primary d-inline-flex align-items-center">
                <i class="bi bi-plus-circle me-2"></i>
                Neuer Checkpoint
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
                                <th class="border-0">Name</th>
                                <th class="border-0">Beschreibung</th>
                                <th class="border-0">QR-Code</th>
                                <th class="border-0">Status</th>
                                <th class="border-0 text-end">Aktionen</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->data as $r) { ?>
                                <tr>
                                    <td class="fw-bold"><?php echo $r->idcleaning_checkpoints; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-pin-map-fill text-primary me-2 fs-5"></i>
                                            <span class="fw-normal"><?php echo htmlspecialchars($r->name); ?></span>
                                        </div>
                                    </td>
                                    <td class="fw-normal">
                                        <?php echo htmlspecialchars(substr($r->description ?? '', 0, 50)); ?>
                                        <?php if (strlen($r->description ?? '') > 50) echo '...'; ?>
                                    </td>
                                    <td>
                                        <code class="bg-light px-2 py-1 rounded"><?php echo htmlspecialchars($r->qr_code); ?></code>
                                    </td>
                                    <td>
                                        <?php if ($r->active) { ?>
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Aktiv
                                            </span>
                                        <?php } else { ?>
                                            <span class="badge bg-secondary">Inaktiv</span>
                                        <?php } ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <a class="btn btn-sm btn-info" href="/cleaning/checkpoint/qrcode/<?php echo $r->idcleaning_checkpoints; ?>" title="QR-Code anzeigen">
                                                <i class="bi bi-qr-code"></i>
                                            </a>
                                            <a class="btn btn-sm btn-primary" href="/cleaning/checkpoint/edit/<?php echo $r->idcleaning_checkpoints; ?>" title="Bearbeiten">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="#"
                                                class="btn btn-sm btn-danger"
                                                onclick="if(confirm('Diesen Checkpoint wirklich löschen?')) { window.location='/cleaning/checkpoint/delete/<?php echo $r->idcleaning_checkpoints; ?>'; } return false;"
                                                title="Löschen">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if (empty($this->data)) { ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                        Keine Checkpoints vorhanden. Klicken Sie auf "Neuer Checkpoint" um einen zu erstellen.
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
