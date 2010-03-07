<?php

require_once 'autoload.php';

// Setting folder where Phiew_View will look for templates
Phiew_View::setTemplateFolder('views');

/**
 * Example login controller
 */
class LoginController
{
	/**
	 * Controller state data
	 * @var Phiew_Controller_State
	 */
	protected $_state;

	/**
	 * Construct: set default state
	 */
	public function __construct()
	{
		$this->_state = new Phiew_Controller_State(array(
			'username'	=> null,
			'message'	=> 'Try to log in with a wrong username.'
		));
	}

	/**
	 * View form
	 */
	public function viewForm()
	{
		Phiew_View::render('controller-state', $this->_state);
	}

	/**
	 * Handle form post request
	 */
	public function postForm()
	{
		// Make sure the username is saved for next request
		$this->_state['username'] = $_POST['username'];

		// Fake login attempt message
		$this->_state['message'] = 'Wrong username or password. Try again.';
		
		// Saves the state and redirects to URL with statekey as parameter
		$this->_state->redirectData($_SERVER['REQUEST_URI']);
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
