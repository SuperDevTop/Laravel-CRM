@extends('master')
@section('page_name')
	System configuration
@stop
<?php
	use App\Models\Settings;
	use App\Models\variables\VAT;
	use App\Models\variables\JobStatus;
	use App\Models\variables\AdType;
?>
@section('breadcrumbs')
	System <i class="breadcrumb"></i> System Settings
@stop

@section('scripts')
	<script src='/js/colpick.js'></script>
	<script src='/js/lightbox.min.js'></script>
	<script>
		$(function() {
			$('.left_menu li').on('click', function(event) {
				$('.tab').hide();
				$('.tab#' + $(this).data('tab')).show();
				$('.left_menu li').removeClass('active');
				$(this).addClass('active');
			});

			$(window).resize(function(event) {
				$('#config_content').height(window.innerHeight - 230);
			});

			$(window).trigger('resize');

			$('#general').show();
		});
		
		var ignoreUpdate = true;
		$(function() {

			$('.colorpick').colpick({
				colorScheme: 'dark',
				flat: true,
				submit: false,
				color: '{{ Settings::setting("templateColor") }}',
				onChange: function(hsb, hex, rgb, el) {
					$('.colorpick_input').val('#' + hex);
				}
			});

			$('.template-thumb button').on('click', function(event) {
				if (!$(this).hasClass('active') && !ignoreUpdate) {
					// Update backend
					ajaxRequest(
						'set_selected_quote_template',
						{
							templateId: $(this).data('template-id')
						},
						function(data) {
							showAlert('You have succesfully selected a new template for your quotes and invoices. All generated quotes/invoices will now have this layout. Don\'t forget to customize your template!');
						}
					);
				}
				ignoreUpdate = false;
				$('.template-thumb button').removeClass('active');
				$('.template-thumb button').html('Select template');
				$(this).addClass('active');
				$(this).html('Selected template');
			});


			// Set active template
			$('.template-thumb button[data-template-id="{{ Settings::setting("quoteTemplate") }}"]').trigger('click');
		});
	</script>
@stop
{{-- My part --}}
<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<base href="{{ Request::root() }}">
<script type="text/javascript" src="//code.jquery.com/jquery-1.11.2.js"></script>
	<script type="text/javascript" src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.full.js'></script>
	<script type="text/javascript" src='/js/tablesorter.js'></script>
	<script type="text/javascript" src='/js/jquery-ui.min.js'></script>
	<script type="text/javascript" src='/js/jquery.timepicker.min.js'></script>
	<script type="text/javascript" src='/js/howler.min.js'></script>
	<script type="text/javascript" src='/js/autosize.min.js'></script>
	<script type="text/javascript" src='/js/datefunctions.js'></script>
	<script type="text/javascript" src='/js/angularjs.min.js'></script>

	<script type="text/javascript" src="/js/main.js"></script>
	<script type="text/javascript" src='/js/tabs.js'></script>
	<script type="text/javascript" src="/js/basinput.js"></script>
	<script type="text/javascript" src="/js/search_anything.js"></script>

<link rel="shortcut icon" type="image/png" href="/icon.png"/>
	<link rel="stylesheet" href="/css/normalize.css">
	<link rel="stylesheet" href="/css/layout.css">
	<link rel="stylesheet" href="/css/ui_elements.css">
	<link rel="stylesheet" href="/css/grid.css">
	<link rel="stylesheet" href="/css/select2.css">
	<link rel="stylesheet" href="/css/jquery-ui.min.css">
	<link rel="stylesheet" href="/css/search_anything.css">
	<link rel="stylesheet" href="/css/chat.css">
	
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href='//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800'>

