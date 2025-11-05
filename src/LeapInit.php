<?php
declare(strict_types=1);

// install.php — HTML installer for Leap Framework

function post($key, $default = '')
{
    return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
}

function ensure_dir($path, &$messages, &$errors, $purpose = '')
{
    if (!is_dir($path)) {
        if (@mkdir($path, 0775, true)) {
            $messages[] = "Created missing folder $path " . ($purpose ? "($purpose)" : "") . ".";
        } else {
            $errors[] = "Failed to create $path. Check directory permissions.";
            return false;
        }
    }
    if (!is_writable($path)) {
        if (!@chmod($path, 0775)) {
            $errors[] = "Directory $path is not writable. Please adjust permissions.";
            return false;
        } else {
            $messages[] = "Fixed permissions for $path.";
        }
    }
    return true;
}

function leapInit()
{
    if (file_exists('.env')) {
        return;
    }

    $messages = [];
    $errors = [];
    $ranGenerator = false;
    $generatorOutput = '';

    $root = __DIR__;
    //remove the /lib at the end
    $root = substr($root, 0, strrpos($root, '/lib'));
    $storageDir = "$root/storage";
    $modelsDir  = "$root/models";
    $confDir    = "$root/conf";

    // === Pre-flight checks ===
    ensure_dir($storageDir, $messages, $errors, 'for sessions');
    ensure_dir($modelsDir, $messages, $errors, 'for generated models');
    ensure_dir($confDir, $messages, $errors, 'for configuration');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $db_name   = post('db_name');
        $db_schema = post('db_schema');
        $db_user   = post('db_user');
        $db_pass   = post('db_pass');
        $db_host   = post('db_host');

        if ($db_name === '') {
            $errors[] = "Database name is required.";
        }
        if ($db_schema === '') {
            $errors[] = "Schema name is required.";
        }
        if ($db_user === '') {
            $errors[] = "Database user is required.";
        }
        if ($db_host === '') {
            $errors[] = "Database host is required.";
        }

        if (!$errors) {
            $env_config = <<<ENVCONFIG
# Database Configuration
DB_HOST={$db_host}
DB_USERNAME={$db_user}
DB_PASSWORD={$db_pass}
DB_DATABASE={$db_name}
DB_SCHEMA={$db_schema}
ENVCONFIG;

            $targetFile = "$root/.env";
            if (@file_put_contents($targetFile, $env_config) === false) {
                $errors[] = "Failed to write .env file. Check file permissions.";
            } else {
                $messages[] = "Saved configuration to .env file.";

                if (is_dir($modelsDir) && file_exists("$root/gen.php")) {
                    $messages[] = "Models directory found. Running generator…";
                    ob_start();
                    // Create dbconfig array from environment variables
                    $dbconfig = [
                      'dbhost' => $db_host,
                      'dbusername' => $db_user,
                      'dbpassword' => $db_pass,
                      'dbdatabase' => $db_name,
                      'dbschema' => $db_schema
                    ];
                    $is_installer = true;
                    $target = null;
                    $schema = $dbconfig['dbschema'];
                    include "$root/gen.php";
                    $generatorOutput = ob_get_clean();
                    $ranGenerator = true;
                    $messages[] = "Generation finished.";
                } elseif (!file_exists("$root/gen.php")) {
                    $messages[] = "Note: gen.php not found — skipping model generation.";
                } else {
                    $messages[] = "Note: models/ folder not found — skipping model generation.";
                }
            }
        }
    }
    ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Leap Framework — Installer</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root { color-scheme: light dark; }
    body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji"; margin: 2rem; line-height: 1.5; }
    .wrap { max-width: 820px; margin: 0 auto; }
    pre.logo { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; font-size: 12px; line-height: 1.05; padding: 1rem; border-radius: 12px; overflow: auto; border: 1px solid #8883; }
    form { display: grid; gap: 1rem; margin-top: 1.5rem; }
    .grid { display: grid; gap: 1rem; grid-template-columns: 1fr 1fr; }
    label { font-weight: 600; }
    input[type="text"], input[type="password"] {
      width: 100%; padding: 0.7rem 0.8rem; border: 1px solid #8886; border-radius: 8px; font-size: 1rem;
    }
    .full { grid-column: 1 / -1; }
    .btn {
      padding: 0.8rem 1rem; border: 0; border-radius: 10px; font-size: 1rem; font-weight: 700; cursor: pointer;
      background: #4f46e5; color: white;
    }
    .panel { padding: 1rem; border: 1px solid #8883; border-radius: 12px; }
    .ok { background: #16a34a22; border-color: #16a34a55; }
    .err { background: #dc262622; border-color: #dc262655; }
    strong { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }
    .out { white-space: pre-wrap; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; font-size: 0.95rem; }
    footer { margin-top: 2rem; opacity: 0.7; font-size: 0.95rem; }
  </style>
</head>
<body>
  <div class="wrap">
    <pre class="logo"><?php echo htmlspecialchars("░        ░░░      ░░░  ░░░░░░░░        ░░░░░░░░  ░░░░░░░░        ░░░      ░░░       ░░
▒  ▒▒▒▒▒▒▒▒  ▒▒▒▒  ▒▒  ▒▒▒▒▒▒▒▒▒▒▒  ▒▒▒▒▒▒▒▒▒▒▒  ▒▒▒▒▒▒▒▒  ▒▒▒▒▒▒▒▒  ▒▒▒▒  ▒▒  ▒▒▒▒  ▒
▓      ▓▓▓▓  ▓▓▓▓  ▓▓  ▓▓▓▓▓▓▓▓▓▓▓  ▓▓▓▓▓▓▓▓▓▓▓  ▓▓▓▓▓▓▓▓      ▓▓▓▓  ▓▓▓▓  ▓▓       ▓▓
█  ████████        ██  ███████████  ███████████  ████████  ████████        ██  ███████
█  ████████  ████  ██        █████  ███████████        ██        ██  ████  ██  ███████
                                                                                      "); ?></pre>

    <h1>Welcome to the amazing Falt Leap Framework!</h1>
    <h2>Tired of being a CRUD MONKEY?</h2>
    <p><strong>First, create your database and tables!</strong></p>
    <p>Already done? Let’s configure your database and verify the project structure.</p>
    <p>Have a safe and productive day!</p>

    <?php if ($errors): ?>
      <div class="panel err">
        There were problems:
        <ul>
          <?php foreach ($errors as $e): ?>
            <li><?php echo htmlspecialchars($e); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php if ($messages): ?>
      <div class="panel ok">
        <?php foreach ($messages as $m): ?>
          <div><?php echo $m; ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if ($ranGenerator && $generatorOutput !== ''): ?>
      <h2>Generator Output</h2>
      <div class="panel out">
        <?php echo htmlspecialchars($generatorOutput); ?>
      </div>
    <?php endif; ?>

    <form method="post" autocomplete="off">
      <div class="grid">
        <div>
          <label for="db_name">Database name</label>
          <input id="db_name" name="db_name" type="text" required
                 value="<?php echo htmlspecialchars(post('db_name')); ?>">
        </div>
        <div>
          <label for="db_schema">Schema name</label>
          <input id="db_schema" name="db_schema" type="text" required
                 value="<?php echo htmlspecialchars(post('db_schema')); ?>">
        </div>

        <div>
          <label for="db_user">Database user</label>
          <input id="db_user" name="db_user" type="text" required
                 value="<?php echo htmlspecialchars(post('db_user')); ?>">
        </div>
        <div>
          <label for="db_pass">Database password</label>
          <input id="db_pass" name="db_pass" type="password" required value="">
        </div>

        <div class="full">
          <label for="db_host">Database host</label>
          <input id="db_host" name="db_host" type="text" required
                 placeholder="e.g. localhost, 127.0.0.1, or a hostname"
                 value="<?php echo htmlspecialchars(post('db_host')); ?>">
        </div>
      </div>

      <button class="btn" type="submit">Save &amp; Generate Models</button>
    </form>
  </div>
</body>
</html>


<?php } ?>
