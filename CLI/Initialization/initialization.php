#!/usr/bin/php
<?php
/**
 * Created by PhpStorm.
 * User: otruffer
 * Date: 10/29/14
 * Time: 3:35 PM
 */

require_once "Tests/Initialization/autoloader.php";

echo "hi";
print "hi";
use \Installer;

$installer = new Installer\Installer();
$installer->installILIAS();

?>