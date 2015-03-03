<?php

if(!file_exists('./CLI/autoloader.php')){
    echo "The current folder does not seem to be a valid ILIAS installation. Make sure to run this command from within the root folder of an ILIAS installation.\n";
    exit(1);
}

$vendor_autoload = './vendor/autoload.php';

if(!file_exists($vendor_autoload)){
    echo "Dependencies are not installed. Please run composer install in the ILIAS root folder.\n";
    exit(1);
}
/** @noinspection PhpIncludeInspection */
require_once $vendor_autoload ;

require_once './CLI/Initialization/autoloader.php';


require_once('./CLI/Commands/DatabaseCommand.php');
require_once('./CLI/Commands/DatabaseInstallCommand.php');
require_once('./CLI/Commands/DatabaseUpdateCommand.php');
require_once('./CLI/Commands/ClientContactInfoCommand.php');
require_once('./CLI/Commands/SkipRegistrationCommand.php');
require_once('./CLI/Commands/FinishSetupCommand.php');
require_once('./CLI/Commands/CreateClientCommand.php');
require_once('./CLI/Commands/MasterSettingsCommand.php');
require_once('./CLI/Commands/EditClientCommand.php');
require_once('./Services/Database/classes/class.ilDBUpdate.php');