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
define('MVCLITE_LIB', '../lib/');
// base-directory of an app (mostly necessary for other constants)
define('MVCLITE_APP', '../app/');
// directory where the controllers reside
define('MVCLITE_CONTROLLER', MVCLITE_APP . 'controllers/');
// directory where the models reside
define('MVCLITE_MODEL', MVCLITE_APP . 'models/');
// directory where the views (or templates) reside
define('MVCLITE_VIEW', MVCLITE_APP . 'views/');
?>