<?php namespace ILIAS\CLI;

use Symfony\Component\Console\Input\InputInterface;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

class SkipRegistrationCommand extends Command {

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
            ->setName('setup:skipRegistration')
            ->setDescription('Skip the registration')
        ->addArgument(
            'client',
            InputArgument::REQUIRED,
            'The ILIAS client id'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $client_id = $input->getArgument('client');
        $this->initClient($client_id);

        $this->setup->getClient()->setSetting("inst_id","0");
        $this->setup->getClient()->setSetting("nic_enabled","0");
        $output->writeln("Registration skipped!");
    }
}

