@extends('master')

<?php
	use App\Models\variables\VAT;
	use App\Models\variables\JobStatus ;  
?>
@section('page_name')
	List reports
@stop

@section('scripts')
	<script>
	var app = angular.module('app', []);

	app.config(function($interpolateProvider) {
		$interpolateProvider.startSymbol('<%').endSymbol('%>');
	});

	app.controller('OfficialReportCtrl', function($scope) {
		$scope.reportType = '';

		$scope.vat_values = [];
		$scope.jobstatus_values = [];

		$scope.readonly = false;
		$scope.newsletter = false;

		$scope.filter_unpaid = false;

		$scope.csvReports = [
			'invoices_by_date',
			'invoices_by_number',
			'newsletter_export',
			'receipts_report',
			'customer_report'
		];

		$scope.reportOptions = {
			newsletter_export: [
					// No options
			],
			receipts_report: [
				'date_range'
			],
			invoices_by_date: [
				'date_range',
				'tax_values',
				'with_products'
			],
			invoices_by_number: [
				'number_range',
				'tax_values',
				'with_products'
			],
			quotes_by_date: [
				'date_range',
				'jobstatus_values',
				'filter_unpaid'
			],
			quotes_by_number: [
				'number_range',
				'jobstatus_values',
				'filter_unpaid'
			],
			creditnotes_by_date: [
				'date_range',
				'tax_values',
				'with_products'
			],
			advertising_new_clients: [
				'date_range'
			],
			advertising_all_clients: [
				'date_range'
			],
			advertising_completed_jobs: [
				'date_range'
			],
			customer_report: [
				'date_range',
				'newsletter',
				'readonly'
			]
		};

		$scope.generateReport = function(isCsv) {
			var csv = (isCsv) ? '&csv=1' : '';
			switch($scope.reportType) {
				case 'receipts_report':
					window.open('openReport?type=receipts_report&dateStart=' + $scope.date_start + '&dateEnd=' + $scope.date_end + csv);
				break;
				case 'newsletter_export':
					window.open('openReport?type=newsletter_export' + csv);
				break;
				case 'invoices_by_date':
					angular.forEach($scope.vat_values, function(vat, index) {
						window.open('openReport?type=invoices_by_date&dateStart=' + $scope.date_start + '&dateEnd=' + $scope.date_end + '&vatId=' + vat + csv);
					});
				break;
				case 'invoices_by_number':
					angular.forEach($scope.vat_values, function(vat, index) {
						window.open('openReport?type=invoices_by_number&numberStart=' + $scope.number_start + '&numberEnd=' + $scope.number_end + '&vatId=' + vat + csv);
					});
				break;
				case 'quotes_by_date':
					window.open('openReport?type=quotes_by_date&dateStart=' + $scope.date_start + '&dateEnd=' + $scope.date_end + '&statusses=' + $scope.jobstatus_values.join() + '&filter_unpaid=' + $scope.filter_unpaid);
				break;
				case 'quotes_by_number':
					window.open('openReport?type=quotes_by_number&numberStart=' + $scope.number_start + '&numberEnd=' + $scope.number_end + '&statusses=' + $scope.jobstatus_values.join() + '&filter_unpaid=' + $scope.filter_unpaid);
				break;
				case 'creditnotes_by_date':
					angular.forEach($scope.vat_values, function(vat, index) {
						window.open('openReport?type=creditnotes_by_date&dateStart=' + $scope.date_start + '&dateEnd=' + $scope.date_end + '&vatId=' + vat + csv);
					});
				break;
				case 'advertising_new_clients':
					window.open('openReport?type=advertising_new_clients&dateStart=' + $scope.date_start + '&dateEnd=' + $scope.date_end);
				break;
				case 'advertising_all_clients':
					window.open('openReport?type=advertising_all_clients&dateStart=' + $scope.date_start + '&dateEnd=' + $scope.date_end);
				break;
				case 'advertising_completed_jobs':
					window.open('openReport?type=advertising_completed_jobs&dateStart=' + $scope.date_start + '&dateEnd=' + $scope.date_end);
				break;
				case 'customer_report':
					window.open('openReport?type=customer_report&dateStart=' + $scope.date_start + '&dateEnd=' + $scope.date_end + '&readonly=' + $scope.readonly + '&newsletter=' + $scope.newsletter + csv);
				break;
			}
		};

		$scope.toggleVatValue = function(event) {
			var vat = event.target.value;
			if ($scope.vat_values.indexOf(vat) == -1) {
				$scope.vat_values.push(vat);
			} else {
				$scope.vat_values.splice($scope.vat_values.indexOf(vat), 1);
			}
		};

		$scope.toggleJobStatusCheckbox = function(event) {
			var status = event.target.value;
			if ($scope.jobstatus_values.indexOf(status) == -1) {
				$scope.jobstatus_values.push(status);
			} else {
				$scope.jobstatus_values.splice($scope.jobstatus_values.indexOf(status), 1);
			}
		};
	});

	app.controller('CustomerReportCtrl', function($scope) {
		$scope.reportType = '';

		$scope.generateReport = function() {
			switch($scope.reportType) {
				case 'customers_with_cif':
					window.open('openReport?type=customers_with_cif');
				break;
				case 'customers_without_cif':
					window.open('openReport?type=customers_without_cif');
				break;
			}
		};
	});
	</script>
@stop

