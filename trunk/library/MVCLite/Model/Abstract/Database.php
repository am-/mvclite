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
 * Currently it is able to return the active database-instance and
 * provides aliasing, which is useful when name of table are changed.
 * These alias should link a general name such as "blog" to a table-name
 * such as "someprefix_blog_content".
 * 
 * @category   MVCLite
 * @package    Model
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id:Abstract.php 133 2007-08-26 08:19:13Z andre.moelle $
 */
abstract class MVCLite_Model_Abstract_Database extends MVCLite_Model_Abstract
{
	/**
	 * Stores the aliases.
	 * 
	 * The key is the alias and the value represents the table-name.
	 * 
	 * <code>
	 * $_aliases = array(
	 * 	'X' => 'table_foo',
	 * 	'foobar' => 'table_bar'
	 * );
	 * </code>
	 * 
	 * @var array
	 */
	protected $_aliases = array();
	
	/**
	 * Returns the table-name of the alias.
	 * 
	 * If the alias was not set a MVCLite_Model_Exception will
	 * be thrown.
	 * 
	 * @param string $alias alias whose table-name is returned
	 * @return string
	 * @throws MVCLite_Model_Exception
	 */
	public function alias ($alias)
	{
		if(!isset($this->_aliases[$alias]))
		{
			throw new MVCLite_Model_Exception('Unknown alias "' . $alias . '"');
		}
		
		return $this->_aliases[$alias];
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
	 * Registers an alias and possibly overwrites an old one.
	 * 
	 * @param string $alias alias of the table
	 * @param string $table name of the table
	 * @return MVCLite_Model_Abstract_Database
	 */
	public function register ($alias, $table)
	{
		$this->_aliases[$alias] = $table;
		
		return $this;
	}
	
	/**
	 * Removes the alias from the aliases-list.
	 * 
	 * @param string $alias alias to remove
	 * @return MVCLite_Model_Abstract_Database
	 */
	public function unregister ($alias)
	{
		if(isset($this->_aliases[$alias]))
		{
			unset($this->_aliases[$alias]);
		}
		
		return $this;
	}
}
?>