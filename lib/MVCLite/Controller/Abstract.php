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
 * This is the abstract controller.
 * 
 * The controller is the class which executes what the request
 * says. It always returns a view, which can be displayed. In some
 * cases empty views are returned.
 * By notation every controller should end with "Controller" (self::SUFFIX)
 * and every action should end with "Action" (self::SUFFIX_ACTION).
 * Although these suffixes can be changed, this it not recommended.
 * The controllers has to be stored in the MVCLITE_CONTROLLER-directory,
 * which usually points to "/app/controllers". As filename you only have
 * to take the classname plus ".php".
 * 
 * Before dispatching the _init-method is executed. Thereafter the
 * corresponding action-method is executed. If this completed successfully
 * a view is returned which can be rendered later.
 * 
 * @category   MVCLite
 * @package    Controller
 * @copyright  2007 Nordic Development
 * @license    http://license.nordic-dev.de/newbsd.txt (New-BSD license)
 * @author     Andre Moelle <andre.moelle@gmail.com>
 * @version    $Id$
 */
abstract class MVCLite_Controller_Abstract
{
	/**
	 * Suffix for controller-classes.
	 * 
	 * @const string
	 */
	const SUFFIX = 'Controller';
	
	/**
	 * Suffix for action-methods.
	 * 
	 * @const string
	 */
	const SUFFIX_ACTION = 'Action';
	
	/**
	 * Determines whether layout should be used or not.
	 * 
	 * @var boolean
	 */
	protected $_layout = true;
	
	/**
	 * Instance of a layout-object.
	 * 
	 * @var MVCLite_View_Layout
	 */
	protected $_layoutObject;
	
	/**
	 * Instance of a model.
	 * 
	 * @var MVCLite_Model_Abstract
	 */
	protected $_model;
	
	/**
	 * Stores the name of this controller to prevent overhead.
	 * 
	 * @var string
	 */
	protected $_name = '';
	
	/**
	 * Request object used by this controller.
	 * 
	 * @var MVCLite_Request
	 */
	protected $_request;
	
	/**
	 * Determines if the view is used.
	 * 
	 * @var boolean
	 */
	protected $_view = true;
	
	/**
	 * Instance of a view.
	 * 
	 * @var MVCLite_View
	 */
	protected $_viewObject;
	
	/**
	 * Returns the view which is displayed at the end.
	 * 
	 * Firstly it checks whether a view should be displayed. If not
	 * it returns a "MVCLite_View_Empty"-object. Otherwise it returns
	 * always a direct MVCLite_View, but when the view should be layouted,
	 * this view is wrapped in "MVCLite_View_Layout"-object.
	 * 
	 * @return MVCLite_View
	 */
	final protected function _display ()
	{
		if(!$this->isDisplayed())
		{
						return new MVCLite_View_Empty();
		}
		
		$view = $this->getView();
		
		if($this->isLayouted())
		{
			$view = $this->getLayout()->setView($view);
		}
		
		return $view;
	}
	
	/**
	 * Returns the name of the controller.
	 * 
	 * This method is useful for some other methods. Since the
	 * controller-name stored in the request-object is not consistent
	 * enough this method returns always the correct name.
	 * 
	 * @return string
	 */
	protected function _getControllerName ()
	{
		if($this->_name == '')
		{
			$refl = new ReflectionObject($this);
			$this->_name = substr($refl->getName(), 0, -strlen(self::SUFFIX));
		}
		
		return $this->_name;
	}
	
	/**
	 * Checks whether the controller has the specified action.
	 * 
	 * The result is the correct method-name (if one exists).
	 * 
	 * @param string $action action to check (without suffix)
	 * @return string
	 * @throws MVCLite_Controller_Exception
	 */
	protected function _hasAction ($action)
	{
		$action = strtolower($action);
		
		if(in_array($action . self::SUFFIX_ACTION, get_class_methods($this)))
		{
			return $action . self::SUFFIX_ACTION;
		}
		
				
		throw new MVCLite_Controller_Exception('Action "' . $action . '" does not exist');
	}
	
	/**
	 * Empty method which can be used to initialize the controller.
	 */
	protected function _init ()
	{
		;
	}
	
