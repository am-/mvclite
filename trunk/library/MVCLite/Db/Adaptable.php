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
 * This interface presents a very basic interface to the database.
 * 
 * As you probably might see this interface is mostly inspired by
 * the PDOs. It enables you to integrate other databases or
 * database-adapters of other frameworks.
 * 
 * @category   MVCLite
 * @package    Db
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
interface MVCLite_Db_Adaptable
{
	/**
	 * Starts a transaction and returns whether it was successful.
	 * 
	 * @return boolean
	 */
	public function beginTransaction ();
	
	/**
	 * Commits the database-operations.
	 * 
	 * It returns whether it was successful.
	 * 
	 * @return boolean
	 */
	public function commit ();
	
	/**
	 * Returns the error-code of the last failed operation.
	 * 
	 * @return string
	 */
	public function errorCode ();
	
	/**
	 * Returns the error-info of the last failed operation.
	 * 
	 * @return array
	 */
	public function errorInfo ();
	
	/**
	 * Executes a statement and returns the affected rows.
	 * 
	 * @param string $statement sql-statement to execute
	 * @return integer
	 * @throws MVCLite_Db_Exception
	 */
	public function execute ($statement);
	
	/**
	 * Checks whether a connection already exists.
	 * 
	 * @return boolean
	 */
	public function isConnected ();
	
	/**
	 * Returns the id of the last inserted record.
	 * 
	 * @param string $name optional name (required for some database)
	 * @return integer
	 */
	public function lastInsertId ($name = null);
	
	/**
	 * This method returns a prepared statement.
	 * 
	 * The result is a PDOStatement or a object which interface
	 * is similar.
	 * 
	 * @param string $statement statement to operate
	 * @param array $options driver-specific options
	 * @return PDOStatement
	 */
	public function prepare ($statement, array $options = array());
	
	/**
	 * Executes a query and returns a statement containing the result.
	 * 
	 * You can perform various actions on the returned PDOStatement.
	 * The returned object can be a class which interface is
	 * compatible to the PDOStatement-interface.
	 * 
	 * @param string $statement statment to perform
	 * @return PDOStatement
	 * @throws MVCLite_Db_Exception
	 */
	public function query ($statement);
	
	/**
	 * Method used for quoting contents.
	 * 
	 * @param string $string string to quote
	 * @return string
	 */
	public function quote ($string);
	
	/**
	 * Rolls the transaction back.
	 * 
	 * @return boolean
	 */
	public function rollBack ();
}
?>