<?php

require_once 'autoload.php';
define('PHIEW_VIEW_DIR', dirname(__FILE__) . '/views');

// Important: Phiew_Controller_StateAbstract need an active session
session_start();

class LoginController extends Phiew_Controller_StateAbstract
{
	protected function _createDefaultState()
	{
		return array(
			'statekey'	=> $this->_getStateKey(),
			'username'	=> null,
			'message'	=> null
		);
	}
	
	public function showForm()
	{
		$state = $this->_getState();
		Phiew_View::render('controller-state', $state);
	}
	
	public function submitForm()
	{
		$url = $_SERVER['REQUEST_URI'];
		$state = $this->_createDefaultState();
		$state['username'] = $_POST['username'];
		
		if ($_POST['username'] == 'test' && $_POST['password'] == 'test')
		{
			$state['message'] = 'Login OK. Todo: redirect user somewhere ...';
			$this->_redirectState($url, $state);
		}
		else
		{
			$state['message'] = 'Wrong username or password. Try again.';
			$this->_redirectState($url, $state);
		}
	}
}

// Bootstrap the controller
$controller = new LoginController();
$action = (isset($_REQUEST['action']) ? $_REQUEST['action'] : 'showForm');
if (is_callable(array($controller, $action)))
{
	$controller->$action();
}
else
{
	echo 'Action does not exist!';
}
