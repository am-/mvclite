<?php
/**
 * MVCLite
 * 
 * LICENSE
 * 
 * The new BSD license is applied on this source-file. For further
 * information please visit http://license.nordic-dev.de/newbsd.txt
 * or send an email to andre.moelle@gmail.com.
 */

ini_set('display_errors', 'On');
error_reporting(E_ALL);

require_once dirname(__FILE__) . '/constants.php';
require_once 'PHPUnit/Framework/TestCase.php';

require_once 'MVCLite/Loader.php';
MVCLite_Loader::register();
?>