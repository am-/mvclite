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
 * Abstract class for all classes handling exceptions.
 * 
 * One method has to be implemented to define the exception-name which
 * should be handled by the specified object. Another abstract-method
 * is used to handle the exception, which returns a MVCLite_View_Layout.
 * 
 * @category   MVCLite
 * @package    Error
 * @copyright  2007-2008 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id:MVCLite.php 133 2007-08-26 08:19:13Z andre.moelle $
 */
abstract class MVCLite_Error_Abstract
{
	/**
	 * This method returns the searched exception.
	 * 
	 * It returns the name of the exception which is applicable
	 * by this chain-element. Since it is possible, that one
	 * handler accepts more than one type of exception, the
	 * result is an array.
	 * 
	 * @return array
	 */
	abstract protected function getApplicableName ();
	
	/**
	 * Handles the exception and returns a view-layout.
	 * 
	 * @param Exception $e handled exception
	 * @return MVCLite_View_Layout
	 */
	abstract public function handle (Exception $e);
	
	/**
	 * Returns the score calculated using the inheritance-list.
	 * 
	 * The score is the level which of the exception in the list.
	 * 
	 * <code>
	 * // Inheritance-list of the exception MVCLite_Loader_Exception
	 * $inheritance = array(
	 * 	0 => 'Exception',
	 * 	1 => 'MVCLite_Exception',
	 * 	2 => 'MVCLite_Loader_Exception'
	 * );
	 * </code>
	 * 
	 * If the name of the searched exception is "MVCLite_Exception" 1
	 * is returned. On "Exception" 0 is returned. "MVCLite_Loader_Exception"
	 * would return 2, although this means that it was already matched
	 * exactly before.
	 * To sum it up, the key of the element whose value is searched is
	 * always returned. If it does not exist, -1 is returned instead.
	 * 
	 * @param array $inheritance inheritance tree
	 * @return integer
	 */
	public function match (array $inheritance)
	{
		$result = -1;
		
		foreach((array)$this->getApplicableName() as $name)
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
	 * Checks if the exception matches exactly the searched type.
	 * 
	 * When true is returned, the chain handles the exception
	 * immediately by this chain-element.
	 * 
	 * @param Exception $e exception that is checked
	 * @return boolean
	 */
	public function matchExactly (Exception $e)
	{
		$class = get_class($e);
		
		return in_array($class, (array)$this->getApplicableName());
	}
}
?>