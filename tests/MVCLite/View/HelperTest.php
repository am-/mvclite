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
 * @package    View
 * @subpackage UnitTests
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id: LayoutTest.php 84 2007-07-11 21:50:48Z andre.moelle $
 */
class MVCLite_View_HelperTest extends PHPUnit_Framework_TestCase
{
	private $_path;
	private $_path2;
	
	public function setUp ()
	{
		$this->_path = realpath(MVCLITE_APP . 'viewHelpers/') . '/';
		$this->_path2 = realpath(MVCLITE_APP . 'viewHelpers/') . '/';
		
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
			"class UnitTest_View_Helper_add extends MVCLite_View_Helper_Abstract\n" .
			"{\n" .
			"	public function add (\$sum1, \$sum2) { return \$sum1 + \$sum2; }\n" .
			"}\n" .
			"?>"
		);
		file_put_contents(
			$this->_path2 . 'Md5.php',
			"<?php\n" .
			"class UnitTest_View_Helper_Md5 extends MVCLite_View_Helper_Abstract\n" .
			"{\n" .
			"	public function md5 (\$string) { return md5(\$string); }\n" .
			"}\n" .
			"?>"
		);
		file_put_contents(
			$this->_path . 'Foobar.php',
			"<?php\n" .
			"class UnitTest_View_Helper_Foobar extends MVCLite_View_Helper_Abstract\n" .
			"{\n" .
			"	public function foobar () { return 'foobar'; }\n" .
			"}\n" .
			"?>"
		);
		file_put_contents(
			$this->_path . 'Foo.php',
			"<?php\n" .
			"class UnitTest_View_Helper_Foo extends MVCLite_View_Helper_Abstract\n" .
			"{\n" .
			"	public function foo () { return 'foobar'; }\n" .
			"}\n" .
			"?>"
		);
		file_put_contents(
			$this->_path2 . 'Bar.php',
			"<?php\n" .
			"class UnitTest_View_Helper_Bar\n" .
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
		$registry = MVCLite_View_Helper_Registry::getInstance();
		
		$registry->addPath(
			'UnitTest_View_Helper_',
			$this->_path
		);
		$registry->addPath(
			'UnitTest_View_Helper_',
			$this->_path2
		);
		
		$view = new MVCLite_View();
		$view->setTemplate('viewHelperTest');
		
		$this->assertEquals(
			md5('foobar') .
			"4" .
			"foobar",
			substr($view->render(), 0, 32 + 1 + 6),
			'Calls were not appropriate.'
		);
	}
	
	public function testRegistryHelpers ()
	{
		$registry = MVCLite_View_Helper_Registry::getInstance();
		
		$registry->addPath(
			'UnitTest_View_Helper_',
			$this->_path
		);
		$registry->addPath(
			'UnitTest_View_Helper_',
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
		catch (MVCLite_View_Helper_Exception $e)
		{
			;
		}
		
		$this->assertTrue(
			$registry->loadHelper('Md5') instanceof MVCLite_View_Helper_Abstract,
			'Returned helper does not extends MVCLite_View_Helper_Abstract'
		);
		
		$this->assertEquals(
			$registry->loadHelper('Foobar'),
			$registry->loadHelper('foobar'),
			'It does not matter whether the first letter is capitalized'
		);
		
		try
		{
			$registry->loadHelper('FOO');
			$this->assertTrue(
				false,
				'Wrong helper-names should not be converted'
			);
		}
		catch (MVCLite_View_Helper_Exception $e)
		{
			;
		}
		
		$this->assertEquals(
			'UnitTest_View_Helper_Foobar',
			get_class($registry->getHelper('Foobar')),
			'Helper "Foobar" could not be retrieved by "getHelper".'
		);
		
		try
		{
			$registry->loadHelper('Bar');
			$this->assertTrue(
				false,
				'Helper not extending MVCLite_View_Helper_Abstract was returned'
			);
		}
		catch (MVCLite_View_Helper_Exception $e)
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
		
		$this->assertEquals(
			md5('foobar'),
			$registry->call('Md5', array('foobar')),
			'Helper does not work correctly'
		);
		$this->assertEquals(
			4,
			$registry->call('add', array(2, 2)),
			'Helper does not work correctly'
		);
		$this->assertEquals(
			'foobar',
			$registry->call('foobar'),
			'Helper does not work correctly'
		);
		
		try
		{
			$registry->call('bar');
			$this->assertTrue(
				false,
				'Invalid helper should not be able to call'
			);
		}
		catch (MVCLite_View_Helper_Exception $e)
		{
			;
		}
	}
	
	public function testRegistryPaths ()
	{
		$registry = MVCLite_View_Helper_Registry::getInstance();
		
		$this->assertEquals(
			'MVCLite_View_Helper_Registry',
			get_class($registry),
			'getInstance does not work correctly'
		);
		$this->assertFalse(
			$registry->helperExists('barbar'),
			'Helper "barbar" exists'
		);
		$this->assertEquals(
			$registry->getPath('MVCLite_View_Helper_'),
			realpath(MVCLITE_LIB . 'MVCLite/View/Helper/') . '/',
			'Default helper path was not added'
		);
		$this->assertEquals(
			'MVCLite_View_Helper_Registry',
			get_class($registry->setPath(array())),
			'setPath does not return itself'
		);
		$this->assertEquals(
			'',
			$registry->getPath('MVCLite_View_Helper_'),
			'setPath did not work correctly'
		);
		$this->assertEquals(
			'MVCLite_View_Helper_Registry',
			get_class(
				$registry->addPath(
					'UnitTest_View_Helper_',
					$this->_path
				)
			),
			'addPath does not return itself.'
		);
		$this->assertEquals(
			$this->_path,
			$registry->getPath('UnitTest_View_Helper_'),
			'addPath did not work correctly'
		);
		$this->assertEquals(
			'MVCLite_View_Helper_Registry',
			get_class($registry->removePath('UnitTest_View_Helper_')),
			'removePath does not return itself'
		);
		$this->assertEquals(
			'',
			$registry->getPath('UnitTest_View_Helper_'),
			'removePath did not work correctly'
		);
	}
}
?>