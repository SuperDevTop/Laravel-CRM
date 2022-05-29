<html>
	<head>
		<title>Quote #{{ $quote->id }}</title>
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
				margin-top: 30px;
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
				font-size: 8pt;
				margin: 0;
				margin-top: 3px;
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
				color: white;
				font-size: 15pt;
				text-align: right;
				padding-right: 10px;
				font-weight: bold;

				height: 40px;
				line-height: 40px;
			}

			table.lessPadding td {
				padding: 5px;
			}

			.fl {
				float: left;
			}

			.ar {
				text-align: right;
			}

			table tr td, table tr th {
		        page-break-inside: avoid;
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
					@if(!isset($isReceipt))
						<h1>
							Quote #{{ $quote->id }}
						</h1>
					@else
						<h1>
							Receipt (quote #{{ $quote->id }})
						</h1>
					@endif
				</div>
			</div>
			<div id="content">
				<hr>

				<table class='styled'>
					<tr>
						<td style='width: 45px;' class='first'><img src='/img/icons/quote/address.png'></td>
						<td style='width: 80px;' class='cellpadding blue'>
							To:
						</td>
						<td style='width: 50%;' class='cellpadding'>
							{{ $quote->getCustomer->getFullAddress() }}<br>
						</td>
						<td colspan='2' style='width: 60%;' class='cellpadding valign_top'>
							<h2 class='blue'>Job description</h2>
							{{ $quote->description }}
						</td>
					</tr>
				</table>
				<hr>
				<table class="styled lessPadding">
					<tr>
						<td style='width: 45px;' class='first' rowspan='3'>
							<img src='/img/icons/quote/calendar.png'>
							<br><br>
							<img src='/img/icons/quote/info.png'>
						</td>
						<td style='width: 90px;' class='cellpadding blue'>
							Order date:
						</td>
						<td style='width: 56%;' class='cellpadding'>
							{{ CommonFunctions::formatDateTime($quote->createdOn) }}
						</td>
						<td style='width: 110px;' class='cellpadding blue'>
							Start date:
						</td>
						<td style='width: 44%;'>
							{{ CommonFunctions::formatDateTime($quote->startedOn) }}
						</td>
					</tr>
					<tr>
						<td class='cellpadding blue'>
							Ceated by:
						</td>
						<td class='cellpadding'>
							{{ User::find($quote->createdBy)->initials }}
						</td>
						<td class='cellpadding blue'>
							Required by:
						</td>
						<td>
							{{ CommonFunctions::formatDateTime($quote->requiredBy) }}
						</td>
					</tr>
					<tr>
						<td style='width: 110px;' class='cellpadding blue'>
							Assigned to:
						</td>
						<td style='width: 100%;' class='cellpadding'>
							{{ User::find($quote->assignedTo)->initials }}
						</td>
						<td style='width: 110px;' class='cellpadding blue'>
							Completed on:
						</td>
						<td style='width: 100%;'>
							{{ CommonFunctions::formatDateTime($quote->completedOn) }}
						</td>
					</tr>
				</table>

				<hr>
				<br>
				<table class="styled">
					<tr>
						<th style='width: 45px; background: transparent;'>No.</th>
						<th style='width: 100%; padding-left: 15px;'>Product</th>
						@if (Settings::setting('quoteHasVisitDate') == '1')
							<th style='width: 80px;'>Visit date</th>
						@endif
						<th style='width: 40px;' class='ar'>Qty</th>
						<th style='width: 70px;' class='ar'>Unit Price</th>
						<th style='width: 50px;' class='ar'>D%</th>
						<th style='width: 70px;' class='ar'>Sum</th>
					</tr>
				</table>
				<table class='styled products'>
					<?php $counter = 1; ?>
					@foreach($quote->getEntries as $entry)
						<tr>
							<td style='width: 45px;'>{{ $counter }}</td>
							<td style='width: 100%;'>
								<h1 class='fl'>{{ $entry->productName}} <span style='font-size: 8pt; color: #8b8b8b;'>{{ $entry->description }}</span></h1>
							</td>
							@if (Settings::setting('quoteHasVisitDate') == '1')
								<td style='width: 80px;'>{{ CommonFunctions::formatDate($entry->visitDate) }}</td>
							@endif
							<td style='width: 40px;' class='ar'>{{ $entry->quantity }}</td>
							<td style='width: 70px;' class='ar'>{{ CommonFunctions::formatNumber($entry->unitPrice) }}</td>
							<td style='width: 50px;' class='ar'>{{ $entry->discount }}%</td>
							<td style='width: 70px;' class='ar'>{{ CommonFunctions::formatNumber($entry->getTotal()) }}</td>
						</tr>
						<?php $counter++; ?>
					@endforeach
				</table>

				<hr class="blue">

				<table class="styled">
					<tr>
						<td style='width: 45px;' class='first'></td>
						<td style='width: 100%; padding-left: 10px; vertical-align: top;' rowspan='2'>
							<br>
							@if(!isset($isReceipt))
								{{ nl2br(Settings::setting('quoteText')) }}
							@endif
						</td>
						<td style='width: 120px; line-height: 40px;' class='blue'>
							@if(!isset($isReceipt))
								Estimated total:
							@else
								Total:
							@endif
						</td>
						<td style='width: 170px; backgound-color: {{ $templateColor }};' class='total'>&euro; {{ CommonFunctions::formatNumber($quote->getTotal()) }}</td>
					</tr>
					<tr>
						<td class='first'></td>
						<td></td>
						<td style='text-align: right; font-size: 8pt; vertical-align: top;'>
							<b>Subtotal:</b> &euro; {{ CommonFunctions::formatNumber($quote->getSubtotal()) }}<br>
							<b>IVA:</b> &euro; {{ CommonFunctions::formatNumber($quote->getVat()) }}<br>
							<b>Extra costs:</b> &euro; {{ CommonFunctions::formatNumber($quote->supCosts) }}<br>
						</td>
					</tr>
					<tr>
						<td class='first' id='verticalSpread'></td>
						<td colspan='3' style='vertical-align: top; padding: 5px;'>
							<br><br>
							@if(!isset($isReceipt) && !Request::has('nocomments') && false)
								<table class="styled">
									<tr>
										<th style='width: 100px; background: transparent; text-align: left;'>Date</th>
										<th style='width: 100%;'>Communication</th>
									</tr>
									@foreach($quote->getComments as $comment)
										<tr>
											<td>{{ CommonFunctions::formatDate($comment->placedOn) }}</td>
											<td>{{ $comment->comment }}</td>
										</tr>
									@endforeach
								</table>
							@endif
							@if (isset($isReceipt))
								{{ CommonFunctions::getReceiptText($quote->getCustomer->id) }}
							@endif
						</td>
					</tr>
					<tr>
						<td class='first'></td>
						<td colspan='3' style='padding: 10px;'><b>{{ Settings::setting('companyName') }}</b> - {{ Settings::setting('companyAddress') }}</td>
					</tr>
				</table>
			</div>
		</div>
	</body>
</html>