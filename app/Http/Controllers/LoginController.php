<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use App\Models\SsoTicket;
use App\Models\Settings;

// use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use App\Classes\LogManager;

class LoginController extends BaseController {

	public $restful = true;

	public function getIndex() {
	  $te = 90;
		?>
		<script type='text/javascript'>
			var te = "<?php print($te); ?>"
			// alert(te)
		</script>
<?php
		if (Auth::check()) {
			return redirect('dashboard');
		}

		$logo = Settings::setting('logo');
		// $logo = '';
		return view("login")
			->with('logo', $logo);
	}

	// Attempt the login. If succesful, redirect to the dashboard. If not, show the error message
	// Also remember the user if he has the 'remember me' checkbox
	public function postIndex(Request $request) {

		$credentials = $request->all();
		$loginAttempt = Auth::attempt(array('username' => $credentials['username'], 'password' => $credentials['password']), $request->get('rememberme'));
		
		if ($loginAttempt) {
			// Check if the user is not allowed to log in
			// if (!Auth::user()->hasPermission('user_login') || Auth::user()->disabled == 1) {
			// 	Auth::logout();
			// 	return array('valid' => false, 'message' => 'Your account has been disabled');
			// }
			
			LogManager::log('User logged in.');

			// if ($request->session()->has('url.intended'))
			// 	if (strpos($request->session()->get('url.intended'), 'log_ajax_error') !== false)
			// 		return redirect('dashboard');

			return array('valid' => true, 'url' => Redirect::intended('dashboard')->getTargetUrl());
		} else {
			return array('valid' => false, 'message' => 'Invalid username or password');
		}

		// return array('valid' => true, 'url' => redirect()->intended('dashboard')->getTargetUrl());
	}

	// SSO ticket login
	public function ssoLogin($hash) {
		$ticket = SsoTicket::where('hash', '=', $hash)->first();
		$ticket->delete();
		SsoTicket::truncate();

		if (!$ticket)
			return redirect('login');

		if ($ticket->ip != Request::ip())
			return redirect('login');

		if ($ticket->validTill < date('Y-m-d H:i:s'))
			return redirect('login');

		$user = User::find($ticket->userId);
		Auth::logout();
		Auth::login($user);

		return redirect('dashboard');
	}

	// Logout the user
	public function logout() {
		Auth::logout();
		return redirect('login');
	}
}

?>