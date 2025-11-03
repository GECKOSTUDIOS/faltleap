<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontrollblatt - Lieferantenprüfung</title>
    <style>
        body {
            padding: 20px;
            font-family: 'Calibri', 'Arial', sans-serif;
            color: #4A4B4C;
            font-size: 14px;
            max-width: 650px;
            margin: 0 auto;
        }
        .header-logo {
            text-align: right;
            float: right;
        }
        .header-logo img {
            height: 40px;
            margin-top: 0;
        }
        h1 {
            margin-bottom: 3px;
            font-size: 28px;
        }
        h3 {
            margin-top: 3px;
            font-size: 18px;
            font-weight: normal;
        }
        hr {
            border: 0;
            border-top: 2px solid #333;
            margin: 20px 0;
        }
        .info-section {
            margin: 30px 0;
        }
        .info-label {
            font-weight: normal;
        }
        .info-value {
            font-weight: bold;
        }
        .signature-section {
            margin-top: 60px;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            display: inline-block;
            min-width: 300px;
            margin-bottom: 5px;
        }
        .footer {
            margin-top: 40px;
            background-color: #F2F2F2;
            color: #BDBDBD;
            font-size: 11px;
            padding: 10px;
        }
        .footer b {
            color: #999;
        }
        .clearfix {
            clear: both;
        }
        .spacer {
            height: 25px;
        }
        @media print {
            body {
                padding: 10px;
            }
            .no-print {
                display: none;
            }
        }
        .btn-print {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .btn-print:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">
            🖨️ Drucken
        </button>
        <button onclick="window.location.href='/receiptctrl'" class="btn-print" style="background-color: #6c757d;">
            ← Zurück zur Übersicht
        </button>
    </div>

    <div class="header-logo">
        <img src="/volt/img/brand/light.svg" alt="CargoCrew" />
    </div>

    <h1>Lieferantenprüfung</h1>
    <h3>
        Betrieb: <?= htmlspecialchars($this->data->control->location) ?>,
        Datum: <?= date('d.m.Y', strtotime($this->data->control->date)) ?>
    </h3>

    <hr>

    <div class="spacer"></div>

    <div class="info-section">
        <span class="info-label">Zu prüfender Lieferant:</span>
        <span class="info-value"><?= htmlspecialchars($this->data->control->supplier) ?></span>
        <?php if ($this->data->control->is_new): ?>
            <span style="color: #007bff; font-weight: bold;"> (NEU)</span>
        <?php endif; ?>
        <br><br>

        <span class="info-label">Anzahl gelieferter Gebinde:</span>
        <span class="info-value"><?= $this->data->control->amount_delivered ?></span>
        <br>

        <span class="info-label">Anzahl zu prüfender Gebinde:</span>
        <span class="info-value"><?= $this->data->control->amount_control ?></span>
        <br>

        <span class="info-label">Kontrollquadrant:</span>
        <span class="info-value"><?= $this->data->control->quadrant ?></span>
        <br>

        <span class="info-label">Papierposition:</span>
        <span class="info-value">
            <?= $this->data->control->paper_placement === 'top' ? 'Oben (auf den Waren)' : 'Seite (an der Seite)' ?>
        </span>
    </div>

    <div class="spacer"></div>
    <div class="spacer"></div>

    <div class="signature-section">
        <div style="float: right; text-align: center;">
            <div class="signature-line"></div><br>
            Unterschrift, Stempel
        </div>

        <div style="text-align: left;">
            <div class="signature-line"></div><br>
            Datum, Uhrzeit
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="spacer"></div>
    <hr>

    <div class="footer">
        <b>Interne Angaben</b><br>
        Tool: Postman Dashboard<br>
        Abruf: <?= date('d.m.Y H:i:s') ?><br>
        User: <?= htmlspecialchars($this->data->control->username) ?><br>
        Kontroll-ID: <?= $this->data->control->idlba_controls ?><br>
        Seiten: 1 von 1
    </div>
</body>
</html>
