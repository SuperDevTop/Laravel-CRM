@extends('master')
<?php 
	use App\Classes\CommonFunctions;
?>
@section('reportTitle')
	Quotes
@stop

@section('subtitle')
	(number {{ $numberStart }} - {{ $numberEnd}})
@stop

@section('table')
	<table celspacing='0'>
		<tr>
			<th style='width: 100px;'>Date</th>
			<th style='width: 80px;'>Order #</th>
			<th style='width: 100%;'>Customer Name</th>
			<th style='width: 200px;;'>Job status</th>
			<th style='width: 120px;'>Total</th>
			<th style='width: 120px;'>Received</th>
		</tr>
		@foreach($entries as $quote)
			<tr>
				<td>{{ CommonFunctions::formatDate($quote->createdOn) }}</td>
				<td>{{ $quote->id }}</td>
				<td>{{ $quote->getCustomer->getCustomerName() }}</td>
				<td>{{ $quote->getStatus->type }}</td>
				<td>€{{ CommonFunctions::formatNumber($quote->getTotal()) }}</td>
				<td>€{{ CommonFunctions::formatNumber($quote->getPaid()) }}</td>
			</tr>
		@endforeach
	</table>
@stop