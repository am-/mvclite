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
require_once 'MVCLite/Loader.php';
require_once 'MVCLite/Model/Abstract.php';
require_once 'MVCLite/Request.php';
require_once 'MVCLite/Request/Route/Standard.php';
require_once 'MVCLite/View.php';

/**
 * Tests MVCLite_Controller_Abstract.
 * 
 * @category   MVCLite
 * @package    Controller
 * @subpackage UnitTests
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_ControllerTest extends PHPUnit_Framework_TestCase
{
	public function testDispatcher ()
	{
		$controller = new ControllertestController();
		$request = new MVCLite_Request(new MVCLite_Request_Route_Standard());
		$request->setController('Controllertest');
		
		$this->assertEquals(
			'MVCLite_View_Empty',
			get_class($controller->dispatch($request->setAction('empty'))),
			'Empty view was not returned'
		);
		$this->assertTrue(
			!$controller->isLayouted() && !$controller->isDisplayed(),
			'Controller should not be layouted and displayed'
		);
		$this->assertEquals(
			'MVCLite_View_Layout',
			get_class($controller->dispatch($request->setAction('layout'))),
			'Layout view was not returned'
		);
		$this->assertEquals(
			'MVCLite_View_Layout',
			get_class($controller->getLayout()),
			'Layout view was not returned'
		);
		$this->assertTrue(
			$controller->isLayouted() && $controller->isDisplayed(),
			'Controller should be layouted'
		);
		$this->assertEquals(
			'MVCLite_View',
			get_class($controller->dispatch($request->setAction('view'))),
			'Normal view was not returned'
		);
		$this->assertEquals(
			'MVCLite_View',
			get_class($controller->getView()),
			'Normal view was not returned'
		);
		$this->assertTrue(
			!$controller->isLayouted() && $controller->isDisplayed(),
			'Controller should not be layouted, but displayed'
		);
		$this->assertEquals(
			$request,
			$controller->getRequest(),
			'Dispatched request should have been returned'
		);
		$this->assertTrue(
			$controller->initialized,
			'Controller should have been initialized'
		);
		
		$newRequest = new MVCLite_Request(new MVCLite_Request_Route_Standard());
		$this->assertEquals(
			$newRequest,
			$controller->setRequest($newRequest)->getRequest(),
			'Dispatched request should have been returned'
		);
		
		$view = new MVCLite_View();
		$this->assertEquals(
			$view,
			$controller->setView($view)->getView(),
			'Inserted and returned view do not match'
		);
		
		$model = 'Controllertest';
		$class = $model . MVCLite_Loader::getSuffix(MVCLite_Loader::MODEL);
		$path = MVCLITE_MODEL . $model . MVCLite_Loader::getSuffix(MVCLite_Loader::MODEL) . '.php';
		
		file_put_contents(
			$path,
			"<?php\n" .
			"class $class extends MVCLite_Model_Abstract\n" .
			"{\n" .
			"" .
			"}"
		);
		
		$this->assertEquals(
			$class,
			get_class($controller->getModel()),
			'Returned model does not match'
		);
		
		unlink($path);
	}
}

/*
 * Classes required for unittesting.
 */
class ControllertestController extends MVCLite_Controller_Abstract
{
	public $initialized = false;
	
	protected function _init ()
	{
		$this->initialized = true;
	}
	
	public function emptyAction ()
	{
		$this->useView(false);
	}
	
	public function layoutAction ()
	{
		$this->useLayout();
	}
	
	public function viewAction ()
	{
		$this->useView();
		$this->useLayout(false);
	}
}
?>