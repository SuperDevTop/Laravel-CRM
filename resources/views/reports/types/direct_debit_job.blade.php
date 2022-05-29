@extends('master')
@section('reportTitle')
	Direct Debit Report
@stop
<?php
	use App\Classes\CommonFunctions;
?>
@section('subtitle')
	{{ $extraData['job']->description }} (Created on {{ CommonFunctions::formatDate($extraData['job']->date)}})
@stop

@section('table')
	<table celspacing='0'>
		<tr>
			<th style='width: 90px'>Invoice</th>
			<th style='width: 110px;'>Date</th>
			<th style='width: 100%;'>Customer</th>
			<th style='width: 100px;'>Invoice price</th>
			<th style='width: 100px;'>Bank charges</th>
		</tr>
		<?php
			$bankCharge = 0;
			$invoiceTotal = 0;
		?>
		@foreach($entries as $invoice)
			<?php
				$bankCharge += $invoice->bankCharge;
				$invoiceTotal += $invoice->total;
			?>
			<tr>
				<td>{{ $invoice->invoice }}</td>
				<td>{{ CommonFunctions::formatDate($invoice->getInvoice->createdOn) }}</td>
				<td>{{ $invoice->getInvoice->getCustomer->getCustomerName() }}</td>
				<td class='tar'>{{ CommonFunctions::formatNumber($invoice->total) }}</td>
				<td class='tar'>{{ CommonFunctions::formatNumber($invoice->bankCharge) }}</td>
				<td></td>
			</tr>
		@endforeach
	</table>
	<hr>
	<br><br>
	<table celspacing='0' style='width: 50%;'>
		<tr>
			<td>Bank Charges</td>
			<td>{{ CommonFunctions::formatNumber($bankCharge) }}</td>
		</tr>
		<tr>
			<td>Invoices total</td>
			<td>{{ CommonFunctions::formatNumber($invoiceTotal) }}</td>
		</tr>
		<tr>
			<td>Total sum sent to bank</td>
			<td>{{ CommonFunctions::formatNumber($bankCharge + $invoiceTotal) }}</td>
		</tr>
	</table>
@stop