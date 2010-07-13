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
 * Controller state management with session
 */
class Phiew_Controller_State_Session implements Phiew_Controller_StateInterface
{
	/**
	 * @var string
	 */
	protected static $_stateKey;

	/**
	 * @var integer
	 */
	protected static $_instanceCounter;

	/**
	 * @var integer
	 */
	protected $_instanceId;

	/**
	 * @var array
	 */
	protected $_data;

    /**
	 * @var array
	 */
	protected $_defaultData;

	/**
	 * @param array $defaultData
	 */
	public function __construct(array $defaultData = array())
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
		$this->__set('stateKey', $this->getStateKey());
		$this->__set('stateInstance', $this->getInstanceId());

		// Set default data
        $this->setDefaultData($defaultData);

		// Load data from session, if any
		$this->loadState();

		// Make sure we have the data in session
		$this->saveState();
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
	public function getStateKey()
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
	public function loadState()
	{
		if (isset($_SESSION[$this->_getSessionKey()]))
		{
			$this->_data = unserialize($_SESSION[$this->_getSessionKey()]);
		}
	}

	/**
	 * Save state data
	 */
	public function saveState()
	{
		$_SESSION[$this->_getSessionKey()] = serialize($this->_data);
	}
	
    public function getData() {
        return $this->_data + $this->_defaultData;
    }
    
    public function setData(array $data) {
        $this->_data = $data;
    }

    public function getDefaultData() {
        return $this->_defaultData;
    }

    public function setDefaultData(array $data) {
        $this->_defaultData = $data;
    }

	protected function _getSessionKey()
	{
		return sprintf('state-%s-%s-%s', $this->getStateKey(), get_class($this), $this->getInstanceId());
	}

	public function __get($name)
	{
		if (isset($this->_data[$name]))
		{
			return $this->_data[$name];
		}
        else if (isset($this->_defaultData[$name]))
        {
            return $this->_defaultData[$name];
        }
		else
		{
			return null;
		}
	}

	public function __set($name, $value)
	{
		$this->_data[$name] = $value;
	}
}
