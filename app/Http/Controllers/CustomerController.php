<?php 

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

use App\Models\Customer;
use App\Models\Quote;
use App\Models\Settings;
use App\Models\Product;
use App\Models\Renewal;
use App\Models\CustomerFile;
use App\Models\quotes\QuoteDetail;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\App;
use App\Classes\LogManager;
use App\Classes\CommonFunctions;

class CustomerController extends Controller {
	protected $layout = 'master';

	public function index() {
		if (!Auth::user()->hasPermission('customer_view'))
			return redirect('/');
		
		$azAll = range('A', 'Z');

		// $this->layout->nav_main = 'Customers';
		// $this->layout->nav_sub = 'List Customers';

		// $this->layout->content = view('customer.index')
		// 	->with('azAll', $azAll);

		
			return view('customer.index', 
			[
				'azAll' => $azAll,
				'nav_main' => 'Customers',
				'nav_sub' => 'List Customers'
			]);	
	}

	public function show($customerId) {
		if (!Auth::user()->hasPermission('customer_view'))
			return redirect('/');

		$customer = Customer::with(
			'getContactHistory',
			'getContactHistory.getPlacedBy',

			'getQuotes',
			'getQuotes.getEntries',
			'getQuotes.getStatus',
			'getQuotes.getComments',

			'getInvoices',
			'getInvoices.getEntries',

			'getPayments',
			'getPayments.getPayMethod',

			'getRenewals',
			'getRenewals.getProduct'
		)->find($customerId);

		// $this->layout->nav_main = 'Customers';
		// $this->layout->nav_sub = 'List Customers';
		// $this->layout->content = view('customer.show', compact('customer'));

		return view('customer.show', [
			'nav_main' => 'Customers',
			'nav_sub' => 'List Customers',
			'customer' => $customer
		]);
	}

	public function search(Request $request) {
		$layout = '';

		$searchType = Request::get('type');

		switch($searchType) {
			case 'query':
				$query = Request::get('searchQuery');
				$customers = Customer::where('companyName', 'LIKE', '%' . $query . '%')
					->orWhere('customerCode', 'LIKE', '%' . $query . '%')
					->orWhere('contactName', 'LIKE', '%' . $query . '%')
					->orWhere('phone', 'LIKE', '%' . $query . '%')
					->orWhere('mobile', 'LIKE', '%' . $query . '%')
					->orWhere('email', 'LIKE', '%' . $query . '%')
					->orWhere('fax', 'LIKE', '%' . $query . '%')
					->orWhere('accountHolder', 'LIKE', '%' . $query . '%')
					->orWhere('city', 'LIKE', '%' . $query . '%')
					->orderBy('companyName', 'ASC')
					->get();
			break;

			case 'az':
				$letter = Request::get('letter');

				if ($letter == 'all') {
					$customers = Customer::orderBy('companyName', 'ASC')->get();
				} else {
					$customers = Customer::where('companyName', 'LIKE', $letter . '%')
						->orWhere('contactName', 'LIKE', $letter . '%')
						->orderBy('companyName', 'ASC')
						->get();
				}
			break;
		}
		if (empty($customers)) return '';
		return view('customer.result')
			->with('customers', $customers);
	}

	public function edit($id) {
		App::abort(404);
		
		if (!Auth::user()->hasPermission('customer_edit'))
			return redirect('/');
		
		$customer = Customer::find($id);

		if (!$customer)
			App::abort(404);

		// $this->layout->nav_main = 'Customers';
		// $this->layout->nav_sub = 'List Customers';

		// $this->layout->content = view('customer.edit')
		// 	->with('customer', $customer);

		
		return view('customer.edit', 
		[
			'customer' => $customer,
			'nav_main' => 'Customers',
			'nav_sub' => 'List Customers'
		]);
	}

	public function create() {
		if (!Auth::user()->hasPermission('customer_create'))
			return redirect('/');
		
		// $this->layout->nav_main = 'Customers';
		// $this->layout->nav_sub = 'New Customer';

		// $this->layout->content = view('customer.create');

		
		return view('customer.create', 
		[
			'nav_main' => 'Customer',
			'nav_sub' => 'New Customer'
		]);
	}

	public function store(Request $request) {
		if (!Auth::user()->hasPermission('customer_create'))
			return redirect('/');
		
		$customer = Customer::create($request::all());
		$customer->createdBy = Auth::user()->id;

		// If the newsletter is set to false, set the newsletter unsubscribe date to now
		if (Request::get('newsletter') == 0)
			$customer->newsletter_unsubscribe = date('Y-m-d');

		$customer->save();

		LogManager::log('Created customer: ' . $customer->getCustomerName());

		return redirect('customers/' . $customer->id)
			->with('flash_success', 'Customer created succesfully');
	}

	public function update($id, Request $request) {
		if (!Auth::user()->hasPermission('customer_edit'))
			return redirect('/');
		
		$customer = Customer::find($id);

		// If we've switched the newsletter setting...
		if (Request::get('newsletter') == 0 && $customer->newsletter == 1)
			$customer->newsletter_unsubscribe = date('Y-m-d');
		else if (Request::get('newsletter') == 1 && $customer->newsletter == 0)
			$customer->newsletter_unsubscribe = null;

		$customer->fill($request::except('iban'));

		$customer->save();

		LogManager::log('Updated customer: ' . $customer->getCustomerName());

		return redirect('customers/' . $customer->id)
			->with('flash_msg', 'Customer update succesfully');
	}

