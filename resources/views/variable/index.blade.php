@extends('master')
@section('breadcrumbs')
	System <i class="breadcrumb"></i> Manage System Variables
@stop

<?php
	use Illuminate\Support\Facades\Request;
	use Illuminate\Support\Facades\Response;
	
	use App\Models\variables\AdType;
	use App\Models\variables\ProductCategory;
	use App\Models\variables\CustomerCreditRating;
	use App\Models\variables\JobStatus;
	use App\Models\variables\PayMethod;
	use App\Models\variables\CallOutFee;
	use App\Models\variables\VAT;
	use App\Models\variables\CompanyRole;
	use App\Models\variables\PaymentTerm;
	use App\Models\variables\CustomerType;
	use App\Models\variables\Sector;
	use App\Models\ExpensePayment;
	use App\Models\Payment;
	use App\Models\invoices\Invoice;
	
	use app\Models\Customer;
	use app\Models\Quote;
?>

@section('scripts')
	<script>
		var app = angular.module('app', []);

		app.config(function($interpolateProvider) {
			$interpolateProvider.startSymbol('<%').endSymbol('%>');
		});

		app.controller('variableCtrl', function($scope, $compile) {
			$scope.currentCategory = null;
			$scope.$compile = $compile;

			$scope.categories = {{ json_encode([
					['Advertisement Types', AdType::orderBy('id')->get()],
					['Product Categories', ProductCategory::orderBy('id')->get()],
					['Customer Credit Ratings', CustomerCreditRating::orderBy('id')->get()],
					['Job Statusses', JobStatus::orderBy('id')->get()],
					['Payment Methods', PayMethod::orderBy('id')->get()],
					['Call Out Fees', CallOutFee::orderBy('id')->get()],
					['VAT values', VAT::orderBy('id')->get()],
					['Company Roles', CompanyRole::orderBy('id')->get()],
					['Payment Terms', PaymentTerm::orderBy('id')->get()],
					['Customer Types', CustomerType::orderBy('id')->get()],
					['Customer Sectors', Sector::orderBy('id')->get()]
				])}};

			$scope.categoryFields = {
				'Customer Credit Ratings': {
					label: 'Abbreviation',
					name: 'abbreviation'
				},
				'Payment Methods': {
					label: 'Commission',
					name: 'commission'
				},
				'Call Out Fees': {
					label: 'Price',
					name: 'price'
				},
				'VAT values': {
					label: 'Value',
					name: 'value'
				}
			};

			$scope.unfoldCategory = function(category) {
				$scope.currentCategory = category;
			};

			$scope.editVariable = function(variable) {
				$scope.editingVariable = variable;

				openModal('Edit variable', $('#edit_variable'), 600, false);
			};

			$scope.deleteVariable = function(variable) {
				confirmDialog(
					'Are you sure?',
					'Are you sure you want to delete this variable<br><br>Before deleting the variable, Pepper will check if the variable is used anywhere.',
					function() {
						$.ajax({
							url: 'variables/delete',
							method: 'POST',
							dataType: 'JSON',
							data: {
								type: $scope.currentCategory[0],
								id: variable.id
							},
							success: function(data) {
								if (data.success) {
									$scope.currentCategory[1].splice($scope.currentCategory[1].indexOf(variable), 1);
									$scope.$apply();
									showSuccess('Variable deleted');
								} else {
									showError(data.msg);
								}
							}
						});
					}
				);
			};

			$scope.openNewVariablePopup = function() {
				$scope.newVariable = {
					type: '',
					value: ''
				};

				openModal('New variable', $('#create_variable'), 600, false);
			};

			$scope.saveNewVariable = function() {
				ajaxRequest(
					'save_new_system_variable',
					{
						category: $scope.currentCategory[0],
						variable: $scope.newVariable
					},
					function(data) {
						if (data.success) {
							$scope.newVariable.id = data.varId;
							$scope.currentCategory[1].push($scope.newVariable);
							$scope.$apply();

							closeCurrentModal();
							showSuccess('Variable saved succesfully');
						} else
							showError('Something went wrong while saving the variable. Please try again.');
					}
				);
			};

			$scope.saveVariable = function() {
				ajaxRequest(
					'save_existing_system_variable',
					{
						category: $scope.currentCategory[0],
						variable: $scope.editingVariable
					},
					function(data) {
						if (data.success) {
							closeCurrentModal();
							showSuccess('Variable saved succesfully');
						} else
							showError('Something went wrong while saving the variable. Please try again.');
					}
				);
			};

			// This prevents managing default variables
			$scope.isManageable = function(variable) {
				switch($scope.currentCategory[0]) {
					case 'Job Statusses':
					  	return (variable.id > 5);
					break;
					case 'Payment Methods':
					  	return (variable.id > 4);
					break;
					case 'Advertisement Types':
					case 'Customer Credit Ratings':
					case 'VAT values':
					case 'Company Roles':
					case 'Payment Terms':
					case 'Customer Types':
					case 'Customer Sectors':
					  	return (variable.id > 1);
				   	break;
				   	default:
				   		return true;
				   	break;
				}
			};
		});
	</script>
