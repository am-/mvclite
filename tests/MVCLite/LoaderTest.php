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
require_once 'MVCLite/Loader.php';
require_once 'MVCLite/Loader/Exception.php';

/**
 * Unit-testing for MVCLite_Loader.
 * 
 * @category   MVCLite
 * @package    Core
 * @subpackage UnitTests
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id:$
 */
class MVCLite_LoaderTest extends PHPUnit_Framework_TestCase
{
	public function testClass ()
	{
		$class = 'MVCLite_Foobar';
		$path = MVCLITE_LIB . str_replace('_', '/', $class) . '.php';
		
		file_put_contents(
			$path,
			"<?php\n" .
			"class $class { }\n" .
			"?>"
		);
		
		$this->assertFalse(class_exists($class, false), 'Class ' . $class . ' already exists');
		MVCLite_Loader::loadClass($class);
		$this->assertTrue(class_exists($class, false), 'Class ' . $class . ' does not exist');
		
		unlink($path);
	}
	
	public function testController ()
	{
		$controller = 'FoobarTest';
		$class = $controller . MVCLite_Loader::getSuffix(MVCLite_Loader::CONTROLLER);
		$path = MVCLITE_CONTROLLER . $class . '.php';
		
		file_put_contents(
			$path,
			"<?php\n" .
			"class $class { }\n" .
			"?>"
		);
		
		$this->assertFalse(class_exists($class, false), 'Controller exists although it should not');
		MVCLite_Loader::loadController($controller);
		$this->assertTrue(class_exists($class, false), 'Controller could not be loaded');
		
		unlink($path);
		
		try
		{
			MVCLite_Loader::loadController('NotExisting');
			$this->assertTrue(
				false,
				'Failed loading of controllers should produce MVCLite_Loader_Exceptions.'
			);
		}
		catch (MVCLite_Loader_Exception $e)
		{
			;
		}
	}
	
	public function testFile ()
	{
		$file = 'tempTest.php';
		$constant = 'TEST_FOOBAR';
		file_put_contents($file, "<?php\ndefine('$constant', 1);\n?>");
		
		$this->assertFalse(defined($constant), 'Constant was already defined');
		
		MVCLite_Loader::loadFile($file);
		
		$this->assertTrue(defined($constant), 'Constant was not defined');
		
		unlink($file);
		
		try
		{
			MVCLite_Loader::loadFile('anyNonExistingFile.txt');
			$this->assertTrue(
				false,
				'Failed loading of files should produce MVCLite_Loader_Exceptions.'
			);
		}
		catch (MVCLite_Loader_Exception $e)
		{
			;
		}
	}
	
	public function testModel ()
	{
		$model = 'FoobarTest';
		$class = $model . MVCLite_Loader::getSuffix(MVCLite_Loader::MODEL);
		$path = MVCLITE_MODEL . $class . '.php';
		
		file_put_contents(
			$path,
			"<?php\n" .
			"class $class { }\n" .
			"?>"
		);
		
		$this->assertFalse(class_exists($class, false), 'Model exists although it should not');
		MVCLite_Loader::loadModel($model);
		$this->assertTrue(class_exists($class, false), 'Model could not be loaded');
		
		unlink($path);
		
		try
		{
			MVCLite_Loader::loadController('NonExistingModel');
			$this->assertTrue(
				false,
				'Failed loading of models should produce MVCLite_Loader_Exceptions.'
			);
		}
		catch (MVCLite_Loader_Exception $e)
		{
			;
		}
	}
	
	public function testSuffix ()
	{
		$this->assertEquals(
			MVCLite_Loader::getSuffix(MVCLite_Loader::CONTROLLER),
			'Controller',
			'Controller-suffix did not match'
		);
		$this->assertEquals(
			MVCLite_Loader::getSuffix(MVCLite_Loader::MODEL),
			'Model',
			'Model-suffix did not match'
		);
		$this->assertEquals(
			MVCLite_Loader::getSuffix(0),
			'',
			'Unknown suffix returned a non-empty string'
		);
	}
}
?>