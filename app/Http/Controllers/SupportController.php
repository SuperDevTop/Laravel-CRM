<?php

namespace App\Http\Controllers;

class SupportController extends BaseController {

	protected $layout = 'master';

	public function index() {
		// $this->layout->nav_main = 'Help & feedback';
		// $this->layout->nav_sub = 'Support';
		// $this->layout->content = View::make('support/index');
		return view('supplier.index', 
			[
				'nav_main' => 'Help & feedback',
				'nav_sub' => 'Support'
			]);
	}

}