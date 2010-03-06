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
 * Extend controller classes with this to get state management
 */
abstract class Phiew_Controller_StateAbstract
{
	/**
	 * The random state key
	 */
	protected $_stateKey;

	/**
	 * Generate default state. Overload this.
	 *
	 * @return mixed
	 */
	protected function _createDefaultState()
	{
		return array(); // Return your own state
	}

	/**
	 * Get the saved state, or default state if empty
	 *
	 * @return mixed
	 */
	protected function _getState()
	{
		$stateKey = $this->_getStateKey();

		if (isset($_SESSION[get_class($this)][$stateKey]))
		{
			return unserialize($_SESSION[get_class($this)][$stateKey]);
		}
		else
		{
			return $this->_createDefaultState();
		}
	}

	/**
	 * Save state
	 *
	 * @param mixed $state
	 */
	protected function _setState($state)
	{
		$_SESSION[get_class($this)][$this->_getStateKey()] = serialize($state);
	}

	/**
	 * Get the state key, existing or new
	 *
	 * @return string
	 */
	protected function _getStateKey()
	{
		if (empty($this->_stateKey))
		{
			if (isset($_REQUEST['statekey']))
			{
				$this->_stateKey = $_REQUEST['statekey'];
			}
			else
			{
				$this->_stateKey = substr(sha1(mt_rand()), 0, 16);
			}
		}

		return $this->_stateKey;
	}

	/**
	 * Redirect to another page, with the state key as parameter
	 *
	 * @param string $url
	 * @param mixed $state
	 */
	protected function _redirectState($url, $state = null)
	{
		if (!is_null($state))
		{
			$this->_setState($state);

			if (preg_match('/[&\?]statekey=/', $url))
			{
				$url = preg_replace('/([&\?]statekey=)[^&#]*/', '${1}'.$this->_getStateKey(), $url);
			}
			else
			{
				$url .= (strpos($url, '?') ? '&' : '?') . 'statekey=' . $this->_getStateKey();
			}
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

		exit('<a href="' . htmlspecialchars($url) . '">Redirecting ...</a>');
	}
}
