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

// directory which contains the MVCLite.php-file and the MVCLite-directory.
define('MVCLITE_LIB', dirname(__FILE__) . '/../library/');
// base-directory of an app (mostly necessary for other constants)
define('MVCLITE_APP', dirname(__FILE__) . '/_files/');
// directory where application-specific code resides
define('MVCLITE_CODE', MVCLITE_APP . 'code/');
// directory where the controllers reside
define('MVCLITE_CONTROLLER', MVCLITE_APP . 'controllers/');
// directory where the models reside
define('MVCLITE_MODEL', MVCLITE_APP . 'models/');
// directory where the views (or templates) reside
define('MVCLITE_VIEW', MVCLITE_APP . 'views/');

/*
 * Adds new paths to the include-path.
 */
$paths = explode(PATH_SEPARATOR, get_include_path());
$paths[] = MVCLITE_LIB;
$paths[] = MVCLITE_CODE;

foreach($paths as $key => $path)
{
	$paths[$key] = realpath($path);
	
	if(!$paths[$key])
	{
		unset($paths[$key]);
	}
}

set_include_path(implode(PATH_SEPARATOR, array_unique($paths)));
?>