<div class="py-4">
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
            <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-door"></i></a></li>
            <li class="breadcrumb-item active" aria-current="page">Wareneingangskontrolle</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Wareneingangskontrolle</h1>
            <p class="mb-0">Verwaltung der Lieferantenprüfungen und Kontrollen</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-6 mb-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h5 class="card-title mb-0">Kontrolle starten</h5>
                    </div>
                    <div class="icon-shape icon-md bg-primary text-white rounded">
                        <i class="bi bi-clipboard-check fs-4"></i>
                    </div>
                </div>
                <p class="card-text text-gray-700 mb-4">
                    Neue Wareneingangskontrolle für eine Lieferung durchführen. Standort und Lieferant auswählen.
                </p>
                <a href="/receiptctrl/start/step1" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Neue Kontrolle starten
                </a>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 mb-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h5 class="card-title mb-0">Auswertung</h5>
                    </div>
                    <div class="icon-shape icon-md bg-success text-white rounded">
                        <i class="bi bi-graph-up fs-4"></i>
                    </div>
                </div>
                <p class="card-text text-gray-700 mb-4">
                    Statistische Auswertung der durchgeführten Kontrollen mit Export-Funktionen.
                </p>
                <a href="/receiptctrl/evaluation" class="btn btn-success">
                    <i class="bi bi-bar-chart me-2"></i>Auswertung anzeigen
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mb-0">Informationen</h5>
            </div>
            <div class="card-body">
                <h6 class="mb-2"><i class="bi bi-info-circle text-primary me-2"></i>Ablauf einer Kontrolle</h6>
                <ol class="mb-3">
                    <li><strong>Standort und Datum</strong> auswählen</li>
                    <li><strong>Lieferant</strong> auswählen oder neu anlegen</li>
                    <li><strong>Kontrollentscheidung</strong> wird automatisch getroffen (25% Kontrollquote)</li>
                    <li><strong>Liefermenge</strong> eingeben (Kontrollmenge wird automatisch berechnet: 25%)</li>
                    <li><strong>Kontrollblatt</strong> ausdrucken mit Quadrant und Papierposition</li>
                </ol>

                <h6 class="mb-2"><i class="bi bi-info-circle text-primary me-2"></i>Kontrolllogik</h6>
                <ul class="mb-0">
                    <li><strong>Bekannte Lieferanten</strong> werden nicht kontrolliert</li>
                    <li><strong>Unbekannte Lieferanten</strong> werden nach Zufallstabelle geprüft</li>
                    <li><strong>Mindestquote 25%</strong> pro Standort und Monat wird sichergestellt</li>
                    <li><strong>Manuelle Kontrolle</strong> kann erzwungen werden</li>
                </ul>
            </div>
        </div>
    </div>
</div>
