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
require_once 'MVCLite/Db/Exception.php';
require_once 'MVCLite/Db/PDO.php';

/**
 * Unit-testing for the PDO-database adapter.
 * 
 * @category   MVCLite
 * @package    Db
 * @subpackage UnitTests
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_Db_PDOTest extends PHPUnit_Framework_TestCase
{
	public function testMySQL ()
	{
		// TODO: Add tests for transactions.
		
		try
		{
			$mysql = new MVCLite_Db_PDO('mysql:host=foobar;dbname=__mvclite', 'root', '');
			$mysql->quote('foobar');
			$this->assertTrue(false, 'On connection-failure a exception should be thrown.');
		}
		catch (MVCLite_Db_Exception $e)
		{
			;
		}
		
		$mysql = new MVCLite_Db_PDO('mysql:host=localhost;dbname=__mvclite', 'root', '');
		
		$this->assertFalse(
			$mysql->isConnected(),
			'PDO-adapter should not be connected'
		);
		
		// this is necessary to fetch the correct last insert id
		$mysql->execute('TRUNCATE TABLE `foobar` ');
		
		$this->assertEquals(
			1,
			$mysql->execute('INSERT INTO foobar (content) VALUES ("foobar")'),
			'Insertion failed'
		);
		$this->assertTrue(
			$mysql->isConnected(),
			'PDO-adapter should be connected'
		);
		
		$this->assertEquals(
			1,
			$mysql->lastInsertId(),
			'1 should be last inserted id'
		);
		
		$statement = $mysql->prepare('SELECT * FROM foobar');
		
		$this->assertEquals(
			'PDOStatement',
			get_class($statement),
			'Prepared statement should be an instance of PDOStatement'
		);
		
		$statement->execute();
		
		$this->assertEquals(
			1,
			count($statement->fetchAll()),
			'Only one result should be returned'
		);
		
		$this->assertEquals(
			1,
			$mysql->execute('INSERT INTO foobar (content) VALUES ("foobar")'),
			'Insertion failed'
		);
		
		try
		{
			$mysql->execute('UPDATE content = "bar" FROM foobar');
			$this->assertTrue(false, 'Invalid query should throw an exception');
		}
		catch (MVCLite_Db_Exception $e)
		{
			
		}
		
		$this->assertEquals(
			2,
			$mysql->execute('UPDATE foobar SET content = "bar" WHERE content = "foobar"'),
			'This method should indicate that two rows are updated'
		);
		$this->assertEquals(
			0,
			$mysql->execute('UPDATE foobar SET content = "bar" WHERE content = "foobar"'),
			'This method should indicate that no rows were updated'
		);
		
		$this->assertEquals(
			0,
			count($mysql->query('SELECT * FROM foobar WHERE content = "foobar"')->fetchAll()),
			'Select returned more than zero rows'
		);
		
		$this->assertEquals(
			'00000',
			$mysql->errorCode(),
			'If no errors occured, this method should return 00000'
		);
		$this->assertEquals(
			array('00000'),
			$mysql->errorInfo(),
			'If no errors occured, this method should return an array with the element 00000'
		);
		
		$this->assertEquals(
			'\'foo\\"bar\'',
			$mysql->quote('foo"bar'),
			'Quote failed'
		);
		
		$this->assertEquals(
			2,
			$mysql->execute('DELETE FROM foobar WHERE 1'),
			'One row should be deleted'
		);
	}
}
?>