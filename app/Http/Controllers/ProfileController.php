<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Hash;

use App\Classes\SimpleImage;

class ProfileController extends BaseController {

	protected $layout = 'master';

	public function index() {
		$user = Auth::user();

		// $this->layout->nav_main = '-1';
		// $this->layout->nav_sub = '-1';
		// $this->layout->content = View::make('profile')
		// 	->with('user', $user);
		return view('profile', 
			[
				'user' => $user,
				'nav_main' => '-1',
				'nav_sub' => '-1'
			]);

	}

	public function save() {
		$user = Auth::user();

		if ($user) {
			if (Request::hasFile('avatar')) {
				$avatar = new SimpleImage;
				$avatar->load_base64(substr(Request::get('new_avatar_data'), 22));
				$avatar->best_fit(100, 100);
				$avatar->save(sys_get_temp_dir() . '/tmp.png');

				$user->photo = file_get_contents(sys_get_temp_dir() . '/tmp.png');
				unlink(sys_get_temp_dir() . '/tmp.png');
			}

			$user->companyEmail = Request::get('companyEmail');

			if (Request::get('newHashedPassword') != '') {
				$user->password = Hash::make(Request::get('newHashedPassword'));
			}
			
			$user->save();
		}

		Session::flash('flash_msg', 'Profile saved succesfully');
		return redirect('/profile')
			->with('flash_msg', 'Profile saved succesfully');
	}

}