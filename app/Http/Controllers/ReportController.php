<?php

namespace App\Http\Controllers;

use App\Classes\CommonFunctions;
use App\Classes\WkHtmlToPdf;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\DateTime;
use Illuminate\Support\Facades\View;

use App\Models\invoices\Invoice;
use App\Models\invoices\InvoiceDetail;
use App\Models\invoices\InvoiceComment;
use App\Models\variables\VAT;
use App\Models\quotes\QuoteComment;
use App\Models\variables\PayMethod;
use App\Models\variables\Sector;
use App\Models\variables\CustomerType;
use App\Models\variables\AdType;
use App\Models\variables\JobStatus;
use App\Models\creditnotes\Creditnote;
use App\Models\Quote;
use App\Models\User;
use App\Models\Renewal;
use App\Models\Settings;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\DirectDebitJob;
use App\Models\DirectDebitDetail;


class ReportController extends BaseController {

	protected $layout = 'master';

	public function indexCharts() {
		// $this->layout->nav_main = 'Reports';
		// $this->layout->nav_sub = 'Visual reports';
		// $this->layout->content = View::make('reports.charts');
		return view('reports.charts', 
			[
				'nav_main' => 'Reports',
				'nav_sub' => 'Visual reports'
			]);
	}
	public function indexLists() {
		// $this->layout->nav_main = 'Reports';
		// $this->layout->nav_sub = 'List reports';
		// $this->layout->content = View::make('reports.lists');

		return view('reports.lists', 
			[
				'nav_main' => 'Reports',
				'nav_sub' => 'List reports'
			]);
	}

