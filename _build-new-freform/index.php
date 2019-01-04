#!/usr/bin/php5
<?php
//echo 'Started'; exit;
// Parse command line arguments into the $_GET variable:
parse_str(implode('&', array_slice($argv, 1)), $_GET);

echo 'Name: ' . $_GET['name'] . "\n";
echo 'Description: ' . $_GET['description'] . "\n";
#exit;
$name        = ucwords($_GET['name']);
$lc_name     = strtolower($name);
$uc_name     = strtoupper($name);
$com_lc_name = 'com_' . str_replace(' ', '', $lc_name);
$com_uc_name = strtoupper(str_replace(' ', '', $com_lc_name));
$classname   = str_replace(' ', '', $name);
$description = $_GET['description'];

echo 'Classname: ' . $classname . "\n";

include_once('_functions.php');

$new_dir     = dirname(__DIR__) . '/__builds/' . $com_lc_name;

copy_dir(__DIR__ . '/_com_freform', $new_dir);

perform_renames(
    $new_dir,
    array('_freform', str_replace(' ', '', $lc_name)),
    array(
        '{{NAME}}'        => $name,
        '{{DESCRIPTION}}' => $description,
        '_freform'        => str_replace(' ', '', $lc_name),
        '_Freform'        => $classname,
        '_com_freform'    => $com_lc_name,
        'COM_FREFORM'     => $com_uc_name,
        '_FREFORM'        => '_' . str_replace(' ', '', $uc_name)
    )
);
?>