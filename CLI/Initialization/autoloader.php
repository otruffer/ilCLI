<?php
$require_function = function ($folder) {
    $directory = new RecursiveDirectoryIterator($folder);
    $recIterator = new RecursiveIteratorIterator($directory);
    $regex = new RegexIterator($recIterator, '/^.+\.php$/i');

    foreach ($regex as $file) {
        require_once $file->getPathname();
    }
};

$require_function('CLI/Initialization/classes');

/////////////////////////////////////////////////
/**  FROM HERE ON WE TRY TO INITIALIZE ILIAS: **/
/**                 IT MAY GET UGLY           **/
/////////////////////////////////////////////////

error_reporting(ini_get('error_reporting') & ~E_NOTICE);

define("DEBUG",false);
set_include_path("./Services/PEAR/lib".PATH_SEPARATOR.ini_get('include_path'));
require_once "./include/inc.check_pear.php";

//include files from PEAR
require_once "PEAR.php";

require_once "HTML/Template/ITX.php";
require_once "./Services/UICore/classes/class.ilTemplateHTMLITX.php";

require_once "./setup/classes/class.ilTemplate.php";	// modified class. needs to be merged with base template class
require_once "./setup/classes/class.ilLanguage.php";	// modified class. needs to be merged with base language class
require_once "./Services/Logging/classes/class.ilLog.php";
require_once "./Services/Authentication/classes/class.ilSession.php";
require_once "./Services/Utilities/classes/class.ilUtil.php";
require_once "./Services/Init/classes/class.ilIniFile.php";
require_once "./Services/Database/classes/class.ilDB.php";
require_once "./setup/classes/class.ilSetupGUI.php";
require_once "./setup/classes/class.Session.php";
require_once "./setup/classes/class.ilClientList.php";
require_once "./setup/classes/class.ilClient.php";
require_once "./Services/FileSystem/classes/class.ilFile.php";
require_once "./setup/classes/class.ilCtrlStructureReader.php";
require_once "./Services/Xml/classes/class.ilSaxParser.php";
require_once "./include/inc.ilias_version.php";

require_once './Services/Tree/classes/class.ilTree.php';

// include error_handling
require_once "./Services/Init/classes/class.ilErrorHandling.php";

define("ILIAS_ABSOLUTE_PATH", ".");

// init session
//$sess = new Session();

$lang = (isset($_GET["lang"])) ? $_GET["lang"] : $_SESSION["lang"];

$_SESSION["lang"] = $lang;

// init languages
$lng = new ilLanguage($lang);

// init log
$log = new ilLog(ILIAS_ABSOLUTE_PATH,"ilias.log","SETUP",false);
$ilLog =& $log;

require_once "./Services/Utilities/classes/class.ilBenchmark.php";
$ilBench = new ilBenchmark();
$GLOBALS['ilBench'] = $ilBench;

include_once("./Services/Database/classes/class.ilDBAnalyzer.php");
include_once("./Services/Database/classes/class.ilMySQLAbstraction.php");
include_once("./Services/Database/classes/class.ilDBGenerator.php");

define ("TPLPATH","./templates/blueshadow");

require_once "./setup/classes/class.ilSetup.php";

//$reqo_function('Services/Workflow/classes');
//$reqo_function('setup/classes');