	public function openReport() {

		$entries = array();
		$extraData = new stdClass();
		$orientation = 'landscape';

		$isCsv = Request::has('csv');

		$reportType = Request::get('type');

		if (Request::has('dateStart') && Request::has('dateEnd')) {
			$dateStart = CommonFunctions::parseMaskedDateTime(Request::get('dateStart'));
			$dateEnd = CommonFunctions::parseMaskedDateTime(Request::get('dateEnd'));
		} else {
			$dateStart = '';
			$dateEnd = '';
		}

		if (Request::has('numberStart') && Request::has('numberEnd')) {
			$numberStart = Request::get('numberStart');
			$numberEnd = Request::get('numberEnd');
		} else {
			$numberStart = '';
			$numberEnd = '';
		}


		switch($reportType) {
			case 'invoices_by_date':
				$vatId = Request::get('vatId');
				$reportTitle = 'Invoices';
				$entries = Invoice::where('vat', '=', $vatId)->where(DB::raw('DATE(createdOn)'), '>=', $dateStart)->where(DB::raw('DATE(createdOn)'), '<=', $dateEnd)->orderBy('createdOn', 'ASC')->get();
				$vat = VAT::find($vatId)->value;
				$subtotal_products = 0;
				$subtotal_work = 0;
				$total_subtotal = 0;
				$total_vat = 0;
				$total_supcosts = 0;
				foreach($entries as $entry) {
					$subtotal_products += $entry->getSubNotWork();
					$subtotal_work += $entry->getSubWork();
					$total_subtotal += $entry->getSubTotal();
					$total_vat += $entry->getVat();
					$total_supcosts += $entry->getSupCosts();
				}
				$extraData = compact('vat', 'subtotal_products', 'subtotal_work', 'total_subtotal', 'total_vat', 'total_supcosts');
			break;
			case 'invoices_by_number':
				$vatId = Request::get('vatId');
				$reportTitle = 'Invoices';
				$entries = Invoice::where('vat', '=', $vatId)->where('id', '>=', $numberStart)->where('id', '<=', $numberEnd)->orderBy('createdOn', 'ASC')->get();
			
				$vat = VAT::find($vatId)->value;
				$subtotal_products = 0;
				$subtotal_work = 0;
				$total_subtotal = 0;
				$total_vat = 0;
				$total_supcosts = 0;
				foreach($entries as $entry) {
					$subtotal_products += $entry->getSubNotWork();
					$subtotal_work += $entry->getSubWork();
					$total_subtotal += $entry->getSubTotal();
					$total_vat += $entry->getVat();
					$total_supcosts += $entry->getSupCosts();
				}
				$extraData = compact('vat', 'subtotal_products', 'subtotal_work', 'total_subtotal', 'total_vat', 'total_supcosts');
			break;
			case 'quotes_by_date':
				$jobStatusses = explode(',', Request::get('statusses'));
				$reportTitle = 'Quotes';
				$entries = Quote::whereIn('status', $jobStatusses)->where(DB::raw('DATE(createdOn)'), '>=', $dateStart)->where(DB::raw('DATE(createdOn)'), '<=', $dateEnd)->orderBy('id', 'ASC')->get();
				
				// Only show unpaid quotes if filter_unpaid == true
				if (Request::get('filter_unpaid') == 'true') {
					$newEntries = array();
					foreach($entries as $entry) {
						if (abs($entry->getTotal() - $entry->getPaid()) > 0.02) {
							$newEntries[] = $entry;
						}
					}
					$entries = $newEntries;
				}
			break;
			case 'quotes_by_number':
				$jobStatusses = explode(',', Request::get('statusses'));
				$reportTitle = 'Quotes';
				$entries = Quote::whereIn('status', $jobStatusses)->where('id', '>=', $numberStart)->where('id', '<=', $numberEnd)->orderBy('id', 'ASC')->get();
				
				// Only show unpaid quotes if filter_unpaid == true
				if (Request::get('filter_unpaid') == 'true') {
					$newEntries = array();
					foreach($entries as $entry) {
						if (abs($entry->getTotal() - $entry->getPaid()) > 0.02) {
							$newEntries[] = $entry;
						}
					}
					$entries = $newEntries;
				}
			break;
			case 'creditnotes_by_date':
				$vatId = Request::get('vatId');
				$reportTitle = 'Credit notes';
				$entries = Creditnote::where('vat', '=', $vatId)->where(DB::raw('DATE(createdOn)'), '>=', $dateStart)->where(DB::raw('DATE(createdOn)'), '<=', $dateEnd)->orderBy('createdOn', 'ASC')->get();
				$vat = VAT::find($vatId)->value;
				$subtotal_products = 0;
				$subtotal_work = 0;
				$total_subtotal = 0;
				$total_vat = 0;
				$total_supcosts = 0;
				foreach($entries as $entry) {
					$subtotal_products += $entry->getSubNotWork();
					$subtotal_work += $entry->getSubWork();
					$total_subtotal += $entry->getSubTotal();
					$total_vat += $entry->getVat();
					$total_supcosts += $entry->getSupCosts();
				}
				$extraData = compact('vat', 'subtotal_products', 'subtotal_work', 'total_subtotal', 'total_vat', 'total_supcosts');
			break;
			case 'customers_with_cif':
				$entries = Customer::where('cifnif', '!=', '')->orderBy('id', 'ASC')->get();

				$extraData = [];
			break;
			case 'customers_without_cif':
				$entries = Customer::where('cifnif', '=', '')->orderBy('id', 'ASC')->get();

				$extraData = [];
			break;
			case 'customer_quotes':
				$orientation = 'portrait';
				$reportTitle = 'Account Statement';

				$customer = Customer::find(Request::get('customerId'));
				if (!$customer)
					return redirect('/');

				$payments = Payment::where('customerId', '=', $customer->id)->where(DB::raw('DATE(date)'), '>=', $dateStart)->where(DB::raw('DATE(date)'), '<=', $dateEnd)->get();
				$quotes = Quote::where('customer', '=', $customer->id)->where(DB::raw('DATE(createdOn)'), '>=', $dateStart)->where(DB::raw('DATE(createdOn)'), '<=', $dateEnd)->get();
				$entries = compact('payments', 'quotes');

				$total_subtotal = 0;
				$total_vat = 0;
				$total_supcosts = 0;
				$total_received = 0;
				foreach($quotes as $quote) {
					$total_subtotal += $quote->getSubTotal();
					$total_vat += $quote->getVat();
					$total_supcosts += $quote->supCosts;
				}
				foreach($payments as $payment) {
					$total_received += $payment->getTotal();
				}
				$extraData = compact('customer', 'total_subtotal', 'total_vat', 'total_supcosts', 'total_received');
			break;
			case 'customer_invoices':
				$orientation = 'portrait';
				$reportTitle = 'Customer invoices';

				$customer = Customer::find(Request::get('customerId'));
				if (!$customer)
					return redirect('/');

				$invoices = Invoice::where('customer', '=', $customer->id)->where(DB::raw('DATE(createdOn)'), '>=', $dateStart)->where(DB::raw('DATE(createdOn)'), '<=', $dateEnd)->get();
				$entries = compact('invoices');

				$total_subtotal = 0;
				$total_vat = 0;
				$total_supcosts = 0;
				$total_received = 0;
				foreach($invoices as $invoice) {
					$total_subtotal += $invoice->getSubTotal();
					$total_vat += $invoice->getVat();
					$total_supcosts += $invoice->getSupCosts();
				}
				$extraData = compact('customer', 'total_subtotal', 'total_vat', 'total_supcosts', 'total_received');
			break;
			case 'customer_creditnotes':
				$orientation = 'portrait';
				$reportTitle = 'Customer credit notes';

				$customer = Customer::find(Request::get('customerId'));
				if (!$customer)
					return redirect('/');

				$creditnotes = Creditnote::where('customer', '=', $customer->id)->where(DB::raw('DATE(createdOn)'), '>=', $dateStart)->where(DB::raw('DATE(createdOn)'), '<=', $dateEnd)->get();
				$entries = compact('creditnotes');

				$total_subtotal = 0;
				$total_vat = 0;
				$total_supcosts = 0;
				$total_received = 0;
				foreach($creditnotes as $creditnote) {
					$total_subtotal += $creditnote->getSubTotal();
					$total_vat += $creditnote->getVat();
					$total_supcosts += $creditnote->getSupCosts();
				}
				$extraData = compact('customer', 'total_subtotal', 'total_vat', 'total_supcosts', 'total_received');
			break;
			case 'direct_debit_job':
				$orientation = 'portrait';
				$reporttitle = 'Direct Debit Report';

				$job = DirectDebitJob::find(Request::get('jobId'));

				if (!$job)
					return redirect('/');

				$entries = DirectDebitDetail::where('job', '=', $job->id)->orderBy('invoice', 'ASC')->get();

				$extraData = compact('job', 'entries');
			break;
			case 'advertising_new_clients':
				$reportTitle = 'Advertising types new clients';

				// Figure out the months
				$start = new DateTime($dateStart);
				$end = new DateTime($dateEnd);
				$interval = DateInterval::createFromDateString('1 month');
				$period = new DatePeriod($start, $interval, $end);

				// Create the months object
				$adTypes = [];
				$monthTotals = array_fill(0, iterator_count($period), 0);

				foreach(AdType::all() as $adType) {

					// Loop through every month in the defined period, and shove the values in an array
					$months = [];
					$subtotal = 0;
					foreach($period as $index => $month) {
						// Find the amount of new quotes in this month, and shove it in the array
						$count = Quote::where('adType', '=', $adType->id)
								->where('adType', '<>', CommonFunctions::getExistingCustomerAdType())
								->whereBetween('createdOn', [
									$month->modify('first day of this month')->format('Y-m-d H:i:s'),
									$month->modify('last day of this month')->format('Y-m-d H:i:s')
								])
							->count();
						$months[] = $count;
						$subtotal += $count;
						$monthTotals[$index] += $count;
					}

					$adTypes[] = [
						'type' => $adType->type,
						'months' => $months,
						'subtotal' => $subtotal
					];
				}

				$entries = $adTypes;

				// Sort the entries by subtotal..
				usort($entries, function($a, $b) {
					return $a['subtotal'] < $b['subtotal'];
				});

				$extraData = [
					'months' => $period,
					'monthTotals' => $monthTotals
				];
			break;
			case 'advertising_all_clients':
				$reportTitle = 'Advertising types new clients';

				// Figure out the months
				$start = new DateTime($dateStart);
				$end = new DateTime($dateEnd);
				$interval = DateInterval::createFromDateString('1 month');
				$period = new DatePeriod($start, $interval, $end);

				// Create the months object
				$adTypes = [];
				$monthTotals = array_fill(0, iterator_count($period), 0);

				foreach(AdType::all() as $adType) {

					// Loop through every month in the defined period, and shove the values in an array
					$months = [];
					$subtotal = 0;
					foreach($period as $index => $month) {
						// Find the amount of new quotes in this month, and shove it in the array
						$count = Quote::where('adType', '=', $adType->id)
							->whereBetween('createdOn', [
								$month->modify('first day of this month')->format('Y-m-d H:i:s'),
								$month->modify('last day of this month')->format('Y-m-d H:i:s')
							])
							->count();
						$months[] = $count;
						$subtotal += $count;
						$monthTotals[$index] += $count;
					}

					$adTypes[] = [
						'type' => $adType->type,
						'months' => $months,
						'subtotal' => $subtotal
					];
				}

				$entries = $adTypes;

				// Sort the entries by subtotal..
				usort($entries, function($a, $b) {
					return $a['subtotal'] < $b['subtotal'];
				});

				$extraData = [
					'months' => $period,
					'monthTotals' => $monthTotals
				];
				break;
			case 'advertising_completed_jobs':
				$reportTitle = 'Advertising types completed jobs';

				$footerText = 'If an advertising type is not on this list, it has not generated any leads during this period';

				// Figure out the months
				$start = new DateTime($dateStart);
				$end = new DateTime($dateEnd);
				$interval = DateInterval::createFromDateString('1 month');
				$period = new DatePeriod($start, $interval, $end);

				// Create the months object
				$adTypes = [];
				$monthTotals = array_fill(0, iterator_count($period), 0);

				foreach(AdType::all() as $adType) {

					// Loop through every month in the defined period, and shove the values in an array
					$months = [];
					$subtotal = 0;
					foreach($period as $index => $month) {
						// Find the amount of new quotes in this month, and shove it in the array
						$count = Quote::where('adType', '=', $adType->getKey())
							->where('status', '=', CommonFunctions::getCompletedJobStatus())
							->whereBetween('createdOn', [
								$month->modify('first day of this month')->format('Y-m-d H:i:s'),
								$month->modify('last day of this month')->format('Y-m-d H:i:s')
							])
							->count();
						$months[] = $count;
						$subtotal += $count;
						$monthTotals[$index] += $count;
					}

					$adTypes[] = [
						'type' => $adType->type,
						'months' => $months,
						'subtotal' => $subtotal
					];
				}

				$entries = $adTypes;

				// Sort the entries by subtotal..
				usort($entries, function($a, $b) {
					return $a['subtotal'] < $b['subtotal'];
				});

				$extraData = [
					'months' => $period,
					'monthTotals' => $monthTotals
				];
			break;
		}

		if ($isCsv) {
			// Generate CSV and exit it
			$csv = "sep=;\r\n";

			switch ($reportType) {
				case 'receipts_report':
					$fileName = 'Receipts (' . date('d-m-Y', strtotime($dateStart)) . ' - ' . date('d-m-Y', strtotime($dateEnd)) . ')';

					$payments = Payment::where('outToBank', '0')->where(DB::raw('DATE(date)'), '>=', $dateStart)->where(DB::raw('DATE(date)'), '<=', $dateEnd)->orderBy('date', 'ASC')->get();
					$paymentMethods = PayMethod::whereIn('id', array_unique($payments->pluck('paymentType')))->get();

					// Create the headers
					$csv .= '"Date","Quote","Invoice","Customer",';
					$paymentMethodIds = [];
					foreach($paymentMethods as $paymentMethod) {

						if ($paymentMethod->type == 'Cancelled')
							continue;

						$csv .= '"' . $paymentMethod->type . '",';
						$paymentMethodIds[] = strval($paymentMethod->id);
					}
					$csv = substr($csv, 0, strlen($csv)-1) . "\r\n";

					foreach($payments as $payment) {

						if (!in_array($payment->paymentType, $paymentMethodIds))
							continue;

						$quoteIds = array_unique(PaymentDetail::where('paymentId', $payment->id)->pluck('quoteId'));

						if (count($quoteIds))
							$invoiceIds = array_unique(InvoiceDetail::whereIn('quoteId', $quoteIds)->pluck('invoiceId'));
						else
							$invoiceIds = [];

						$csv .= '"' . date('d-m-Y', strtotime($payment->date)) . '",';
						$csv .= '"' . implode(', ', $quoteIds) . '",';
						$csv .= '"' . implode(', ', $invoiceIds) . '",';
						$csv .= '"' . $payment->getCustomer->getCustomerName() . '",';

						for ($i=0; $i<count($paymentMethodIds); $i++) {
							if ($payment->paymentType == $paymentMethodIds[$i]) {

								if (($payment->getTotal() - $payment->nonCash) != 0) {
									// Cash payment
									$csv .= '"' . ($payment->getTotal() - $payment->nonCash) . '",';
								} else {
									// Non-cash
									$csv .= '"' . $payment->nonCash . '",';
								}

							} else {
								$csv .= '"",';
							}
						}

						$csv = substr($csv, 0, strlen($csv)-1) . "\r\n";
					}
				break;
				case 'newsletter_export':
					$fileName = 'Newsletter export as of ' . date('d-m-Y');
					foreach(Customer::where('newsletter', '1')->where('readonly', 0)->where('email', '!=', '')->get() as $customer) {
						$csv .= '"' . $customer->getCustomerName() . '";';
						$csv .= '"' . $customer->email . '";';
						$csv .= '"' . $customer->phone . '"' . "\r\n";
					}
				break;
				case 'invoices_by_date':
					$fileName = Settings::setting('companyName') . ' Invoice List (' . $dateStart . '-' . $dateEnd . ')';
					foreach($entries as $entry) {
						$csv .= '"' . $entry->id . '";';
						$csv .= '"' . CommonFunctions::formatDate($entry->createdOn) . '";';
						$csv .= '"' . $entry->getCustomer->getCustomerName() . '";';
						$csv .= '"' . $entry->getCustomer->cifnif . '";';
						$csv .= '"' . $entry->jobTitle . '";';
						$csv .= '"' . CommonFunctions::formatNumber($entry->getSubtotal()) . '";';
						$csv .= '"' . Vat::find($entry->vat)->value . '";';
						$csv .= '"' . CommonFunctions::formatNumber($entry->getVat()) . '";';
						$csv .= '"' . CommonFunctions::formatNumber($entry->getTotal()) . '"' . "\r\n";
					}
				break;
				case 'invoices_by_number':
					$fileName = Settings::setting('companyName') . ' Invoice List (' . $numberStart . '-' . $numberEnd . ')';
					foreach($entries as $entry) {
						$csv .= '"' . $entry->id . '";';
						$csv .= '"' . CommonFunctions::formatDate($entry->createdOn) . '";';
						$csv .= '"' . $entry->getCustomer->getCustomerName() . '";';
						$csv .= '"' . $entry->getCustomer->cifnif . '";';
						$csv .= '"' . $entry->jobTitle . '";';
						$csv .= '"' . CommonFunctions::formatNumber($entry->getSubtotal()) . '";';
						$csv .= '"' . Vat::find($entry->vat)->value . '";';
						$csv .= '"' . CommonFunctions::formatNumber($entry->getVat()) . '";';
						$csv .= '"' . CommonFunctions::formatNumber($entry->getTotal()) . '"' . "\r\n";
					}
				break;
				case 'direct_debit_job':
					$fileName = Settings::setting('companyName') . ' Direct Debit List';
					foreach($entries as $entry) {
						$csv .= '"' . $entry->invoice . '";';
						$csv .= '"' . CommonFunctions::formatDate($entry->getInvoice->createdOn) . '";';
						$csv .= '"' . $entry->getInvoice->getCustomer->getCustomerName() . '";';
						$csv .= '"' . CommonFunctions::formatNumber($entry->total) . '";';
						$csv .= '"' . CommonFunctions::formatNumber($entry->bankCharge) . '";' . "\r\n";
					}
				break;
				case 'customer_report':
					if (Request::get('dateStart') != 'undefined' && Request::get('dateEnd') !== '')
						$fileName = "Customer report " . Request::get('dateStart') . ' - ' . Request::get('dateEnd');
					else
						$fileName = "Customer report as of " . date('d-m-Y');

					$customerTypes = CustomerType::pluck('type', 'id');
					$jobStatusses = JobStatus::pluck('type', 'id');
					$sectors = Sector::pluck('type', 'id');
					
					$quotes = Quote::with('getCustomer');

					if (Request::get('dateStart') != 'undefined' && Request::get('dateStart') !== '')
						$quotes = $quotes->where('createdOn', '>=', date('Y-m-d H:i:s', strtotime(Request::get('dateStart'))));

					if (Request::get('dateEnd') != 'undefined' && Request::get('dateEnd') !== '')
						$quotes = $quotes->where('createdOn', '<=', date('Y-m-d H:i:s', strtotime(Request::get('dateEnd'))));

					$quotes = $quotes->orderBy('createdOn')->get();

					$customerIdsDone = [];

					foreach($quotes as $quote) {

						if (in_array($quote->customer, $customerIdsDone))
							continue;

						if (Request::get('readonly') == 'true' && $quote->getCustomer->readonly == '1')
							continue;

						if (Request::get('newsletter') == 'true' && $quote->getCustomer->newsletter == '0')
							continue;
						
						$customerIdsDone[] = $quote->customer;

						$csv .= '"' . $quote->getCustomer->getCustomerName() . '";';
						$csv .= '"' . $quote->getCustomer->companyName . '";';
						$csv .= '"' . $quote->getCustomer->phone . '";';
						$csv .= '"' . $quote->getCustomer->mobile . '";';
						$csv .= '"' . $quote->getCustomer->email . '";';
						$csv .= '"' . $customerTypes[$quote->getCustomer->type] . '";';
						$csv .= '"' . $sectors[$quote->getCustomer->sector] . '";';
						$csv .= '"' . $quote->getCustomer->address . '";';

						$csv .= '"' . $quote->id . '";';
						$csv .= '"' . $quote->createdOn . '";';
						$csv .= '"' . $jobStatusses[$quote->status] . '";';
						$csv .= '"' . $quote->getTotal() . '";';

						$csv = substr($csv, 0, strlen($csv) - 1);
						$csv .= "\r\n";
					}					
				break;
			}

			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename=" . $fileName . ".csv");
			header("Pragma: no-cache");
			header("Expires: 0");

			echo chr(255) . chr(254) . mb_convert_encoding($csv, 'UTF-16LE', 'UTF-8');
			die();
		}

		$pdf = new WkHtmlToPdf(array(
		    'no-outline',         // Make Chrome not complain
		    'margin-top'    => 10,
		    'margin-right'  => 5,
		    'margin-bottom' => 10,
		    'margin-left'   => 5,
		    'image-quality' => 90,
		    'javascript-delay' => 20,
		    'debug-javascript',
		    'zoom' => 0.77,
			'disable-smart-shrinking',
			'orientation' => $orientation
		));

		if (isset($footerText)) {
			$pdf->setPageOptions(array(
				'footer-right' => 'Page [page] of [topage]',
				'footer-center' => $footerText . PHP_EOL . '[date]',
				'footer-font-size' => 9,
				'header-right' => Settings::setting('companyName')
			));
		} else {
			$pdf->setPageOptions(array(
				'footer-right' => 'Page [page] of [topage]',
				'footer-center' => '[date]',
				'footer-font-size' => 9,
				'header-right' => Settings::setting('companyName')
			));
		}

		$this->layout = View::make('reports.template');
		$this->layout->content = View::make('reports.types.' . $reportType)
			->with('dateStart', CommonFunctions::formatDate($dateStart))
			->with('dateEnd', CommonFunctions::formatDate($dateEnd))
			->with('numberStart', $numberStart)
			->with('numberEnd', $numberEnd)
			->with('entries', $entries)
			->with('extraData', $extraData);

		$pdf->addPage($this->layout->render());
		$this->layout = null;

		return $pdf->send();
	}

