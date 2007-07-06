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

require_once 'MVCLite/Request/Exception.php';
require_once 'MVCLite/Request/Global.php';
require_once 'MVCLite/Request/Route.php';
require_once 'MVCLite/Request/Global/Cookie.php';
require_once 'MVCLite/Request/Global/Get.php';
require_once 'MVCLite/Request/Global/Post.php';
require_once 'MVCLite/Request/Global/Request.php';
require_once 'MVCLite/Request/Global/Server.php';
require_once 'MVCLite/Request/Global/Session.php';
require_once 'MVCLite/Request/Global/Synchronizable.php';

/**
 * This class represents a request.
 * 
 * It is instantiated using a route-object. Usually it is the
 * default route-object. Additionally, there are some more
 * features. E.g. you do not have to use superglobals because
 * these are wrapped into objects. Furthermore the synchronizing
 * of them is done here.
 * To convert the request back to a request-uri it contains the
 * route-object. Because this class is used by the controller
 * for dispatching, it contains information about the request,
 * which are necessary for the controller.
 * 
 * @category   MVCLite
 * @package    Request
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id:$
 */
class MVCLite_Request implements MVCLite_Request_Global_Synchronizable
{
	/**
	 * Indicates that the params are deleted before setting new params.
	 * 
	 * @var integer
	 */
	const MODUS_RECREATE = 1;
	
	/**
	 * Indicates that old elements will be overwritten by new ones.
	 * 
	 * @var integer
	 */
	const MODUS_OVERWRITE = 2;
	
	/**
	 * Indicates that old elements will not be overwritten by new ones.
	 * 
	 * @var integer
	 */
	const MODUS_PROTECT = 3;
	
	/**
	 * Name of the action.
	 * 
	 * @var string
	 */
	private $_action = 'index';
	
	/**
	 * Name of the controller.
	 * 
	 * @var string
	 */
	private $_controller = 'Index';
	
	/**
	 * Array containing essential global variables.
	 * 
	 * @var array
	 */
	private $_globals = array();
	
	/**
	 * Array of parameters.
	 * 
	 * @var array
	 */
	private $_params = array();
	
	/**
	 * Instance of a route.
	 * 
	 * @var MVCLite_Request_Route 
	 */
	private $_route;
	
	/**
	 * Constructor makes the object ready.
	 * 
	 * In detail it sets the route-object which this request should
	 * use.
	 * 
	 * @param MVCLite_Request_Route $route route the request should use
	 */
	public function __construct (MVCLite_Request_Route $route)
	{
		$this->_route = $route;
		
		$this->setGlobal(MVCLite_Request_Global_Cookie::getInstance()->set(null))
			 ->setGlobal(MVCLite_Request_Global_Get::getInstance()->set(null))
			 ->setGlobal(MVCLite_Request_Global_Post::getInstance()->set(null))
			 ->setGlobal(MVCLite_Request_Global_Request::getInstance()->set(null))
			 ->setGlobal(MVCLite_Request_Global_Server::getInstance()->set(null))
			 ->setGlobal(MVCLite_Request_Global_Session::getInstance()->set(null));
	}
	
	/**
	 * Converts this object to a string which can be used as URI.
	 * 
	 * @return string
	 */
	public function __toString ()
	{
		return $this->_route->assemble($this);
	}
	
	/**
	 * Checks if the namespace exists.
	 * 
	 * If not, a MVCLite_Request_Exception will be thrown. Otherwise
	 * the namespace-name is returned.
	 * 
	 * @param string $namespace namespace to check
	 * @return string
	 * @throws MVCLite_Request_Exception
	 */
	private function _checkNamespace ($namespace)
	{
		$namespace = strtolower($namespace);
		
		if(isset($this->_globals[$namespace]))
		{
			return $namespace;
		}
		
		throw new MVCLite_Request_Exception(
			'Namespace "' . $namespace . '" does not exist'
		);
	}
	
	/**
	 * 
	 * 
	 * @param string $namespace namespace as defined in $_globals
	 * @param string $name name of variable
	 * @return mixed
	 * @throws MVCLite_Request_Exception
	 */
	private function _get ($namespace, $name = null)
	{
		$namespace = $this->_checkNamespace($namespace);
		
		if($name == null)
		{
			return $this->_globals[$namespace];
		}
		if(!isset($this->_globals[$namespace]->$name))
		{
			return null;
		}
		
		return $this->_globals[$namespace]->$name;
	}
	
	/**
	 * Returns a GET-variable.
	 * 
	 * If no name was specified the entire array is returned.
	 * 
	 * @param string $name optional name
	 * @return mixed
	 */
	public function get ($name = null)
	{
		return $this->_get('get', $name);
	}
	
	/**
	 * Returns the name of the action.
	 * 
	 * @return string
	 */
	public function getAction ()
	{
		return $this->_action;
	}
	
