@extends('master')
@section('reportTitle')
	Account Summary
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
		<h3>Account Summary</h3>
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
					<td class='tar'><b>Total + Extra Costs</b></td>
					<td class='tar'>€{{ CommonFunctions::formatNumber($extraData['total_subtotal'] + $extraData['total_vat'] + $extraData['total_supcosts']) }}</td>
				</tr>
				<tr>
					<td class='tar'><b>Received</b></td>
					<td class='tar'>€{{ CommonFunctions::formatNumber($extraData['total_received']) }}</td>
				</tr>
				<tr>
					<th style='background-color: #C7C7C7;'><b>Total pending:</b></th>
					<th style='background-color: #C7C7C7;' class='tar'>€{{ CommonFunctions::formatNumber(($extraData['total_subtotal'] + $extraData['total_vat'] + $extraData['total_supcosts']) - $extraData['total_received']) }}</th>
				</tr>
			</table>
		</div>
	</div>

	<br clear='both'><br>
	<b>Payments received</b>
	<table celspacing='0'>
		<tr>
			<th style='width: 80px;'>Date</th>
			<th style='width: 100%;'>Notes</th>
			<th style='width: 120px;'>Method</th>
			<th style='width: 90px;'>Amount</th>
		</tr>
		<?php $totalReceived = 0; ?>
		@foreach($entries['payments'] as $payment)
			<tr>
				<td>{{ CommonFunctions::formatDate($payment->date) }}</td>
				<td>{{ $payment->notes }}</td>
				<td>{{ PayMethod::find($payment->paymentType)->type }}</td>
				<td>€{{ CommonFunctions::formatNumber($payment->getTotal()) }}</td>
			</tr>
			<?php $totalReceived += $payment->getTotal(); ?>
		@endforeach
		<tr>
			<td></td>
			<td></td>
			<td><b>Total received:</b></td>
			<td><b><u>€{{ CommonFunctions::formatNumber($totalReceived) }}</u></b></td>
		</tr>
	</table>

	
	<br><br><br>
	<b>Invoices issued</b>
	<table celspacing='0'>
		<tr>
			<td style='width: 70px;'></td>
			<td style='width: 80px;'></td>
			<td style='width: 100%;'><b>Total:</b></td>
			<td class='tar' style='width: 80px;'>€{{ CommonFunctions::formatNumber($extraData['total_subtotal']) }}</td>
			<td class='tar' style='width: 80px;'>€{{ CommonFunctions::formatNumber($extraData['total_vat']) }}</td>
			<td class='tar' style='width: 80px;'>€{{ CommonFunctions::formatNumber($extraData['total_subtotal'] + $extraData['total_vat']) }}</td>
			<td class='tar' style='width: 80px;'>€{{ CommonFunctions::formatNumber($extraData['total_supcosts']) }}</td>
		</tr>
		<tr>
			<th>Order ID</th>
			<th>Invoice Date</th>
			<th>Description</th>
			<th>Subtotal</th>
			<th>IVA</th>
			<th>Total</th>
			<th>Xtra Costs</th>
		</tr>
		@foreach($entries['quotes'] as $quote)
			<tr>
				<td>{{ $quote->id }}</td>
				<td>{{ CommonFunctions::formatDate($quote->createdOn) }}</td>
				<td>{{ $quote->description }}</td>
				<td class='tar'>€{{ CommonFunctions::formatNumber($quote->getSubtotal()) }}</td>
				<td class='tar'>€{{ CommonFunctions::formatNumber($quote->getVat()) }}</td>
				<td class='tar'>€{{ CommonFunctions::formatNumber($quote->getTotalExcludingSupCosts()) }}</td>
				<td class='tar'>€{{ CommonFunctions::formatNumber($quote->supCosts) }}</td>
				<td></td>
			</tr>
		@endforeach
	</table>
@stop