@section('stylesheets')
	<link rel="stylesheet" href="/css/colpick.css">
	<link rel="stylesheet" href="/css/lightbox.css">
	<style>
		.sidebar {
			float: left;
			width: 200px;
		}

		.left_menu {
			list-style-type: none;
		}

		.left_menu li {
			padding-left: 10px;
			display: block;
			height: 30px;
			line-height: 30px;
			width: 100%;
			border-bottom: 1px solid #A7A7A7;

			cursor: pointer;
		}

		.left_menu li.active {
			background-color: #F88234;
			color: white;
			font-weight: bold;
		}

		.left_menu li i {
			width: 20px;
			text-align: center;
		}

		.left_menu li:hover {
			background-color: #D6D6D6;
		}

		.content {
			position: relative;
		
			border-left: 1px solid #A7A7A7;

			padding: 15px;
		}

		.content input[type='text'], .content select, .content input[type='password'] {
			width: 400px;
		}

		.content h1 {
			margin: 0;

			margin-bottom: 20px;
		}

		.content .tab {
			display: none;
		}

		td {
			height: 50px;
		}

		.template-thumb {
			float: left;
			border: 2px solid gray;
			margin-bottom: 10px;
		}

		.template-thumb:not(:last-child) {
			margin-right: 10px;
		}

		.template-thumb button.active {
			background-color: #00b102;
		}

		.template-thumb h2 {
			font-size: 12pt;
			text-align:center;
			margin: 5px;
		}
	</style>
@stop

