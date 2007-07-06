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
 * 
 * @category   MVCLite
 * @package    Core
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id:$
 */
class MVCLite
{
	/**
	 * Instance of this class.
	 * 
	 * @var MVCLite
	 */
	private static $_instance;
	
	/**
	 * 
	 */
	public function dispatch ()
	{
		
	}
	
	/**
	 * Creates and returns an instance of this class.
	 * 
	 * @param void
	 * @return MVCLite
	 */
	public function getInstance ()
	{
		if(self::$_instance == null)
		{
			self::$_instance = new self();
		}
		
		return self::$_instance;
	}
}
?>