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
 * Handles 404-errors.
 * 
 * @category   MVCLite
 * @package    Error
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id:MVCLite.php 133 2007-08-26 08:19:13Z andre.moelle $
 */
class MVCLite_Error_NotFound extends MVCLite_Error_Abstract
{
	/**
	 * @see MVCLite_Error_Abstract::getApplicableName()
	 */
	protected function getApplicableName ()
	{
		return array(
			'MVCLite_Controller_Exception',
			'MVCLite_Request_Dispatcher_Exception'
		);
	}
	
	/**
	 * @see MVCLite_Error_Abstract::match()
	 */
	public function match (array $inheritance)
	{
		$result = -1;
		
		foreach($this->getApplicableName() as $name)
		{
			$score = array_search($name, $inheritance);
			
			if($score !== false && $score > $result)
			{
				$result = $score;
			}
		}
		
		return $result;
	}
	
	/**
	 * @see MVCLite_Error_Abstract::matchExactly()
	 */
	public function matchExactly (Exception $e)
	{
		$class = get_class($e);
		
		return in_array($class, $this->getApplicableName());
	}
	
	/**
	 * @see MVCLite_Error_Abstract::handle()
	 */
	public function handle (Exception $e)
	{
		$view = new MVCLite_View_Layout();
		$view->setView('_errors/404')
			 ->title = '404 - Not Found';
		
		$subView = $view->getView();
		$subView->requestUrl = $e->getUrl();
		
		return $view;
	}
}
?>