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
 * @copyright  2007-2008 Nordic Development
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
	 * Dispatcher this FrontController uses.
	 * 
	 * @var MVCLite_Request_Dispatcher
	 */
	private $_dispatcher;
	
	/**
	 * Instance of the MVCLite_Error-class.
	 * 
	 * @var MVCLite_Error
	 */
	private $_error;
	
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
			$view = $this->getDispatcher()
			             ->dispatch(substr($url, strlen(MVCLITE_BASE_URL)));
		}
		catch (Exception $e)
		{
			if($e instanceof MVCLite_Exception)
			{
				$e->setUrl($url);
			}
			
			$view = $this->getError()->handle($e);
		}
		
		return $view;
	}
	
	/**
	 * Returns the dispatcher.
	 * 
	 * @return MVCLite_Request_Dispatcher
	 * @throws MVCLite_Exception
	 */
	public function getDispatcher ()
	{
		if($this->_dispatcher == null)
		{
			$this->setDispatcher(
				new MVCLite_Request_Dispatcher(new MVCLite_Request_Route_Standard())
			);
		}
		
		return $this->_dispatcher;
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
			$this->setError(new MVCLite_Error());
		}
		
		return $this->_error;
	}
	
	/**
	 * Sets the new dispatcher.
	 * 
	 * This method is fluent.
	 * 
	 * @param MVCLite_Request_Dispatcher $dispatcher
	 * @return MVCLite
	 */
	public function setDispatcher (MVCLite_Request_Dispatcher $dispatcher)
	{
		$this->_dispatcher = $dispatcher;
		return $this;
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
}
?>