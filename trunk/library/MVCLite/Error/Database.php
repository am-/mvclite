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
 * Handles database errors.
 * 
 * @category   MVCLite
 * @package    Error
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id:MVCLite.php 133 2007-08-26 08:19:13Z andre.moelle $
 */
class MVCLite_Error_Database extends MVCLite_Error_Abstract
{
	/**
	 * @see MVCLite_Error_Abstract::getApplicableName()
	 */
	protected function getApplicableName ()
	{
		return (array)'MVCLite_Db_Exception';
	}
	
	/**
	 * @see MVCLite_Error_Abstract::handle()
	 */
	public function handle (Exception $e)
	{
		$view = new MVCLite_View_Layout();
		$view->setView('_errors/database')
			 ->title = 'Database error';
		
		$subView = $view->getView();
		$subView->render = MVCLite_Db::getInstance()->isDisplayed();
		$subView->requestUrl = $e->getUrl();
		$subView->exceptionObject = $e;
		
		return $view;
	}
}
?>
