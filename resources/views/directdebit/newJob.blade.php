@extends('master')
@section('page_name')
	New Direct Debit Job
@stop

@section('breadcrumbs')
	Direct Debits <i class="breadcrumb"></i> New job
@stop

@section('scripts')
	<script type='text/javascript' src='js/maskedinput.min.js'></script>
	<script src='https://ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular.js'></script>
	<script>
		var app = angular.module('app', [])
		.config(function($interpolateProvider){
		        $interpolateProvider.startSymbol('<%').endSymbol('%>');
		    }
		);

		app.controller('DirectDebitController', function($rootScope, $scope, $http) {
			$scope.jobInvoices = [];
			$scope.queueInvoices = [];
			$scope.jobDescription = '';
			$scope.debitDate = '';

			// First, receive data needed
			// This is provisional job ID, and available invoices that are in the queue
			$http.post(
				'ajax/get_new_direct_debit_job_data',
				{

				}
			).success(function(data) {
				$scope.provisionalJobId = data.provisionalJobId;
				$scope.queueInvoices = data.queueInvoices;
			});

			$scope.switchToJob = function(invoice) {
				var invoiceCopy = invoice;
				$scope.queueInvoices.splice($scope.queueInvoices.indexOf(invoice), 1);
				$scope.jobInvoices.push(invoiceCopy);
			};

			$scope.switchToQueue = function(invoice) {
				var invoiceCopy = invoice;
				$scope.jobInvoices.splice($scope.jobInvoices.indexOf(invoice), 1);
				$scope.queueInvoices.push(invoiceCopy);
			};

			$scope.getJobTotal = function() {
				var total = 0;

				angular.forEach($scope.jobInvoices, function(invoice) {
					total = total + parseFloat(invoice.total);
				});
				console.log(total);
				return total;
			};

			$scope.checkDataIntegrity = function() {
				// Send the selected invoices to the server, and request validation of the data. Server side validation will make sure all data is correct for the xml file generation
				// First, generate an array of invoice id's
				var invoiceIds = [];
				angular.forEach($scope.jobInvoices, function(invoice) {
					invoiceIds.push(invoice.id);
				});

				// Send validation request...
				ajaxRequestHtml(
					'validate_direct_debit_data',
					{
						description: $scope.jobDescription,
						debitDate: $scope.debitDate,
						invoices: invoiceIds
					},
					function(data) {
						// Open window with integrity information
						var w = window.open("", "", "width=1200, height=700");
						console.log(w);
						w.document.write(data);
					}
				);
				return true;
			};

			$scope.createDirectDebitFile = function() {
				// Validation...
				if ($scope.jobInvoices.length == 0) {
					showError('Could not save direct-debit job.<br><br>You haven\'t added any invoices to the selected invoices!');
					return;
				}

				if ($scope.getJobTotal() == 0) {
					showError('Could not save direct-debit job.<br><br>The sum of the invoices is €0.00!<br><br>You can\'t have a direct-debit job with €0.00 total!');
					return;
				}

				if ($scope.jobDescription == '') {
					showError('Could not save direct-debit job.<br><br>The job description cannot be empty!');
					return;
				}

				if ($scope.debitDate == '') {
					showError('Could not save direct-debit job.<br><br>You have to fill in the debit date!');
					return;
				}

				//Send the data to the server, and redirect to the generated XML
				// First, generate an array of invoice id's
				var invoiceIds = [];
				angular.forEach($scope.jobInvoices, function(invoice) {
					invoiceIds.push(invoice.id);
				});

				// Make the request
				ajaxRequest(
					'save_direct_debit_job',
					{
						description: $scope.jobDescription,
						debitDate: $scope.debitDate,
						invoices: invoiceIds
					},
					function(data) {
						if (data.success) {
							window.open('ddGetSepaXml/' + data.jobId);
							location.assign('/ddIndex');
						} else {
							showError(data.msg);
						}
					}
				);
			};

			$scope.deleteInvoiceFromQueue = function(invoice) {
				confirmDialog(
					'Are you sure?',
					'Are you sure you want to delete this invoice from the direct-debit queue?',
					function() {
						ajaxRequest(
							'delete_invoice_from_direct_debit_queue',
							{
								detailId: invoice.id
							},
							function(data) {
								if (data.success) {
									$scope.queueInvoices.splice($scope.queueInvoices.indexOf(invoice), 1);
									notify('Invoice removed from queue');
									$scope.$apply();
								}
							}
						);	
					}
				);
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
	</script>
@stop

@section('content')
	<div ng-app='app' ng-controller='DirectDebitController'>
		<div class="frame">
			<div class="bit-3">
				<div class="container">
					<div class="container_title blue">
						Job details
					</div>
					<div class="container_content" style='height:160px;'>
						<table class="form">
							<tr>
								<td style='width: 160px;'>Job ID (Provisional)</td>
								<td style='width: 100%;'><input type='text' ng-model='provisionalJobId' style='width: 100px;' readonly disabled></td>
							</tr>
							<tr>
								<td>Job Description</td>
								<td><input type='text' ng-model='jobDescription'></td>
							</tr>
							<tr>
								<td>Debit Date</td>
								<td><input type='text' class='basinput date' ng-model='debitDate'></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div class="bit-3">
				<div class="container">
					<div class="container_title blue">
						Job totals
					</div>
					<div class="container_content" style='height:160px;'>
						<table class="form">
							<tr>
								<td style='width: 120px;'>Invoice count</td>
								<td style='width: 100%;'><input type='text' ng-value='jobInvoices.length' style='width: 120px;' readonly></td>
							</tr>
							<tr>
								<td>Total sum</td>
								<td><input type='text' ng-value='getJobTotal() | currency' style='width: 120px;' readonly></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div class="bit-3">
				<div class="container">
					<div class="container_title blue">
						Actions
					</div>
					<div class="container_content" style='height:160px;'>
						<button type='button' class="btn btn-default" style='width: 200px;' ng-click='checkDataIntegrity()'>
							<i class="fa fa-check"></i> Check data integrity
						</button>
						<br>
						<button type='button' class="btn btn-red" style='width: 200px;' ng-click='createDirectDebitFile()'>
							<i class="fa fa-dollar"></i> Create Direct Debit file
						</button>
					</div>
				</div>
			</div>
		</div>
		<br clear='both'>
		<div class="frame">
			<div class="bit-2">
				<div class="container">
					<div class="container_title red">
						Available Invoices
					</div>
					<div class="container_content nop">
						<table class="data highlight">
							<tr>
								<th style='width: 50px;'>Delete</th>
								<th style='width: 60px;'>#</th>
								<th style='width: 50%;'>Description</th>
								<th style='width: 50%;'>Customer</th>
								<th style='width: 100px;'>Bank charge</th>
								<th style='width: 110px;'>Total</t h>
								<th style='width: 50px;'>Add</th>
							</tr>
							<tr ng-repeat='invoice in queueInvoices'>
								<td>
									<button class="btn btn-red btn-square" ng-click='deleteInvoiceFromQueue(invoice)'>
										<i class="fa fa-trash-o"></i>
									</button>
								</td>
								<td><% invoice.invoice %></td>
								<td><% invoice.description %></td>
								<td><% invoice.customerName %></td>
								<td><% invoice.bankCharge | currency %></td>
								<td><% invoice.total | currency %></td>
								<td>
									<button class="btn btn-green btn-square" ng-click='switchToJob(invoice)'>
										<i class="fa fa-arrow-right"></i>
									</button>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div class="bit-2">
				<div class="container">
					<div class="container_title green">
						Selected Invoices
					</div>
					<div class="container_content nop">
						<table class="data highlight">
							<tr>
								<th style='width: 50px;'>Delete</th>
								<th style='width: 60px;'>#</th>
								<th style='width: 50%;'>Description</th>
								<th style='width: 50%;'>Customer</th>
								<th style='width: 100px;'>Bank charge</th>
								<th style='width: 110px;'>Total</th>
							</tr>
							<tr ng-repeat='invoice in jobInvoices'>
								<td>
									<button class="btn btn-orange btn-square" ng-click='switchToQueue(invoice)'>
										<i class="fa fa-arrow-left"></i>
									</button>
								</td>
								<td><% invoice.invoice %></td>
								<td><% invoice.description %></td>
								<td><% invoice.customerName %></td>
								<td><% invoice.bankCharge | currency %></td>
								<td><% invoice.total | currency %></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop