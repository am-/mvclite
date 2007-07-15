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
 * The record class works as transfer object.
 * 
 * It contains the content of a row in the table. To know which table
 * the row belongs to, the constructor requires the correct table-object.
 * You can easily access the content of the rows and also change them.
 * Only the primary key can be changed only via "changedPrimary" or
 * "set" with the correct parameters (no MODUS_PROTECTED).
 * 
 * @category   MVCLite
 * @package    Db
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id: Adaptable.php 81 2007-07-11 10:40:58Z andre.moelle $
 */
class MVCLite_Db_Record
{
	/**
	 * If this bit is set, the primary key cannot get changed.
	 * 
	 * @const integer
	 */
	const MODUS_PROTECTED = 1;
	
	/**
	 * If this bit is set, an exception is thrown on error.
	 * 
	 * Otherwise any possibly error-message is omitted.
	 * 
	 * @const integer
	 */
	const MODUS_EXCEPTION = 2;
	
	/**
	 * If this bit is set, old content is deleted before setting the new one.
	 * 
	 * @const integer
	 */
	const MODUS_RECREATE = 4;
	
	/**
	 * Content of this record.
	 * 
	 * @var array
	 */
	private $_content = array();
	
	/**
	 * Instance of the table.
	 * 
	 * @var MVCLite_Db_Table_Abstract
	 */
	private $_table;
	
	/**
	 * Constructor sets the table-object and sets the specified input.
	 * 
	 * @param MVCLite_Db_Table_Abstract $table table-class
	 * @param array $input input the record has
	 */
	public function __construct (MVCLite_Db_Table_Abstract $table, array $input)
	{
		$this->_table = $table;
		$this->set($input, 0);
	}
	
	/**
	 * Returns the content of a specified variable.
	 * 
	 * If the variable does not exist in the table, an exception
	 * will be thrown. If the variable exists in the table but not
	 * in the record, null is returned. Otherwise the correct value
	 * is returned.
	 * 
	 * @param string $name name of the variable to return
	 * @return mixed
	 * @throws MVCLite_Db_Exception
	 */
	public function __get ($name)
	{
		if(!$this->hasColumn($name))
		{
			throw new MVCLite_Db_Exception(
				'Column "' . $name . '" does not exist in table "' . $this->getTable()->getName() . '".'
			);
		}
		
		if(!isset($this->_content[$name]))
		{
			return null;
		}
		
		return $this->_content[$name];
	}
	
	/**
	 * Sets a new value to the specified variable.
	 * 
	 * If the variable does not exist in the table an exception
	 * will be thrown. This is also the case if you want to change
	 * the primary key, which is only possible via "changePrimary"
	 * or set() with the correct modus.
	 * 
	 * @param string $name name of the variable
	 * @param string $value new value of the variable
	 * @throws MVCLite_Db_Exception
	 */
	public function __set ($name, $value)
	{
		if(!$this->hasColumn($name))
		{
			throw new MVCLite_Db_Exception(
				'Column "' . $name . '" does not exist in table "' . $this->getTable()->getName() . '".'
			);
		}
		if($this->isPrimary($name))
		{
			throw new MVCLite_Db_Exception(
				'The primary-key has to be changed explicitly via changePrimary.'
			);
		}
		
		$this->_content[$name] = $value;
	}
	
	/**
	 * Changes the primary key since this is not allowed via __set.
	 * 
	 * It is not allowed to change the primary key because this could
	 * result in security errors. Therefore this method changes the
	 * primary key explicitly.
	 * 
	 * @param mixed $value new value of the primary key
	 * @return MVCLite_Db_Record
	 */
	public function changePrimary ($value)
	{
		$this->_content[$this->getTable()->getPrimary()] = $value;
		
		return $this;
	}
	
	/**
	 * Returns the content of this record.
	 * 
	 * @return array
	 */
	public function get ()
	{
		return $this->_content;
	}
	
	/**
	 * Returns the value of the primary key.
	 * 
	 * If the primary key was not set, null is returned instead.
	 * 
	 * @return mixed
	 */
	public function getPrimary ()
	{
		return $this->{$this->getTable()->getPrimary()};
	}
	
	/**
	 * Returns the table-object for this record.
	 * 
	 * @return MVCLite_Db_Table_Abstract
	 */
	public function getTable ()
	{
		return $this->_table;
	}
	
	/**
	 * Checks whether the table associated with this record has the given column.
	 * 
	 * @param string $name name of the column
	 * @return boolean
	 */
	public function hasColumn ($name)
	{
		return in_array($name, $this->getTable()->getColumns());
	}
	
	/**
	 * Checks whether the given column-name is the primary-key.
	 * 
	 * True is returned when the given column-name is the primary key.
	 * 
	 * @param string $name name of the column
	 * @return boolean
	 */
	public function isPrimary ($name)
	{
		return $name == $this->getTable()->getPrimary();
	}
	
	/**
	 * Sets new content to this record.
	 * 
	 * There are some modi you can chose. You can combine all constants
	 * in the modus (self::MODUS_*). By default the constants 
	 * MODUS_PROTECTED and MODUS_EXCEPTION are set. In protected mode, it
	 * is not possible to change the primary key, which is enabled by
	 * default.
	 * 
	 * Protected mode can be applied with any other modus. When the
	 * content should be recreated, the primary value is retained.
	 * Recreation means that the old content is deleted before
	 * inserting the new one, therefore the primary-key gets deleted too.
	 * When no exception will be thrown, the primary-key is not changed
	 * in protected mode.
	 * 
	 * @param array $input new input
	 * @param integer $modus modus for setting
	 * @return MVCLite_Db_Record
	 * @throws MVCLite_Db_Exception
	 */
	public function set (array $input, $modus = null)
	{
		if($modus === null)
		{
			$modus = 0;
			$modus |= self::MODUS_PROTECTED;
			$modus |= self::MODUS_EXCEPTION;
		}
		
		if(($modus & self::MODUS_PROTECTED) == self::MODUS_PROTECTED)
		{
			$primary = $this->getTable()->getPrimary();
			
			if(isset($input[$primary]))
			{
				if(($modus & self::MODUS_EXCEPTION) == self::MODUS_EXCEPTION)
				{
					throw new MVCLite_Db_Exception(
						'Primary key is protected by set() with that modus'
					);
				}
				
				unset($input[$this->getTable()->getPrimary()]);
			}
		}
		
		if(($modus & self::MODUS_RECREATE) == self::MODUS_RECREATE)
		{
			$this->_content = 
				(($modus & self::MODUS_PROTECTED) == self::MODUS_PROTECTED) ?
				  array($primary => $this->getPrimary()) 
				: array();
		}
		
		$this->_content = array_merge(
			$this->_content,
			$this->getTable()->assemble($input)
		);
		
		return $this;
	}
}
?>