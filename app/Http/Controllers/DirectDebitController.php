<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\DirectDebitJob;
use App\Models\DirectDebitDetail;
use App\Models\Settings;
use App\Models\Customer;
use App\Models\invoices\Invoice;

use App\Classes\SEPAGroupHeader;
use App\Classes\SEPAMessage;
use App\Classes\IBAN;
use App\Classes\SEPAPaymentInfo;
use App\Classes\SEPADirectDebitTransaction;

class DirectDebitController extends BaseController {

	protected $layout = 'master';

	public function newJob() {
		if (!Auth::user()->hasPermission('direct_debit_create'))
			return redirect('/');

		// $this->layout->nav_main = 'Direct Debiting';
		// $this->layout->nav_sub = 'New Direct Debit Job';
		// $this->layout->content = View::make('directdebit.newJob');

		
		return view('directdebit.newJob', 
		[
			'nav_sub' => 'New Direct Debit Job',
			'nav_main' => 'Direct Debiting'
		]);

	}

	public static function getInvoiceValidation($detailId) {
		$errors = '<ul>';

		// Loads of things we have to check here

		$detail = DirectDebitDetail::find($detailId);
		$invoice = Invoice::find($detail->invoice);
		$customer = Customer::find($detail->customer);

		// Check if they exist (they should)
		if (!$detail) {
			$errors .= '<li>The direct debit entry could not be found</li>';
			return $errors;
		}
		if (!preg_match("/^([\-A-Za-z0-9\+\/\?:\(\)\., ]){1,35}\z/", $detail->description)) {
			$errors .= '<li>The entry description may only contain regular letters (no accents!), digits, whitespaces and any of the following characters: <b>- + / ? : ( ) .</b><br>It also may only contain max 35 characters';
			return $errors;
		}
		if (!$invoice) {
			$errors .= '<li>The invoice could not be found</li>';
			return $errors;
		}
		if (!$customer) {
			$errors .= '<li>The customer could not be found</li>';
			return $errors;
		}


		// We need a mandate id and date
		if ($customer->sepa_mandateId == '')
			$errors .= '<li>The SEPA mandate id is empty</li>';
		if ($customer->sepa_mandateDate == '')
			$errors .= '<li>The SEPA mandate date is empty</li>';

		if (!preg_match("/^([A-Za-z0-9]|[\+|\?|\/|\-|:|\(|\)|\.|,|']){1,35}\z/", $customer->sepa_mandateId))
			$errors .= '<li>The SEPA mandate id is not valid (between 1 and 35 characters and no special characters)</li>';

		if ($invoice->getTotal() == 0)
			$errors .= '<li>The total for this invoice is €0.00</li>';

		// Validate IBAN
		if ($customer->iban == '' || $customer->iban == null)
			$errors .= '<li>The iban for this customer is empty</li>';

		$iban = new IBAN;
		if ($customer->iban != '' && !$iban->Verify($customer->iban))
			$errors .= '<li>The iban for this customer (' . $customer->getCustomerName() . ') is invalid</li>';

		// End to end identification = jobtitle (must be between 0 and 36 characters long)
		if (strlen($invoice->jobTitle) < 1)
			$errors .= '<li>The job title can not be empty</li>';

		return $errors . '</ul>';
	}

	public function index() {
		if (!Auth::user()->hasPermission('direct_debit_list'))
			return redirect('/');

		// $this->layout->nav_main = 'Direct Debiting';
		// $this->layout->nav_sub = 'Index jobs';
		// $this->layout->content = View::make('directdebit.index');

		
		return view('directdebit.index', 
		[
			'nav_main' => 'Direct Debiting',
			'nav_sub' => 'Index Jobs'
		]);
	}

	public function getSepaXML($jobId) {
		$this->layout = null;
		$job = DirectDebitJob::find($jobId);

		if ($job) {

			// Create the SEPA message (or the root element in the XML file)
			$message = new SEPAMessage('urn:iso:std:iso:20022:tech:xsd:pain.008.001.02');
			$groupHeader = new SEPAGroupHeader(); // (1..1)
			$groupHeader->setMessageIdentification('SEPA-'.time());
			$groupHeader->setInitiatingPartyName(Settings::setting('sepa_initiating_party'));
			$message->setGroupHeader($groupHeader);



			// Payment information
			$paymentInfo = new SEPAPaymentInfo(); // (1..n)
			$paymentInfo->setPaymentInformationIdentification(1);
			$paymentInfo->setBatchBooking((Settings::setting('sepa_batch_booking') == true) ? 'true' : 'false');
			$paymentInfo->setLocalInstrumentCode(Settings::setting('sepa_local_instrument_code'));

			$paymentInfo->setSequenceType(Settings::setting('sepa_sequence_type'));

			$paymentInfo->setRequestedCollectionDate(date('Y-m-d', strtotime('+' . Settings::setting('sepa_default_collection_date') . ' days')));

			$paymentInfo->setCreditorName(Settings::setting('sepa_creditor_name'));
			$paymentInfo->setCreditorAccountIBAN(Settings::setting('sepa_creditor_iban'));
			$paymentInfo->setCreditorAgentBIC(Settings::setting('sepa_creditor_bic'));
			$paymentInfo->setCreditorSchemeIdentification(Settings::setting('sepa_creditor_scheme_identification'));
			




			$jobDetails = DirectDebitDetail::where('job', '=', $job->id)->get();

			foreach($jobDetails as $detail) {
				$customer = Customer::find($detail->customer);
				$invoice = Invoice::find($detail->invoice);
				// If the customer or invoice is not found, ignore the job
				if (!$customer || !$invoice)
					continue;
				$_ENV['test'] = $detail->description . ' - ' . $customer->getCustomerName();
				$transaction = new SEPADirectDebitTransaction(); // (1..n)
				$transaction->setEndToEndIdentification(substr($detail->description, 0, 35)); // Unique transaction identifier (shown to the debtor)
				$transaction->setInstructedAmount($detail->total + $detail->bankCharge);
				$transaction->setMandateIdentification($customer->sepa_mandateId);/// ??????????
				$transaction->setDateOfSignature(date('Y-m-d', strtotime($customer->sepa_mandateDate)));    /// ??????????
				$transaction->setAmendmentIndicator('false');
				$transaction->setDebtorName($customer->getCustomerName());
				$transaction->setDebtorIban($customer->iban);
				$transaction->setRemittanceInformation('Invoice ' . $detail->invoice); // Shown to the debtor
				$paymentInfo->addTransaction($transaction);
			}
			$message->addPaymentInfo($paymentInfo);

			header('Content-Type: application/xml; charset=utf-8');
			header("Content-Transfer-Encoding: Binary"); 
			header("Content-disposition: attachment; filename=\"" . $job->description . "\".xml");
			$dom = dom_import_simplexml($message->getXML())->ownerDocument;
			$dom->preserveWhiteSpace = false;
			$dom->formatOutput = true;
			echo $dom->saveXML();

		} else {
			return redirect('/');
		}
	}

}
