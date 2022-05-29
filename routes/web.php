<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Route;

use App\Models\Product;
use App\Models\Customer;
use App\Models\quotes\QuoteDetail;
use App\Models\Renewal;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*Route::get('monkeys', function() {
	$quotes = DB::select('SELECT * FROM quotes WHERE createdOn BETWEEN "2017-01-01 00:00:00" AND "2018-12-31 23:59:59" AND customer IN (SELECT id FROM customers WHERE readonly=0)');

	$customerIds = [];
	foreach($quotes as $quote) {
		if (!in_array($quote->customer, $customerIds))
			$customerIds[] = $quote->customer;
	}

	dd($customerIds);
});*/

Route::get('product-update', function() {
	return;
	set_time_limit(0);

	// Get all product names
	$productNames = [];
	foreach(Product::all() as $product) {
		$productNames[strval($product->id)] = $product->name;
	}

	// Get all products used for active renewals
	$renewalProducts = [];
	foreach(Product::whereIn('id', Renewal::where('cancelled', 0)->pluck('product'))->get() as $product) {
		$renewalProducts[$product->id] = $product->toArray();
	}

	// Delete all products
	Product::truncate();

	// Create new product
	Product::create([
		'category' => 1,
		'supplier' => 0,
		'name' => 'Old Product',
		'description' => '',
		'purchasePrice' => 0,
		'salesPrice' => 0,
		'discontinued' => 0,
		'isWork' => 0
	]);

	// Re-create renewal products
	foreach($renewalProducts as $product) {
		$dbProduct = new Product;
		$dbProduct->category = $product['category'];
		$dbProduct->supplier = $product['supplier'];
		$dbProduct->name = $product['name'];
		$dbProduct->description = $product['description'];
		$dbProduct->purchasePrice = $product['purchasePrice'];
		$dbProduct->salesPrice = $product['salesPrice'];
		$dbProduct->image = $product['image'];
		$dbProduct->discontinued = $product['discontinued'];
		$dbProduct->isWork = $product['isWork'];
		$dbProduct->save();

		DB::update("UPDATE renewals SET product=? WHERE product=?", [
			$dbProduct->id,
			$product['id']
		]);
	}

	// Update rows
	foreach($productNames as $productId => $productName) {
		DB::update("UPDATE quotedetails SET productId=1, productName='Old Product', description=CONCAT(?, ' | ', description) WHERE productId=?", [
			$productName,
			$productId
		]);
	}
});

Route::get('segmentation-update', function(Request $request) {
	return;
	if ($request->has('type')) {
		$customer = Customer::find($request->input('id'));
		$customer->type = $request->input('type');
		$customer->save();

		$nextCustomer = Customer::where('discontinued', 0)->where('readonly', 0)->where('email', '!=', '')->where('phone', '!=', '')->where('id', '>', $customer->id)->orderBy('id')->first();
	} else {
		$nextCustomer = Customer::where('discontinued', 0)->where('readonly', 0)->where('email', '!=', '')->where('phone', '!=', '')->orderBy('id')->first();
	}

	$layout = view('master');
	$layout->nav_main = '-1';
	$layout->nav_sub = '-1';
	$layout->content = view('segmentation')->with('customer', $nextCustomer);

	return $layout;
});

Route::get('products-to-single', function() {
	foreach(QuoteDetail::with('product')->get() as $entry) {
		echo $entry->id . '<br>';
	}
});

Route::get('importscr', [ImportController::class, 'import']);
Route::get('updatesrc', [ImportController::class, 'update']);

// Enable debug
Route::get('enable_debug/1337head', function() {
	session()->put('debug_enabled', true);
	return redirect(URL::previous());
});
 
// Enable debug
Route::get('disable_debug/1337head', function(Request $request) {
	$request->session()->forget('debug_enabled');
	return redirect(URL::previous());
});

// The setup script route. Used by the registration form to create the user
Route::get('setup_script/1337head/{username}/{password}/{firstName}/{lastName}/{email}', [UserController::class, 'createFirstUser']);

// The bootstrap route. Is bound to /
Route::get('/', [HomeController::class, 'openWebsite']);

// The login route

//** */
// Route::controller('login', [LoginController::class, array('before' => 'guest')]);
//** */

Route::get('login', [LoginController::class, 'getIndex']);
Route::post('login', [LoginController::class, 'postIndex']);
///

///
Route::get('sso_login/{hash}', [LoginController::class, 'ssoLogin']);

// The system logo route :)
Route::get('logo.png', [SettingsController::class, 'showLogo']);
Route::get('icon.png', [SettingsController::class, 'showIcon']);

