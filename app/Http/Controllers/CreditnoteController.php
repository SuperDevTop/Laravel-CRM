<?php

namespace App\Http\Controllers;

use App\Models\creditnotes\Creditnote;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;

use App\Classes\SimpleImage;
use App\Classes\WkHtmlToPdf;

class CreditnoteController extends Controller {

	protected $layout = 'master';

	public function index() {
		$creditnotes = Creditnote::with('getCustomer', 'getEntries')->orderBy('id', 'DESC')->take(100)->get();

		// $this->layout->nav_main = 'Quotes and creditnotes';
		// $this->layout->nav_sub = 'Quote list';
		// $this->layout->content = View::make('creditnotes.index')
		// 	->with('creditnotes', $creditnotes);

			return view('creditnotes.index', 
			[
				'creditnotes' => $creditnotes,
				'nav_main' => 'Quotes and creditnotes',
				'nav_sub' => 'Quote list'
			]);
	}

	public function show($creditnoteId) {
		$creditnote = Creditnote::find($creditnoteId);

		if (!$creditnote)
			return Redirect::back()
				->with('flash_msg', 'Creditnote not found');

		$customer = $creditnote->getCustomer;
		// $this->layout->nav_main = 'Customers';
		// $this->layout->nav_sub = 'List Customers';
		// $this->layout->content = View::make('creditnotes.edit', compact('creditnote', 'customer'));

		return view('creditnotes.edit', 
			[
				'creditnote' => $customer,
				'nav_main' => 'Customers',
				'nav_sub' => 'List Customers'
			]);
	}

	public function create($customerId) {
		$creditnote = new Creditnote;
		$creditnote->id = (Creditnote::max('id') + 1);
		$creditnote->createdOn = date('Y-m-d H:i:s');
		$creditnote->createdBy = Auth::user()->id;
		$creditnote->customer = $customerId;
		$creditnote->vat = Settings::setting('defaultVat');
		$creditnote->save();

		return redirect('/creditnotes/' . $creditnote->id . '/edit');
	}

	public function createCreditnoteTemplate($creditnoteId) {
		// First, check if the quote exists
		$creditnote = Creditnote::find($creditnoteId);
		if (!$creditnote) {
			die('creditnote does not exist.');
		}

		// Get the logo and best fit it for the creditnote
		$simpleImage = new SimpleImage;
		$simpleImage->load_base64(base64_encode(Settings::setting('logo')));
		$simpleImage->best_fit(200, 70);
		$logo = $simpleImage->output_base64('png');

		// Load the creditnote with the creditnote details
		$this->layout = null;

		$templateColor = Settings::setting('templateColor');

		return View::make('creditnotes.templates.' . Settings::setting('quoteTemplate'), compact('creditnote', 'logo', 'templateColor'));
	}

	public function generatePdf($creditnoteId, $type) {
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
		    'zoom' => 0.77,
			'disable-smart-shrinking'
		));

		$pdf->addPage(Request::root() . '/creditnotetemplate/' . $creditnoteId . '/1337head');

		if ($type == 'download') {
			$pdf->send('Creditnote #' . Creditnote::find($creditnoteId)->id . '.pdf');
		} else {
			$pdf->send();
		}
		
	}

}