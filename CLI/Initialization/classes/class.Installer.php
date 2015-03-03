<?php namespace Installer;

use Installer\Exceptions\InstallerException;
use Installer\Util as Util;

class Installer {

    /**
     * @var \ilSetup
     */
    protected $setup;

    /**
     * @var string
     */
    protected $dbUser;

    /**
     * @var string
     */
    protected $dbPassword;

    /**
     * @var string
     */
    protected $dbName;

    /**
     * @var \string[]
     */
    protected $errors;

    /**
     * @var \string[]
     */
    protected $messages;

    /**
     * @var Util\CopyUtil
     */
    protected $util;
    protected $iliasIniFile = "CLI/Initialization/Injection/ilias.ini.php";
    protected $iliasDataFolder = "CLI/Initialization/Injection/data";
    protected $databaseCollation = "utf8_general_ci";

    /**
     * @return string
     */
    public function getIliasDataFolder()
    {
        return $this->iliasDataFolder;
    }

    /**
     * @param $client_id string ILIAS client id.
     */
    public function __construct($client_id) {
        $this->setup = new \ilSetup(true, true);
        $this->util = new Util\CopyUtil();
        $this->initClient($client_id);
    }

    /**
     * @param $client_id string
     */
    public function initClient($client_id){
        $this->setup->newClient($client_id);
    }

    /**
     * Copies the file specified in iliasIniFile to root/ilias.ini.php
     */
    public function injectILIASIniFile() {
        copy($this->iliasIniFile, "ilias.ini.php");
    }

    /**
     * @return bool
     */
	public function checkPreliminaries()
    {
        return $this->setup->checkPreliminaries();
    }

    /**
     * @return array[][] 2D array: array[$preliminaryId] = array($text, $status, $comment);
     */
    public function getPreliminariesText() {
        global $lng;
        $preliminaries = array("php", "root", "folder_create",
            "cookies_enabled", "dom", "xsl", "gd", "memory");
        $result = array();
        foreach ($preliminaries as $preliminary) {
            if ($this->setup->preliminaries_result[$preliminary]["status"] == true) {
                $status = 'OK';
            }
            else {
                $status = 'Failed';
            }
                $result[$preliminary] = array($lng->txt("pre_".$preliminary), $status, $this->setup->preliminaries_result[$preliminary]["comment"]);
        }
        return $result;
    }

    public function updateDatabase() {
        global $ilCtrlStructureReader;
        $ilCtrlStructureReader = new \ilCtrlStructureReader();
        $ilCtrlStructureReader->setIniFile($this->setup->getClient()->ini);
        $db = $this->setup->getClient()->db;
        $dbUpdate = new \ilDBUpdate($db);
        $dbUpdate->applyUpdate();
        $this->parseUpdateMessages($dbUpdate->updateMsg);
    }

    protected function parseUpdateMessages($updateMessage) {
        if($updateMessage == "no_changes"){
            $this->addMessage("Update Database did not make any changes to the databse.");
        } else {
            foreach ($updateMessage as $message) {
                if ($message["msg"] == "update_applied")
                {
                    $this->addMessage("Update applied.");
                }
                else
                {
                    $this->addError("DB Update Error on step {$message['nr']}: ".$message['msg']);
                }
            }
        }
    }

    /**
     * Copies the folder specified in iliasDataFolder to root/data recursivly.
     */
    public function injectDataFolder() {
        $this->util->deepCopy($this->iliasDataFolder, "data");
    }

    public function createDatabase() {
        if(!$this->setup->createDatabase($this->getDatabaseCollation())) {
            throw new InstallerException("The database could not be created.");
        }
    }

    public function installDatabase() {
        if(!$this->setup->installDatabase()) {
            throw new InstallerException("The database could not be installed.");
        }
    }

    public function saveContactInformation() {

    }

    /**
     * @param string $iliasIniFile
     */
    public function setIliasIniFile($iliasIniFile)
    {
        $this->iliasIniFile = $iliasIniFile;
    }

    /**
     * @return string
     */
    public function getIliasIniFile()
    {
        return $this->iliasIniFile;
    }

    /**
     * @param string $iliasDataFolder
     */
    public function setIliasDataFolder($iliasDataFolder)
    {
        $this->iliasDataFolder = $iliasDataFolder;
    }

    /**
     * @param string $databaseCollation
     */
    public function setDatabaseCollation($databaseCollation)
    {
        $this->databaseCollation = $databaseCollation;
    }

    /**
     * @return string
     */
    public function getDatabaseCollation()
    {
        return $this->databaseCollation;
    }

    /**
     * @param string $dbName
     */
    public function setDbName($dbName)
    {
        $this->dbName = $dbName;
    }

    /**
     * @return string
     */
    public function getDbName()
    {
        return $this->dbName;
    }

    /**
     * @param string $dbPassword
     */
    public function setDbPassword($dbPassword)
    {
        $this->dbPassword = $dbPassword;
    }

    /**
     * @return string
     */
    public function getDbPassword()
    {
        return $this->dbPassword;
    }

    /**
     * @param string $dbUser
     */
    public function setDbUser($dbUser)
    {
        $this->dbUser = $dbUser;
    }

    /**
     * @return string
     */
    public function getDbUser()
    {
        return $this->dbUser;
    }

    /**
     * @return \string[]
     */
    public function getErrors()
    {
        $errors = $this->errors;
        $this->errors = array();
        return $errors;
    }

    /**
     * @param $error \string
     */
    protected function addError($error) {
        $this->errors[] = $error;
    }

    /**
     * @param $errors string[]
     */
    protected function addErrors($errors) {
        foreach($errors as $error){
            $this->addError($error);
        }
    }

    /**
     * @return \string[]
     */
    public function getMessages()
    {
        $messages = $this->messages;
        $this->messages = array();
        return $messages;
    }

    /**
     * @param $messages
     */
    protected function addMessage($messages) {
        $this->messages[] = $messages;
    }

    /**
     * @param $messages
     */
    protected function addMessages($messages) {
        foreach($messages as $message){
            $this->addError($message);
        }
    }
}