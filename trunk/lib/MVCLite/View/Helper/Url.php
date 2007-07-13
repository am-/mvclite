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
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_View_Helper_Url extends MVCLite_View_Helper_Abstract
{
	/**
	 * Instance of the MVCLite-object.
	 * 
	 * @var MVCLite
	 */
	private $_front;
	
	/**
	 * @see MVCLite_View_Helper_Abstract::_init()
	 */
	protected function _init ()
	{
		$this->_front = MVCLite::getInstance();
	}
	
	/**
	 * Converts the given information to an url using the active route.
	 * 
	 * @param string $url incomplete url
	 * @return string
	 */
	public function url ($controller = 'Index', $action = 'index', array $args = array())
	{
		$request = new MVCLite_Request($this->_front->getRoute());
		$request->setController($controller)
				->setAction($action)
				->setParams($args, MVCLite_Request::MODUS_RECREATE);
		
		return $this->_front->getBaseUrl() . $request;
	}
}
?>