@section('content')
	<div class="frame">
		<div class="bit-1">
			<div class="container">
				<div class="container_title orange">
					System configuration
				</div>
				<div class="container_content nop" style='overflow: hidden;'>
				{{--	{{ Form::model($_ENV['settings'], array('files' => true)) }} --}}
					{{ Form::model('settings', array('files' => true)) }}
					<div class='sidebar'>
						<ul class='left_menu'>
							<li data-tab='general' class='active'>
								<i class="fa fa-gear"></i> General Settings
							</li>
							<li data-tab='customer'>
								<i class="fa fa-user"></i> Customer Settings
							</li>
							<li data-tab='quotesInvoices'>
								<i class="fa fa-dollar"></i> Quotes & Invoices
							</li>
							<li data-tab='email'>
								<i class="fa fa-envelope-o"></i> Email Settings
							</li>
							<li data-tab='templates'>
								<i class="fa fa-sitemap"></i> Template Settings
							</li>
							<li data-tab='sepa'>
								<i class="fa fa-euro"></i> SEPA Settings
							</li>
							<li data-tab='company'>
								<i class="fa fa-building"></i> Company Settings
							</li>
						</ul>
						<br>
						<div class="containerpadding">
							<button type='submit' class="btn btn-green" style='width: 80%; margin-left: 10%;'>
								<i class="fa fa-save"></i> Save settings
							</button>
						</div>
					</div>
					<div class='content' id='config_content' style='height: 600px; overflow: auto;'>
						<div class="tab" id="general">
							<h1><i class="fa fa-gear"></i> General settings</h1>
							
							<table class="form" style='width:70%;'>
								<tr>
									<td style='width: 200px;'><i class="help" data-tooltip='Your application name. This is most likely your company name + CRM.'></i> Application name</td>
									<td style='width: 100%;'>
										{{ Form::text('app_name') }}
									</td>
								</tr>
								<tr>
									<td><i class="help" data-tooltip="Your company's logo. This will appear on the login page, quotes and invoices and more."></i> New logo</td>
									<td>
										{{ Form::file('logo') }}
									</td>
								</tr>
								<tr>
									<td><i class="help" data-tooltip="Your company's icon. This will show up next to your URL bar. It will also be used by your phone if you add the website to your homescreen!"></i> New icon (1:1 ratio)</td>
									<td>
										{{ Form::file('icon') }}
									</td>
								</tr>
								<tr>
									<td><i class="help" data-tooltip="Checking this box will enable the chat system on the CRM. This will allow you to chat with your collegues!"></i> Chat enabled</td>
									<td>
										{{ Form::hidden('chatEnabled', 0) }}
										{{ Form::checkbox('chatEnabled', 1) }}
									</td>
								</tr>
							</table>
							<br><br>
							<table style='width: 100%; table-layout: fixed;'>
								<tr>
									<td style='width: 50%;'><h2>Current Logo</h2></td>
									<td style='width: 50%;'><h2>Current Icon</h2></td>
								</tr>
								<tr>
									<td><img src="logo.png" width='256' alt="Current logo"></td>
									<td><img src="icon.png" width='64' alt="Current icon"></td>
								</tr>
							</table>
						</div>
						<div class="tab" id="customer">
							<h1><i class="fa fa-user"></i> Customer Settings</h1>
							
							<table class="form" style='width:70%;'>
								<tr>
									<td style='width: 200px;'><i class="help" data-tooltip='Every customer has 2 notes fields. Here you can name the first one to whatever you want!'></i> Notes Name</td>
									<td style='width: 100%;'>
										{{ Form::text('notes1Name') }}
									</td>
								</tr>
								<tr>
									<td style='width: 200px;'><i class="help" data-tooltip='Every customer has 2 notes fields. Here you can name the second, alternative notes field.'></i> Secondary Notes Name</td>
									<td style='width: 100%;'>
										{{ Form::text('notes2Name') }}
									</td>
								</tr>
							</table>
						</div>
						<div class="tab" id="quotesInvoices">
							<h1><i class="fa fa-dollar"></i> Quotes & Invoices</h1>

							<table class="form" style='width:70%;'>
								<tr>
									<td style='width: 200px;'><i class="help" data-tooltip='This VAT type will automatically be selected when creating a quote or invoice.'></i> Default Vat</td>
									<td style='width: 100%;'>{{ Form::select('defaultVat', Vat::all()->pluck('type', 'id')) }}</td>
								</tr>
								<tr>
									<td><i class="help" data-tooltip='Select which job status you want to set automatically for new quotes.'></i> Default Job Status</td>
									<td>{{ Form::select('defaultJobStatus', JobStatus::all()->pluck('type', 'id')) }}</td>
								</tr>
								<tr>
									<td><i class="help" data-tooltip='This advertisement type will automatically get assigned to new quotes you create.'></i> Default advertisement type</td>
									<td>{{ Form::select('defaultAdType', AdType::all()->pluck('type', 'id')) }}</td>
								</tr>
								<tr>
									<td>IRPF</td>
									<td>
										{{ Form::checkbox('invoiceHasIrpf') }} Invoices have IRPF
									</td>
								</tr>
								<tr>
									<td><i class="help" data-tooltip='Defines whether or not you can define a visit date in quotes. Note that this visit date is per item in your quote!'></i> Visit date</td>
									<td>
										{{ Form::checkbox('quoteHasVisitDate') }} Quote entries have visit date
									</td>
								</tr>
								<tr>
									<td><i class="help" data-tooltip='Enabling this option makes required-by dates required in quotes. This allows you to easily track jobs.'></i> Enable job monitoring</td>
									<td>
										{{ Form::checkbox('jobMonitoringEnabled') }} Enabled
									</td>
								</tr>
								<tr>
									<td><i class="help" data-tooltip='The default bank charge for new direct debit jobs.'></i> Default Bank Charge</td>
									<td>{{ Form::text('defaultDirectDebitBankCharge', null, array('class' => 'euro', 'style' => 'width: 100px !important;')) }}</td>
								</tr>
								<tr>
									<td>
										<i class="help" data-tooltip='Here you can select which job status(ses) you see as "Pending Payment". This is used in different places. One of them is the new payment page.'></i> Payment job status(ses)
									</td>
									<td>
										{{ Form::select('awaitingPaymentJobStatusses[]', JobStatus::all()->pluck('type', 'id'), explode(',', Settings::setting('awaitingPaymentJobStatusses')), array('class' => 'select2', 'multiple' => 'multiple')) }}
									</td>
								</tr>
								<tr>
									<td>
										<i class="help" data-tooltip='Here you can select which job statusses are "completed". If a quotes job status is changed to any of these statusses, the completed date will automatically be filled in.'></i> Completed job status(ses)
									</td>
									<td>
										{{ Form::select('completedJobStatusses[]', JobStatus::all()->pluck('type', 'id'), explode(',', Settings::setting('completedJobStatusses')), array('class' => 'select2', 'multiple' => 'multiple')) }}
									</td>
								</tr>
							</table>
						</div>
						<div class="tab" id="email">
							<h1><i class="fa fa-envelope-o"></i> Email settings</h1>

							<table class="form" style='width:70%;'>
								<tr style='height: 300px;'>
									<td style='width: 200px;' valign='top'><i class="help" data-tooltip='The template for sending a quote by email. You can use 2 variables inside this template:<br><br>- {customer_name} will be replaced with the customer name<br>- {quote_id} will be replaced with the quote number.'></i> Quote email template</td>
									<td style='width: 100%;'>
										{{ Form::textarea('quoteEmailTemplate', null, array('style' => 'height:200px;'))}}
									</td>
								</tr>
								<tr style='height: 300px;' valign='top'>
									<td><i class="help" data-tooltip='The template for sending an invoice by email. You can use 2 variables inside this template:<br><br>- {customer_name} will be replaced with the customer name<br>- {invoice_id} will be replaced with the invoice number.'></i> Invoice email template</td>
									<td>
										{{ Form::textarea('invoiceEmailTemplate', null, array('style' => 'height:200px;'))}}
									</td>
								</tr>
								<tr>
									<td><i class="help" data-tooltip='The title of the email template. See this image:<br><br><img src="/img/tooltips/email_template.png">'></i> Email Title</td>
									<td>{{ Form::text('emailTitle') }}</td>
								</tr>
								<tr style='height: 100px;' valign='top'>
									<td><i class="help" data-tooltip='The content of the orange footer bar at the bottom of the email. See this image:<br><br><img src="/img/tooltips/email_template.png">'></i> Email Footer Bar</td>
									<td>
										{{ Form::textarea('emailFooterBar', null, array('style' => 'height:100px;'))}}
									</td>
								</tr>
								<tr style='height: 100px;' valign='top'>
									<td><i class="help" data-tooltip='The content of the copyright footer bar at the bottom of the emails you send out. See this image:<br><br><img src="/img/tooltips/email_template.png">'></i> Email Footer Copyright</td>
									<td>
										{{ Form::textarea('emailFooterCopyright', null, array('style' => 'height:100px;'))}}
									</td>
								</tr>
								<tr>
									<td><i class="help" data-tooltip="Your SMTP host. This is needed for sending emails from the system. If you don't know your SMTP details please contact your webmaster / IT support company."></i> SMTP Host</td>
									<td>{{ Form::text('smtpHost') }}</td>
								</tr>
								<tr>
									<td><i class="help" data-tooltip="Your SMTP port. This is needed for sending emails from the system. If you don't know your SMTP details please contact your webmaster / IT support company."></i> SMTP Port</td>
									<td>{{ Form::text('smtpPort') }}</td>
								</tr>
								<tr>
									<td><i class="help" data-tooltip="The encryption type your SMTP server uses, if any. If you don't know your SMTP details please contact your webmaster / IT support company."></i> SMTP Encryption</td>
									<td>{{ Form::select('smtpEncryption', ['none' => 'No encryption', 'tls' => 'TLS', 'ssl' => 'SSL']) }}</td>
								</tr>
								<tr>
									<td><i class="help" data-tooltip="Your SMTP username. This is needed for sending emails from the system. If you don't know your SMTP details please contact your webmaster / IT support company."></i> SMTP Username</td>
									<td>{{ Form::text('smtpUsername') }}</td>
								</tr>
								<tr>
									<td><i class="help" data-tooltip="Your SMTP password. This is needed for sending emails from the system. If you don't know your SMTP details please contact your webmaster / IT support company."></i> SMTP Password</td>
									<td>{{ Form::password('smtpPassword') }}</td>
								</tr>
								<tr>
									<td><i class="help" data-tooltip='The name you want to send emails from. This is the name people that receive your emails will see.'></i> SMTP Sender Name</td>
									<td>{{ Form::text('smtpSenderName') }}</td>
								</tr>
								<tr>
									<td><i class="help" data-tooltip="The email you want to send emails from. This is the email people that receive your emails will see as the sender. Please note that this email should actually exist on your SMTP server. If you don't know your SMTP details please contact your webmaster / IT support company."></i> SMTP Sender Email</td>
									<td>{{ Form::text('smtpSenderEmail') }}</td>
								</tr>
							</table>
						</div>
						<div class="tab" id="templates">
							<h1><i class="fa fa-sitemap"></i> Template Settings</h1>

							<table class="form" style='width:70%;'>
								<tr>
									<td style='width: 200px;'><i class="help" data-tooltip='We have multiple quote and invoice templates available. Please select the one you want here.'></i> Select template</td>
									<td style='width: 100%;'>
										{{ Form::radio('quoteTemplate', 1) }} Line - Extended
										<br>
										{{ Form::radio('quoteTemplate', 2)}} Line - Compact
									</td>
								</tr>
								<tr>
									<td><i class="help" data-tooltip='Every template has a main color. Here you can select this color. Try to match this color with the main color of your logo.'></i> Template color</td>
									<td>
										<input type='hidden' class='colorpick_input' name='templateColor' value='{{ Settings::setting("templateColor") }}'>
										<div class='colorpick'></div>
									</td>
								</tr>
								<tr>
									<td><i class="help" data-tooltip='This text will appear below the products on your quotes. See this image:<br><img src="/img/tooltips/quote_template.png">'></i> Quote text</td>
									<td>{{ Form::textarea('quoteText', null, array('style' => 'height: 100px;')) }}</td>
								</tr>
								<tr>
									<td><i class="help" data-tooltip='This text will appear below the quotes on your invoice. See this image:<br><img src="/img/tooltips/quote_template.png">'></i> Invoice text</td>
									<td>{{ Form::textarea('invoiceText', null, array('style' => 'height: 100px;')) }}</td>
								</tr>
								<tr>
									<td><i class="help" data-tooltip='This text will appear below the products on your receipt. See this image:<br><img src="/img/tooltips/quote_template.png">'></i> Receipt text</td>
									<td>{{ Form::textarea('receiptText', null, array('style' => 'height: 100px;')) }}</td>
								</tr>
								<tr>
									<td><i class="help" data-tooltip='This line will appear below the bank details on your invoices. See this image:<br><img src="/img/tooltips/quote_template.png">'></i> Additional invoice line</td>
									<td>{{ Form::text('additionalInvoiceLine', null, array('style' => 'width: 100%;')) }}</td>
								</tr>
							</table>
						</div>
						<div class="tab" id="sepa">
							<h1><i class="fa fa-euro"></i> SEPA Settings</h1>

							<h3>General Batch Information</h3>
							<table class="form" style='width:70%;'>
								<tr>
									<td style='width: 200px;'>Initiating Party Name</td>
									<td style='width: 100%;'>
										{{ Form::text('sepa_initiating_party') }}
									</td>
								</tr>
							</table>
							<br><br>

							<h3>Payment Information</h3>
							<table class="form" style='width:70%;'>
								<tr>
									<td style='width: 200px;'>Batch Booking</td>
									<td style='width: 100%;'>
										{{ Form::checkbox('sepa_batch_booking') }}
									</td>
								</tr>
								<tr>
									<td>Local Instrument Code</td>
									<td>{{ Form::text('sepa_local_instrument_code') }}</td>
								</tr>
								<tr>
									<td>Sequence Type</td>
									<td>{{ Form::text('sepa_sequence_type') }}</td>
								</tr>
								<tr>
									<td>Default Collection Date</td>
									<td>Today + {{ Form::text('sepa_default_collection_date', null, array('style' => 'width: 100px !important;')) }} days</td>
								</tr>
							</table>
							<br><br>

							<h3>Creditor Information</h3>
							<table class="form" style='width:70%;'>
								<tr>
									<td style='width: 200px;'>Creditor Name</td>
									<td style='width: 100%;'>
										{{ Form::text('sepa_creditor_name') }}
									</td>
								</tr>
								<tr>
									<td>Creditor IBAN</td>
									<td>{{ Form::text('sepa_creditor_iban') }}</td>
								</tr>
								<tr>
									<td>Creditor BIC</td>
									<td>{{ Form::text('sepa_creditor_bic') }}</td>
								</tr>
								<tr>
									<td>Creditor Scheme Identification</td>
									<td>{{ Form::text('sepa_creditor_scheme_identification') }}</td>
								</tr>
							</table>
						</div>
						<div class="tab" id="company">
							<h1><i class="fa fa-building"></i> Company Information</h1>
							<table class="form" style='width:70%;'>
								<tr>
									<td style='width: 200px;'><i class="help" data-tooltip='Your company name. This will appear on your quotes, invoices and more.'></i> Company Name</td>
									<td style='width: 100%;'>
										{{ Form::text('companyName') }}
									</td>
								</tr>
								<tr>
									<td><i class="help" data-tooltip='Your company address, telephone number and other relavant information. Keep under 5 lines please.'></i> Company Address and<br>telephone number<br>(max. 4 lines)</td>
									<td>{{ Form::textarea('companyAddress', null, array('style' => 'height: 90px;')) }}</td>
								</tr>
								<tr>
									<td><i class="help" data-tooltip='Your bank details. This will go at the bottom on your invoices. Has to be 1 line only.'></i> Bank Details (1 line)</td>
									<td>{{ Form::text('bankDetails') }}</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop