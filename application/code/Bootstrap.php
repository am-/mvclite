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
 * This class is the bootstrap which makes the application ready.
 * 
 * It should be used to attach view-helpers, select the correct route,
 * activate plugins and so on.
 * 
 * @category   MVCLite
 * @package    Core
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id:MVCLite.php 133 2007-08-26 08:19:13Z andre.moelle $
 */
class Bootstrap extends MVCLite_Bootstrap
{
	/**
	 * Initializes the base-url automatically.
	 * 
	 * WARNING: Do not edit this unless you REALLY know what you are doing.
	 */
	public function initBase ()
	{
		MVCLite::getInstance()->setBaseUrl(
			dirname($_SERVER['PHP_SELF']) == '/' ? '/'
				: substr(
					$_SERVER['PHP_SELF'],
					0,
					strrpos($_SERVER['PHP_SELF'], '/')
				) . '/'
		);
	}
	
	/**
	 * Sends the X-Powered-By header to the browser.
	 */
	public function initPoweredBy ()
	{
		if(PHP_SAPI != 'cli')
		{
			header('X-Powered-By: ' . MVCLite::NAME . ' ' . MVCLite::VERSION);
		}
	}
	
	/**
	 * Sets the default request.
	 * 
	 * WARNING: Do not edit this unless you REALLY know what you are doing.
	 */
	public function initRoute ()
	{
		MVCLite::getInstance()->setRoute(new MVCLite_Request_Route_Standard());
	}	
}
?>