	public function getData() {
		$this->layout = null;
		$startDate = Request::get('startDate');
		$endDate = Request::get('endDate');
		$chartType = Request::get('chartType');
		$chartInterval = Request::get('chartInterval');

		$concat = '';
		$groupBy = '';
		$orderBy = '';
		$dateColumn = '';

		$chartTypeDateColumnNames = [
			'new_quotes_line' => 'createdOn',
			'new_quotes_bar' => 'createdOn',
			'payment_count_line' => 'date',
			'payment_count_bar' => 'date',
			'payments_sum_line' => 'date',
			'payments_sum_bar' => 'date',
			'payments_avg_line' => 'date',
			'payments_avg_bar' => 'date'
		];
		$dateColumn = $chartTypeDateColumnNames[$chartType];

		switch($chartInterval) {
			case 'day_calendar':
				$concat = 'CONCAT(DAY(' . $dateColumn . '), "-", MONTH(' . $dateColumn . '), "-", YEAR(' . $dateColumn . '))';
				$groupBy = 'DAY(' . $dateColumn . '), MONTH(' . $dateColumn . '), YEAR(' . $dateColumn . ')';
				$orderBy = 'YEAR(' . $dateColumn . '), MONTH(' . $dateColumn . '), DAY(' . $dateColumn . ')';
			break;
			case 'day_week':
				$concat = 'date_format(' . $dateColumn . ', "%W")';
				$groupBy = 'DAYOFWEEK(' . $dateColumn . ')';
				$orderBy = 'DAYOFWEEK(' . $dateColumn . ')';
			break;
			case 'week':
				$concat = 'CONCAT(WEEKOFYEAR(' . $dateColumn . '), "-", YEAR(' . $dateColumn . '))';
				$groupBy = 'WEEKOFYEAR(' . $dateColumn . '), YEAR(' . $dateColumn . ')';
				$orderBy = 'YEAR(' . $dateColumn . '), WEEKOFYEAR(' . $dateColumn . ')';
			break;
			case 'month':
				$concat = 'CONCAT(MONTH(' . $dateColumn . '), "-", YEAR(' . $dateColumn . '))';
				$groupBy = 'MONTH(' . $dateColumn . '), YEAR(' . $dateColumn . ')';
				$orderBy = 'YEAR(' . $dateColumn . '), MONTH(' . $dateColumn . ')';
			break;
			case 'quarter':
				$concat = 'CONCAT(QUARTER(' . $dateColumn . '), "-", YEAR(' . $dateColumn . '))';
				$groupBy = 'QUARTER(' . $dateColumn . '), YEAR(' . $dateColumn . ')';
				$orderBy = 'YEAR(' . $dateColumn . '), QUARTER(' . $dateColumn . ')';
			break;
			case 'year':
				$concat = 'YEAR(' . $dateColumn . ')';
				$groupBy = 'YEAR(' . $dateColumn . ')';
				$orderBy = 'YEAR(' . $dateColumn . ')';
			break;
		}

		$type = '';
		$title = '';
		$labels = [];
		$values = [];

		switch($chartType) {
			case 'new_quotes_line':
				$type = 'line';
				$title = 'New quote count (' . $startDate . ') - (' . $endDate . ')';
				$labels = $this->getChartData('quotes', $dateColumn, $concat, $groupBy, $orderBy, $startDate, $endDate);
				$values = $this->getChartData('quotes', $dateColumn, 'count(*)', $groupBy, $orderBy, $startDate, $endDate);
			break;
			case 'new_quotes_bar':
				$type = 'bar';
				$title = 'New quote count (' . $startDate . ') - (' . $endDate . ')';
				$labels = $this->getChartData('quotes', $dateColumn, $concat, $groupBy, $orderBy, $startDate, $endDate);
				$values = $this->getChartData('quotes', $dateColumn, 'count(*)', $groupBy, $orderBy, $startDate, $endDate);
			break;
			case 'payment_count_line':
				$type = 'line';
				$title = 'New payment count (' . $startDate . ') - (' . $endDate . ')';
				$labels = $this->getChartData('payments', $dateColumn, $concat, $groupBy, $orderBy, $startDate, $endDate);
				$values = $this->getChartData('payments', $dateColumn, 'count(*)', $groupBy, $orderBy, $startDate, $endDate);
			break;
			case 'payment_count_bar':
				$type = 'bar';
				$title = 'New payment count (' . $startDate . ') - (' . $endDate . ')';
				$labels = $this->getChartData('payments', $dateColumn, $concat, $groupBy, $orderBy, $startDate, $endDate);
				$values = $this->getChartData('payments', $dateColumn, 'count(*)', $groupBy, $orderBy, $startDate, $endDate);
			break;
			case 'payments_sum_line':
				$type = 'line';
				$title = 'Payment sum (' . $startDate . ') - (' . $endDate . ')';
				$labels = $this->getChartData('payments', $dateColumn, $concat, $groupBy, $orderBy, $startDate, $endDate);
				$values = $this->getChartData('payments', $dateColumn, 'round(sum(n500*500 + n200*200 + n100*100 + n50*50 + n20+20 + n10*10 + n5*5 + c200*2 + c100 + c50*0.5 + c20*0.2 + c10*0.1 * c5*0.05 + c2*0.02 + c1*0.01 + nonCash), 2)', $groupBy, $orderBy, $startDate, $endDate);
			break;
			case 'payments_sum_bar':
				$type = 'bar';
				$title = 'Payment sum (' . $startDate . ') - (' . $endDate . ')';
				$labels = $this->getChartData('payments', $dateColumn, $concat, $groupBy, $orderBy, $startDate, $endDate);
				$values = $this->getChartData('payments', $dateColumn, 'round(sum(n500*500 + n200*200 + n100*100 + n50*50 + n20+20 + n10*10 + n5*5 + c200*2 + c100 + c50*0.5 + c20*0.2 + c10*0.1 * c5*0.05 + c2*0.02 + c1*0.01 + nonCash), 2)', $groupBy, $orderBy, $startDate, $endDate);
			break;
			case 'payments_avg_line':
				$type = 'line';
				$title = 'Payment average (' . $startDate . ') - (' . $endDate . ')';
				$labels = $this->getChartData('payments', $dateColumn, $concat, $groupBy, $orderBy, $startDate, $endDate);
				$values = $this->getChartData('payments', $dateColumn, 'round(avg(n500*500 + n200*200 + n100*100 + n50*50 + n20+20 + n10*10 + n5*5 + c200*2 + c100 + c50*0.5 + c20*0.2 + c10*0.1 * c5*0.05 + c2*0.02 + c1*0.01 + nonCash), 2)', $groupBy, $orderBy, $startDate, $endDate);
			break;
			case 'payments_avg_bar':
				$type = 'bar';
				$title = 'Payment average (' . $startDate . ') - (' . $endDate . ')';
				$labels = $this->getChartData('payments', $dateColumn, $concat, $groupBy, $orderBy, $startDate, $endDate);
				$values = $this->getChartData('payments', $dateColumn, 'round(avg(n500*500 + n200*200 + n100*100 + n50*50 + n20+20 + n10*10 + n5*5 + c200*2 + c100 + c50*0.5 + c20*0.2 + c10*0.1 * c5*0.05 + c2*0.02 + c1*0.01 + nonCash), 2)', $groupBy, $orderBy, $startDate, $endDate);
			break;
		}

		return [
			'chartTitle' => $title,
			'type' => $type,
			'labels' => $labels,
			'values' => $values
		];
	}

