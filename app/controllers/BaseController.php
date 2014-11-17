<?php

class BaseController extends Controller {
    protected $user = '';

    public function __construct() {
        $this->user = (new User)->checkLogin();
    }

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}
