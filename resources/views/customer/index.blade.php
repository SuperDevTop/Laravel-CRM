@extends('master')
@section('page_name')
	Manage customers
@stop

@section('breadcrumbs')
	Customers <i class="breadcrumb"></i> List Customers
@stop

@section('scripts')
	<script>
		$(function() {
			$('#searchQuery').focus();
			$('#searchQuery').on('keyup', function(e) {
				if ($('#searchQuery').val() == '') {
					$('#customer_results').html('');
					return;
				}
				$.ajax({
					url: 'customers/search',
					type: 'post',
					dataType: 'html',
					data: {
						type: 'query',
						searchQuery: $('#searchQuery').val()
					},
					success: function(data) {
						$('#customer_results').html(data);
						enableTableSorting($('#customer_results table.sortable'));
					}
				});
			});
		});

		function showAz(letter) {
			$.ajax({
				url: 'customers/search',
				type: 'post',
				dataType: 'html',
				data: {
					type: 'az',
					letter: letter
				},
				success: function(data) {
					$('#customer_results').html(data);
					enableTableSorting($('#customer_results table.sortable'));
				}
			});
		}

		function confirmDelete(customer_id) {
			confirmDialog('Are you sure?',
				'Are you sure you want to delete this customer?<br><br><span style="color: red; font-weight: bold;">Warning: <u><i>all</i></u> customer data such as history, invoices and quotes will be permanently deleted!</span><br><br><span style="color: red; font-weight: bold; font-size: 13pt"><u><i>This cannot be undone!</i></u></span>',
				function() {
					ajaxRequest(
						'delete_customer',
						{
							customerId: customer_id
						},
						function() {
							
						}
					);
				},
				function() {

				}
			);
		}
	</script>
@stop

@section('content')
	<div class="frame">
		<div class="bit-4">
			<div class="container">
				<div class="container_title blue">
					Search customers
				</div>
				<div class="container_content">
					<h3 class='underlined'>Magic search</h3>
					<input type="text" id='searchQuery' style='width: 100%;' placeholder='Search...' id='searchQuery'>
					<br><br>
					<h3 class='underlined'>A-Z listing</h3>
					@foreach($azAll as $val)
						<button style='width: 25px; font-size: 10pt; margin: 3px; font-weight:bold; height: 25px; padding: 0;' class='btn btn-default fl' onClick='showAz("{{ $val }}");'>{{ $val }}</button>
					@endforeach
					<button style='width: 35px; font-size: 10pt; margin: 3px; font-weight:bold; height: 25px; padding: 0;' class='btn btn-default' onClick='showAz("all");'>ALL</button>
					<br><br>
					@if (Auth::user()->hasPermission('customer_create'))
						<button class="btn btn-green" data-href='customers/create'>
							<i class="fa fa-plus"></i>
							Create new customer
						</button>
					@endif
				</div>
			</div>
		</div>
		<div class="bit-75">
			<div class="container">
				<div class="container_title green">
					Customer results
				</div>
				<div class="container_content nop" id='customer_results'>
					
				</div>
			</div>
		</div>
	</div>
@stop