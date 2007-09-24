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

/**
 * The base-model contains the suffix for each model.
 * 
 * It is designed as base-class for each model-class. Since there
 * are numerous types of models this class does not contain any
 * specific code of these types such as database or other data-sources.
 * 
 * @category   MVCLite
 * @package    Model
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id:Abstract.php 133 2007-08-26 08:19:13Z andre.moelle $
 */
abstract class MVCLite_Model_Abstract
{
	/**
	 * Suffix for controller-classes.
	 */
	const SUFFIX = 'Model';
}
?>