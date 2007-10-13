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
 * Handles general errors.
 * 
 * @category   MVCLite
 * @package    Error
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id:MVCLite.php 133 2007-08-26 08:19:13Z andre.moelle $
 */
class MVCLite_Error_General extends MVCLite_Error_Abstract
{
	/**
	 * @see MVCLite_Error_Abstract::getApplicableName()
	 */
	protected function getApplicableName ()
	{
		return 'Exception';
	}
	
	/**
	 * @see MVCLite_Error_Abstract::handle()
	 */
	public function handle (Exception $e)
	{
		$view = new MVCLite_View_Layout();
		$view->setView('_errors/general')
			 ->title = 'General error';
		
		$subView = $view->getView();
		$subView->requestUrl = ($e instanceof MVCLite_Exception ? 
			$e->getUrl() : $_SERVER['REQUEST_URI']);
		$subView->exceptionObject = $e;
		
		return $view;
	}
}
?>