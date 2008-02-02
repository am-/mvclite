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
 * Abstract class for plugins.
 * 
 * You can do everything with the plugins, but you have to implement
 * the abstract methods. The plugins pre-process method is called
 * before dispatching the controller.
 * 
 * @category   MVCLite
 * @package    Request
 * @subpackage Dispatcher
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
abstract class MVCLite_Request_Dispatcher_Plugin_Abstract
{
	/**
	 * Request-object.
	 * 
	 * @var MVCLite_Controller_Abstract
	 */
	protected $_controller;
	
	/**
	 * Returns the active controller.
	 * 
	 * @return MVCLite_Controller_Abstract
	 */
	public function getController ()
	{
		return $this->_controller;
	}
	
	/**
	 * This method called before preprocessing.
	 * 
	 * It should be used to initialize the object correctly. Since
	 * the controller can be called twice, this method should be
	 * used to revert changes on the object if necessary.
	 */
	public function init ()
	{
		;
	}
	
	/**
	 * Processes some tasks after the controller was dispatched.
	 */
	abstract public function postProcess ();
	
	/**
	 * Processes some tasks before the controller is dispatched.
	 */
	abstract public function preProcess ();
	
	/**
	 * Sets a new controller.
	 * 
	 * @param MVCLite_Controller_Abstract $controller controller that is processed
	 * @return MVCLite_Request_Dispatcher_Plugin_Abstract
	 */
	public function setController (MVCLite_Controller_Abstract $controller)
	{
		$this->_controller = $controller;
		
		return $this;
	}
}
?>