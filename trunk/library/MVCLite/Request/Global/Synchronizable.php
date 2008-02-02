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
 * Interface for classes which synchronize with their superglobal.
 * 
 * @category   MVCLite
 * @package    Request
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
interface MVCLite_Request_Global_Synchronizable
{
	/**
	 * Synchronizes its content with the superglobal-variable.
	 * 
	 * @return boolean
	 */
	public function synchronize ();
}
?>