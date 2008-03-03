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

/**
 * Unit-testing for MVCLite_View_Helper_Registry.
 * 
 * @category   MVCLite
 * @package    Controller
 * @subpackage UnitTests
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_Controller_HelperTest extends PHPUnit_Framework_TestCase
{
	private $_path;
	private $_path2;
	
	public function setUp ()
	{
		$this->_path = realpath(MVCLITE_APP . 'controllerHelper/') . '/';
		$this->_path2 = realpath(MVCLITE_APP . 'controllerHelper/') . '/';
		
		if(!file_exists($this->_path))
		{
			mkdir($this->_path);
		}
		if(!file_exists($this->_path2))
		{
			mkdir($this->_path);
		}
		
		file_put_contents(
			$this->_path . 'Add.php',
			"<?php\n" .
			"class UnitTest_Controller_Helper_add extends MVCLite_Controller_Helper_Abstract\n" .
			"{\n" .
			"	public function add (\$sum1, \$sum2) { return \$sum1 + \$sum2; }\n" .
			"}\n" .
			"?>"
		);
		file_put_contents(
			$this->_path2 . 'MD5.php',
			"<?php\n" .
			"class UnitTest_Controller_Helper_MD5 extends MVCLite_Controller_Helper_Abstract\n" .
			"{\n" .
			"	public function md5 (\$string) { return md5(\$string); }\n" .
			"}\n" .
			"?>"
		);
		file_put_contents(
			$this->_path . 'Foobar.php',
			"<?php\n" .
			"class UnitTest_Controller_Helper_Foobar extends MVCLite_Controller_Helper_Abstract\n" .
			"{\n" .
			"	public function foobar () { return 'foobar'; }\n" .
			"}\n" .
			"?>"
		);
		file_put_contents(
			$this->_path . 'Foo.php',
			"<?php\n" .
			"class UnitTest_Controller_Helper_Foo extends MVCLite_Controller_Helper_Abstract\n" .
			"{\n" .
			"	public function foo () { return 'foobar'; }\n" .
			"}\n" .
			"?>"
		);
		file_put_contents(
			$this->_path2 . 'Bar.php',
			"<?php\n" .
			"class UnitTest_Controller_Helper_Bar\n" .
			"{\n" .
			"	public function foobar () { return 'foobar'; }\n" .
			"}\n" .
			"?>"
		);
		
		// create view
		file_put_contents(
			MVCLITE_VIEW . 'viewHelperTest.phtml',
			'<?= $this->md5("foobar") ?>' .
			'<?= $this->add(2, 2) ?>' . 
			'<?= $this->foobar() ?>' . "\n\n"
		);
	}
	
	public function tearDown ()
	{
		$array = glob($this->_path . '*');
		
		if(!$array)
		{
			$array = array();
		}
		
		foreach($array as $path)
		{
			unlink($path);
		}
		
		unlink(MVCLITE_VIEW . 'viewHelperTest.phtml');
	}
	
	public function testIntegration ()
	{
		$registry = MVCLite_Controller_Helper_Registry::getInstance();
		
		$registry->addPath(
			'UnitTest_Controller_Helper_',
			$this->_path
		);
		$registry->addPath(
			'UnitTest_Controller_Helper_',
			$this->_path2
		);
		
		$controller = new UnitTest_ControllerHelper_TestController();
		
		$md5 = $controller->getHelper('MD5');
		
		$this->assertEquals(
			'UnitTest_Controller_Helper_MD5',
			get_class($md5)
		);
		$this->assertEquals(
			$md5,
			$controller->getHelper('MD5')
		);
		$this->assertEquals(
			$controller,
			$md5->getController()
		);
		
		$this->assertEquals(
			$md5,
			$md5->setController(new UnitTest_ControllerHelper_Test2Controller())
		);
		
		$this->assertEquals(
			'UnitTest_ControllerHelper_Test2Controller',
			get_class($md5->getController())
		);
	}
	
	public function testRegistryHelpers ()
	{
		$registry = MVCLite_Controller_Helper_Registry::getInstance();
		
		$registry->addPath(
			'UnitTest_Controller_Helper_',
			$this->_path
		);
		$registry->addPath(
			'UnitTest_Controller_Helper_',
			$this->_path2
		);
		
		try
		{
			$registry->getHelper('Foo');
			$this->assertTrue(
				false,
				'Non-existing helper did not throw any exception'
			);
		}
		catch (MVCLite_Controller_Helper_Exception $e)
		{
			;
		}
		
		$this->assertTrue(
			$registry->loadHelper('MD5') instanceof MVCLite_Controller_Helper_Abstract,
			'Returned helper does not extends MVCLite_Controller_Helper_Abstract'
		);
		
		try
		{
			$registry->loadHelper('FOO');
			$this->assertTrue(
				false,
				'Wrong helper-names should not be converted'
			);
		}
		catch (MVCLite_Controller_Helper_Exception $e)
		{
			;
		}
		
		$this->assertEquals(
			'UnitTest_Controller_Helper_MD5',
			get_class($registry->getHelper('MD5')),
			'Helper "Foobar" could not be retrieved by "getHelper".'
		);
		
		try
		{
			$registry->loadHelper('Bar');
			$this->assertTrue(
				false,
				'Helper not extending MVCLite_Controller_Helper_Abstract was returned'
			);
		}
		catch (MVCLite_Controller_Helper_Exception $e)
		{
			;
		}
		
		$this->assertFalse(
			$registry->helperExists('Bar'),
			'Bar should not exist'
		);
		$registry->loadHelper('Md5');
		$this->assertTrue(
			$registry->helperExists('Md5'),
			'Md5 should be marked as existing.'
		);
	}
	
	public function testRegistryPaths ()
	{
		$registry = MVCLite_Controller_Helper_Registry::getInstance();
		$default = array(
			'MVCLite_Controller_Helper_' => $registry->getPath('MVCLite_Controller_Helper_')
		);
		
		$this->assertEquals(
			'MVCLite_Controller_Helper_Registry',
			get_class($registry),
			'getInstance does not work correctly'
		);
		$this->assertFalse(
			$registry->helperExists('barbar'),
			'Helper "barbar" exists'
		);
		$this->assertEquals(
			$registry->getPath('MVCLite_Controller_Helper_'),
			realpath(MVCLITE_LIB . 'MVCLite/Controller/Helper/') . '/',
			'Default helper path was not added'
		);
		$this->assertEquals(
			'MVCLite_Controller_Helper_Registry',
			get_class($registry->setPath(array())),
			'setPath does not return itself'
		);
		$this->assertEquals(
			'',
			$registry->getPath('MVCLite_Controller_Helper_'),
			'setPath did not work correctly'
		);
		$this->assertEquals(
			'MVCLite_Controller_Helper_Registry',
			get_class(
				$registry->addPath(
					'UnitTest_Controller_Helper_',
					$this->_path
				)
			),
			'addPath does not return itself.'
		);
		$this->assertEquals(
			$this->_path,
			$registry->getPath('UnitTest_Controller_Helper_'),
			'addPath did not work correctly'
		);
		$this->assertEquals(
			'MVCLite_Controller_Helper_Registry',
			get_class($registry->removePath('UnitTest_Controller_Helper_')),
			'removePath does not return itself'
		);
		$this->assertEquals(
			'',
			$registry->getPath('UnitTest_Controller_Helper_'),
			'removePath did not work correctly'
		);
		$registry->setPath($default);
	}
}

// required classes

class UnitTest_ControllerHelper_TestController extends MVCLite_Controller_Abstract
{
	
}

class UnitTest_ControllerHelper_Test2Controller extends MVCLite_Controller_Abstract
{
	
}
?>