<?php

// Register autoloader
require_once '../../../library/Phiew/Autoload.php';
Phiew_Autoload::register();

// Setting folder where Phiew_View will look for templates
Phiew_View::setTemplateFolder('../Views');

/**
 * Example login controller
 */
class LoginController extends Phiew_Controller
{
	public $username = null;
	public $message  = 'Try to log in with a wrong username.';

	/**
	 * View form
	 */
	public function viewForm()
	{
		echo $this->_renderCurrentAction();
	}

	/**
	 * Handle form post request
	 */
	public function postForm()
	{
		// Make sure the username is saved for next request
		$this->username = $_POST['username'];

		// Fake login attempt message
		$this->message = 'Wrong username or password. Try again.';
		
		// Saves the state and redirects to URL with statekey as parameter
		$this->_redirectState($_SERVER['REQUEST_URI']);
	}
}


//------------------------------------------------------------------------------

// Bootstrap the controller (so the example works)
$controller = new LoginController();
$action = (isset($_REQUEST['action']) ? $_REQUEST['action'] : 'viewForm');
if (is_callable(array($controller, $action)))
{
	$controller->$action();
}
else
{
	echo 'Action does not exist!';
}
