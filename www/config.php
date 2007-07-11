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
define('MVCLITE_LIB', dirname(__FILE__) . '/../lib/');
// base-directory of an app (mostly necessary for other constants)
define('MVCLITE_APP', dirname(__FILE__) . '/../app/');
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
$paths[] = MVCLITE_APP;

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
 * Registers the autoloader.
 */
require_once 'MVCLite/Loader.php';
MVCLite_Loader::register();

/*
 * Most essential configurations are done here.
 */

switch ($profile)
{
	default:
	case 'development':
		// set error-levels
		error_reporting(E_ALL);
		ini_set('display_errors', 'On');
		
		// setting up database
		MVCLite_Db::getInstance()
				  ->setAdapter(new MVCLite_Db_PDO('mysql:host=localhost;dbname=mvclite', 'root'))
				  ->display(true);
		
				MVCLite::getInstance()->display(true);
		
		break;
		
	case 'production':
		// set error-levels
		ini_set('display_errors', 'Off');
		
		break;
		
	case 'test':
		// set error-levels
		ini_set('display_errors', 'Off');
		
		break;
}
?>