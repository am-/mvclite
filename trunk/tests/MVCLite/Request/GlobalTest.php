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
require_once 'MVCLite/Request/Global.php';
require_once 'MVCLite/Request/Global/Cookie.php';
require_once 'MVCLite/Request/Global/Get.php';
require_once 'MVCLite/Request/Global/Post.php';
require_once 'MVCLite/Request/Global/Request.php';
require_once 'MVCLite/Request/Global/Server.php';
require_once 'MVCLite/Request/Global/Session.php';
require_once 'MVCLite/Request/Global/Synchronizable.php';

/**
 * Unit-testing for MVCLite_Global and descendants.
 * 
 * @category   MVCLite
 * @package    Request
 * @subpackage UnitTests
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_Request_GlobalTest extends PHPUnit_Framework_TestCase
{
	public function testCookie ()
	{
		$cookie = MVCLite_Request_Global_Cookie::getInstance();
		
		$this->assertEquals(
			'cookie',
			$cookie->getName(),
			'Name of cookie-instance does not match'
		);
		$this->assertEquals(
			array(),
			$cookie->getChildren(),
			'Cookie is not empty'
		);
		$cookie->foobar = 'bar';
		$this->assertTrue(isset($cookie->foobar), 'Cookie-item "foobar" is not set');
		$this->assertEquals(
			'bar',
			$cookie->foobar,
			'Content of "foobar" could not be retrieved correctly'
		);
		unset($cookie->foobar);
		$this->assertFalse(isset($cookie->foobar), 'Cookie-item "foobar" was not unset');
		
		$array = array(
			'bar' => 'foo',
			'foo' => 'bar',
			'foobar' => 'barfoo'
		);
		
		$this->assertEquals(
			$array,
			$cookie->set($array)->getChildren(),
			'Inserted and returned value do not match'
		);
		
		$newCookie = MVCLite_Request_Global_Cookie::getInstance();
		$this->assertEquals($cookie, $newCookie, 'Singleton does not work correctly');
		
		$newCookie->set(null);
		
		$this->assertEquals(
			array(),
			$newCookie->getChildren(),
			'Cookie is not empty'
		);
		
		$_COOKIE = array('barbar' => 'foobar');
		$newCookie->set(null);
		
		$this->assertEquals(
			$_COOKIE,
			$newCookie->getChildren(),
			'Fetching of superglobals does not work correctly'
		);
		
		$newCookie->foobar = 'bar';
		$newCookie->synchronize();
		
		$this->assertEquals(
			$newCookie->getChildren(),
			$_COOKIE,
			'Synchronizing failed'
		);
		
		$this->assertFalse(
			$newCookie->synchronize(),
			'Synchronizing should be done when something changes'
		);
		
		$newCookie->set(array())->synchronize();
	}
	
	public function testGet ()
	{
		$obj = MVCLite_Request_Global_Get::getInstance();
		$global =& $_GET;
		
		$this->assertEquals(
			'get',
			$obj->getName(),
			'Instance-name does not match'
		);
		$this->assertEquals(
			array(),
			$obj->getChildren(),
			'Instance is not empty'
		);
		$obj->foobar = 'bar';
		$this->assertTrue(isset($obj->foobar), 'Item "foobar" is not set');
		$this->assertEquals(
			'bar',
			$obj->foobar,
			'Content of "foobar" could not be retrieved correctly'
		);
		unset($obj->foobar);
		$this->assertFalse(isset($obj->foobar), 'Item "foobar" was not unset');
		
		$array = array(
			'bar' => 'foo',
			'foo' => 'bar',
			'foobar' => 'barfoo'
		);
		
		$this->assertEquals(
			$array,
			$obj->set($array)->getChildren(),
			'Inserted and returned value do not match'
		);
		
		$newObj = MVCLite_Request_Global_Get::getInstance();
		$this->assertEquals($obj, $newObj, 'Singleton does not work correctly');
		
		$newObj->set(null);
		
		$this->assertEquals(
			array(),
			$newObj->getChildren(),
			'Instance is not empty'
		);
		
		$global = array('barbar' => 'foobar');
		$newObj->set(null);
		
		$this->assertEquals(
			$global,
			$newObj->getChildren(),
			'Fetching of superglobals does not work correctly'
		);
		
		$global = array();
	}
	
	public function testPost ()
	{
		$obj = MVCLite_Request_Global_Post::getInstance();
		$global =& $_POST;
		
		$this->assertEquals(
			'post',
			$obj->getName(),
			'Instance-name does not match'
		);
		$this->assertEquals(
			array(),
			$obj->getChildren(),
			'Instance is not empty'
		);
		$obj->foobar = 'bar';
		$this->assertTrue(isset($obj->foobar), 'Item "foobar" is not set');
		$this->assertEquals(
			'bar',
			$obj->foobar,
			'Content of "foobar" could not be retrieved correctly'
		);
		unset($obj->foobar);
		$this->assertFalse(isset($obj->foobar), 'Item "foobar" was not unset');
		
		$array = array(
			'bar' => 'foo',
			'foo' => 'bar',
			'foobar' => 'barfoo'
		);
		
		$this->assertEquals(
			$array,
			$obj->set($array)->getChildren(),
			'Inserted and returned value do not match'
		);
		
		$newObj = MVCLite_Request_Global_Post::getInstance();
		$this->assertEquals($obj, $newObj, 'Singleton does not work correctly');
		
		$newObj->set(null);
		
		$this->assertEquals(
			array(),
			$newObj->getChildren(),
			'Instance is not empty'
		);
		
		$global = array('barbar' => 'foobar');
		$newObj->set(null);
		
		$this->assertEquals(
			$global,
			$newObj->getChildren(),
			'Fetching of superglobals does not work correctly'
		);
		
		$global = array();
	}
	
	public function testRequest ()
	{
		$obj = MVCLite_Request_Global_Request::getInstance();
		$global =& $_REQUEST;
		
		$this->assertEquals(
			'request',
			$obj->getName(),
			'Instance-name does not match'
		);
		$this->assertEquals(
			array(),
			$obj->getChildren(),
			'Instance is not empty'
		);
		$obj->foobar = 'bar';
		$this->assertTrue(isset($obj->foobar), 'Item "foobar" is not set');
		$this->assertEquals(
			'bar',
			$obj->foobar,
			'Content of "foobar" could not be retrieved correctly'
		);
		unset($obj->foobar);
		$this->assertFalse(isset($obj->foobar), 'Item "foobar" was not unset');
		
		$array = array(
			'bar' => 'foo',
			'foo' => 'bar',
			'foobar' => 'barfoo'
		);
		
		$this->assertEquals(
			$array,
			$obj->set($array)->getChildren(),
			'Inserted and returned value do not match'
		);
		
		$newObj = MVCLite_Request_Global_Request::getInstance();
		$this->assertEquals($obj, $newObj, 'Singleton does not work correctly');
		
		$newObj->set(null);
		
		$this->assertEquals(
			array(),
			$newObj->getChildren(),
			'Instance is not empty'
		);
		
		$global = array('barbar' => 'foobar');
		$newObj->set(null);
		
		$this->assertEquals(
			$global,
			$newObj->getChildren(),
			'Fetching of superglobals does not work correctly'
		);
		
		$global = array();
	}
	
	public function testServer ()
	{
		$obj = MVCLite_Request_Global_Server::getInstance();
		$old = $_SERVER;
		$_SERVER = array();
		$global =& $_SERVER;
		
		$this->assertEquals(
			'server',
			$obj->getName(),
			'Instance-name does not match'
		);
		$this->assertEquals(
			array(),
			$obj->getChildren(),
			'Instance is not empty'
		);
		$obj->foobar = 'bar';
		$this->assertTrue(isset($obj->foobar), 'Item "foobar" is not set');
		$this->assertEquals(
			'bar',
			$obj->foobar,
			'Content of "foobar" could not be retrieved correctly'
		);
		unset($obj->foobar);
		$this->assertFalse(isset($obj->foobar), 'Item "foobar" was not unset');
		
		$array = array(
			'bar' => 'foo',
			'foo' => 'bar',
			'foobar' => 'barfoo'
		);
		
		$this->assertEquals(
			$array,
			$obj->set($array)->getChildren(),
			'Inserted and returned value do not match'
		);
		
		$newObj = MVCLite_Request_Global_Server::getInstance();
		$this->assertEquals($obj, $newObj, 'Singleton does not work correctly');
		
		$newObj->set(null);
		
		$this->assertEquals(
			array(),
			$newObj->getChildren(),
			'Instance is not empty'
		);
		
		$global = array('barbar' => 'foobar');
		$newObj->set(null);
		
		$this->assertEquals(
			$global,
			$newObj->getChildren(),
			'Fetching of superglobals does not work correctly'
		);
		
		$global = $old;
	}
	
	public function testSession ()
	{
		$session = MVCLite_Request_Global_Session::getInstance();
		
		$this->assertEquals(
			'session',
			$session->getName(),
			'Name of cookie-instance does not match'
		);
		$this->assertEquals(
			array(),
			$session->getChildren(),
			'Cookie is not empty'
		);
		$session->foobar = 'bar';
		$this->assertTrue(isset($session->foobar), 'Cookie-item "foobar" is not set');
		$this->assertEquals(
			'bar',
			$session->foobar,
			'Content of "foobar" could not be retrieved correctly'
		);
		unset($session->foobar);
		$this->assertFalse(isset($session->foobar), 'Cookie-item "foobar" was not unset');
		
		$array = array(
			'bar' => 'foo',
			'foo' => 'bar',
			'foobar' => 'barfoo'
		);
		
		$this->assertEquals(
			$array,
			$session->set($array)->getChildren(),
			'Inserted and returned value do not match'
		);
		
		$newSession = MVCLite_Request_Global_Session::getInstance();
		$this->assertEquals($session, $newSession, 'Singleton does not work correctly');
		
		$newSession->set(null);
		
		$this->assertEquals(
			array(),
			$newSession->getChildren(),
			'Cookie is not empty'
		);
		
		$_SESSION = array('barbar' => 'foobar');
		$newSession->set(null);
		
		$this->assertEquals(
			$_SESSION,
			$newSession->getChildren(),
			'Fetching of superglobals does not work correctly'
		);
		
		$newSession->foobar = 'bar';
		$newSession->synchronize();
		
		$this->assertEquals(
			$newSession->getChildren(),
			$_SESSION,
			'Synchronizing failed'
		);
		
		$this->assertFalse(
			$newSession->synchronize(),
			'Synchronizing should be done when something changes'
		);
		
		$newSession->set(array())->synchronize();
	}
} 
?>