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
 * This exception is used for redirecting to another controller/action.
 * 
 * @category   MVCLite
 * @package    Request
 * @subpackage Dispatcher
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_Request_Dispatcher_Redirect extends MVCLite_Request_Exception
{
	/**
	 * External redirect.
	 * 
	 * If this code is set, the script uses the header() to redirect to
	 * another page.
	 */
	const EXTERNAL = 1;
	
	/**
	 * Forces dirty environment.
	 * 
	 * I.e. that the globals are not changed.
	 */
	const DIRTY = 2;
}
?>