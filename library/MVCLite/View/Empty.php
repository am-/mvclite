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
 * This view-class represents views that should not be rendered.
 * 
 * @category   MVCLite
 * @package    View
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_View_Empty extends MVCLite_View
{	
	/**
	 * @see MVCLite_View::__construct()
	 */
	public function __construct ($template = '')
	{
		;
	}
	
	/**
	 * @see MVCLite_View::__get()
	 * @throws MVCLite_View_Exception
	 */
	public function __get ($name)
	{
		throw new MVCLite_View_Exception('Empty views cannot be modified');
	}
	
	/**
	 * @see MVCLite_View::__set()
	 * @throws MVCLite_View_Exception
	 */
	public function __set ($name, $value)
	{
		throw new MVCLite_View_Exception('Empty views cannot be modified');
	}
	
	/**
	 * @see MVCLite_View::__toString()
	 */
	public function __toString ()
	{
		return $this->render();
	}
	
	/**
	 * @see MVCLite_View::getPath()
	 * @throws MVCLite_View_Exception
	 */
	public function getPath ()
	{
		throw new MVCLite_View_Exception('Empty views cannot be modified');
	}
	
	/**
	 * @see MVCLite_View::getSuffix()
	 * @throws MVCLite_View_Exception
	 */
	public function getSuffix ()
	{
		throw new MVCLite_View_Exception('Empty views cannot be modified');
	}
	
	/**
	 * @see MVCLite_View::getTemplate()
	 * @throws MVCLite_View_Exception
	 */
	public function getTemplate ()
	{
		throw new MVCLite_View_Exception('Empty views cannot be modified');
	}
	
	/**
	 * @see MVCLite_View::setSuffix()
	 * @throws MVCLite_View_Exception
	 */
	public function setSuffix ($suffix)
	{
		throw new MVCLite_View_Exception('Empty views cannot be modified');
	}
	
	/**
	 * @see MVCLite_View::setTemplate()
	 * @throws MVCLite_View_Exception
	 */
	public function setTemplate ($template)
	{
		throw new MVCLite_View_Exception('Empty views cannot be modified');
	}
	
	/**
	 * Returns an empty string.
	 * 
	 * @see MVCLite_View::render()
	 */
	public function render ($file = '')
	{
		return '';
	}
}
?>