#!/usr/bin/php5
<?php
// Parse command line arguments into the $_GET variable:
parse_str(implode('&', array_slice($argv, 1)), $_GET);

echo 'Owner: ' . $_GET['owner'] . "\n";
echo 'Name: ' . $_GET['name'] . "\n";
echo 'Description: ' . $_GET['description'] . "\n";

$owner              = ucwords($_GET['owner']);
$name               = ucwords($_GET['name']);
$lc_name            = strtolower($name);
$lc_name_singular   = preg_replace('/s$/', '', $lc_name);
$uc_name            = strtoupper($name);
$uc_name_singular   = preg_replace('/S$/', '', $uc_name);
$com_lc_name        = 'com_' . str_replace(' ', '', $lc_name);
$com_uc_name        = strtoupper(str_replace(' ', '', $com_lc_name));
$classname          = str_replace(' ', '', $name);
$classname_singular = preg_replace('/s$/', '', $classname);
$description        = $_GET['description'];

echo 'Classname: ' . $classname . "\n";

include_once('_functions.php');

$new_dir     = dirname(__DIR__) . '/__builds/' . $com_lc_name;

copy_dir(__DIR__ . '/_com_freform', $new_dir);

perform_renames(
    $new_dir,
    array('_freform', str_replace(' ', '', $lc_name_singular)),
    array(
        '{{OWNER}}'         => $owner,
        '{{NAME}}'          => $name,
        '{{DESCRIPTION}}'   => $description,
        '{{NAME-NO-SPACE}}' => str_replace(' ', '', $name),
        '_freform1'         => str_replace(' ', '', $lc_name_singular),
        '_freform'          => str_replace(' ', '', $lc_name),
        '_Freform1'         => $classname_singular,
        '_Freform'          => $classname,
        '_com_freform'      => $com_lc_name,
        'COM_FREFORM'       => $com_uc_name,
        '_FREFORM1'         => '_' . str_replace(' ', '', $uc_name_singular),
        '_FREFORM'          => '_' . str_replace(' ', '', $uc_name),
        '{{MONTH}}'         => date('F'),
        '{{YEAR}}'          => date('Y')
    )
);
?>