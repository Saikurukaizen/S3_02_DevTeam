<?php
declare(strict_types=1);

$settings = parse_ini_file('settings.ini', true);

$dbh = new PDO(
  sprintf(
    "%s:host=%s;dbname=%s",
    $settings['database']['driver'],
    $settings['database']['host'],
    $settings['database']['dbname']
  ),
  $settings['database']['user'],
  $settings['database']['password']
);

?>