	public function createRenewalForm($customerId) {
		$customer = Customer::find($customerId);

		if ($customer) {
			// $this->layout->nav_main = 'Customers';
			// $this->layout->nav_sub = '';
			// $this->layout->content = view('customer.createRenewal', compact('customer'));

			return view('customer.createRenewal', 
				[
					'nav_main' => 'Customers',
					'nav_sub' => '',
					'customer' => $customer
				]);
		}  else {
			return redirect('/');
		}
	}

	public function createRenewalSave($customerId) {
		// Create the renewal, and if the 'create quote' checkbox is ticked, generate a quote
		$renewal = new Renewal;
		$renewal->customer = $customerId;
		$renewal->product = Request::get('product');
		$renewal->startDate = date('Y-m-d', strtotime(Request::get('startDate')));
		$renewal->renewalCount = Request::get('renewalCount');
		$renewal->discount = Request::get('discount');
		$renewal->nextRenewalDate = CommonFunctions::dateTimePickerToMysqlDateTime(CommonFunctions::addMonthsToDate($renewal->startDate, (($renewal->renewalCount + 1) * (int) Request::get('renewalFreq'))));
		$renewal->renewalFreq = Request::get('renewalFreq');
		$renewal->notes = Request::get('notes');
		$renewal->cancelled = 0;
		$renewal->save();

		$product = Product::find(Request::get('product'));

		$customer = Customer::find($customerId);

		if (Request::get('createQuote') == 1) {
			// Create a new quote for the first period
			$quote = new Quote;
			$quote->createdOn = CommonFunctions::getMysqlDate();
			$quote->createdBy = Auth::user()->id;
			$quote->assignedTo = Auth::user()->id;
			$quote->adType = ($customer->getQuoteCount() > 0) ? Settings::setting('defaultAdType') : 0;
			$quote->customer = $customerId;
			$quote->description = $product->name;
			$quote->status = Settings::setting('defaultJobStatus');
			$quote->vat = Settings::setting('defaultVat');
			$quote->supCosts = 0;
			$quote->save();

			$quoteDetail = new QuoteDetail;
			$quoteDetail->quoteId = $quote->id;
			$quoteDetail->productId = $renewal->product;
			$quoteDetail->productName = $product->name;
			$quoteDetail->description = 'Period (' . date('d-m-Y', strtotime($renewal->startDate)) . ' - ' . date('d-m-Y', strtotime($renewal->nextRenewalDate)) . ')';
			$quoteDetail->purchasePrice = $product->purchasePrice;
			$quoteDetail->unitPrice = $product->salesPrice;
			$quoteDetail->discount = $renewal->discount;
			$quoteDetail->quantity = 1;
			$quoteDetail->save();

			return redirect('/quotes/' . $quote->id . '/edit')
				->with('flash_msg', 'Renewal & quote created succesfully');
		} else {
			return redirect('customers/' . $customerId)
				->with('flash_msg', 'Renewal created succesfully');
		}
	}

	public function uploadFile($customerId) {
		$customer = Customer::find($customerId);

		if (!$customer)
			return redirect('customers')
				->with('flash_err', 'Customer not found');

		if (!Request::hasFile('file') || !Request::file('file')->isValid())
			return redirect('customers')
				->with('flash_err', 'Something went wrong!');

		if (Request::file('file')->getSize() > 10000000)
			return redirect('customers')
				->with('flash_err_popup', 'That file is too big. The maximum size for files is 10MB');

		// Create the client file folder if it doesn't exist yet
		if (!File::exists(storage_path() . '/files/' . Settings::setting('installationId') . '/customer_files/' . $customerId)) {
			@mkdir(storage_path() . '/files/' . Settings::setting('installationId') . '/customer_files');
			mkdir(storage_path() . '/files/' . Settings::setting('installationId') . '/customer_files/' . $customerId);
		}

		// Create the customer file
		$customerFile = new CustomerFile;
		$customerFile->customer = $customerId;
		$customerFile->filename = Request::file('file')->getClientOriginalName();
		$customerFile->description = Request::get('description');
		$customerFile->size = Request::file('file')->getSize();
		$customerFile->filetype = Request::file('file')->getMimeType();

		// Create a hash for the file
		while (true) {
			$hash = CommonFunctions::generateRandomString(16);

			$hashCheck = CustomerFile::where('customer', '=', $customerId)->where('hash', '=', $hash)->count();

			if ($hashCheck == 0)
				break;
		}

		$customerFile->hash = $hash;
		$customerFile->name = pathinfo($customerFile->filename, PATHINFO_FILENAME);
		$customerFile->addedBy = Auth::id();
		$customerFile->addedOn = CommonFunctions::getMysqlDate();
		$customerFile->save();

		// Move the file
		Request::file('file')->move(storage_path() . '/files/' . Settings::setting('installationId') . '/customer_files/' . $customerId, $hash . '.' . Request::file('file')->getClientOriginalExtension());
		
		// Return back to the customer
		return redirect('customers/' . $customerId);
	}

	public function downloadFile($customerId, $fileHash) {
		$customer = Customer::find($customerId);

		if (!$customer)
			return redirect('customers')
				->with('flash_err', 'Customer not found');

		$customerFile = CustomerFile::where('customer', '=', $customerId)->where('hash', '=', $fileHash)->first();

		if (!$customerFile)
			return redirect('customers')
				->with('flash_err', 'Sorry, file not found');

		header('Content-Type: ' . $customerFile->filetype);
		header("Content-Transfer-Encoding: Binary"); 
		header("Content-disposition: attachment; filename=\"" . $customerFile->filename . "\"");
		readfile(storage_path() . '/files/' . Settings::setting('installationId') . '/customer_files/' . $customerId . '/' . $customerFile->hash . '.' . pathinfo($customerFile->filename, PATHINFO_EXTENSION));
	}
}