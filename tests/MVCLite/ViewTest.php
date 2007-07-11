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
 * Unit-testing for MVCLite_View.
 * 
 * @category   MVCLite
 * @package    View
 * @subpackage UnitTests
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_ViewTest extends PHPUnit_Framework_TestCase
{
	public function testAssign ()
	{
		$view = new MVCLite_View();
		
		try
		{
			$view->bar = 'bar';
		}
		catch (MVCLite_View_Exception $e)
		{
			$this->assertTrue(false, 'bar should have been set, setting was not successful');
		}
		
		try
		{
			$view->_bar = 'bar';
			$this->assertTrue(false, '_bar should not have been set, but setting was successful');
		}
		catch (MVCLite_View_Exception $e)
		{
			;
		}
		
		try
		{
			$this->assertEquals($view->bar, 'bar', 'Returned value does not match inserted value');
		}
		catch (MVCLite_View_Exception $e)
		{
			$this->assertTrue(false, 'bar should have been returned, but was not');
		}
		
		
		try
		{
			$view->_bar;
			$this->assertTrue(false, 'Variable was returned although it starts with an underscore');
		}
		catch (MVCLite_View_Exception $e)
		{
			$this->assertTrue(true, 'getting of _bar was not successful');
		}
	}
	
	public function testPath ()
	{
		$view = new MVCLite_View();
		$path = MVCLITE_VIEW . 'fooUnitTest' . $view->getSuffix();
		
		file_put_contents($path, '');
		
		try
		{
			$view->getPath();
			$this->assertTrue(false, 'Path was returned although it cannot exist');
		}
		catch (MVCLite_View_Exception $e)
		{
			;
		}
		
		try
		{
			$this->assertEquals(
				MVCLITE_VIEW . 'fooUnitTest' . $view->getSuffix(),
				$view->setTemplate('fooUnitTest')->getPath(),
				'Inserted path does not match generated path'
			);
		}
		catch (MVCLite_View_Exception $e)
		{
			$this->assertTrue(false, 'Existing path threw an exception');
		}
	}
	
	public function testRender ()
	{
		$view = new MVCLite_View();
		
		$paths = array(
			MVCLITE_VIEW . 'fooUnitTest' . $view->getSuffix(),
			MVCLITE_VIEW . 'barUnitTest' . $view->getSuffix()
		);
		$templates = array(
			'My content is: <?= $this->foo ?>',
			'My name is: <?= $this->foo ?>'
		);
		$content = 'foo';
		
		foreach($paths as $key => $path)
		{
			file_put_contents($path, $templates[$key]);
		}
		
		$view->foo = $content;
		$view->setTemplate('fooUnitTest');
		
		$this->assertEquals(
			'My name is: ' . $content,
			$view->render('barUnitTest'),
			'Rendered output does not match expected output'
		);
		
		// this method checks if the render()-method without argument is rendered
		// correctly and whether the set template by the previous render-call
		// was reverted.
		$this->assertEquals(
			'My content is: ' . $content,
			$view->render(),
			'Rendered output does not match expected output'
		);
		
		try
		{
			$view->render('notExistingTemplate');
			$this->assertTrue(false, 'Non existing template was rendered');
		}
		catch (MVCLite_View_Exception $e)
		{
			;
		}
		try
		{
			$view->setTemplate('notExistingTemplate');
			
			$this->assertEquals(
				'',
				(string)$view,
				'Casting to a string with a non-existing template should return an empty string'
			);
		}
		catch (MVCLite_View_Exception $e)
		{
			;
		}
		
		foreach($paths as $path)
		{
			unlink($path);
		}
	}
	
	public function testSuffix ()
	{
		$view = new MVCLite_View();
		
		$this->assertEquals($view->getSuffix(), MVCLite_View::SUFFIX, 'Default suffix not set');
		$this->assertEquals($view->setSuffix('.php')->getSuffix(), '.php', 'New suffix not set');
		$this->assertEquals($view->setSuffix('phtml')->getSuffix(), '.phtml', 'New suffix (without trailing dot) not set');
	}
	
	public function testTemplate ()
	{
		$view = new MVCLite_View();
		
		$this->assertEquals('', $view->getTemplate(), 'Default template was set');
		$this->assertEquals('tpl', $view->setTemplate('tpl' . $view->getSuffix())->getTemplate(), 'Template-file (with extension) was not set correctly');
		$this->assertEquals('template', $view->setTemplate('template')->getTemplate(), 'Template-file was not set correctly');
		$this->assertEquals('controller/template', $view->setTemplate('controller/template')->getTemplate(), 'Template-file (in subdirectory) was not set correctly');
		
		$view = new MVCLite_View('default');
		$this->assertEquals('default', $view->getTemplate(), 'Given template was not set');
	}
}
?>