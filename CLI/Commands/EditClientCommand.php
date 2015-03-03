<?php namespace ILIAS\CLI;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

class EditClientCommand extends Command {

    /**
     * @var \ilIniFile
     */
    protected $ini;

    /**
     * @var string TODO
     */
    protected $id = "travis";

    public function __construct() {
        parent::__construct();
        $this->setup = new \ilSetup(true, true);
    }

    protected function readIni() {
        //TODO parameter
        $filePath = ILIAS_ABSOLUTE_PATH."/".ILIAS_WEB_DIR."/".$this->id."/client.ini.php";
        $this->ini = new \ilIniFile($filePath);
        $this->ini->read();
    }

    protected function configure() {
        $this
            ->setName('client:edit')
            ->setDescription('Create the data dir, including the client.ini')
            ->addArgument(
                'client',
                InputArgument::REQUIRED,
                'The ILIAS client id'
            )
            ->addOption(
                'database_user',
                null,
                InputOption::VALUE_OPTIONAL,
                'The database user.'
            )
            ->addOption(
                'password',
                'p',
                InputOption::VALUE_NONE,
                'Will give you a prompt to insert the password.'
            )
            ->addOption(
                'client_name',
                null,
                InputOption::VALUE_REQUIRED,
                'The clients name'
            )->addOption(
                'database_name',
                null,
                InputOption::VALUE_REQUIRED,
                'The database name.'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->id = $input->getArgument('client');
        $this->readIni();

        $dialog = $this->getHelper('dialog');
        if($input->getOption('password')) {
            $this->ini->setVariable('db', 'pass', $dialog->askHiddenResponse($output, 'Database password?', ''));
        }
        if($input->getOption('database_user')) {
            $this->ini->setVariable('db', 'user', $input->getOption('database_user'));
        }
        if($input->getOption('database_name')) {
            $this->ini->setVariable('db', 'name', $input->getOption('database_name'));
        }
        if($input->getOption('client_name')) {
            $this->ini->setVariable('client', 'name', $input->getOption('client_name'));
        }
        if($this->ini->write()) {
            $output->writeln("Ini file updated.");
        } else {
            $output->writeln("Ini file could not be written!");
            exit(1);
        }
    }
}

