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

MVCLite_Loader::loadClass('MVCLite/Loader/Exception.php');

/**
 * This class is used as loader in many occasions
 * 
 * It helps you by mapping class-names to file names or by preventing
 * being addicted to the MVCLITE_*-constants.
 * 
 * @category   MVCLite
 * @package    Core
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_Loader
{
	/**
	 * Identifier for the model.
	 * 
	 * @var integer
	 */
	const MODEL = 1;
	
	/**
	 * Identifier for the controller
	 * 
	 * @var integer
	 */
	const CONTROLLER = 2;
	
	/**
	 * Determines whether an autoloader should be used.
	 * 
	 * @var boolean
	 */
	private static $_autoload = false;
	
	/**
	 * Callback for the autoloader.
	 * 
	 * @var array
	 */
	private static $_callback = array(self, 'loadClass');
	
	/**
	 * Formats a class-name to a filename (without suffix).
	 * 
	 * @param string $class name of the class to format
	 * @return string
	 */
	private static function _format ($class)
	{
		return str_replace('_', '/', $class);
	}
	
	/**
	 * Loads a class using the autoloader.
	 * 
	 * @param string $class class which is required
	 */
	private static function _load ($class)
	{
		include self::_format($class) . '.php';
	}
	
	/**
	 * Returns the suffix for controllers.
	 *
	 * @param string $type  
	 * @return string
	 */
	public static function getSuffix ($type)
	{
		switch ($type)
		{
			case self::CONTROLLER:
				self::loadClass('MVCLite_Controller_Abstract');
				
				return MVCLite_Controller_Abstract::SUFFIX;
				
			case self::MODEL:
				self::loadClass('MVCLite_Model_Abstract');
				
				return MVCLite_Model_Abstract::SUFFIX;
				
			default:
				return '';
		}
	}
	
	/**
	 * Returns whether the autoloader is registered.
	 * 
	 * @return boolean
	 */
	public static function isRegistered ()
	{
		return self::$_autoload;
	}
	
	/**
	 * Loads a class at runtime.
	 * 
	 * This method only uses the include_path which may save
	 * performance. Due to the use of MVCLite_Loader::loadClass(, the application
	 * stops if the class cannot be found.
	 * 
	 * @param string $name name of the class
	 */
	public static function loadClass ($name)
	{
		if(!self::isRegistered())
		{
			MVCLite_Loader::loadClass( self::_format($name) . '.php';
		}
	}
	
	/**
	 * Loads a controller using the loadFile-method.
	 * 
	 * The result is the name of the class.
	 * 
	 * @param string $name name of the controller (without suffix)
	 * @return string
	 * @throws MVCLite_Loader_Exception
	 */
	public static function loadController ($name)
	{
		$name .= self::getSuffix(self::CONTROLLER);
		self::loadFile(MVCLITE_CONTROLLER . $name . '.php');
		
		return $name;
	}
	
	/**
	 * Loads a file using include_once.
	 * 
	 * @param string $path file to load
	 * @throws MVCLite_Loader_Exception
	 */
	public static function loadFile ($path)
	{
		if(!file_exists($path))
		{
			throw new MVCLite_Loader_Exception(
				'File "' . $path . '" cannot be found'
			);
		}
		
		include_once $path;
	}
	
	/**
	 * Loads a model using the loadFile-method.
	 * 
	 * The result is the name of the class.
	 * 
	 * @param string $name name of the model without suffix
	 * @return string
	 * @throws MVCLite_Loader_Exception
	 */
	public static function loadModel ($name)
	{
		$name .= self::getSuffix(self::MODEL);
		self::loadFile(MVCLITE_MODEL . $name . '.php');
		
		return $name;
	}
	
	/**
	 * Registers the class as autoloader.
	 * 
	 * @return boolean
	 */
	public static function register ()
	{
		if(self::isRegistered())
		{
			return false;
		}
		
		self::$_autoload = spl_autoload_register(self::$_callback);
		
		return self::isRegisterd();
	}
	
	/**
	 * Unregisters this class as autoloader.
	 * 
	 * @return boolean
	 */
	public static function unregister ()
	{
		if(!self::isRegistered())
		{
			return false;
		}
		
		self::$_autoload = spl_autoload_unregister(self::$_callback);
		
		return !self::isRegistered();
	}
}
?>