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
 * This interface defines classes which are able to create routes.
 * 
 * These classes must be able to resolve paths to their information,
 * so that these be splitted up into controller, action and the
 * arguments. Additionally, it has to provide the possibility to
 * revert this parsing. Therefore you are able to create URLs fitting
 * to the route dynamically.
 * 
 * @category   MVCLite
 * @package    Request
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id:$
 */
interface MVCLite_Request_Route
{
	/**
	 * Assembles the request using a request-object and returns an URI.
	 * 
	 * @param MVCLite_Request $request request to assemble
	 * @return string
	 */
	public function assemble (MVCLite_Request $request);
	
	/**
	 * Parses a request-URI and returns a MVCLite_Request-object.
	 * 
	 * @param string $uri uri to parse
	 * @return MVCLite_Request
	 */
	public function parse ($uri);
}
?>