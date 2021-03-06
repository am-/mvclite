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
 * This helper enables the developer to create consistent url.
 * 
 * Conistent urls are used to hinder errors caused by wrong urls.
 * A popular example is the moving into a subdirectory. That would
 * force us to change all urls in the templates, to make it work
 * correctly. Therefore this helper prepend the base-url to the
 * given url.
 * 
 * @category   MVCLite
 * @package    View
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_View_Helper_Prepend extends MVCLite_View_Helper_Abstract
{
	/**
	 * Returns the real url of a file.
	 * 
	 * @param string $url incomplete url
	 * @return string
	 */
	public function prepend ($url)
	{
		if($url[0] == '/')
		{
			return MVCLITE_BASE_URL . substr($url, 1);
		}
		
		return MVCLITE_BASE_URL . $url;
	}
}
?>