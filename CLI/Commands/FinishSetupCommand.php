<?php namespace ILIAS\CLI;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

class FinishSetupCommand extends Command {

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
            ->setName('setup:finish')
            ->setDescription('Validate Setup')
            ->addArgument(
                'client',
                InputArgument::REQUIRED,
                'The ILIAS client id'
            )
            ->addOption(
                'setDefaultClient',
                'd',
                InputOption::VALUE_NONE,
                'IF the flag + theres only one client, it will be set as a default client'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $client_id = $input->getArgument('client');
        $this->initClient($client_id);
        if($this->validateSetup($input, $output)){
            $output->writeln("Validation successful.");
            $this->setup->getClient()->setSetting("setup_ok",1);
        } else {
            $output->writeln("Validation not successful.");
        }
    }

    /**
     * This is built similar to validateSetup of ilSetupGUI
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool
     */
    protected function validateSetup(InputInterface $input, OutputInterface $output)
    {
        if(is_array($this->setup->getClient()->status )){
            foreach ($this->setup->getClient()->status as $key => $val)
            {
                if ($key != "finish" and $key != "access")
                {
                    if ($val["status"] != true)
                    {
                        $output->writeln($key." seems not to be finished yet.");
                        return false;
                    }
                }
        }
        }

        $clientList = new \ilClientList($this->setup->db_connections);
        $list = $clientList->getClients();
        if (count($list) == 1)
        {
            if($input->getOption('setDefaultClient')) {
                $this->setup->ini->setVariable("clients", "default", $this->setup->getClient()->getId());
                $this->setup->ini->write();
                $output->writeln("The default client was set to: ".$this->setup->getClient()->getId());
            } else {
                $output->writeln("The default could be set to: ".$this->setup->getClient()->getId(). ". Please rerun command with -d flag to do so.");
            }

            $this->setup->getClient()->ini->setVariable("client","access",1);
            $this->setup->getClient()->ini->write();
        }
        return true;
    }
}

