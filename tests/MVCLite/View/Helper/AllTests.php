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

require_once 'MVCLite/View/HelperTest.php';
require_once 'MVCLite/View/Helper/PrependTest.php';
require_once 'MVCLite/View/Helper/UrlTest.php';

/**
 * Runs all helper-tests.
 * 
 * @category   MVCLite
 * @package    View
 * @subpackage UnitTests
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_View_Helper_AllTests
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
		$suite = new PHPUnit_Framework_TestSuite('MVCLite - View-helper tests');
		
		$suite->addTestSuite('MVCLite_View_HelperTest');
		$suite->addTestSuite('MVCLite_View_Helper_PrependTest');
		$suite->addTestSuite('MVCLite_View_Helper_UrlTest');
		
		return $suite;
	}
}
?>