<?php

namespace App\Http\Controllers;


use App\Models\Product;
use App\Models\Supplier;
use App\Models\variables\ProductCategory;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProductController extends BaseController {
	protected $layout = 'master';

	public function index() {
		$products = Product::orderBy('name', 'ASC')->get();
		$categories = ProductCategory::orderBy('type', 'ASC')->get();

		$categoriesById = [];
		foreach($categories as $category) {
			$categoriesById[$category->id] = $category->type;
		}

		$azAll = range('A', 'Z');

		// $this->layout->nav_main = 'Products';
		// $this->layout->nav_sub = 'Manage Products';

		// $this->layout->content = View::make('product.index')
		// 	->with('products', $products)
		// 	->with('categories', $categories)
		// 	->with('categoriesById', $categoriesById)
		// 	->with('azAll', $azAll);

		return view('product.index', 
			[
				'products' => $products,
				'categories' => $categories,
				'categoriesById' => $categoriesById,
				'azAll' => $azAll,
				'nav_main' => 'Products',
				'nav_sub' => 'Manage Products'
			]);
	}

	public function show($id) {
		$product = Product::find($id);
		if (!$product) {
			return redirect('products')
				->with('flash_err', 'That product doesn\'t exist!');
		}

		$layout = 'master';
		// $this->layout->nav_main = 'Products';
		// $this->layout->nav_sub = '';

		// $this->layout->content = View::make('product.show')
		// 	->with('product', $product);

		return view('product.show', 
			[
				'product' => $product,
				'nav_main' => 'Products',
				'nav_sub' => ''
			]);
	}

	public function search() {
		$layout = '';

		$searchType = Request::get('type');

		switch($searchType) {
			case 'query':
				if (strlen(Request::get('searchQuery')) < 2) return '';
				$products = Product::where('name', 'LIKE', '%' . Request::get('searchQuery') . '%')->get();
			break;
			case 'category':
				$products = Product::where('category', '=', Request::get('category'))->get();
			break;
			case 'az':
				if (Request::get('letter') == 'all') {
					$products = Product::all();
				} else {
					$products = Product::where('name', 'LIKE', Request::get('letter') . '%')->get();

				}
			break;
		}



		return View::make('product.result')
			->with('products', $products);
		
	}

	public function update($id) {
		if (!Auth::user()->hasPermission('product_edit'))
			return redirect('/');

		$product = Product::find($id);

		$product->fill(Request::all());

		$product->save();

		return redirect('products')
			->with('flash_msg', 'Product saved succesfully!');
	}

	public function create() {
		if (!Auth::user()->hasPermission('product_create'))
			return redirect('/');

		// If we don't have any suppliers or product categories, we return to the products page with an error
		if (Supplier::count() == 0)
			return redirect('products')
				->with('flash_err_popup', 'Sorry! In order to create a product you first have to create a supplier! Click <a href="/suppliers/create">here</a> to create a new supplier.');

		if (ProductCategory::count() == 0)
			return redirect('products')
				->with('flash_err_popup', 'Sorry! In order to create a product you first have to create a product category! Click <a href="/variables">here</a> to create a new product category.');

		// $this->layout->nav_main = 'Products';
		// $this->layout->nav_sub = '';

		// $this->layout->content = View::make('product.create');

		return view('product.create', 
			[
				'nav_main' => 'Products',
				'nav_sub' => ''
			]);
	}

	public function store() {
		if (!Auth::user()->hasPermission('product_create'))
			return redirect('/');

		$product = Product::create(Request::all());
		$product->save();

		return Redirect::route('products.index')
			->with('flash_msg', 'Product created');
	}
}

?>