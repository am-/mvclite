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

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once 'MVCLite/RequestTest.php';
require_once 'MVCLite/Request/GlobalTest.php';
require_once 'MVCLite/Request/RouteTest.php';
require_once 'MVCLite/Request/DispatcherTest.php';
require_once 'MVCLite/Request/Dispatcher/PluginTest.php';

/**
 * Runs all tests in the core.
 * 
 * @category   MVCLite
 * @package    Request
 * @subpackage UnitTests
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_Request_AllTests
{
	/**
	 * Runs the tests.
	 * 
	 * @return void
	 */
	public static function main ()
	{
		PHPUnit_TextUI_TestRunner::run(self::suite());
	}
	
	/**
	 * Returns the test-suite containing all tests to execute.
	 * 
	 * @return PHPUnit_Framework_TestSuite
	 */
	public static function suite ()
	{
		$suite = new PHPUnit_Framework_TestSuite('MVCLite - Request tests');
		
		$suite->addTestSuite('MVCLite_RequestTest');
		$suite->addTestSuite('MVCLite_Request_GlobalTest');
		$suite->addTestSuite('MVCLite_Request_RouteTest');
		$suite->addTestSuite('MVCLite_Request_DispatcherTest');
		$suite->addTestSuite('MVCLite_Request_Dispatcher_PluginTest');
		
		return $suite;
	}
}
?>