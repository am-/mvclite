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

require_once 'MVCLite/Model/DatabaseTest.php';

/**
 * Runs all tests in the model-component.
 * 
 * @category   MVCLite
 * @package    Model
 * @subpackage UnitTests
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_Model_AllTests
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
		$suite = new PHPUnit_Framework_TestSuite('MVCLite - Model tests');
		
		$suite->addTestSuite('MVCLite_Model_DatabaseTest');
		
		return $suite;
	}
}
?>