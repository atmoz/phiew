<?php

// Folder where Phiew_View will look for templates
Phiew_View::setTemplateFolder(dirname(__FILE__) . '/../Views');

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
