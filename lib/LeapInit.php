<?php
$logo = "░        ░░░      ░░░  ░░░░░░░░        ░░░░░░░░  ░░░░░░░░        ░░░      ░░░       ░░
▒  ▒▒▒▒▒▒▒▒  ▒▒▒▒  ▒▒  ▒▒▒▒▒▒▒▒▒▒▒  ▒▒▒▒▒▒▒▒▒▒▒  ▒▒▒▒▒▒▒▒  ▒▒▒▒▒▒▒▒  ▒▒▒▒  ▒▒  ▒▒▒▒  ▒
▓      ▓▓▓▓  ▓▓▓▓  ▓▓  ▓▓▓▓▓▓▓▓▓▓▓  ▓▓▓▓▓▓▓▓▓▓▓  ▓▓▓▓▓▓▓▓      ▓▓▓▓  ▓▓▓▓  ▓▓       ▓▓
█  ████████        ██  ███████████  ███████████  ████████  ████████        ██  ███████
█  ████████  ████  ██        █████  ███████████        ██        ██  ████  ██  ███████
                                                                                      ";
echo $logo . PHP_EOL;
if (!file_exists('conf/db.config.php')) {
  echo "\nWelcome to Leap Framework!\n";
  echo "\nLet's configure the database, so we can generate the dbconfig and models.\n";
  echo "Enter the database name: ";
  $db_name = trim(fgets(STDIN));
  echo "Enter the schema name: ";
  $db_schema = trim(fgets(STDIN));
  echo "Enter the database user: ";
  $db_user = trim(fgets(STDIN));
  echo "Enter the database password: ";
  $db_pass = trim(fgets(STDIN));
  echo "Enter the database host: ";
  $db_host = trim(fgets(STDIN));

  $db_config = <<<LEAPCONFIG
<?php
\$dbconfig = [
  'dbhost' => '{$db_host}',
  'dbusername' => '{$db_user}',
  'dbpassword' => '{$db_pass}',
  'dbdatabase' => '{$db_name}',
  'dbschema' => '{$db_schema}'
];
LEAPCONFIG;

  $db_config_file = fopen('conf/db.config.php', 'w');
  fwrite($db_config_file, $db_config);
  fclose($db_config_file);
}

//run gen.php
if (file_exists('models')) {
  echo "======================================================================\n";
  echo "Generating models...\n";
  echo "======================================================================\n";
  include('conf/db.config.php');
  $is_installer = true;
  $target = null;
  $schema = $dbconfig['dbschema'];
  include 'gen.php';

  echo "======================================================================\n";
  echo "DONE!\n\n";
  die();
}
