#!/usr/bin/php5
<?php
// Parse command line arguments into the $_GET variable:
parse_str(implode('&', array_slice($argv, 1)), $_GET);

echo 'Owner: ' . $_GET['owner'] . "\n";
echo 'Name: ' . $_GET['name'] . "\n";
echo 'Singular: ' . $_GET['singular'] . "\n";
echo 'Description: ' . $_GET['description'] . "\n";

$owner              = ucwords($_GET['owner']);
$name               = ucwords($_GET['name']);
$singular           = ucwords($_GET['singular']);

$lc_name            = strtolower($name);
$lc_name_singular   = strtolower($singular);
$uc_name            = strtoupper($name);
$uc_name_singular   = strtoupper($singular);
$com_lc_name        = 'com_' . str_replace(' ', '', $lc_name);
$com_uc_name        = strtoupper(str_replace(' ', '', $com_lc_name));
$classname          = ucfirst(strtolower(str_replace(' ', '', $name)));
$classname_singular = ucfirst(strtolower(str_replace(' ', '', $singular)));
$description        = $_GET['description'];

echo 'Classname: ' . $classname . "\n";
echo 'Singular Classname: ' . $classname_singular . "\n";

include_once('_functions.php');

$new_dir     = dirname(__DIR__) . '/__builds/' . $com_lc_name;

copy_dir(__DIR__ . '/com__bones', $new_dir);

perform_renames(
    $new_dir,
    [
        '_bones' => str_replace(' ', '', $lc_name),
        '_bone'  => str_replace(' ', '', $lc_name_singular),
        '_Bones' => str_replace(' ', '', $classname),
        '_Bone'  => str_replace(' ', '', $classname_singular)
    ],
    [
        '{{OWNER}}'         => $owner,
        '{{NAME}}'          => $name,
        '{{SINGULAR}}'      => $singular,
        '{{DESCRIPTION}}'   => $description,
        '{{NAME-NO-SPACE}}' => str_replace(' ', '', $name),
        '_bones'            => str_replace(' ', '', $lc_name),
        '_bone'             => str_replace(' ', '', $lc_name_singular),
        '_Bones'            => $classname,
        '_Bone'             => $classname_singular,
        'COM_BONES'         => $com_uc_name,
        '_BONES'            => '_' . str_replace(' ', '', $uc_name),
        '_BONE'             => '_' . str_replace(' ', '', $uc_name_singular),
        '{{MONTH}}'         => date('F'),
        '{{YEAR}}'          => date('Y')
    ]
);
?>
