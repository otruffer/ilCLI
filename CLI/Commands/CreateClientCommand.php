<?php namespace ILIAS\CLI;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Installer\ContactInfo;

class CreateClientCommand extends Command {

    protected $setup;

    public function __construct() {
        parent::__construct();
        $this->setup = new \ilSetup(true, true);
    }

    protected function initClient($client_id) {
        $this->setup->newClient($client_id);
    }

    protected function configure() {
        $this
            ->setName('client:create')
            ->setDescription('Create the data dir, including the client.ini')
        ->addArgument(
            'client',
            InputArgument::REQUIRED,
            'The ILIAS client id.'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $client_id = $input->getArgument('client');
        $this->initClient($client_id);

        if(!file_exists('./data')) {
            mkdir ('./data');
        }
        if($this->setup->getClient()->create()){
            $output->writeln("Client data dir was created successfully.");
        } else {
            $error = $this->setup->getClient()->getError();
            $output->writeln("There was a problem creating the data directory: ".$error);
            $output->writeln("Maybe the client was already initialized or you don't have write permissions.");
        }
    }
}

