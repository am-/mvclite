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
 * This is the classic-route for servers unable to rewrite urls.
 * 
 * It is designed to work without url-rewriting or other hacks.
 * Therefore it works with GET. Please refer to the following example
 * how the query is built.
 * 
 * <code>
 * controller=foo
 * -> controller would be set to Foo
 * action=bar
 * -> action would be set to bar
 * otherThanActionOrController=argument
 * -> would added an argument with the specified name (otherThanActionOrController)
 *    and the value "argument".
 * </code>
 * 
 * @category   MVCLite
 * @package    Request
 * @subpackage Route
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_Request_Route_Classic implements MVCLite_Request_Route
{
	/**
	 * Name of the action-attribute in URL-strings.
	 * 
	 * @const string
	 */
	const ACTION = 'action';
	
	/**
	 * Replacement-character for invalid characters.
	 */
	const CHARACTER = '-';
	
	/**
	 * Name of the controller-attribute in URL-strings.
	 * 
	 * @const string
	 */
	const CONTROLLER = 'controller';
	
	/**
	 * Prepares the key of a parameter.
	 * 
	 * The key is only allowed to contain characters and digits.
	 * Therefore non applicable signs are filtered out.
	 * 
	 * @param string $key key which should be prepared
	 * @return string
	 */
	protected function _prepareKey ($key)
	{
		return preg_replace('([^a-zA-Z0-9])', '', $key);
	}
	
	/**
	 * @see MVCLite_Request_Route::assemble()
	 */
	public function assemble (MVCLite_Request $request)
	{
		$array = array();
		
		foreach($request->getParams() as $key => $value)
		{
			if(is_numeric($key))
			{
				$key = $value;
				$value = '';
			}
			
			$array[$this->_prepareKey($key)] = $value;
		}
		
		if($request->getController() != 'Index')
		{
			$array[self::CONTROLLER] = strtolower($request->getController());
		}
		if($request->getAction() != 'index')
		{
			$array[self::ACTION] = $request->getAction();
		}
		
		$result = http_build_query($array);
		
		return ($result ? '?' : '') . $result;
	}
	
	/**
	 * @see MVCLite_Request_Route::parse()
	 */
	public function parse ($url)
	{
		$request = new MVCLite_Request($this);
		
		$query = parse_url($url, PHP_URL_QUERY);
		$query = strtr($query, array_flip(get_html_translation_table()));
		
		if(!$query)
		{
			return $request;
		}
		
		$args = array();
		
		foreach(explode('&', $query) as $part)
		{
			$temp = explode('=', $part);
			
			if($temp[0] == self::CONTROLLER && isset($temp[1]))
			{
				$request->setController($temp[1]);
			}
			else if($temp[0] == self::ACTION && isset($temp[1]))
			{
				$request->setAction($temp[1]);
			}
			else
			{
				$args[$temp[0]] = rawurldecode((isset($temp[1]) ? $temp[1] : ''));
			}
		}
		
		$request->setParams($args);
		
		return $request;
	}
}
?>