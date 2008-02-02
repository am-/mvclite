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
require_once 'MVCLite/Db/Row.php';

/**
 * 
 * @category   MVCLite
 * @package    Db
 * @subpackage UnitTests
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id: PDOTest.php 84 2007-07-11 21:50:48Z andre.moelle $
 */
class MVCLite_Db_RowTest extends PHPUnit_Framework_TestCase
{
	public function insertCase (array $array)
	{
		$this->assertEquals(3, count($array));
		foreach(array('foobar', 'fubar', 'foo') as $element)
		{
			$this->assertTrue(in_array($element, $array), "Element $element not in array");
		}
	}
	
	public function updateCase (array $array)
	{
		$this->assertEquals(2, count($array));
		foreach(array('foobar', 'bar') as $element)
		{
			$this->assertTrue(in_array($element, $array), "Element $element not in array");
		}
	}
	
	public function grantCase (array $array)
	{
		$this->assertEquals(0, count($array));
	}
	
	public function getObject ()
	{
		return new UnitTestSampleRow();
	}
	public function testInsert ()
	{
		$obj = $this->getObject();
		$this->insertCase($obj->insert());
		
		$obj->modus = true;
		$this->grantCase($obj->insert());
	}
	
	public function testUpdate ()
	{
		$obj = $this->getObject();
		$this->updateCase($obj->update());
		
		$obj->modus = true;
		$this->grantCase($obj->update());
	}
	
	public function testValidate ()
	{
		$obj = $this->getObject();
		
		$this->updateCase($obj->validate());
		$this->updateCase($obj->validate(true));
		$this->insertCase($obj->validate(false));
		
		$obj->modus = true;
		$this->grantCase($obj->validate());
	}
}

// required classes

class UnitTestSampleRow extends MVCLite_Db_Row
{
	public $modus = false;
	
	public function getTable ()
	{
		return 'foobar';
	}
	
	public function validateFoobar ()
	{
		if($this->modus)
		{
			return array();
		}
		
		return (array)'foobar';
	}
	
	public function validateFooOnInsert ()
	{
		if($this->modus)
		{
			return array();
		}
		
		return (array)'foo';
	}
	
	public function validateFubarOnInsert ()
	{
		if($this->modus)
		{
			return array();
		}
		
		return (array)'fubar';
	}
	
	public function validateBarOnUpdate ()
	{
		if($this->modus)
		{
			return array();
		}
		
		return (array)'bar';
	}
}
?>