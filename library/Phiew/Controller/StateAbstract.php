<?php
/**
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
	 * Generate default state.
	 * 
	 * @return mixed
	 */
	abstract protected function _createDefaultState();
	
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
			return $_SESSION[get_class($this)][$stateKey];
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
		$stateKey = $this->_getStateKey();
		$_SESSION[get_class($this)][$stateKey] = $state;
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
			if (isset($_REQUEST['stateKey']))
			{
				$this->_stateKey = $_REQUEST['stateKey'];
			}
			else
			{
				$this->_stateKey = $this->_createNewStateKey();
			}
		}
		
		return $this->_stateKey;
	}
	
	/**
	 * Generate new random state key
	 * 
	 * @return string
	 */
	protected function _createNewStateKey()
	{
		return sha1(md5(mt_rand() . $_SERVER['SERVER_ADDR']) . $_SERVER['REMOTE_ADDR']);
	}
	
	/**
	 * Redirect to another page, with the state key as parameter
	 * 
	 * @param string $url
	 * @param mixed $state
	 */
	protected function _redirectState($url, $state = null)
	{
		$stateKey = null;
		if (!is_null($state))
		{
			$this->_setState($state);
			$stateKey = $this->_getStateKey();
			$url = $url . (stripos($url, '?') ? '&' : '?') . 'stateKey=' . $stateKey;
		}
		
		if (headers_sent())
		{
			echo '<script type="text/javascript">document.location = '
				 . htmlspecialchars($url) . '</script>';
			echo '<a href="' . htmlspecialchars($url) . '">Redirecting ...</a>';
		}
		else
		{
			header('location: ' . $url);
		}
		
		exit();
	}
}