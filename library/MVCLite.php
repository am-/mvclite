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
	 * Instance of the MVCLite_Error-class.
	 * 
	 * @var MVCLite_Error
	 */
	private $_error;
	
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
	 */
	public function bootstrap ($profile)
	{
		$bootstrap = $this->getBootstrap($profile);
		
		if($bootstrap === false)
		{
			throw new MVCLite_Exception('MVCLite cannot work without bootstrap-file!');
		}
		
		$bootstrap->bootstrap();
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
		catch (Exception $e)
		{
			if($e instanceof MVCLite_Exception)
			{
				$e->setUrl($url);
			}
			
			$view = $this->getError()
						 ->handle($e);
		}
		
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
	 * @param string $profile profile used for the bootstrap
	 * @return Bootstrap|false
	 */
	public function getBootstrap ($profile = 'development')
	{
		if($this->_bootstrap == null)
		{
			if(!file_exists(MVCLITE_CODE . 'Bootstrap.php'))
			{
				return false;
			}
			
			$this->_bootstrap = new Bootstrap($profile);
		}
		
		return $this->_bootstrap;
	}
	
	/**
	 * Returns the error-object.
	 * 
	 * @return MVCLite_Error
	 */
	public function getError ()
	{
		if($this->_error == null)
		{
			$this->setError(new MVCLite_Error())
				 ->getError()
				 ->attach(new MVCLite_Error_Database())
				 ->attach(new MVCLite_Error_General())
				 ->attach(new MVCLite_Error_NotFound())
				 ->attach(new MVCLite_Error_Security());
		}
		
		return $this->_error;
	}
	
	/**
	 * Creates and returns an instance of this class.
	 * 
	 * @param string $profile current profile
	 * @return MVCLite
	 */
	public static function getInstance ($profile = 'development')
	{
		if(self::$_instance == null)
		{
			self::$_instance = new self();
			self::$_instance->bootstrap($profile);
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
	 * Sets a new error-object.
	 * 
	 * @param MVCLite_Error $error new object
	 * @return MVCLite
	 */
	public function setError (MVCLite_Error $error)
	{
		$this->_error = $error;
		
		return $this;
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