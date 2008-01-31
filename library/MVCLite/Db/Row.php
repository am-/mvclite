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
 * This is the row-class for creating the bridge between database and application.
 * 
 * It is mainly used as object containing data. But it can also be used
 * for business-objects. This is intentionally left out in the standard
 * implementation. Another important fact is the automatic validation. For
 * more information see the method "validate".
 * 
 * 
 * @category   MVCLite
 * @package    Db
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id:Adaptable.php 133 2007-08-26 08:19:13Z andre.moelle $
 */
abstract class MVCLite_Db_Row
{
	/**
	 * The suffix for validation method called before an insert.
	 * 
	 * @var string
	 */
	const VALIDATE_INSERT = 'OnInsert';
	
	/**
	 * The prefix for the validate-methods.
	 * 
	 * @var string
	 */
	const VALIDATE_PREFIX = 'validate';
	
	/**
	 * The suffix for validation method called before an update.
	 * 
	 * @var string
	 */
	const VALIDATE_UPDATE = 'OnUpdate';
	
	/**
	 * Returns the name of the table whose row this object 
	 */
	abstract public function getTable ();
	
	/**
	 * This method inserts the row into the database.
	 * 
	 * On success an empty array is returned.
	 * 
	 * @return array
	 */
	public function insert ()
	{
		return $this->validate(false);
	}
	
	/**
	 * This method updates the specified row in the database.
	 * 
	 * On success an empty array is returned.
	 * 
	 * @return array
	 */
	public function update ()
	{
		return $this->validate();
	}
	
	/**
	 * This method validates the content of the row.
	 * 
	 * On sucess an empty array is returned otherwise the array
	 * contains error-messages.
	 * 
	 * It calls each method in this object which starts with the
	 * given prefix (VALIDATE_PREFIX, by default "validate"). To
	 * prevent endless-loops the prefix itself will be ignored.
	 * Additionally some suffixes are added (VALIDATE_INSERT and
	 * VALIDATE_UPDATE) which enables the developer to treat some
	 * fields different on insert or update. An incremented
	 * primary key does not have to be validated on insert since
	 * the database does it for you. For the update the primary key
	 * would be obligatory. Simply, it adds flexibility to the
	 * validation routine.
	 * 
	 * @param boolean $update decides whether an update or insert follows
	 * @return array
	 */
	public function validate ($update = true)
	{
		$result = array();
		$prefix = self::VALIDATE_PREFIX;
		$len = strlen($prefix);
		
		$suffix = array(self::VALIDATE_UPDATE);
		$element = self::VALIDATE_INSERT;
		
		if($update)
		{
			$suffix[] = $element;
		}
		else
		{
			array_unshift($suffix, $element);
		}
		
		$suffixLen = strlen($suffix[0]);
		
		foreach(get_class_methods($this) as $method)
		{
			if($method == $prefix || substr($method, 0, $len) != $prefix)
			{
				continue;
			}
			
			$end = substr($method, -$suffixLen);
			
			if($end == $suffix[1])
			{
				continue;
			}
			
			foreach($this->$method() as $item)
			{
				$result[] = $item;
			}
		}
		
		return $result;
	}
}
?>