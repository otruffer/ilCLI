<?php namespace ILIAS\CLI;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Installer\Installer;

class MasterSettingsCommand extends DatabaseCommand {

    /**
     * @var Installer
     */
    protected $installer;

    public function __construct() {
        parent::__construct();
    }

    protected function configure() {
        $this
            ->setName('ILIAS:init')
            ->setDescription('This will set the master settings accoring to the Initialization injection folder.')
            ->addArgument(
                'client',
                InputArgument::REQUIRED,
                'The ILIAS client id'
            )->addOption(
                'iliasdata',
                null,
                InputOption::VALUE_REQUIRED,
                'The path to the iliasdata folder.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $client_id = $input->getArgument('client');
        $this->installer = new Installer($client_id);

        $this->installer->injectILIASIniFile();
        $output->writeln("The ilias.ini file has been injected.");

        $iniFile = new \ilIniFile(ILIAS_ABSOLUTE_PATH."/ilias.ini.php");
        $output->write(ILIAS_ABSOLUTE_PATH."/ilias.ini.php");
        $iniFile->read();
        $iniFile->setVariable('server', 'absolute_path', getcwd());
        $datadir = ($input->getOption('iliasdata')?$input->getOption('iliasdata'):'/var/iliasdata');
        $output->writeln('iliasdata option:'.$datadir);
        $iniFile->setVariable('clients', 'datadir', $datadir);
        $iniFile->setVariable('clients', 'default', $client_id);
        $iniFile->setVariable('log', 'path', $datadir."/".$client_id);
        if($iniFile->write())
            $output->writeln("The ilias.ini has been edited.");
        else {
            $output->writeln("There was a problem editing the ilias INI file.");
            exit(1);
        }

//        $checks = $this->installer->getPreliminariesText();
//        if(!$this->installer->checkPreliminaries()) {
//            foreach($checks as $check) {
//                $output->writeln(implode(' - ', $check));
//            }
//            exit(1);
//        }
    }
}

