<div class="py-4">
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
            <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-door"></i></a></li>
            <li class="breadcrumb-item"><a href="/receiptctrl">Wareneingangskontrolle</a></li>
            <li class="breadcrumb-item active" aria-current="page">Schritt 1</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Kontrolle starten</h1>
            <p class="mb-0">Standort und Datum auswählen</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Standort und Datum</h5>
            </div>
            <div class="card-body">
                <form action="/receiptctrl/start/step2" method="POST">
                    <div class="mb-4">
                        <label for="id_location" class="form-label fw-bold">
                            Standort <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-lg" id="id_location" name="id_location" required>
                            <option value="">-- Bitte wählen --</option>
                            <?php
                            $isFirst = true;
                            foreach ($this->data->locations as $location): ?>
                                <option value="<?= $location->idlba_locations ?>" <?= $isFirst ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($location->location) ?>
                                </option>
                            <?php
                            $isFirst = false;
                            endforeach; ?>
                        </select>
                        <div class="form-text">Wählen Sie den Standort aus, an dem die Lieferung eingegangen ist.</div>
                    </div>

                    <div class="mb-4">
                        <label for="date" class="form-label fw-bold">
                            Datum <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control form-control-lg" id="date" name="date"
                               value="<?= htmlspecialchars($this->data->date) ?>" required>
                        <div class="form-text">Datum der Lieferung (Standard: heute)</div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="/receiptctrl" class="btn btn-secondary">
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

    <div class="col-12 col-lg-4 mt-4 mt-lg-0">
        <div class="card border-0 shadow bg-light">
            <div class="card-body">
                <h6 class="mb-3"><i class="bi bi-info-circle text-primary me-2"></i>Hinweise</h6>
                <ul class="mb-0 small">
                    <li class="mb-2">Wählen Sie den <strong>Standort</strong> aus, an dem die Lieferung angekommen ist</li>
                    <li class="mb-2">Das <strong>Datum</strong> ist normalerweise der heutige Tag</li>
                    <li>Im nächsten Schritt wählen Sie den <strong>Lieferanten</strong> aus</li>
                </ul>
            </div>
        </div>
    </div>
</div>
