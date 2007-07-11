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
 * Unit-testing for MVCLite.
 * 
 * @category   MVCLite
 * @package    Core
 * @subpackage UnitTests
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLiteTest extends PHPUnit_Framework_TestCase
{
	public function compare (MVCLite_View $view = null, MVCLite_View $view2 = null)
	{
		if( $view == null || $view2 == null ||
			get_class($view) != get_class($view2))
		{
			return false;
		}
		
		$methods = array(
			'getTemplate'
		);
		
		if($view instanceof MVCLite_View_Layout)
		{
			$methods[] = 'getViewTemplate';
		}
		
		foreach($methods as $method)
		{
			if($view->$method() != $view2->$method())
			{
				return false;
			}
		}
		
		return true;
	}
	
	public function testDispatch ()
	{
		$mvc = MVCLite::getInstance();
		
		$class = ucfirst(strtolower(__CLASS__)) . MVCLite_Loader::getSuffix(MVCLite_Loader::CONTROLLER);
		$path = MVCLITE_CONTROLLER . $class . '.php';
		
		$request = new MVCLite_Request(new MVCLite_Request_Route_Standard());
		$request->setController(ucfirst(strtolower(__CLASS__)));
		
		$this->assertTrue(
			$mvc->display(true)->isDisplayed(),
			'Display-method does not function correctly'
		);
		$this->assertFalse(
			$mvc->display(false)->isDisplayed(),
			'Display-method does not function correctly'
		);
		$this->assertFalse(
			$mvc->display()->isDisplayed(),
			'Display-method does not function correctly'
		);
		
		$this->assertTrue(
			$this->compare(
				$mvc->get404((string)$request),
				$mvc->dispatch('/' . (string)$request)
			),
			'Unknown controller should produce a 404'
		);
		
		file_put_contents(
			$path,
			"<?php\n" .
			"class $class extends MVCLite_Controller_Abstract\n" .
			"{\n" .
			"	public function indexAction () { }\n" .
			"}"
		);
		$request->setController(__CLASS__)
				->setAction('bar');
		$this->assertTrue(
			$this->compare(
				$mvc->get404((string)$request),
				$mvc->dispatch('/' . (string)$request)
			),
			'Unknown action should produce a 404'
		);
		$request->setController(__CLASS__)
				->setAction('index');
		$this->assertFalse(
			$this->compare(
				$mvc->get404((string)$request),
				$mvc->dispatch('/' . (string)$request)
			),
			'Valid action should not produce a 404 error'
		);
		
		unlink($path);
		
		$class = ucfirst(strtolower(__CLASS__)) . '2' . MVCLite_Loader::getSuffix(MVCLite_Loader::CONTROLLER);
		$path = MVCLITE_CONTROLLER . $class . '.php';
		
		file_put_contents(
			$path,
			"<?php\n" .
			"class $class extends MVCLite_Controller_Abstract\n" .
			"{\n" .
			"	public function indexAction ()\n" .
			"	{\n" .
			"		throw new MVCLite_Exception('General error');\n" .
			"	}\n" .
			"	public function errorAction ()\n" .
			"	{\n" .
			"		throw new MVCLite_Db_Exception('Database error');\n" .
			"	}\n" .
			"	public function securityAction ()\n" .
			"	{\n" .
			"		throw new MVCLite_Security_Exception('Security issue');\n" .
			"	}\n" .
			"}"
		);
		$request->setController(__CLASS__ . '2')
				->setAction('index');
		$this->assertTrue(
			$this->compare(
				$mvc->getGeneralError((string)$request),
				$mvc->dispatch('/' . (string)$request)
			),
			'Thrown exception should produce a general error'
		);
		$mvc->display(true);
		$this->assertTrue(
			$mvc->dispatch('/' . (string)$request)->getView()->render,
			'General error gets not rendered'
		);
		$mvc->display(false);
		$this->assertFalse(
			$mvc->dispatch('/' . (string)$request)->getView()->render,
			'General error gets rendered'
		);
		
		$request->setController(__CLASS__ . '2')
				->setAction('error');
		$this->assertTrue(
			$this->compare(
				$mvc->getDatabaseError((string)$request),
				$mvc->dispatch('/' . (string)$request)
			),
			'Thrown exception should produce a database error'
		);
		MVCLite_Db::getInstance()->display(true);
		$this->assertTrue(
			$mvc->dispatch('/' . (string)$request)->getView()->render,
			'Database error gets not rendered'
		);
		MVCLite_Db::getInstance()->display(false);
		$this->assertFalse(
			$mvc->dispatch('/' . (string)$request)->getView()->render,
			'Database error gets rendered'
		);
		
		$request->setController(__CLASS__ . '2')
				->setAction('security');
		$this->assertTrue(
			$this->compare(
				$mvc->getSecurityIssue((string)$request),
				$mvc->dispatch('/' . (string)$request)
			),
			'Thrown exception should produce a security-issue'
		);
		
		unlink($path);
	}
}
?>