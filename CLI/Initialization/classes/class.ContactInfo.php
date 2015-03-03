<?php namespace Installer;

use Installer\Exceptions\InstallerException;

class ContactInfo {
    /**
     * @var \ilSetup
     */
    protected $setup;

    /**
     * @var string
     */
    protected $firstname;

    /**
     * @var string
     */
    protected $lastname;

    /**
     * @var string
     */
    protected $admin_email;

    /**
     * @var string
     */
    protected $feeback_receipient;

    /**
     * @param $client_id string the ILIAS client id.
     */
    public function __construct($client_id) {
        $this->setup = new \ilSetup(true, true);
        $this->initClient($client_id);
    }

    public function read() {
        /** @var $client ilClient */
        $client = $this->setup->getClient();
        if($client == null) {
            throw new InstallerException("There is no client found with name travis");
        }
        $client->init();
        $this->setFirstname($this->setFirstname($client->getSetting('admin_firstname')));
        $this->setLastname($this->setFirstname($client->getSetting('admin_lastname')));
        $this->setAdminEmail($this->setFirstname($client->getSetting('admin_email')));
        $this->setFeebackReceipient($this->setFirstname($client->getSetting('feedback_recipient')));
    }

    /**
     * @return bool returns true if everything's ok. otherwise you get the error string.
     */
    public function save() {
        $this->setup->getClient()->setSetting("admin_firstname", $this->firstname);
        $this->setup->getClient()->setSetting("admin_lastname", $this->lastname);
        $this->setup->getClient()->setSetting("admin_email", $this->admin_email);
        $this->setup->getClient()->setSetting("feedback_recipient", $this->feeback_receipient);

        $array = $this->setup->checkClientContact($this->setup->client);
        $status = $array['status'];
        $comment = $array['comment'];
        if($status === true) {
            $this->setup->getClient()->status["contact"]["status"] = $status;
            $this->setup->getClient()->status["contact"]["comment"] = $comment;
            $this->setup->getClient()->ini->write();
            return true;
        } else {
            return $comment;
        }
    }

    public function initClient($client_id){
        $this->setup->newClient($client_id);
    }

    /**
     * @param string $admin_email
     */
    public function setAdminEmail($admin_email)
    {
        $this->admin_email = $admin_email;
    }

    /**
     * @param string $feeback_receipient
     */
    public function setFeebackReceipient($feeback_receipient)
    {
        $this->feeback_receipient = $feeback_receipient;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getAdminEmail()
    {
        return $this->admin_email;
    }

    /**
     * @return string
     */
    public function getFeebackReceipient()
    {
        return $this->feeback_receipient;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }
}