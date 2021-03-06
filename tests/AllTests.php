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

require_once 'MVCLite/Controller/AllTests.php';
require_once 'MVCLite/Core/AllTests.php';
require_once 'MVCLite/Db/AllTests.php';
require_once 'MVCLite/Error/AllTests.php';
require_once 'MVCLite/Model/AllTests.php';
require_once 'MVCLite/Request/AllTests.php';
require_once 'MVCLite/Security/AllTests.php';
require_once 'MVCLite/View/AllTests.php';

/**
 * Runs all tests.
 * 
 * @category   MVCLite
 * @package    Core
 * @subpackage UnitTests
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class AllTests
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
		$suite = new PHPUnit_Framework_TestSuite('MVCLite - All tests');
		
		$suite->addTest(MVCLite_Controller_AllTests::suite());
		$suite->addTest(MVCLite_Core_AllTests::suite());
		$suite->addTest(MVCLite_Db_AllTests::suite());
		$suite->addTest(MVCLite_Error_AllTests::suite());
		$suite->addTest(MVCLite_Model_AllTests::suite());
		$suite->addTest(MVCLite_Request_AllTests::suite());
		$suite->addTest(MVCLite_Security_AllTests::suite());
		$suite->addTest(MVCLite_View_AllTests::suite());
		
		return $suite;
	}
}
?>