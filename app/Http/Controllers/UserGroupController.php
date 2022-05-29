<?php

namespace App\Http\Controllers;

class UserGroupController extends BaseController {
	protected $layout = 'master';

	public function index() {
		// $this->layout->nav_main = 'System';
		// $this->layout->nav_sub = 'Manage User Groups';
		// $this->layout->content = View::make('usergroups.index');
		return view('usergroups.index', 
			[
				'nav_main' => 'System',
				'nav_sub' => 'Manage User Groups'
			]);
	}

}