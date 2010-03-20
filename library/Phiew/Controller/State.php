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
 * Controller state management
 */
class Phiew_Controller_State
{
	/**
	 * Random state key
	 * @var string
	 */
	protected static $_stateKey;

	/**
	 * Counting instances
	 * @var integer
	 */
	protected static $_instanceCounter;

	/**
	 * Instance ID
	 * @var integer
	 */
	protected $_instanceId;

	/**
	 * Constructor
	 *
	 * @param array $defaultData
	 */
	public function __construct()
	{
		self::$_instanceCounter++;

		// Determine what current instance ID should be
		if (isset($_POST['stateInstance']) && ctype_digit($_POST['stateInstance']))
		{
			$this->_instanceId = (int) $_POST['stateInstance'];
		}
		else
		{
			$this->_instanceId = self::$_instanceCounter;
		}

		// We need an active session
		if (!session_id())
		{
			session_start();
		}

		// If using the object directly in the view, this is nice to have access to
		$this->stateKey  = $this->_getStateKey();
		$this->stateInstance = $this->_getInstanceId();

		// Load data from session, if any
		$this->_loadState();

		// Make sure we have the data in session
		$this->_saveState();
	}

	/**
	 * Get current instance ID
	 *
	 * @return integer
	 */
	protected function _getInstanceId()
	{
		return $this->_instanceId;
	}

	/**
	 * Get the session key, existing or new
	 *
	 * @return string
	 */
	protected function _getStateKey()
	{
		if (empty(self::$_stateKey))
		{
			// Depends on the option variables_order in php.ini to have default value
			if (isset($_REQUEST['stateKey']))
			{
				self::$_stateKey = $_REQUEST['stateKey'];
			}
			else
			{
				self::$_stateKey = substr(sha1(mt_rand()), 0, 8);
			}
		}

		return self::$_stateKey;
	}

	/**
	 * Load saved data, if any
	 */
	protected function _loadState()
	{
		$sessionKey = $this->_getStateKey();

		if (isset($_SESSION[__CLASS__][$sessionKey][$this->_getInstanceId()]))
		{
			$state = unserialize($_SESSION[__CLASS__][$sessionKey][$this->_getInstanceId()]);
			//print_r($state); echo '<br />';
			foreach ($state as $property => $value)
			{
					$this->$property = $value;
			}
		}
	}

	/**
	 * Save state data
	 */
	protected function _saveState()
	{
		$state = array();
		foreach ((array)$this as $property => $value)
		{
			if (ctype_alpha(substr($property, 0, 1)) && strpos($property, ':') === false)
				$state[$property] = $value;
		}
		$_SESSION[__CLASS__][$this->_getStateKey()][$this->_getInstanceId()] = serialize($state);
	}

	/**
	 * Save data and redirect to another page, with the session key as parameter
	 *
	 * @param string $url
	 */
	protected function _redirectState($url)
	{
		$this->_saveState();

		if (preg_match('/[&\?]stateKey=/', $url))
		{
			$url = preg_replace('/([&\?]stateKey=)[^&#]*/', '${1}'.$this->_getStateKey(), $url);
		}
		else
		{
			$url .= (strpos($url, '?') ? '&' : '?') . 'stateKey=' . $this->_getStateKey();
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

		exit( sprintf('Redirecting to "<a href="%1$s">%1$s</a>"', htmlspecialchars($url)) );
	}
}
