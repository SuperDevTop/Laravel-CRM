<?php
/**
 * 
 * @author Bas
 *
 */
namespace App\Http\Controllers;
use App\Models\variables\VAT;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

use App\Classes\SimpleImage;

class SettingsController extends BaseController {
	protected $layout = 'master';

	public function index() {
		$vats = VAT::all();

		return view('system.configuration', 
			[
				'vats' => $vats,
				'nav_main' => 'System',
				'nav_sub' => 'System Settings'
			]);
	}

	public function save(Request $request) {
		$noMassAssign = array('logo', 'icon', 'awaitingPaymentJobStatusses', 'completedJobStatusses', 'smtpPassword');
		foreach(Request::all() as $input_name => $input_value) {
			if (in_array($input_name, $noMassAssign))
				continue;

			$this->updateSetting($input_name, $input_value);
		}

		// If there is a new logo uploaded, resize it and put it in the db
		if (Request::hasFile('logo') && Request::file('logo')->isValid()) {
			$image = new SimpleImage(Request::file('logo')->getRealPath());
			$hash = md5(time());
			$image->best_fit(400, 65)->save(sys_get_temp_dir() . '/' . $hash . '.png');
			$imageData = file_get_contents(sys_get_temp_dir() . '/' . $hash . '.png');
			$this->updateSetting('logo', $imageData);
			unlink(sys_get_temp_dir() . '/' . $hash . '.png');
		}

		// If there is a new icon uploaded, resize it and put it in the db
		if (Request::hasFile('icon') && Request::file('icon')->isValid()) {
			$image = new SimpleImage(Request::file('icon')->getRealPath());
			$hash = md5(time());
			$image->best_fit(192, 192)->save(sys_get_temp_dir() . '/' . $hash . '.png');
			$imageData = file_get_contents(sys_get_temp_dir() . '/' . $hash . '.png');
			$this->updateSetting('icon', $imageData);
			unlink(sys_get_temp_dir() . '/' . $hash . '.png');
		}

		if (!empty(Request::get('smtpPassword'))) {
			$this->updateSetting('smtpPassword', Request::get('smtpPassword'));
		}

		if (is_array(Request::get('awaitingPaymentJobStatusses'))) {
			$this->updateSetting('awaitingPaymentJobStatusses', implode(',', Request::get('awaitingPaymentJobStatusses')));
		} else {
			$this->updateSetting('awaitingPaymentJobStatusses', '');
		}

		if (is_array(Request::get('completedJobStatusses'))) {
			$this->updateSetting('completedJobStatusses', implode(',', Request::get('completedJobStatusses')));
		} else {
			$this->updateSetting('completedJobStatusses', '');
		}

		$this->updateSetting('quoteHasVisitDate', Request::has('quoteHasVisitDate') ? 1 : 0);
        // Angel -> now irpf is really send to table settings 
		$this->updateSetting('invoiceHasIrpf', Request::has('invoiceHasIrpf') ? 1 : 0);

		return redirect('/settings')
			->with('flash_msg', 'Settings saved succesfully');
	}

	public function updateSetting($setting, $value) {
		$setting = Settings::where('name', '=', $setting)->first();

		if ($setting) {
			$setting->value = $value;
			$setting->save();
		}
	}

	public function showLogo() {

		$this->layout = null;

		$logo = Settings::setting('logo');

		if ($logo) {
			$response = Response::make($logo, 200);
			$response->header('Content-Type', 'image/png');
			return $response;
		} else {
			$response = Response::make(file_get_contents(public_path() . '/img/default_logo.png'), 200);
			$response->header('Content-Type', 'image/png');
			return $response;
		}
	}

	public function showIcon() {
		$this->layout = null;

		$icon = Settings::setting('icon');

		if ($icon) {
			$response = Response::make($icon, 200);
			$response->header('Content-Type', 'image/png');
			return $response;
		} else {
			$response = Response::make(file_get_contents(public_path() . '/img/default_icon.png'), 200);
			$response->header('Content-Type', 'image/png');
			return $response;
		}
	}

	public function templateSettings() {

		return view('system.templates', 
			[
				'nav_main' => 'System',
				'nav_sub' => 'Template settings'
			]);
	}

	public function setupScript($username, $password, $firstname, $lastname) {
		$this->layout = null;

		// If there are already users, return (safety)
		if (User::count() != 0)
			return json_encode(arraY('success' => false));

		// Create the user
		$user = new User;
		$user->username = $username;
		$user->password = Hash::make(hash('sha512', $password));
		$user->firstname = $firstname;
		$user->lastname = $lastname;
		$user->userGroup = 1;
		$user->companyRole = 1;
		$user->save();

		return redirect('/');
	}
}