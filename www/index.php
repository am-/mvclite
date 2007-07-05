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
 * This is the bootstrap-file which dispatches the request.
 * 
 * @category   Core
 * @package    MVCLite
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id:$
 */

include_once 'config.php';

/*
 * Adds new paths to the include-path.
 */
$paths = explode(PATH_SEPARATOR, get_include_path());
$paths[] = MVCLITE_LIB;

foreach($paths as $key => $path)
{
	$paths[$key] = realpath($path);
	
	if(!$paths[$key])
	{
		unset($paths[$key]);
	}
}

set_include_path(implode(PATH_SEPARATOR, array_unique($paths)));

/*
 * Lets the application dispatch.
 */
require_once 'MVCLite.php';

$mvc = MVCLite::getInstance();
echo $mvc->dispatch();
?>