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
 * Tests the classic-route.
 * 
 * @category   MVCLite
 * @package    Request
 * @subpackage UnitTests
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_Request_Route_ClassicTest extends PHPUnit_Framework_TestCase
{
	public function compare (MVCLite_Request $obj, MVCLite_Request $obj2)
	{
		foreach(array('getController', 'getAction', 'getParams') as $method)
		{
			if($obj->$method() != $obj2->$method())
			{
				var_dump($obj->$method());
				var_dump($obj2->$method());
				echo $method . '(' . $obj->$method() . '!=' . $obj2->$method() . ')';
				return false;
			}
		}
		
		return true;
	}

	public function testAssembler ()
	{
		$route = new MVCLite_Request_Route_Classic();
		$request = new MVCLite_Request($route);
		
		$this->assertEquals(
			'',
			$route->assemble(
				$request->setController('Index')
						->setAction('index')
						->setParams(array(), MVCLite_Request::MODUS_RECREATE)
			),
			'index|index|null was not recognized correctly'
		);
		$this->assertEquals(
			'?action=foobar',
			$route->assemble(
				$request->setController('Index')
						->setAction('foobar')
						->setParams(array(), MVCLite_Request::MODUS_RECREATE)
			),
			'index|foobar|null was not recognized correctly'
		);
		$this->assertEquals(
			'?controller=foobar&action=foobar',
			$route->assemble(
				$request->setController('Foobar')
						->setAction('foobar')
						->setParams(array(), MVCLite_Request::MODUS_RECREATE)
			),
			'foobar|foobar|null was not recognized correctly'
		);
		$this->assertEquals(
			'?id=42',
			$route->assemble(
				$request->setController('Index')
						->setAction('index')
						->setParams(array('id' => 42), MVCLite_Request::MODUS_RECREATE)
			),
			'index|index|id=42 was not recognized correctly'
		);
		$this->assertEquals(
			'?id=42&foo=bar',
			$route->assemble(
				$request->setController('Index')
						->setAction('index')
						->setParams(array('id' => 42, 'foo' => 'bar'), MVCLite_Request::MODUS_RECREATE)
			),
			'index|index|id=42;foo=bar was not recognized correctly'
		);
		$this->assertEquals(
			'?id=42&foo=bar&controller=bar&action=foo',
			$route->assemble(
				$request->setController('Bar')
						->setAction('foo')
						->setParams(array('id' => 42, 'foo' => 'bar'), MVCLite_Request::MODUS_RECREATE)
			),
			'bar|foo|id=42;foo=bar was not recognized correctly'
		);
		$this->assertEquals(
			'?foo=foo+ist+b%E4r.&controller=bar&action=foo',
			$route->assemble(
				$request->setController('Bar')
						->setAction('foo')
						->setParams(array('foo' => 'foo ist bär.'), MVCLite_Request::MODUS_RECREATE)
			),
			'bar|foo|foo="foo ist bär" was not recognized correctly'
		);
		$this->assertEquals(
			'?foo=foo+++bar&controller=bar&action=foo',
			$route->assemble(
				$request->setController('Bar')
						->setAction('foo')
						->setParams(array('foo' => 'foo   bar'), MVCLite_Request::MODUS_RECREATE)
			),
			'bar|foo|foo="foo   bar" was not recognized correctly'
		);
		$this->assertEquals(
			'?foo=foo._bar&controller=bar&action=foo',
			$route->assemble(
				$request->setController('Bar')
						->setAction('foo')
						->setParams(array('foo' => 'foo._bar'), MVCLite_Request::MODUS_RECREATE)
			),
			'bar|foo|foo="foo._bar" was not recognized correctly'
		);
		$this->assertEquals(
			'?somecontent=&controller=bar&action=foo',
			$route->assemble(
				$request->setController('Bar')
						->setAction('foo')
						->setParams(array('some content'), MVCLite_Request::MODUS_RECREATE)
			),
			'bar|foo|"some content" was not recognized correctly'
		);
	}
	
	public function testParser ()
	{
		$route = new MVCLite_Request_Route_Classic();
		$request = new MVCLite_Request($route);
		
		$this->assertTrue(
			$this->compare(
				$request,
				$route->parse('?')
			),
			'index|index|null parsing failed'
		);
		$this->assertTrue(
			$this->compare(
				$request,
				$route->parse('index.php?controller=INDEX&action=INDEX')
			),
			'index|index|null parsing failed'
		);
		$this->assertTrue(
			$this->compare(
				$request->setController('bar'),
				$route->parse('?controller=bar&action=INDEX')
			),
			'bar|index|null parsing failed'
		);
		$this->assertTrue(
			$this->compare(
				$request->setAction('foo'),
				$route->parse('?controller=bar&action=foo')
			),
			'bar|foo|null parsing failed'
		);
		$this->assertTrue(
			$this->compare(
				$request->setParam('id', '42'),
				$route->parse('?controller=bar&action=foo&id=42')
			),
			'bar|foo|id=42 parsing failed'
		);
		$this->assertTrue(
			$this->compare(
				$request->setParam('id', '42.bar')->setParam('foo', 'bar'),
				$route->parse('?controller=bar&action=foo&id=42.bar&foo=bar')
			),
			'bar|foo|id="42.bar";foo=bar parsing failed'
		);
	}
}
?>