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
 * This helper enables the developer to redirect to another page.
 * 
 * For more information, see the method "redirect".
 * 
 * @category   MVCLite
 * @package    Controller
 * @subpackage Helper
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_Controller_Helper_Redirector extends MVCLite_Controller_Helper_Abstract
{
	/**
	 * This method allows to redirect.
	 * 
	 * You have to assign controller, action and arguments and
	 * probably the modus of redirection. Otherwise the default
	 * values are used instead. The redirection is done by using
	 * an exeception. This causes that the code after the
	 * redirection is not executed.
	 * 
	 * There are some different modi for redirection. See more
	 * in the exception MVCLite_Request_Dispatcher_Redirect. To
	 * sum it up, a short description:
	 * 
	 * <code>
	 * default (0)
	 * -> clean internal redirection
	 * MVCLite_Request_Dispatcher_Redirect::EXTERNAL (1)
	 * -> redirects to another URL using the Location-header
	 * MVCLite_Request_Dispatcher_Redirect::DIRTY (2)
	 * -> does not delete set data in the globals
	 * </code>
	 * 
	 * @param string $controller destination controller
	 * @param string $action destination action
	 * @param array $arguments destination arguments
	 * @param integer $modus type of redirection
	 * @throws MVCLite_Request_Dispatcher_Redirect
	 */
	public function redirect ($controller = 'Index',
							  $action = 'index',
							  array $arguments = array(),
							  $modus = 0)
	{
		throw new MVCLite_Request_Dispatcher_Redirect(
			(string)$this->getController()
						 ->getRequest()
						 ->setController($controller)
						 ->setAction($action)
						 ->setParams($arguments),
			$modus
		);
	}
}
?>