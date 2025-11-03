<div class="row">
    <div class="col-12 mb-4">
        <h2 class="h4">Wärmesensor-Analytik</h2>
        <p class="text-gray-600">Kühlzellentemperatur</p>
    </div>
</div>

<!-- Date Filter Form -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow">
            <div class="card-header">
                <h5 class="mb-0">Nach Datumsbereich filtern</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="/heatsensors" class="row g-3">
                    <div class="col-md-5">
                        <label for="datefrom" class="form-label">Von Datum & Uhrzeit</label>
                        <input type="datetime-local" class="form-control" id="datefrom" name="datefrom"
                               value="<?php echo date('Y-m-d\TH:i', strtotime($this->data->dateFrom)); ?>" required>
                    </div>
                    <div class="col-md-5">
                        <label for="dateto" class="form-label">Bis Datum & Uhrzeit</label>
                        <input type="datetime-local" class="form-control" id="dateto" name="dateto"
                               value="<?php echo date('Y-m-d\TH:i', strtotime($this->data->dateTo)); ?>" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i>
                            Filtern
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Chart Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="fs-5 fw-bold mb-0">
                            <i class="bi bi-graph-up me-2"></i>
                            Temperaturdiagramm
                        </h2>
                    </div>
                    <div class="col-auto">
                        <small class="text-muted">
                            <?php echo count($this->data->intervalData); ?> Intervalle (15 Min) von
                            <?php echo date('M j, Y H:i', strtotime($this->data->dateFrom)); ?> to
                            <?php echo date('M j, Y H:i', strtotime($this->data->dateTo)); ?>
                        </small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($this->data->intervalData)): ?>
                <canvas id="heatSensorChart" style="max-height: 400px;"></canvas>
                <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
                <script>
                (function() {
                    const ctx = document.getElementById('heatSensorChart').getContext('2d');

                    <?php
                    // Prepare data for chart
                    $chartTimestamps = [];
                    $chartData = [];

                    // Build timestamp labels and data arrays for each sensor
                    foreach ($this->data->intervalData as $timestamp => $sensors) {
                        $date = new DateTime($timestamp);
                        $chartTimestamps[] = $date->format('d.m.y H:i');
                    }

                    // Build data arrays for each sensor
                    foreach ($this->data->sensorIds as $sensorId) {
                        $chartData[$sensorId] = [];
                        foreach ($this->data->intervalData as $timestamp => $sensors) {
                            $chartData[$sensorId][] = isset($sensors[$sensorId]) ? $sensors[$sensorId] : null;
                        }
                    }
                    ?>

                    // Define colors for different sensors
                    const colors = [
                        { border: 'rgb(220, 53, 69)', bg: 'rgba(220, 53, 69, 0.1)' },    // Red
                        { border: 'rgb(13, 110, 253)', bg: 'rgba(13, 110, 253, 0.1)' },  // Blue
                        { border: 'rgb(25, 135, 84)', bg: 'rgba(25, 135, 84, 0.1)' },    // Green
                        { border: 'rgb(255, 193, 7)', bg: 'rgba(255, 193, 7, 0.1)' },    // Yellow
                        { border: 'rgb(111, 66, 193)', bg: 'rgba(111, 66, 193, 0.1)' },  // Purple
                    ];

                    const labels = <?php echo json_encode($chartTimestamps); ?>;
                    const datasets = [];
                    const sensorNames = <?php echo json_encode($this->data->sensorNames); ?>;

                    <?php
                    $colorIndex = 0;
                    $colorCount = 5; // Number of colors defined in the colors array
                    foreach ($this->data->sensorIds as $sensorId):
                        ?>
                    datasets.push({
                        label: sensorNames[<?php echo $sensorId; ?>] || 'Sensor <?php echo htmlspecialchars($sensorId); ?>',
                        data: <?php echo json_encode($chartData[$sensorId]); ?>,
                        borderColor: colors[<?php echo $colorIndex % $colorCount; ?>].border,
                        backgroundColor: colors[<?php echo $colorIndex % $colorCount; ?>].bg,
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
                                        maxTicksLimit: 20
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
                    <p class="mb-0 mt-2">Keine Temperaturdaten für den ausgewählten Datumsbereich verfügbar</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Data Table Section -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="fs-5 fw-bold mb-0">
                            <i class="bi bi-table me-2"></i>
                            Temperaturmessungen
                        </h2>
                    </div>
                    <div class="col-auto">
                        <small class="text-muted">
                            <?php echo count($this->data->intervalData); ?> Intervalle insgesamt
                        </small>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($this->data->intervalData)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <?php foreach ($this->data->sensorIds as $sensorId): ?>
                                <th class="border-0">
                                    <?php echo htmlspecialchars($this->data->sensorNames[$sensorId] ?? "Sensor {$sensorId}"); ?>
                                </th>
                                <?php endforeach; ?>
                                <th class="border-0">Zeitstempel</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->data->intervalData as $timestamp => $sensors): ?>
                            <tr>
                                <?php foreach ($this->data->sensorIds as $sensorId): ?>
                                <td>
                                    <?php if (isset($sensors[$sensorId])): ?>
                                        <strong><?php echo htmlspecialchars($sensors[$sensorId]); ?>°C</strong>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <?php endforeach; ?>
                                <td class="text-muted small">
                                    <?php
                                    $date = new DateTime($timestamp);
                                    echo $date->format('d.m.y H:i:s');
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
                    <p class="mb-0 mt-2">Keine Temperaturmessungen für den ausgewählten Datumsbereich gefunden</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
