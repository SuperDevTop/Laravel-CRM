<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Direct Debit - Data Integrity Check</title>
	<link rel="stylesheet" href="css/style.css">

</head>
<?php
	use App\Http\Controllers\DirectDebitController;
	use App\Classes\CommonFunctions;
?>
<body>
	<h1>Direct Debit Precheck</h1>
	<table class='styled' style='table-layout: fixed;' border='1'>
		<tr>
			<th style='width: 100px;'>#</th>
			<th style='width: 20%;'>Description</th>
			<th style='width: 20%;'>Customer</th>
			<th style='width: 120px;'>Bank charge</th>
			<th style='width: 80px;'>Total</th>
			<th style='width: 40%;'>Errors</th>
		</tr>
		@foreach($invoices as $invoice)
		<tr <?= (DirectDebitController::getInvoiceValidation($invoice->id) == '<ul></ul>') ? 'style="background-color: #CEFFCE;"' : 'style="background-color: #FFDDDD;"' ?>>
			<td>{{ $invoice->getInvoice->id }}</td>
			<td>{{ $invoice->getInvoice->jobTitle }}</td>
			<td>{{ $invoice->getInvoice->getCustomer->getCustomerName() }}</td>
			<td>€ {{ CommonFunctions::formatNumber($invoice->bankCharge) }}</td>
			<td>€ {{ CommonFunctions::formatNumber($invoice->getInvoice->getTotal()) }}</td>
			<td>
				{{ DirectDebitController::getInvoiceValidation($invoice->id) }}
			</td>
		</tr>
		@endforeach
		<tr>
			<td colspan='4'>Total</td>
			<td>{{ CommonFunctions::formatNumber($total) }}</td>
			<td></td>
		</tr>
	</table>
</body>
</html>