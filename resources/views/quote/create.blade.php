@section('page_name')
	New quote for '{{ $customer->getCustomerName() }}'
@stop

@section('scripts')
	<script src='https://ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular.min.js'></script>
	<script>
		var app = angular.module('app', [])
		.config(function($interpolateProvider){
		        $interpolateProvider.startSymbol('<%').endSymbol('%>');
		    }
		);

		app.controller('QuoteController', function() {
			this.entries = [
				{
					product: 10,
					description: "Description here"
				},
				{
					product: 10,
					description: "Description here"
				},
				{
					product: 10,
					description: "Description here"
				},
				{
					product: 10,
					description: "Description here"
				},
			];
		});

		app.directive('chosen', function() {
			return {
				restrict: 'A',
				link: function(scope, element, attrs) {
					$(element).chosen()
					.change(function() {
						alert('test');
					});
				}
			};
		});
	</script>
@stop

@section('stylesheets')
	<style>
		#quote_entries input {
			width: 100%;
		}
	</style>
@stop

@section('content')
	<div ng-app='app' ng-controller='QuoteController as quoteCtrl'>
		<% quoteCtrl.entries[0].product %>
		<div class="frame">
			<div class="bit-3">
				<div class="container">
					<div class="container_title blue">
						General quote information
					</div>
					<div class="container_content">
						{{ Form::open() }}
							<table class="form">
								<tr>
									<td style='width: 150px;'><label>Job Title</label></td>
									<td style='width: 100%;'>{{ Form::text('jobTitle', null, array('placeholder' => 'Job Title')) }}</td>
								</tr>
								<tr>
									<td><label>Customer</label></td>
									<td>{{ Form::text('customer', $customer->getCustomerName(), array('readonly' => 'readonly')) }}</td>
								</tr>
								<tr>
									<td><label>Advertisement Type</label></td>
									<td>{{ Form::select('adType', AdType::lists('type', 'id')) }}</td>
								</tr>
								<tr>
									<td><label>Job status</label></td>
									<td>{{ Form::select('jobStatus', JobStatus::lists('type', 'id')) }}</td>
								</tr>
							</table>
						{{ Form::close() }}
					</div>
				</div>
			</div>
		</div>
		<div class="frame">
			<div class="bit-1">
				<div class="container">
					<div class="container_title red">
						Quote entries
					</div>
					<div class="container_content nop" style='overflow: visible;'>
						<table class="styled tlf highlight" id='quote_entries'>
							<tr>
								<th style='width: 40%;'>Product</th>
								<th style='width: 60%;'>Description</th>
								<th style='width: 130px;'>Unit price</th>
								@if(System::find(1)->quoteHasVisitDate == 1)
									<th style='width: 130px;'>Visit Date</th>
								@endif
								<th style='width: 80px;'>Discount</th>
								<th style='width: 80px;'>Quantity</th>
								<th style='width: 110px;'>Total</th>
							</tr>
							<tr ng-repeat='entry in quoteCtrl.entries'>
								<td>
									<select type='text' name='product[]' chosen ng-model='entry.product'>
										@foreach(ProductCategory::all() as $category)
											<optgroup label='{{ $category->type }}'>
												@foreach($category->getProducts as $product)
													<option value='{{ $product->id }}'>{{ $product->name }}</option>
												@endforeach
											</optgroup>
										@endforeach
									</select>
								</td>
								<td><input type='text' ng-model='entry.description'></td>
								<td><input type='text' ng-mogel='entry.unitprice' class='euro'></td>
								@if(System::find(1)->quoteHasVisitDate == 1)
									<td><input type='text' name='visitdate[]'></td>
								@endif
								<td><input type='text' name='discount[]'></td>
								<td><input type='text' name='quantity[]' class='quantity'></td>
								<td><input type="text" class='euro' readonly></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop