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
 * Unittest for the URL-helper.
 * 
 * @category   MVCLite
 * @package    View
 * @subpackage UnitTests
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id: LayoutTest.php 84 2007-07-11 21:50:48Z andre.moelle $
 */
class MVCLite_View_Helper_UrlTest extends PHPUnit_Framework_TestCase
{
	public function testHelper ()
	{
		$helper = new MVCLite_View_Helper_Url();
		
		$this->assertEquals(
			'/',
			$helper->url(),
			'index|index|null was not recognized correctly'
		);
		$this->assertEquals(
			'/foobar',
			$helper->url('foobar'),
			'foobar|index|null was not recognized correctly'
		);
		$this->assertEquals(
			'/foobar/foobar',
			$helper->url('foobar', 'foobar'),
			'foobar|foobar|null was not recognized correctly'
		);
		$this->assertEquals(
			'/foo/bar/id.42.html',
			$helper->url('foo', 'bar', array('id' => 42)),
			'foo|bar|id=42 was not recognized correctly'
		);
	}
}
?>