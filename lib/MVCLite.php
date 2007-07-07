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
 * @version    $Id:$
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
	const VERSION = '0.1.0-dev';
	
	/**
	 * Contains the base-url.
	 * 
	 * @var string
	 */
	private $_base;
	
	/**
	 * Instance of this class.
	 * 
	 * @var MVCLite
	 */
	private static $_instance;
	
	/**
	 * Constructor makes the object ready and sends header.
	 */
	private function __construct ()
	{
		if(!defined('PHPUnit_MAIN_METHOD'))
		{
			header('X-Powered-By: ' . self::NAME . ' ' . self::VERSION);
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
		require_once 'MVCLite/Controller/Exception.php';
		require_once 'MVCLite/Request/Dispatcher.php';
		require_once 'MVCLite/Request/Dispatcher/Exception.php';
		require_once 'MVCLite/Request/Route/Standard.php';
		
		try
		{
			$dispatcher = new MVCLite_Request_Dispatcher(new MVCLite_Request_Route_Standard());
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
		catch (Exception $e)
		{
			$view = $this->getGeneralError($url, $e);
		}
		
		return $view;
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
		require_once 'MVCLite/View/Layout.php';
		
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
		if($this->_base == null)
		{
			if(defined('PHPUnit_MAIN_METHOD'))
			{
				$this->_base = '/';
			}
			else
			{
				$this->_base =	
					dirname($_SERVER['PHP_SELF']) == '/' ? '/'
					: substr(
						$_SERVER['PHP_SELF'],
						0,
						strrpos($_SERVER['PHP_SELF'], '/')
					) . '/';
			}
		}
		
		return $this->_base;
	}
	
	/**
	 * Returns a view displaying a general error.
	 * 
	 * @param string $url requested url
	 * @param MVCLite_Exception $e exception containing some useful information
	 * @return MVCLite_View
	 */
	public function getGeneralError ($url, MVCLite_Exception $e = null)
	{
		require_once 'MVCLite/View/Layout.php';
		
		$view = new MVCLite_View_Layout();
		$view->setView('_errors/general')
			 ->title = 'General error';
		
		$subView = $view->getView();
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
		}
		
		return self::$_instance;
	}
}
?>