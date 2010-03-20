<?php

/**
 * Example index controller
 */
class IndexController extends Phiew_Controller
{
	/**
	 * View intro
	 */
	public function index()
	{
		echo $this->_renderCurrentAction();
	}
}
