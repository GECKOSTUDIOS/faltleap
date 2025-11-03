<div class="py-4">
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
            <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-door"></i></a></li>
            <li class="breadcrumb-item"><a href="/receiptctrl">Wareneingangskontrolle</a></li>
            <li class="breadcrumb-item active" aria-current="page">Schritt 5</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Kontrolle starten</h1>
            <p class="mb-0">Liefermenge eingeben und abschließen</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow mb-3">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Liefermenge</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-4">
                    <strong>Standort:</strong> <?= htmlspecialchars($this->data->control->location) ?> |
                    <strong>Datum:</strong> <?= date('d.m.Y', strtotime($this->data->control->date)) ?> |
                    <strong>Lieferant:</strong> <?= htmlspecialchars($this->data->control->supplier) ?>
                </div>

                <?php if ($this->data->control->do_control): ?>
                    <div class="alert alert-warning mb-4">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Kontrolle erforderlich!</strong> Eine Prüfung muss durchgeführt werden.
                    </div>
                <?php else: ?>
                    <div class="alert alert-success mb-4">
                        <i class="bi bi-check-circle me-2"></i>
                        <strong>Keine Kontrolle erforderlich.</strong> Der Lieferant ist bekannt oder die Zufallskontrolle entfiel.
                    </div>
                <?php endif; ?>

                <form action="/receiptctrl/save-amount" method="POST">
                    <input type="hidden" name="id" value="<?= $this->data->control->idlba_controls ?>">

                    <div class="mb-4">
                        <label for="amount_delivered" class="form-label fw-bold">
                            Liefermenge (Gebinde) <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control form-control-lg" id="amount_delivered"
                               name="amount_delivered" min="1" required>
                        <div class="form-text">Anzahl der gelieferten Gebinde/Packstücke</div>
                    </div>

                    <div class="alert alert-secondary">
                        <i class="bi bi-calculator me-2"></i>
                        Die <strong>Kontrollmenge</strong> wird automatisch berechnet (25% der Liefermenge, aufgerundet).
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-check-circle me-2"></i>Kontrolle abschließen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow bg-light mb-3">
            <div class="card-body">
                <h6 class="mb-3"><i class="bi bi-info-circle text-primary me-2"></i>Kontrollinformationen</h6>
                <dl class="row mb-0 small">
                    <dt class="col-sm-6">Quadrant:</dt>
                    <dd class="col-sm-6"><strong><?= $this->data->control->quadrant ?></strong></dd>

                    <dt class="col-sm-6">Papierposition:</dt>
                    <dd class="col-sm-6">
                        <strong><?= $this->data->control->paper_placement === 'top' ? 'Oben' : 'Seite' ?></strong>
                    </dd>

                    <?php if ($this->data->control->is_new): ?>
                        <dt class="col-sm-12 mt-2">
                            <span class="badge bg-info">Neuer Lieferant</span>
                        </dt>
                    <?php endif; ?>

                    <?php if ($this->data->control->is_manipulated): ?>
                        <dt class="col-sm-12 mt-2">
                            <span class="badge bg-danger">Manipulation</span>
                        </dt>
                    <?php endif; ?>

                    <?php if ($this->data->control->is_optional): ?>
                        <dt class="col-sm-12 mt-2">
                            <span class="badge bg-warning text-dark">Optional</span>
                        </dt>
                    <?php endif; ?>
                </dl>
            </div>
        </div>

        <div class="card border-0 shadow bg-light">
            <div class="card-body">
                <h6 class="mb-3"><i class="bi bi-info-circle text-primary me-2"></i>Nächste Schritte</h6>
                <ul class="mb-0 small">
                    <li class="mb-2">Geben Sie die <strong>Liefermenge</strong> ein</li>
                    <li class="mb-2">Die <strong>Kontrollmenge</strong> wird automatisch berechnet</li>
                    <li>Nach dem Abschluss können Sie das <strong>Kontrollblatt</strong> ausdrucken</li>
                </ul>
            </div>
        </div>
    </div>
</div>
