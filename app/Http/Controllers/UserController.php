<?php


namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Exception;

use App\Classes\SimpleImage;
use App\Classes\LogManager;

class UserController extends BaseController {
	
	protected $layout = 'master';

	// The Manage users page
	public function index(Request $request) {
		// $this->layout->nav_main = 'System';
		// $this->layout->nav_sub = 'Manage users';

		$users = [];
		if (Request::has('disabled')) 
			$users = User::where('disabled', '=', 1)->orderBy('firstName')->get();
		else
			$users = User::where('disabled', '=', 0)->orderBy('firstName')->get();

		// $this->layout->content = view('user.index')
		// 						->with('users', $users);

	       return view('user.index', 
			[
				'users' => $users,
				'nav_main' => 'System',
				'nav_sub' => 'Manage users'
			]);
	}

	// Edit a user
	public function edit($id) {
		$user = User::find($id);

		// $this->layout->nav_main = 'System';
		// $this->layout->nav_sub = 'Manage users';

		// $this->layout->content = view('user.edit')
		// 						->with('user', $user);
		return view('user.edit', 
			[
				'user' => $user,
				'nav_main' => 'System',
				'nav_sub' => 'Manage users'
			]);
	}

	// Save the edited user
	public function update($id) {
		$user = User::find($id);

		$user->fill(Request::except('newpassword', 'newpassword_confirm', 'hashedPassword', 'hireDate', 'dob', 'myJobStatusses', 'photo'));

		if (Request::has('hireDate'))
			$user->hireDate = date('Y-m-d', strtotime(Request::get('hireDate')));

		if (Request::has('dob'))
			$user->dob = date('Y-m-d', strtotime(Request::get('dob')));

		if (Request::hasFile('photo')) {
			$image = new SimpleImage(Request::file('photo')->getRealPath());
			$image->best_fit(140, 140)->save(sys_get_temp_dir() . '/tmp.png');
			$imageData = file_get_contents(sys_get_temp_dir() . '/tmp.png');
			$user->photo = $imageData;
			unlink(sys_get_temp_dir() . '/tmp.png');
		}

		if (Request::get('hashedPassword') != '') {
			$user->password = Hash::make(Request::get('hashedPassword'));
		}

		$myJobStatusses = [];
		if (Request::has('myJobStatusses')) {
			foreach(Request::get('myJobStatusses') as $status => $value) {
				array_push($myJobStatusses, $status);
			}
			$user->myJobStatusses = implode(',', $myJobStatusses);
		} else {
			$user->myJobStatusses = '';
		}

		$user->save();
		
		// Log the changes...
		LogManager::log('Updated user ' . $user->getFullname());

		Session::flash('flash_msg', 'Succesfully updated user.');
		return redirect('users');
	}

	// Show a user
	public function show($id) {
		$user = User::find($id);

		// $this->layout->nav_main = 'System';
		// $this->layout->nav_sub = 'Manage users';

		// $this->layout->content = view('user.show')
		// 	->with('user', $user);
		return view('user.show', 
		[
			'user' => $user,
			'nav_main' => 'System',
			'nav_sub' => 'Manage users'
		]);
	}

	// Store a new user
	public function store(Request $request) {
		// Create the user
		$user = User::create(Request::except('password', 'password_confirm'));

		$user->password = hash('sha512', Hash::make(Request::get('password')));
		$user->hireDate = date('Y-m-d');
		$user->dob =  date('Y-m-d');
		$user->userGroup = 0;

		$user->save();

		// Log the change...
		LogManager::log('Created user ' . $user->getFullname());
		
		return redirect('users/' . $user->id)
			->with('flash_msg', 'Succesfully created user.');
	}

	// Show a user image
	public function getUserImage($userid) {
		$user = User::find($userid);

		// Check if the user has an image. If not, show the default one
		if ($user->photo == '') {
			$content = file_get_contents('img/user_no_img.png');
		} else {
			$content = $user->photo;
		}

		if (Request::has('thumb')) {
			$image = new SimpleImage();
			$image->load_base64(base64_encode($content));
			$image->thumbnail(150, 150);
			$image->output();
		}

		$response = Response::make($content);
		$response->header('content-type', 'Image/png');
		$response->header('cache-control', 'public, max-age=600');
		return $response;
	}

	private function imageToPng($srcFile, $maxSize = 100) {  
	    list($width_orig, $height_orig, $type) = getimagesize($srcFile);        

	    // Get the aspect ratio
	    $ratio_orig = $width_orig / $height_orig;

	    $width  = $maxSize; 
	    $height = $maxSize;

	    // resize to height (orig is portrait) 
	    if ($ratio_orig < 1) {
	        $width = $height * $ratio_orig;
	    } 
	    // resize to width (orig is landscape)
	    else {
	        $height = $width / $ratio_orig;
	    }

	    // Temporarily increase the memory limit to allow for larger images
	    ini_set('memory_limit', '2048M');

	    switch ($type) 
	    {
	        case IMAGETYPE_GIF: 
	            $image = imagecreatefromgif($srcFile); 
	            break;   
	        case IMAGETYPE_JPEG:  
	            $image = imagecreatefromjpeg($srcFile); 
	            break;   
	        case IMAGETYPE_PNG:  
	            $image = imagecreatefrompng($srcFile);
	            break; 
	        default:
	            throw new Exception('Unrecognized image type ' . $type);
	    }

	    // create a new blank image
	    $newImage = imagecreatetruecolor($width, $height);

	    // Copy the old image to the new image
	    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

	    // Output to a temp file
	    $destFile = tempnam('/tmp', 'userimg');
	    imagepng($newImage, $destFile);  

	    // Free memory                           
	    imagedestroy($newImage);

	    if ( is_file($destFile) ) {
	        $f = fopen($destFile, 'rb');   
	        $data = fread($f, filesize($destFile));       
	        fclose($f);

	        // Remove the tempfile
	        unlink($destFile);    
	        return $data;
	    }

	    throw new Exception('Image conversion failed.');
	}

	public function createFirstUser($username, $password, $firstName, $lastName, $email) {
		$userCount = User::count();

		if ($userCount == 0) {
			$user = new User;
			$user->username = $username;
			$user->password = Hash::make(hash('sha512', $password));
			$user->firstName = $firstName;
			$user->lastName = $lastName;
			$user->companyEmail = $email;

			$user->userGroup = 1;
			$user->companyRole = 1;
			
			$user->save();

			return array('success' => true);
		}

		return array('success' => true);
	}

	public function destroy($userId) {
		$user = User::find($userId);

		if ($user) {
			$user->disabled = 1;
			$user->save();
		}

		return redirect('users')
			->with('flash_msg', 'User succesfully deleted.');
	}
}

?>