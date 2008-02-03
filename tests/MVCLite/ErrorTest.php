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
require_once 'MVCLite/Error.php';

/**
 * Unit-testing for MVCLite_Error.
 * 
 * @category   MVCLite
 * @package    Error
 * @subpackage UnitTests
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_ErrorTest extends PHPUnit_Framework_TestCase
{
	public function testChain ()
	{
		$error = new MVCLite_Error();
		
		$this->assertEquals(
			array(),
			$error->getChain()
		);
		
		$this->assertEquals(
			$error,
			$error->attach(new UnitTestError_Element())
		);
		
		$chain = $error->getChain();
		$this->assertEquals(
			'UnitTestError_Element',
			get_class($chain['UnitTestError_Element'])
		);
		$this->assertEquals(1, count($chain));
		
		$this->assertEquals(
			$error,
			$error->attach(new UnitTestError_Element())
		);
		$chain = $error->getChain();
		$this->assertEquals(
			'UnitTestError_Element',
			get_class($chain['UnitTestError_Element'])
		);
		$this->assertEquals(1, count($chain));
		
		$this->assertEquals(
			$error,
			$error->detach('UnitTestError_Element')
		);
		$this->assertEquals(
			array(),
			$error->getChain()
		);
		
		$this->assertEquals(
			$error,
			$error->detach('UnitTestError_Element')
		);
	}
	
	public function testHandle ()
	{
		$error = new MVCLite_Error();
		$elements = array(
			new UnitTestError_Element(),
			new UnitTestError_Element2()
		);
		$elements[0]->name = 'Exception';
		$elements[1]->name = 'MVCLite_Exception';
		
		$this->assertEquals(null, $error->handle(new Exception));
		
		foreach($elements as $element)
		{
			$error->attach($element);
		}
		
		$this->assertEquals(
			'Exception',
			$error->handle(new Exception())->name
		);
		$this->assertEquals(
			'MVCLite_Exception',
			$error->handle(new MVCLite_Exception())->name
		);
		$this->assertEquals(
			'MVCLite_Exception',
			$error->handle(new MVCLite_Loader_Exception())->name
		);
		
		
		$this->assertEquals(
			null,
			$error->detach(get_class($elements[0]))->handle(new FooException())
		);
	}
	
	public function testInheritance ()
	{
		$error = new MVCLite_Error();
		
		$this->assertEquals(
			array(
				'Exception',
				'MVCLite_Exception',
				'MVCLite_Loader_Exception'
			),
			$error->parseInheritance(new MVCLite_Loader_Exception())
		);
		$this->assertEquals(
			array(
				'Exception'
			),
			$error->parseInheritance(new Exception())
		);
	}
	
	public function testMatch ()
	{
		$obj = new UnitTestError_Element();
		$array = array(
			'Exception',
			'MVCLite_Exception',
			'MVCLite_Loader_Exception'
		);
		
		$obj->name = 'Exception';
		$this->assertEquals(
			0,
			$obj->match($array)
		);
		
		$obj->name = 'MVCLite_Exception';
		$this->assertEquals(
			1,
			$obj->match($array)
		);
		
		$obj->name = 'MVCLite_Loader_Exception';
		$this->assertEquals(
			2,
			$obj->match($array)
		);
		
		$obj->name = 'MVCLite_View_Exception';
		$this->assertEquals(
			-1,
			$obj->match($array)
		);
	}
	
	public function testMatchExactly ()
	{
		$obj = new UnitTestError_Element();
		$obj->name = 'Exception';
		
		$this->assertFalse($obj->matchExactly(new MVCLite_Exception()));
		$this->assertTrue($obj->matchExactly(new Exception()));
	}
}

// Required classes
class UnitTestError_Element extends MVCLite_Error_Abstract
{
	public $name;
	
	protected function getApplicableName ()
	{
		return $this->name;
	}
	
	public function handle (Exception $e)
	{
		$result = new MVCLite_View_Layout();
		$result->name = $this->getApplicableName();
		
		return $result;
	}
}

class UnitTestError_Element2 extends UnitTestError_Element
{
	
}

class FooException extends Exception { }
?>