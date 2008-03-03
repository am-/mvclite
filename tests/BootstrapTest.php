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

include 'setUp.php';
require_once 'MVCLite.php';
require_once 'MVCLite/Controller/Abstract.php';
require_once 'MVCLite/Db/Exception.php';
require_once 'MVCLite/Exception.php';
require_once 'MVCLite/Loader.php';
require_once 'MVCLite/Request.php';
require_once 'MVCLite/Request/Route/Standard.php';
require_once 'MVCLite/Security/Exception.php';
require_once 'MVCLite/View.php';
require_once 'MVCLite/View/Layout.php';

/**
 * Unit-testing for the abstract bootstrap.
 * 
 * @category   MVCLite
 * @package    Core
 * @subpackage UnitTests
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id: MVCLiteTest.php 158 2008-02-02 16:48:55Z andre.moelle $
 */
class BootstrapTest extends PHPUnit_Framework_TestCase
{
	public function testFrontController ()
	{
		$bootstrap = new Bootstrap('foo');
		
		$mvclite = $bootstrap->bootstrap();
		$this->assertEquals(
			'MVCLite',
			get_class($mvclite)
		);
		$this->assertNotSame(
			$mvclite,
			$bootstrap->bootstrap()
		);
	}
	
	public function testInit ()
	{
		$bootstrap = new Bootstrap('foo');
		
		$this->assertEquals(
			'',
			$bootstrap->foo
		);
		$this->assertEquals(
			'',
			$bootstrap->bar
		);
		
		
		$bootstrap->bootstrap();
		$this->assertEquals(
			'bar',
			$bootstrap->foo
		);
		$this->assertEquals(
			'foo',
			$bootstrap->bar
		);
	}
	
	public function testProfile ()
	{
		$bootstrap = new Bootstrap('production');
		$this->assertEquals(
			'production',
			$bootstrap->getProfile()
		);
		$bootstrap = new Bootstrap('test');
		$this->assertEquals(
			'test',
			$bootstrap->getProfile()
		);
	}
}
?>