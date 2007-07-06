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
require_once 'MVCLite/View.php';
require_once 'MVCLite/View/Exception.php';
require_once 'MVCLite/View/Layout.php';

/**
 * Unit-testing for MVCLite_View_Layout.
 * 
 * @category   MVCLite
 * @package    View
 * @subpackage UnitTests
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id:$
 */
class MVCLite_View_LayoutTest extends PHPUnit_Framework_TestCase
{
	public function testLayout ()
	{
		$layout = new MVCLite_View_Layout();
		
		$this->assertEquals(
			MVCLite_View_Layout::LAYOUT,
			$layout->getTemplate(),
			'Default layout does not match.'
		);
		$this->assertEquals(
			'newLayout',
			$layout->setTemplate('newLayout')->getTemplate(),
			'Inserted layout does not match returned layout'
		);
		
		$layout = new MVCLite_View_Layout('veryNewLayout');
		
		$this->assertEquals(
			'veryNewLayout',
			$layout->getTemplate(),
			'Constructor-layout does not match returned layout'
		);
	}
	
	public function testRender ()
	{
		$layout = new MVCLite_View_Layout('UnitTestLayout');
		$path = MVCLITE_VIEW . 'UnitTestLayout' . $layout->getSuffix();
		$template = MVCLITE_VIEW . 'UnitTestView' . $layout->getSuffix();
		
		file_put_contents(
			$path,
			"header\n" .
			'<?= $this->view->render() ?>' . "\n\n" .
			"footer"
		);
		file_put_contents(
			$template,
			'<?= $this->foobar ?>'
		);
		
		$content = 'foobar';
		$layout->setView('UnitTestView');
		$layout->getView()->foobar = $content;
		
		$this->assertEquals(
			"header\n" .
			"$content\n" .
			"footer",
			$layout->render(),
			'Layout was not rendered correctly'
		);
		
		unlink($path);
		unlink($template);
	}
	
	public function testView ()
	{
		$layout = new MVCLite_View_Layout();
		
		try
		{
			$layout->getView();
			$this->assertTrue(false, 'View was returned although it does not exist');
		}
		catch (MVCLite_View_Exception $e)
		{
			;
		}
		
		$this->assertEquals(
			'',
			$layout->getViewTemplate(),
			'A non-empty template was returned'
		);
		
		$this->assertEquals(
			'foo',
			$layout->setView('foo')->getViewTemplate(),
			'Inserted and returned template do not match'
		);
		
		$view = $layout->getView();
		$layout->setView('bar');
		
		$this->assertEquals(
			$view,
			$layout->setView($view)->getView(),
			'Inserted and returned view do not match'
		);
	}
}
?>