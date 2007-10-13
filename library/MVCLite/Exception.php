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
 * Exception which is used within MVCLite.
 * 
 * @category   MVCLite
 * @package    Core
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id:Exception.php 133 2007-08-26 08:19:13Z andre.moelle $
 */
class MVCLite_Exception extends Exception
{
	/**
	 * URL in which the error occured.
	 * 
	 * @var string
	 */
	protected $_url;
	
	/**
	 * Returns the url.
	 * 
	 * @return string
	 */
	public function getUrl ()
	{
		return $this->_url;
	}
	
	/**
	 * Sets an url.
	 * 
	 * @param string $url new url
	 */
	public function setUrl ($url)
	{
		$this->_url = $url;
	}
}
?>