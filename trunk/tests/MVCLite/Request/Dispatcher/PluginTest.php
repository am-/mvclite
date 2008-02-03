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
 * Unit-testing for the plugins.
 * 
 * @category   MVCLite
 * @package    Request
 * @subpackage Dispatcher
 * @subpackage UnitTests
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_Request_Dispatcher_PluginTest extends PHPUnit_Framework_TestCase
{
	public function setUp ()
	{
		$path = MVCLITE_CONTROLLER . 'Plugintest' . MVCLite_Controller_Abstract::SUFFIX . '.php';
		
		file_put_contents(
			$path,
			"<?php\n" .
			"class PlugintestController extends MVCLite_Controller_Abstract\n" .
			"{\n" .
			"	public function indexAction () {  }\n" .
			"}"
		);
		
		$plugin = new UnitTest_Dispatcher_SecondPlugin();
		$registry = MVCLite_Request_Dispatcher_Plugin_Registry::getInstance();
		$registry->attach($plugin);
		$dispatcher = new MVCLite_Request_Dispatcher(new MVCLite_Request_Route_Standard());
		
		for($i = 0; $i < 2; $i++)
		{
			$dispatcher->dispatch('plugintest');
			$this->assertEquals(
				4,
				$plugin->integer,
				'Failure in run #' . $i + 1
			);
		}
		
		$registry->detach('UnitTest_Dispatcher_SecondPlugin');
		
	}
	
	public function tearDown ()
	{
		unlink(MVCLITE_CONTROLLER . 'Plugintest' . MVCLite_Controller_Abstract::SUFFIX . '.php');
	}
	
	public function testIntegration ()
	{
		
	}
	
	public function testRegistry ()
	{
		$registry = MVCLite_Request_Dispatcher_Plugin_Registry::getInstance();
		$class = 'MVCLite_Request_Dispatcher_Plugin_Registry';
		$plugin = array(
			new UnitTest_Dispatcher_Plugin(),
			new UnitTest_Dispatcher_SecondPlugin()
		);
		$classes = array(
			'UnitTest_Dispatcher_Plugin',
			'UnitTest_Dispatcher_SecondPlugin'
		);
		
		$this->assertEquals(
			$class,
			get_class($registry),
			'getInstance returns no instance of itself'
		);
		$this->assertEquals(
			$registry,
			MVCLite_Request_Dispatcher_Plugin_Registry::getInstance(),
			'getInstance does not work properly.'
		);
		
		$this->assertEquals(
			$class,
			get_class($registry->attach($plugin [0])),
			'attach does not return instance'
		);
		
		$this->assertTrue(
			$registry->exists($classes[0]),
			'Existing plugin was not recognized'
		);
		$this->assertFalse(
			$registry->exists($classes[1]),
			'Non-existing plugin was recognized'
		);
		
		$this->assertEquals(
			$plugin[0],
			$registry->get($classes[0]),
			'get does not return the correct plugin'
		);
		
		try
		{
			$registry->get($classes[1]);
			
			$this->assertTrue(
				false,
				'get did not throw an exception although the plugin does not exist'
			);
		}
		catch (MVCLite_Request_Dispatcher_Exception $e)
		{
			
		}
		
		$this->assertEquals(
			$class,
			get_class($registry->detach($classes[0])),
			'detach does not return instance'
		);
		
		try
		{
			$registry->detach($classes[0]);
			
			$this->assertTrue(
				false,
				'Detaching a non-existing plugin should throw an exception.'
			);
		}
		catch (MVCLite_Request_Dispatcher_Exception $e)
		{
			
		}
		
		$this->assertFalse(
			$registry->exists($classes[0]),
			'detach does not work properly'
		);
		
		$registry->attach($plugin[0]);
		$registry->attach($plugin[1]);
		
		$controller = new UnitTest_Dispatcher_Plugin_Controller();
		
		$registry->initialize($controller);
		
		$this->assertEquals(
			10,
			$plugin[0]->integer
		);
		$this->assertEquals(
			0,
			$plugin[1]->integer
		);
		$this->assertEquals(
			$controller,
			$plugin[0]->getController()
		);
		$this->assertEquals(
			$controller,
			$plugin[1]->getController()
		);
		
		$registry->preProcess();
		
		$this->assertEquals(
			11,
			$plugin[0]->integer
		);
		$this->assertEquals(
			2,
			$plugin[1]->integer
		);
		
		$registry->postProcess();
		
		$this->assertEquals(
			12,
			$plugin[0]->integer
		);
		$this->assertEquals(
			4,
			$plugin[1]->integer
		);
		
		$registry->initialize($controller);
		$registry->preProcess();
		
		$this->assertEquals(
			13,
			$plugin[0]->integer
		);
		$this->assertEquals(
			2,
			$plugin[1]->integer
		);
	}
}

// necessary plugins for unit-test

class UnitTest_Dispatcher_Plugin_Controller extends MVCLite_Controller_Abstract
{
	
}

class UnitTest_Dispatcher_Plugin extends MVCLite_Request_Dispatcher_Plugin_Abstract
{
	public $integer = 10;
	
	public function postProcess ()
	{
		$this->integer++;
	}
	
	public function preProcess ()
	{
		$this->integer++;
	}
}
class UnitTest_Dispatcher_SecondPlugin extends MVCLite_Request_Dispatcher_Plugin_Abstract
{
	public $integer;
	
	public function init ()
	{
		$this->integer = 0;
	}
	
	public function postProcess ()
	{
		$this->integer += 2;
	}
	
	public function preProcess ()
	{
		$this->integer += 2;
	}
}
?>