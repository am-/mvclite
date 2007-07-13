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
 * This is the basic view-object which is used for most views.
 * 
 * It renders a specified template. This can be achieved by setting
 * it while construction, using the setTemplate-method or use a
 * template-file as parameter for the render-method.
 * You can also assign and get variables, which is very important for
 * the views.
 * To improve flexibility you can also define your own suffix for template
 * files.
 * View-helpers can be called by using $this->nameofhelper in the template.
 * The name of the helper shoud only consist of non-capitalized letters.
 * Normal assignment of variables and fetching of these variables is done
 * by calling the property directly. This is provided because this class
 * uses the magic methods __get and __set.
 * 
 * @category   MVCLite
 * @package    View
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_View
{
	/**
	 * Default suffix for view-templates.
	 * 
	 * @var string
	 */
	const SUFFIX = '.phtml';
	
	/**
	 * Active suffix for view-templates.
	 * 
	 * @var string
	 */
	protected $_suffix = self::SUFFIX;
	
	/**
	 * Active template-file.
	 * 
	 * This file is rendered when render() is called.
	 * 
	 * @var string
	 */
	protected $_template = '';
	
	/**
	 * Variables this view contains.
	 * 
	 * These variables can be accessed via __get/__set.
	 * 
	 * @var array
	 */
	protected $_variables = array();
	
	/**
	 * Constructor sets a default template.
	 * 
	 * @param string $template template-file
	 */
	public function __construct ($template = '')
	{
		$this->setTemplate($template);
	}
	
	/**
	 * This magic method delegates some calls to the helpers.
	 * 
	 * @param string $method name of the method to call
	 * @param array $arguments arguments used for the call
	 * @return mixed
	 */
	public function __call ($method, $arguments)
	{
		return MVCLite_View_Helper_Registry::getInstance()->call($method, $arguments);
	}
	
	/**
	 * Magic method for getting variables of the view.
	 * 
	 * These names must not start with underscores. In this case
	 * an exception would be thrown.
	 * 
	 * @param string $name name of the variable to return
	 * @return mixed
	 * @throws MVCLite_View_Exception
	 */
	public function __get ($name)
	{
		$this->_checkVarName($name);
		
		if(!isset($this->_variables[$name]))
		{
			return null;
		}
		
		return $this->_variables[$name];
	}
	
	/**
	 * Magic method for setting variables to the view.
	 * 
	 * The names are not allowed to start with underscores. In this
	 * case an exception would be thrown.
	 * 
	 * @param string $name name of the variable
	 * @param mixed $value value of the variable
	 * @throws MVCLite_View_Exception
	 */
	public function __set ($name, $value)
	{
		$this->_checkVarName($name);
		$this->_variables[$name] = $value;
	}
	
	/**
	 * Renders the view.
	 * 
	 * @return string
	 */
	public function __toString ()
	{
		try
		{
			return $this->render();
		}
		catch (MVCLite_View_Exception $e)
		{
			trigger_error($e->getMessage(), E_USER_NOTICE);
		}
		
		return '';
	}
	
	/**
	 * Checks whether a variable-name is valid.
	 * 
	 * @param string $name name of the variable to prove
	 * @return boolean
	 * @throws MVCLite_View_Exception
	 */
	private function _checkVarName ($name)
	{
		if($name[0] == '_')
		{
			throw new MVCLite_View_Exception(
				'The variable "' . $name . '" is invalid since it starts with an underscore'
			);
		}
		
		return true;
	}
	
	/**
	 * Returns the path to the template specified by this template.
	 * 
	 * If the path does not exist a MVCLite_View_Exception will be thrown.
	 * 
	 * @return string
	 * @throws MVCLite_View_Exception
	 */
	public function getPath ()
	{
		$path = MVCLITE_VIEW . $this->getTemplate() . $this->getSuffix();
		
		if(file_exists($path) && is_file($path))
		{
			return $path;
		}
		
		throw new MVCLite_View_Exception('Path "' . $path . '" does not exist');
	}
	
	/**
	 * Returns the active suffix.
	 * 
	 * @return string
	 */
	public function getSuffix ()
	{
		return $this->_suffix;
	}
	
	/**
	 * Returns the name (probably the path) without suffix of the template.
	 * 
	 * @return string
	 */
	public function getTemplate ()
	{
		return $this->_template;
	}
	
	/**
	 * Sets a new suffix.
	 * 
	 * The new suffix should start with a dot. If it does not it
	 * is prepended automatically.
	 * 
	 * @param string $suffix new suffix
	 * @return MVCLite_View
	 */
	public function setSuffix ($suffix)
	{
		if($suffix[0] != '.')
		{
			$suffix = '.' . $suffix;
		}
		
		$this->_suffix = $suffix;
		
		return $this;
	}
	
	/**
	 * Sets a new template-file.
	 * 
	 * @param string $template template-name (probably the path without suffix)
	 * @return MVCLite_View
	 */
	public function setTemplate ($template)
	{
		if($template == '')
		{
			$this->_template = '';
			return $this;
		}
		
		$info = pathinfo($template);
		
		$this->_template = '';
		
		if($info['dirname'] != '.')
		{
			$this->_template = $info['dirname'] . '/';
		}
		
		if(isset($info['extension']) ? $info['extension'] != $this->getSuffix() : false)
		{
			$this->_template .= $info['filename'];
		}
		else
		{
			$this->_template .= $info['basename'];
		}
		
		return $this;
	}
	
	/**
	 * This method renders a template.
	 * 
	 * Which template is rendered is specified by the parameter. If no
	 * argument or an empty string was given it renders the template
	 * set by the setTemplate-method. Otherwise the given template
	 * is rendered. In every case, the previous declared template
	 * is not changed.
	 * 
	 * @param string $file optional template-file
	 * @return string
	 * @throws MVCLite_View_Exception
	 */
	public function render ($file = '')
	{
		if($file != '')
		{
			$old = $this->getTemplate();
			$this->setTemplate($file);
		}
		
		$path = $this->getPath();
		
		ob_start();
		include $path;
		$result = ob_get_contents();
		ob_end_clean();
		
		if(isset($old))
		{
			$this->setTemplate($old);
		}
		
		return $result;
	}
}
?>