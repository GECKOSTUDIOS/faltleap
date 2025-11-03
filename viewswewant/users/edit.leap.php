<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-0"><?php echo $this->data->idusers ? 'Benutzer bearbeiten' : 'Neuen Benutzer hinzufügen'; ?></h2>
                <p class="text-muted mb-0">
                    <?php echo $this->data->idusers ? 'Aktualisieren Sie die Benutzerinformationen unten' : 'Erstellen Sie ein neues Benutzerkonto'; ?>
                </p>
            </div>
            <a href="/users" class="btn btn-secondary d-inline-flex align-items-center">
                <i class="bi bi-arrow-left me-2"></i>
                Zurück zu Benutzern
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow">
            <div class="card-header">
                <h5 class="mb-0">Benutzerinformationen</h5>
            </div>
            <div class="card-body">
                <form method="post" action="/users/edit/<?php echo $this->data->idusers ?? ''; ?>">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="username" class="form-label">Benutzername</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text"
                                       class="form-control"
                                       id="username"
                                       name="username"
                                       value="<?php echo htmlspecialchars($this->data->username ?? ''); ?>"
                                       placeholder="Benutzernamen eingeben"
                                       required>
                            </div>
                            <small class="form-text text-muted">Wählen Sie einen eindeutigen Benutzernamen für diesen Benutzer.</small>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="email" class="form-label">E-Mail-Adresse</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email"
                                       class="form-control"
                                       id="email"
                                       name="email"
                                       value="<?php echo htmlspecialchars($this->data->email ?? ''); ?>"
                                        placeholder="benutzer@beispiel.com"
                                        required>
                            </div>
                            <small class="form-text text-muted">Wir verwenden dies für Kontobenachrichtigungen.</small>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="password" class="form-label">Passwort</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password"
                                       class="form-control"
                                       id="password"
                                       name="password"
                                       value=""
                                        placeholder="<?php echo $this->data->idusers ? 'Leer lassen, um das aktuelle Passwort beizubehalten' : 'Passwort eingeben'; ?>"
                                       <?php echo $this->data->idusers ? '' : 'required'; ?>>
                            </div>
                            <?php if ($this->data->idusers) { ?>
                                <small class="form-text text-muted">Leer lassen, um das aktuelle Passwort beizubehalten.</small>
                            <?php } else { ?>
                                <small class="form-text text-muted">Wählen Sie ein starkes Passwort für dieses Konto.</small>
                            <?php } ?>
                        </div>

                        <div class="col-12 mb-4">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               id="is_admin"
                                               name="is_admin"
                                               value="1"
                                               <?php echo (isset($this->data->is_admin) && $this->data->is_admin == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="is_admin">
                                            <strong>Administrator-Zugang</strong>
                                            <br>
                                            <small class="text-muted">Gewähren Sie diesem Benutzer vollständige administrative Berechtigungen</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Benutzer speichern
                                </button>
                                <a href="/users" class="btn btn-outline-secondary">
                                    Abbrechen
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow">
            <div class="card-header">
                <h5 class="mb-0">Tipps</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Benutzername:</strong> Muss eindeutig sein
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>E-Mail:</strong> Gültiges E-Mail-Format erforderlich
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Passwort:</strong> Verwenden Sie ein starkes Passwort
                    </li>
                    <li class="mb-0">
                        <i class="bi bi-shield-check text-danger me-2"></i>
                        <strong>Admin:</strong> Vollständiger Systemzugang
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
