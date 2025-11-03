<div class="row">
    <div class="col-12 mb-4">
        <h2 class="h4">Dashboard-Übersicht</h2>
        <p class="text-gray-600">Willkommen im CargoCrew Sensorboard Admin-Panel</p>
    </div>
</div>

<div class="row">
    <!-- Users Card -->
    <div class="col-12 col-sm-6 col-xl-4 mb-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <div class="row d-block d-xl-flex align-items-center">
                    <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                        <div class="icon-shape icon-shape-primary rounded me-4 me-sm-0">
                            <i class="bi bi-people fs-2"></i>
                        </div>
                    </div>
                    <div class="col-12 col-xl-7 px-xl-0">
                        <div class="d-none d-sm-block">
                            <h2 class="h5">Users</h2>
                            <h3 class="fw-extrabold mb-1"><?php echo $this->data->usersCount ?? 0; ?></h3>
                             <div class="small mt-2">
                                 <a href="/users" class="text-primary">Benutzer verwalten</a>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sensors Card -->
    <div class="col-12 col-sm-6 col-xl-4 mb-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <div class="row d-block d-xl-flex align-items-center">
                    <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                        <div class="icon-shape icon-shape-secondary rounded me-4 me-sm-0">
                            <i class="bi bi-cpu fs-2"></i>
                        </div>
                    </div>
                    <div class="col-12 col-xl-7 px-xl-0">
                        <div class="d-none d-sm-block">
                            <h2 class="h5">Sensors</h2>
                            <h3 class="fw-extrabold mb-1"><?php echo $this->data->sensorsCount ?? 0; ?></h3>
                             <div class="small mt-2">
                                 <a href="/sensors" class="text-secondary">Sensoren verwalten</a>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sensor Types Card -->
    <div class="col-12 col-sm-6 col-xl-4 mb-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <div class="row d-block d-xl-flex align-items-center">
                    <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                        <div class="icon-shape icon-shape-tertiary rounded me-4 me-sm-0">
                            <i class="bi bi-tags fs-2"></i>
                        </div>
                    </div>
                    <div class="col-12 col-xl-7 px-xl-0">
                        <div class="d-none d-sm-block">
                            <h2 class="h5">Sensor Types</h2>
                            <h3 class="fw-extrabold mb-1"><?php echo $this->data->sensorTypesCount ?? 0; ?></h3>
                             <div class="small mt-2">
                                 <a href="/sensortypes" class="text-tertiary">Typen verwalten</a>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Receipt Control Card -->
    <div class="col-12 col-sm-6 col-xl-4 mb-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <div class="row d-block d-xl-flex align-items-center">
                    <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                        <div class="icon-shape icon-shape-success rounded me-4 me-sm-0">
                            <i class="bi bi-box-seam fs-2"></i>
                        </div>
                    </div>
                    <div class="col-12 col-xl-7 px-xl-0">
                        <div class="d-none d-sm-block">
                            <h2 class="h5">Warenkontrolle</h2>
                            <h3 class="fw-extrabold mb-1"><?php echo $this->data->receiptControlCount ?? 0; ?></h3>
                             <div class="small mt-2">
                                 <a href="/receiptctrl" class="text-success">Kontrolle starten</a>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Latest Logs Section -->
