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
 * The base-model defines some useful methods.
 * 
 * Firstly it is able to fetch the used database-object. Secondly
 * it handles table-classes. You should create these table classes
 * after construction. The use of table-classes allows you to
 * make use of record-objects, validation and some other nice
 * features.
 * 
 * @category   MVCLite
 * @package    Model
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id:Abstract.php 133 2007-08-26 08:19:13Z andre.moelle $
 */
abstract class MVCLite_Model_Abstract_Database extends MVClite_Model_Abstract
{
	/**
	 * Array containing the tables this model uses.
	 * 
	 * @var array
	 */
	protected $_tables = array();
	
	/**
	 * Attaches a table to the model.
	 * 
	 * @param string $alias alias of the table
	 * @param MVCLite_Db_Table_Abstract $table table-class
	 * @return MVCLite_Model_Abstract
	 */
	public function attachTable ($alias, MVCLite_Db_Table_Abstract $table)
	{
		$this->_tables[$alias] = $table;
		
		return $this;
	}
	
	/**
	 * Detaches a table from this model.
	 * 
	 * @param string $alias alias of the table
	 * @return MVCLite_Model_Abstract
	 */
	public function detachTable ($alias)
	{
		if($this->hasTable($alias))
		{
			unset($this->_tables[$alias]);
		}
		
		return $this;
	}
	
	/**
	 * Returns the active database-adapter.
	 * 
	 * @return MVCLite_Db_Adaptable
	 * @throws MVCLite_Db_Exception
	 */
	public function getDatabase ()
	{
		return MVCLite_Db::getInstance()->getAdapter();
	}
	
	/**
	 * Returns the table.
	 * 
	 * If the table does not exist, a MVCLite_Model_Exception will
	 * be thrown.
	 * 
	 * @param string $alias alias of the table to return
	 * @return MVCLite_Db_Table_Abstract
	 * @throws MVCLite_Model_Exception
	 */
	public function getTable ($alias)
	{
		if(!$this->hasTable($alias))
		{
			throw new MVCLite_Model_Exception(
				'Table with the alias "' . $alias . '" does not exist'
			);
		}
		
		return $this->_tables[$alias];
	}
	
	/**
	 * Checks whether a table with that alias exists.
	 * 
	 * @param string $alias alias of the table
	 * @return boolean
	 */
	public function hasTable ($alias)
	{
		return isset($this->_tables[$alias]);
	}
}
?>