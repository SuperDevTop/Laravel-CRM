<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

use App\Models\Quote;
use App\Models\Customer;
use App\Models\Settings;
use App\Models\ContactHistory;
use App\Models\QuoteEmail;
use App\Models\QuoteDescriptionRow;

use App\Classes\WkHtmlToPdf;
use App\Classes\CommonFunctions;
use App\Classes\SimpleImage;

use Illuminate\Http\Request;


class QuoteController extends BaseController {
	protected $layout = 'master';

	public function index(Request $request) {
		$quotes = Quote::with('getCustomer', 'getStatus', 'getEntries');

		if ($request->has('user'))
			$quotes = $quotes->where('assignedTo', '=', $request->input('user'));

		if ($request->has('status'))
			$quotes = $quotes->where('status', '=', $request->input('status'));

		if ($request->has('datestart'))
			$quotes = $quotes->where(DB::raw('DATE(createdOn)'), '>=', CommonFunctions::parseMaskedDateTime($request->input('datestart')));

		if ($request->has('dateend'))
			$quotes = $quotes->where(DB::raw('DATE(createdOn)'), '<=', CommonFunctions::parseMaskedDateTime($request->input('dateend')));

		$quotes = $quotes->orderBy('id', 'DESC')->take(100)->get();

		return view('quotes.index2', 
			[
				'quotes' => $quotes,
				'nav_main' => 'Quotes and invoices',
				'nav_sub' => 'Quote list'
			]);

		// $this->layout->nav_main = 'Quotes and invoices';
		// $this->layout->nav_sub = 'Quote list';
		// $this->layout->content = view('quotes.index')
		// 						->with('quotes', $quotes);

		//my
		// return view('quotes.index2');
		//
	}

	public function create($customerId) {
		$customer = Customer::find($customerId);
		if (!$customer) return redirect('customers');

		// Create new quote row
		$quote = new Quote();
		$quote->customer = $customerId;
		$quote->createdBy = Auth::user()->id;
		$quote->assignedTo = Auth::user()->id;
		$quote->createdOn = date('Y-m-d H:i:s');
		$quote->startedOn = CommonFunctions::getMysqlDate();
		$quote->status = Settings::setting('defaultJobStatus');
		$quote->adType = ($customer->getQuoteCount() > 0) ? Settings::setting('defaultAdType') : 0;
		$quote->vat = Settings::setting('defaultVat');
		$quote->supCosts = 0;
		$quote->save();

		// Add customer history item
		$item = new ContactHistory;
		$item->customer = $customer->id;
		$item->placedOn = CommonFunctions::getMysqlDate();
		$item->placedBy = Auth::user()->id;
		$item->message = 'Quote #' . $quote->id . ' created by ' . Auth::user()->getFullname();
		$item->save();

		return redirect('quotes/' . $quote->id . '/edit');
	}
	
	public function edit($quoteId) {
		$quote = Quote::find($quoteId);

		if (!$quote)
			return Redirect::back()
				->with('flash_err', 'Quote not found');

		$customer = $quote->getCustomer;

		// $this->layout->nav_main = 'Customers';
		// $this->layout->nav_sub = 'List Customers';
		// $this->layout->content = view('quote.edit', compact('quote', 'customer'));

		return view('quote.edit', 
		[
			'customer' => $customer,
			'quote' => $quote,
			'nav_main' => 'Quotes and invoices',
			'nav_sub' => 'Quote list'
		]);
	}

	public function createQuoteTemplate($quoteId) {
		// First, check if the quote exists
		$quote = Quote::find($quoteId);
		if (!$quote) {
			die('Quote does not exist.');
		}

		// Get the logo and best fit it for the quote
		$simpleImage = new SimpleImage;
		$simpleImage->load_base64(base64_encode(Settings::setting('logo')));
		$simpleImage->best_fit(200, 70);
		$logo = $simpleImage->output_base64('jpg');

		// Load the quote with the quote details
		$this->layout = null;

		$templateColor = Settings::setting('templateColor');

		return view('quote.templates.' . Settings::setting('quoteTemplate'), compact('quote', 'logo', 'templateColor'));
	}

	public function createReceiptTemplate($quoteId) {
		// First, check if the quote exists
		$quote = Quote::find($quoteId);
		if (!$quote) {
			die('Quote does not exist.');
		}

		// Get the logo and best fit it for the quote
		$simpleImage = new SimpleImage;
		$simpleImage->load_base64(base64_encode(Settings::setting('logo')));
		$simpleImage->best_fit(200, 70);
		$logo = $simpleImage->output_base64('jpg');

		// Load the quote with the quote details
		$this->layout = null;

		$templateColor = Settings::setting('templateColor');
		$isReceipt = true;
		return view('quote.templates.' . Settings::setting('quoteTemplate'), compact('quote', 'logo', 'templateColor', 'isReceipt'));
	}

