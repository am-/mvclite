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
require_once 'MVCLite/Controller/Abstract.php';
require_once 'MVCLite/Controller/Exception.php';
require_once 'MVCLite/Request.php';
require_once 'MVCLite/Request/Dispatcher.php';
require_once 'MVCLite/Request/Dispatcher/Exception.php';
require_once 'MVCLite/Request/Route.php';
require_once 'MVCLite/Request/Route/Standard.php';

/**
 * Unit-testing for every basic MVCLite_Request_Dispatcher.
 * 
 * @category   MVCLite
 * @package    Request
 * @subpackage UnitTests
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_Request_DispatcherTest extends PHPUnit_Framework_TestCase
{
	public function testConstruction ()
	{
		$route = new MVCLite_Request_Route_Standard();
		$route2 = new UnitTestFooRoute();
		$dispatcher = new MVCLite_Request_Dispatcher($route);
		
		$this->assertEquals(
			$route,
			$dispatcher->getRoute(),
			'Inserted and returned route are not equal'
		);
		$this->assertEquals(
			$route2,
			$dispatcher->setRoute($route2)->getRoute(),
			'Inserted and returned route do not match'
		);
	}
	
	public function testDispatch ()
	{
		$path = MVCLITE_CONTROLLER . 'Foo' . MVCLite_Controller_Abstract::SUFFIX . '.php';
		
		file_put_contents(
			$path,
			"<?php\n" .
			"class FooController extends MVCLite_Controller_Abstract\n" .
			"{\n" .
			"	public function indexAction () { echo 'foobar'; }\n" .
			"	public function redirectAction () { \$this->getRequest()->get()->foo = 'fubar'; \$this->getHelper('Redirector')->redirect('Foo', 'redirect2', array()); }\n" .
			"	public function redirect2Action () { echo \$this->getRequest()->get()->foo; }\n" .
			"	public function dirtyAction () { \$this->getRequest()->get()->foo = 'fubar'; \$this->getHelper('Redirector')->redirect('Foo', 'dirty2', array(), MVCLite_Request_Dispatcher_Redirect::DIRTY); }\n" .
			"	public function dirty2Action () { echo \$this->getRequest()->get()->foo; }\n" .
			"}"
		);
		
		$dispatcher = new MVCLite_Request_Dispatcher(new MVCLite_Request_Route_Standard());
		$request = new MVCLite_Request($dispatcher->getRoute());
		
		ob_start();
		$dispatcher->dispatch(
			(string)$request->setController('Foo')
		);
		$result = ob_get_contents();
		ob_end_clean();
		
		$this->assertEquals(
			'foobar',
			$result,
			'Output is not correct'
		);
		
		ob_start();
		$dispatcher->dispatch(
			$request->setController('Foo')
					->setAction('redirect')
		);
		$result = ob_get_contents();
		ob_end_clean();
		
		$this->assertEquals(
			'',
			$result,
			'Output is not correct'
		);
		
		ob_start();
		$dispatcher->dispatch(
			$request->setController('Foo')
					->setAction('dirty')
		);
		$result = ob_get_contents();
		ob_end_clean();
		
		$this->assertEquals(
			'fubar',
			$result,
			'Output is not correct'
		);
		
		try
		{
			$dispatcher->dispatch(
				(string)$request->setController('UnitTestUnknown')
			);
			$this->assertTrue(
				false,
				'When an unknown controller is accessed an "MVCLite_Request_Dispatcher_Exception" ' .
				'should be thrown'
			);
		}
		catch (MVCLite_Request_Dispatcher_Exception $e)
		{
			;
		}
		
		try
		{
			$dispatcher->dispatch(
				(string)$request->setController('Foo')->setAction('Undefined')
			);
			$this->assertTrue(
				false,
				'When an unknown action of a controller is accessed an "MVCLite_Controller_Exception" ' .
				'should be thrown'
			);
		}
		catch (MVCLite_Controller_Exception $e)
		{
			;
		}
		
		unlink($path);
	}
}

/*
 * Classes needed for UnitTesting. 
 */
class UnitTestFooRoute implements MVCLite_Request_Route
{
	public function assemble (MVCLite_Request $request)
	{
		return 'foo';
	}
	
	public function parse ($string)
	{
		return new MVCLite_Request($this);
	}
}
?>