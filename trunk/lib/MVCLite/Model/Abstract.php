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
 * Firstly it is able to fetch the used database-object. Many
 * useful features are added in the future.
 * 
 * @category   MVCLite
 * @package    Model
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
abstract class MVCLite_Model_Abstract
{
	/**
	 * Suffix for controller-classes.
	 */
	const SUFFIX = 'Model';
	
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
}
?>