<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

use App\Classes\LogManager;

use App\Models\variables\AdType;
use App\Models\variables\ProductCategory;
use App\Models\variables\CustomerCreditRating;
use App\Models\variables\JobStatus;
use App\Models\variables\PayMethod;
use App\Models\variables\CallOutFee;
use App\Models\variables\VAT;
use App\Models\variables\CompanyRole;
use App\Models\variables\PaymentTerm;
use App\Models\variables\CustomerType;
use App\Models\variables\Sector;
use App\Models\ExpensePayment;
use App\Models\Payment;
use App\Models\invoices\Invoice;

use app\Models\Customer;
use app\Models\Quote;

class VariableController extends BaseController {
	protected $layout = 'master';

	public function index() {
		// Fix: Delete variables where type==''
		AdType::where('type', '=', '')->delete();
		ProductCategory::where('type', '=', '')->delete();
		CustomerCreditRating::where('type', '=', '')->delete();
		JobStatus::where('type', '=', '')->delete();
		PayMethod::where('type', '=', '')->delete();
		CallOutFee::where('type', '=', '')->delete();
		VAT::where('type', '=', '')->delete();
		CompanyRole::where('type', '=', '')->delete();

		// Get all the variable types
		$adTypes = AdType::all();
		$productCategories = ProductCategory::all();
		$customerCreditRatings = CustomerCreditRating::all();
		$jobStatuses = JobStatus::all();
		$payMethods = PayMethod::all();
		$callOutFees = CallOutFee::all();
		$vats = VAT::all();
		$companyRoles = CompanyRole::all();

		// $this->layout->nav_main = 'System';
		// $this->layout->nav_sub = 'Manage System Variables';

		// $this->layout->content = View::make('variable.index')
		// 	->with('adTypes', $adTypes)
		// 	->with('productCategories', $productCategories)
		// 	->with('customerCreditRatings', $customerCreditRatings)
		// 	->with('jobStatuses', $jobStatuses)
		// 	->with('payMethods', $payMethods)
		// 	->with('callOutFees', $callOutFees)
		// 	->with('vats', $vats)
		// 	->with('companyRoles', $companyRoles);

		return view('variable.index', 
			[
				'adTypes' => $adTypes,
				'productCategories' => $productCategories,
				'customerCreditRatings' => $customerCreditRatings,
				'jobStatuses' => $jobStatuses,
				'payMethods' => $payMethods,
				'callOutFees' => $callOutFees,
				'vats' => $vats,
				'companyRole' => $companyRoles,
				'nav_main' => 'System',
				'nav_sub' => 'Manage System Variables'
			]);
	}

