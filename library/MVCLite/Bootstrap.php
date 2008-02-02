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
 * This class is the abstract bootstrap.
 * 
 * It contains method for setting up the bootstrap easily.
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
	public function bootstrap ()
	{
		foreach(get_class_methods($this) as $method)
		{
			if(substr($method, 0, 4) != 'init')
			{
				continue;
			}
			
			$this->$method();
		}
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
}
?>