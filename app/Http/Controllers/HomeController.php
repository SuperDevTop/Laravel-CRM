<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

class HomeController extends BaseController {

	// Check if we are logged in, if we are redirect to the dashboard, if not, redirect to /login
	public function openWebsite() {

		if (Auth::check()) {
			return redirect('dashboard');
		} else {
			return redirect('login');
		}
	}

}

?>