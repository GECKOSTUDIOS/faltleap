<div class="py-4">
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
            <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-door"></i></a></li>
            <li class="breadcrumb-item"><a href="/receiptctrl">Wareneingangskontrolle</a></li>
            <li class="breadcrumb-item active" aria-current="page">Schritt 2</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Kontrolle starten</h1>
            <p class="mb-0">Lieferant auswählen oder neu anlegen</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow mb-3">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mb-0"><i class="bi bi-truck me-2"></i>Lieferant</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Standort:</strong> <?= htmlspecialchars($this->data->location->location) ?> |
                    <strong>Datum:</strong> <?= date('d.m.Y', strtotime($this->data->date)) ?>
                </div>

                <form action="/receiptctrl/start/step3" method="POST" id="supplierForm">
                    <input type="hidden" name="id_location" value="<?= $this->data->location->idlba_locations ?>">
                    <input type="hidden" name="date" value="<?= htmlspecialchars($this->data->date) ?>">

                    <div class="mb-4">
                        <label class="form-label fw-bold">Lieferant auswählen</label>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="supplier_option" id="existing_supplier"
                                   value="existing" checked onchange="toggleSupplierInput()">
                            <label class="form-check-label" for="existing_supplier">
                                Bestehender Lieferant
                            </label>
                        </div>

                        <select class="form-select mb-3" id="id_supplier" name="id_supplier">
                            <option value="">-- Bitte wählen --</option>
                            <?php
                            $isFirst = true;
                            foreach ($this->data->suppliers as $supplier): ?>
                                <option value="<?= $supplier->idlba_suppliers ?>" <?= $isFirst ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($supplier->supplier) ?>
                                    <?php if ($supplier->is_bekannt): ?>
                                        (Bekannt)
                                    <?php endif; ?>
                                </option>
                            <?php
                            $isFirst = false;
                            endforeach; ?>
                        </select>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="supplier_option" id="new_supplier"
                                   value="new" onchange="toggleSupplierInput()">
                            <label class="form-check-label" for="new_supplier">
                                Neuer Lieferant
                            </label>
                        </div>

                        <input type="text" class="form-control" id="new_supplier_name" name="new_supplier"
                               placeholder="Name des neuen Lieferanten" disabled>
                        <div class="form-text">Ein neuer Lieferant wird automatisch dem System hinzugefügt.</div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="/receiptctrl/start/step1" class="btn btn-secondary">
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
                <ul class="mb-0 small">
                    <li class="mb-2">Wählen Sie einen <strong>bestehenden Lieferanten</strong> aus der Liste</li>
                    <li class="mb-2">Oder legen Sie einen <strong>neuen Lieferanten</strong> an</li>
                    <li><strong>Bekannte Lieferanten</strong> werden nicht automatisch kontrolliert</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function toggleSupplierInput() {
    const existingRadio = document.getElementById('existing_supplier');
    const supplierSelect = document.getElementById('id_supplier');
    const newSupplierInput = document.getElementById('new_supplier_name');

    if (existingRadio.checked) {
        supplierSelect.disabled = false;
        supplierSelect.required = true;
        newSupplierInput.disabled = true;
        newSupplierInput.required = false;
        newSupplierInput.value = '';
    } else {
        supplierSelect.disabled = true;
        supplierSelect.required = false;
        supplierSelect.value = '';
        newSupplierInput.disabled = false;
        newSupplierInput.required = true;
    }
}

// Form validation
document.getElementById('supplierForm').addEventListener('submit', function(e) {
    const existingRadio = document.getElementById('existing_supplier');
    const supplierSelect = document.getElementById('id_supplier');
    const newSupplierInput = document.getElementById('new_supplier_name');

    if (existingRadio.checked && !supplierSelect.value) {
        e.preventDefault();
        alert('Bitte wählen Sie einen Lieferanten aus.');
        return false;
    }

    if (!existingRadio.checked && !newSupplierInput.value.trim()) {
        e.preventDefault();
        alert('Bitte geben Sie einen Namen für den neuen Lieferanten ein.');
        return false;
    }
});
</script>