Route::group(array('before' => 'auth'), function() {
	//Route::controller('ajax', 'AjaxController');
	// Route::post('ajax/{type}', 'AjaxController::classajaxRequest');

	// The quote controller
	Route::get('quotes', [QuoteController::class, 'index']);
	Route::get('quotes/create/{customerId}', [QuoteController::class, 'create']);
	Route::get('quotes/{quoteId}/edit', [QuoteController::class, 'edit']);

	Route::get('quotes/{quoteId}/edit/description-sheet', [QuoteController::class, 'descriptionSheet']);

	// My jobs
	Route::get('myjobs', [QuoteController::class, 'listMyJobs']);

	// The logout route
	Route::get('logout', [LoginController::class, 'logout']);

	// The navigation route
	Route::get('navigation', [NavigationController::class, 'showNavigation']);

	// The quick sell route
	Route::get('quick-sell', [QuickSellController::class, 'index']);

	// // The suppliers resource
	Route::resource('suppliers', SupplierController::class);

	// // The users resource
	Route::resource('users', UserController::class);

	Route::post('users/{userid}/update_user_permissions', [UserController::class, 'updateUserPermissions']);
	Route::get('users/{userid}/delete', [UserController::class, 'destroy']);

	// The System configuration controller
	Route::get('settings', [SettingsController::class, 'index']);
	Route::post('settings', [SettingsController::class, 'save']);

	// // The user groups route
	Route::get('user-groups', [UserGroupController::class, 'index']);

	// // The variables resource
	Route::resource('variables', VariableController::class);
	Route::get('variables/edit/{type}/{id}', [VariableController::class, 'edit']);
	Route::post('variables/delete', [VariableController::class, 'delete']);

	// // The products resource
	Route::resource('products', ProductController::class);
	Route::post('products/search', [ProductController::class, 'search']);

	// // // The customers resource
	Route::resource('customers', CustomerController::class);
	Route::post('customers/search', [CustomerController::class, 'search']);
	Route::get('customers/{customerId}/createRenewal', [CustomerController::class, 'createRenewalForm']);
	Route::post('customers/{customerId}/createRenewal', [CustomerController::class, 'createRenewalSave']);

	// Customer file upload route
	Route::post('customerfiles-upload/{customerId}', [CustomerController::class, 'uploadFile']);
	Route::get('customerfiles-download/{customerId}/{fileHash}', [CustomerController::class, 'downloadFile']);


	// Route::get('dashboard', array('as' => 'dashboard', 'uses' =>
    //           [DashboardController::class, 'index']));

	Route::get('dashboard', [DashboardController::class, 'index']);


	Route::get('users/{id}/photo', [UserController::class, 'getUserImage']);

	Route::controller('statistics', [StatisticsController::class]);

	Route::get('invoices', [InvoiceController::class, 'index']);
	Route::get('invoices/{invoiceId}/edit', [InvoiceController::class, 'show']);
	Route::get('invoices/create/{customerId}', [InvoiceController::class, 'create']);

	Route::get('creditnotes', [CreditnoteController::class, 'index']);
	Route::get('creditnotes/{creditnoteId}/edit', [CreditnoteController::class, 'show']);
	Route::get('creditnotes/create/{customerId}', [CreditnoteController::class, 'create']);

	route::get('renewals', [RenewalController::class, 'index']);
	Route::get('renewals/{renewalId}/quote', [RenewalController::class, 'quote']);
	Route::get('renewals/{renewalId}/cancel', [RenewalController::class, 'cancel']);
	Route::get('renewals/{renewalId}/delete', [RenewalController::class, 'delete']);

	Route::get('profile', [ProfileController::class, 'index']);
	Route::post('profile', [ProfileController::class, 'save']);

	Route::get('ddNewJob', [DirectDebitController::class, 'newJob']);
	Route::get('ddGetSepaXml/{jobId}', [DirectDebitController::class, 'getSepaXML']);
	Route::get('ddIndex', [DirectDebitController::class ,'index']);

	Route::get('reports/overview', [ReportController::class, 'overview']);
	Route::get('reports/charts', [ReportController::class, 'indexCharts']);
	Route::get('reports/lists', [ReportController::class, 'indexLists']);
	Route::get('openReport', [ReportController::class, 'openReport']);

	Route::get('payments', [PaymentController::class, 'index']);
	Route::get('payments/create/{customerId?}', [PaymentController::class, 'create']);

	Route::get('expenses', [ExpenseController::class, 'index']);
	Route::get('expenses/create', [ExpenseController::class, 'create']);
	Route::get('expenses/categories', [ExpenseController::class, 'manageCategories']);
	Route::get('expenses/{id}', [ExpenseController::class, 'edit']);

	Route::post('chart_api', [ReportController::class, 'getData']);

	// The media route
	Route::get('media/{filename}', [MediaController::class, 'getMedia']);

	// // The quote & invoice email attachment url (allows the user to view attachments sent my email later)
	Route::get('quoteemailattachment/{emailId}', [QuoteController::class, 'downloadEmailAttachment']);
	Route::get('quoteemailadditionalattachment/{emailId}', [QuoteController::class, 'downloadAdditionalEmailAttachment']);
	Route::get('invoiceemailattachment/{emailId}', [InvoiceController::class, 'downloadEmailAttachment']);
	Route::get('invoiceemailadditionalattachment/{emailId}', [InvoiceController::class, 'downloadAdditionalEmailAttachment']);
});

// The media route for the generation of pdfs (no auth required)
Route::get('extmedia/{filename}/1337head', [MediaController::class, 'getMedia']);

// Tablet routes (tmp?)
Route::get('tablet', [TabletController::class, 'index']);
Route::get('tablet/create_customer', [TabletController::class, 'createCustomer']);
Route::post('tablet/create_customer', [TabletController::class, 'saveNewCustomer']);
Route::get('tablet/success', [TabletController::class, 'success']);

// Generate quote in PDF
Route::get('quotetemplate/{quoteId}/1337head', [QuoteController::class, 'createQuoteTemplate']);
Route::get('receipttemplate/{quoteId}/1337head', [QuoteController::class, 'createReceiptTemplate']);

Route::get('quotedescriptionsheet/{quoteId}/1337head', [QuoteController::class, 'printDescriptionSheet']);

Route::get('quotepdf/{quoteId}/{type}', [QuoteController::class, 'generatePdf']);

// Generate invoice in PDF
Route::get('invoicetemplate/{invoiceId}/1337head', [InvoiceController::class, 'createInvoiceTemplate']);
Route::get('invoicepdf/{invoiceId}/{type}', [InvoiceController::class, 'generatePdf']);

Route::get('creditnotetemplate/{creditnoteId}/1337head', [CreditnoteController::class, 'createCreditnoteTemplate']);
Route::get('creditnotepdf/{creditnoteId}/{type}', [CreditnoteController::class, 'generatePdf']);
