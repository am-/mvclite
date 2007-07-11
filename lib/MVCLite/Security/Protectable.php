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
 * This interface has to be implemented in controllers to protect.
 * 
 * It defines the methods each protected controller needs. Firstly
 * it must be able to return a protector which protects it. Secondly
 * it should define what to do on security issues.
 * 
 * @category   MVCLite
 * @package    Security
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
interface MVCLite_Security_Protectable
{
	/**
	 * Returns the protector of a protectable controller.
	 * 
	 * @return MVCLite_Security_Protector_Abstract
	 */
	public function getProtector ();
	
	/**
	 * This method is called if an action was protected.
	 * 
	 * I.e. a user wanted to access an action which he is not
	 * allowed to access. It is the fallback-method for
	 * missing security-permissions.
	 */
	public function wasProtected ();
}
?>