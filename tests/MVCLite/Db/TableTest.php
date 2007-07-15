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
 * Unit-testing for the PDO-database adapter.
 * 
 * @category   MVCLite
 * @package    Db
 * @subpackage UnitTests
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id: PDOTest.php 84 2007-07-11 21:50:48Z andre.moelle $
 */
class MVCLite_Db_TableTest extends PHPUnit_Framework_TestCase
{
	public function getObject ()
	{
		return new UnitTest_Db_TableTest();
	}
	
	public function testAssemble ()
	{
		$table = $this->getObject();
		
		$array = array(
			'foobar' => 'barbar',
			'foo' => 'barbar',
			'fubar' => 'buz'
		);
		
		$this->assertEquals(
			array(
				'foobar' => 'barbar',
				'foo' => 'barbar'
			),
			$table->assemble($array)
		);
		$this->assertEquals(
			array(),
			$table->assemble(array())
		);
	}
	
	public function testDb ()
	{
		$table = $this->getObject();
		
		try
		{
			$this->assertTrue(
				$table->db() instanceof MVCLite_Db_Adaptable
			);
		}
		catch (MVCLite_Db_Exception $e)
		{
			;
		}
	}
	
	public function testRecord ()
	{
		$table = $this->getObject();
		
		$array = array(
			'foobar' => 'barbar',
			'foo' => 'barbar',
			'fubar' => 'buz'
		);
		
		$record = new MVCLite_Db_Record($table, $array);
		$array2 = $record->get();
		
		$this->assertEquals(
			$record,
			$table->fetchRecord($array)
		);
		$this->assertEquals(
			array(),
			$table->fetchRecord()->get()
		);
		$this->assertEquals(
			$array2,
			$table->fetchRecord($array2)->get()
		);
	}
}

// required classes
class UnitTest_Db_TableTest extends MVCLite_Db_Table_Abstract
{
	public function delete ($id)
	{
		throw new MVCLite_Db_Exception('This is not tested here.');
	}
	
	public function getColumns ()
	{
		return array(
			'foobar',
			'foo',
			'bar'
		);
	}
	
	public function getName ()
	{
		throw new MVCLite_Db_Exception('This is not tested here.');
	}
	
	public function getPrimary ()
	{
		throw new MVCLite_Db_Exception('This is not tested here.');
	}
	
	public function insert (array $input)
	{
		throw new MVCLite_Db_Exception('This is not tested here.');
	}
	
	public function select ($id)
	{
		throw new MVCLite_Db_Exception('This is not tested here.');
	}
	
	public function update (array $input, $id)
	{
		throw new MVCLite_Db_Exception('This is not tested here.');
	}
	
	public function validate (array $input)
	{
		throw new MVCLite_Db_Exception('This is not tested here.');
	}
}
?>