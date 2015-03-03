<?php namespace ILIAS\CLI;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Installer\Installer;

class DatabaseCommand extends Command {
    /**
     * @var Installer
     */
    protected $installer;

    public function __construct() {
        parent::__construct();
    }

    protected function configure() {
        $this->addArgument(
            'client',
            InputArgument::REQUIRED,
            'The ILIAS client id'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $client_id = $input->getArgument('client');
        $this->installer = new Installer($client_id);
    }

    protected function writeMessages(OutputInterface $output) {

        $messages = $this->installer->getMessages();
        $output->writeln($messages);

        $errors = $this->installer->getErrors();
        print_r($errors);
        if(count($errors)) {
            $output->writeln($errors);
            $output->writeln("There was an error during the execution.");
            exit(1); //we exit with an error state.
        }

        $output->writeln("Process terminated successfully.");
    }
}

