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
 * This class represents the container for server-variables.
 * 
 * @category   MVCLite
 * @package    Request
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_Request_Global_Server extends MVCLite_Request_Global
{
	/**
	 * Global instance.
	 * 
	 * @var MVCLite_Request_Global
	 */
	private static $_instance;
	
	/**
	 * @see MVCLite_Request_Global::_getGlobal()
	 */
	protected function _getGlobal ()
	{
		return $_SERVER;
	}
	
	/**
	 * @see MVCLite_Request_Global::getInstance()
	 */
	public static function getInstance ($content = null)
	{
		if(self::$_instance == null)
		{
			self::$_instance = new self($content);
		}
		
		return self::$_instance;
	}
	
	/**
	 * @see MVCLite_Request_Global::getName()
	 */
	public function getName ()
	{
		return 'server';
	}
}
?>