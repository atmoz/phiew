<?php

/**
 * Example login controller
 */
class LoginController extends Phiew_ControllerAbstract
{
    protected function _initialize()
    {
        $this->_state->setDefaultData(array(
			'username' => null,
			'message'  => 'Try to log in with a wrong username.'
		));
    }

	/**
	 * View form
	 */
	public function getIndex()
	{
		echo $this->_renderCurrentAction();
	}

	/**
	 * Handle form post request
	 */
	public function postIndex()
	{
		// Make sure the username is saved for next request
		$this->_state->username = $_POST['username'];

		// Fake login attempt message
		$this->_state->message = 'Wrong username or password. Try again.';
		
		// Saves the state and redirects to URL with statekey as parameter
		$this->_redirectState($_SERVER['REQUEST_URI']);
	}
}
