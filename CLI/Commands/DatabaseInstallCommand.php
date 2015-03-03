<?php namespace ILIAS\CLI;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DatabaseInstallCommand extends DatabaseCommand {

    protected function configure() {
        parent::configure();
        $this
            ->setName('db:install')
            ->setDescription('Will install the database for ILIAS')
            ->addOption(
                '--create',
                '-c',
                InputOption::VALUE_NONE,
                'Should the database be created or just filled?'
            );

    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        parent::execute($input, $output);
        if($input->getOption('create')){
            $output->writeln("Creating Database.");
            $this->installer->createDatabase();
        }
        $output->writeln("Installing Database.");
        $this->installer->installDatabase();
        $output->writeln("Updating Database.");
        $this->installer->updateDatabase();
        $this->writeMessages($output);
    }
}

