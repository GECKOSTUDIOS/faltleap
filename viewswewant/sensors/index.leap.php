<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
             <h2 class="h4 mb-0">Sensoren-Verwaltung</h2>
             <a href="/sensors/edit" class="btn btn-primary d-inline-flex align-items-center">
                 <i class="bi bi-plus-circle me-2"></i>
                 Neuen Sensor hinzufügen
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
                                 <th class="border-0">LORA-Kennung</th>
                                 <th class="border-0">Sensortyp</th>
                                 <th class="border-0 text-end">Aktionen</th>
                             </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->data as $r) { ?>
                                <tr>
                                    <td class="fw-bold"><?php echo $r->idsensors; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                <i class="bi bi-cpu text-white"></i>
                                            </div>
                                            <span class="fw-normal"><?php echo htmlspecialchars($r->name); ?></span>
                                        </div>
                                    </td>
                                    <td class="fw-normal">
                                        <code><?php echo htmlspecialchars($r->loraidentifier ?? 'N/A'); ?></code>
                                    </td>
                                    <td>
                                         <span class="badge bg-info"><?php echo htmlspecialchars($r->sensortypes->sensortype ?? 'Nicht zugewiesen'); ?></span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                             <a class="btn btn-sm btn-primary" href="/sensors/edit/<?php echo $r->idsensors; ?>" title="Bearbeiten">
                                                 <i class="bi bi-pencil"></i>
                                             </a>
                                             <a href="#"
                                                 class="btn btn-sm btn-danger"
                                                 onclick="if(confirm('Sind Sie sicher, dass Sie diesen Sensor löschen möchten?')) { window.location='/sensors/delete/<?php echo $r->idsensors; ?>'; } return false;"
                                                 title="Löschen">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if (empty($this->data)) { ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                         Keine Sensoren gefunden. Klicken Sie auf "Neuen Sensor hinzufügen", um einen zu erstellen.
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
