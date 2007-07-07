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

require_once 'MVCLite/Loader.php';
require_once 'MVCLite/Request.php';
require_once 'MVCLite/Request/Dispatcher/Exception.php';
require_once 'MVCLite/Request/Route.php';

/**
 * The dispatcher parses a request and proxies it to the controller.
 * 
 * The controller finalizes the dispatching-process.
 * 
 * @category   MVCLite
 * @package    Request
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id:$
 */
class MVCLite_Request_Dispatcher
{
	/**
	 * Route the dispatcher uses.
	 * 
	 * @var MVCLite_Request_Route
	 */
	protected $_route;
	
	/**
	 * Constructor sets the dispatcher up.
	 * 
	 * @param MVCLite_Request_Route $route standard route
	 */
	public function __construct (MVCLite_Request_Route $route)
	{
		$this->setRoute($route);
	}
	
	/**
	 * Dispatches the request.
	 * 
	 * Firstly the URL is parsed to a request-object. Thereafter
	 * the information parsed are used to create the correct
	 * controller, which is dispatched after then.
	 * 
	 * Result is the view that should be rendered.
	 * 
	 * There are some reasons for a failing dispatch-process. When the
	 * requested controller does not exist a "MVCLite_Request_Dispatcher_Exception"
	 * will be thrown. If the requested action does not exist a
	 * "MVCLite_Controller_Exception" will be thrown.
	 * 
	 * @param string|MVCLite_Request $url url to dispatch
	 * @return MVCLite_View|null
	 * @throws MVCLite_Request_Dispatcher_Exception
	 * @throws MVCLite_Controller_Exception
	 * @throws Exception
	 */
	public function dispatch ($url)
	{
		if($url instanceof MVCLite_Request)
		{
			$request = $url;
			$url = (string)$request;
		}
		else
		{
			$request = $this->getRoute()->parse($url);
		}
		
		try
		{
			$class = MVCLite_Loader::loadController($request->getController());
			$controller = new $class();
			
			return $controller->dispatch($request);
		}
		catch(MVCLite_Loader_Exception $e)
		{
			throw new MVCLite_Request_Dispatcher_Exception(
				'Controller "' . $request->getController() . '" was not found'
			);
		}
		
		return null;
	}
	
	/**
	 * Returns the active route.
	 * 
	 * @return MVCLite_Request_Route
	 */
	public function getRoute ()
	{
		return $this->_route;
	}
	
	/**
	 * Sets a new route-object.
	 * 
	 * @param MVCLite_Request_Route $route new route
	 * @return MVCLite_Request_Dispatcher
	 */
	public function setRoute (MVCLite_Request_Route $route)
	{
		$this->_route = $route;
		
		return $this;
	}
}
?>