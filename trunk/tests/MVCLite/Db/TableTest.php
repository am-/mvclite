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
	
	public function testSave ()
	{
		$table = $this->getObject();
		$record = $table->fetchRecord();
		
		// no primary
		$table->state = 0;
		$record->changePrimary(null);
		$this->assertEquals(
			array(),
			$table->save($record)
		);
		$this->assertEquals(
			UnitTest_Db_TableTest::INSERT,
			$table->state
		);
		
		// primary (which does not exist)
		$table->state = 0;
		$record->changePrimary('insert');
		$this->assertEquals(
			array(),
			$table->save($record)
		);
		$this->assertEquals(
			UnitTest_Db_TableTest::INSERT,
			$table->state
		);
		
		// primary (which exists)
		$table->state = 0;
		$record->changePrimary('update');
		$this->assertEquals(
			array(),
			$table->save($record)
		);
		$this->assertEquals(
			UnitTest_Db_TableTest::UPDATE,
			$table->state
		);
		
		try
		{
			// invalid primary
			$table->state = 0;
			$record->changePrimary('nothin');
			$table->save($record);
			
			$this->assertTrue(
				false,
				'It should be neither inserted nor updated.'
			);
		}
		catch (MVCLite_Db_Exception $e)
		{
			$this->assertEquals(
				0,
				$table->state
			);
		}
		
		// primary (which exists)
		$table->state = 0;
		$record->changePrimary('update');
		$this->assertEquals(
			array(),
			$table->saveArray($record->get())
		);
		$this->assertEquals(
			UnitTest_Db_TableTest::UPDATE,
			$table->state
		);
		
		// validation fails (nothing should be inserted)
		$table->state = 0;
		$table->errors = array('foo');
		$record->changePrimary('insert');
		$this->assertEquals(
			array('foo'),
			$table->save($record)
		);
		$this->assertEquals(
			0,
			$table->state
		);
	}
}

// required classes
class UnitTest_Db_TableTest extends MVCLite_Db_Table_Abstract
{
	const INSERT = 1;
	const UPDATE = 2;
	
	public $errors = array();
	
	public $state = 0;
	
	public function delete ($id)
	{
		throw new MVCLite_Db_Exception('This is not tested here.');
	}
	
	public function getColumns ()
	{
		return array(
			'id',
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
		return 'id';
	}
	
	public function insert (array $input)
	{
		if(empty($input['id']) || $input['id'] == 'insert')
		{
			$this->state = self::INSERT;
			return 1;
		}
		
		return 0;
	}
	
	public function select ($id)
	{
		throw new MVCLite_Db_Exception('This is not tested here.');
	}
	
	public function update (array $input, $id)
	{
		if($input['id'] == 'update')
		{
			$this->state = self::UPDATE;
			return 1;
		}
		
		return 0;
	}
	
	public function validate (array $input)
	{
		return $this->errors;
	}
}
?>