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
 * This is the default-route for the MVCLite-project.
 * 
 * The route consists of three componenents:
 * 1.) Controller
 * 2.) Action
 * 3.) Arguments
 * 
 * These components are separated by the "/" (COMPONENT_DELIMITER).
 * Controller and action are both strings which consist mainly of
 * characters. "Index" or "Foo" are examples for controller- or
 * action-name. The arguments are far more complex, due it abstracts
 * an associative array in an URI and tries to make it as readable
 * as possible.
 * There are some delimiters in the arguments-component. At first
 * the underscore (ARG_DELIMITER) indicates the token between two
 * different arguments. The point (VALUE_DELIMITER) indicates the
 * token between key and value. The part before the point is the
 * key while the part after the point represents the value. Leaving
 * out the point causes the part to be a value.
 * You can find more information in the specified methods.
 * 
 * @category   MVCLite
 * @package    Request
 * @subpackage Route
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
class MVCLite_Request_Route_Standard implements MVCLite_Request_Route
{
	/**
	 * Delimiter between two arguments.
	 * 
	 * @const string
	 */
	const ARG_DELIMITER = '_';
	
	/**
	 * Character to which unallowed characters are converted to.
	 */
	const CHARACTER = '-';
	
	/**
	 * Character which separates the components.
	 * 
	 * @const string
	 */
	const COMPONENT_DELIMITER = '/';
	
	/**
	 * Suffix of the entire uri.
	 * 
	 * @const string
	 */
	const SUFFIX = '.html';
	
	/**
	 * Delimiter between key and value of an argument.
	 * 
	 * @const string
	 */
	const VALUE_DELIMITER = '.';
	
	/**
	 * This method assembles an array of arguments to a single-string.
	 * 
	 * <code>
	 * $arguments = array(
	 * 	'foo' => 'bar'
	 * );
	 * // would output "foo.bar"
	 * $arguments = array(
	 * 	'foo' => 'bar',
	 * 	'barbar'
	 * );
	 * // would output "foo.bar_barbar"
	 * $arguments = array(
	 * 	'foo' => 'bar',
	 * 	'barbar',
	 * 	'bar' => ''
	 * )
	 * // would output "foo.bar_barbar_bar."
	 * </code>
	 * 
	 * Please note that every key (value before a ".") can only consist of
	 * characters and digits. '$abc123' would be translated to 'abc123'.
	 * Some characters in the value (value after a ".") can be filtered. See
	 * for more information in the "_prepare"-method.
	 * 
	 * @see MVCLite_Request_Route_Standard::_prepare()
	 * @see MVCLite_Request_Route_Standard::_prepareKey()
	 * @param array $arguments arguments to assemble
	 * @return string
	 */
	protected function _assembleArguments (array $arguments)
	{
		if($arguments == array())
		{
			return '';
		}
		
		$args = array();
		
		foreach($arguments as $key => $value)
		{
			$value = $this->_prepare($value);
			
			if(is_numeric($key))
			{
				$args[] = $value;
			}
			else
			{
				$args[] = $this->_prepareKey($key) . self::VALUE_DELIMITER . $value;
			}
		}
		
		return implode(self::ARG_DELIMITER, $args);
	}
	
	/**
	 * The arguments from an URL-string are parsed here.
	 * 
	 * The suffix is cut off when it existed before. Otherwise nothing
	 * is done. I.e. that you can leave out the suffix in URLs, but you
	 * are advised to do not so.
	 *
	 * <code>
	 * $string = 'foo.bar___'; // would output only array('foo' => 'bar')
	 * $string = 'foo.bar.baz'; // would output array('foo', 'bar.baz')
	 * $string = 'foo'; // would output array('foo')
	 * $string = 'foo.'; // would output array('foo' => '')
	 * $string = '.bar'; // would output array(); applies whenever nothing is before the "."
	 * $string = '_'; // would output array()
	 * $string = '._'; // would output array()
	 * </code>
	 * 
	 * @param string $string string to transform to an array containing arguments
	 * @return array
	 */
	protected function _parseArguments ($string)
	{
		$result = array();
		
		if($string == '')
		{
			return $result;
		}
		
		if(substr($string, -strlen(self::SUFFIX)) == self::SUFFIX)
		{
			$string = substr($string, 0, -strlen(self::SUFFIX));
		}
		
		foreach(explode(self::ARG_DELIMITER, $string) as $argument)
		{
			if(!$argument)
			{
				continue;
			}
			
			$temp = explode(self::VALUE_DELIMITER, $argument, 2);
			
			if(count($temp) > 1 && $temp[0])
			{
				$result[$temp[0]] = $temp[1];
			}
			else if($temp[0])
			{
				$result[] = $argument;
			}
		}
		
		return $result;
	}
	
	/**
	 * Prepares the value.
	 * 
	 * It converts the German umlauts and the sharp s to their
	 * pendants. Additionally, the argument-delimiter and whitespaces
	 * are converted to the CHARACTER-string. Thereafter all %xx
	 * characters are converted to the CHARACTER-string. If there are
	 * more than one CHARACTER-strings in a row, they are replaced to
	 * only one CHARACTER-string.
	 * 
	 * @param string $value value to prepare
	 * @return string
	 */
	protected function _prepare ($value)
	{
		$result = rawurlencode(
			str_replace(
				array(
					self::ARG_DELIMITER,
					' ',
					'ä',
					'Ä',
					'ü',
					'Ü',
					'ö',
					'Ö',
					'ß'
				),
				array(
					self::CHARACTER,
					self::CHARACTER,
					'ae',
					'Ae',
					'ue',
					'Ue',
					'oe',
					'Oe',
					'ss'
				),
				$value
			)
		);
		
		$result = preg_replace(
			'/(\-){2,}/',
			'-',
			preg_replace(
				'(%[a-zA-Z0-9]{2})',
				'-',
				$result
			)
		);
		
		if(in_array(substr($result, -1), array(self::CHARACTER, self::ARG_DELIMITER, self::VALUE_DELIMITER)))
		{
			return substr($result, 0, -1);
		}
		
		return $result;
	}
	
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
		$url = array();
		$url[] = strtolower($request->getController());
		$url[] = $request->getAction();
		$url[] = $this->_assembleArguments($request->getParams()) . self::SUFFIX;
		
		foreach(array_reverse($url) as $key => $value)
		{
			if($value != self::SUFFIX && $value != 'index')
			{
				break;
			}
			
			unset($url[2 - $key]);
		}
		
		if(!count($url))
		{
			return '';
		}
		
		return implode(self::COMPONENT_DELIMITER, $url);
	}
	
	/**
	 * @see MVCLite_Request_Route::parse()
	 */
	public function parse ($uri)
	{
		$request = new MVCLite_Request($this);
		$components = $uri ? explode(self::COMPONENT_DELIMITER, $uri, 3) : array();
		
		switch (count($components))
		{
			case 0:
				$components[] = 'Index';
			case 1:
				$components[] = 'index';
			case 2:
				$components[] = '';
		}
		
		$request->setController($components[0])
				->setAction($components[1])
				->setParams($this->_parseArguments($components[2]));
		
		return $request;
	}
}
?>