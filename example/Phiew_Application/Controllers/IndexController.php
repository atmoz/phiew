<?php

/**
 * Example index controller
 */
class IndexController extends Phiew_Controller
{
	/**
	 * View intro
	 */
	public function getIndex()
	{
		echo $this->_renderCurrentAction(array(
			'loginUrl' => $this->_router->generateUrl('login')
		));
	}
}
