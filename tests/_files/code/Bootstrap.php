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
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id:MVCLite.php 133 2007-08-26 08:19:13Z andre.moelle $
 */
class Bootstrap extends MVCLite_Bootstrap
{
	/**
	 * Variable for testing.
	 */
	public $bar = 'bar';
	/**
	 * Variable for testing.
	 */
	public $foo = 'foo';
	
	public function init ()
	{
		$this->bar = 'foo';
	}
	
	public function initBase ()
	{
		MVCLite::getInstance()->setBaseUrl('/');
	}
	
	public function initFoo ()
	{
		$this->foo = 'bar';
	}
	
	public function initRoute ()
	{
		MVCLite::getInstance()->setRoute(new MVCLite_Request_Route_Standard());
	}	
}
?>