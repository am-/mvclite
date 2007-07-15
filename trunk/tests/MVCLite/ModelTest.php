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
require_once 'MVCLite/Db/PDO.php';
require_once 'MVCLite/Loader.php';
require_once 'MVCLite/Model/Abstract.php';

/**
 * Tests MVCLite_Model_Abstract.
 * 
 * @category   MVCLite
 * @package    Model
 * @subpackage UnitTests
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_ModelTest extends PHPUnit_Framework_TestCase
{
	public function testDatabase ()
	{
		$adapter = new MVCLite_Db_PDO('unimportant');
		MVCLite_Db::getInstance()->setAdapter($adapter);
		
		$model = new ModeltestModel();
		
		$this->assertEquals(
			$adapter,
			$model->getDatabase(),
			'Adapter and model-adapter do not match.'
		);
	}
	
	public function testTables ()
	{
		$model = new ModeltestModel();
		
		$foo = new UnitTest_ModelTest();
		$bar = new UnitTest_ModelTest();
		
		$this->assertEquals(
			$model,
			$model->attachTable('foo', $foo)
		);
		$this->assertEquals(
			$model,
			$model->attachTable('bar', $bar)
		);
		
		$this->assertTrue(
			$model->hasTable('foo')
		);
		$this->assertFalse(
			$model->hasTable('unknown')
		);
		
		$this->assertEquals(
			$foo,
			$model->getTable('foo')
		);
		
		try
		{
			$model->getTable('unknown');
			$this->assertTrue(
				false,
				'If retrieving an unknown table a exception should be thrown'
			);
		}
		catch (MVCLite_Model_Exception $e)
		{
			
		}
		
		$this->assertEquals(
			$model,
			$model->detachTable('foo')
		);
		$this->assertFalse(
			$model->hasTable('foo')
		);
		
		$this->assertEquals(
			$bar,
			$model->getTable('bar')
		);
	}
}

/*
 * Classes required for unittesting.
 */
class ModeltestModel extends MVCLite_Model_Abstract
{
	
}


class UnitTest_ModelTest extends MVCLite_Db_Table_Abstract
{
	public function delete ($id)
	{
		throw new MVCLite_Db_Exception('This is not tested here.');
	}
	
	public function getColumns ()
	{
		throw new MVCLite_Db_Exception('This is not tested here.');
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