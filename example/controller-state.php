<?php

require_once 'autoload.php';
define('PHIEW_VIEW_DIR', dirname(__FILE__) . '/views');

// Important: Phiew_Controller_StateAbstract need an active session
session_start();

class LoginController extends Phiew_Controller_StateAbstract
{
	protected function _createDefaultState()
	{
		// Define default values in state
		return array(
			'username'	=> null,
			'message'	=> 'Try to log in with a wrong username.'
		);
	}
	
	public function showForm()
	{
		$state = $this->_getState();
		Phiew_View::render('controller-state', $state);
	}
	
	public function submitForm()
	{
		$state = $this->_createDefaultState();
		$state['username'] = $_POST['username']; // Modify state
		
		if ($_POST['username'] == 'test' && $_POST['password'] == 'test')
		{
			$state['message'] = 'Login OK. :-)';
		}
		else
		{
			$state['message'] = 'Wrong username or password. Try again.';
		}
		
		// Saves the state and redirects to URL with statekey as parameter
		$this->_redirectState($_SERVER['REQUEST_URI'], $state);
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
