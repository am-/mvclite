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
 * This is the base-class for each helper.
 * 
 * Every helper must extend this class directly or even indirectly.
 * The helpers should be stateless, which reduces the error
 * probability and improves the reusability. Therefore view-helpers
 * cannot accept arguments at their creation time.
 * 
 * To create a view-helper you only have to create a class which is in
 * the path (see MVCLite_View_Helper_Registry). Only files in that
 * directory are accepted. The name of the helper has to start with a
 * capitalized letter, while the rest of the name must not be capitalized.
 * Digits are permitted.
 * The method which represents the helper, i.e. the method which is called
 * by the call-method of the registry, matches the helpername with the
 * addition, that every letter is non capitalized.
 * 
 * <code>
 * MVCLite_View_Helper_Registry::getInstance()->addPath('Foobar_', '/var/tmp/');
 * 
 * // content of /var/tmp/Bar.php
 * class Foobar_Bar extends MVCLite_View_Helper_Abstract
 * {
 *	protected function _init () { echo 'helper initialized'; }
 *	
 *	public function bar ()
 *  {
 *		if(func_num_args())
 *		{
 *			return implode('+', func_get_args());
 *		}
 *		
 *		return 'nothing!';
 *	}
 * }
 * 
 * // helper call in a template
 * $this->bar('1', '2', '3'); // retuns "1+2+3"
 * $this->bar(1); // returns 1
 * $this->bar(); // returns "nothing!"
 * </code>
 * 
 * @category   MVCLite
 * @package    View
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
abstract class MVCLite_View_Helper_Abstract
{
	/**
	 * Helpers should never use the constructor.
	 * 
	 * Since helpers should be stateless, they do not need a
	 * constructor which probably accepts parameters. To
	 * accomplish that the constructor is final. But the
	 * helper can make itself ready, using the method "_init",
	 * which is called in the constructor.
	 */
	final public function __construct ()
	{
		$this->_init();
	}
	
	/**
	 * Method which is called after creation.
	 * 
	 * It makes the object ready and is called after constructing
	 * the helper.
	 */
	protected function _init ()
	{
		;
	}
}
?>