	/**
	 * Method used for protecting the controller.
	 * 
	 * Firstly it checks whether the object is protectable. If not,
	 * false is returned immediately.
	 * Otherwise a security-check is executed. In cases of denied
	 * security-checks fallback-method of the Protectable-interface
	 * is executed. If this method does not redirect to another page
	 * or throw an own exception, a MVCLite_Security_Exception will
	 * be thrown. This disables the developer to make mistakes in
	 * the fallback-method which can cause security-lacks.
	 * 
	 * @return boolean
	 * @throws MVCLite_Security_Exception
	 */
	final protected function _protect ()
	{
		if(!$this instanceof MVCLite_Security_Protectable)
		{
			return false;
		}
		
		if($this->getProtector()->protect($this, $this->getRequest()->getAction()))
		{
			return true;
		}
		
		$this->wasProtected();
		
				throw new MVCLite_Security_Exception(
			'A security issue raised. You are not permitted to do that action.'
		);
	}
	
	/**
	 * This method dispatches the request.
	 * 
	 * It extracts the action from the request and calls the action
	 * if it exists. If this is not the case a "MVCLite_Controller_Exception"
	 * will be thrown. On security-issues an "MVCLite_Security_Exception"
	 * is thrown instead.
	 * Usually this method should return a MVCLite_View-object which contains
	 * the data which is displayed later.
	 * To finalize the request, the global-containers are synchronized.
	 * 
	 * @param MVCLite_Request $request request to dispatch
	 * @return MVCLite_View
	 * @throws MVCLite_Controller_Exception
	 * @throws MVCLite_Security_Exception
	 * @throws Exception
	 */
	final public function dispatch (MVCLite_Request $request)
	{
		$this->setRequest($request)
			 ->_init();
		$this->_protect();
		$this->{$this->_hasAction($request->getAction())}();
		
		$request->synchronize();
		
		return $this->_display();
	}
	
	/**
	 * Returns the layout-object using lazy initialization.
	 * 
	 * @return MVCLite_View_Layout
	 */
	public function getLayout ()
	{
		if($this->_layoutObject == null)
		{
						$this->_layoutObject = new MVCLite_View_Layout();
		}
		
		return $this->_layoutObject;
	}
	
	/**
	 * Loads and creates a model for this controller.
	 * 
	 * @return MVCLite_Model_Abstract
	 */
	public function getModel ()
	{
		if($this->_model == null)
		{
			$model = MVCLite_Loader::loadModel($this->_getControllerName());
			$this->_model = new $model();
		}
		
		return $this->_model;
	}
	
	/**
	 * Returns the request.
	 * 
	 * @return MVCLite_Request
	 */
	public function getRequest ()
	{
		return $this->_request;
	}
	
	/**
	 * Returns a view using lazy initialization.
	 * 
	 * If the view does not exist, a template fitting to the action
	 * is set.
	 * 
	 * <code>
	 * $controller = 'Foobar';
	 * $action = 'bar';
	 * // would set template "foobar/bar.phtml"
	 * </code>
	 * 
	 * @return MVCLite_View
	 */
	public function getView ()
	{
		if($this->_viewObject == null)
		{
						
			$this->setView(new MVCLite_View());
			$this->getView()
				 ->setTemplate(strtolower($this->_getControllerName()) . '/' . 
				 			   $this->getRequest()->getAction());		
		}
		
		return $this->_viewObject;
	}
	
	/**
	 * Returns true when a view should be displayed.
	 * 
	 * @return boolean
	 */
	public function isDisplayed ()
	{
		return $this->_view;
	}
	
	/**
	 * Returns true if the layout should be used.
	 * 
	 * @return boolean
	 */
	public function isLayouted ()
	{
		return $this->_layout;
	}
	
	/**
	 * Sets a request.
	 * 
	 * @param MVCLite_Request $request new request
	 * @return MVCLite_Controller_Abstract
	 */
	public function setRequest (MVCLite_Request $request)
	{
		$this->_request = $request;
		
		return $this;
	}
	
	/**
	 * Sets a view for this class.
	 * 
	 * @param MVCLite_View $view new view
	 * @return MVCLite_Controller_Abstract
	 */
	public function setView (MVCLite_View $view)
	{
		$this->_viewObject = $view;
		
		return $this;
	}
	
	/**
	 * Determines whether the layout should be used or not.
	 * 
	 * Setting this to true implies that a view will be used.
	 * 
	 * @param boolean $layout true if the layout should be used
	 * @return MVCLite_Controller_Abstract
	 */
	public function useLayout ($layout = true)
	{
		$this->_layout = (bool)$layout;
		
		if($layout)
		{
			return $this->useView(true);
		}
		
		return $this;
	}
	
	/**
	 * Determines whether this controller should return a view.
	 * 
	 * @param boolean $view true if a view should be returned
	 * @return MVCLite_Controller_Abstract
	 */
	public function useView ($view = true)
	{
		$this->_view = $view;
		
		if(!$view)
		{
			return $this->useLayout(false);
		}
		
		return $this;
	}
}
?>