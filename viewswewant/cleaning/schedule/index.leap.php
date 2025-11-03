<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Wöchentlicher Reinigungsplan</h2>
            <a href="/cleaning/schedule/edit" class="btn btn-primary d-inline-flex align-items-center">
                <i class="bi bi-calendar-plus me-2"></i>
                Neue Zeit hinzufügen
            </a>
        </div>
    </div>
</div>

<div class="row">
    <?php foreach ($this->data->schedulesByDay as $dayNum => $schedules) { ?>
        <div class="col-lg-6 col-xl-4 mb-4">
            <div class="card border-0 shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar3 me-2"></i>
                        <?php echo $this->data->dayNames[$dayNum]; ?>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($schedules)) { ?>
                        <p class="text-muted text-center py-3">
                            <i class="bi bi-info-circle me-1"></i>
                            Keine Reinigungszeiten geplant
                        </p>
                    <?php } else { ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($schedules as $schedule) { ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <i class="bi bi-clock me-2 text-primary"></i>
                                        <strong><?php echo substr($schedule->time_of_day, 0, 5); ?> Uhr</strong>
                                    </div>
                                    <div class="btn-group btn-group-sm">
                                        <a class="btn btn-primary" href="/cleaning/schedule/edit/<?php echo $schedule->idcleaning_schedules; ?>" title="Bearbeiten">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="#"
                                            class="btn btn-danger"
                                            onclick="if(confirm('Diese Zeit wirklich löschen?')) { window.location='/cleaning/schedule/delete/<?php echo $schedule->idcleaning_schedules; ?>'; } return false;"
                                            title="Löschen">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
