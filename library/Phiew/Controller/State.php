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
class Phiew_Controller_State extends ArrayObject
{
	/**
	 * Random state session key
	 * @var string
	 */
	protected static $_sessionKey;

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
	public function __construct($defaultData = array())
	{
		parent::__construct($defaultData, ArrayObject::ARRAY_AS_PROPS);
		self::$_instanceCounter++;
		$this->_initInstanceId();

		// We need an active session
		if (!session_id())
		{
			session_start();
		}

		// Load data from session
		$this->loadData();

		// If using the object directly in the view, this is nice to have access to
		$this->stateSession  = $this->getSessionKey();
		$this->stateInstance = $this->getInstanceId();

		// Make sure we have the data in session
		$this->saveData();
	}

	/**
	 * Determine what the instance ID is
	 */
	protected function _initInstanceId()
	{
		if (isset($_POST['stateInstance']))
		{
			$this->_instanceId = $_POST['stateInstance'];
		}
		else
		{
			$this->_instanceId = self::$_instanceCounter;
		}
	}

	/**
	 * Get current instance ID
	 *
	 * @return integer
	 */
	public function getInstanceId()
	{
		return $this->_instanceId;
	}

	/**
	 * Get the session key, existing or new
	 *
	 * @return string
	 */
	public function getSessionKey()
	{
		if (empty(self::$_sessionKey))
		{
			// Depends on the option variables_order in php.ini to have default value
			if (isset($_REQUEST['stateSession']))
			{
				self::$_sessionKey = $_REQUEST['stateSession'];
			}
			else
			{
				self::$_sessionKey = substr(sha1(mt_rand()), 0, 8);
			}
		}

		return self::$_sessionKey;
	}

	/**
	 * Load saved data, if any
	 */
	public function loadData()
	{
		$sessionKey = $this->getSessionKey();

		if (isset($_SESSION[__CLASS__][$sessionKey][$this->getInstanceId()]))
		{
			$data = unserialize($_SESSION[__CLASS__][$sessionKey][$this->getInstanceId()]);
			$this->exchangeArray((array) $data);
		}
	}

	/**
	 * Save state data
	 */
	public function saveData()
	{
		$data = (array) $this;
		$_SESSION[__CLASS__][$this->getSessionKey()][$this->getInstanceId()] = serialize($data);
	}

	/**
	 * Save data and redirect to another page, with the session key as parameter
	 *
	 * @param string $url
	 */
	public function redirectData($url)
	{
		$this->saveData();

		if (preg_match('/[&\?]stateSession=/', $url))
		{
			$url = preg_replace('/([&\?]stateSession=)[^&#]*/', '${1}'.$this->getSessionKey(), $url);
		}
		else
		{
			$url .= (strpos($url, '?') ? '&' : '?') . 'stateSession=' . $this->getSessionKey();
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