	public function edit($type, $id) {
		// $this->layout->nav_main = 'System';
		// $this->layout->nav_sub = 'Manage System Variables';

		$typeTitle = '';
		$tableName = '';
		$additionalFields = array();
		$discontinuedField = false;
		$startId = $id;

		switch($type) {
			case 'AdType' :
				if ($id == -1) $id = AdType::create(array())->id;
				$typeTitle = 'Advertisement type';
				$tableName = 'adtypes';
				$variable = AdType::find($id);
				$discontinuedField = true;
			break;

			case 'ProductCategory' :
				if ($id == -1) $id = ProductCategory::create(array())->id;
				$typeTitle = 'Product category';
				$tableName = 'productcategories';
				$variable = ProductCategory::find($id);
				$discontinuedField = true;
			break;

			case 'CustomerCreditRating' :
				if ($id == -1) $id = CustomerCreditRating::create(array())->id;
				$typeTitle = 'Customer cedit rating';
				$tableName = 'credit';
				$variable = CustomerCreditRating::find($id);
				$additionalFields = array(array('label' => 'Abbreviation', 'name' => 'abbreviation'));
				$discontinuedField = true;
			break;

			case 'JobStatus' :
				if ($id == -1) $id = JobStatus::create(array())->id;
				$typeTitle = 'Job status';
				$tableName = 'jobstatus';
				$variable = JobStatus::find($id);
			break;

			case 'PayMethod' :
				if ($id == -1) $id = PayMethod::create(array())->id;
				$typeTitle = 'Payment Method';
				$tableName = 'paymethod';
				$variable = PayMethod::find($id);
				$additionalFields = array(array('label' => 'Commission', 'name' => 'commission'));
			break;
			case 'CallOutFee' :
				if ($id == -1) $id = CallOutFee::create(array())->id;
				$typeTitle = 'Call out fee';
				$tableName = 'calloutfees';
				$variable = CallOutFee::find($id);
				$additionalFields = array(array('label' => 'Price', 'name' => 'price'));
			break;
			case 'VAT' :
				if ($id == -1) $id = VAT::create(array())->id;
				$typeTitle = 'VAT';
				$tableName = 'vat';
				$variable = VAT::find($id);
				$additionalFields = array(array('label' => 'Value', 'name' => 'value'));
			break;
			case 'CompanyRole' :
				if ($id == -1) $id = CompanyRole::create(array())->id;
				$typeTitle = 'Company role';
				$tableName = 'companyroles';
				$variable = CompanyRole::find($id);
				$additionalFields = array();
			break;
		}

		// $this->layout->content = View::make('variable.edit')
		// 	->with('id', $startId)
		// 	->with('typeTitle', $typeTitle)
		// 	->with('tableName' , $tableName)
		// 	->with('variable', $variable)
		// 	->with('additionalFields', $additionalFields)
		// 	->with('discontinuedField', $discontinuedField);
		return view('variable.edit', 
			[
				'id' => $startId,
				'typeTitle' => $typeTitle,
				'tableName' => $tableName,
				'variable' => $variable,
				'addtionalFields' => $additionalFields,
				'discontinuedField' => $discontinuedField,
				'nav_main' => 'System',
				'nav_sub' => 'Manage System Variables'
			]);
	}

	public function update() {
		$type = Request::get('varType');

		switch($type) {
			case 'adtypes':
				$adType = AdType::find(Request::get('id'));
				$adType->type = Request::get('type');
				$adType->discontinued = Request::get('discontinued');
				$adType->save();
			break;

			case 'productcategories':
				$category = ProductCategory::find(Request::get('id'));
				$category->type = Request::get('type');
				$category->discontinued = Request::get('discontinued');
				$category->save();
			break;

			case 'credit':
				$credit = CustomerCreditRating::find(Request::get('id'));
				$credit->type = Request::get('type');
				$credit->abbreviation = Request::get('abbreviation');
				$credit->discontinued = Request::get('discontinued');
				$credit->save();
			break;

			case 'jobstatus':
				$jobstatus = JobStatus::find(Request::get('id'));
				$jobstatus->type = Request::get('type');
				$jobstatus->save();
			break;

			case 'paymethod':
				$paymethod = PayMethod::find(Request::get('id'));
				$paymethod->type = Request::get('type');
				$paymethod->commission = Request::get('commission');
				$paymethod->save();
			break;

			case 'calloutfees':
				$calloutfees = CallOutFee::find(Request::get('id'));
				$calloutfees->type = Request::get('type');
				$calloutfees->price = Request::get('price');
				$calloutfees->save();
			break;

			case 'vat':
				$vat = VAT::find(Request::get('id'));
				$vat->type = Request::get('type');
				$vat->value = Request::get('value');
				$vat->save();
			break;

			case 'companyroles':
				$companyrole = CompanyRole::find(Request::get('id'));
				$companyrole->type = Request::get('type');
				$companyrole->save();
			break;
		}

		// Log the change...
		LogManager::log('Updated "' . $type . '": ' . Request::get('type'));
		
		return redirect('variables')
			->with('flash_msg', 'Variable updated succesfully');
	}

