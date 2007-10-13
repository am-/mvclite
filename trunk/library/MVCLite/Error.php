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
 * This class handles all errors while dispatching.
 * 
 * It is used mainly for rendering some type of errors correctly.
 * I.e. a non-existing page should produce a view displaying the
 * 404-error. For database-exception it is useful to print out
 * the query and the error-message.
 * 
 * @category   MVCLite
 * @package    Error
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id:MVCLite.php 133 2007-08-26 08:19:13Z andre.moelle $
 */
class MVCLite_Error
{
	/**
	 * Array containing MVCLite_Error_Abstract-objects.
	 * 
	 * @var array
	 */
	private $_chain = array();
	
	/**
	 * Attaches a new element to the chain.
	 * 
	 * @param MVCLite_Error_Abstract $element new element
	 * @return MVCLite_Error
	 */
	public function attach (MVCLite_Error_Abstract $element)
	{
		$this->_chain[get_class($element)] = $element;
		
		return $this;
	}
	
	/**
	 * Detaches an element from the chain.
	 * 
	 * @param string $class element which should be removed
	 * @return MVCLite_Error
	 */
	public function detach ($class)
	{
		if(isset($this->_chain[$class]))
		{
			unset($this->_chain[$class]);
		}
		
		return $this;
	}
	
	/**
	 * Returns the chain.
	 * 
	 * @return array
	 */
	public function getChain ()
	{
		return $this->_chain;
	}
	
	/**
	 * This method handles an exception.
	 * 
	 * It is used to display errors appropriately. To build a dynamic
	 * system for handling, this method uses some kind of Chain of
	 * Responsibility. This means that the method searches for an
	 * error-type which fits best to the given exception. To
	 * accomplish this the inheritance tree of the given exception
	 * is used.
	 * 
	 * @param Exception $e handles an exception
	 * @return MVCLite_View_Layout
	 */
	public function handle (Exception $e)
	{
		$inheritance = $this->parseInheritance($e);
		$handler = null;
		$score = -1;
		
		foreach($this->getChain() as $element)
		{
			if($element->matchExactly($e))
			{
				return $element->handle($e);
			}
			
			if(($newScore = $element->match($inheritance)) < $score)
			{
				continue;
			}
			
			$score = $newScore;
			$handler = $element;
		}
		
		if($score < 0)
		{
			return null;
		}
		
		return $handler->handle($e);
	}
	
	/**
	 * This method parses the inheritance tree of an exception.
	 * 
	 * It is used for finding the chain-element which fits best
	 * to the given exception.
	 * 
	 * <code>
	 * // Example for the exception MVCLite_Loader_Exception
	 * $result = array(
	 * 	'Exception',
	 * 	'MVCLite_Exception',
	 * 	'MVCLite_Loader_Exception'
	 * );
	 * </code>
	 * 
	 * @param Exception $e exception that inheritance tree is parsed
	 * @return array
	 */
	public function parseInheritance (Exception $e)
	{
		$result = array();
		$class = new ReflectionClass(get_class($e));
		
		do
		{
			array_unshift($result, $class->getName());
			$class = $class->getParentClass();
		}
		while($class);
		
		return $result;
	}
}
?>