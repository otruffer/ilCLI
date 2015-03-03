#!/bin/sh

mkdir "$PWD/iliasdata"

# make ILIAS ini file.
./CLI/cli.php ILIAS:init travis --iliasdata="$PWD/iliasdata"

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
touch ~/iliasdata/travis/ilias.log
