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
 * This is the PDO-adapter which can be used by MVCLite.
 * 
 * Mainly it directs the method-calls to the PDO-object.
 * 
 * @category   MVCLite
 * @package    Db
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_Db_PDO implements MVCLite_Db_Adaptable
{
	/**
	 * Instance of the PDO-instance.
	 * 
	 * @var PDO
	 */
	protected $_pdo;
	
	/**
	 * Information about the database-connection.
	 * 
	 * These information are extracted from the constructor and
	 * are used to create the PDO-object.
	 * 
	 * @var array
	 */
	protected $_connection = array();
	
	/**
	 * Creates the instance of the PDO-class.
	 * 
	 * @param string $dsn information about the connection
	 * @param string $username username
	 * @param string $password password
	 * @param array $options driver-specific options
	 */
	public function __construct ($dsn, $username = '', $password = '', array $options = array())
	{
		$array = func_get_args();
		
		for($i = 0, $c = func_num_args(); $i < $c; $i++)
		{
			if(!$array[$i])
			{
				break;
			}
		}
		
		$this->_connection = array_slice($array, 0, $i);
	}
	
	/**
	 * Returns the PDO-instance.
	 * 
	 * Because this adapter can be created without need for database
	 * operations, the PDO-class is instantiated only when it is
	 * needed.
	 * 
	 * @return PDO
	 * @throws MVCLite_Db_Exception
	 */
	protected function pdo ()
	{
		if(!$this->isConnected())
		{
			try
			{
				$class = new ReflectionClass('PDO');
				$this->_pdo = $class->newInstanceArgs($this->_connection);
			}
			catch (PDOException $e)
			{
				throw new MVCLite_Db_Exception($e->getMessage(), $e->getCode());
			}
		}
		
		return $this->_pdo;
	}
	
	/**
	 * @see MVCLite_Db_Adaptable::beginTransaction()
	 */
	public function beginTransaction ()
	{
		return $this->pdo()->beginTransaction();
	}
	
	/**
	 * @see MVCLite_Db_Adaptable::commit()
	 */
	public function commit ()
	{
		return $this->pdo()->commit();
	}
	
	/**
	 * @see MVCLite_Db_Adaptable::errorCode()
	 */
	public function errorCode ()
	{
		return $this->pdo()->errorCode();
	}
	
	/**
	 * @see MVCLite_Db_Adaptable::errorInfo()
	 */
	public function errorInfo ()
	{
		return $this->pdo()->errorInfo();
	}
	
	/**
	 * @see MVCLite_Db_Adaptable::execute()
	 */
	public function execute ($statement)
	{
		$stmt = $this->prepare($statement);
		$stmt->execute();
		
		$result = $stmt->rowCount();
		$info = $stmt->errorInfo();
		
		if($info != array('00000'))
		{
			throw new MVCLite_Db_Exception($info[1] . ': ' . $info[2]);
		}
		
		return $result;
	}
	
	/**
	 * @see MVCLite_Db_Adaptable::isConnected()
	 */
	public function isConnected ()
	{
		return $this->_pdo != null;
	}
	
	/**
	 * @see MVCLite_Db_Adaptable::lastInsertId()
	 */
	public function lastInsertId ($name = null)
	{
		if($name)
		{
			return $this->pdo()->lastInsertId($name);
		}
		
		return $this->pdo()->lastInsertId();
	}
	
	/**
	 * @see MVCLite_Db_Adaptable::prepare()
	 */
	public function prepare ($statement, array $options = array())
	{
		if($options)
		{
			$stmt = $this->pdo()->prepare($statement, $options);
		}
		else
		{
			$stmt = $this->pdo()->prepare($statement);
		}
		
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		return $stmt;
	}
	
	/**
	 * @see MVCLite_Db_Adaptable::query()
	 */
	public function query ($statement)
	{
		$result = $this->prepare($statement);
		$result->execute();
		$info = $result->errorInfo();
		
		if($info != array('00000'))
		{
			throw new MVCLite_Db_Exception($info[1] . ': ' . $info[2]);
		}
		
		return $result;
	}
	
	/**
	 * @see MVCLite_Db_Adaptable::quote()
	 */
	public function quote ($string)
	{
		return $this->pdo()->quote($string);
	}
	
	/**
	 * @see MVCLite_Db_Adaptable::rollBack()
	 */
	public function rollBack ()
	{
		return $this->pdo()->rollBack();
	}
}
?>