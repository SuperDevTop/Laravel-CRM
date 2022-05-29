<?php

namespace App\Http\Controllers;

class DashboardController extends BaseController {

	protected $layout = 'master';

	public function index() {
		return redirect('quotes');

		// $this->layout->nav_main = 'Dashboard';
		// $this->layout->nav_sub = '';
		// $this->layout->content = View::make("dashboard")
		// 	->with('nav_main', 'Dashboard')
		// 	->with('nav_sub', '');
	}
}

?>