<div class="row">
    <div class="col-12 col-xl-8">
        <div class="card border-0 shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-event me-2"></i>
                    <?php echo $this->data->schedule->idcleaning_schedules ? 'Reinigungszeit bearbeiten' : 'Neue Reinigungszeit hinzufügen'; ?>
                </h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <?php if (isset($this->data->schedule->idcleaning_schedules) && $this->data->schedule->idcleaning_schedules): ?>
                    <input type="hidden" name="idcleaning_schedules" value="<?php echo htmlspecialchars($this->data->schedule->idcleaning_schedules); ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="day_of_week" class="form-label">Wochentag</label>
                        <select class="form-select" id="day_of_week" name="day_of_week" required>
                            <?php foreach ($this->data->dayNames as $dayNum => $dayName) { ?>
                                <option value="<?php echo $dayNum; ?>"
                                    <?php echo (isset($this->data->schedule->day_of_week) && $this->data->schedule->day_of_week == $dayNum) ? 'selected' : ''; ?>>
                                    <?php echo $dayName; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="time_of_day" class="form-label">Uhrzeit</label>
                        <input type="time" class="form-control" id="time_of_day" name="time_of_day"
                            value="<?php echo htmlspecialchars($this->data->schedule->time_of_day ?? '08:00'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="active" name="active" value="1"
                                <?php echo (!isset($this->data->schedule->active) || $this->data->schedule->active) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="active">
                                Aktiv
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="/cleaning/schedule" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            Zurück
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>
                            Speichern
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
