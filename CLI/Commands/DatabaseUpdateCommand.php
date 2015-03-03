<?php namespace ILIAS\CLI;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DatabaseUpdateCommand extends DatabaseCommand {

    protected function configure() {
        parent::configure();
        $this
            ->setName('db:update')
            ->setDescription('Will update the database for ILIAS');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        parent::execute($input, $output);
        $output->writeln("Updating Database.");
        $this->installer->updateDatabase();
        $this->writeMessages($output);
    }
}

