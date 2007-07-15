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
 * Abstract table providing some essential methods.
 * 
 * It provides some methods for easy inserting, retrieving, updating
 * and deleting rows in the table. Due to the aimed simplicity they
 * only provide basic functionalities. In fact it supports only
 * primary keys which can be passed to these methods.
 * Furthermore, these table-classes contain information about the
 * table itself, such as columns, the primary-key - only a single primary-key
 * is allowed to reduce complexity - and of course the name of the table.
 * It is also able to transform an array to a MVCLite_Db_Record-object, which
 * can be used for easy data-manipulation. To ensure nothing wrong gets
 * in the database, an abstract validate-method is provided. You are advised
 * to use this method.
 * 
 * @category   MVCLite
 * @package    Db
 * @subpackage Table
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
abstract class MVCLite_Db_Table_Abstract
{
	/**
	 * Assembles the content to this table.
	 * 
	 * This means that the elements, which are not in the table,
	 * are deleted and the clean array is then returned.
	 * 
	 * @param array $input array to assemble
	 * @return array
	 */
	public function assemble (array $input)
	{
		$result = array();
		
		foreach($this->getColumns() as $column)
		{
			if(isset($input[$column]))
			{
				$result[$column] = $input[$column];
			}
		}
		
		return $result;
	}
	
	/**
	 * Returns the active instance of the database.
	 * 
	 * If no adapter was set a MVCLite_Db_Exception will be
	 * thrown.
	 * 
	 * @return MVCLite_Db_Adaptable
	 * @throws MVCLite_Db_Exception
	 */
	public function db ()
	{
		return MVCLite_Db::getInstance()->getAdapter();
	}
	
	/**
	 * Deletes some entries and returns the number of deleted items.
	 * 
	 * @param array|integer $id id of the entries to delete
	 * @return integer
	 */
	abstract public function delete ($id);
	
	/**
	 * Returns an empty record.
	 * 
	 * @param array $input input of the record
	 * @return MVCLite_Db_Record
	 */
	public function fetchRecord (array $input = array())
	{
		return new MVCLite_Db_Record($this, $this->assemble($input));
	}
	
	/**
	 * Returns the columns of this table.
	 * 
	 * The result is an array whose elements represent the name
	 * of the columns, the table owns.
	 * 
	 * @return array
	 */
	abstract public function getColumns ();
	
	/**
	 * Returns the name of the table.
	 * 
	 * @return string
	 */
	abstract public function getName ();
	
	/**
	 * Returns the name of the primary-key.
	 * 
	 * @return string
	 */
	abstract public function getPrimary ();
	
	/**
	 * Inserts a new entry to the table.
	 * 
	 * Additionally, it returns the incremented id, whenever possible.
	 * 
	 * @param array $input new inserted data
	 * @return integer
	 */
	abstract public function insert (array $input);
	
	/**
	 * Returns one or more items from the table.
	 * 
	 * The result is always an array, due to the fact that this class
	 * can return more than one item. Each element of the array is
	 * MVCLite_Db_Record-object.
	 * 
	 * @param array|integer $id value of the primary keys
	 * @return array
	 */
	abstract public function select ($id);
	
	/**
	 * Updates an item and returns whether the process was successful.
	 * 
	 * @param array $input new input
	 * @param integer $id id of the item to update
	 * @return boolean
	 */
	abstract public function update (array $input, $id);
	
	/**
	 * Validates the given array.
	 * 
	 * If any error occurs they are returned as elements in the
	 * array. Otherwise an empty array is returned.
	 * 
	 * @param array $input input to validate
	 * @return array
	 */
	abstract public function validate (array $input);
}
?>