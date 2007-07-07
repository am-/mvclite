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
 * Dispatches the application.
 */
require_once 'MVCLite.php';

$start = microtime(true);

$mvc = MVCLite::getInstance();
echo $mvc->dispatch($_SERVER['REQUEST_URI']);

echo "\n" . '<!-- ' . sprintf('%0.3f', microtime(true) - $start) . 's -->';
?>