<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Renewal;
use App\Models\Customer;
use App\Models\Settings;
use App\Models\Product;
use App\Models\ContactHistory;
use App\Models\Quote;
use App\Models\quotes\QuoteDetail;

use App\Classes\CommonFunctions;
use Illuminate\Http\Request;


class RenewalController extends Controller {

	protected $layout = 'master';

	public function index(Request $request) {
		if (!Auth::user()->hasPermission('renewal_list_due'))
			return redirect('/');

		if ($request->has('week')) {
			$renewalsDue = Renewal::where('nextRenewalDate', '<', date('Y-m-d', strtotime('+1 weeks')))->where('cancelled', '=', 0)->orderBy('nextRenewalDate', 'ASC')->get();
		} else if ($request->has('year')){
			$renewalsDue = Renewal::where('nextRenewalDate', '<', date('Y-m-d', strtotime('+1 years')))->where('cancelled', '=', 0)->orderBy('nextRenewalDate', 'ASC')->get();
		} else if ($request->has('product')) {
			$renewalsDue = Renewal::where('cancelled', '=', 0)->where('product', '=', $request->get('product'))->orderBy('nextRenewalDate', 'ASC')->get();
		} else if ($request->has('category')) {
			$renewalsDue = Renewal::where('cancelled', '=', 0)->whereIn('product', Product::where('category', '=', $request->get('category'))->pluck('id'))->orderBy('nextRenewalDate', 'ASC')->get();
		} else {
			$renewalsDue = Renewal::where('nextRenewalDate', '<', date('Y-m-d', strtotime('+1 months')))->where('cancelled', '=', 0)->orderBy('nextRenewalDate', 'ASC')->get();
		}

		// $this->layout->nav_main = 'Renewals';
		// $this->layout->nav_sub = '';
		// $this->layout->content = View::make('renewals.index')
		// 	->with('renewals', $renewalsDue);

		return view('renewals.index', 
			[
				'renewals' => $renewalsDue,
				'nav_main' => 'Renewals',
				'nav_sub' => ''
			]);
	}

	public function quote($renewalId) {
		if (!Auth::user()->hasPermission('renewal_quote'))
			return redirect('/');

		$renewal = Renewal::find($renewalId);

		if ($renewal) {

			$customer = Customer::find($renewal->customer);

			$quote = new Quote;
			$quote->createdOn = date('Y-m-d H:i:s');
			$quote->customer = $renewal->customer;
			$quote->description = 'Product renewal';
			$quote->status = Settings::setting('defaultJobStatus');
			$quote->createdBy = Auth::user()->id;
			$quote->assignedTo = Auth::user()->id;
			$quote->adType = ($customer->getQuoteCount() > 0) ? Settings::setting('defaultAdType') : 0;
			$quote->vat = Settings::setting('defaultVat');
			$quote->supCosts = 0;
			$quote->startedOn = date('Y-m-d H:i:s');
			$quote->save();

			// Add customer history item
			$item = new ContactHistory;
			$item->customer = $renewal->customer;
			$item->placedOn = CommonFunctions::getMysqlDate();
			$item->placedBy = Auth::user()->id;
			$item->message = 'Renewal quote #' . $quote->id . ' created by ' . Auth::user()->getFullname();
			$item->save();

			// Calculate next renewal date
			$nextRenewalDate = CommonFunctions::addMonthsToDate($renewal->startDate, ($renewal->renewalFreq * ($renewal->renewalCount + 2)));

			$quoteDetail = new QuoteDetail;
			$quoteDetail->quoteId = $quote->id;
			$quoteDetail->productId = $renewal->getProduct->id;
			$quoteDetail->productName = $renewal->getProduct->name;
			$quoteDetail->description = 'Period (' . date('d-m-Y', strtotime($renewal->nextRenewalDate)) .  ' - ' . $nextRenewalDate . ')';
			$quoteDetail->purchasePrice = $renewal->getProduct->purchasePrice;
			$quoteDetail->unitPrice = $renewal->getProduct->salesPrice;
			$quoteDetail->quantity = 1;
			$quoteDetail->discount = $renewal->discount;
			$quoteDetail->save();

			$renewal->renewalCount = (int) $renewal->renewalCount + 1;
			$renewal->nextRenewalDate = date('Y-m-d', strtotime($nextRenewalDate));
			$renewal->save();

			return redirect('/quotes/' . $quote->id . '/edit');

		} else {
			return redirect('/');
		}
	}

	public function cancel($renewalId) {
		if (!Auth::user()->hasPermission('renewal_cancel'))
			return redirect('/');

		$renewal = Renewal::find($renewalId);

		if ($renewal) {
			$renewal->cancelled = 1;
			$renewal->cancelDate = CommonFunctions::getMysqlDate();
			$renewal->save();

			return redirect('/customers/' . $renewal->customer)
				->with('flash_msg', 'Renewal cancelled succesfully!');
		}
	}

	public function delete($renewalId) {
		if (!Auth::user()->hasPermission('renewal_delete'))
			return redirect('/');

		$renewal = Renewal::find($renewalId);

		if ($renewal) {
			$renewal->delete();

			return redirect('/customers/' . $renewal->customer)
				->with('flash_msg', 'Renewal deleted succesfully!');
		}
	}
}