<?php
/**
 * 
 * @author Bas & Angel
 *
 */
namespace App\Http\Controllers;
use App\Models\invoices\Invoice;
use App\Models\invoices\InvoiceEmail;
use App\Models\Settings;

use App\Classes\SimpleImage;
use App\Classes\WkHtmlToPdf;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

class InvoiceController extends BaseController {

	protected $layout = 'master';

	public function index() {
		$invoices = Invoice::with('getCustomer', 'getEntries')->orderBy('id', 'DESC')->take(100)->get();

		// $this->layout->nav_main = 'Quotes and invoices';
		// $this->layout->nav_sub = 'Quote list';
		// $this->layout->content = View::make('invoices.index')
		// 	->with('invoices', $invoices);
		
		return view('invoices.index', 
			[
				'invoices' => $invoices,
				'nav_main' => 'Quotes and invoices',
				'nav_sub' => 'Quote list'
			]);
	}

	public function show($invoiceId) {
		$invoice = Invoice::find($invoiceId);

		if (!$invoice)
			return Redirect::back()
				->with('flash_msg', 'Invoice not found');

		$customer = $invoice->getCustomer;
		// $this->layout->nav_main = 'Customers';
		// $this->layout->nav_sub = 'List Customers';
		// $this->layout->content = View::make('invoices.edit', compact('invoice', 'customer'));

		return view('invoices.edit', [
			'invoice' => $invoice,
			'customer'=> $customer,
			'nav_main' => 'Customers',
			'nav_sub' => 'List Customers'
		]);
	}

	public function create($customerId) {
		$invoice = new Invoice;
		$invoice->id = (Invoice::max('id') + 1);
		$invoice->createdOn = date('Y-m-d H:i:s');
		$invoice->createdBy = Auth::user()->id;
		$invoice->customer = $customerId;
		$invoice->vat = Settings::setting('defaultVat');
		$invoice->save();

		return redirect('/invoices/' . $invoice->id . '/edit');
	}

	public function createInvoiceTemplate($invoiceId) {
		// First, check if the quote exists
		$invoice = Invoice::find($invoiceId);
		if (!$invoice) {
			die('invoice does not exist.');
		}

		// Get the logo and best fit it for the invoice
		$simpleImage = new SimpleImage;
		$simpleImage->load_base64(base64_encode(Settings::setting('logo')));
		$simpleImage->best_fit(200, 70);
		$logo = $simpleImage->output_base64('png');

		// Load the invoice with the invoice details
		$this->layout = null;

		$templateColor = Settings::setting('templateColor');

		return View::make('invoices.templates.' . Settings::setting('quoteTemplate'), compact('invoice', 'logo', 'templateColor'));
	}

	public function generatePdf($invoiceId, $type) {
		$this->layout = null;
		$pdf = new WkHtmlToPdf(array(
		    'no-outline',         // Make Chrome not complain
		    'margin-top'    => 0,
		    'margin-right'  => 0,
		    'margin-bottom' => 0,
		    'margin-left'   => 0,
		    'image-quality' => 90,
		    'javascript-delay' => 20,
		    'debug-javascript',
		    'zoom' => 0.98,
			'disable-smart-shrinking'
		));

		$pdf->addPage(Request::root() . '/invoicetemplate/' . $invoiceId . '/1337head');

		if ($type == 'download') {
			$pdf->send('Invoice #' . Invoice::find($invoiceId)->id . '.pdf');
		} else {
			$pdf->send();
		}
		
	}

	public function downloadEmailAttachment($emailId) {
		$email = InvoiceEmail::find($emailId);

		if (!$email)
			return redirect('/');

		$pdfPath = storage_path() . '/files/' . Settings::setting('installationId') . '/invoice_emails/' . $email->filename . '.pdf';
		$pdfContents = file_get_contents($pdfPath);

		$response = Response::make($pdfContents);
		$response->header('content-type', 'application/pdf');
		$response->header('cache-control', 'public, max-age=600');
		return $response;
	}

	public function downloadAdditionalEmailAttachment($emailId) {
		$email = InvoiceEmail::find($emailId);
		$attachments = $email->attachments;

		if (!$email || $attachments->count() != 1)
			return redirect('/');

		$attachment = $attachments->first();		

		$attachmentPath = storage_path() . '/files/' . Settings::setting('installationId') . '/invoice_emails/additional_attachment_' . $email->id;
		$attachmentContents = file_get_contents($attachmentPath);

		$response = Response::make($attachmentContents);
		$response->header('Content-Disposition', 'attachment; filename=' . json_encode($attachment->filename));
		$response->header('content-type', $attachment->mime);
		$response->header('cache-control', 'public, max-age=600');
		return $response;
	}
	
}
