@extends('master')
@section('reportTitle')
	Customer credit notes
@stop
<?php
	use App\Classes\CommonFunctions;
?>
@section('subtitle')
	(Period {{ $dateStart }} - {{ $dateEnd}})
@stop

@section('table')
	<b>Customer:</b>
	<br>
	{{ $extraData['customer']->getFullAddress() }}
	
	<div style='width: 250px; float: right; margin-top: -70px;'>
		<h3>Total summary</h3>
		<div style='width: 95%; border: 1px solid gray; padding: 5px;'>
			<table>
				<tr>
					<td style='width: 60%;' class='tar'><b>Subtotal</b></td>
					<td style='width: 40%;' class='tar'>€{{ CommonFunctions::formatNumber($extraData['total_subtotal']) }}</td>
				</tr>
				<tr>
					<td class='tar'><b>IVA</b></td>
					<td class='tar'>€{{ CommonFunctions::formatNumber($extraData['total_vat']) }}</td>
				</tr>
				<tr>
					<td class='tar'><b>Total</b></td>
					<td class='tar'>€{{ CommonFunctions::formatNumber($extraData['total_subtotal'] + $extraData['total_vat']) }}</td>
				</tr>
				<tr>
					<td class='tar'><b>Extra costs</b></td>
					<td class='tar'>€{{ CommonFunctions::formatNumber($extraData['total_supcosts']) }}</td>
				</tr>
			</table>
		</div>
	</div>

	<br><br><br><br><br><br><br>
	<b>Credit notes issued</b>
	<table celspacing='0'>
		<tr>
			<td style='width: 90px;'></td>
			<td style='width: 110px;'></td>
			<td style='width: 100%;'><b>Total:</b></td>
			<td class='tar' style='width: 80px;'>€{{ CommonFunctions::formatNumber($extraData['total_subtotal']) }}</td>
			<td class='tar' style='width: 80px;'>€{{ CommonFunctions::formatNumber($extraData['total_vat']) }}</td>
			<td class='tar' style='width: 80px;'>€{{ CommonFunctions::formatNumber($extraData['total_subtotal'] + $extraData['total_vat']) }}</td>
			<td class="tar" style='width: 80px;'>€{{ CommonFunctions::formatNumber($extraData['total_supcosts']) }}</td>
		</tr>
		<tr>
			<th>ID</th>
			<th>Date</th>
			<th>Description</th>
			<th>Subtotal</th>
			<th>IVA</th>
			<th>Total</th>
			<th>Extra costs</th>
		</tr>
		@foreach($entries['creditnotes'] as $creditnote)
			<tr>
				<td>{{ $creditnote->id }}</td>
				<td>{{ CommonFunctions::formatDate($creditnote->createdOn) }}</td>
				<td>{{ $creditnote->jobTitle }}</td>
				<td class='tar'>€{{ CommonFunctions::formatNumber($creditnote->getSubtotal()) }}</td>
				<td class='tar'>€{{ CommonFunctions::formatNumber($creditnote->getVat()) }}</td>
				<td class='tar'>€{{ CommonFunctions::formatNumber($creditnote->getTotalExcludingSupCosts()) }}</td>
				<td class='tar'>€{{ CommonFunctions::formatNumber($creditnote->getSupCosts()) }}</td>
			</tr>
		@endforeach
	</table>
@stop