	private function getChartData($tableName, $dateColumn, $concatSelect, $groupBy, $orderBy, $startDate, $endDate) {
		return DB::table($tableName)
				->select(DB::raw($concatSelect . ' AS value'))
				->whereNotNull($dateColumn)
				->where($dateColumn, '>=', date('Y-m-d', strtotime($startDate)))
				->where($dateColumn, '<=', date('Y-m-d', strtotime($endDate)))
				->groupBy(DB::raw($groupBy))
				->orderBy(DB::raw($orderBy))
				->pluck('value');
	}

	public function index() {
		$results = [];

		switch(Request::get('type')) {

			case 'invoices':
				$results = Invoice::where(DB::raw('date(createdOn)'), '>=', date('Y-m-d', strtotime(Request::get('date1'))))->where(DB::raw('date(createdOn)'), '<=', date('Y-m-d', strtotime(Request::get('date2'))))->orderBy('createdOn', 'desc')->get();
			break;

			case 'quotes':
				$results = Quote::where(DB::raw('date(createdOn)'), '>=', date('Y-m-d', strtotime(Request::get('date1'))))->where(DB::raw('date(createdOn)'), '<=', date('Y-m-d', strtotime(Request::get('date2'))))->orderBy('createdOn', 'desc')->get();
			break;

			case 'renewals':
				$results = Renewal::where(DB::raw('date(nextRenewalDate)'), '>=', date('Y-m-d', strtotime(Request::get('date1'))))->where(DB::raw('date(nextRenewalDate)'), '<=', date('Y-m-d', strtotime(Request::get('date2'))))->orderBy('nextRenewalDate', 'desc')->get();
			break;

		}

		// $this->layout->nav_main = 'Reports';
		// $this->layout->nav_sub = '';
		// $this->layout->content = View::make('reports.index')
		// 	->with('results', $results);
		return view('reports.index', 
			[
				'results' => $results,
				'nav_main' => 'Reports',
				'nav_sub' => ''
			]);
	}