<div class="row">
    <!-- Door Logs -->
    <div class="col-12 col-xl-6 mb-4">
        <div class="card border-0 shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                         <h2 class="fs-5 fw-bold mb-0">
                             <i class="bi bi-door-open me-2"></i>
                             Türprotokolle
                         </h2>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($this->data->doorLogs)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                             <tr>
                                 <th class="border-0">ID</th>
                                 <th class="border-0">RFID</th>
                                 <th class="border-0">Sensor-ID</th>
                                 <th class="border-0">Zeitstempel</th>
                             </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->data->doorLogs as $log): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($log->iddoorlogs); ?></td>
                                <td><span class="badge bg-primary"><?php echo htmlspecialchars($log->rfid ?? 'N/A'); ?></span></td>
                                <td><?php echo htmlspecialchars($log->idsensors); ?></td>
                                <td class="text-muted small">
                                    <?php
                                        $date = new DateTime($log->createdat);
                                echo $date->format('Y-m-d H:i:s');
                                ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="p-4 text-center text-muted">
                    <i class="bi bi-inbox fs-1"></i>
                     <p class="mb-0 mt-2">Keine Türprotokolle verfügbar</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Heat Sensor Logs Chart -->
    <div class="col-12 col-xl-6 mb-4">
        <div class="card border-0 shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                         <h2 class="fs-5 fw-bold mb-0">
                             <i class="bi bi-thermometer-half me-2"></i>
                             Temperaturmessungen
                         </h2>
                    </div>
                    <div class="col-auto">
                        <a href="/heatsensors" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-list-ul me-1"></i>
                            Alle Protokolle
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($this->data->heatLogs)): ?>
                <canvas id="heatSensorChart" style="max-height: 300px;"></canvas>
                <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
                <script>
                (function() {
                    const ctx = document.getElementById('heatSensorChart').getContext('2d');

                    <?php
                    // Organize logs by sensor ID and aggregate into 5-minute intervals
                    $sensorData = [];
                    $allTimestamps = [];

                    // Reverse to get chronological order (oldest to newest)
                    $reversedLogs = array_reverse($this->data->heatLogs);

                    // Group readings by sensor and 5-minute intervals
                    $groupedData = [];

                    foreach ($reversedLogs as $log) {
                        $sensorId = $log->idsensors;
                        $date = new DateTime($log->createdat);

                        // Round down to nearest 5-minute interval
                        $minute = (int)$date->format('i');
                        $roundedMinute = floor($minute / 5) * 5;
                        $date->setTime(
                            (int)$date->format('H'),
                            $roundedMinute,
                            0
                        );
                        $timestamp = $date->format('H:i');

                        if (!isset($groupedData[$sensorId])) {
                            $groupedData[$sensorId] = [];
                        }

                        if (!isset($groupedData[$sensorId][$timestamp])) {
                            $groupedData[$sensorId][$timestamp] = [
                                'sum' => 0,
                                'count' => 0
                            ];
                        }

                        // Accumulate values for averaging
                        $groupedData[$sensorId][$timestamp]['sum'] += floatval($log->val ?? 0);
                        $groupedData[$sensorId][$timestamp]['count']++;
                    }

                    // Calculate averages and build final dataset
                    foreach ($groupedData as $sensorId => $intervals) {
                        $sensorData[$sensorId] = [];

                        foreach ($intervals as $timestamp => $data) {
                            $average = $data['sum'] / $data['count'];
                            $sensorData[$sensorId][$timestamp] = round($average, 1);

                            if (!in_array($timestamp, $allTimestamps)) {
                                $allTimestamps[] = $timestamp;
                            }
                        }
                    }

                    // Sort timestamps chronologically
                    sort($allTimestamps);
                    ?>

                    // Define colors for different sensors
                    const colors = [
                        { border: 'rgb(220, 53, 69)', bg: 'rgba(220, 53, 69, 0.1)' },    // Red
                        { border: 'rgb(13, 110, 253)', bg: 'rgba(13, 110, 253, 0.1)' },  // Blue
                        { border: 'rgb(25, 135, 84)', bg: 'rgba(25, 135, 84, 0.1)' },    // Green
                        { border: 'rgb(255, 193, 7)', bg: 'rgba(255, 193, 7, 0.1)' },    // Yellow
                        { border: 'rgb(111, 66, 193)', bg: 'rgba(111, 66, 193, 0.1)' },  // Purple
                    ];

                    const labels = <?php echo json_encode($allTimestamps); ?>;
                    const datasets = [];
                    const sensorNames = <?php echo json_encode($this->data->sensorNames); ?>;

                    <?php
                    $colorIndex = 0;
                    foreach ($sensorData as $sensorId => $readings):
                        ?>
                    datasets.push({
                        label: sensorNames[<?php echo $sensorId; ?>] || 'Sensor <?php echo htmlspecialchars($sensorId); ?>',
                        data: [
                            <?php
                                foreach ($allTimestamps as $timestamp) {
                                    if (isset($sensorData[$sensorId][$timestamp])) {
                                        echo $sensorData[$sensorId][$timestamp];
                                    } else {
                                        echo 'null';
                                    }
                                    echo ',';
                                }
                        ?>
                        ],
                        borderColor: colors[<?php echo $colorIndex; ?>].border,
                        backgroundColor: colors[<?php echo $colorIndex; ?>].bg,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        spanGaps: true
                    });
                    <?php
                    $colorIndex++;
                    endforeach;
                            ?>

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: datasets
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top'
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: false,
                                    ticks: {
                                        callback: function(value) {
                                            return value + '°C';
                                        }
                                    }
                                },
                                x: {
                                    ticks: {
                                        maxRotation: 45,
                                        minRotation: 45,
                                        maxTicksLimit: 15
                                    }
                                }
                            },
                            interaction: {
                                mode: 'nearest',
                                axis: 'x',
                                intersect: false
                            }
                        }
                    });
                })();
                </script>
                <?php else: ?>
                <div class="p-4 text-center text-muted">
                    <i class="bi bi-inbox fs-1"></i>
                     <p class="mb-0 mt-2">Keine Temperaturdaten verfügbar</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- Cleaning Status Section -->
