<?php
/**
 * @author Adrian Dvergsdal
 * @link http://github.com/atmoz/phiew
 * @license http://creativecommons.org/licenses/by-sa/3.0/us/
 */

/**
 * Extend controller classes with this to get state management
 */
abstract class Phiew_Controller_Abstract
{
	/**
	 * The random state key
	 */
	protected $_stateKey;
	
	/**
	 * Redirect to another page, with the state key as parameter
	 * 
	 * @param string $url
	 * @param mixed $state
	 */
	protected function _redirect($url, $state = null)
	{
		$stateKey = null;
		if (!is_null($state))
		{
			$this->_setState($state);
			$stateKey = $this->_getStateKey();
			$url = $url . (stripos($url, '?') ? '&' : '?') . '=' . $stateKey;
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
	
	/**
	 * Generate new random state key
	 * 
	 * @return string
	 */
	protected function _generateStateKey()
	{
		return sha1(md5(mt_rand() . $_SERVER['SERVER_ADDR']) . $_SERVER['REMOTE_ADDR']);
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
				$this->_stateKey = $this->_generateStateKey();
			}
		}
		
		return $this->_stateKey;
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
			return $_SESSION[get_class($this)][$stateKey];
		}
		else
		{
			return $this->_getDefaultState();
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
	 * Generate default state. Overload this function.
	 * 
	 * @return mixed
	 */
	protected function _getDefaultState()
	{
		return array();
	}
}