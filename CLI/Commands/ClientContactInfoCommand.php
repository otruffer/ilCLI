<?php namespace ILIAS\CLI;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Installer\ContactInfo;

class ClientContactInfoCommand extends Command {

    protected $contact;

    public function __construct() {
        parent::__construct();
    }

    protected function configure() {
        $this
            ->setName('client:contactInfo')
            ->setDescription('Will set the contact info for ILIAS')
            ->addArgument(
                'client',
                InputArgument::REQUIRED,
                'The ILIAS client id.'
            )
            ->addOption(
                'interactive',
                'i',
                InputOption::VALUE_NONE,
                'Use the interactive mode instead of setting the values by parameters.'
            )
            ->addOption(
                'first_name',
                'fn',
                InputOption::VALUE_REQUIRED,
                'The admins first name.'
            )
            ->addOption(
                'last_name',
                'ln',
                InputOption::VALUE_REQUIRED,
                'The admins last name'
            )
            ->addOption(
                'admin_email',
                'ae',
                InputOption::VALUE_REQUIRED,
                'The admins email'
            )
            ->addOption(
                'feedback_recipient',
                'fr',
                InputOption::VALUE_REQUIRED,
                'Email for feedback.'
            )
        ;

    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $client_id = $input->getArgument('client');
        $this->contact = new ContactInfo($client_id);
        $this->contact->read();

        if($input->getOption('interactive')) {
            $this->interactive($output);
        } else {
            $this->predefined($input, $output);
        }
    }

    protected function predefined(InputInterface $input, OutputInterface $output) {
        if($input->getOption('first_name')) {
            $this->contact->setFirstname($input->getOption('first_name'));
        }
        if($input->getOption('last_name')) {
            $this->contact->setLastname($input->getOption('last_name'));
        }
        if($input->getOption('admin_email')) {
            $this->contact->setAdminEmail($input->getOption('admin_email'));
        }
        if($input->getOption('feedback_recipient')) {
            $this->contact->setFeebackReceipient($input->getOption('feedback_recipient'));
        }
        $this->saveContact($output);
    }

    protected function interactive(OutputInterface $output) {
        $dialog = $this->getHelper('dialog');
        $this->contact->setFirstname($dialog->ask($output, "The admins first name?"));
        $this->contact->setLastname($dialog->ask($output, "The admins last name?"));
        $this->contact->setAdminEmail($dialog->ask($output, "The admins email?"));
        $this->contact->setFeebackReceipient($dialog->ask($output, "Feedback receipients email?"));
        $this->saveContact($output);
    }

    protected function saveContact(OutputInterface $output) {
        $result = $this->contact->save();
        if($result === true){
            $output->writeln("Contact Infos have been saved.");
        } else {
            $output->writeln("something went wrong:");
            $output->writeln($result);
            $output->writeln("Please rerun the command with correct parameters. Otherwise settings are in an inconsistent state");
            exit(1);
        }
    }
}

