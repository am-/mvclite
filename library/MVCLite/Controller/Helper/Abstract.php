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
 * This is the class which each controller must extend directly or indirectly.
 * 
 * These helpers should be as stateless as possible, therefore the
 * constructor does not accept any arguments. But to enable you to
 * initialize the object, you can overwrite the method "_init", which
 * is called after the object is created.
 * Additionally, helpers can fetch and set controllers, which may be
 * useful.
 * It is up to you, how you name your methods in the helper, because
 * only the is returned in the controllers instead of calling a
 * specified method of the helper.
 * 
 * @category   MVCLite
 * @package    Controller
 * @subpackage Helper
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_Controller_Helper_Abstract
{
	/**
	 * Controller which is dispatched at the moment.
	 * 
	 * @var MVCLite_Controller_Abstract
	 */
	private $_controller;
	
	/**
	 * Constructor calls the init-method.
	 * 
	 * It is final since the constructor should not be overwritten.
	 * These helpers should be as stateless as possible, therefore
	 * the constructor forces that the helper does not accept any
	 * arguments.
	 */
	final public function __construct ()
	{
		$this->_init();
	}
	
	/**
	 * Init-method which is called after construction.
	 */
	protected function _init ()
	{
		;
	}
	
	/**
	 * Returns the set controller.
	 * 
	 * @return MVCLite_Controller_Abstract
	 */
	public function getController ()
	{
		return $this->_controller;
	}
	
	/**
	 * Sets a new controller.
	 * 
	 * @param MVCLite_Controller_Abstract $controller new controller
	 * @return MVCLite_Controller_Helper_Abstract
	 */
	public function setController (MVCLite_Controller_Abstract $controller)
	{
		$this->_controller = $controller;
		
		return $this;
	}
}
?>