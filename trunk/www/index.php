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
 * @category   MVCLite
 * @package    Core
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */

$start = microtime(true);
include_once 'config.php';

/*
 * Dispatches the application.
 */
$bootstrap = new Bootstrap('development');
$mvc = $bootstrap->bootstrap();
echo $mvc->dispatch($_SERVER['REQUEST_URI'])
		 ->render();

echo "\n" . '<!-- ' . sprintf('%0.3f', microtime(true) - $start) . 's -->';
?>