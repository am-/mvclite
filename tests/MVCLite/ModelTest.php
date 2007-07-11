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
}

/*
 * Classes required for unittesting.
 */
class ModeltestModel extends MVCLite_Model_Abstract
{
	
}
?>