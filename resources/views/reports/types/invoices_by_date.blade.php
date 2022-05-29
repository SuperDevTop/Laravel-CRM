@extends('master')
@section('reportTitle')

	Invoices
@stop

<?php
	use App\Classes\CommonFunctions;
?>
@section('subtitle')
	(Period {{ $dateStart }} - {{ $dateEnd}})
@stop

@section('table')
	<table celspacing='0'>
		<tr>
			<td style='width: 60px;'></td>
			<td style='width: 80px;'></td>
			<td style='width: 100%;'></td>
			<td style='width: 100px;'><b>Total:</b></td>
			<td class='tar' style='width: 90px;'>€{{ CommonFunctions::formatNumber($extraData['subtotal_products']) }}</td>
			<td class='tar' style='width: 90px;'>€{{ CommonFunctions::formatNumber($extraData['subtotal_work']) }}</td>
			<td class='tar' style='width: 90px;'>€{{ CommonFunctions::formatNumber($extraData['total_subtotal']) }}</td>
			<td class='tar' style='width: 90px;'>€{{ CommonFunctions::formatNumber($extraData['total_vat']) }}</td>
			<td class='tar' style='width: 90px;'>€{{ CommonFunctions::formatNumber($extraData['total_subtotal'] + $extraData['total_vat']) }}</td>
			<td class='tar' style='width: 90px;'>€{{ CommonFunctions::formatNumber($extraData['total_supcosts']) }}</td>
		</tr>
		<tr>
			<th>Order #</th>
			<th>Date</th>
			<th>Company Name</th>
			<th>CIFNIF</th>
			<th>Products</th>
			<th>Work</th>
			<th>Subtotal</th>
			<th>IVA ({{ $extraData['vat'] }}%)</th>
			<th>Total</th>
			<th>Extra Costs</th>
		</tr>
		@foreach($entries as $invoice)
			<tr>
				<td>{{ $invoice->id }}</td>
				<td>{{ CommonFunctions::formatDate($invoice->createdOn) }}</td>
				<td>{{ $invoice->getCustomer->getCustomerName() }}</td>
				<td>{{ $invoice->getCustomer->cifnif }}</td>
				<td class='tar'>€{{ CommonFunctions::formatNumber($invoice->getSubNotWork()) }}</td>
				<td class='tar'>€{{ CommonFunctions::formatNumber($invoice->getSubWork()) }}</td>
				<td class='tar'>€{{ CommonFunctions::formatNumber($invoice->getSubtotal()) }}</td>
				<td class='tar'>€{{ CommonFunctions::formatNumber($invoice->getVat()) }}</td>
				<td class='tar'>€{{ CommonFunctions::formatNumber($invoice->getTotalExcludingSupCosts()) }}</td>
				<td class='tar'>€{{ CommonFunctions::formatNumber($invoice->getSupCosts()) }}</td>
				<td></td>
			</tr>
		@endforeach
	</table>
@stop