	public function overview() {

		// First we figure out the start and end date. If specified, it's that range, if not it's today
		if (Request::has('date1')) {
			$startDate = date('Y-m-d', strtotime(Request::get('date1')));
			$endDate   = date('Y-m-d', strtotime(Request::get('date2')));
		} else {
			$startDate = date('Y-m-d');
			$endDate   = date('Y-m-d');
		}

		/********************************************************************************
		*       _____ _    _          _____ _______ _____ 
		*      / ____| |  | |   /\   |  __ \__   __/ ____|
		*     | |    | |__| |  /  \  | |__) | | | | (___  
		*     | |    |  __  | / /\ \ |  _  /  | |  \___ \ 
		*     | |____| |  | |/ ____ \| | \ \  | |  ____) |
		*      \_____|_|  |_/_/    \_\_|  \_\ |_| |_____/ 
		*                                                 
		*********************************************************************************/
		// NEW CLIENTS
		$quoteIds = Customer::whereRaw('DATE(created_at) >= "' . $startDate . '"')
								->whereRaw('DATE(created_at) <= "' . $endDate . '"')->pluck('advertisingType');
		
		$quoteIds = [];
		$adIds = array_count_values($quoteIds);
		arsort($adIds);
		$newClientAdCount = [];
		foreach($adIds as $typeId => $count) {
			$adType = AdType::find($typeId);

			if ($adType->discontinued == 1)
				continue;

			$newClientAdCount[$adType->type] = $count;
		}
		$extraData['new-client-chart'] = $newClientAdCount;

		// ALL QUOTES ===========================================================================
		$quoteIds = Quote::whereNotNull('adType')
				->whereRaw('DATE(createdOn) >= "' . $startDate . '"')
				->whereRaw('DATE(createdOn) <= "' . $endDate . '"')
				->pluck('adType');
		
		$quoteIds = [];
		$adIds = array_count_values($quoteIds);
		arsort($adIds);
		$allQuotesAdCount = [];

		foreach($adIds as $typeId => $count) {
			$adType = AdType::find($typeId);

			if (!$adType)
				continue;

			if ($adType->discontinued == 1)
				continue;

			$allQuotesAdCount[$adType->type] = $count;
		}
		$extraData['all-quotes-chart'] = $allQuotesAdCount;

		// PENDING PAYMENT & COMPLETED ==============================================================
		$quoteIds = Quote::whereIn(
						'status',
						JobStatus::whereIn('type', ['Completed', 'Completed Pending Payment'])->pluck('id')
					)
					->whereNotNull('adType')
					->whereRaw('DATE(createdOn) >= "' . $startDate . '"')
					->whereRaw('DATE(createdOn) <= "' . $endDate . '"')
					->pluck('adType');
		
		$quoteIds = [];
		$adIds = array_count_values($quoteIds);
		arsort($adIds);
		$pendingCompletedAdCount = [];

		foreach($adIds as $typeId => $count) {
			$adType = AdType::find($typeId);

			if (!$adType)
				continue;

			if ($adType->discontinued == 1)
				continue;

			$pendingCompletedAdCount[$adType->type] = $count;
		}

		$extraData['pending-completed-chart'] = $pendingCompletedAdCount;

		/********************************************************************************************
		*       _____ ______ _   _ ______ _____            _          _____ _______    _______ _____ 
		*      / ____|  ____| \ | |  ____|  __ \     /\   | |        / ____|__   __|/\|__   __/ ____|
		*     | |  __| |__  |  \| | |__  | |__) |   /  \  | |       | (___    | |  /  \  | | | (___  
		*     | | |_ |  __| | . ` |  __| |  _  /   / /\ \ | |        \___ \   | | / /\ \ | |  \___ \ 
		*     | |__| | |____| |\  | |____| | \ \  / ____ \| |____    ____) |  | |/ ____ \| |  ____) |
		*      \_____|______|_| \_|______|_|  \_\/_/    \_\______|  |_____/   |_/_/    \_\_| |_____/ 
		*                                                                                           
		*********************************************************************************************/

		/**
		 * Pre-calculations for the user statistics
		 */
		$completedJobStatusses = JobStatus::where('type', 'LIKE', 'completed')->pluck('id');
		$inProgressJobStausses = JobStatus::where('type', 'LIKE', '%progress%')->pluck('id');
		$pendingPaymentJobStatusses = JobStatus::where('type', 'LIKE', '%pending payment%')->pluck('id');

		$today_comments_added = QuoteComment::whereRaw('DATE(placedOn) >= "' . $startDate . '"')->whereRaw('DATE(placedOn) <= "' . $endDate . '"')->count();
		$today_comments_added += InvoiceComment::whereRaw('DATE(placedOn) >= "' . $startDate . '"')->whereRaw('DATE(placedOn) <= "' . $endDate . '"')->count();

		$today_quotes_created = 0;
		$today_quotes_created_sum = 0;
		foreach(Quote::whereRaw('DATE(createdOn) >= "' . $startDate . '"')->whereRaw('DATE(createdOn) <= "' . $endDate . '"')->get() as $quote) {
			$today_quotes_created++;
			$today_quotes_created_sum += $quote->getTotal();
		}

		$today_invoices_created = 0;
		$today_invoices_created_sum = 0;
		foreach(Invoice::whereRaw('DATE(createdOn) >= "' . $startDate . '"')->whereRaw('DATE(createdOn) <= "' .$endDate . '"')->get() as $quote) {
			$today_invoices_created++;
			$today_invoices_created_sum += $quote->getTotal();
		}

		$today_quotes_completed = 0;
		$today_quotes_completed_sum = 0;
		foreach(Quote::whereRaw('DATE(completedOn) >= "' . $startDate . '"')->whereRaw('DATE(completedOn) <= "' . $endDate . '"')->get() as $quote) {
			$today_quotes_completed++;
			$today_quotes_completed_sum += $quote->getTotal();
		}

		$today_pending_payment = 0;
		$today_pending_payment_sum = 0;
		foreach(Quote::whereRaw('DATE(completedOn) >= "' . $startDate . '"')
				->whereRaw('DATE(completedOn) <= "' . $endDate . '"')
				->whereIn('status', $pendingPaymentJobStatusses)
				->get() as $quote) {
			$today_pending_payment++;
			$today_pending_payment_sum += $quote->getTotal();
		}
		
		/********************************************************************
		*      _    _  _____ ______ _____     _____ _______    _______ _____ 
		*     | |  | |/ ____|  ____|  __ \   / ____|__   __|/\|__   __/ ____|
		*     | |  | | (___ | |__  | |__) | | (___    | |  /  \  | | | (___  
		*     | |  | |\___ \|  __| |  _  /   \___ \   | | / /\ \ | |  \___ \ 
		*     | |__| |____) | |____| | \ \   ____) |  | |/ ____ \| |  ____) |
		*      \____/|_____/|______|_|  \_\ |_____/   |_/_/    \_\_| |_____/ 
		*                                                                    
		*********************************************************************/

		// Comments placed by user
		$user_comments = [];

		$user_quotes = [];
		$user_quotes_sum = [];
		$user_quotes_progress = [];
		$user_quotes_progress_sum = [];
		$user_quotes_completed = [];
		$user_quotes_completed_sum = [];
		$user_quotes_pending = [];
		$user_quotes_pending_sum = [];

		$user_invoices = [];
		$user_invoices_sum = [];
		foreach(User::where('disabled', '=', 0)->get() as $user) {

			/**
			 * User Comment Count
			 */
			$user_comments[$user->id] = QuoteComment::where('placedBy', '=', $user->id)
						->whereRaw('DATE(placedOn) >= "' . $startDate . '"')
						->whereRaw('DATE(placedOn) <= "' . $endDate . '"')->count();


			/**
			 * User created quotes count
			 */
			$quoteSum = 0;

			$quoteProgress = 0;
			$quoteProgressSum = 0;

			$quoteCompleted = 0;
			$quoteCompletedSum = 0;

			$quotePending = 0;
			$quotePendingSum = 0;

			$quotes = Quote::with('getEntries')->where('createdBy', '=', $user->id)
					->whereRaw('DATE(createdOn) >= "' . $startDate . '"')
					->whereRaw('DATE(createdOn) <= "' . $endDate . '"');
			$quotes = $quotes->get();
			foreach($quotes as $quote) {
				$quoteTotal = $quote->getTotal();
				$quoteSum += $quoteTotal;

				if (in_array($quote->status, $inProgressJobStausses)) {
					$quoteProgress++;
					$quoteProgressSum += $quoteTotal;
				}

				if (in_array($quote->status, $completedJobStatusses)) {
					$quoteCompleted++;
					$quoteCompletedSum += $quoteTotal;
				}

				if (in_array($quote->status, $pendingPaymentJobStatusses)) {
					$quotePending++;
					$quotePendingSum += $quoteTotal;
				}
			}
			$user_quotes[$user->id] = $quotes->count();
			$user_quotes_sum[$user->id] = $quoteSum;

			$user_quotes_progress[$user->id] = $quoteProgress;
			$user_quotes_progress_sum[$user->id] = $quoteProgressSum;

			$user_quotes_completed[$user->id] = $quoteCompleted;
			$user_quotes_completed_sum[$user->id] = $quoteCompletedSum;

			$user_quotes_pending[$user->id] = $quotePending;
			$user_quotes_pending_sum[$user->id] = $quotePendingSum;

			/**
			 * User created invoices count
			 */
			$invoiceSum = 0;
			$invoices = Invoice::with('getEntries')->where('createdBy', '=', $user->id)
						->whereRaw('DATE(createdOn) >= "' . $startDate . '"')
						->whereRaw('DATE(createdOn) <= "' . $endDate . '"');
			$invoices = $invoices->get();
			foreach($invoices as $invoice) {
				$invoiceSum += $invoice->getTotal();
			}
			$user_invoices[$user->id] = $invoices->count();
			$user_invoices_sum[$user->id] = $invoiceSum;
		}


		// $this->layout->nav_main = 'Reports';
		// $this->layout->nav_sub = 'Overview';
		// $this->layout->content = View::make('reports.overview', compact(
		// 	'extraData',
		// 	'today_comments_added',
		// 	'today_quotes_created',
		// 	'today_quotes_created_sum',
		// 	'today_invoices_created',
		// 	'today_invoices_created_sum',
		// 	'today_quotes_completed',
		// 	'today_quotes_completed_sum',
		// 	'today_pending_payment',
		// 	'today_pending_payment_sum',

		// 	'user_comments',

		// 	'user_quotes',
		// 	'user_quotes_sum',

		// 	'user_quotes_progress',
		// 	'user_quotes_progress_sum',

		// 	'user_quotes_completed',
		// 	'user_quotes_completed_sum',

		// 	'user_quotes_pending',
		// 	'user_quotes_pending_sum',

		// 	'user_invoices',
		// 	'user_invoices_sum'
		// ));

		return view('reports.overview', [
			'extraData' => $extraData,
			'today_comments_added' => $today_comments_added,
			'today_quotes_created' => $today_quotes_created,
			'today_quotes_created_sum' => $today_quotes_created_sum,
			'today_invoices_created' => $today_invoices_created,
			'today_invoices_created_sum' => $today_invoices_created_sum,
			'today_quotes_completed' => $today_quotes_completed,
			'today_quotes_completed_sum' => $today_quotes_completed_sum,
			'today_pending_payment' => $today_pending_payment,
			'today_pending_payment_sum' => $today_pending_payment_sum,

			'user_comments' => $user_comments,

			'user_quotes' => $user_quotes,
			'user_quotes_sum' => $user_quotes_sum,

			'user_quotes_progress' => $user_quotes_progress,
			'user_quotes_progress_sum' => $user_quotes_progress_sum,

			'user_quotes_completed' => $user_quotes_completed,
			'user_quotes_completed_sum' => $user_quotes_sum,

			'user_quotes_pending' => $user_quotes_pending,
			'user_quotes_pending_sum' => $user_quotes_pending_sum,

			'user_invoices' => $user_invoices,
			'user_invoices_sum' => $user_invoices_sum
		]);
	}
}
