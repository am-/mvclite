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
 * This class stores the used adapter.
 * 
 * It also functions as global point for accessing the used adapter.
 * 
 * @category   MVCLite
 * @package    Db
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_Db
{
	/**
	 * Instance of the used adapter.
	 * 
	 * @var MVCLite_Db_Adaptable
	 */
	private $_adapter;
	
	/**
	 * Determines whether database-errors should be displayed.
	 * 
	 * @var boolean
	 */
	private $_display = false;
	
	/**
	 * Returns the instance of this class.
	 * 
	 * @var MVCLite_Db
	 */
	private static $_instance;
	
	/**
	 * This class is a singleton, therefore the constructor is hidden.
	 */
	private function __construct ()
	{
		;
	}
	
	/**
	 * Determines whether errors should be displayed.
	 * 
	 * @param boolean $display true when errors should be displayed
	 * @return MVCLite_Db
	 */
	public function display ($display = false)
	{
		$this->_display = (bool)$display;
		
		return $this;
	}
	
	/**
	 * Returns the used adapter.
	 * 
	 * If no adapter was set a MVCLite_Db_Exception will be thrown.
	 * 
	 * @return MVCLite_Db_Adaptable
	 * @throws MVCLite_Db_Exception
	 */
	public function getAdapter ()
	{
		if($this->_adapter == null)
		{
			throw new MVCLite_Db_Exception('No adapter set.');
		}
		
		return $this->_adapter;
	}
	
	/**
	 * Returns the instance of the database.
	 * 
	 * @return MVCLite_Db
	 */
	public static function getInstance ()
	{
		if(self::$_instance == null)
		{
			self::$_instance = new self();
		}
		
		return self::$_instance;
	}
	
	/**
	 * Returns true when errors should be displayed.
	 * 
	 * @return boolean
	 */
	public function isDisplayed ()
	{
		return $this->_display;
	}
	
	/**
	 * Returns the set adapter.
	 * 
	 * @param MVCLite_Db_Adaptable $adapter sets a new adapter
	 * @return MVCLite_Db
	 */
	public function setAdapter (MVCLite_Db_Adaptable $adapter = null)
	{
		$this->_adapter = $adapter;
		
		return $this;
	}
}
?>