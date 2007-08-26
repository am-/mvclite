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
	 * Static method for autoloading.
	 * 
	 * Uses include instead of require_once which fastens the loading.
	 * 
	 * @param string $class class to load
	 */
	private static function _load ($class)
	{
		include str_replace('_', '/', $class) . '.php';
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
	 * Loads a class at runtime.
	 * 
	 * This method only uses the include_path which may save
	 * performance. Due to the use of require_once, the application
	 * stops if the class cannot be found.
	 * 
	 * @param string $name name of the class
	 */
	public static function loadClass ($name)
	{
		require_once self::_format($name) . '.php';
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
	 * Registers the autoloader.
	 * 
	 * If there already exists a __autoload, it is appended to the
	 * spl_autoloader.
	 * 
	 * @see http://de.php.net/manual/en/function.spl-autoload-register.php
	 * @return boolean
	 */
	public static function register ()
	{
		if(function_exists('__autoload'))
		{
			spl_autoload_register('__autoload');
		}
		
		return spl_autoload_register(array(__CLASS__, '_load'));
	}
}
?>