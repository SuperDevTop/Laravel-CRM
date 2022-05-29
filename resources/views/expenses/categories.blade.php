@extends('master')
@section('page_name')
	Manage expense categories
@stop

@section('scripts')
	<script type='text/javascript' src='/js/angularjs.min.js'></script>
	<script>
		var app = angular.module('app', [])
		.config(function($interpolateProvider) {
			$interpolateProvider.startSymbol('<%').endSymbol('%>');
		});

		app.controller('mainCtrl', function($scope, $compile) {
			// Get the expense categories...
			$scope.categories = {{ json_encode($categories) }};

			// We have to use the Angular compile service within the modal...
			$scope.$compile = $compile;

			$scope.openCategory = function(category) {
				$scope.currentCategory = category;

				$('.subCategories').fadeIn(100);
			};

			$scope.editCategory = function(category) {
				$scope.editingCategoryClone = angular.copy(category);
				$scope.editingCategory = category;

				console.info(category);

				openModal("Edit expense", $('#modal-edit-category'), 500, false);
			};

			$scope.toggleDisabled = function(category) {
				category.disabled = (category.disabled == 1) ? 0 : 1;
				console.log(category.disabled);
				/*ajaxRequest(
					'expense_category_toggle_disabled',
					{
						categoryId: category.id,
						disabled: category.disabled
					},
					function(data) {
						if (data.success) {
							if (category.disabled)
								showSuccess('Category disabled');
							else
								showSuccess('Category enabled');
						}
					}
				);*/
			}

			$scope.saveCategoryChanges = function() {
				var index = $scope.categories.indexOf($scope.editingCategory);

				ajaxRequest(
					'update_expense_category',
					$scope.editingCategoryClone,
					function(data) {
						if (data.success) {
							$scope.categories[index] = angular.copy($scope.editingCategoryClone);

							delete $scope.editingCategoryClone;

							closeCurrentModal();
							$scope.$apply();

							showSuccess('Expense category saved succesfully');
						} else {
							showError('Something went wrong while saving the expense category. Please try again.');
						}
					}
				);
			};

			$scope.cancelCategoryChanges = function() {
				delete $scope.editingCategoryClone;

				closeCurrentModal();
			};

			$scope.openNewSubCategoryModal = function() {
				$scope.newSubCategory = {
					category: $scope.currentCategory.id
				};

				openModal('Create new subcactegory', $('#modal-new-subcategory'), 500, false);
			};

			$scope.saveNewSubCategory =  function() {
				ajaxRequest(
					'create_new_expense_subcategory',
					$scope.newSubCategory,
					function(data) {
						if (data.success) {
							$scope.newSubCategory.id = data.categoryId;
							$scope.newSubCategory.accountingCode = data.accountingCode;
							$scope.currentCategory.subCategories.push(angular.copy($scope.newSubCategory));

							delete $scope.newSubCategory;
							closeCurrentModal();

							showSuccess('Expense subcategory created succesfully.');
							$scope.$apply();
						} else {
							showError('Something went wrong while creating the subcategory. Please try again.');
						}
					}
				);	
			};

			$scope.cancelNewSubCategory = function() {
				delete $scope.newSubCategory;

				closeCurrentModal();
			}

			$scope.openEditSubCategoryModal = function(subCategory) {
				$scope.editingSubCategoryClone = angular.copy(subCategory);
				$scope.editingSubCategory = subCategory;

				openModal("Edit subcategory", $('#modal-edit-subcategory'), 500, false);
			};

			$scope.saveSubCategoryChanges = function() {
				var index = $scope.currentCategory.subCategories.indexOf($scope.editingSubCategory);

				ajaxRequest(
					'update_expense_subcategory',
					$scope.editingSubCategoryClone,
					function(data) {
						if (data.success) {
							$scope.currentCategory.subCategories[index] = angular.copy($scope.editingSubCategoryClone);
							delete $scope.editingSubCategoryClone;
							closeCurrentModal();

							$scope.$apply();
							showSuccess('Changes saved succesfully');
						} else {
							showError('Something went wrong while saving your changes. Please try again.');
						}
					}
				);
			}

			$scope.cancelSubCategoryChanges = function() {
				delete $scope.editingSubCategoryClone;
				delete $scope.editingSubCategory;

				closeCurrentModal();
			}

			$scope.openNewCategoryModal = function() {
				$scope.newCategory = {
					subCategories: []
				};

				openModal('Create new category', $('#modal-new-category'), 500, false);
			};

			$scope.saveNewCategory = function() {

				ajaxRequest(
					'create_new_expense_category',
					$scope.newCategory,
					function(data) {
						if (data.success) {
							$scope.newCategory.id = data.categoryId;

							$scope.categories.push(angular.copy($scope.newCategory));
							delete $scope.newCategory;

							closeCurrentModal();
							$scope.$apply();

							showSuccess('Category created');
						} else {
							showError('An error occured while creating the expense category. Please try again.');
						}
					}
				);
			};

			$scope.cancelNewCategory = function() {
				delete $scope.newCategory;

				closeCurrentModal();
			};

			$scope.deleteSubCategory = function(subCategory) {
				confirmDialog(
					'Are you sure?',
					'Are you sure you want to delete this subcategory?<br><br>Please note, you can only delete subcategories that haven\'t been used in any expenses!',
					function() {
						ajaxRequest(
							'delete_expense_subcategory',
							{
								subCategoryId: subCategory.id
							},
							function(data) {
								if (data.success) {
									$scope.currentCategory.subCategories.splice($scope.currentCategory.subCategories.indexOf(subCategory), 1);
									$scope.$apply();
									showSuccess('Subcategory deleted');
								} else {
									showError(data.message);
								}
							}
						);
					}
				);
			};
		});
	</script>
