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
require_once 'MVCLite/Exception.php';
require_once 'MVCLite/Request.php';
require_once 'MVCLite/Request/Route/Standard.php';
require_once 'MVCLite/Security/Protectable.php';
require_once 'MVCLite/Security/Protector.php';
require_once 'MVCLite/Security/Exception.php';

/**
 * Unit-testing for the protector.
 * 
 * @category   MVCLite
 * @package    Security
 * @subpackage UnitTests
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id: DbTest.php 59 2007-07-08 08:19:43Z andre.moelle $
 */
class MVCLite_Security_ProtectorTest extends PHPUnit_Framework_TestCase
{
	public function testProtector ()
	{
		$request = new MVCLite_Request(new MVCLite_Request_Route_Standard());
		
		$request->setController('Protectortest')
				->setAction('foo');
		
		$controller = new ProtectortestController();
		
		try
		{
			$controller->dispatch($request);
			$this->assertTrue(
				false,
				'Protected action should throw a MVCLite_Security_Exception'
			);
		}
		catch (MVCLite_Security_Exception $e)
		{
			;
		}
		
		$this->assertTrue(
			$controller->isProtected(),
			'Protected action was not protected'
		);
		
		$controller = new ProtectortestController();
		
		try
		{
			$controller->set(true);
			$controller->dispatch($request);
			
			$this->assertTrue(
				false,
				'Protection does not work correctly'
			);
		}
		catch(MVCLite_Security_Exception $e)
		{
			$this->assertTrue(
				false,
				'wasProtected was not called.'
			);
		}
		catch(MVCLite_Exception $e)
		{
			;
		}
		
		$this->assertTrue(
			$controller->isProtected(),
			'Protected action was not protected'
		);
		
		$controller = new ProtectortestController();
		$request->setAction('bar');
		$controller->dispatch($request);
		
		$this->assertFalse(
			$controller->isProtected(),
			'Action was not called correctly.'
		);
	}
}

/*
 * Necessary classes used for this test.
 */

class ProtectortestController extends MVCLite_Controller_Abstract implements MVCLite_Security_Protectable
{
	private $_protected = true;
	private $_state = false;
	
	public function getProtector ()
	{
		return new SampleTestProtector();
	}
	
	public function set ($state)
	{
		$this->_state = $state;
	}
	
	public function fooAction ()
	{
		$this->_protected = false;
	}
	
	public function barAction ()
	{
		$this->_protected = false;
	}
	
	public function isProtected ()
	{
		return $this->_protected;
	}
	
	public function wasProtected ()
	{
		if($this->_state)
		{
			throw new MVCLite_Exception('An unexpected exception');
		}
	}
}

class SampleTestProtector implements MVCLite_Security_Protector
{
	public function protect (MVCLite_Controller_Abstract $controller, $method)
	{
		if($method == 'foo')
		{
			return false;
		}
		
		return true;
	}
}
?>