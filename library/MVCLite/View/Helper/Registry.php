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
 * This registry handles everything related to the view-helpers.
 * 
 * How the helpers should be named and constructed, you can see in the
 * class "MVCLite_View_Helper_Abstract". This class stores each helper,
 * probably returns them and delegates calls to them.
 * Additionally, this class is able to add new paths or delete old ones.
 * This enables us to distribute view-helpers everywhere in your
 * application and allows us logical encapsulation.
 * The stored helpers are always stateless to save performance and
 * provide a clean environment, since it excludes errors caused
 * by wrong states.
 * The default helpers available for MVCLite are stored in the path
 * MVCLITE_LIB . 'MVCLite/View/Helper/'. The prefix of them is
 * "MVCLite_View_Helper_". Each helper in this path is provided by
 * default.
 * 
 * @category   MVCLite
 * @package    View
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_View_Helper_Registry
{
	/**
	 * Array of available helpers.
	 * 
	 * It contains already created helpers.
	 * 
	 * @var array
	 */
	private $_helpers = array();
	
	/**
	 * Instance of the registry.
	 * 
	 * @var MVCLite_View_Helper_Registry
	 */
	private static $_instance;
	
	/**
	 * Stores the paths where the helpers can lay.
	 * 
	 * <code>
	 * $array = array(
	 * 	'Prefix_Of_This_Helper_' => '/usr/local/www/app/various/helpers/',
	 * 	'MVCLite_View_Helper_' => MVCLITE_LIB . 'MVCLite/View/Helper/'
	 * );
	 * </code>
	 * 
	 * The key is the prefix for the specified object, following example
	 * will show:
	 * 
	 * <code>
	 * /usr/local/www/app/various/helpers/Foobar.php
	 * // contains the class Prefix_Of_This_Helper_Foobar.
	 * </code>
	 * 
	 * @var array
	 */
	private $_paths = array();
	
	/**
	 * The constructor is hidden since this class is a singleton.
	 * 
	 * It configures the registry by adding a new path for view-helpers.
	 */
	private function __construct ()
	{
		$this->addPath(
			substr(__CLASS__, 0, strrpos(__CLASS__, '_') + 1),
			dirname(__FILE__)
		);
	}
	
	/**
	 * Returns the name of a helper which fits to that name.
	 * 
	 * Firstly it checks for classes that probably exist and
	 * fit to the given helper-name. Thereafter the paths
	 * are checked. When a helper in the paths match, it
	 * is included and thereafter the name of the class
	 * is returned.
	 * If no helper matches an MVCLite_View_Helper_Exception will
	 * be thrown.
	 * 
	 * @param string $name name of the helper to check
	 * @return string
	 * @throws MVCLite_View_Helper_Exception
	 */
	private function _checkHelper ($name)
	{
		foreach(array_keys($this->_paths) as $prefix)
		{
			if(class_exists($prefix . $name, false))
			{
				return $prefix . $name;
			}
		}
		
		foreach($this->_paths as $prefix => $path)
		{
			if(file_exists($path . $name . '.php'))
			{
				require_once $path . $name . '.php';
				return $prefix . $name;
			}
		}
		
		throw new MVCLite_View_Helper_Exception('Helper "' . $name . '" not found');
	}
	
	/**
	 * Returns the formatted helper-name.
	 * 
	 * @param string $name name of a helper
	 * @return string
	 */
	private function _format ($name)
	{
		return ucfirst($name);
	}
	
	/**
	 * Adds a new path to the internal path-array.
	 * 
	 * You should be careful with the prefix, do not forget the
	 * "_", since this is an error often done.
	 * 
	 * @param string $prefix prefix for the classes in that path
	 * @param string $path path where the helpers reside
	 * @return MVCLite_View_Helper_Registry
	 */
	public function addPath ($prefix, $path)
	{
		$this->_paths[$prefix] = realpath($path). '/';
		
		return $this;
	}
	
	/**
	 * Calls a helper with the specified arguments.
	 * 
	 * @param string $helper name of the helper to call
	 * @param array $arguments arguments specified for the call
	 * @return mixed
	 * @throws MVCLite_View_Helper_Exception
	 */
	public function call ($helper, array $arguments = array())
	{
		$obj = $this->loadHelper($helper);
		$method = strtolower($helper);
		
		if(!$arguments)
		{
			return $obj->$method();
		}
		
		return call_user_func_array(array($obj, $method), $arguments);
	}
	
	/**
	 * Returns a helper.
	 * 
	 * If the helper does not exist, a MVCLite_View_Helper_Exception
	 * will be thrown.
	 * 
	 * @param string $name name of the helper
	 * @return MVCLite_View_Helper_Abstract
	 * @throws MVCLite_View_Helper_Exception
	 */
	public function getHelper ($name)
	{
		$name = $this->_format($name);
		
		if($this->helperExists($name))
		{
			return $this->_helpers[$name];
		}
		
		throw new MVCLite_View_Helper_Exception(
			'The helper "' . $name . '" does not exist'
		);
	}
	
	/**
	 * This method returns a registry object.
	 * 
	 * @return MVCLite_View_Helper_Registry
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
	 * Returns the path corresponding to the prefix.
	 * 
	 * @param string
	 * @return string
	 */
	public function getPath ($prefix)
	{
		if(!isset($this->_paths[$prefix]))
		{
			return '';
		}
		
		return $this->_paths[$prefix];
	}
	
	/**
	 * Returns true if the helper already exists in the registry.
	 * 
	 * @param string $name name of the helper
	 * @return boolean
	 */
	public function helperExists ($name)
	{
		return (isset($this->_helpers[$this->_format($name)]));
	}
	
	/**
	 * Loads a helper and returns its instance.
	 * 
	 * If an instance already exists, it is returned instead of
	 * creating a new one. When a helper with that name could
	 * not be loaded, a "MVCLite_View_Helper_Exception" will
	 * be thrown.
	 * 
	 * @param string $name name of the helper
	 * @return MVCLite_View_Helper_Abstract
	 * @throws MVCLite_View_Helper_Exception
	 */
	public function loadHelper ($name)
	{
		$name = $this->_format($name);
		
		try
		{
			return $this->getHelper($name);
		}
		catch (MVCLite_View_Helper_Exception $e)
		{
			;
		}
		
		$class = $this->_checkHelper($name);
		
		$result = new $class();
		
		if($result instanceof MVCLite_View_Helper_Abstract)
		{
			$this->_helpers[$name] = $result;
			return $result;
		}
		
		throw new MVCLite_View_Helper_Exception(
			'The helper with the classname "' . $class . '" does not extend the abstract ' .
			'helper-class.'
		);
	}
	
	/**
	 * Removes a path using the specified prefix.
	 * 
	 * @param string $prefix prefix of the path to remove
	 * @return MVCLite_View_Helper_Registry
	 */
	public function removePath ($prefix)
	{
		if(isset($this->_paths[$prefix]))
		{
			unset($this->_paths[$prefix]);
		}
		
		return $this;
	}
	
	/**
	 * Replaces the old paths array with a new one.
	 * 
	 * @param array $array new path-array
	 * @return MVCLite_View_Helper_Registry
	 */
	public function setPath (array $array)
	{
		$this->_paths = $array;
		
		return $this;
	}
}
?>