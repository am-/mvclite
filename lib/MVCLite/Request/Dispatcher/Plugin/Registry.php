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
 * This registry stores the plugins and processes them.
 * 
 * It provides functionalities for retrieving, existence-checking,
 * removing and adding of plugins. Furthermore you can initialize
 * these plugins using the initialize()-method and do the
 * processing via pre- and postProcess().
 * Since it implements a the singleton pattern, the plugins are
 * globally accessible.
 * 
 * @category   MVCLite
 * @package    Request
 * @subpackage Dispatcher
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_Request_Dispatcher_Plugin_Registry
{
	/**
	 * Instance of this class.
	 * 
	 * @var MVCLite_Request_Dispatcher_Plugin_Registry
	 */
	private static $_instance;
	
	/**
	 * All plugin-instances.
	 * 
	 * @var array
	 */
	private $_plugins = array();
	
	/**
	 * This class is a singleton, therefore the constructor is hidden.
	 */
	private function __construct ()
	{
		;
	}
	
	/**
	 * Calls the specified method and parameters to each plugin.
	 * 
	 * @param string $method name of the method to call on them
	 * @param array $arguments arguments needed for the call
	 */
	private function _call ($method, array $arguments = array())
	{
		foreach($this->_plugins as $plugin)
		{
			if($arguments)
			{
				call_user_func_array(array($plugin, $method), $arguments);
			}
			else
			{
				$plugin->$method();
			}
		}
	}
	
	/**
	 * Attaches a new plugin and overwrites the old one if one exists.
	 * 
	 * @param MVCLite_Request_Dispatcher_Plugin_Abstract $plugin new plugin
	 * @return MVCLite_Request_Dispatcher_Plugin_Registry
	 */
	public function attach (MVCLite_Request_Dispatcher_Plugin_Abstract $plugin)
	{
		$this->_plugins[get_class($plugin)] = $plugin;
		
		return $this;
	}
	
	/**
	 * Detaches a plugin from this registry.
	 * 
	 * @param string $name name of the plugin to detach
	 * @return MVCLite_Request_Dispatcher_Plugin_Registry
	 */
	public function detach ($name)
	{
		if(!$this->exists($name))
		{
			throw new MVCLite_Request_Dispatcher_Exception(
				'Plugin of class "' . $name . '" does not exist'
			);
		}
		
		unset($this->_plugins[$name]);
		
		return $this;
	}
	
	/**
	 * Returns true if the plugin already exists.
	 * 
	 * @param string $name name of the plugin to check
	 * @return boolean
	 */
	public function exists ($name)
	{
		return isset($this->_plugins[$name]);
	}
	
	/**
	 * Returns the plugin with the given name.
	 * 
	 * If the plugin does not exist a "MVCLite_Request_Dispatcher_Exception"
	 * will be thrown.
	 * 
	 * @param string $name name of the plugin to return
	 * @return MVCLite_Request_Dispatcher_Plugin_Abstract
	 * @throws MVCLite_Request_Dispatcher_Exception
	 */
	public function get ($name)
	{
		if(!$this->exists($name))
		{
			throw new MVCLite_Request_Dispatcher_Exception(
				'Plugin of class "' . $name . '" does not exist'
			);
		}
		
		return $this->_plugins[$name];
	}
	
	/**
	 * Returns the instance of this class.
	 * 
	 * @return MVCLite_Request_Dispatcher_Plugin_Registry
	 */
	public static function getInstance ()
	{
		if(self::$_instance == null)
		{
			self::$_instance = new self();
		}
		
		return self::$_instance;
	}
	
	/**
	 * Initializes the plugins.
	 * 
	 * The init-method of the plugins is called. Additionally, the
	 * dispatched request is set.
	 * 
	 * @param MVCLite_Controller_Abstract $controller controller that is dispatched
	 */
	public function initialize (MVCLite_Controller_Abstract $controller)
	{
		$this->_call('setController', array($controller));
		$this->_call('init');
	}
	
	/**
	 * Calls the postProcess-method of each plugin.
	 */
	public function postProcess ()
	{
		$this->_call('postProcess');
	}
	
	/**
	 * Calls the preProcess-method of each plugin.
	 */
	public function preProcess ()
	{
		$this->_call('preProcess');
	}
}
?>