	/**
	 * Returns a cookie-variable.
	 * 
	 * If no name was specified the entire array is returned.
	 * 
	 * @param string $name optional name
	 * @return mixed
	 */
	public function getCookie ($name = null)
	{
		return $this->_get('cookie', $name);
	}
	
	/**
	 * Returns the name of the controller.
	 * 
	 * @return string
	 */
	public function getController ()
	{
		return $this->_controller;
	}
	
	/**
	 * Returns the given parameters.
	 * 
	 * @return array
	 */
	public function getParams ()
	{
		return $this->_params;
	}
	
	/**
	 * Returns a POST-variable.
	 * 
	 * If no name was specified the entire array is returned.
	 * 
	 * @param string $name optional name
	 * @return mixed
	 */
	public function getPost ($name = null)
	{
		return $this->_get('post', $name);
	}
	
	/**
	 * Returns a request-variable.
	 * 
	 * If no name was specified the entire array is returned.
	 * 
	 * @param string $name optional name
	 * @return mixed
	 */
	public function getRequest ($name = null)
	{
		return $this->_get('request', $name);
	}
	
	/**
	 * Returns a server-variable.
	 * 
	 * If no name was specified the entire array is returned.
	 * 
	 * @param string $name optional name
	 * @return mixed
	 */
	public function getServer ($name = null)
	{
		return $this->_get('server', $name);
	}
	
	/**
	 * Returns a session-variable.
	 * 
	 * If no name was specified the entire array is returned.
	 * 
	 * @param string $name optional name
	 * @return mixed
	 */
	public function getSession ($name = null)
	{
		return $this->_get('session', $name);
	}
	
	/**
	 * Sets a new action.
	 * 
	 * @param string $action new action-name
	 * @return MVCLite_Request
	 */
	public function setAction ($action)
	{
		$this->_action = strtolower($action);
		
		return $this;
	}
	
	/**
	 * Sets a new controller.
	 * 
	 * @param string $controller new controller
	 * @return MVCLite_Request
	 */
	public function setController ($controller)
	{
		$this->_controller = ucfirst(strtolower($controller));
		
		return $this;
	}
	
	/**
	 * Sets a new global class.
	 * 
	 * @param MVCLite_Request_Global $global object containing global variables
	 * @return MVCLite_Request
	 */
	public function setGlobal (MVCLite_Request_Global $global)
	{
		$this->_globals[$global->getName()] = $global;
		
		return $this;
	}
	
	/**
	 * Sets only one parameter.
	 * 
	 * You can set only one parameter by using this method. It is based
	 * on the setParams-method which enables you to use a modus as well.
	 * Internally the parameter is transformed to an array.
	 * 
	 * <code>
	 * $name = 'foo';
	 * $value = 'foobar';
	 * // would be transformed to: array('foo' => 'foobar')
	 * </code>
	 * 
	 * @param string $name name of the variable
	 * @param mixed $value value of the variable
	 * @param integer $modus because this method is based on setParams you can apply
	 * 						 the same modi
	 * @return MVCLite_Request
	 */
	public function setParam ($name, $value, $modus = self::MODUS_OVERWRITE)
	{
		return $this->setParams(array($name => $value), $modus);
	}
	
	/**
	 * Sets new parameters.
	 * 
	 * <code>
	 * $params = array(
	 * 	'nameOfParam' => 'contentOfParam',
	 * 	'nameOfAnotherParam' => 'contentOfAnotherParam'
	 * );
	 * </code>
	 * 
	 * This second parameter $modus indicates how the new parameters
	 * are applied. There are some ways which are described in the
	 * class constants MODUS_OVERWRITE, MODUS_PROTECT and MODUS_RECREATE.
	 * MODUS_OVERWRITE is the default modus.
	 * 
	 * @see MVCLite_Request::MODUS_OVERWRITE
	 * @see MVCLite_Request::MODUS_PROTECT
	 * @see MVCLite_Request::MODUS_RECREATE
	 * @param array $params array of parameters
	 * @param integer $modus modus of parameter-setting
	 * @return MVCLite_Request 
	 */
	public function setParams (array $params = array(), $modus = self::MODUS_OVERWRITE)
	{
		switch ($modus)
		{
			case self::MODUS_OVERWRITE:
				$this->_params = array_merge($this->_params, $params);
				
				break;
			
			case self::MODUS_PROTECT:
				foreach(array_keys(array_intersect_key($this->_params, $params)) as $key)
				{
					unset($params[$key]);
				}
				
				$this->_params =  array_merge($this->_params, $params);
				
				break;
				
			default:
			case self::MODUS_RECREATE:
				$this->_params = $params;
				
				break;
		}
		
		return $this;
	}
	
	/**
	 * @see MVCLite_Request_Global_Synchronizable::synchronize()
	 */
	public function synchronize ()
	{
		$result = 0;
		
		foreach($this->_globals as $global)
		{
			if($global instanceof MVCLite_Request_Global_Synchronizable)
			{
				$result += (int)$global->synchronize();
			}
		}
		
		return $result > 0;
	}
}
?>