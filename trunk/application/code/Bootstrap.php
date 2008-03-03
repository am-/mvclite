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
 * It should be used to attach view-helpers, activate plugins and so on.
 * 
 * @category   MVCLite
 * @package    Core
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id:MVCLite.php 133 2007-08-26 08:19:13Z andre.moelle $
 */
class Bootstrap extends MVCLite_Bootstrap
{
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