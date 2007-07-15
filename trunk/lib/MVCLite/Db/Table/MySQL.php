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
 * This class implements essential database-operations for MySQL.
 * 
 * @category   MVCLite
 * @package    Db
 * @subpackage Table
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
abstract class MVCLite_Db_Table_MySQL extends MVCLite_Db_Table_Abstract
{
	/**
	 * Binds the parameters to the statement.
	 * 
	 * This method is useful because it is sometimes tricky to get
	 * along with parameter binding, since the binding works with
	 * references instead of values.
	 * 
	 * @param PDOStatement|stdObject $stmt statement which binds the parameters
	 * @param array $params parameter to bind
	 */
	protected function _bind ($stmt, array $params)
	{
		$params = array_values($params);
		
		foreach($params as $key => $value)
		{
			$stmt->bindParam($key + 1, $params[$key]);
		}
	}
	
	/**
	 * Checks whether an error occured.
	 * 
	 * If this requirement is met, an exception will be thrown.
	 * 
	 * @param PDOStatement|stdObject $statement pdo-statement to check
	 * @throws MVCLite_Db_Exception
	 */
	protected function _check ($statement)
	{
		$info = $statement->errorInfo();
		
		if($info != array('00000'))
		{
			throw new MVCLite_Db_Exception($info[1] . ': ' . $info[2]);
		}
	}
	
	/**
	 * Binds parameters, executes the statement and checks for errors.
	 * 
	 * @param PDOStatement|stdObject $statement statement to execute
	 * @param array $params params that get binded
	 * @throws MVCLite_Db_Exception
	 */
	protected function _execute ($statement, array $params)
	{
		$this->_bind($statement, $params);
		$statement->execute();
		$this->_check($statement);
	}
	
	/**
	 * Prepares the columns by adding ` around them.
	 * 
	 * @param array $array columns to prepare
	 * @return array
	 */
	protected function _prepareColumns (array $array)
	{
		foreach($array as $key => $value)
		{
			$array[$key] = '`' . $value . '`';
		}
		
		return $array;
	}
	
	/**
	 * Prepares the values of an array using quote().
	 * 
	 * @param array $array array to prepare for query
	 * @return array
	 */
	protected function _prepareValues (array $array)
	{
		$db = $this->getDb();
		
		foreach($array as $key => $value)
		{
			$array[$key] = $db->quote($value);
		}
		
		return $array;
	}
	
	/**
	 * @see MVCLite_Db_Table_Abstract::delete()
	 */
	public function delete ($id)
	{
		$id = (array)$id;
		
		$stmt = $this->db()
					 ->prepare(
			'DELETE FROM `' . $this->getName() . '` ' .
			'WHERE ' . $this->getPrimary() . ' IN(' . implode(',', array_fill(0, count($id), '?')) . ')'
		);
		
		$this->_execute($stmt, $id);
		
		return $stmt->rowCount();
	}
	
	/**
	 * @see MVCLite_Db_Table_Abstract::insert()
	 */
	public function insert (array $input)
	{
		$db = $this->db();
		$stmt = $db->prepare(
				'INSERT INTO `' . $this->getName() . '` ' .
				'(' . implode(',', $this->_prepareColumns(array_keys($input))) . ') ' .
				'VALUES (' . implode(',', array_fill(1, count($input), '?')) . ')'
		);
		
		$this->_execute($stmt, $input);
		
		return $db->lastInsertId();
	}
	
	/**
	 * @see MVCLite_Db_Table_Abstract::select()
	 */
	public function select ($id)
	{
		$id = (array)$id;
		
		$stmt = $this->db()
					 ->prepare(
			'SELECT * FROM `' . $this->getName() . '` ' .
			'WHERE ' . $this->getPrimary() . ' IN(' . implode(',', array_fill(0, count($id), '?')) . ') ' .
			'LIMIT ' . count($id)
		);
		
		$this->_execute($stmt, $id);
		
		$result = array();
		
		foreach($stmt->fetchAll() as $item)
		{
			$result[$item[$this->getPrimary()]] = $this->fetchRecord($item);
		}
		
		return $result;
	}
	
	/**
	 * @see MVCLite_Db_Table_Abstract::update()
	 */
	public function update (array $input, $id)
	{
		$db = $this->db();
		
		$id = (array)$id;
		$fields = array();
		
		foreach(array_keys($input) as $key)
		{
			$fields[] = '`' . $key . '` = ?';
		}
		
		$stmt = $db->prepare(
			'UPDATE `' . $this->getName() . '` ' .
			'SET ' . implode(',', $fields) . ' ' .
			'WHERE ' . $this->getPrimary() . ' IN(' . implode(',', array_fill(0, count($id), '?')) . ') ' .
			'LIMIT ' . count($id)
		);
		
		$params = array();
		
		foreach(array_values($input) as $value)
		{
			$params[] = $value;
		}
		foreach($id as $value)
		{
			$params[] = $value;
		}
		
		$this->_execute($stmt, $params);
		
		return $stmt->rowCount();
	}
}
?>