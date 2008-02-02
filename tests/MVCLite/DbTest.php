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

include 'setUp.php';
require_once 'MVCLite/Db.php';
require_once 'MVCLite/Db/Exception.php';
require_once 'MVCLite/Db/PDO.php';

/**
 * Unit-testing for the PDO-database adapter.
 * 
 * @category   MVCLite
 * @package    Db
 * @subpackage UnitTests
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_DbTest extends PHPUnit_Framework_TestCase
{
	public function testClass ()
	{
		$db = MVCLite_Db::getInstance();
		
		try
		{
			$db->setAdapter(null)->getAdapter();
			$this->assertTrue(
				false,
				'Getting of an unset adapter should thrown an exception'
			);
		}
		catch (MVCLite_Db_Exception $e)
		{
			;
		}
		
		$this->assertEquals(
			'MVCLite_Db_PDO',
			get_class($db->setAdapter(new MVCLite_Db_PDO('unimportant'))->getAdapter()),
			'Class of set adapter does not match'
		);
		
		$this->assertTrue(
			$db->display(true)->isDisplayed(),
			'Display-method does not function correctly'
		);
		$this->assertFalse(
			$db->display(false)->isDisplayed(),
			'Display-method does not function correctly'
		);
		$this->assertFalse(
			$db->display()->isDisplayed(),
			'Display-method does not function correctly'
		);
	}
}
?>