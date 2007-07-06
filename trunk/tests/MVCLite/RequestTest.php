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
require_once 'MVCLite/Request.php';

/**
 * Unit-testing for MVCLite_Request.
 * 
 * @category   MVCLite
 * @package    Request
 * @subpackage UnitTests
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id:$
 */
class MVCLite_RequestTest extends PHPUnit_Framework_TestCase
{
	public function getRoute ()
	{
		return new UnitTestRoute();
	}
	
	public function testAssemble ()
	{
		$request = new MVCLite_Request($this->getRoute());
		
		$request->setController('foobar')
				->setAction('bar')
				->setParams(array('foo' => 'bar'));
		
		ob_start();
		var_dump($request);
		$result = ob_get_contents();
		ob_end_clean();
		
		$this->assertEquals(
			$result,
			(string)$request,
			'Stringified request does not match predicted URL'
		);
	}
	
	public function testConstruction ()
	{
		$request = new MVCLite_Request($this->getRoute());
		
		$controller = 'foobar';
		$action = 'bar';
		$params = array(
			'fubar' => 'barbar',
			'foobar' => 'burbur',
			'barbar' => 'baz'
		);
		
		$this->assertEquals(
			$controller,
			$request->setController($controller)->getController(),
			'Inserted and returned controller do not match'
		);
		$this->assertEquals(
			$action,
			$request->setAction($action)->getAction(),
			'Inserted and returned action do not match'
		);
		$this->assertEquals(
			$params,
			$request->setParams($params)->getParams(),
			'Inserted and returned params do not match'
		);
		$this->assertEquals(
			array(
				'fubar' => 'foo',
				'bar' => 'foobar',
				'foobar' => 'burbur',
				'barbar' => 'baz'
			),
			$request->setParams(array('fubar' => 'foo', 'bar' => 'foobar'))->getParams(),
			'Overwrite-modus for setParams does not work correctly'
		);
		$this->assertEquals(
			array(
				'fubar' => 'foo',
				'bar' => 'foobar',
				'foobar' => 'burbur',
				'barbar' => 'baz',
				'baz' => 'barbar'
			),
			$request->setParams(
				array('fubar' => 'barbar', 'baz' => 'barbar'),
				MVCLite_Request::MODUS_PROTECT)
				->getParams(),
			'Protected setting for setParams does not work correctly'
		);
		$this->assertEquals(
			array(
				'foo' => 'bar',
				'bar' => 'foo'
			),
			$request->setParams(
				array('foo' => 'bar', 'bar' => 'foo'),
				MVCLite_Request::MODUS_RECREATE)
				->getParams(),
			'Recreate-modus for setParams does not work correctly.'
		);
	}
	
	public function testGlobals ()
	{
		$request = new MVCLite_Request($this->getRoute());
		
		$request->setGlobal(MVCLite_Request_Global_Cookie::getInstance()->set(array('cookie' => 'yes')))
				->setGlobal(MVCLite_Request_Global_Get::getInstance()->set(array('get' => 'yes')))
				->setGlobal(MVCLite_Request_Global_Post::getInstance()->set(array('post' => 'yes')))
				->setGlobal(MVCLite_Request_Global_Request::getInstance()->set(array('request' => 'yes')))
				->setGlobal(MVCLite_Request_Global_Server::getInstance()->set(array('server' => 'yes')))
				->setGlobal(MVCLite_Request_Global_Session::getInstance()->set(array('session' => 'yes')));
		
		$this->assertEquals(
			'yes',
			$request->getCookie('cookie'),
			'Cookie was not set correctly'
		);
		$this->assertEquals(
			'yes',
			$request->get('get'),
			'Get was not set correctly'
		);
		$this->assertEquals(
			'yes',
			$request->getPost('post'),
			'Post was not set correctly'
		);
		$this->assertEquals(
			'yes',
			$request->getRequest('request'),
			'Request was not set correctly'
		);
		$this->assertEquals(
			'yes',
			$request->getServer('server'),
			'Server was not set correctly'
		);
		$this->assertEquals(
			'yes',
			$request->getSession('session'),
			'Session was not set correctly'
		);
		
		
	}
	
	public function testSynchronize ()
	{
		$request = new MVCLite_Request($this->getRoute());
				
		$GLOBALS['foobar'] = array('foobar' => 'foo');
		$global = UnitTestGlobal::getInstance();
		$global->foobar = 'bar';
		$request->setGlobal($global);
		
		$this->assertEquals(
			'foo',
			$GLOBALS['foobar']['foobar'],
			'Global value should be "foo"'
		);
		$this->assertEquals(
			'bar',
			$global->foobar,
			'Local value should be "bar"'
		);
		
		$this->assertTrue(
			$request->synchronize(),
			'Synchronizing should have been successful'
		);
		
		$this->assertEquals(
			$global->foobar,
			$GLOBALS['foobar']['foobar'],
			'Global and local value should be synchronized'
		);
		
		$this->assertFalse(
			$request->synchronize(),
			'Synchronizing should fail, since every container is already synchronized'
		);
	}
}

/*
 * Class declarations which are necessary for unit-testing.
 */
class UnitTestRoute implements MVCLite_Request_Route
{
	public function assemble (MVCLite_Request $request)
	{
		ob_start();
		
		var_dump($request);
		
		$result = ob_get_contents();
		ob_end_clean();
		
		return $result;
	}
}

require_once 'MVCLite/Request/Global.php';
require_once 'MVCLite/Request/Global/Synchronizable.php';

class UnitTestGlobal extends MVCLite_Request_Global implements MVCLite_Request_Global_Synchronizable
{
	protected static $_instance = null;
	
	protected function _getGlobal ()
	{
		if(!isset($GLOBALS['foobar']))
		{
			return array();
		}
		
		return $GLOBALS['foobar'];
	}
	
	public static function getInstance ($array = null)
	{
		if(self::$_instance == null)
		{
			self::$_instance = new self($array);
		}
		
		return self::$_instance;
	}
	
	public function getName ()
	{
		return 'foobar';
	}
	
	public function synchronize ()
	{
		if($this->getChildren() == $this->_getGlobal())
		{
			return false;
		}
		
		$GLOBALS['foobar'] = $this->getChildren();
		
		return true;
	}
}
?>