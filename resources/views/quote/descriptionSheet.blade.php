@section('page_name')
	Quote Description Sheet for 
@stop

@section('breadcrumbs')
	<a href="/customers">Customers</a> <i class="breadcrumb"></i> <a href='/customers/{{ $customer->id }}'>{{ $customer->getCustomerName() }}</a> <i class="breadcrumb"></i> Quote #{{ $quote->id }} <i class="breadcrumb"></i> Quote Description Sheet
@stop

@section('scripts')
	<script src='/js/ui-sortable.js'></script>
	<script>
	var app = angular.module('app', ['ui.sortable']);

	app.config(function($interpolateProvider) {
		$interpolateProvider.startSymbol('<%').endSymbol('%>');
	});

	app.controller('sheetCtrl', function($scope) {
		$scope.rows = [];

		@if ($quote->getDescriptionRows)
			$scope.rows = {{ json_encode(QuoteDescriptionRow::where('quoteId', '=', $quote->id)->orderBy('position', 'ASC')->get()) }};
		@endif

		$scope.selectPhoto = function(row, image) {
			openMediaGallery(function(item) {
				row[((image == 1) ? 'image1' : 'image2')] = $('#media_library #library_wrapper').scope().currentItem;
				$('#media_library #library_wrapper .close-btn').trigger('click');
				$scope.$apply();
			});
		};

		$scope.addRow = function() {
			$scope.rows.push({
				title: '',
				description: ''
			});
		};

		$scope.deleteRow = function(row) {
			confirmDialog(
				'Are you sure?',
				'Are you sure you want to delete this row?',
				function() {
					$scope.rows.splice($scope.rows.indexOf(row), 1);
					$scope.$apply();
				}
			);
		};

		$scope.saveSheet = function() {
			ajaxRequest(
				'save_quote_description_sheet',
				{
					quoteId: {{ $quote->id }},
					rows: $scope.rows
				},
				function(data) {
					if (data.success)
						location.assign('/quotes/{{ $quote->id }}/edit');
				}
			);
		};

		$scope.discardChanges = function() {
			location.assign('/quotes/{{ $quote->id }}/edit');
		}

		$scope.deleteImage = function(row, number) {
			confirmDialog(
				'Are you sure?',
				'Are you sure you want to delete this image from the quote description page?',
				function() {
					if (number == 1)
						delete row.image1;
					else
						delete row.image2;
					$scope.$apply();
				}
			);
		};
	});
	</script>
@stop

@section('stylesheets')
	<style>
		.row .bit-2:first-child {
			padding-left: 0;
		}

		.row .bit-2:last-child {
			padding-right: 0;
		}

		.row .placeholder {
			width: 100%;
			height: 200px;

			text-align: center;

			font-size: 21px;

			background-color: #D5D5D5;

			padding-top: 50px;

			cursor: pointer;
		}

		.row img {
			max-width: 100%;
			max-height: 200px;
		}

		.row .placeholder:hover {
			color: #1D8F28;
		}

		.row .placeholder i.fa {
			font-size: 72px;
		}
	</style>
@stop

@section('content')
	<div class="frame" ng-app='app' ng-controller='sheetCtrl'>
		<div class="bit-1 tac" ng-show='rows.length == 0'>
			<h2><i class="fa fa-frown-o" style='font-size: 60px;'></i><br>You don't have any rows in this sheet. Click the green button to add one!</h2>
		</div>
		<div ui-sortable ng-model='rows'>
			<div class="bit-1" ng-repeat='row in rows'>
				<div class="container">
					<div class="container_title blue">
						<input type='text' ng-model='row.title' placeholder='Title...' style='width: 90%;'>
						<i class="fa fa-arrows fr mt10" style='font-size: 18px;'></i>
					</div>
					<div class="container_content">
						<div class="row">
							<div>
								<div class="bit-2">
									<div class="placeholder" ng-hide='row.image1' ng-click='selectPhoto(row, 1)'>
										<i class="fa fa-picture-o"></i>
										<br>
										Click here to add a photo
									</div>
									<center>
										<img ng-show='row.image1' ng-src='<% row.image1.url %>' ng-click='selectPhoto(row, 1)'><br>
										<button class="btn btn-red" ng-show='row.image1' ng-click='deleteImage(row, 1)'><i class="fa fa-remove"></i> Delete</button>
									</center>
								</div>
								<div class="bit-2">
									<div class="placeholder" ng-hide='row.image2' ng-click='selectPhoto(row, 2)'>
										<i class="fa fa-picture-o"></i>
										<br>
										Click here to add a photo
									</div>
									<center>
										<img ng-show='row.image2' ng-src='<% row.image2.url %>' ng-click='selectPhoto(row, 2)'><br>
										<button class="btn btn-red" ng-show='row.image2' ng-click='deleteImage(row, 2)'><i class="fa fa-remove"></i> Delete</button></center>
								</div>
							</div>
							<textarea placeholder='Description...' ng-model='row.description'></textarea>
							<br clear='both'>
							<button class="btn btn-red fr mt10" ng-click='deleteRow(row)'><i class="fa fa-remove"></i> Delete row</button>
							<br clear='both'>
						</div>
					</div>
				</div>
			</div>
		</div>
		<button class="btn btn-green fr mr10" ng-click='saveSheet()'><i class="fa fa-save"></i> Save changes</button>
		<button class="btn btn-green fr mr10" ng-click='addRow()'><i class="fa fa-plus"></i> Add row</button>
		<button class="btn btn-red fr mr10" ng-click='discardChanges()'><i class="fa fa-remove"></i> Cancel</button>
	</div>
@stop