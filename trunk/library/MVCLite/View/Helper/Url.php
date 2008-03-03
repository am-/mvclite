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
 * This helper is used for creating urls transparently.
 * 
 * These URLs consist of controller, action and arguments. This provides
 * an abstraction of the underlying route. You do not have to change
 * your URLs when you change your route. Additionally, the base-url
 * is prepended.
 * 
 * @category   MVCLite
 * @package    View
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_View_Helper_Url extends MVCLite_View_Helper_Abstract
{
	/**
	 * Converts the given information to an url using the active route.
	 * 
	 * @param string $url incomplete url
	 * @return string
	 */
	public function url ($controller = 'Index', $action = 'index', array $args = array())
	{
		// TODO: Provide other routes.
		$request = new MVCLite_Request(new MVCLite_Request_Route_Standard());
		$request->setController($controller)
				->setAction($action)
				->setParams($args, MVCLite_Request::MODUS_RECREATE);
		
		return MVCLITE_BASE_URL . $request;
	}
}
?>