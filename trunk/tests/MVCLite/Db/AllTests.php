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

require_once 'MVCLite/DbTest.php';
require_once 'MVCLite/Db/PDOTest.php';
require_once 'MVCLite/Db/RecordTest.php';
require_once 'MVCLite/Db/TableTest.php';

/**
 * Runs all tests in the core.
 * 
 * @category   MVCLite
 * @package    Db
 * @subpackage UnitTests
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_Db_AllTests
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
		$suite = new PHPUnit_Framework_TestSuite('MVCLite - Database tests');
		
		$suite->addTestSuite('MVCLite_DbTest');
		$suite->addTestSuite('MVCLite_Db_PDOTest');
		$suite->addTestSuite('MVCLite_Db_RecordTest');
		$suite->addTestSuite('MVCLite_Db_TableTest');
		
		return $suite;
	}
}
?>