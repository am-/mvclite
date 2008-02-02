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
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id: ModelTest.php 120 2007-07-15 13:35:26Z andre.moelle $
 */
class MVCLite_Model_DatabaseTest extends PHPUnit_Framework_TestCase
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
	
	public function testAliasing ()
	{
		$model = new ModeltestModel();
		
		$this->assertTrue(
			$model->register('foobar', 'X') instanceof MVCLite_Model_Abstract_Database
		);
		$this->assertEquals(
			'X',
			$model->alias('foobar')
		);
		
		$this->assertTrue(
			$model->unregister('foobar') instanceof MVCLite_Model_Abstract_Database
		);
		
		try
		{
			$model->alias('foobar');
			$this->assertTrue(false, 'Unknown alias should throw an exception');
		}
		catch (MVCLite_Model_Exception $e)
		{
			
		}
	}
}

/*
 * Classes required for unittesting.
 */
class ModeltestModel extends MVCLite_Model_Abstract_Database
{
	
}
?>