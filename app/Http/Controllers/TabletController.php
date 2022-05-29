<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Signature;
use App\Models\variables\CustomerCreditRating;

use App\Classes\CommonFunctions;

class TabletController extends BaseController {

	protected $layout = null;

	public function index() {
		return View::make('tablet.index');
	}

	public function createCustomer() {
		return View::make('tablet.createcustomer');
	}

	public function saveNewCustomer() {
		$customer = new Customer;

		$customer->contactName = Request::get('details')['name'];
		$customer->contactTitle = Request::get('details')['title'];

		$customer->companyName = Request::get('details')['companyName'];
		$customer->address = Request::get('details')['address'];
		$customer->city = Request::get('details')['city'];
		$customer->region = Request::get('details')['region'];
		$customer->postalCode = Request::get('details')['postcode'];

		$customer->email = Request::get('details')['email'];

		$customer->phone = Request::get('details')['phone'];
		$customer->mobile = Request::get('details')['mobile'];

		$customer->cifnif = Request::get('details')['cifnif'];

		$customer->credit = CustomerCreditRating::first()->getKey();
		$customer->locationLat = 36.5445126;
		$customer->locationLng = -4.6247328;
		$customer->createdBy = Auth::id();

		$customer->newsletter = Request::get('newsletter');

		//$customer->save();

		// Save signature
		$signature = new Signature;
		//$signature->customerId = $customer->id;
		$signature->signature = urldecode(Request::get('signature'));
		$signature->setOn = CommonFunctions::getMysqlDate();
		$signature->save();

		return array('success' => true);

	}

	public function success() {
		return View::make('tablet.success');
	}

}