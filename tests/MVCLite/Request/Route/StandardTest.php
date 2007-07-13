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
 * Tests the default-route.
 * 
 * @category   MVCLite
 * @package    Request
 * @subpackage UnitTests
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id: RouteTest.php 84 2007-07-11 21:50:48Z andre.moelle $
 */
class MVCLite_Request_Route_StandardTest extends PHPUnit_Framework_TestCase
{
	public function compare (MVCLite_Request $obj, MVCLite_Request $obj2)
	{
		foreach(array('getController', 'getAction', 'getParams') as $method)
		{
			if($obj->$method() != $obj2->$method())
			{
				echo $method . '(' . $obj->$method() . '!=' . $obj2->$method() . ')';
				return false;
			}
		}
		
		return true;
	}

	public function testAssembler ()
	{
		$route = new MVCLite_Request_Route_Standard();
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
			'index/foobar',
			$route->assemble(
				$request->setController('Index')
						->setAction('foobar')
						->setParams(array(), MVCLite_Request::MODUS_RECREATE)
			),
			'index|foobar|null was not recognized correctly'
		);
		$this->assertEquals(
			'foobar/foobar',
			$route->assemble(
				$request->setController('Foobar')
						->setAction('foobar')
						->setParams(array(), MVCLite_Request::MODUS_RECREATE)
			),
			'foobar|foobar|null was not recognized correctly'
		);
		$this->assertEquals(
			'index/index/id.42.html',
			$route->assemble(
				$request->setController('Index')
						->setAction('index')
						->setParams(array('id' => 42), MVCLite_Request::MODUS_RECREATE)
			),
			'index|index|id=42 was not recognized correctly'
		);
		$this->assertEquals(
			'index/index/id.42_foo.bar.html',
			$route->assemble(
				$request->setController('Index')
						->setAction('index')
						->setParams(array('id' => 42, 'foo' => 'bar'), MVCLite_Request::MODUS_RECREATE)
			),
			'index|index|id=42;foo=bar was not recognized correctly'
		);
		$this->assertEquals(
			'bar/foo/id.42_foo.bar.html',
			$route->assemble(
				$request->setController('Bar')
						->setAction('foo')
						->setParams(array('id' => 42, 'foo' => 'bar'), MVCLite_Request::MODUS_RECREATE)
			),
			'bar|foo|id=42;foo=bar was not recognized correctly'
		);
		$this->assertEquals(
			'bar/foo/foo.foo-ist-baer.html',
			$route->assemble(
				$request->setController('Bar')
						->setAction('foo')
						->setParams(array('foo' => 'foo ist bär.'), MVCLite_Request::MODUS_RECREATE)
			),
			'bar|foo|foo="foo ist bär" was not recognized correctly'
		);
		$this->assertEquals(
			'bar/foo/foo.foo-bar.html',
			$route->assemble(
				$request->setController('Bar')
						->setAction('foo')
						->setParams(array('foo' => 'foo   bar'), MVCLite_Request::MODUS_RECREATE)
			),
			'bar|foo|foo="foo   bar" was not recognized correctly'
		);
		$this->assertEquals(
			'bar/foo/foo.foo.-bar.html',
			$route->assemble(
				$request->setController('Bar')
						->setAction('foo')
						->setParams(array('foo' => 'foo._bar'), MVCLite_Request::MODUS_RECREATE)
			),
			'bar|foo|foo="foo._bar" was not recognized correctly'
		);
		$this->assertEquals(
			'bar/foo/some-content.html',
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
		$route = new MVCLite_Request_Route_Standard();
		$request = new MVCLite_Request($route);
		
		$this->assertTrue(
			$this->compare(
				$request,
				$route->parse('')
			),
			'index|index|null parsing failed'
		);
		$this->assertTrue(
			$this->compare(
				$request,
				$route->parse('INDEX/INDEX')
			),
			'index|index|null parsing failed'
		);
		$this->assertTrue(
			$this->compare(
				$request->setController('bar'),
				$route->parse('bar/INDEX')
			),
			'bar|index|null parsing failed'
		);
		$this->assertTrue(
			$this->compare(
				$request->setAction('foo'),
				$route->parse('bar/foo')
			),
			'bar|foo|null parsing failed'
		);
		$this->assertTrue(
			$this->compare(
				$request->setParam('id', '42'),
				$route->parse('bar/foo/id.42.html')
			),
			'bar|foo|id=42 parsing failed'
		);
		$this->assertTrue(
			$this->compare(
				$request->setParam('id', '42.bar'),
				$route->parse('bar/foo/id.42.bar.html')
			),
			'bar|foo|id="42.bar" parsing failed'
		);
		$this->assertTrue(
			$this->compare(
				$request->setParam('id', '42.bar')->setParam('foo', 'bar'),
				$route->parse('bar/foo/id.42.bar_foo.bar.html')
			),
			'bar|foo|id="42.bar";foo=bar parsing failed'
		);
		$this->assertTrue(
			$this->compare(
				$request,
				$route->parse('bar/foo/id.42.bar_.._.__foo.bar.html')
			),
			'bar|foo|id="42.bar";foo=bar parsing failed'
		);
	}
}
?>