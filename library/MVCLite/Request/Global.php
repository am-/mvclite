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
 * This class is used as base-class for containers storing superglobals.
 * 
 * These container-classes provide the possibility for a better testing.
 * It can be applied especially for testing the controllers.
 * 
 * @category   MVCLite
 * @package    Request
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
abstract class MVCLite_Request_Global
{
	/**
	 * Children which this class contains.
	 * 
	 * @var array
	 */
	protected $_children = null;
	
	/**
	 * Since this class is a singleton, the constructor is hidden.
	 * 
	 * @param array|null $global content of the superglobal
	 */
	protected function __construct ($global = null)
	{
		$this->set($global);
	}
	
	/**
	 * Since this class should not be cloned, the __clone-method is hidden.
	 */
	protected function __clone ()
	{
		;
	}
	
	/**
	 * Returns the child with the given name.
	 * 
	 * @param string $name name of the child to return
	 * @return mixed
	 */
	public function __get ($name)
	{
		$children = $this->getChildren();
		
		if(!isset($children[$name]))
		{
			return null;
		}
		
		return $children[$name];
	}
	
	/**
	 * Checks whether a property of this class is set.
	 * 
	 * @param string $name variable to check
	 * @return boolean
	 */
	public function __isset ($name)
	{
		$children = $this->getChildren();
		
		return isset($children[$name]);
	}
	
	/**
	 * Sets a new variable and possibly overwrites an old one.
	 * 
	 * @param string $name name of the variable
	 * @param mixed $value value of the new variable
	 */
	public function __set ($name, $value)
	{
		$children = $this->getChildren();
		$children[$name] = $value;
		
		$this->set($children);
	}
	
	/**
	 * Unsets a value.
	 * 
	 * @param string $name name of the variable to unset
	 */
	public function __unset ($name)
	{
		if(isset($this->$name))
		{
			unset($this->_children[$name]);
		}
	}
	
	/**
	 * Returns the superglobal variable for this class.
	 * 
	 * @return array
	 */
	abstract protected function _getGlobal ();
	
	/**
	 * Returns the children of this class.
	 * 
	 * If the children do not exist the default globals are
	 * taken instead.
	 * 
	 * @return array
	 */
	public function getChildren ()
	{
		if($this->_children == null)
		{
			$this->_children = $this->_getGlobal();
		}
		
		return $this->_children;
	}
	
	/**
	 * Name of the superglobal.
	 * 
	 * @return string
	 */
	abstract public function getName ();
	
	/**
	 * Global classes should implement the singleton pattern.
	 * 
	 * This method is used to return an instance of a global
	 * class.
	 * 
	 * @param array|null $global content of the superglobal
	 * @return MVCLite_Request_Global
	 */
	abstract public static function getInstance ($global = null);
	
	/**
	 * Sets new content.
	 * 
	 * @param array|null $array array representing the new content
	 * @return MVCLite_Request_Global
	 */
	public function set ($array)
	{
		$this->_children = $array;
		
		return $this;
	}
}
?>