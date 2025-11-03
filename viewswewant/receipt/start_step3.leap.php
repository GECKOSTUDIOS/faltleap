<div class="py-4">
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
            <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-door"></i></a></li>
            <li class="breadcrumb-item"><a href="/receiptctrl">Wareneingangskontrolle</a></li>
            <li class="breadcrumb-item active" aria-current="page">Schritt 3</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Kontrolle starten</h1>
            <p class="mb-0">Kontrollart festlegen</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow mb-3">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mb-0"><i class="bi bi-clipboard-check me-2"></i>Kontrollart</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Standort:</strong> <?= htmlspecialchars($this->data->location) ?> |
                    <strong>Datum:</strong> <?= date('d.m.Y', strtotime($this->data->date)) ?> |
                    <strong>Lieferant:</strong> <?= htmlspecialchars($this->data->supplier) ?>
                    <?php if ($this->data->is_bekannt): ?>
                        <span class="badge bg-success ms-2">Bekannt</span>
                    <?php endif; ?>
                </div>

                <?php if ($this->data->needs_control): ?>
                    <div class="alert alert-warning mb-4">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Kontrolle erforderlich!</strong>
                        <?php if (!$this->data->is_bekannt): ?>
                            Der Lieferant ist nicht als bekannt markiert.
                        <?php else: ?>
                            Eine Zufallskontrolle wurde ausgelöst oder die 25%-Quote muss erfüllt werden.
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-success mb-4">
                        <i class="bi bi-check-circle me-2"></i>
                        <strong>Keine Kontrolle erforderlich.</strong>
                    </div>
                <?php endif; ?>

                <form action="/receiptctrl/start/step4" method="POST">
                    <input type="hidden" name="id_location" value="<?= $this->data->id_location ?>">
                    <input type="hidden" name="date" value="<?= htmlspecialchars($this->data->date) ?>">
                    <input type="hidden" name="id_supplier" value="<?= $this->data->id_supplier ?>">
                    <input type="hidden" name="is_new" value="<?= $this->data->is_new ? '1' : '0' ?>">
                    <input type="hidden" name="needs_control" value="<?= $this->data->needs_control ? '1' : '0' ?>">

                    <?php if (!$this->data->needs_control): ?>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Abweichende Kontrollart (optional)</label>
                            <div class="form-text mb-3">Falls keine dieser Optionen zutrifft, einfach auf "Weiter" klicken.</div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="control_type" id="control_manipulation" value="MANIPULATION">
                                <label class="form-check-label" for="control_manipulation">
                                    <strong>Manipulationsverdacht</strong>
                                </label>
                                <div class="form-text ms-4">Kontrolle aufgrund von Verdacht auf Manipulation</div>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="control_type" id="control_optional" value="OPTIONAL">
                                <label class="form-check-label" for="control_optional">
                                    <strong>Zusätzliche Kontrolle</strong>
                                </label>
                                <div class="form-text ms-4">Freiwillige Zusatzkontrolle ohne besonderen Grund</div>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="control_type" id="control_restart" value="RESTART">
                                <label class="form-check-label" for="control_restart">
                                    <strong>Neuer Vorgang</strong>
                                </label>
                                <div class="form-text ms-4">Vorgang abbrechen und neu starten</div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex gap-2">
                        <a href="/receiptctrl/start/step2" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Zurück
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Weiter <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow bg-light">
            <div class="card-body">
                <h6 class="mb-3"><i class="bi bi-info-circle text-primary me-2"></i>Hinweise</h6>
                <dl class="mb-0 small">
                    <dt class="mb-1">Standard</dt>
                    <dd class="mb-3 text-muted">
                        Das System hat bereits automatisch entschieden, ob eine Kontrolle nötig ist
                    </dd>

                    <dt class="mb-1">Manipulationsverdacht</dt>
                    <dd class="mb-3 text-muted">
                        Erzwingt eine Kontrolle bei Verdacht auf manipulierte Ware
                    </dd>

                    <dt class="mb-1">Zusätzliche Kontrolle</dt>
                    <dd class="mb-3 text-muted">
                        Optional durchgeführte Kontrolle ohne speziellen Grund
                    </dd>

                    <dt class="mb-1">Neuer Vorgang</dt>
                    <dd class="mb-0 text-muted">
                        Startet den Eingabeprozess von vorne
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>
