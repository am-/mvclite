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
 * This interface defines method(s) required for protecting a controller.
 * 
 * @category   MVCLite
 * @package    Security
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
interface MVCLite_Security_Protector
{
	/**
	 * Controller which is protected.
	 * 
	 * If there was a security issue raised, false is returned.
	 * Otherwise the protector grants the user to use the
	 * specified action.
	 * 
	 * @param MVCLite_Controller_Abstract $controller protected controller
	 * @param string $method method that gets accessed
	 * @return boolean
	 */
	public function protect (MVCLite_Controller_Abstract $controller, $method);
}
?>