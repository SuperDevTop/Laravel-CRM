@extends('master')
@section('page_name')
	New payment
@stop
<?php
 use App\Models\variables\PayMethod;
 use App\Models\variables\JobStatus;
 use App\Classes\CommonFunctions;
?>

@section('stylesheets')
	<style>
		.payment_notes_coins td {
			width: 30px;
			padding: 1px 3px 1px 3px;
			text-align: center;
			font-weight: bold;
			font-size: 9pt;
		}

		.payment_notes_coins input {
			width: 100%;
			text-align: center;
			margin-top: -4px;
			border-top: 0;
			border-bottom: 0;
		}

		.payment_notes_coins button {
			width: 32px;
			height: 30px;
			padding: 0;
		}

		.payment_notes_coins button.btn-green {
			border-bottom-left-radius: 0;
			border-bottom-right-radius: 0;
		}

		.payment_notes_coins button.btn-red {
			border-top-left-radius: 0;
			border-top-right-radius: 0;
			margin-top: -2px;
		}

		table.form label {
			font-weight: bold;
		}
	</style>
@stop

@section('scripts')
	<script src='js/angularjs.min.js'></script>

	<script>
		$(function() {
			$('#payMethod').select2({
				placeholder: 'Select a payment method...',
			}).on('change', function(event) {
				$('#rootScopeElement').scope().paymentData.paymentMethod = $('#payMethod option:selected').val();
			});
			setTimeout(function() {
				$('#payMethod').next('.select2').find('.select2-selection').on('focus', function() {
					$(this).closest('.select2').prev('#payMethod').select2('open');
				});
			}, 100);
			$('#customer').select2({
				width: '100%',
			    minimumInputLength: 2,
			    placeholder: 'Select a customer...',
				ajax: {
			        url: "ajax/search_customer",
			        type: 'POST',
			        dataType: 'json',
			        data: function (params) {
			            return {
			                query: params.term, // search term
			            };
			        },
			        processResults: function (data) {
						var results = [];
						$.each(data.customers, function(index, item){

							var companyName = item.companyName;

							if (item.contactName != '')
								companyName += ' (' + item.contactName + ')';

							results.push({
								id: item.id,
								text: companyName
							});
						});
						return {
							results: results
						};
					}
			    }@if($customerId != null),
			    initSelection: function(element, callback) {
					callback({
						id: {{ $customerId }},
						text: '{{ addslashes(Customer::find($customerId)->getCustomerName()) }}'
					});
				}@endif
			}).on('change', function(event) {
				var customerId = $('#customer option:selected').val();
				$('#rootScopeElement').scope().paymentData.customer = customerId;
			});
		});

		function addCustomerQuoteToPayment(quoteId, total) {
			$('#rootScopeElement').scope().selectedQuote = {
				id: quoteId,
				total: total
			};
			$('#rootScopeElement').scope().addQuoteToPayment();
			$('#rootScopeElement').scope().$apply();
		}


		var app = angular.module('app', []);

		app.config(function($interpolateProvider) {
			$interpolateProvider.startSymbol('<%').endSymbol('%>');
		});

		app.controller('paymentCtrl', function($scope, $http) {
			$scope.selectedQuote = {};
			$scope.quoteSelected = false;
			$scope.payment_notes = {
				500: 0,
				200: 0,
				100: 0,
				50: 0,
				20: 0,
				10: 0,
				5: 0
			};
			$scope.payment_coins = {
				200: 0,
				100: 0,
				50: 0,
				20: 0,
				10: 0,
				5: 0,
				2: 0,
				1: 0,
			};

			$scope.entries = [
				
			];

			$scope.paymentData = {
				customer: -1,
				paymentMethod: -1,
				nonCashTotal: 0,
				date: '<?= date("d-m-Y H:i"); ?>',
				notes: '',
				checkedByManagement: false,
				outToBank: false
			};

			@if($customerId != null)
				$scope.paymentData.customer = {{ $customerId }};
			@endif

			$scope.increaseNote = function(value) {
				$scope.payment_notes[value]++;
			};

			$scope.increaseCoin = function(value) {
				$scope.payment_coins[value]++;
			};

			$scope.decreaseNote = function(value) {
				$scope.payment_notes[value]--;
			};

			$scope.decreaseCoin = function(value) {
				$scope.payment_coins[value]--;
			};

			$scope.quoteNumberChange = function(event) {
				if (event.which != 13)
					return;

				$http.post(
					'ajax/get_quote_overview',
					{
						quoteId: $('#quoteNumber').val()
					}
				).success(function(data) {
					$('#quoteNumber').val('');
					if (data.success) {
						$scope.quoteSelected = true;
						$scope.selectedQuote = data.quote;
						$('#selectedQuoteStatus option').removeAttr('selected');
						$('#selectedQuoteStatus').val(data.quote.status)
					} else {
						notify('Quote not found');
					}
				});
			};

			$scope.addQuoteToPayment = function() {
				$scope.entries.push({
					date: getCurrentDate(),
					quote: $scope.selectedQuote.id,
					amount: $scope.selectedQuote.total.toFixed(2)
				});
			};

			$scope.addPayment = function() {
				$scope.entries.push({
					date: getCurrentDate(),
					quote: '',
					amount: ''
				});
			};

			$scope.deletePayment = function(payment) {
				$scope.entries.splice($scope.entries.indexOf(payment), 1);
			};

			$scope.getReceived = function() {
				return parseFloat($scope.paymentData.nonCashTotal) + parseFloat($scope.getCashTotal());
			};

			$scope.getAssigned = function() {
				var total = 0;
				angular.forEach($scope.entries, function(entry) {
					if (!isNaN(entry.amount) && entry.amount != '')
						total += parseFloat(entry.amount);
				});
				return total;
			};

			$scope.getRemaining = function() {
				return parseFloat($scope.getReceived()) - parseFloat($scope.getAssigned());
			}

			$scope.getCashTotal = function() {
				// Here we go...
				var total = 0;
				total += $scope.payment_notes[500] * 500;
				total += $scope.payment_notes[200] * 200;
				total += $scope.payment_notes[100] * 100;
				total += $scope.payment_notes[50] * 50;
				total += $scope.payment_notes[20] * 20;
				total += $scope.payment_notes[10] * 10;
				total += $scope.payment_notes[5] * 5;

				total += $scope.payment_coins[200] * 2;
				total += $scope.payment_coins[100] * 1;
				total += $scope.payment_coins[50] * 0.5;
				total += $scope.payment_coins[20] * 0.2;
				total += $scope.payment_coins[10] * 0.1;
				total += $scope.payment_coins[5] * 0.05;
				total += $scope.payment_coins[2] * 0.02;
				total += $scope.payment_coins[1] * 0.01;
				return total;
			};

			$scope.save = function() {
				var paymentType = $('#payMethod option:selected').val();

				if ($scope.paymentData.customer == -1) {
					showError('Please select a customer');
					return;
				}
				if (paymentType == '-1') {
					showError('Please select a payment type');
					return;
				}
				if ($scope.getRemaining().toFixed(2) != 0 && !$scope.paymentData.outToBank) {
					showAlert('An error occured', 'Could not create payment.<br><br>Of the €' + $scope.getReceived().toFixed(2) + ' received you have assigned €' + $scope.getAssigned().toFixed(2) + '<br><br>The sum of non-cash and cash money received should equal the sum of the assignments!');
					return;
				}

				if ($scope.paymentData.outToBank && $scope.getAssigned() != 0) {
					showAlert('An error occured', 'Could not create payment.<br><br>You have selected \'Out to bank\' but you have assigned €' + $scope.getAssigned() + '.<br>You can\'t assign the money to quotes if you select \'Out to bank\'');
					return;
				}

				$('#saveBtn').html('<i class="fa fa-spin fa-spinner"></i> Saving payment...');
				$('#saveBtn').attr('disabled', 'disabled');

				$scope.paymentData.paymentMethod = $('#payMethod option:selected').val();
				$http.post(
					'ajax/save_payment',
					{
						paymentData: $scope.paymentData,
						paymentNotes: $scope.payment_notes,
						paymentCoins: $scope.payment_coins,
						entries: $scope.entries
					}
				).success(function(data) {
					if (data.success) {
						$('#saveBtn').html('<i class="fa fa-check"></i> Payment created!');
						setTimeout(function() {
							location.assign('/customers/' + $scope.paymentData.customer);
						}, 1000);
						
					}
				});
			};

			$scope.updateSelectedQuoteStatus = function() {
				$http.post(
					'ajax/update_quote_status',
					{
						quoteId: $scope.selectedQuote.id,
						newStatus: $('#selectedQuoteStatus option:selected').val()
					}
				).success(function(data) {
					if (data.success) {
						notify('Quote status updated');
					}
				});
			};
		});

		app.filter('currency', ['$filter', function($filter) {
			return function(input) {
				var curSymbol = '€ ';
				var decPlaces = 2;
				var thouSep = '.';
				var decSep = ',';

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
					return "-" + curSymbol + out;
				}else{
					return curSymbol + out;
				}
			}
		}]);

		function updateQuoteStatus(quoteId, select) {
			ajaxRequest(
				'update_quote_status',
				{
					quoteId: quoteId,
					newStatus: $(select).find('option:selected').val()
				},
				function(data) {
					if (data.success) {
						notify('Quote status updated');
					}
				}
			);	
		}
	</script>
