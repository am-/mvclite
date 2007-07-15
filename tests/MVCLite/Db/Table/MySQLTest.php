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

/**
 * Unit-testing for the MySQL-table-class.
 * 
 * @category   MVCLite
 * @package    Db
 * @subpackage UnitTests
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id: PDOTest.php 84 2007-07-11 21:50:48Z andre.moelle $
 */
class MVCLite_Db_Table_MySQLTest extends PHPUnit_Framework_TestCase
{
	public function getObject ()
	{
		return new MVCLite_Db_Table_MySQL();
	}
	
	public function setUp ()
	{
		$adapter = new MVCLite_Db_PDO('mysql:host=localhost;dbname=__mvclite', 'root', '');
		MVCLite_Db::getInstance()->setAdapter($adapter);
		$adapter->execute('TRUNCATE TABLE `foobar`');
	}
	
	public function tearDown ()
	{
		MVCLite_Db::getInstance()->setAdapter(null);
	}
	
	public function testTable ()
	{
		$table = new MVCLite_Db_Table_UnitTestTable();
		
		$this->assertEquals(
			1,
			$table->insert(array('content' => 'foobar'))
		);
		$this->assertEquals(
			2,
			$table->insert(array('content' => 'barbar'))
		);
		$this->assertEquals(
			4,
			$table->insert(array('id' => 4, 'content' => 'blubb'))
		);
		
		try
		{
			$table->insert(array('unknown' => 'value'));
			$this->assertTrue(
				false,
				'Invalid query should throw an exception'
			);
		}
		catch (MVCLite_Db_Exception $e)
		{
			
		}
		
		$this->assertEquals(
			1,
			$table->update(array('content' => 'bar'), 2)
		);
		$this->assertEquals(
			2,
			$table->update(array('content' => 'fubar'), array(1, 4))
		);
		$this->assertEquals(
			0,
			$table->update(array('content' => 'nothing!'), 5)
		);
		
		try
		{
			$table->update(array('unknown' => 'value'), 1);
			$this->assertTrue(
				false,
				'Invalid queries should throw exceptions'
			);
		}
		catch (MVCLite_Db_Exception $e)
		{
			
		}
		
		$temp = $table->select(array(1, 2));
		
		$this->assertTrue(
			is_array($temp)
		);
		
		foreach($temp as $id => $row)
		{
			$this->assertEquals(
				'MVCLite_Db_Record',
				get_class($row)
			);
			$this->assertEquals(
				$id,
				$row->getPrimary()
			);
			
			switch ($row->getPrimary())
			{
				case 1:
					$this->assertEquals(
						'fubar',
						$row->content
					);
					
					break;
				case 2:
					$this->assertEquals(
						'bar',
						$row->content
					);
					
					break;
			}
		}
		
		$temp = $table->select(4);
		
		$this->assertTrue(
			is_array($temp)
		);
		$this->assertEquals(
			'fubar',
			$temp[4]->content
		);
		
		$this->assertEquals(
			array(),
			$table->select(42)
		);
		
		$this->assertEquals(
			2,
			$table->delete(array(1, 4))
		);
		$this->assertEquals(
			0,
			$table->delete(array(1, 4))
		);
		$this->assertEquals(
			1,
			$table->delete(2)
		);
	}
}

// required classes

class MVCLite_Db_Table_UnitTestTable extends MVCLite_Db_Table_MySQL
{
	public function getColumns ()
	{
		return array(
			'id',
			'content'
		);
	}
	
	public function getName ()
	{
		return 'foobar';
	}
	
	public function getPrimary ()
	{
		return 'id';
	}
	
	public function validate (array $input)
	{
		return $this->errors;
	}
}
?>