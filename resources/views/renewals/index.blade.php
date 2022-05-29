@extends('master')
@section('page_name')
	Manage renewals
@stop

<?php
	use App\Models\Product;
	use App\Models\variables\ProductCategory;
?>

@section('breadcrumbs')
	Renewals <i class="breadcrumb"></i> List Renewals
@stop

@section('scripts')
	<script>
		function confirmRenewalCancel(renewalId) {
			confirmDialog(
				'Are you sure?',
				'Are you sure you want to cancel this renewal? This cannot be undone!',
				function() {
					window.location.assign('/renewals/' + renewalId + '/cancel');
				}
			);
		}

		function confirmRenewRenewal(renewalId) {
			confirmDialog(
				'Are you sure?',
				'Are you sure you want to renew this renewal? This will automatically create a new quote, and increase the renew count!',
				function() {
					window.location.assign('/renewals/' + renewalId + '/quote');
				}
			);
		}

		function showNotes(button) {
			$('tr').hide();
			$(button).closest('tr').next('tr').show();
		}

		function saveNotes(button) {
			$('tr').hide();
			$('tr:not(.notes)').show();
			ajaxRequest(
				'update_renewal_notes',
				{
					renewalId: $(button).closest('tr').attr('data-renewal-id'),
					notes: $(button).closest('tr').find('textarea').val()
				},
				function(data) {
					if (data.success) {
						notify('Notes updated succesfully');
					}
				}
			);
		}

		function applyProductFilter() {
			var productId = $('#product option:selected').first().val();
			location.assign('renewals?product=' + productId);
		}

		function applyCategoryFilter() {
			var catId = $('#product_cat option:selected').first().val();
			location.assign('renewals?category=' + catId);
		}
	</script>
@stop

@section('stylesheets')
	<style>
		table.thirds tr:nth-child(4n+3) td {
			background-color: #FFF !important;
		}

		table.thirds tr:hover td {
			background-color: #F4F4F4 !important;
		}
	</style>
@stop

@section('content')
	<div class="frame">
		<div class="bit-2">
			<div class="container">
				<div class="container_title orange"><i class="fa fa-filter"></i> Filters</div>
				<div class="container_content">
					<table class="form">
						<tr>
							<td style="width: 150px;">
								Product
							</td>
							<td style="width: 100%">
								<select name='product' id="product" onChange="applyProductFilter()" class='select2' placeholder='Please select a product...'>
									<option></option>
									@foreach(Product::where('discontinued', '=', 0)->orderBy('name')->get() as $product)
										<option value='{{ $product->id }}' @if (Request::get('product') == $product->id) selected="selected" @endif>{{ $product->name }}</option>
									@endforeach
								</select>
							</td>
						</tr>
						<tr>
							<td style="width: 150px;">
								Product Category
							</td>
							<td style="width: 100%">
								<select name='product_cat' id="product_cat" onChange="applyCategoryFilter();" class='select2' placeholder='Please select a product...'>
									<option></option>
									@foreach(ProductCategory::where('discontinued', '=', 0)->orderBy('type')->get() as $category)
										<option value='{{ $category->id }}' @if (Request::get('category') == $category->id) selected="selected" @endif>{{ $category->type }}</option>
									@endforeach
								</select>
							</td>
						</tr>
						@if (Request::has('product') || Request::has('category'))
							<tr>
								<td></td>
								<td>
									<a href="renewals"><button class="btn btn-red"><i class="fa fa-remove"></i> Delete filter</button></a>
								</td>
							</tr>
						@endif
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="frame">
		<div class="bit-1">
			<div class="container">
				<div class="container_title blue">
					@if (!Request::has('product') && !Request::has('category'))
						Renewals due within 1 {{ (Request::has('week')) ? 'week' : (Request::has('year') ? 'year' : 'month') }}
					@else
						All renewals matching filter
					@endif
					@if(!Request::has('week'))
					<button class="btn btn-orange fr ml10" onClick='location.assign("/renewals?week=1")'>
						<i class="fa fa-calendar"></i> Switch to week
					</button>
					@endif
					@if(Request::has('week') || Request::has('year'))
					<button class="btn btn-orange fr ml10" onClick='location.assign("/renewals")'>
						<i class="fa fa-calendar"></i> Switch to month
					</button>
					@endif
					@if(!Request::has('year'))
					<button class="btn btn-orange fr ml10" onClick='location.assign("/renewals?year=1")'>
						<i class="fa fa-calendar"></i> Switch to year
					</button>
					@endif
				</div>
				<div class="container_content nop">
					<table class="data highlight sortable thirds">
						<thead>
							<tr>
								<th style='width: 250px;'>Customer</th>
								<th style='width: 250px;'>Product</th>
								<th style='width: 100px;' data-sortable-date>Start date</th>
								<th style='width: 100%;'>Notes</th>
								<th style='width: 130px;' data-sortable-date>Next renewal</th>
								<th style='width: 100px;'>Frequency</th>
								<th style='width: 100px;'>Renewed</th>
								<th style='width: 120px;' data-sortable-no></th>
							</tr>
						</thead>
						<tbody>
							@foreach($renewals as $renewal)
								<?php $_ENV['test123'] = $renewal->product; ?>
								<tr>
									<td><a href="customers/{{ $renewal->customer }}">{{ $renewal->getCustomer->getCustomerName() }}</a></td>
									<td>{{ $renewal->getProduct->name }}</td>
									<td>{{ $renewal->startDate }}</td>
									<td>{{ $renewal->notes }}</td>
									<td>{{ $renewal->nextRenewalDate }}</td>
									<td>{{ $renewal->renewalFreq }} {{ ($renewal->renewalFreq == 1) ? 'Month' : 'Months' }}</td>
									<td>{{ $renewal->renewalCount }}</td>
									<td>
										<button class="btn btn-orange" onclick='showNotes(this)' data-tooltip="Edit Notes">
											<i class="fa fa-comment"></i>
										</button>
										<button class="btn btn-default" onclick='confirmRenewRenewal({{ $renewal->id }})' data-tooltip="Renew & Create Quote">
											<i class="fa fa-share"></i>
										</button>
										<button class='btn btn-red' onclick='confirmRenewalCancel({{ $renewal->id }})' data-tooltip="Cancel Renewal">
											<i class="fa fa-minus"></i>
										</button>
									</td>
								</tr>
								<tr class='hide notes no_highlight' data-renewal-id="{{ $renewal->id }}">
									<td colspan='9'>
										<p style='padding: 10px;'>Here you can edit the notes for your selected invoice. Don't forget to click save!</p>
										<textarea placeholder='Put renewal notes here and click "Save Notes"' style='height: 100px;' class='fw'>{{ $renewal->notes }}</textarea>
										<button class="btn btn-green fr ml10" style='margin-top: 5px;' onclick='saveNotes(this)'>
											<i class="fa fa-check"></i> Save
										</button>
										<button class="btn btn-red fr" style='margin-top: 5px;' onclick='saveNotes(this)'>
											<i class="fa fa-remove"></i> Back
										</button>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@stop