@extends('master')
@section('page_name')
	View suppliers
@stop

@section('breadcrumbs')
	Suppliers <i class="breadcrumb"></i> List
@stop

@section('scripts')
	<script>
		$(function() {
			$('#searchQuery').focus();
			$('#searchQuery').bind('input', function(e) {
				$('#resultsTable tr').each(function(index, row) {
					if (index == 0) return;
					var resultFound = false;

					$(this).find('td').each(function(i, e) {
						if ($(e).html().toLowerCase().indexOf($('#searchQuery').val().toLowerCase()) != -1) resultFound = true;
					});

					if (resultFound && $('#searchQuery').val() != '') {
						$(row).show();
					} else {
						$(row).hide();
					}
				});
			});
		});
	</script>
@stop

@section('content')
	<div class="frame">
		<div class="bit-3">
			<div class="container">
				<div class="container_title blue">
					Search suppliers
				</div>
				<div class="container_content">
					<h3 class='underlined'>Search by name</h3>
					<input type="text" id='searchQuery' style='width: 100%;' placeholder='Search...' id='searchQuery'>
					<br><br>
					@if (Auth::user()->hasPermission('supplier_create'))
						<button class="btn btn-green" data-href='suppliers/create'>
							<i class="fa fa-plus"></i>
							Create new supplier
						</button>
					@endif
				</div>
			</div>
		</div>
		<div class="bit-66">
			<div class="container" id='product_results_container'>
				<div class="container_title green">
					Supplier results
				</div>
				<div class="container_content nop" id='product_results'>
					<table class="data highlight sortable" id='resultsTable'>
							<thead>
							<tr>
								<th style='width: 130px;'>Code</th>
								<th style='width: 60%;'>Company name</th>
								<th style='width: 40%;'>Contact name</th>
								<th style='width: 70px;' data-sortable-no>Actions</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($suppliers as $supplier)
								<tr class=''>
									<td>{{ $supplier->supplierCode }}</td>
									<td>{{ $supplier->companyName }}</td>
									<td>{{ $supplier->contactName }}</td>
									<td>
										@if (Auth::user()->hasPermission('supplier_view'))
											<button onClick='window.location.assign("/suppliers/{{ $supplier->id }}");' class='btn btn-default btn-square'>
												<i class="fa fa-eye"></i>
											</button>
										@endif
										@if (Auth::user()->hasPermission('supplier_edit'))
											<button onClick='window.location.assign("/suppliers/{{ $supplier->id }}/edit");' class='btn btn-orange btn-square'>
												<i class="fa fa-edit"></i>
											</button>
										@endif
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