<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class NavigationController extends BaseController {
	public static function showNavigation() {
		return View::make('navigation')
			->with('user', Auth::user());
	}
}