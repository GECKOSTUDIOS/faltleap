<div class="py-4">
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
            <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-door"></i></a></li>
            <li class="breadcrumb-item"><a href="/receiptctrl">Wareneingangskontrolle</a></li>
            <li class="breadcrumb-item active" aria-current="page">Auswertung</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Kontrollen Auswertung</h1>
            <p class="mb-0">Statistische Auswertung der Wareneingangskontrolle</p>
        </div>
    </div>
</div>

<!-- Search Form -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mb-0"><i class="bi bi-search me-2"></i>Suchfilter</h5>
            </div>
            <div class="card-body">
                <form action="/receiptctrl/evaluation" method="POST" id="searchForm">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="date_from" class="form-label">Von Datum</label>
                            <input type="date" class="form-control" id="date_from" name="date_from"
                                   value="<?= htmlspecialchars($this->data->dateFrom ?? '') ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="date_to" class="form-label">Bis Datum</label>
                            <input type="date" class="form-control" id="date_to" name="date_to"
                                   value="<?= htmlspecialchars($this->data->dateTo ?? '') ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="id_location" class="form-label">Standort</label>
                            <select class="form-select" id="id_location" name="id_location">
                                <option value="">Alle Standorte</option>
                                <?php foreach ($this->data->locations as $location): ?>
                                    <option value="<?= $location->idlba_locations ?>"
                                        <?= $this->data->idLocation == $location->idlba_locations ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($location->location) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-2"></i>Suchen
                        </button>
                        <?php if (!empty($this->data->rows)): ?>
                            <button type="button" class="btn btn-success" onclick="exportCsv()">
                                <i class="bi bi-file-earmark-spreadsheet me-2"></i>CSV Export
                            </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Statistics -->
<?php if ($this->data->facts): ?>
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card border-0 shadow">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Kontrollstatistik (Anzahl)</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="text-muted small">Gesamt</div>
                        <div class="fs-4 fw-bold"><?= $this->data->facts->gesamt ?></div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="text-muted small">Unbekannt</div>
                        <div class="fs-4 fw-bold"><?= $this->data->facts->unbekannt ?></div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="text-muted small">Kontrollen</div>
                        <div class="fs-4 fw-bold text-success"><?= $this->data->facts->kontrolle ?></div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="text-muted small">Kontrollquote</div>
                        <div class="fs-4 fw-bold text-primary"><?= $this->data->facts->quote ?>%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($this->data->facts2): ?>
    <div class="col-md-6 mb-3">
        <div class="card border-0 shadow">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="bi bi-box-seam me-2"></i>Mengenstatistik</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="text-muted small">Liefermenge</div>
                        <div class="fs-4 fw-bold"><?= $this->data->facts2->liefermenge ?></div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="text-muted small">Kontrollmenge</div>
                        <div class="fs-4 fw-bold text-success"><?= $this->data->facts2->kontrollmenge ?></div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="text-muted small">Kontrollquote (Menge)</div>
                        <div class="fs-4 fw-bold text-primary"><?= $this->data->facts2->quote ?>%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Results Table -->
<?php if (!empty($this->data->rows)): ?>
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mb-0"><i class="bi bi-table me-2"></i>Kontrollergebnisse (<?= count($this->data->rows) ?>)</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Datum</th>
                                <th>Standort</th>
                                <th>Lieferant</th>
                                <th>Kontrolleur</th>
                                <th class="text-center">Bekannt</th>
                                <th class="text-center">Neu</th>
                                <th class="text-center">Kontrolle</th>
                                <th class="text-end">Liefermenge</th>
                                <th class="text-end">Kontrollmenge</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->data->rows as $row): ?>
                            <tr>
                                <td><?= date('d.m.Y', strtotime($row->date)) ?></td>
                                <td><?= htmlspecialchars($row->location) ?></td>
                                <td><?= htmlspecialchars($row->supplier) ?></td>
                                <td><?= htmlspecialchars($row->name) ?></td>
                                <td class="text-center">
                                    <?php if ($row->is_bekannt): ?>
                                        <span class="badge bg-success">Ja</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($row->is_new): ?>
                                        <span class="badge bg-info">Neu</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($row->do_control): ?>
                                        <span class="badge bg-warning text-dark">Ja</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nein</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end"><?= $row->amount_delivered ?? '-' ?></td>
                                <td class="text-end"><?= $row->amount_control ?? '-' ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php elseif ($_POST['date_from'] ?? false): ?>
<div class="alert alert-info">
    <i class="bi bi-info-circle me-2"></i>Keine Kontrollen im ausgewählten Zeitraum gefunden.
</div>
<?php endif; ?>

<script>
function exportCsv() {
    // Create a temporary form to submit CSV export via POST
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/receiptctrl/evaluation/csv';

    // Copy values from search form
    const dateFrom = document.getElementById('date_from').value;
    const dateTo = document.getElementById('date_to').value;
    const idLocation = document.getElementById('id_location').value;

    // Add hidden fields
    const fields = {
        'date_from': dateFrom,
        'date_to': dateTo,
        'id_location': idLocation
    };

    for (const [key, value] of Object.entries(fields)) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
}
</script>
