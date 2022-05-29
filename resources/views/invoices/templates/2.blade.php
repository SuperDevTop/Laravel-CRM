<?php 
	use App\Classes\CommonFunctions;
?>
<html>
	<head>
		<title>Invoice #{{ $invoice->id }}</title>
		<base href="{{ Request::root() }}">
		<style>
			body {
				margin: 0;
				padding: 0;

				width: 215mm;
				height: 1140px;

				font-family: 'Serif';
				color: #101010;
				letter-spacing: 0px;
			}

			* {
				font-size: 10pt;
			}

			#pre_header {
				background-color: #f4f4f4;

				width: 100%;
				height: 30px;
			}

			#pre_header .mark {
				width: 45px;
				height: 30px;

				background-color: {{ $templateColor }};

				margin-left: 40px;
			}

			#wrapper {
				margin-left: 40px;
				margin-right: 30px;
			}

			#header {
				height: 100px;
			}

			#header .logo {
				margin-top: 20px;
				float: left;
				max-width: 400px;
				max-height: 80px;
			}

			#header .text {
				float: right;
				width: 300px;
				height: 100px;

				text-align: right;
			}

			#header .text h1 {
				margin-top: 25px;
				margin-bottom: 0;
				font-size: 16pt;
			}

			#header .text h3 {
				margin-top: 5px;
				font-size: 10pt;
			}

			hr {
				border: 2px solid #f0f0f0;
				margin: 0;
			}

			hr.blue {
				border: 2px solid {{ $templateColor }};
				margin: 0;
			}

			#content {

			}

			table.styled {
				table-layout: fixed;

				vertical-align: middle;

				width: 100%;

				margin-top: 3px;
				margin-bottom: 3px;

				border-collapse: collapse;
			}

			table.styled th {
				color: {{ $templateColor }};
				border-bottom: 4px solid {{ $templateColor }};
			}

			table.styled td.first {
				background-color: {{ $templateColor }};
				width: 45px;
				text-align: center;
			}

			th {
				text-align: left;
			}

			.cellpadding {
				padding: 15px;
			}

			.blue {
				color: {{ $templateColor }};
				vertical-align: top;
			}

			.valign_top {
				vertical-align: top;
			}

			table.products td:first-child, th:first-child {
				background-color: {{ $templateColor }};
				width: 45px;
				text-align: center;
				color: white;
				font-weight: bold;
			}

			table.products td:not(:first-child) {
				background-color: #f4f4f4;
				padding-left: 10px;
				padding-right: 5px;

				color: #424242;
				font-size: 9pt;

				border-left: 3px solid white;
			}

			table.products tr:not(:last-child) td {
				border-bottom: 3px solid white;
			}

			table.products td:nth-child(2) h1 {
				font-size: 10pt;
				margin: 0;
				margin-top: 8px;
			}

			table.products td:nth-child(2) h2 {
				font-size: 7pt;
				font-weight: 0;
				margin: 0;
				margin-bottom: 8px;
				color: #7a7a7a;
			}

			td.total {
				vertical-align: top;
				background-color: {{ $templateColor }};
				color: black;
				font-size: 15pt;
				text-align: right;
				padding-right: 10px;
				font-weight: bold;

				text-decoration: underline;

				height: 40px;
				line-height: 40px;
				line-height: {{ $templateColor }}
			}

			.ar {
				text-align: right;
			}

			.grey_bg {
				background-color: #E6E6E6;
			}
		</style>
		<script src='/js/jquery.js'></script>
		<script>
			$(function() {
				var windowH = $(window).height();
				var offset = $('#verticalSpread').offset().top;
				$('#verticalSpread').css('height', (windowH - offset - 70) + 'px');
			});
		</script>
	</head>
	<body>
		<div id="pre_header">
			<div class="mark"></div>
		</div>
		<div id="wrapper">
			<div id="header">
				<img src="logo.png" alt="Logo" class="logo">
				<div class="text">
					<h1>
						Invoice #{{ substr(Settings::setting('companyName'), 0, 1) }}-{{ date('Y', strtotime($invoice->createdOn)) }}-{{ $invoice->id }}
					</h1>
					<h2>{{ $invoice->jobTitle }}</h2>
				</div>
			</div>
			<div id="content">
				<hr>

				<table class='styled'>
					<tr>
						<td style='width: 45px;' class='first'><img src='/img/icons/quote/address.png'></td>
						<td style='width: 70px;' class='cellpadding blue'>
							From:
						</td>
						<td style='width: 50%;' class='cellpadding'>
							{{ Settings::setting('companyName') }}<br>
							{{ nl2br(Settings::setting('companyAddress')) }}
						</td>
						<td style='width: 90px;' class='cellpadding blue'>
							To:
						</td>
						<td style='width: 50%;'>
							{{ $invoice->getCustomer->getFullAddress() }}<br>
							CIF: {{ $invoice->getCustomer->cifnif }}<br>
						</td>
					</tr>
				</table>
				<hr>
				<table class="styled">
					<tr>
						<td style='width: 45px;' class='first'><img src='/img/icons/quote/info.png'></td>
						<td style='width: 70px; padding: 15px 10px 15px 15px;' class='cellpadding blue'>
							Date:
						</td>
						<td style='width: 50%;' class='cellpadding'>
							{{ CommonFunctions::formatDate($invoice->createdOn) }}
						</td>
						<td style='width: 90px;' class='cellpadding blue'>
							By:
						</td>
						<td style='width: 50%;'>
							{{ User::find($invoice->createdBy)->initials }}
						</td>
					</tr>
				</table>

				<hr>
				<br>
				<?php $counter = 1; ?>

				@foreach($invoice->getEntries as $invoiceDetail)
				<?php
					$quoteEntry = Quote::find($invoiceDetail->quoteId);
				?>
				<h1 class='job_title'>Quote #{{ $quoteEntry->id }} - {{ $quoteEntry->description }}</h1>
					<table class="styled">
						<tr>
							<th style='width: 45px; background: transparent;'>No.</th>
							<th style='width: 100%; padding-left: 15px;'>Product</th>
							@if (Settings::setting('quoteHasVisitDate') == '1')
								<th style='width: 80px;'>Visit date</th>
							@endif
							<th style='width: 40px;' class='ar'>Qty</th>
							<th style='width: 90px;' class='ar'>Unit Price</th>
							<th style='width: 50px;' class='ar'>D%</th>
							<th style='width: 90px;' class='ar'>Sum</th>
						</tr>
					</table>
					<table class='styled products'>
						@foreach($quoteEntry->getEntries as $entry)
							<tr>
								<td style='width: 45px;'>{{ $counter }}</td>
								<td style='width: 100%;'>
									<h1 class='fl' style='font-size: 8pt; margin: 4px;'>{{ $entry->productName}} <span style='font-size: 8pt; color: #8b8b8b;'>{{ $entry->description }}</span></h1>
								</td>
								@if (Settings::setting('quoteHasVisitDate') == '1')
									<td style='width: 80px;'>{{ CommonFunctions::formatDate($entry->visitDate) }}</td>
								@endif
								<td style='width: 40px;' class='ar'>{{ $entry->quantity }}</td>
								<td style='width: 90px;' class='ar'>&euro; {{ number_format($entry->unitPrice, 2, ',', '.') }}</td>
								<td style='width: 50px;' class='ar'>{{ $entry->discount }}%</td>
								<td style='width: 90px;' class='ar'>&euro; {{ number_format($entry->getTotal(), 2, ',', '.') }}</td>
							</tr>
							<?php $counter++; ?>
						@endforeach
						<tr>
							<td></td>
							@if (Settings::setting('quoteHasVisitDate') == '1')
								<td colspan='6' style="font-size: 10px; font-weight: bold; padding: 5px 14px;">
									Quote Subtotal: &euro;{{ number_format($quoteEntry->getSubTotal(), 2, ',', '.') }}
									@if ($invoiceDetail->discount > 0)
										- &euro;{{ number_format($invoiceDetail->getDiscountSum(), 2, ',', '.') }} ({{ $invoiceDetail->discount }}% additional discount) 
									@endif
									+ &euro;{{ number_format($invoiceDetail->getVAT(), 2, ',', '.') }} (IVA)
									@if ($invoiceDetail->supCosts > 0)
										+ &euro;{{ number_format($invoiceDetail->supCosts, 2, ',', '.') }} (Extra Costs) 
									@endif
									 = &euro;{{ number_format($invoiceDetail->getEntrySubTotal(), 2, ',', '.') }}
								</td>
							@else
								<td colspan='5'><strong style='font-size: 8pt;'>Extra costs:</strong> &euro; {{ number_format($quoteEntry->supCosts, 2, ',', '.') }}</td>
							@endif
						</tr>
					</table>
					<br>
				@endforeach
				
				<br>
				<hr class="blue">
			
				<table class="styled">
					<tr>
						<td class='first' style='width: 45px;'></td>
						<td style='width: 370px;'></td>
						<td style='width: 50px;'></td>
						<td style='text-align: right; font-size: 8pt; vertical-align: top; width: 100%; padding-top: 15px; padding-bottom: 15px; background-color: #f4f4f4;'>
							<table style='font-size: 8pt; width: 100%;'>
								<tr>
									<td style='text-align: right; width: 70%;'><strong style='font-size: 8pt;'>Subtotal:</strong></td>
									<td style='font-size: 8pt; text-align: right;'>&euro; {{ CommonFunctions::formatNumber($invoice->getSubtotal()) }}</td>
								</tr>
								<tr>
									<td style='text-align: right; width: 15%;'><strong style='font-size: 8pt;'>IVA:</strong></td>
									<td style='font-size: 8pt; text-align: right;'>&euro; {{ CommonFunctions::formatNumber($invoice->getVat()) }}</td>
								</tr>
								
								@if (Settings::setting('invoiceHasIrpf'))
								<tr>
									<td style='text-align: right; width: 40%;'><strong style='font-size: 8pt;'>IRPF:</strong></td>
									<td style='font-size: 8pt; text-align: right;'>&euro; {{ CommonFunctions::formatNumber($invoice->getIrpf()) }}</td>
								</tr>
								@endif								
								
								<tr>
									<td style='text-align: right; width: 15%;'><strong style='font-size: 8pt;'>Extra costs:</strong></td>
									<td style='font-size: 8pt; text-align: right;'>&euro; {{ CommonFunctions::formatNumber($invoice->getSupcosts()) }}</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td class='first'></td>
						<td colspan='2'></td>
						<td style='backgound-color: #40b8e9;' class='total'>
							<span style='float: left; margin-left: 10px; font-size: 22px; margin-top: 2px;'>TOTAL:</span>
							&euro; {{ CommonFunctions::formatNumber($invoice->getTotal()) }}
						</td>
					</tr>
					<tr>
						<td id='verticalSpread' class='first'></td>
						<td valign='top' style='padding: 10px;'>
							{{ Settings::setting('invoiceText') }}
						</td>
						<td></td>
						<td valign='top'>
							<div class='grey_bg' style='width: 100%; height: 40px; text-align: right; font-size: 8pt; vertical-align: top; width: 100%; padding-top: 15px; padding-bottom: 15px;'>
							<table style='font-size: 8pt; width: 100%; text-align: right;'>
								<tr>
									<td style='text-align: right; width: 70%;'><strong style='font-size: 8pt;'>Subtotal work:</strong></td>
									<td style='font-size: 8pt; text-align: right;'>&euro; {{ CommonFunctions::formatNumber($invoice->getSubWork()) }}</td>
								</tr>
								<tr>
									<td style='text-align: right; width: 70%;'><strong style='font-size: 8pt;'>Subtotal products:</strong></td>
									<td style='font-size: 8pt; text-align: right;'>&euro; {{ CommonFunctions::formatNumber($invoice->getSubNotWork()) }}</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td class='first'></td>
						<td colspan='3' style='padding-left: 10px;'>{{ Settings::setting('bankDetails') }}</td>
					</tr>
					<tr>
						<td class='first'></td>
						<td colspan='3' style='padding-left: 10px;'>{{ Settings::setting('additionalInvoiceLine') }}</td>
					</tr>
				</table>
			</div>
		</div>
	</body>
</html>