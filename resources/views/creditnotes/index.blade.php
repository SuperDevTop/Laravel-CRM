@extends('master')
@section('page_name')
	Last 100 invoices
@stop
<?php 
	use App\Classes\CommonFunctions;
?>
@section('breadcrumbs')
	Quotes & invoices <i class="breadcrumb"></i> List invoices
@stop

@section('content')
	<div class="frame">
		<div class="bit-1">
			<div class="container">
				<div class="container_title blue">
					Last 100 invoices
				</div>
				<div class="container_content nop">
					<table class="data highlight sortable">
						<thead>
							<tr>
								<th style='width: 140px;'>Invoice number</th>
								<th style='width: 130px;' data-sortable-date>Date created</th>
								<th style='width: 50%;'>Customer</th>
								<th style='width: 50%;'>Title</th>
								<th style='width: 130px;'>Total</th>
								<th style='width: 250px;'>Payment status</th>
							</tr>
						</thead>
						<tbody>
							@foreach($invoices as $invoice)
							<tr data-href='/invoices/{{ $invoice->id }}/edit' class='cp'>
								<td>{{ $invoice->id }}</td>
								<td>{{ CommonFunctions::formatDateTime($invoice->createdOn) }}</td>
								<td>{{ $invoice->getCustomer->getCustomerName() }}</td>
								<td>{{ $invoice->jobTitle }}</td>
								<td class='tar'>€ {{ CommonFunctions::formatNumber($invoice->getTotal()) }}</td>
								<td><td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@stop