<?php

namespace App\Http\Controllers;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\ExpenseSubCategory;

class ExpenseController extends BaseController {

	protected $layout = 'master';

	public function index() {
		$expenses = Expense::orderBy('id', 'DESC')->take(100)->get();

		// $this->layout->nav_main = 'Expenses';
		// $this->layout->nav_sub = 'List expenses';
		// $this->layout->content = View::make('expenses.index', compact('expenses'));

		return view('expenses.index', [
			'nav_main' => 'Expenses',
			'nav_sub' => 'List expenses',
			'expenses' => $expenses
		]);
	}

	public function edit($expenseId) {
		$expense = Expense::find($expenseId);

		if (!$expense)
			return redirect('/');

		// $this->layout->nav_main = 'Expenses';
		// $this->layout->nav_sub = '';
		// $this->layout->content = View::make('expenses.edit')
		// 	->with('isEdit', true)
		// 	->with('expense', $expense);
		
		return view('expenses.index', 
		[
			'isEdit' => true,
			'expense' => $expense,
			'nav_main' => 'Expenses',
			'nav_sub' => ''
		]);
	}

	public function create() {
		// $this->layout->nav_main = 'Expenses';
		// $this->layout->nav_sub = 'Create expense';
		// $this->layout->content = View::make('expenses.edit')
		// 	->with('isEdit', false);
		
		return view('expenses.edit', 
		[
			'isEdit' => false,
			'nav_main' => 'Expenses',
			'nav_sub' => 'Create expense'
		]);
	}

	public function manageCategories() {

		$categories = ExpenseCategory::orderBy('accountingCode', 'ASC')->get();

		foreach($categories as $category) {
			$category->subCategories = ExpenseSubcategory::where('category', '=', $category->id)->orderBy('accountingCode', 'ASC')->get();
		}

		// $this->layout->nav_main = 'Expenses';
		// $this->layout->nav_sub = 'Manage categories';
		// $this->layout->content = View::make('expenses.categories')
		// 	->with('categories', $categories);
		
		return view('expenses.categories', 
		[
			'categories' => $categories,
			'nav_main' => 'Expenses',
			'nav_sub' => 'Manage categories'
		]);
	}
}