@stop

@section('content')
	<div class="frame" ng-app='app' ng-controller='variableCtrl'>
		<div class="modal_content" id="create_variable">
			<table class="form">
				<tr>
					<td style='width: 150px;'>Name</td>
					<td style='width: 100%;'><input type='text' ng-model='newVariable.type'></td>
				</tr>
				<tr ng-show='categoryFields.hasOwnProperty(currentCategory[0])'>
					<td style='width: 150px;'><% categoryFields[currentCategory[0]].label %></td>
					<td style='width: 100%;'><input type='text' ng-model='newVariable[categoryFields[currentCategory[0]].name]'></td>
				</tr>
				<tr ng-show='currentCategory[0] == "VAT values"'>
					<td style='width: 150px;'>Description</td>
					<td style='width: 100%;'><textarea ng-model='newVariable.description'></textarea></td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button class="btn btn-green fr ml10" ng-click='saveNewVariable()'><i class="fa fa-save"></i> Save new variable</button>
						<button class="btn btn-red fr" onClick='closeCurrentModal()'><i class="fa fa-remove"></i> Cancel</button>
					</td>
				</tr>
			</table>
		</div>
		<div class="modal_content" id="edit_variable">
			<table class="form">
				<tr>
					<td style='width: 150px;'>Name</td>
					<td style='width: 100%;'><input type='text' ng-model='editingVariable.type'></td>
				</tr>
				<tr ng-show='categoryFields.hasOwnProperty(currentCategory[0])'>
					<td style='width: 150px;'><% categoryFields[currentCategory[0]].label %></td>
					<td style='width: 100%;'><input type='text' ng-model='editingVariable[categoryFields[currentCategory[0]].name]'></td>
				</tr>
				<tr ng-show='currentCategory[0] == "VAT values"'>
					<td style='width: 150px;'>Description</td>
					<td style='width: 100%;'><textarea ng-model='editingVariable.description'></textarea></td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button class="btn btn-green fr ml10" ng-click='saveVariable()'><i class="fa fa-save"></i> Save variable</button>
						<button class="btn btn-red fr" onClick='closeCurrentModal()'><i class="fa fa-remove"></i> Cancel</button>
					</td>
				</tr>
			</table>
		</div>
		<div class="bit-3">
			<div class="container">
				<div class="container_title blue">
					<i class="fa fa-list-ul"></i> System variables
				</div>
				<div class="container_content nop">
					<table class='data smallrows'>
						<tr>
							<th style='width: 100%'>Variable type</th>
							<th style='width: 80px;'>Count</th>
							<th style='width: 60px;'>Actions</th>
						</tr>
						<tr ng-repeat='category in categories'>
							<td><% category[0] %></td>
							<td><% category[1].length %></td>
							<td>
								<button ng-click='unfoldCategory(category)' class="btn btn-default btn-square" title="View variables">
									<i class="fa fa-eye"></i>
								</button>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div class="bit-3" ng-show='currentCategory'>
			<div class="container">
				<div class="container_title blue">
					Manage <% currentCategory[0] %> <button class="btn btn-green btn-square fr" ng-click='openNewVariablePopup()'><i class="fa fa-plus"></i></button>
				</div>
				<div class="container_content nop">
					<table class="data highlight">
						<tr>
							<th style='width: 100%;'>Variable</th>
							<th style='width: 70px;'>Actions</th>
						</tr>
						<tr ng-repeat='variable in currentCategory[1] | orderBy: "type"'>
							<td><% variable.type %></td>
							<td>
								<button class="btn btn-orange btn-square" ng-click='editVariable(variable)' ng-show='isManageable(variable)'><i class="fa fa-edit"></i></button>
								<button class="btn btn-red btn-square" ng-click='deleteVariable(variable)' ng-show='isManageable(variable)'><i class="fa fa-remove"></i></button>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
@stop