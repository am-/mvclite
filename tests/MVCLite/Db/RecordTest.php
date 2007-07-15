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
class MVCLite_Db_RecordTest extends PHPUnit_Framework_TestCase
{
	public function getObject ()
	{
		return new UnitTest_Db_RecordTest();
	}
	
	public function testConstruction ()
	{
		$table = $this->getObject();
		
		$record = new MVCLite_Db_Record($table, array());
		
		$this->assertEquals(
			array(),
			$record->get()
		);
		
		$full = array(
			'id' => '42',
			'foobar' => 'as',
			'foo' => 'bd',
			'bar' => 'dg'
		);
		
		$record = new MVCLite_Db_Record($table, $full);
		
		$this->assertEquals(
			$full,
			$record->get()
		);
		
		$overhead = array(
			'id' => '42',
			'foobar' => 'as',
			'foo' => 'bd',
			'bar' => 'dg',
			'unknown' => 'asas',
			'overhead' => 'asas'
		);
		
		$record = new MVCLite_Db_Record($table, $overhead);
		
		$this->assertEquals(
			$full,
			$record->get()
		);
		
		$this->assertEquals(
			$table,
			$record->getTable()
		);
	}
	
	public function testColumn ()
	{
		$record = new MVCLite_Db_Record($this->getObject(), array('id' => '42'));
		
		$this->assertTrue(
			$record->hasColumn('id')
		);
		$this->assertTrue(
			$record->hasColumn('foobar')
		);
		$this->assertFalse(
			$record->hasColumn('asasas')
		);
	}
	
	public function testHandling ()
	{
		$table = $this->getObject();
		
		$record = new MVCLite_Db_Record($table, array());
		
		$this->assertEquals(
			null,
			$record->foobar
		);
		
		$record->foobar = 'bar';
		
		$this->assertEquals(
			'bar',
			$record->foobar
		);
		
		try
		{
			$record->id = '23';
			$this->assertTrue(
				false,
				'Primary cannot be changed'
			);
		}
		catch (MVCLite_Db_Exception $e)
		{
			
		}
		
		try
		{
			$record->asas = 'foobar';
			$this->assertTrue(
				false,
				'Unknown columns cannot be changed'
			);
		}
		catch (MVCLite_Db_Exception $e)
		{
			
		}
		try
		{
			$record->asas;
			$this->assertTrue(
				false,
				'Unknown columns cannot be retrieved'
			);
		}
		catch (MVCLite_Db_Exception $e)
		{
			
		}
	}
	
	public function testPrimary ()
	{
		$table = $this->getObject();
		
		$record = new MVCLite_Db_Record($table, array('id' => '42'));
		
		$this->assertFalse(
			$record->isPrimary('foobar')
		);
		$this->assertTrue(
			$record->isPrimary('id')
		);
		
		$this->assertEquals(
			'42',
			$record->getPrimary()
		);
		
		$record->changePrimary('23');
		
		$this->assertEquals(
			'23',
			$record->getPrimary()
		);
		
		$record = new MVCLite_Db_Record($table, array());
		
		$this->assertEquals(
			null,
			$record->getPrimary()
		);
	}
	
	public function testSet ()
	{
		$table = $this->getObject();
		
		$record = new MVCLite_Db_Record($table, array());
		
		try
		{
			$record->set(array('id' => 42));
			$this->assertTrue(
				false,
				'By default the protected- and exception-mode should be activated'
			);
		}
		catch (MVCLite_Db_Exception $e)
		{
			
		}
		
		$this->assertEquals(
			null,
			$record->set(array('id' => 42, 'foobar' => 'foo'), MVCLite_Db_Record::MODUS_PROTECTED)->getPrimary(),
			'Protected mode should protect the primary-key'
		);
		
		$this->assertEquals(
			'foo',
			$record->foobar
		);
		
		$this->assertEquals(
			42,
			$record->set(array('id' => 42), 0)->getPrimary(),
			'Primary should be set.'
		);
		$this->assertEquals(
			42,
			$record->set(array('id' => 23, 'bar' => 'foo'), MVCLite_Db_Record::MODUS_RECREATE | MVCLite_Db_Record::MODUS_PROTECTED)->getPrimary(),
			'Primary should be retained when recreating in protected mode'
		);
		
		$this->assertEquals(
			null,
			$record->foobar,
			'Recreation did not delete old content'
		);
		$this->assertEquals(
			'foo',
			$record->bar,
			'Recreation did not set new content'
		);
		
		$this->assertEquals(
			23,
			$record->set(array('id' => 23, 'foobar' => 'bar'), MVCLite_Db_Record::MODUS_RECREATE)->getPrimary(),
			'Primary should be retained in only recreation-modus.'
		);
		$this->assertEquals(
			'bar',
			$record->foobar,
			'Recreation did not set new content'
		);
		$this->assertEquals(
			null,
			$record->bar,
			'Recreation did not delete old content'
		);
	}
}

// required classes
class UnitTest_Db_RecordTest extends MVCLite_Db_Table_Abstract
{
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
		return 'testTable';
	}
	
	public function getPrimary ()
	{
		return 'id';
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