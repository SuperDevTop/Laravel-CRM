@extends('master')
@section('page_name')
	User groups
@stop

@section('breadcrumbs')
	System <i class="breadcrumb"></i> Manage User Groups
@stop

@section('stylesheets')
	<style>
		.selectAll {
			font-size: 10pt;
			color: blue;
			cursor: pointer;
			text-decoration: underline;
		}

		.permissionGroup {
			margin-left: 25px;
		}
	</style>
@stop

@section('scripts')
	<script src='https://ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular.js'></script>
	<script>
		var app = angular.module('app', [])
		.config(function($interpolateProvider){
		        $interpolateProvider.startSymbol('<%').endSymbol('%>');
		    }
		);

		app.controller('UserGroupController', function($rootScope, $scope) {
			$scope.permissions = {};
			$scope.openGroupPermissions = function(groupId) {
				ajaxRequest(
					'get_user_group_permissions',
					{
						'groupId': groupId
					},
					function(data) {
						$scope.permissions = data.permissions;

						if ($scope.permissions.length == 0) {
							$scope.permissions = {
								
							};
						}

						$scope.$apply();

						$scope.currentGroup = groupId;

						$('.groupPermissions').fadeIn(150);
					}
				);
			};

			$scope.savePermissions = function() {
				ajaxRequest(
					'save_user_group_permissions',
					{
						groupId: $scope.currentGroup,
						'permissions': $scope.permissions
					},
					function(data) {
						notify('Group permissions saved succesfully');
					}
				);
			};

			$scope.deleteGroup = function(groupId) {
				confirmDialog(
					'Are you sure?', 
					'Are you sure you want to delete this user group and all it\'s permissions?',
					function() {
						ajaxRequest(
							'delete_user_group',
							{
								'groupId': groupId
							},
							function(data) {
								if (data.success) {
									notify('User Group succesfully deleted!');
								} else {
									showError('Could not delete User Group. The group is not empty. A User Group can only be deleted if the group is empty!');
								}
							}
						);
					}
				);
			};

			$scope.saveNewGroup = function() {
				ajaxRequest(
					'new_user_group',
					{
						groupName: $scope.newgroupname
					},
					function(data) {
						if (data.success) {
							location.reload();
						} else {
							showError(data.error);
						}
					}
				);
			};
		});

		function selectAll(link) {
			$(link).parent().next('div').find('input[type="checkbox"]').trigger('click');
		}
	</script>
@stop

@section('content')
	<div class="frame" ng-app='app' ng-controller='UserGroupController as ugCtrl' ng-init='permissions=[]'>
		<div class="bit-2">
			<div class="container">
				<div class="container_title blue">
					Manage User Groups
				</div>
				<div class="container_content nop">
					<div class="containerpadding">
						<input type="text" ng-model='newgroupname' placeholder='Name for new group...' style='width: 150px;' class='fl'> <button class="btn btn-green fl ml10	" ng-click='saveNewGroup()'><i class="fa fa-check"></i> Create group</button>
					</div>
					<table class="data highlight">
						<tr>
							<th style='width: 100%;'>Group name</th>
							<th style='width: 100px;'>User count</th>
							<th style='width: 90px;'>Actions</th>
						</tr>
						@foreach(UserGroup::where('id', '!=', '0')->get() as $userGroup)
						<tr>
							<td>{{ $userGroup->name }}</td>
							<td>{{ $userGroup->getMemberCount() }}</td>
							<td>
								<button class='btn btn-default btn-square' ng-click='openGroupPermissions({{ $userGroup->id }})'>
									<i class="fa fa-edit"></i>
								</button>
								<button class="btn btn-red btn-square" ng-click='deleteGroup({{ $userGroup->id }})'>
									<i class="fa fa-trash"></i>
								</button>
							</td>
						</tr>
						@endforeach
					</table>
				</div>
			</div>
		</div>
		<div class="bit-2">
			<div class="container hide groupPermissions">
				<div class="container_title blue">
					Manage Group Permissions
				</div>
				<div class="container_content" style='max-height: initial;'>
					<div style='width: 350px; float: left;'>
						<h2>System permissions <span class='selectAll' onClick='selectAll(this)'>(Toggle All)</span></h2>
						<div class='permissionGroup'>
							<input type='checkbox' ng-model='permissions.manage_users'> Manage User Permissions<br>
							<input type='checkbox' ng-model='permissions.manage_user_groups'> Manage User Groups<br>
							<input type='checkbox' ng-model='permissions.edit_user_group'> Edit User Group<br>
							<input type='checkbox' ng-model='permissions.manage_system_variables'> Manage System Variables<br>
							<input type='checkbox' ng-model='permissions.edit_template_settings'> Template Settings<br>
							<input type='checkbox' ng-model='permissions.edit_system_settings'> System Settings<br>
						</div>
						
						<br>
						<h2>User permissions <span class='selectAll' onClick='selectAll(this)'>(Toggle All)</span></h2>
						<div class='permissionGroup'>
							<input type='checkbox' ng-model='permissions.user_login'> Login<br>
							<input type='checkbox' ng-model='permissions.user_edit_avatar'> Edit Avatar<br>
							<input type='checkbox' ng-model='permissions.user_change_password'> Change Password<br>
						</div>

						<br>
						<h2>Customer permissions <span class='selectAll' onClick='selectAll(this)'>(Toggle All)</span></h2>
						<div class='permissionGroup'>
							<input type='checkbox' ng-model='permissions.customer_view'> View Customers<br>
							<input type='checkbox' ng-model='permissions.customer_create'> Create Customers<br>
							<input type='checkbox' ng-model='permissions.customer_edit'> Edit Customers<br>
							<input type='checkbox' ng-model='permissions.customer_delete'> Delete Customers<br>
							<input type='checkbox' ng-model='permissions.customer_quote'> Quote Customers<br>
							<input type='checkbox' ng-model='permissions.customer_invoice'> Invoice Customers<br>
							<input type='checkbox' ng-model='permissions.customer_history'> View / edit contact history<br>
						</div>

						<br>
						<h2>Product permissions <span class='selectAll' onClick='selectAll(this)'>(Toggle All)</span></h2>
						<div class='permissionGroup'>
							<input type='checkbox' ng-model='permissions.product_view'> View Products<br>
							<input type='checkbox' ng-model='permissions.product_create'> Create Products<br>
							<input type='checkbox' ng-model='permissions.product_edit'> Edit Products<br>
							<input type='checkbox' ng-model='permissions.product_delete'> Delete Products<br>
						</div>

						<br>
						<h2>Supplier permissions <span class='selectAll' onClick='selectAll(this)'>(Toggle All)</span></h2>
						<div class='permissionGroup'>
							<input type='checkbox' ng-model='permissions.supplier_view'> View Suppliers<br>
							<input type='checkbox' ng-model='permissions.supplier_create'> Create Suppliers<br>
							<input type='checkbox' ng-model='permissions.supplier_edit'> Edit Suppliers<br>
							<input type='checkbox' ng-model='permissions.supplier_delete'> Delete Suppliers<br>
						</div>
					</div>

					<div style='float: left;'>
						<h2>Payment permissions <span class='selectAll' onClick='selectAll(this)'>(Toggle All)</span></h2>
						<div class='permissionGroup'>
							<input type='checkbox' ng-model='permissions.direct_debit_create'> Create Direct Debit Job<br>
							<input type='checkbox' ng-model='permissions.direct_debit_list'> List Direct Debit Jobs<br>
							<input type='checkbox' ng-model='permissions.payment_create'> Create Payment<br>
							<input type='checkbox' ng-model='permissions.payment_list'> List payments<br>
						</div>
						
						<br>
						<h2>Renewal permissions <span class='selectAll' onClick='selectAll(this)'>(Toggle All)</span></h2>
						<div class='permissionGroup'>
							<input type='checkbox' ng-model='permissions.renewal_create'> Create renewals<br>
							<input type='checkbox' ng-model='permissions.renewal_list_customer'> List customer renewals<br>
							<input type='checkbox' ng-model='permissions.renewal_list_due'> List renewals due<br>
							<input type='checkbox' ng-model='permissions.renewal_quote'> Quote renewals<br>
							<input type='checkbox' ng-model='permissions.renewal_cancel'> Cancel renewals<br>
						</div>

						<br>
						<h2>Report permissions <span class='selectAll' onClick='selectAll(this)'>(Toggle All)</span></h2>
						<div class='permissionGroup'>
							<input type='checkbox' ng-model='permissions.reports_listed'> View listed reports<br>
							<input type='checkbox' ng-model='permissions.reports_visual'> Generate visual reports<br>
						</div>
					</div>
					<br clear='both'>
					<button class='btn btn-green fr' ng-click='savePermissions()'>
						<i class="fa fa-check"></i>
						Save permissions
					</button>
					<br clear='both'>
				</div>
			</div>
		</div>
	</div>
@stop