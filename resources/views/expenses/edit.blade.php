@extends('master')
@section('page_title')

@stop
<?php 
	use App\Models\variables\PayMethod;
	use App\Models\ExpenseCategory;
?>
@section('page_header')
	<button class="btn btn-green" onClick='$(".bit-1").scope().saveExpense()'>
		<i class="fa fa-save"></i> Save expense
	</button>
@stop

@section('breadcrumbs')
	<a href='/expenses'>Expenses</a> <i class="breadcrumb"></i> Create expense
@stop

@section('scripts')
	<script>
	var app = angular.module('app', []);

	app.config(function($interpolateProvider) {
		$interpolateProvider.startSymbol('<%').endSymbol('%>');
	});

	app.controller('expenseController', function($scope, $compile) {

		$scope.expense = {
			products: [
				{
					productName: '',
					lastPrice: 0,
					purchasePrice: 0,
					salesPrice: 0,
					quantity: 1,
					discount: 0,
					isAsset: false
				}
			],
			subtotals: [
				{
					subtotal: 0,
					vat: 21
				}
			],
			supplier: null,
			supplierName: '',
			payments: [],
			isOfficial: true,
			isInternal: false,
			waitingForInvoice: false,
			invoiceReceivedDate: '',
			invoiceDate: '',
			proforma: '',
			invoiceNumber: '',
			quoteId: '',
			category: null,
			subcategory: null
		};

		$scope.paymentMethods = {{ json_encode(PayMethod::all()->pluck('type', 'id')) }};

		$scope.$compile = $compile;
		$scope.editingPayment = {
			entries: {
				amount: 5
			}
		};
		$scope.editingPaymentId = -1;

		// If we are editing, get the data
		@if($isEdit)
			$scope.expense = {{ json_encode($expense) }};
			$scope.expense.subtotals = {{ json_encode($expense->getSubtotals) }};
			$scope.expense.products = {{ json_encode($expense->getProducts) }};
			$scope.expense.payments = {{ json_encode($expense->getPaymentsWithDetails()) }};

			$scope.expense.isOfficial = ($scope.expense.isOfficial == 1) ? true : false;
			$scope.expense.isInternal = ($scope.expense.isInternal == 1) ? true : false;

			$scope.expense.invoiceReceivedDate = '{{ date('d-m-Y', strtotime($expense->invoiceReceivedDate)) }}';
			$scope.expense.invoiceDate = '{{ date('d-m-Y', strtotime($expense->invoiceDate)) }}';

			setTimeout(function() {
				$scope.getSubCategories();
			}, 1);
		@else

		@endif

		$scope.getSubCategories = function() {
			ajaxRequest(
				'get_expense_subcategories',
				{
					mainCategory: $scope.expense.category
				},
				function(data) {
					$scope.subcategories = data;
					$scope.$apply();
					$('#subcategory').trigger('change');
				}
			);
		};

		$scope.getSubtotalVat = function(subtotal) {
			return (parseFloat(subtotal.subtotal) * (parseFloat(subtotal.vat) / 100)).toFixed(2);
		};

		$scope.addSubtotal = function() {
			$scope.expense.subtotals.push({
				id: -1,
				subtotal: 0,
				vat: 21
			});
		};

		$scope.removeSubtotal = function(subtotal) {
			$scope.expense.subtotals.splice($scope.expense.subtotals.indexOf(subtotal), 1);
		};

		$scope.addProduct = function() {
			$scope.expense.products.push({
				id: -1,
				productId: -1,
				productName: '',
				lastPrice: 0,
				purchasePrice: 0,
				salesPrice: 0,
				quantity: 1,
				discount: 0,
				isAsset: false
			});
		};

		$scope.getProductPreSubtotal = function(product) {
			return (parseFloat(product.quantity) * parseFloat(product.purchasePrice));
		};

		$scope.getProductSubTotal = function(product) {
			return ((parseFloat(product.quantity) * parseFloat(product.purchasePrice)) * (1-(parseFloat(product.discount)/100)));
		};

		$scope.getSubtotal = function() {
			var total = 0;
			angular.forEach($scope.products, function(element, index) {
				total += $scope.getProductSubTotal(element);
			});
			return total;
		};

		$scope.getTotal = function() {
			var total = 0;
			angular.forEach($scope.products, function(element, index) {
				total += $scope.getProductSubTotal(element);
			});
			return "BITCH";
		};

		$scope.openNewPaymentModal = function() {
			$scope.editingPaymentIndex = -1;
			$scope.editingPayment = {
				id: -1,
				entries: [
				{
					amount: '0'
				}]
			};

			openModal("Create new payment", $('#createPaymentModal'), 600, true);

			$scope.$apply();

			applyBasInput($('#modal_window_content input[basinput]'));
		};

		$scope.deletePaymentEntry = function(entry) {
			$scope.editingPayment.entries.splice($scope.editingPayment.entries.indexOf(entry), 1);
		};

		$scope.addPaymentEntry = function() {
			$scope.editingPayment.entries.push({
				id: -1,
				invoiceId: '',
				amount: ''
			});
		};

		$scope.editPayment = function(payment) {
			$scope.editingPaymentIndex = $scope.expense.payments.indexOf(payment);
			$scope.editingPayment = $.extend(true, {}, payment); // Littlebit of jQuery magic here

			openModal("Create new payment", $('#createPaymentModal'), 600, true);

			setTimeout(function() {
				applyBasInput($('#modal_window_content input[basinput]'));
			}, 50);
		};

		$scope.saveExpense = function() {
			if ($scope.expense.supplierName == '') {
				showError('Please select a supplier before creating the expense');
				return;
			}
			if ($scope.expense.subcategory == null) {
				showError('Please select a category and subcategory before creating the expense');
				return;
			}

			ajaxRequest(
				'save_expense',
				{
					expense: $scope.expense
				},
				function(data) {
					if (data.success) {
						location.assign('expenses');
					} else {
						showError(data.message);
					}
				}
			);
		};

		$scope.finishPayment = function() {
			if ($scope.editingPaymentIndex == -1) {
				// Add
				$scope.expense.payments.push($scope.editingPayment);
			} else {
				// Replace
				$scope.expense.payments[$scope.editingPaymentIndex] = $scope.editingPayment;
			}
			$('#mask').trigger('click');
		};

		$scope.deleteProduct = function(product) {
			$scope.expense.products.splice($scope.expense.products.indexOf(product), 1);
		};

		$scope.getSubtotalTotal = function() {
			var total = 0;
			angular.forEach($scope.expense.subtotals, function(element, index) {
				total += parseFloat(element.subtotal);
			});
			return total;
		};

		$scope.getVatTotal = function() {
			var total = 0;
			angular.forEach($scope.expense.subtotals, function(element, index) {
				total += parseFloat($scope.getSubtotalVat(element));
			});
			return total;
		};
	});

	app.directive('productSelect', function() {
			return {
				restrict: 'A',
        		scope: false,
				link: function(scope, element, attrs) {
					setTimeout(function() {
						$(element).select2({
							ajax: {
								url: 'ajax/search_product',
								type: 'POST',
								dataType: 'json',
								delay: 200,
								data: function(params) {
									return {
										query: params.term
									}
								},
								processResults: function(data) {
									return {
										results: data.map(function (product) {
						                    return {
						                        text: product.name,
						                        price: product.purchasePrice,
						                        id: product.id
						                    }
						                })
									}
								},
								cache: true,

							},
							@if($isEdit)
								initSelection: function (element, callback) {
									callback({ id: scope.product.id, text: scope.product.productName });
								},
							@endif
							minimumInputLength: 2
						}).on('change', function() {
							scope.product.productId = $(this).select2('data')[0].id;
							scope.product.productName = $(this).select2('data')[0].text;
							scope.product.purchasePrice = $(this).select2('data')[0].price;
							scope.$apply();

							// Get last sale price
							ajaxRequest(
								'get_product_last_sales_price',
								{
									supplierId: scope.$parent.expense.supplier,
									productId: scope.product.productId
								},
								function(data) {
									scope.product.lastPrice = data.lastPrice;
									scope.$apply();
								}
							);
						});
					}, 1);
						
				}
			}
		});

		app.filter('currency', ['$filter', function($filter) {
			return function(input) {
				var curSymbol = '€ ';
				var decPlaces = 2;
				var thouSep = '';
				var decSep = '.';

				// Check for invalid inputs
				var out = isNaN(input) || input === '' || input === null ? 0.0 : input;

				//Deal with the minus (negative numbers)
				var minus = input < 0;
				out = Math.abs(out);
				out = $filter('number')(out, decPlaces);

				// Replace the thousand and decimal separators.  
				// This is a two step process to avoid overlaps between the two
				if(thouSep != ",") out = out.replace(/\,/g, "T");
				if(decSep != ".") out = out.replace(/\./g, "D");
				out = out.replace(/T/g, thouSep);
				out = out.replace(/D/g, decSep);

				// Add the minus and the symbol
				if(minus){
					return "-" + out;
				}else{
					return out;
				}
			}
		}]);

	$(function() {
		$('#supplier_search').select2({
			width: '100%',
			ajax: {
				url: '/ajax/search_supplier',
				type: 'POST',
				dataType: 'json',
				data: function(params) {
					return {
						query: params.term
					}
				},
				processResults: function (data, page) {
			      return {
		                results :
		                    data.map(function(item) {
		                        return {
		                            id : item.id,
		                            text : (item.tradingName) ? item.companyName + ' (' + item.tradingName + ')' : item.companyName,
		                            vatid: item.cifnif
		                        };
		                    }
		            )};
			    },
			    cache: true
			},
			@if($isEdit)
			initSelection: function (element, callback) {
				callback({ id: {{ $expense->supplier }}, text: "{{ $expense->supplierName }}" });
			},
			@endif
  			minimumInputLength: 2,
		});

		$('#supplier_search').on('select2:select', function(event) {
			var scope = $('#supplier_search').scope();
			var data = $(this).select2('data')[0];

			scope.expense.supplier = data.id;
			scope.expense.supplierName = data.text;
			scope.expense.supplierVatId = data.vatid;

			scope.$apply();
		});
	});
	</script>
@stop

@section('content')
	<div ng-app='app' ng-controller='expenseController'>
		<div class="modal_content" id='createPaymentModal'>
			<table class="form">
				<tr>
					<td style='width: 130px;'>Payment Method</td>
					<td style='width: 100%;'>
						<select ng-model='editingPayment.paymentMethod'>
							<option value='-1' selected disabled>Please select a payment method...</option>
							@foreach(PayMethod::all() as $paymentMethod)
							<option value="{{ $paymentMethod->id }}">{{ $paymentMethod->type }}</option>
							@endforeach
						</select>
					</td>
				</tr>
				<tr>
					<td>Description</td>
					<td><input type="text" ng-model='editingPayment.description'></td>
				</tr>
				<tr>
					<td>Date</td>
					<td><input type="text" class='date' ng-model='editingPayment.date' basinput date notempty></td>
				</tr>
				<tr>
					<td>Amount</td>
					<td><input type="text" class='euro' ng-model='editingPayment.amount'></td>
				</tr>
			</table>
			<br>
			<table class="data">
				<tr>
					<th style='width: 40px;'></th>
					<th style='width: 50%;'>Invoice number</th>
					<th style='width: 50%;'>Amount</th>
				</tr>
				<tr ng-repeat='paymentSub in editingPayment.entries'>
					<td>
						<button class="btn btn-red btn-square" ng-click='deletePaymentEntry(paymentSub)'><i class="fa fa-minus"></i></button>
					</td>
					<td><input type="text" ng-model='paymentSub.invoiceId'></td>
					<td><input type="text" class='euro' ng-model='paymentSub.amount'></td>
				</tr>
				<tr>
					<td colspan='3'>
						<button class="btn btn-green btn-square" ng-click='addPaymentEntry()'>
							<i class="fa fa-plus"></i>
						</button>
					</td>
				</tr>
				<tr>
					<td colspan='3'>
						<button class="btn btn-green fr" ng-click='finishPayment()'>
							<i class="fa fa-save"></i> Save payment
						</button>
					</td>
				</tr>
			</table>
		</div>
		<div class="frame" >
			<div class="bit-3">
				<div class="container">
					
					<div class="container_title blue">
						New expense
					</div>
					<div class="container_content">
						<table class="form">
							<tr>
								<td style='width: 130px;'>Supplier</td>
								<td style='width: 100%;'>
									<select ng-modle='expense.supplier' class='select2 ajax' id='supplier_search'></select>
								</td>
							</tr>
							<tr>
								<td>Company name</td>
								<td><input type='text' ng-model='expense.supplierName'></td>
							</tr>
							<tr>
								<td>Vat ID</td>
								<td><input type='text' ng-model='expense.supplierVatId'></td>
							</tr>
							<tr>
								<td>Description</td>
								<td><input type='text' ng-model='expense.description'></td>
							</tr>
							<tr class='pt'>
								<td>Category</td>
								<td>
									<select class='select2' ng-model='expense.category' placeholder='Please select a category...' ng-change='getSubCategories()'>
										<option selected disabled>Please select a category...</option>
										@foreach(ExpenseCategory::all() as $category)
										<option value='{{ $category->id }}' @if ($isEdit && $category->id == $expense->category) selected="selected" @endif>{{ $category->english }}</option>
										@endforeach
									</select>
								</td>
							</tr>
							<tr>
								<td>Sub category</td>
								<td>
									<select ng-model='expense.subcategory' id='subcategory' class='select2' placeholder='Please select a subcategory...' ng-options='subcategory.id as subcategory.english for subcategory in subcategories'>
										<option></option>
									</select>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div class="bit-3">
				<div class="container">
					<div class="container_title blue">
						Invoice Details
					</div>
					<div class="container_content">
						<table class="form">
							<tr>
								<td style='width: 140px;'>Received on</td>
								<td style='width: 100%;'><input type='text' class='basinput date' ng-model='expense.invoiceReceivedDate'></td>
							</tr>
							<tr>
								<td>Issued on</td>
								<td><input type='text' class='basinput date' ng-model='expense.invoiceDate'></td>
							</tr>
							<tr>
								<td>Proforma number</td>
								<td><input type='text' ng-model='expense.proforma'></td>
							</tr>
							<tr>
								<td>Invoice number</td>
								<td>
									<input type='text' ng-model='expense.invoiceNumber' style='width: 40%;'>&nbsp;&nbsp;
									<input type='checkbox' ng-model='expense.waitingForInvoice'> Waiting for invoice
								</td>
							</tr>
							<tr>
								<td>Job / Quote Number</td>
								<td><input type='text' ng-model='expense.quoteId'></td>
							</tr>
							<tr class='pt'>
								<td></td>
								<td><input type='checkbox' ng-model='expense.isOfficial'> Official Invoice &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type='checkbox' ng-model='expense.isInternal'> Internal</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div class="bit-3">
				<div class="container">
					<div class="container_title blue">
						Expense subtotals
						<span class='fr'><span ng-bind='getSubtotalTotal().toFixed(2)'></span> + <span ng-bind='getVatTotal().toFixed(2)'></span> = <span ng-bind='(getSubtotalTotal() + getVatTotal()).toFixed(2)'></span></span>
					</div>
					<div class="container_content nop">
						<table class="data">
							<tr>
								<th style='width: 33%;'>Subtotal</th>
								<th style='width: 33%;'>Tax</th>
								<th style='width: 33%;'>Tax</th>
								<th style='width: 40px;'></th>
							</tr>
							<tr ng-repeat='subtotal in expense.subtotals'>
								<td><input type='text' class='euro' ng-model='subtotal.subtotal'></td>
								<td><input type='text' class='percentage' ng-model='subtotal.vat'></td>
								<td><input type='text' class='euro' ng-value='getSubtotalVat(subtotal)'></td>
								<td>
									<button class="btn btn-red btn-square" ng-click='removeSubtotal(subtotal)'>
										<i class="fa fa-minus"></i>
									</button>
								</td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td colspan='2'>
									<button class="btn btn-green btn-square fr" ng-click='addSubtotal()'>
										<i class="fa fa-plus"></i>
									</button>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="container">
					<div class="container_title blue">
						Payment details
					</div>
					<div class="container_content nop">
						<table class="data highlight sortable">
							<tr>
								<th style='width: 90px;' data-sortable-date>Date</th>
								<th style='width: 40%;'>Amount</th>
								<th style='width: 60%;'>Method</th>
								<th style='width: 40px;'></th>
							</tr>
							<tr ng-repeat='payment in expense.payments'>
								<td>26-02-1994</td>
								<td class='tar'>€ <% payment.amount %></td>
								<td><% paymentMethods[payment.paymentMethod] %></td>
								<td>
									<button class="btn btn-orange btn-square" ng-click='editPayment(payment)'>
										<i class="fa fa-edit"></i>
									</button>
								</td>
							</tr>
							<tr class='no_highlight'>
								<td colspan='4'>
									<button class="btn btn-green btn-square fr" ng-click='openNewPaymentModal()'>
										<i class="fa fa-plus"></i>
									</button>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
		<br clear='both'>
		<div class="frame" ng-hide='expense.supplier'>
			<div class="bit-1">
				<div class="container">
					<div class="container_content">
						<center><h1><i class="fa fa-arrow-up"></i><br>Select a supplier to get started</h1></center>
					</div>
				</div>
			</div>
		</div>
		<div class="frame" ng-show='expense.supplier'>
			<div class="bit-1">
				<div class="container">
					<div class="container_title orange">
						<i class="fa fa-list"></i> Purchased products
					</div>
					<div class="container_content nop">
						<table class="data">
							<tr>
								<th style='width: 50%;'>Product</th>
								<th style='width: 50%;'>Product name</th>
								<th style='width: 80px;'>Price: Last</th>
								<th style='width: 80px;'>Purchase</th>
								<th style='width: 80px;'>Sales</th>
								<th style='width: 50px;'>Qty</th>
								<th style='width: 100px;'>Discount</th>
								<th style='width: 100px;'>Pre-sub total</th>
								<th style='width: 100px;'>Sub total</th>
								<th style='width: 50px;'>Asset</th>
								<th style='width: 50px;'></th>
							</tr>
							<tr ng-repeat='product in expense.products'>
								<td>
									<select class='product_selector' product-select style='width: 100%;'>

									</select>
								</td>
								<td><input type="text" ng-model='product.productName'></td>
								<td><input type="text" class='euro' readonly='readonly' ng-model='product.lastPrice'></td>
								<td><input type="text" class='euro' ng-model='product.purchasePrice'></td>
								<td><input type="text" class='euro' ng-model='product.salesPrice'></td>
								<td><input type="text" ng-model='product.quantity'></td>
								<td><input type="text" class='percentage' ng-model='product.discount'></td>
								<td><input type="text" class='euro' readonly ng-value='getProductPreSubtotal(product) | currency'></td>
								<td><input type="text" class='euro' readonly ng-value='getProductSubTotal(product) | currency'></td>
								<td><input type="checkbox" ng-model='product.isAsset'></td>
								<td>
									<button class="btn btn-red btn-square" ng-click='deleteProduct(product)'><i class="fa fa-minus"></i></button>
								</td>
							</tr>
							<tr>
								<td colspan='12'>
									<button class="btn btn-green btn-square fr mr10" ng-click='addProduct()'>
										<i class="fa fa-plus"></i>
									</button>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop