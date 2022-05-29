<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;

class PaymentController extends BaseController {

	protected $layout = 'master';

	public function create($customerId = null) {
		if (!Auth::user()->hasPermission('payment_create'))
			return redirect('/');

		// $this->layout->nav_main = 'Payments';
		// $this->layout->nav_sub = 'New payment';
		// $this->layout->content = View::make('payments.create')
		// 	->with('customerId', $customerId);
			return view('payments.create', 
			[
				'customerId' => $customerId,
				'nav_main' => 'Payments',
				'nav_sub' => 'New payment'
			]);
	}

	public function getCreator() {
		return $this->hasOne(User::class, 'id', 'createdBy');
	}

	public function index() {
		if (!Auth::user()->hasPermission('payment_list'))
			return redirect('/');

		$counts = Payment::select(DB::raw('
			sum(n500) AS n500,
			sum(n200) AS n200,
			sum(n100) AS n100,
			sum(n50) AS n50,
			sum(n20) AS n20,
			sum(n10) AS n10,
			sum(n5) AS n5,
			sum(c200) AS c200,
			sum(c100) AS c100,
			sum(c50) AS c50,
			sum(c20) AS c20,
			sum(c10) AS c10,
			sum(c5) AS c5,
			sum(c2) AS c2,
			sum(c1) AS c1'))->first();
		$totals = DB::select(DB::raw('SELECT
			(sum(n500)*500) AS n500,
			(sum(n200)*200) AS n200,
			(sum(n100)*100) AS n100,
			(sum(n50)*50) AS n50,
			(sum(n20)*20) AS n20,
			(sum(n10)*10) AS n10,
			(sum(n5)*5) AS n5,
			(sum(c200)*2) AS c200,
			(sum(c100)*1) AS c100,
			(sum(c50)*0.5) AS c50,
			(sum(c20)*0.2) AS c20,
			(sum(c10)*0.10) AS c10,
			(sum(c5)*0.05) AS c5,
			(sum(c2)*0.02) AS c2,
			(sum(c1)*0.01) AS c1
			FROM payments'))[0];

		// $this->layout->nav_main = 'Payments';
		// $this->layout->nav_sub = 'List payments';
		// $this->layout->content = View::make('payments.index')
		// 	->with('counts', $counts)
		// 	->with('totals', $totals);

			return view('payments.index', 
			[
				'counts' => $counts,
				'totals' => $totals,
				'nav_main' => 'Payments',
				'nav_sub' => 'List payments'
			]);

	}

}