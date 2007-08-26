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

/**
 * This class is a front-controller for the application.
 * 
 * It runs the application.
 * 
 * @category   MVCLite
 * @package    Core
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id:MVCLite.php 133 2007-08-26 08:19:13Z andre.moelle $
 */
final class MVCLite
{
	/**
	 * Name of the project.
	 * 
	 * @const string
	 */
	const NAME = 'MVCLite';
	
	/**
	 * Version-string.
	 * 
	 * @const string
	 */
	const VERSION = '0.1.2-dev';
	
	/**
	 * Contains the base-url.
	 * 
	 * @var string
	 */
	private $_base;
	
	/**
	 * Instance of the bootstrap class.
	 * 
	 * @var Bootstrap
	 */
	private $_bootstrap;
	
	/**
	 * Determines whether database-errors should be displayed.
	 * 
	 * @var boolean
	 */
	private $_display = false;
	
	/**
	 * Instance of this class.
	 * 
	 * @var MVCLite
	 */
	private static $_instance;
	
	/**
	 * Active route-object.
	 * 
	 * @var MVCLite_Request_Route
	 */
	private $_route;
	
	/**
	 * Constructor makes the object ready and sends header.
	 */
	private function __construct ()
	{
		
	}
	
	/**
	 * Executes the bootstrap class.
	 * 
	 * Each method in the bootstrap class that begins with "init"
	 * is executed.
	 * The bootstrap file resides in the code-directory and is always
	 * named "Bootstrap.php", the class-name is "Bootstrap" as you
	 * surely expected.
	 */
	public function bootstrap ()
	{
		$bootstrap = $this->getBootstrap();
		
		if($bootstrap === false)
		{
			throw new MVCLite_Exception('MVCLite cannot work without bootstrap-file!');
		}
		
		foreach(get_class_methods($bootstrap) as $method)
		{
			if(substr($method, 0, 4) != 'init')
			{
				continue;
			}
			
			$bootstrap->$method();
		}
	}
	
	/**
	 * This method dispatches the request.
	 * 
	 * It is the entry-point to the application.
	 * 
	 * @param string $url complete request-url
	 * @return string
	 */
	public function dispatch ($url)
	{
		try
		{
			$dispatcher = new MVCLite_Request_Dispatcher($this->getRoute());
			$view = $dispatcher->dispatch(substr($url, strlen($this->getBaseUrl())));
		}
		catch (MVCLite_Controller_Exception $e)
		{
			$view = $this->get404($url, $e);
		}
		catch (MVCLite_Request_Dispatcher_Exception $e)
		{
			$view = $this->get404($url, $e);
		}
		catch (MVCLite_Db_Exception $e)
		{
			$view = $this->getDatabaseError($url, $e);
		}
		catch (MVCLite_Security_Exception $e)
		{
			$view = $this->getSecurityIssue($url, $e);
		}
		catch (Exception $e)
		{
			$view = $this->getGeneralError($url, $e);
		}
		
		return $view;
	}
	
	/**
	 * Determines whether errors should be displayed.
	 * 
	 * @param boolean $display true when errors should be displayed
	 * @return MVCLite
	 */
	public function display ($display = false)
	{
		$this->_display = (bool)$display;
		
		return $this;
	}
	
	/**
	 * Returns the template which is displayed on a 404-error.
	 * 
	 * @param string $url requested url
	 * @param MVCLite_Exception $e exception containing some useful information
	 * @return MVCLite_View
	 */
	public function get404 ($url, MVCLite_Exception $e = null)
	{
		$view = new MVCLite_View_Layout();
		$view->setView('_errors/404')
			 ->title = '404 - Not Found';
		
		$subView = $view->getView();
		$subView->requestUrl = $url;
		
		return $view;
	}
	
	/**
	 * Returns the base-url.
	 * 
	 * @return string
	 */
	public function getBaseUrl ()
	{
		return $this->_base;
	}
	
	/**
	 * Returns the bootstrap-object if one exists.
	 * 
	 * @return Bootstrap|false
	 */
	public function getBootstrap ()
	{
		if($this->_bootstrap == null)
		{
			if(!file_exists(MVCLITE_CODE . 'Bootstrap.php'))
			{
				return false;
			}
			
			$this->_bootstrap = new Bootstrap();
		}
		
		return $this->_bootstrap;
	}
	
	/**
	 * Returns a view representing a database error.
	 * 
	 * @param string $url url that was request
	 * @param MVCLite_Db_Exception $e catched database-error
	 * @return MVCLite_View
	 */
	public function getDatabaseError ($url, MVCLite_Db_Exception $e = null)
	{
		$view = new MVCLite_View_Layout();
		$view->setView('_errors/database')
			 ->title = 'Database error';
		
		$subView = $view->getView();
		$subView->render = MVCLite_Db::getInstance()->isDisplayed();
		$subView->requestUrl = $url;
		$subView->execptionObject = $e;
		
		return $view;
	}
	
	/**
	 * Returns a view displaying a general error.
	 * 
	 * @param string $url requested url
	 * @param MVCLite_Exception $e exception containing some useful information
	 * @return MVCLite_View
	 */
	public function getGeneralError ($url, Exception $e = null)
	{
				
		$view = new MVCLite_View_Layout();
		$view->setView('_errors/general')
			 ->title = 'General error';
		
		$subView = $view->getView();
		$subView->render = $this->isDisplayed();
		$subView->requestUrl = $url;
		$subView->exceptionObject = $e;
		
		return $view;
	}
	
	/**
	 * Creates and returns an instance of this class.
	 * 
	 * @param void
	 * @return MVCLite
	 */
	public static function getInstance ()
	{
		if(self::$_instance == null)
		{
			self::$_instance = new self();
			self::$_instance->bootstrap();
		}
		
		return self::$_instance;
	}
	
	/**
	 * Returns the currently active route.
	 * 
	 * @return MVCLite_Request_Route
	 */
	public function getRoute ()
	{
		return $this->_route;
	}
	
	/**
	 * Returns a view displaying a security issue.
	 * 
	 * @param string $url requested url
	 * @param MVCLite_Exception $e exception containing some useful information
	 * @return MVCLite_View
	 */
	public function getSecurityIssue ($url, MVCLite_Security_Exception $e = null)
	{
		$view = new MVCLite_View_Layout();
		$view->setView('_errors/security')
			 ->title = 'Security issue';
		
		$subView = $view->getView();
		$subView->requestUrl = $url;
		$subView->exceptionObject = $e;
		
		return $view;
	}
	
	/**
	 * Returns true when errors should be displayed.
	 * 
	 * @return boolean
	 */
	public function isDisplayed ()
	{
		return $this->_display;
	}
	
	/**
	 * Sets a new route.
	 * 
	 * @param MVCLite_Request_Route $route new route
	 * @return MVCLite
	 */
	public function setRoute (MVCLite_Request_Route $route)
	{
		$this->_route = $route;
		
		return $this;
	}
	
	/**
	 * Sets a new base-url.
	 * 
	 * @param string $url new base-url
	 * @return MVCLite
	 */
	public function setBaseUrl ($url)
	{
		$this->_base = $url;
		
		return $this;
	}
}
?>