	public function generatePdf($quoteId, $type, Request $request) {
		$quote = Quote::find($quoteId);
		$this->layout = null;
		$pdf = new WkHtmlToPdf(array(
		    'no-outline',         // Make Chrome not complain
		    'margin-top'    => 0,
		    'margin-right'  => 0,
		    'margin-bottom' => 0,
		    'margin-left'   => 0,
		    'image-quality' => 90,
		    'javascript-delay' => 20,
		    'de bug-javascript',
		    'zoom' => 0.98,
			'disable-smart-shrinking'
		));

		$urlPostfix = (($request->has('nocomments')) ? '?nocomments=1' : '');

		if ($type == 'receipt') {
			$pdf->addPage($request->root() . '/receipttemplate/' . $quoteId . '/1337head');
		} else {
			$pdf->addPage($request->root() . '/quotetemplate/' . $quoteId . '/1337head' . $urlPostfix);
			if (QuoteDescriptionRow::where('quoteId', '=', $quoteId)->count() > 0)
				$pdf->addPage($request->root() . '/quotedescriptionsheet/' . $quoteId . '/1337head');
		}

		/*if ($_SERVER['REMOTE_ADDR'] == '91.5245.201.201') {
			$pdf->saveAs(storage_path('test.pdfff'));
			return $pdf->getError();
		}*/

		if ($type == 'download') {
			$pdf->send('Quote #' . Quote::find($quoteId)->id . '.pdf');
		} else {
			$pdf->send();
		}
		
	}

	public function listMyJobs() {
		if (!Auth::user()->hasPermission('quotes_view'))
			return redirect('/');

		$quotes = Quote::where('assignedTo', '=', Auth::id())->whereIn('status', explode(',', Auth::user()->myJobStatusses))->orderBy('requiredBy', 'DESC')->orderBy('status', 'ASC')->get();

		// $this->layout->nav_main = 'Quotes';
		// $this->layout->nav_sub = 'My Jobs';
		// $this->layout->content = view('quotes.myjobs')
		// 	->with('quotes', $quotes);
		return view('quotes.myjobs', 
			[
				'quotes' => $quotes,
				'nav_main' => 'Quotes',
				'nav_sub' => 'My Jobs'
			]);
	}

	public function descriptionSheet($quoteId) {
		$quote = Quote::find($quoteId);
		$customer = $quote->getCustomer;

		if (!$quote)
			return redirect('/');

		// $this->layout->nav_main = 'Customers';
		// $this->layout->nav_sub = 'List Customers';
		// $this->layout->content = view('quote.descriptionSheet', compact('quote', 'customer'));

		return view('quote.descriptionSheet', [
			'nav_main' => 'Customers',
			'nav_sub' => 'List Customers',
			'quote' => $quote,
			'customer' => $customer
		]);
	}

	public function printDescriptionSheet($quoteId) {
		$quote = Quote::find($quoteId);

		if (!$quote)
			return redirect('/');

		$rows = QuoteDescriptionRow::where('quoteId', '=', $quote->id)->orderBy('position', 'ASC')->get();

		$this->layout = null;

		return view('quote.templates.descriptionSheet')
			->with('quote', $quote)
			->with('rows', $rows)
			->with('templateColor', Settings::setting('templateColor'));
	}

	public function downloadEmailAttachment($emailId) {
		$email = QuoteEmail::find($emailId);

		if (!$email)
			return redirect('/');

		$pdfPath = storage_path() . '/files/' . Settings::setting('installationId') . '/quote_emails/' . $email->filename . '.pdf';
		$pdfContents = file_get_contents($pdfPath);

		$response = Response::make($pdfContents);
		$response->header('content-type', 'application/pdf');
		$response->header('cache-control', 'public, max-age=600');
		return $response;
	}

	public function downloadAdditionalEmailAttachment($emailId) {
		$email = QuoteEmail::find($emailId);
		$attachments = $email->attachments;

		if (!$email || $attachments->count() != 1)
			return redirect('/');

		$attachment = $attachments->first();		

		$attachmentPath = storage_path() . '/files/' . Settings::setting('installationId') . '/quote_emails/additional_attachment_' . $email->id;
		$attachmentContents = file_get_contents($attachmentPath);

		$response = Response::make($attachmentContents);
		$response->header('Content-Disposition', 'attachment; filename=' . json_encode($attachment->filename));
		$response->header('content-type', $attachment->mime);
		$response->header('cache-control', 'public, max-age=600');
		return $response;
	}
}
