<?php

namespace App\Http\Controllers;

class QuickSellController extends BaseController {

	protected $layout = 'master';

	public function index() {
		// $this->layout->nav_main = 'Quick Sell';
		// $this->layout->nav_sub = '';
		// $this->layout->content = View::make('quicksell.index');
		return view('quicksell.index', 
			[
				'nav_main' => 'Quick Sell',
				'nav_sub' => ''
			]);
	}

}