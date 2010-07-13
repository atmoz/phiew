<?php
/**
 * Phiew
 * - Simple and fast MVC components for PHP 5
 *
 * @version 0.12.0
 * @author Adrian Dvergsdal
 * @link http://github.com/atmoz/phiew
 * @license http://creativecommons.org/licenses/by-sa/3.0/us/
 */

/**
 * Makes your controllers easier to use
 */
abstract class Phiew_ControllerAbstract
{
	/**
	 * @var Phiew_Controller_RouterInterface
	 */
	protected $_router;

	/**
	 * @var Phiew_Controller_StateInterface
	 */
	protected $_state;

	/**
	 * Setup
	 * 
	 * @param array $config
	 */
	public final function __construct($config = array())
	{
		// Merge values with default
		$config += array(
			'router' => new Phiew_Controller_Router_UrlParameters(),
			'state'  => new Phiew_Controller_State_Session()
		);

		// Set values
		$this->setRouter($config['router']);
		$this->setState($config['state']);

        // Run custom construct function
        $this->_initialize();
	}

    /**
     * Override to use
     */
    protected function _initialize()
    {
    }

    /**
     * @return Phiew_Controller_RouterInterface
     */
	public function getRouter()
	{
		return $this->_router;
	}

    /**
     * @param Phiew_Controller_RouterInterface $router
     */
	public function setRouter(Phiew_Controller_RouterInterface $router)
	{
		$this->_router = $router;
	}

    /**
     * @return Phiew_Controller_StateInterface
     */
	public function getState() {
		return $this->_state;
	}

    /**
     * @param Phiew_Controller_StateInterface $state
     */
	public function setState(Phiew_Controller_StateInterface $state) {
		$this->_state = $state;
	}

	/**
	 * Render template
	 *
	 * @param string $view
	 * @param array $data
	 * @return string|null
	 */
	protected function _render($view, array $data = array())
	{
		if (empty($data))
		{
			$data = $this->getState()->getData();
		}

		return Phiew_View::render($view, $data);
	}

	/**
	 * Render template based on controller action. Using class name as folder.
	 *
	 * @param string $action
	 * @param array $data
	 * @return string|null
	 */
	protected function _renderAction($action, $data = array())
	{
		return $this->_render(get_class($this) . '/' . $action, $data);
	}

	/**
	 * Render template based on current controller action (calling function)
	 *
	 * @param array $data
	 * @return string|null
	 */
	protected function _renderCurrentAction($data = array())
	{
		// Get calling function name
		$trace = debug_backtrace(false);
		$function = $trace[1]['function'];

		return $this->_renderAction($function, $data);
	}

	/**
	 * Save state and redirect to another page, with the session key as parameter
	 *
	 * @param string $url
	 */
	protected function _redirectState($url)
	{
		$this->_state->saveState();

		if (preg_match('/[&\?]stateKey=/', $url))
		{
			$url = preg_replace('/([&\?]stateKey=)[^&#]*/', '${1}'.$this->_state->getStateKey(), $url);
		}
		else
		{
			$url .= (strpos($url, '?') ? '&' : '?') . 'stateKey=' . $this->_state->getStateKey();
		}

		if (headers_sent())
		{
			echo '<script type="text/javascript">document.location = "'
				. htmlspecialchars($url) . '"</script>';
		}
		else
		{
			header('Location: ' . $url);
		}

		exit(sprintf('Redirecting to "<a href="%1$s">%1$s</a>"', htmlspecialchars($url)));
	}
}