<?php if (!empty($this->data->cleaningStatus)): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow">
            <div class="card-header bg-gradient-primary">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="fs-5 fw-bold mb-0">
                            <i class="bi bi-droplet me-2"></i>
                            Heutiger Reinigungsstatus
                        </h2>
                    </div>
                    <div class="col-auto">
                        <a href="/cleaning/schedule" class="btn btn-sm btn-light">
                            <i class="bi bi-calendar3 me-1"></i>
                            Plan anzeigen
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3">
                    <?php foreach ($this->data->cleaningStatus as $status): ?>
                        <div class="cleaning-status-item p-3 border rounded text-center"
                             style="min-width: 120px; <?php echo $status['completed'] ? 'background-color: #d4edda; border-color: #c3e6cb !important;' : 'background-color: #f8d7da; border-color: #f5c6cb !important;'; ?>">
                            <div class="mb-2">
                                <?php if ($status['completed']): ?>
                                    <i class="bi bi-check-circle-fill text-success" style="font-size: 2.5rem;"></i>
                                <?php else: ?>
                                    <i class="bi bi-x-circle-fill text-danger" style="font-size: 2.5rem;"></i>
                                <?php endif; ?>
                            </div>
                            <div class="fw-bold"><?php echo htmlspecialchars($status['time']); ?> Uhr</div>
                            <div class="small text-muted mt-1">
                                <?php echo $status['completed'] ? 'Erledigt' : 'Ausstehend'; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="mt-3 text-center">
                    <a href="/cleaning/record/record" class="btn btn-success" target="_blank">
                        <i class="bi bi-qr-code-scan me-2"></i>
                        Reinigung erfassen
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- 14-Day Cleaning Overview -->
<?php if (!empty($this->data->cleaningOverview['days'])): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="fs-5 fw-bold mb-0">
                            <i class="bi bi-calendar-week me-2"></i>
                            Reinigungsübersicht 
                        </h2>
                    </div>
                    <div class="col-auto">
                        <a href="/cleaning/record" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-list-ul me-1"></i>
                            Alle Protokolle
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="min-width: 90px;">Datum</th>
                                <?php foreach ($this->data->cleaningOverview['schedule_times'] as $time): ?>
                                    <th class="text-center" style="min-width: 80px;"><?php echo htmlspecialchars($time); ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->data->cleaningOverview['days'] as $day): ?>
                                <tr <?php echo $day['is_today'] ? 'class="table-info"' : ''; ?>>
                                    <td class="text-center fw-bold align-middle">
                                        <div><?php echo htmlspecialchars($day['day_name']); ?></div>
                                        <div class="small text-muted"><?php echo htmlspecialchars($day['formatted_date']); ?></div>
                                    </td>
                                    <?php foreach ($this->data->cleaningOverview['schedule_times'] as $time): ?>
                                        <td class="text-center p-2 align-middle" style="background-color: <?php
                                            if ($day['time_slots'][$time] === null) {
                                                echo '#f8f9fa'; // Light gray - not scheduled
                                            } elseif (!$day['time_slots'][$time][0]['has_passed']) {
                                                echo '#ffffff'; // White - not yet due
                                            } else {
                                                // Check completion status
                                                $allCompleted = true;
                                                foreach ($day['time_slots'][$time] as $cp) {
                                                    if (!$cp['completed']) {
                                                        $allCompleted = false;
                                                        break;
                                                    }
                                                }
                                                echo $allCompleted ? '#d4edda' : '#f8d7da'; // Green if all done, red if missed
                                            }
                                        ?>;">
                                            <?php if ($day['time_slots'][$time] === null): ?>
                                                <span class="text-muted">-</span>
                                            <?php elseif (!$day['time_slots'][$time][0]['has_passed']): ?>
                                                <i class="bi bi-clock text-muted" style="font-size: 1rem;"></i>
                                            <?php else: ?>
                                                <?php
                                                $completed = 0;
                                                $total = count($day['time_slots'][$time]);
                                                foreach ($day['time_slots'][$time] as $checkpoint):
                                                    if ($checkpoint['completed']) {
                                                        $completed++;
                                                    }
                                                endforeach;
                                                ?>
                                                <div>
                                                    <?php if ($completed === $total): ?>
                                                        <i class="bi bi-check-circle-fill text-success" style="font-size: 1.2rem;"></i>
                                                    <?php else: ?>
                                                        <i class="bi bi-x-circle-fill text-danger" style="font-size: 1.2rem;"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="small mt-1">
                                                    <span class="badge <?php echo $completed === $total ? 'bg-success' : 'bg-danger'; ?>">
                                                        <?php echo $completed; ?>/<?php echo $total; ?>
                                                    </span>
                                                </div>
                                                <?php if ($completed < $total): ?>
                                                    <div class="small text-muted mt-1" style="font-size: 0.7rem;">
                                                        <?php foreach ($day['time_slots'][$time] as $cp): ?>
                                                            <?php if (!$cp['completed']): ?>
                                                                <div><?php echo htmlspecialchars(substr($cp['checkpoint_name'], 0, 15)); ?></div>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="p-3 bg-light border-top">
                    <div class="d-flex flex-wrap gap-3 small">
                        <div><i class="bi bi-check-circle-fill text-success me-1"></i> Alle Checkpoints erledigt</div>
                        <div><i class="bi bi-x-circle-fill text-danger me-1"></i> Mindestens ein Checkpoint fehlt</div>
                        <div><i class="bi bi-clock text-muted me-1"></i> Noch nicht fällig</div>
                        <div><span class="badge bg-secondary me-1">-</span> Nicht geplant</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-12 col-xl-8 mb-4">
        <div class="card border-0 shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                         <h2 class="fs-5 fw-bold mb-0">Schnellaktionen</h2>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                         <a href="/users/edit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center">
                             <i class="bi bi-person-plus me-2"></i>
                             Neuen Benutzer hinzufügen
                         </a>
                    </div>
                    <div class="col-md-6">
                         <a href="/sensors/edit" class="btn btn-secondary w-100 d-flex align-items-center justify-content-center">
                             <i class="bi bi-plus-circle me-2"></i>
                             Neuen Sensor hinzufügen
                         </a>
                    </div>
                    <div class="col-md-6">
                         <a href="/sensortypes/edit" class="btn btn-tertiary w-100 d-flex align-items-center justify-content-center">
                             <i class="bi bi-tag me-2"></i>
                             Sensortyp hinzufügen
                         </a>
                    </div>
                    <div class="col-md-6">
                         <a href="/users" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
                             <i class="bi bi-list-ul me-2"></i>
                             Alle Benutzer anzeigen
                         </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


