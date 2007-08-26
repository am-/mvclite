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
 * This class represents the container for cookie-variables.
 * 
 * @category   MVCLite
 * @package    Request
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_Request_Global_Cookie extends MVCLite_Request_Global implements MVCLite_Request_Global_Synchronizable
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
		return $_COOKIE;
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
		return 'cookie';
	}
	
	/**
	 * @see MVCLite_Request_Global_Synchronizable::synchronize()
	 */
	public function synchronize ()
	{
		$children = $this->getChildren();
		
		if($children == array() || $this->_getGlobal() == $children)
		{
			return false;
		}
		
		$_COOKIE = $children;
		
		return true;
	}
}
?>