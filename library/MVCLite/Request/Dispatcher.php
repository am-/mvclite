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
 * The dispatcher parses a request and proxies it to the controller.
 * 
 * The controller finalizes the dispatching-process.
 * 
 * @category   MVCLite
 * @package    Request
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id:Dispatcher.php 133 2007-08-26 08:19:13Z andre.moelle $
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
	 * This method applies dirty data to the new request.
	 * 
	 * That means that globals are not cleant and their input can ergo
	 * be used for the new request. The new request is returned thereafter.
	 * 
	 * @param MVCLite_Request $request contains data from the old request
	 * @param string $url url of the new request
	 * @return MVCLite_Request
	 */
	private function _applyDirty (MVCLite_Request $request, $url)
	{
		$content = array();
		
		foreach($request->getGlobals() as $name => $global)
		{
			$content[$name] = $global->getChildren();
		}
		
		$result = $this->getRoute()->parse($url);
		
		foreach($result->getGlobals() as $name => $global)
		{
			$global->set($content[$name]);
		}
		
		return $result;
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
		while(true)
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
				$registry = MVCLite_Request_Dispatcher_Plugin_Registry::getInstance();
				$class = MVCLite_Loader::loadController($request->getController());
				$controller = new $class();
				
				$controller->setRequest($request);
				$registry->initialize($controller);
				$registry->preProcess();
				
				$result = $controller->dispatch($request);
				
				$registry->postProcess();
				
				return $result;
			}
			catch (MVCLite_Request_Dispatcher_Redirect $e)
			{
				$url = $e->getMessage();
				$code = $e->getCode();
				
				switch ($code)
				{
					case MVCLite_Request_Dispatcher_Redirect::EXTERNAL:
						header('Location: ' . MVCLite::getInstance()->getBaseUrl() . $url);
						exit;
					
					case MVCLite_Request_Dispatcher_Redirect::DIRTY:
						$url = $this->_applyDirty($request, $url);
						
						break;
				}
			}
			catch (MVCLite_Loader_Exception $e)
			{
				throw new MVCLite_Request_Dispatcher_Exception(
					'Controller "' . $request->getController() . '" was not found'
				);
			}
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