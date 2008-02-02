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
 * This class is used to create a layout for the webpage.
 * 
 * Layouts are equal across most requests, therefore a default
 * implementation is required. When instantiating you should pass
 * a template-file which represents the layout. Otherwise the default
 * template is taken instead.
 * 
 * Please note that setting or getting variables from this class, it will
 * only affect the layout-class and not the wrapped view.
 * If you want to alter the view itself you should use getView() and
 * perform the actions you want. To set a new view you should use
 * the setTemplate-method.
 * 
 * @category   MVCLite
 * @package    View
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_View_Layout extends MVCLite_View
{
	/**
	 * Default layout-file without suffix.
	 * 
	 * @var string
	 */
	const LAYOUT = 'layout';
	
	/**
	 * Wrapped view.
	 * 
	 * @var MVCLite_View
	 */
	protected $_view;
	
	/**
	 * Constructor sets the layout.
	 * 
	 * @param string $layout template-file containing the layout
	 */
	public function __construct ($layout = self::LAYOUT)
	{
		parent::__construct($layout);
	}
	
	/**
	 * Returns the instance of the wrapped view.
	 * 
	 * If no instance exists an exception will be thrown.
	 * 
	 * @return MVCLite_View
	 * @throws MVCLite_View_Exception
	 */
	public function getView ()
	{
		if($this->_view == null)
		{
			throw new MVCLite_View_Exception(
				'View for this layout does not exist'
			);
		}
		
		return $this->_view;
	}
	
	/**
	 * This method returns the template of the wrapped view.
	 * 
	 * If there is no wrapped view an empty string is returned.
	 * 
	 * @return string
	 */
	public function getViewTemplate ()
	{
		try
		{
			return $this->getView()->getTemplate();
		}
		catch (MVCLite_View_Exception $e)
		{
			return '';
		}
	}
	
	/**
	 * Sets a view-template.
	 * 
	 * This can be either a MVCLite_View-object or a string representing
	 * a template-file. But this template is always stored as
	 * MVCLite_View-object.
	 * 
	 * @param MVCLite_View|string $file
	 * @return MVCLite_View_Layout
	 */
	public function setView ($view)
	{
		if(!is_object($view) || !($view instanceof MVCLite_View))
		{
			$view = new MVCLite_View($view);
		}
		
		$this->_view = $view;
		
		return $this;
	}
	
	/**
	 * This method renders the layout.
	 * 
	 * The optional parameter never affecst the view in any way.
	 * 
	 * @param string $file optional template-file to parse
	 * @return string
	 * @throws MVCLite_View_Exception
	 */
	public function render ($file = '')
	{
		$this->view = $this->getView();
		
		return parent::render();
	}
}
?>