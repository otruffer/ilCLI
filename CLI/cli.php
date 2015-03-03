#!/usr/bin/env php
<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

require_once __DIR__.'/autoloader.php';

use ILIAS\CLI\DatabaseInstallCommand;
use ILIAS\CLI\DatabaseUpdateCommand;
use ILIAS\CLI\ClientContactInfoCommand;
use Symfony\Component\Console\Application;
use ILIAS\CLI\SkipRegistrationCommand;
use ILIAS\CLI\FinishSetupCommand;
use ILIAS\CLI\CreateClientCommand;
use ILIAS\CLI\MasterSettingsCommand;
use ILIAS\CLI\EditClientCommand;

$application = new Application();
$application->add(new DatabaseInstallCommand);
$application->add(new DatabaseUpdateCommand);
$application->add(new ClientContactInfoCommand());
$application->add(new SkipRegistrationCommand());
$application->add(new FinishSetupCommand());
$application->add(new CreateClientCommand());
$application->add(new MasterSettingsCommand());
$application->add(new EditClientCommand());
$application->run();

/** INSTALLING ILIAS:

 * UNDER CONSTRUCTION *
     use at your own risk

client name is currently by default 'travis' and cannot be changed.
Data folder must be read/writable for current user and is by default: /var/iliasdata/

# make ILIAS ini file.
./CLI/cli.php ILIAS:init travis

# creat the travis client.
./CLI/cli.php client:create travis

# edit the travis client for db installation.
./CLI/cli.php client:edit travis --database_name='travis' --database_user='travis' --client_name='travis'

# create the database
./CLI/cli.php db:install travis --create

# update the database if necessary.
./CLI/cli.php db:update travis

# add the required contact informations.
./CLI/cli.php client:contactInfo travis --first_name='travis' --last_name='ci' --admin_email='ot@studer-raimann.ch' --feedback_recipient='ot@studer-raimann.ch'

# skip the online registration for this installation
./CLI/cli.php setup:skipRegistration travis

# finish and set trasvis to the default client.
./CLI/cli.php setup:finish travis -d

# todo: dynamic.
touch /var/iliasdata/travis/ilias.log


Now set owner to www-data recursivly for www and data folder.


More infos with: ./CLI/cli.php help

 */