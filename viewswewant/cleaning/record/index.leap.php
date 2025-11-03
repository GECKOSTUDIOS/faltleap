<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Reinigungsprotokolle</h2>
            <a href="/cleaning/record/record" class="btn btn-success d-inline-flex align-items-center" target="_blank">
                <i class="bi bi-qr-code-scan me-2"></i>
                Reinigung erfassen
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
                                <th class="border-0">Checkpoint</th>
                                <th class="border-0">Benutzer</th>
                                <th class="border-0">Zeitpunkt</th>
                                <th class="border-0">Notizen</th>
                                <th class="border-0 text-end">Unterschrift</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->data as $r) { ?>
                                <tr>
                                    <td class="fw-bold"><?php echo $r->idcleaning_records; ?></td>
                                    <td>
                                        <i class="bi bi-pin-map text-primary me-2"></i>
                                        <?php echo htmlspecialchars($r->checkpoint_name ?? 'Unbekannt'); ?>
                                    </td>
                                    <td class="fw-normal">
                                        <?php echo htmlspecialchars($r->username ?? 'Anonym'); ?>
                                    </td>
                                    <td>
                                        <span class="text-muted">
                                            <?php echo date('d.m.Y H:i', strtotime($r->cleaning_time)); ?> Uhr
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $notes = $r->notes ?? '';
                                        if (strlen($notes) > 30) {
                                            echo htmlspecialchars(substr($notes, 0, 30)) . '...';
                                        } else {
                                            echo htmlspecialchars($notes);
                                        }
                                        ?>
                                    </td>
                                    <td class="text-end">
                                        <a href="/cleaning/record/signature/<?php echo $r->idcleaning_records; ?>"
                                           class="btn btn-sm btn-outline-primary"
                                           target="_blank"
                                           title="Unterschrift anzeigen">
                                            <i class="bi bi-pen"></i>
                                            Anzeigen
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if (empty($this->data)) { ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                        Keine Reinigungsprotokolle vorhanden.
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