@section('content')
	<div class="frame" ng-app='app'>
		<div class="bit-3" ng-controller='OfficialReportCtrl'>
			<div class="container">
				<div class="container_title blue">
					Official Reports
				</div>
				<div class="container_content" style='overflow: auto;'>
					<p>First please select a report type. After this certain options will appear. Click the button to generate the </p>
					<br>
					<table class='form'>
						<tr>
							<td style='width: 150px;'>Report type:</td>
							<td style='width: 100%;'>
							
								<select style='width: 100%;' name="reportType" id="reportType" class='select2' ng-model='reportType' placeholder='Please select a report type...'>
									<option value=''></option>
									<option value="customer_report">Customer report</option>
									<option value="receipts_report">Receipts report</option>
									<option value="newsletter_export">Newsletter export</option>
									<option value="invoices_by_date">Invoices by date</option>
									<option value="invoices_by_number">Invoices by number</option>
									<option value="quotes_by_date">Quotes by date</option>
									<option value="quotes_by_number">Quotes by number</option>
									<option value="creditnotes_by_date">Credit notes by date</option>
									<option value="advertising_new_clients">Advertising new clients</option>
									<option value="advertising_all_clients">Advertising all clients</option>
									<option value="advertising_completed_jobs">Advertising completed jobs</option>
								</select>
							</td>
						</tr>
					</table>
					<div ng-show='reportOptions[reportType].indexOf("date_range") == 0'>
						<table class='form'>
							<tr>
								<td style='width: 150px;'>Date start</td>
								<td style='width: 100%;'><input type='text' class='basinput date' ng-model='date_start'></td>
							</tr>
							<tr>
								<td>Date end</td>
								<td><input type='text' class='basinput date' ng-model='date_end'></td>
							</tr>
						</table>
					</div>
					<div ng-show='reportOptions[reportType].indexOf("number_range") > -1'>
						<table class='form'>
							<tr>
								<td style='width: 150px;'>Number start</td>
								<td style='width: 100%;'><input type='text'  ng-model='number_start'></td>
							</tr>
							<tr>
								<td>Number end</td>
								<td><input type='text' ng-model='number_end'></td>
							</tr>
						</table>
					</div>
					<div ng-show='reportOptions[reportType].indexOf("tax_values") > -1'>
						<br>
						<p>Please select the tax values that you want reports for. You will get 1 report per tax value.</p>
						@foreach(VAT::all() as $vat)
							<input type='checkbox' ng-click='toggleVatValue($event)' value='{{ $vat->id }}'> {{ $vat->value }}<br>
						@endforeach
					</div>
					<div ng-show='reportOptions[reportType].indexOf("filter_unpaid") > -1'>
						<br>
						<input type='checkbox' ng-model='filter_unpaid'> Only show unpaid quotes
					</div>
					<div ng-show='reportOptions[reportType].indexOf("readonly") > -1'>
						<br>
						<input type='checkbox' ng-model='readonly'> Exclude readonly customers
					</div>
					<div ng-show='reportOptions[reportType].indexOf("newsletter") > -1'>
						<br>
						<input type='checkbox' ng-model='newsletter'> Exclude newsletter unsubscribed customers
					</div>
					<div ng-show='reportOptions[reportType].indexOf("jobstatus_values") > -1'>
						<br>
						<p>Please select the job statusses that you want in the report.</p>
						@foreach(JobStatus::all() as $status)
							<input type='checkbox' ng-click='toggleJobStatusCheckbox($event)' value='{{ $status->id }}'> {{ $status->type }}<br>
						@endforeach
					</div>
					<br>
					<button class="btn btn-green fr" ng-show='reportType != "" && reportType != "newsletter_export" && reportType != "receipts_report" && reportType != "customer_report"' ng-click='generateReport(false)'>
						<i class="fa fa-gear"></i> Generate PDF report
					</button>
					<button class="btn btn-default fr" style='margin-right: 5px;' ng-show='csvReports.indexOf(reportType) !== -1' ng-click='generateReport(true)'>
						<i class="fa fa-file-excel-o"></i> Export to CSV
					</button>
					<br clear='both'>
				</div>
			</div>
		</div>


		<!-- ============================================================================ -->
		<!-- ============================================================================ -->
		<!-- ============================================================================ -->
		<!-- ============================================================================ -->
		<!-- ============================================================================ -->
		<!-- ============================================================================ -->
		<!-- ============================================================================ -->
		<!-- ============================================================================ -->


		<div class="bit-3" ng-controller='CustomerReportCtrl'>
			<div class="container">
				<div class="container_title blue">
					Customer Reports
					<% scope %>
				</div>
				<div class="container_content" style='overflow: visible;'>
					<p>Various customer reports can be found here.</p>
					<br>
					<table class='form'>
						<tr>
							<td style='width: 150px;'>Report type:</td>
							<td style='width: 100%;'>
							
								<select style='width: 100%;' name="reportType" class='select2' id="reportType" ng-model='reportType' placeholder='Please select a report type...'>
									<option value=''></option>
									<option value="customers_with_cif">Customers with CIF number</option>
									<option value="customers_without_cif">Customers without CIF number</option>
								</select>
							</td>
						</tr>
					</table>
					<br>
					<button class="btn btn-green fr" ng-show='reportType != ""' ng-click='generateReport()'>
						<i class="fa fa-gear"></i> Generate report
					</button>
					<br clear='both'>
				</div>
			</div>
		</div>
	</div>
@stop