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
 * This is the abstract bootstrap-class.
 * 
 * The use of the bootstrap class is mainly creating the front-controller,
 * also known as the class MVCLite. 
 * Additionally, there are methods which encapsulate functionality for
 * configuring the front-controller resp. the entire application. These
 * methods have the prefix "init".
 * Furthermore, it is important to enable the developer to differ
 * between some stages (or enviroments, such as productive or test
 * environments).
 * 
 * @category   MVCLite
 * @package    Core
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id:MVCLite.php 133 2007-08-26 08:19:13Z andre.moelle $
 */
abstract class MVCLite_Bootstrap
{
	/**
	 * Currently used profile.
	 */
	private $_profile;
	
	/**
	 * Instance of the FrontController.
	 * 
	 * @var MVCLite
	 */
	private $_frontController;
	
	/**
	 * Sets the profile.
	 * 
	 * @param string $profile profile currently used
	 */
	public function __construct ($profile)
	{
		$this->_profile = $profile;
	}
	
	/**
	 * Bootstraps the application.
	 * 
	 * Each method in the class that begins with "init" is executed.
	 * The bootstrap file resides in the code-directory and is always
	 * named "Bootstrap.php", the class-name is "Bootstrap" as you
	 * surely expected.
	 */
	final public function bootstrap ()
	{
		$this->_frontController = new MVCLite();
		
		foreach(get_class_methods($this) as $method)
		{
			if(substr($method, 0, 4) == 'init')
			{
				$this->$method();
			}
		}
		
		return $this->getFrontController();
	}
	
	/**
	 * Returns the profile.
	 * 
	 * @return string
	 */
	public function getProfile ()
	{
		return $this->_profile;
	}
	
	/**
	 * Returns the current front-controller.
	 * 
	 * @return MVCLite
	 */
	public function getFrontController ()
	{
		return $this->_frontController;
	}
	
	/**
	 * Sends the X-Powered-By header to the browser.
	 * 
	 * This can be overwritten, but if you like MVCLite you should not. :-)
	 */
	public function initPoweredBy ()
	{
		if(PHP_SAPI != 'cli')
		{
			header('X-Powered-By: ' . MVCLite::NAME . ' ' . MVCLite::VERSION);
		}
	}
}
?>