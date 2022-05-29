<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\Supplier;
use App\Models\Currency;
use Illuminate\Support\Facades\Request;

class SupplierController extends BaseController {
	protected $layout = 'master';

	public function index()
	{
		if (!Auth::user()->hasPermission('supplier_view'))
			return redirect('/');
		
		$suppliers = Supplier::orderBy('companyName', 'ASC')->get();
		// $this->layout->nav_main = 'Suppliers';
		// $this->layout->nav_sub = '';

		// $this->layout->content = View::make('supplier.index')
		// 	->with('suppliers', $suppliers);

		return view('supplier.index', 
			[
				'suppliers' => $suppliers,
				'nav_main' => 'Suppliers',
				'nav_sub' => ''
			]);
	}

	public function create()
	{
		if (!Auth::user()->hasPermission('supplier_create'))
			return redirect('/');
		
		// $this->layout->nav_main = 'Suppliers';
		// $this->layout->nav_sub = '';

		// $this->layout->content = View::make('supplier.create');

		return view('supplier.create', 
			[
				'nav_main' => 'Suppliers',
				'nav_sub' => ''
			]);
	}

	public function store(Request $req)
	{
		if (!Auth::user()->hasPermission('supplier_create'))
			return redirect('/');
		
		$supplier = Supplier::create(Request::except('swiftcode', 'account'));
		$supplier->save();

		return Redirect::route('suppliers.show', $supplier->id);
	}

	public function show($id)
	{
		if (!Auth::user()->hasPermission('supplier_view'))
			return redirect('/');

		$supplier = Supplier::find($id);

		if ($supplier) {
			// $this->layout->nav_main = 'Suppliers';
			// $this->layout->nav_sub = '';

			// $this->layout->content = View::make('supplier.show')
			// 	->with('supplier', $supplier);
			return view('supplier.show', 
			[
				'supplier' => $supplier,
				'nav_main' => 'Suppliers',
				'nav_sub' => ''
			]);
		} else {
			return redirect('/');
		}
	}

	public function edit($id)
	{
		if (!Auth::user()->hasPermission('supplier_edit'))
			return redirect('/');
		
		$supplier = Supplier::find($id);

		// $this->layout->nav_main = 'Suppliers';
		// $this->layout->nav_sub = '';

		// $this->layout->content = View::make('supplier.edit')
		// 	->with('supplier', $supplier);
		return view('supplier.edit', 
			[
				'supplier' => $supplier,
				'nav_main' => 'Suppliers',
				'nav_sub' => ''
			]);
	}

	public function update($id, Request $request)
	{
		if (!Auth::user()->hasPermission('supplier_edit'))
			return redirect('/');
		
		$supplier = Supplier::find($id);
		$supplier->fill(Request::except('swiftcode', 'account'));
		$supplier->save();

		return redirect('suppliers/' . $supplier->id)
			->with('flash_msg', 'Supplier updated');
	}

	public function destroy($id)
	{
		//
	}

}