@stop

@section('content')
	<div ng-app='app' ng-controller='paymentCtrl' id='rootScopeElement'>
		<div class="frame">
			<div class="bit-1">
				<div class="container">
					<div class="container_title blue">
						General payment information
					</div>
					<div class="container_content">
						<table class="tlf" style='width: 100%;'>
							<tr>
								<td style='width: 26%;'><b>Customer</b></td>
								<td style='width: 26%;'><b>Payment Method</b></td>
								<td style='width: 90px;'><b>Non-cash</b></td>
								<td style='width: 35%;'><b>Notes</b></td>
							</tr>
							<tr>
								<td><select id='customer' ng-bind='paymentData.customer'></td>
								<td>
									<select id='payMethod' style='width: 100%;'>
										<option value='-1' disabled selected>Select a payment type...</option>
										@foreach(PayMethod::orderBy('type')->get() as $payMethod)
											<option value='{{ $payMethod->id }}'>{{ $payMethod->type }}</option>
										@endforeach
									</select>
								</td>
								<td><input type='text' class='euro fw' ng-model='paymentData.nonCashTotal'></td>
								<td><input type='text' class='fw' ng-model='paymentData.notes'></td>
							</tr>
						</table>
						<br><br>
						<table class='payment_notes_coins payment_notes' style='float: left;'>
							<tr>
								<td>&euro;500</td>
								<td>&euro;200</td>
								<td>&euro;100</td>
								<td>&euro;50</td>
								<td>&euro;20</td>
								<td>&euro;10</td>
								<td>&euro;5</td>
								<td>&euro;2</td>
								<td>&euro;1</td>
								<td>&euro;0,50</td>
								<td>&euro;0,20</td>
								<td>&euro;0,10</td>
								<td>&euro;0,05</td>
								<td>&euro;0,02</td>
								<td>&euro;0,01</td>
							</tr>
							<tr>
								<td>
									<button class="btn btn-green" ng-click='increaseNote(500)' tabindex='-1'>
										<i class="fa fa-plus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-green" ng-click='increaseNote(200)' tabindex='-1'>
										<i class="fa fa-plus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-green" ng-click='increaseNote(100)' tabindex='-1'>
										<i class="fa fa-plus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-green" ng-click='increaseNote(50)' tabindex='-1'>
										<i class="fa fa-plus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-green" ng-click='increaseNote(20)' tabindex='-1'>
										<i class="fa fa-plus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-green" ng-click='increaseNote(10)' tabindex='-1'>
										<i class="fa fa-plus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-green" ng-click='increaseNote(5)' tabindex='-1'>
										<i class="fa fa-plus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-green" ng-click='increaseCoin(200)' tabindex='-1'>
										<i class="fa fa-plus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-green" ng-click='increaseCoin(100)' tabindex='-1'>
										<i class="fa fa-plus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-green" ng-click='increaseCoin(50)' tabindex='-1'>
										<i class="fa fa-plus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-green" ng-click='increaseCoin(20)' tabindex='-1'>
										<i class="fa fa-plus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-green" ng-click='increaseCoin(10)' tabindex='-1'>
										<i class="fa fa-plus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-green" ng-click='increaseCoin(5)' tabindex='-1'>
										<i class="fa fa-plus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-green" ng-click='increaseCoin(2)' tabindex='-1'>
										<i class="fa fa-plus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-green" ng-click='increaseCoin(1)' tabindex='-1'>
										<i class="fa fa-plus"></i>
									</button>
								</td>
							</tr>
							<tr>
								<td><input type='text' ng-model='payment_notes[500]'></td>
								<td><input type='text' ng-model='payment_notes[200]'></td>
								<td><input type='text' ng-model='payment_notes[100]'></td>
								<td><input type='text' ng-model='payment_notes[50]'></td>
								<td><input type='text' ng-model='payment_notes[20]'></td>
								<td><input type='text' ng-model='payment_notes[10]'></td>
								<td><input type='text' ng-model='payment_notes[5]'></td>
								<td><input type='text' ng-model='payment_coins[200]'></td>
								<td><input type='text' ng-model='payment_coins[100]'></td>
								<td><input type='text' ng-model='payment_coins[50]'></td>
								<td><input type='text' ng-model='payment_coins[20]'></td>
								<td><input type='text' ng-model='payment_coins[10]'></td>
								<td><input type='text' ng-model='payment_coins[5]'></td>
								<td><input type='text' ng-model='payment_coins[2]'></td>
								<td><input type='text' ng-model='payment_coins[1]'></td>
							</tr>
							<tr>
								<td>
									<button class="btn btn-red" ng-click='decreaseNote(500)' tabindex='-1'>
										<i class="fa fa-minus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-red" ng-click='decreaseNote(200)' tabindex='-1'>
										<i class="fa fa-minus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-red" ng-click='decreaseNote(100)' tabindex='-1'>
										<i class="fa fa-minus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-red" ng-click='decreaseNote(50)' tabindex='-1'>
										<i class="fa fa-minus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-red" ng-click='decreaseNote(20)' tabindex='-1'>
										<i class="fa fa-minus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-red" ng-click='decreaseNote(10)' tabindex='-1'>
										<i class="fa fa-minus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-red" ng-click='decreaseNote(5)' tabindex='-1'>
										<i class="fa fa-minus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-red" ng-click='decreaseCoin(200)' tabindex='-1'>
										<i class="fa fa-minus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-red" ng-click='decreaseCoin(100)' tabindex='-1'>
										<i class="fa fa-minus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-red" ng-click='decreaseCoin(50)' tabindex='-1'>
										<i class="fa fa-minus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-red" ng-click='decreaseCoin(20)' tabindex='-1'>
										<i class="fa fa-minus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-red" ng-click='decreaseCoin(10)' tabindex='-1'>
										<i class="fa fa-minus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-red" ng-click='decreaseCoin(5)' tabindex='-1'>
										<i class="fa fa-minus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-red" ng-click='decreaseCoin(2)' tabindex='-1'>
										<i class="fa fa-minus"></i>
									</button>
								</td>
								<td>
									<button class="btn btn-red" ng-click='decreaseCoin(1)' tabindex='-1'>
										<i class="fa fa-minus"></i>
									</button>
								</td>
							</tr>
						</table>
						<div style='width: 370px; float: left; padding-top: 25px; margin-left: 35px;'>
							<table class='tlf' style='width: 100%;'>
								<tr>
									<td style='width: 200px;'>Payment date</td>
									<td style='width: 100%;'><input type='text' class='basinput datetime' ng-model='paymentData.date'></td>
								</tr>
								<tr>
									<td>Checked by management</td>
									<td><input type='checkbox' ng-model='paymentData.checkedByManagement'></td>
								</tr>
								<tr>
									<td>Out to the bank or Petty Cash</td>
									<td><input type='checkbox' ng-model='paymentData.outToBank'></td>
								</tr>
							</table>
						</div>
						<br clear='both'>
					</div>
				</div>
			</div>
		</div>
		<br clear='both'>
		<div class="frame">
			<div class="bit-2">
				<div class="container">
					<div class="container_title red">
						Quote information
					</div>
					<div class="container_content nop">
						@if($customerId == null)
							<div class="containerpadding">
								<input type='text' id='quoteNumber' style='width: 100%;' ng-keyup='quoteNumberChange($event)' placeholder='Type a quote number and press enter'>
								<br>
								<div ng-show='quoteSelected'>
									<table class='data' style='max-width: 350px;'>
										<tr>
											<td style='width: 120px;'><label>Quote number</label></td>
											<td style='width: 100%;'>#<% selectedQuote.id %></td>
										</tr>
										<tr>
											<td><label>Customer</label></td>
											<td><% selectedQuote.customerName %></td>
										</tr>
										<tr>
											<td><label>Completed on</label></td>
											<td><% selectedQuote.completedOn %></td>
										</tr>
										<tr>
											<td><label>Status</label></td>
											<td>
												<select id='selectedQuoteStatus' ng-model='selectedQuote.status' ng-change='updateSelectedQuoteStatus()'>
													@foreach(JobStatus::all() as $status)
													<option value='{{ $status->id }}'>{{ $status->type }}</option>
													@endforeach
												</select>
											</td>
										</tr>
										<tr>
											<td><label>Total amount</label></td>
											<td><% selectedQuote.total | currency %></td>
										</tr>
									</table>
									<center><button class="btn btn-green" ng-click='addQuoteToPayment()'><i class="fa fa-plus"></i> Add to payments</button></center>
								</div>
							</div>
						@else
							<table class="data">
								<tr>
									<th style='width: 65px;'>#</th>
									<th style='width: 85px;'>Price</th>
									<th style='width: 85px;'>Paid</th>
									<th style='width: 100%;'>Status</th>
									<th style='width: 50px;'>Add</th>
								</tr>
								@foreach(Quote::where('customer', '=', $customerId)->whereIn('status', explode(',', Settings::setting('awaitingPaymentJobStatusses')))->orderBy('id', 'DESC')->get() as $quote)
									<?php if(($quote->getTotal() - $quote->getPaid()) < 0.1) continue; ?>
									<tr>
										<td>{{ $quote->id }}</td>
										<td>&euro; {{ CommonFunctions::formatNumber($quote->getTotal()) }}</td>
										<td>&euro; {{ CommonFunctions::formatNumber($quote->getPaid()) }}</td>
										<td>
											<select class='fw' onChange='updateQuoteStatus({{ $quote->id }}, this)'>
												@foreach(JobStatus::orderBy('type')->get() as $status)
													<option value='{{ $status->id }}' @if($quote->status == $status->id) selected="selected" @endif>
														{{ $status->type }}
													</option>
												@endforeach
											</select>
										</td>
										<td style='text-align: center;'>
											<button class="btn btn-green" onclick='addCustomerQuoteToPayment({{ $quote->id }}, {{ $quote->getTotal() }})'>
												<i class="fa fa-plus"></i>
											</button>
										</td>
									</tr>
								@endforeach
							</table>
						@endif
					</div>
				</div>
			</div>
			<div class="bit-2">
				<div class="container">
					<div class="container_title orange">
						Payment assignments
					</div>
					<div class="container_content nop">
						<table class="data highlight">
							<tr>
								<th style='width: 100px'>Date</th>
								<th style='width: 120px;'>Quote number</th>
								<th style='width: 100%;'>Amount</th>
								<th style='width: 60px'>Delete</th>
							</tr>
							<tr ng-repeat='entry in entries'>
								<td><input type='text' ng-model='entry.date' style='width: 100%;'></td>
								<td><input type='text' ng-model='entry.quote' style='width: 100%;'></td>
								<td><input type='text' ng-model='entry.amount' class='euro' style='width: 100%;'></td>
								<td><button class="btn btn-red" ng-click='deletePayment(entry)'><i class="fa fa-trash"></i></button></td>
							</tr>
						</table>
						<div class="containerpadding">
							<table class='tlf' style='width: 360px; float: left;'>
								<tr>
									<td style='width: 33%;'><b>Non-cash + cash</b></td>
									<td style='width: 50px;' align='center'>-</td>
									<td style='width: 33%;'><b>Assigned</b></td>
									<td style='width: 50px;' align='center'>=</td>
									<td style='width: 33%;'><b>Remaining</b></td>
								</tr>
								<tr>
									<td colspan='2'><% getReceived() | currency %></td>
									<td colspan='2'><% getAssigned() | currency %></td>
									<td><b><% getRemaining() | currency %></b></td>
								</tr>
							</table>
							<button class='btn btn-green fr' ng-click='addPayment();'><i class="fa fa-plus"></i> New payment assignment</button>
							<br clear='both'>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="frame">
			<div class="bit-4" style='float: right !important;'>
				<div class="container">
					<div class="container_title green">
						Done?
					</div>
					<div class="container_content">
						<button class="button btn btn-green fw" id='saveBtn' ng-click='save()' style='height: 40px;'><i class="fa fa-check"></i> Save and create payment</button>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop