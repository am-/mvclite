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
 * 
 * @category   MVCLite
 * @package    Controller
 * @subpackage Helper
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_Controller_Helper_Registry
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
	 * @var MVCLite_Controller_Helper_Registry
	 */
	private static $_instance;
	
	/**
	 * Stores the paths where the helpers can lay.
	 * 
	 * <code>
	 * $array = array(
	 * 	'Prefix_Of_This_Helper_' => '/usr/local/www/app/various/helpers/',
	 * 	'MVCLite_Controller_Helper_' => MVCLITE_LIB . 'MVCLite/Controller/Helper/'
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
	 * If no helper matches an MVCLite_Controller_Helper_Exception will
	 * be thrown.
	 * 
	 * @param string $name name of the helper to check
	 * @return string
	 * @throws MVCLite_Controller_Helper_Exception
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
		
		throw new MVCLite_Controller_Helper_Exception('Helper "' . $name . '" not found');
	}
	
	/**
	 * Adds a new path to the internal path-array.
	 * 
	 * You should be careful with the prefix, do not forget the
	 * "_", since this is an error often done.
	 * 
	 * @param string $prefix prefix for the classes in that path
	 * @param string $path path where the helpers reside
	 * @return MVCLite_Controller_Helper_Registry
	 */
	public function addPath ($prefix, $path)
	{
		$this->_paths[$prefix] = realpath($path). '/';
		
		return $this;
	}
	
	/**
	 * Returns a helper.
	 * 
	 * If the helper does not exist, a MVCLite_Controller_Helper_Exception
	 * will be thrown.
	 * 
	 * @param string $name name of the helper
	 * @return MVCLite_Controller_Helper_Abstract
	 * @throws MVCLite_Controller_Helper_Exception
	 */
	public function getHelper ($name)
	{
		if($this->helperExists($name))
		{
			return $this->_helpers[$name];
		}
		
		throw new MVCLite_Controller_Helper_Exception(
			'The helper "' . $name . '" does not exist'
		);
	}
	
	/**
	 * This method returns a registry object.
	 * 
	 * @return MVCLite_Controller_Helper_Registry
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
		return (isset($this->_helpers[$name]));
	}
	
	/**
	 * Loads a helper and returns its instance.
	 * 
	 * If an instance already exists, it is returned instead of
	 * creating a new one. When a helper with that name could
	 * not be loaded, a "MVCLite_Controller_Helper_Exception" will
	 * be thrown.
	 * 
	 * @param string $name name of the helper
	 * @return MVCLite_Controller_Helper_Abstract
	 * @throws MVCLite_Controller_Helper_Exception
	 */
	public function loadHelper ($name)
	{
		try
		{
			return $this->getHelper($name);
		}
		catch (MVCLite_Controller_Helper_Exception $e)
		{
			;
		}
		
		$class = $this->_checkHelper($name);
		
		$result = new $class();
		
		if($result instanceof MVCLite_Controller_Helper_Abstract)
		{
			$this->_helpers[$name] = $result;
			return $result;
		}
		
		throw new MVCLite_Controller_Helper_Exception(
			'The helper with the classname "' . $class . '" does not extend the abstract ' .
			'helper-class.'
		);
	}
	
	/**
	 * Removes a path using the specified prefix.
	 * 
	 * @param string $prefix prefix of the path to remove
	 * @return MVCLite_Controller_Helper_Registry
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
	 * @return MVCLite_Controller_Helper_Registry
	 */
	public function setPath (array $array)
	{
		$this->_paths = $array;
		
		return $this;
	}
}
?>