	public function delete() {
		$type = Request::get('type');
		$id = Request::get('id');
		$data = array();

		switch($type) {
			case 'AdType':
			case 'Advertisement Types':
				if (Customer::where('advertisingType', '=', $id)->count() > 0) {
					$data['success'] = false;
					$data['msg'] = 'Could not delete advertisement type. There are still customers in the system with this advertisement type!';
				} else {
					AdType::find($id)->delete();
					$data['success'] = true;
				}
			break;
			case 'ProductCategory':
			case 'Product Categories':
				if (ProductCategory::find($id)->getProducts()->count() > 0) {
					$data['success'] = false;
					$data['msg'] = 'Could not delete product category. There are still products in the system with this category!';
				} else {
					ProductCategory::find($id)->delete();
					$data['success'] = true;
				}
			break;
			case 'CustomerCreditRating':
			case 'Customer Credit Ratings':
				if (CustomerCreditRating::find($id)->getCustomers()->count() > 0) {
					$data['success'] = false;
					$data['msg'] = 'Could not delete credit rating. There are still customers in the system with this rating!';
				} else {
					CustomerCreditRating::find($id)->delete();
					$data['success'] = true;
				}
			break;
			case 'JobStatus':
			case 'Job Statusses':
				if (Quote::where('status', '=', $id)->count() > 0) {
					$data['success'] = false;
					$data['msg'] = 'Could not delete job status. There are still quotes in the system that have this status!';
				} else {
					JobStatus::find($id)->delete();
					$data['success'] = true;
				}
			break;
			case 'PayMethod':
			case 'Payment Methods':
				if (Payment::where('paymentType', '=', $id)->count() > 0 || ExpensePayment::where('paymentMethod', '=', $id)->count() > 0) {
					$data['success'] = false;
					$data['msg'] = 'Could not delete payment method. There are still payments in the system that have this method!';
				} else {
					PayMethod::find($id)->delete();
					$data['success'] = true;
				}
			break;
			case 'CallOutFee':
			case 'Call Out Fees':
				if (Customer::where('assignedVisitFee', '=', $id)->count() > 0) {
					$data['success'] = false;
					$data['msg'] = 'Could not delete call out fee. There are still customers in the system that have this fee!';
				} else {
					CallOutFee::find($id)->delete();
					$data['success'] = true;
				}
			break;
			case 'VAT':
			case 'VAT values':
				if (Quote::where('vat', '=', $id)->count() > 0 || Invoice::where('vat', '=', $id)->count() > 0) {
					$data['success'] = false;
					$data['msg'] = 'Could not delete VAT value. There are still quotes/invoices in the system that have this value!';
				} else {
					VAT::find($id)->delete();
					$data['success'] = true;
				}
			break;
			case 'CompanyRole':
			case 'Company Roles':
				if (CompanyRole::find($id)->getUsers()->count() > 0) {
					$data['success'] = false;
					$data['msg'] = 'Could not delete company role. There are still users in the system with this company role!';
				} else {
					CompanyRole::find($id)->delete();
					$data['success'] = true;
				}
			break;
			case 'Payment Terms':
				$term = PaymentTerm::find($id);
				if (Customer::where('paymentTerms', '=', $term->getKey())->count() > 0) {
					$data['success'] = false;
					$data['msg'] = 'Could not delete payment term. There are still customers in the system with this payment terms!';
				} else {
					$term->delete();
					$data['success'] = true;
				}
			break;
			case 'Customer Types':
					$type = CustomerType::find($id);
					if (Customer::where('type', '=', $type->getKey())->count() > 0) {
							$data['success'] = false;
							$data['msg'] = 'Could not delete customer type. There are still customers in the system with this customer type!';
					} else {
							$type->delete();
							$data['success'] = true;
					}
			break;
			case 'Customer Sectors':
				$type = Sector::find($id);
				if (Customer::where('sector', '=', $type->getKey())->count() > 0) {
					$data['success'] = false;
					$data['msg'] = 'Could not delete customer sector. There are still customers in the system with this sector!';
				} else {
					$type->delete();
					$data['success'] = true;
				}
			break;
		}

		return Response::json($data);
	}
}

?>
