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
	 * Initializes the database.
	 */
	public function initDatabase ()
	{
		switch ($this->getProfile())
		{
			default:
			case 'development':
				$adapter = new MVCLite_Db_PDO('mysql:host=localhost;dbname=mvclite', 'root');
				
				break;
			case 'test':
				
				break;
			case 'production':
				
				break;
		}
		
		if(isset($adapter))
		{
			MVCLite_Db::getInstance()->setAdapter($adapter);
		}
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
	
	/**
	 * Initializes some options of error-handling.
	 */
	public function initError ()
	{
		switch ($this->getProfile())
		{
			case 'development':
				error_reporting(E_ALL);
				ini_set('display_errors', 'On');
				MVCLite_Db::getInstance()->display(true);
				
				break;
			default:
				ini_set('display_errors', 'Off');
				MVCLite_Db::getInstance()->display(false);
				
				break;
		}
	}
}
?>