@stop

@section('stylesheets')	
	<style>
		tr.active td {
			background-color: #D2FFAB;
		}
	</style>
@stop

@section('content')
	<div ng-app='app' ng-controller='mainCtrl'>
		<div class='modal_content' id='modal-edit-category'>
			<table class="form">
				<tr>
					<td style='width: 150px;'>English Description</td>
					<td style='width: 100%;'><input type="text" ng-model="editingCategoryClone.english"></td>
				</tr>
				<tr>
					<td>Spanish Description</td>

					<td><input type="text" ng-model="editingCategoryClone.spanish"></td>
				</tr>
				<tr>
					<td>Accounting code</td>
					<td><input type="text" ng-model="editingCategoryClone.accountingCode"></td>
				</tr>
				<tr>
					<td>Status</td>
					<td>
						<button class="btn" id='testbtn' ng-class="(editingCategoryClone.disabled == 1) ? 'btn-red' : 'btn-green'" ng-click='toggleDisabled(editingCategoryClone)'>
							Toggle
						</button>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button class="btn btn-green fr ml10" ng-click='saveCategoryChanges()'>
							<i class="fa fa-save"></i> Save changes
						</button>
						<button class="btn btn-grey fr" ng-click='cancelCategoryChanges()'>
							<i class="fa fa-remove"></i> Cancel changes
						</button>
					</td>
				</tr>
			</table>
		</div>
		<div class='modal_content' id="modal-new-subcategory">
			<table class="form">
				<tr>
					<td style='width: 150px;'>English Description</td>
					<td style='width: 100%;'><input type="text" ng-model="newSubCategory.english"></td>
				</tr>
				<tr>
					<td>Spanish Description</td>
					<td><input type="text" ng-model="newSubCategory.spanish"></td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button class="btn btn-green fr ml10" ng-click='saveNewSubCategory()'>
							<i class="fa fa-save"></i> Create subcategory
						</button>
						<button class="btn btn-grey fr" ng-click='cancelNewSubCategory()'>
							<i class="fa fa-remove"></i> Cancel
						</button>
					</td>
				</tr>
			</table>
		</div>
		<div class='modal_content' id="modal-edit-subcategory">
			<table class="form">
				<tr>
					<td style='width: 150px;'>English Description</td>
					<td style='width: 100%;'><input type="text" ng-model="editingSubCategoryClone.english"></td>
				</tr>
				<tr>
					<td>Spanish Description</td>
					<td><input type="text" ng-model="editingSubCategoryClone.spanish"></td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button class="btn btn-green fr ml10" ng-click='saveSubCategoryChanges()'>
							<i class="fa fa-save"></i> Save changes
						</button>
						<button class="btn btn-grey fr" ng-click='cancelSubCategoryChanges()'>
							<i class="fa fa-remove"></i> Cancel
						</button>
					</td>
				</tr>
			</table>
		</div>
		<div class="modal_content" id="modal-new-category">
			<table class="form">
				<tr>
					<td style='width: 150px;'>English Description</td>
					<td style='width: 100%;'><input type="text" ng-model="newCategory.english"></td>
				</tr>
				<tr>
					<td>Spanish Description</td>
					<td><input type="text" ng-model="newCategory.spanish"></td>
				</tr>
				<tr>
					<td>Accounting code</td>
					<td><input type="text" ng-model="newCategory.accountingCode"></td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button class="btn btn-green fr ml10" ng-click='saveNewCategory()'>
							<i class="fa fa-save"></i> Save category
						</button>
						<button class="btn btn-grey fr" ng-click='cancelNewCategory()'>
							<i class="fa fa-remove"></i> Cancel
						</button>
					</td>
				</tr>
			</table>
		</div>
		<div class="bit-2">
			<div class="container">
				<div class="container_title blue">
					Expense categories
					<button class="btn btn-green fr" ng-click='openNewCategoryModal()'><i class="fa fa-plus"></i></button>
				</div>
				<div class="container_content nop">
					<table class="data sortable">
						<thead>
							<tr>
								<th style='width: 100%;'>English description</th>
								<th style='width: 100px;'>ID</th>
								<th style='width: 120px;' data-sortable-no>Actions</th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat='category in categories' ng-class="{ 'active': category == currentCategory }">
								<td><% category.english %></td>
								<td><% category.accountingCode %></td>
								<td>
									<button class="btn btn-default btn" ng-click='openCategory(category)'>
										<i class="fa fa-eye"></i>
									</button>
									<button class="btn btn-orange btn" ng-click='editCategory(category)'>
										<i class="fa fa-edit"></i>
									</button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="bit-2 hide subCategories">
			<div class="container">
				<div class="container_title blue">
					Expense subcategories
					<button class="btn btn-green fr" ng-click='openNewSubCategoryModal()'><i class="fa fa-plus"></i></button>
				</div>
				<div class="container_content nop">
					<table class="data">
						<tr>
							<th style='width: 100%;'>English description</th>
							<th style='width: 100px;'>ID</th>
							<th style='width: 90px;'>Actions</th>
						</tr>
						<tr ng-repeat='sub in currentCategory.subCategories'>
							<td><% sub.english %></td>
							<td><% currentCategory.accountingCode + sub.accountingCode %></td>
							<td>
								<button class="btn btn-orange btn" ng-click='openEditSubCategoryModal(sub)'>
									<i class="fa fa-edit"></i>
								</button>
								<button class="btn btn-red btn" ng-click='deleteSubCategory(sub)'>
									<i class="fa fa-trash-o"></i>
								</button>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
@stop