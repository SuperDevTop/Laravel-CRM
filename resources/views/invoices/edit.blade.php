@extends('master')
<?php 
	use App\Models\invoices\Invoice;
	use App\Models\Settings;
?>

@section('page_name')
	Invoice #{{ $invoice->id }}
@stop

@section('breadcrumbs')
	<a href='/customers'>Customers</a> <i class="breadcrumb"></i> <a href='/customers/{{ $customer->id }}'>{{ $customer->getCustomerName() }}</a> <i class="breadcrumb"></i> Invoice #{{ $invoice->id }}
@stop

@section('page_header')
	<button type='button' class='btn btn-default ml20' onClick="$('#rootScopeElement').scope().backToCustomer()">
		<i class="fa fa-sitemap"></i> Back to customer overview
	</button>
	@if (Invoice::max('id') != $invoice->id)
		<button type='button' class='btn btn-orange' data-tooltip='Go to next quote (#{{ $invoice->id + 1 }})' data-href='invoices/{{ Invoice::where("id", ">", $invoice->id)->orderBy("id", "ASC")->get()->first()->id }}/edit'>
			<i class="fa fa-forward"></i>
		</button>
	@endif
	<input type='text' id='quickInvoiceId' placeholder='#' value='{{ $invoice->id }}' class='fr' style='width: 80px; margin-top: 7px; text-align: center; font-size: 12px;'>
	@if (Invoice::min('id') != $invoice->id)
		<button type='button' class='btn btn-orange ml20' data-tooltip='Go to previous quote (#{{ $invoice->id - 1 }})' data-href='invoices/{{ Invoice::where("id", "<", $invoice->id)->orderBy("id", "DESC")->get()->first()->id }}/edit'>
			<i class="fa fa-backward"></i>
		</button>
	@endif
	@if(Invoice::max('id') == $invoice->id)
		<button class="btn btn-red" data-tooltip='This will permanently delete this invoice. Please note that this is only available on the last entered invoice.' onclick='confirmDelete()'>
			<i class="fa fa-trash-o"></i> Delete invoice
		</button>
	@endif
	<!-- <button class="btn btn-red" data-tooltip='Voiding an invoice means creating a new, negative invoice that counters this invoice.' onclick='confirmVoid()'>
		<i class="fa fa-ban"></i> Void invoice
	</button> -->
@stop

@section('scripts')
	<script src='/js/ui-select.js'></script>
	<script type='text/javascript' src='js/maskedinput.min.js'></script>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.0-beta.1/angular-sanitize.min.js'></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-select/0.13.2/select.css">
	<link rel="stylesheet" href="/css/selectize.css">
	<script>
		$(function() {
			// Enter binding for the quick quote ID
			$('#quickInvoiceId').on('keyup', function(event) {
				if (event.which != 13)
					return;

				location.assign('/invoices/' + $(this).val() + '/edit');
			});

			$(window).on('beforeunload', function(event) {
				if ($('#rootScopeElement').scope().dirty) {
					return 'WARNING - WARNING - WARNING - WARNING - WARNING\r\nWARNING - WARNING - WARNING - WARNING - WARNING\r\nYou have NOT saved your invoice changes yet!\r\nIf you close this page now, you lose your changes!';
				}
			});

			$('#newComment').on('keydown', function(event) {
				if (event.which == 13) { // return
					if ($('#newComment').val() == '')
						return; // We down't want empty comments!
					var theScope = $(this).scope();
					ajaxRequest(
						'invoice_new_comment',
						{
							invoiceId: {{ $invoice->id }},
							comment: $('#newComment').val()
						},
						function(data) {
							var currentdate = new Date(); 
							var datetime = ("0" + currentdate.getDate()).slice(-2) + "-"
							                + ("0" + (currentdate.getMonth() + 1)).slice(-2)  + "-" 
							                + currentdate.getFullYear() + " "  
							                + currentdate.getHours() + ":"  
							                + currentdate.getMinutes()
							// Add it to the scope object
							
							theScope.comments.push({
								id: data.commentId,
								placedOn: datetime,
								placedBy: {{ Auth::id() }},
								placedByInitials: '{{ Auth::user()->initials }}',
								comment: $('#newComment').val()
							});
							theScope.$apply(); // Apply the change as we are outside of the digest loop
							
							$('#newComment').val('');
							$('#commentTable').animate({
								scrollTop: $(this).height()
							}, 2000);
							showSuccess('Your comment has been placed');
						}
					);
				}
			});
		});

		var app = angular.module('app', ['ui.select', 'ngSanitize'])
		.config(function($interpolateProvider, uiSelectConfig){
		        $interpolateProvider.startSymbol('<%').endSymbol('%>');
		        uiSelectConfig.theme = 'selectize';
		    }
		);

		// Here we have the angular controller
		app.controller('invoiceCtrl', ['$scope', '$http', '$compile', function($scope, $http, $compile) {
			// Initialize data
			$scope.invoiceData = {
				vat: 2
			};
			$scope.vats = [];
			$scope.vatValues = {};
			
			// angel -> irpf
			$scope.irpfs = [];
			$scope.irpfValues = {};
						
			
			$scope.vat_per=0;
			$scope.irpf_var=0;
			$scope.subtotal=0;
			$scope.invoicewithIrpf=0;
			//--------------
			
			$scope.entries = {};
			$scope.direct_debit = {
				applyBankCharge: false,
				description: ""
			};
			$scope.dirty = false;
			$scope.initialized = false;

			$scope.$compile = $compile;
			
			// Receive the invoice data
			ajaxRequest(
				'get_invoice',
				{
					invoiceId: {{ $invoice->id }}
				},
				function(data) {
					$scope.invoiceData = data.invoiceData;					
					$scope.vats = data.vats;
					$scope.vatValues = data.vatValues;
					$scope.invoiceData.vat = $scope.invoiceData.vat.toString();

					$scope.irpfs = data.irpfs;
					$scope.irpfValues = data.irpfValues;
					$scope.invoiceData.irpf = $scope.invoiceData.irpf.toString();

					$scope.invoicewithIrpf = data.invoicewithIrpf;

					$scope.vat_per=$scope.invoiceData.vat_per;
					$scope.irpf_var=$scope.invoiceData.irpf_per;
					$scope.subtotal=$scope.invoiceData.subtotal;

					
					$scope.entries = data.entries;
					
					$scope.settings = data.settings;
					$scope.customer = data.customer;

					$scope.comments = data.comments;

					$scope.direct_debit.description = $scope.invoiceData.description;
					$scope.direct_debit.bankCharge = data.defaultBankCharge;

					
					
					$scope.emailData = {};
					$scope.emailData.to = $scope.customer.email;
					$scope.emailData.cc = '';
					$scope.emailData.bcc = '';
					$scope.emailData.subject = 'Invoice #' + $scope.invoiceData.id;
					$scope.emailData.message = data.emailTemplate;
					$scope.emailData.message = $scope.emailData.message.replace('{customer_name}', $scope.customer.contactName);
					$scope.emailData.message = $scope.emailData.message.replace('{invoice_id}', $scope.invoiceData.id);

					// If we have chosen to automatically select the send copy checkbox, set that value
					if (localStorage.getItem('autoTickInvoiceEmailCopy') == 1) {
						$scope.emailData.sendmeacopy = true;
					} else {
						$scope.emailData.sendmeacopy = false;
					}

					$scope.$apply();
					$('select').trigger('chosen:updated');
					$scope.initialized = true;
				}
			);

			// Watches for quoteData and entries to detect a dirty page
			$scope.$watch('entries', function(newValues, oldValues, scope) {
				if (!$scope.initialized)
					return;
				$scope.dirty = true;
			}, true);
			$scope.$watch('invoiceData', function(newValues, oldValues, scope) {
				if (!$scope.initialized)
					return;
				$scope.dirty = true;
			}, true);

			// Function for to get Quote
			$scope.getQuoteData = function(entry) {
				if (entry.quoteId == '')
					return;

				// Check the backend to see if the quote exists. If it does exist, update the description to the quote title, and set the prices
				ajaxRequest(
					'get_invoice_quote',
					{
						quoteId: entry.quoteId
					},
					function(data) {
						if (data.success) {
							entry.description = data.quote.description;
							entry.unitPrice = data.quote.subtotal.toFixed(2);
							entry.supCosts = data.quote.supCosts.toFixed(2);
							notify('Quote added to invoice succesfully');
						} else {
							showError(data.message);
							if (!data.success)
								entry.quoteId = ''; 
						}
						$scope.$apply();
					}
				);
			};

			// Function for to add a new "quotedetails"
			$scope.addNewEntry = function() {
				$scope.entries.push(
					{
						id: -1,
						quoteId: 0,
						productId: -999,
						quantity: 1,
						unitPrice: 0,
						discount: 0,
						description: '',
						supCosts: 0
					}
				);

				$('select').trigger('chosen:updated');
			};

			// Function for delete entry
			$scope.deleteEntry = function(entry) {
				$scope.entries.splice($scope.entries.indexOf(entry), 1);
			};

			// Function for Preview Quote
			$scope.showQuotePreview = function(entry) {
				window.open('quotes/' + entry.quoteId + '/edit');
			};

			// Function for back to Customer
			$scope.backToCustomer = function() {
				window.location.assign('/customers/' + $scope.invoiceData.customer);
			};

			// Subtotal product to product with discount from detail lines
			$scope.getSubTotal = function() {
				var total = 0;
				$.each($scope.entries, function(i, e) {
					total += (e.unitPrice * e.quantity) * (1 - (e.discount/100));
				});

				$scope.invoiceData.subtotal = total;
				
				return total;
			};

			// Function for to get vat
			$scope.getVat = function() {				
					return $scope.getSubTotal() * ($scope.parseFloat($scope.vatValues[$scope.invoiceData.vat]/100));
			};

			// Angel get fee for irpf
			$scope.getIrpf = function() {
				if ($scope.invoicewithIrpf!=0)
					return $scope.getSubTotal() * ($scope.parseFloat($scope.irpfValues[$scope.invoiceData.irpf]/100));
				else
					return 0;
			};

			$scope.getVat_per = function() {
				return $scope.parseFloat($scope.vatValues[$scope.invoiceData.vat]);
			}

			// Angel get irpf's import 
			$scope.getIrpf_per = function() {
				if ($scope.invoicewithIrpf!=0)
					return $scope.parseFloat($scope.irpfValues[$scope.invoiceData.irpf]);
				else
					return 0;
			}



			// Angel modify line 265
			$scope.getTotal = function() {
				var supCosts = 0;
				angular.forEach($scope.entries, function(entry) {
					supCosts += $scope.parseFloat(entry.supCosts);
				});

				$scope.invoiceData.vat_per = $scope.getVat_per();

				if ($scope.invoicewithIrpf!=0)
				{
    				
    				$scope.invoiceData.irpf_per = $scope.getIrpf_per(); 
    				
    				return $scope.getSubTotal() + $scope.getVat() + $scope.getIrpf() + supCosts;
				}
				else
				{
					return $scope.getSubTotal() + $scope.getVat() + supCosts;
				}
			};
			

			// Function for to save invoice
			$scope.saveInvoice = function() {
				// Show loading icon
				$('#saveBtn').html('<i class="fa fa-spinner fa-spin"></i> Saving quote...');
				$('#saveBtn').removeClass('green').addClass('orange');

				ajaxRequest(
					'save_invoice',
					{
						invoiceId: $scope.invoiceData.id,
						invoice: $scope.invoiceData,
						entries: $scope.entries
					},
					function(data) {
						if (data.success) {
							$('#saveBtn').removeClass('orange').addClass('green');
							$('#saveBtn').html('<i class="fa fa-check"></i> Invoice saved!');
							setTimeout(function() {
								$('#saveBtn').html('<i class="fa fa-check"></i> Save invoice!');
								$scope.dirty = false;
								$scope.$apply();
							}, 2000);
						}
					}
				);
			};
			
			$scope.sendEmail = function() {

				// Don't allow sending emails when the user doesn't have his email filled in...
				@if (Auth::user()->companyEmail == '')
					$('#mask').trigger('click');
					setTimeout(function() {
						showAlert('Whoops!', 'Could not send invoice.<br><br>In order to send emails from Pepper you have to fill in your company email in your profile.<br><br>When people respond to emails you send from Pepper, the email will go back to your email.');
					}, 100);
					return;
				@endif

				// Check if everything looks OK
				var emailExp = new RegExp('[A-z0-9._%+-]+@[A-z0-9.-]+\.[A-z]{2,4}');
				
				var toEmails = $scope.emailData.to.split(/[;,]+/);
				var ccEmails = $scope.emailData.cc.split(/[;,]+/);
				var bccEmails = $scope.emailData.bcc.split(/[;,]+/);

				$('#email_to').css('background-color', 'white');
				$('#email_cc').css('background-color', 'white');
				$('#email_bcc').css('background-color', 'white');

				var allOK = true;
				var errors = '';
				if ($scope.emailData.to != '') {
					angular.forEach(toEmails, function(email) {
						if (!emailExp.test(email.trim())) {
							$('#email_to').css('background-color', '#FFCACA');
							allOK = false;
						}
					});
				} else {
					$('#email_to').css('background-color', '#FFCACA');
					allOK = false;
				}

				if ($scope.emailData.cc != '') {
					angular.forEach(ccEmails, function(email) {
						if (!emailExp.test(email.trim())) {
							$('#email_cc').css('background-color', '#FFCACA');
							allOK = false;
						}
					});
				}

				if ($scope.emailData.bcc != '') {
					angular.forEach(bccEmails, function(email) {
						if (!emailExp.test(email.trim())) {
							$('#email_bcc').css('background-color', '#FFCACA');
							allOK = false;
						}
					});
				}
				if (!allOK) {
					return;
				}

				// Change button text to sending and disable the button
				$('#sendEmailButton').html('<i class="fa fa-spinner fa-spin"></i> Sending email...');

				var formData = new FormData();
				formData.append('invoiceId', $scope.invoiceData.id);
				formData.append('to', $scope.emailData.to.trim());
				formData.append('cc', $scope.emailData.cc.trim());
				formData.append('bcc', $scope.emailData.bcc.trim());
				formData.append('subject', $scope.emailData.subject);
				formData.append('message', $scope.emailData.message);
				formData.append('sendcopy', $scope.emailData.sendmeacopy);

				// Attach the additional attachment (if any)
				if ($('#additional_attachment').val() != '') {
					formData.append('additional_attachment', $('#additional_attachment').first().get(0).files[0]);
				}

				$.ajax({
					url: '/ajax/send_invoice_email',
					type: 'POST',
					data: formData,
					contentType: false,
					processData: false,
					success: function(data) {
						if (data.success) {
							showSuccess('Invoice emailed succesfully');
							$('#mask').trigger('click');
						} else {
							showError('Email invoice failed:<br>' + data.message + '<br><br>Please check your email SMTP settings.', 7);
						}
						$('#sendEmailButton').html('<i class="fa fa-send"></i> Send invoice');
					}
				});
			};

			$scope.confirmDirectDebit = function() {
				
				var pattern = new RegExp(/^([\-A-Za-z0-9\+\/\?:\(\)\., ]){1,35}$/);
				if (pattern.test($scope.direct_debit.description) !== true) {
					showError('You may only use regular letters (no accents!), digits, whitespaces and any of the following characters in the description: <b>- + / ? : ( ) .</b><br>You can use a maximum of 35 characters.');
					return;
				}

				ajaxRequest(
					'invoice_add_to_direct_debit_queue',
					{
						invoiceId: $scope.invoiceData.id,
						description: $scope.direct_debit.description,
						bankCharge: $scope.direct_debit.bankCharge,
						applyBankCharge: $scope.direct_debit.applyBankCharge
					},
					function(data) {
						if (data.success) {
							showSuccess('Invoice succesfully added to Direct Debit queue');
							$('#modal_window #modal_window_header_close').trigger('click');
						}
					}
				);
			};

			$scope.editComment = function(event, comment) {
				comment.editing = true;
				setTimeout(function() {
						$(event.target).closest('tr').find('input[type="text"]').focus();
				}, 1);
			};

			$scope.saveComment = function(comment) {
				ajaxRequest(
					'update_invoice_comment',
					{
						commentId: comment.id,
						newComment: comment.comment
					},
					function(data) {
						if (data.success) {
							delete comment.editing;
							showSuccess('Comment saved succesfully');
							$scope.$apply();
						}
					}
				);
			};

			$scope.deleteComment = function(comment) {
				confirmDialog(
					'Are you sure?',
					'Are you sure you want to delete this comment?',
					function() {
						ajaxRequest(
							'delete_invoice_comment',
							{
								commentId: comment.id
							},
							function(data) {
								if (data.success) {
									$scope.comments.splice($scope.comments.indexOf(comment), 1);
									$scope.$apply();
									showSuccess('Comment deleted succesfully');
								}
							}
						);
					}
				);
			};

			$scope.openEmailModal = function() {
				setTimeout(function() {
					$scope.$apply();
				}, 10);
			};

			$scope.parseFloat = function(input) {
				if (!input || input == '')
				return 0;

				return parseFloat(input);
			};

			$scope.toggleSendCopyOfEmail = function() {
				if ($scope.emailData.sendmeacopy == 0) {
					confirmDialog(
						'Remind setting?',
						'You have chosen to send a copy of this email to your own email. Do you want this option to be selected by default in the future?',
						function() {
							localStorage.setItem('autoTickInvoiceEmailCopy', 1);
						}
					);
				} else {
					confirmDialog(
						'Remind setting?',
						'You have chosen not to send a copy of this email to your own email. Do you want this option to be disabled by default in the future?',
						function() {
							localStorage.setItem('autoTickInvoiceEmailCopy', 0);
						}
					);
				}
			};

			$scope.openEmail = function(comment) {
				ajaxRequest(
					'get_invoice_email',
					{
						emailId: comment.emailId
					},
					function(data) {
						$scope.viewing_email = data.email;
						if (data.attachment)
							$scope.viewing_email.attachment = data.attachment;

						$scope.$apply();
						openModal('View email', $('#view_invoice_email_modal'), 600, true);
						$scope.$apply();
					}
				);
			};

			$scope.downloadEmailAttachment = function(email) {
				window.open('/invoiceemailattachment/' + email.id);
			}

			$scope.downloadEmailAdditionalAttachment = function(email) {
				window.open('/invoiceemailadditionalattachment/' + email.id);
			}

			$scope.resendEmail = function() {
				// Don't allow sending emails when the user doesn't have his email filled in...
				@if (Auth::user()->companyEmail == '')
					$('#mask').trigger('click');
					setTimeout(function() {
						showAlert('Whoops!', 'Could not send invoice.<br><br>In order to send emails from Pepper you have to fill in your company email in your profile.<br><br>When people respond to emails you send from Pepper, the email will go back to your email.');
					}, 100);
					return;
				@endif

				// Check if everything looks OK
				var emailExp = new RegExp('[A-z0-9._%+-]+@[A-z0-9.-]+\.[A-z]{2,4}');
				
				var toEmails = $scope.viewing_email.to.split(/[;,]+/);
				var ccEmails = $scope.viewing_email.cc.split(/[;,]+/);
				var bccEmails = $scope.viewing_email.bcc.split(/[;,]+/);

				var allOK = true;
				var errors = '';
				if ($scope.viewing_email.to != '') {
					angular.forEach(toEmails, function(email) {
						if (!emailExp.test(email.trim())) {
							$('#email_to').css('background-color', '#FFCACA');
							allOK = false;
						}
					});
				} else {
					$('#email_to').css('background-color', '#FFCACA');
					allOK = false;
				}

				if ($scope.viewing_email.cc != '') {
					angular.forEach(ccEmails, function(email) {
						if (!emailExp.test(email.trim())) {
							$('#email_cc').css('background-color', '#FFCACA');
							allOK = false;
						}
					});
				}

				if ($scope.viewing_email.bcc != '') {
					angular.forEach(bccEmails, function(email) {
						if (!emailExp.test(email.trim())) {
							$('#email_bcc').css('background-color', '#FFCACA');
							allOK = false;
						}
					});
				}
				if (!allOK) {
					return;
				}

				// 
				// Change button text to sending and disable the button
				$('#sendEmailButton').html('<i class="fa fa-spinner fa-spin"></i> Sending email...');
				ajaxRequest(
					'resend_invoice_email',
					{
						invoiceId: $scope.invoiceData.id,
						emailId: $scope.viewing_email.id,
						to: $scope.viewing_email.to.trim(),
						cc: $scope.viewing_email.cc.trim(),
						bcc: $scope.viewing_email.bcc.trim(),
						subject: $scope.viewing_email.subject,
						message: $scope.viewing_email.body,
						sendcopy: $scope.viewing_email.sendmeacopy
					},
					function(data) {
						if (data.success) {
							showSuccess('Invoice emailed succesfully');
							$('#mask').trigger('click');
						} else {
							showError('Email invoice failed:<br>' + data.message + '<br><br>Please check your email SMTP settings.', 7);
						}
						$('#sendEmailButton').html('<i class="fa fa-send"></i> Resend invoice');
					}
				);
			};

			$scope.openCustomerChangeModal = function() {
				$('#new_customer_select').select2({
					width: '100%',
					ajax: {
						url: 'ajax/search_customer_id',
						type: 'POST',
						dataType: 'json',
						delay: 200,
						data: function(params) {
							return {
								query: params.term
							}
						},
						processResults: function(data) {
							return {
								results: data.customers.map(function(customer) {
									console.log(customer);
				                    return {
				                        text: customer.companyName,
				                        id: customer.id
				                    }
				                })
							}
						},
						cache: true
					},
					minimumInputLength: 2
				});
			};

			$scope.confirmCustomerChange = function() {
				if (!$('#new_customer_select option:selected').val()) {
					showError("Please select a customer first");
					return false;
				}
				confirmDialog(
					'Are you sure?',
					'Are you absolutely sure you want to change the customer of this invoice? By clicking yes you agree that this action is your own responsability.',
					function() {
						ajaxRequest(
							'invoice_change_customer',
							{
								invoiceId: {{ $invoice->id }},
								customerId: $('#new_customer_select option:selected').val()
							},
							function(data) {
								if (!data.success)
									showError(data.message);
								else {
									location.reload();
								}
							}
						);
					}
				);
			}
			// Returns whether the current browser supports async file uploads. Thsi defines whether the user can upload addition attachments in the emails
			$scope.supportsAsyncFileUploads = function() {
				return (typeof(window.FormData) !== "undefined");
			};

			$scope.validateAttachment = function(event) {
				// Get the input
				var $input = $('#additional_attachment').first();

				// Get the file
				var file = $input.get(0).files[0];

				// Validate the file
				if (file.size > 10000000) {
					showError("Sorry, your attachment can't be larger than 10MB.")
					event.preventDefault();
					$input.val('');
					return false;
				}
			}


			$('select').first().trigger('chosen:activate');
		}]);

		app.directive('chosen', function() {
			return {
				restrict: 'A',	
				link: function(scope, element, attrs) {
					$(element).chosen({
						width: '100%'
					})
					.change(function() {
						// If the product id is a quote, return
						if ($(element).val() == '-999') {
							$(element).parent().next('td').find('input').first().focus();
							return;
						}

						// Get the price of the item, and put it in the row
						ajaxRequest(
							'get_product_price',
							{
								productId: $(element).val()
							},
							function(data) {
								scope.entry.unitPrice = data.productPrice;
								scope.$apply();
							}
						);
					});
				}
			};
		});

		app.directive('tabisnewentry', function() {
			return {
				restrict: 'A',
				link: function(scope, element, attrs) {
					$(element).on('keydown', function(event) {
						if (event.which == 9) { // tab
							// Only react if its the last one in the array
							if (!scope.$last) return;
							scope.entries.push(
								{
									id: -1,
									productId: -1,
									quoteId: 0,
									quantity: 1,
									unitPrice: 0,
									discount: 0,
									description: '',
									supCosts: 0
								}
							);
							scope.$apply();
							$('select').trigger('chosen:updated');
							$(element).closest('tr').next('tr').find('select').trigger('chosen:activate');

							event.preventDefault();
						}
					});
				}
			};
		});

		app.filter('currency', ['$filter', function($filter) {
			return function(input) {
				var curSymbol = '€ ';
				var decPlaces = 2;
				var thouSep = ',';
				var decSep = '.';

				// Check for invalid inputs
				var out = isNaN(input) || input === '' || input === null ? 0.0 : input;

				//Deal with the minus (negative numbers)
				var minus = input < 0;
				out = Math.abs(out);
				out = $filter('number')(out, decPlaces);

				// Replace the thousand and decimal separators.  
				// This is a two step process to avoid overlaps between the two
				if(thouSep != ",") out = out.replace(/\,/g, "T");
				if(decSep != ".") out = out.replace(/\./g, "D");
				out = out.replace(/T/g, thouSep);
				out = out.replace(/D/g, decSep);

				// Add the minus and the symbol
				if(minus){
					return "-" + out;
				}else{
					return out;
				}
			}
		}]);

		function confirmDelete() {
			confirmDialog(
				'Are you sure?',
				'Are you sure you want to permanently delete this invoice?<br><br><b>Note:</b> you can only delete the <b>last</b> invoice in the system. Please only use this in emergency mistakes.<br><br><span style="color: red; font-weight: bold;">This action is permanent. The invoice will be gone. Poof!</span><br><br>After deletion you will be redirected to the previous invoice.',
				function() {
					ajaxRequest(
						'delete_invoice',
						{
							invoiceId: {{ $invoice->id }}
						},
						function(data) {
							if (data.success) {
								window.location = 'invoices/{{ $invoice->id - 1 }}/edit';
							} else {
								showError('Could not delete invoice.<br><br>You can only delete the last invoice in the system. This is not the last invoice!');
							}
						}
					);
				}
			);
		}

		function confirmVoid() {
			confirmDialog(
				'Are you sure?',
				'Are you sure you want to void this invoice? This will create a new negative invoice to counter this invoice.<br><br>If you click yes you will be redirected to the new, negative invoice.',
				function() {
					ajaxRequest(
						'void_invoice',
						{
							invoiceId: {{ $invoice->id }}
						},
						function(data) {
							window.location = 'invoices/' + data.invoiceId + '/edit';
						}
					);
				}
			);
		}
	</script>
@stop

@section('stylesheets')
	<style>
		#invoice_entries input {
			width: 100%;
		}
	</style>
@stop

@section('content')
	<div ng-app='app' ng-controller='invoiceCtrl' id='rootScopeElement'>
		<div class="modal_content" id='invoice_direct_debit_modal'>
			<b>Description</b>
			<br>
			<input type='text' ng-model='direct_debit.description' style='width: 100%;'>
			<br><br>
			<b>Bank charge</b>
			<br>
			<input type='checkbox' ng-model='direct_debit.applyBankCharge'> Apply bank charge
			<input type='text' ng-model='direct_debit.bankCharge' ng-show='direct_debit.applyBankCharge' style='width: 100px;' class='euro'>
			<br><br>
			<button class="btn btn-default fr" ng-click='confirmDirectDebit()'>
				<i class="fa fa-check"></i> Add to Direct Debit queue
			</button>
			<br clear='both'>
		</div>
		<div class="modal_content" id='invoice_email_modal'>
			<p>
				Here you can email this invoice to your customer. Cc and bcc fields are available. You can enter more recipients. If you want more addresses in 1 field, use comma's or semi-colons to delimit them.
			</p>
			<br>
			<table class='form'>
				<tr>
					<td style='width: 100px;'>To:</td>
					<td style='width: 100%;'><input type='text' id='email_to' ng-model='emailData.to'></td>
				</tr>
				<tr>
					<td>Cc:</td>
					<td><input type='text' id='email_cc' ng-model='emailData.cc' placeholder='Carbon copy addresses. Ex. collegue@mycompany.com'></td>
				</tr>
				<tr>
					<td>Bcc:</td>
					<td><input type='text' id='email_bcc' ng-model='emailData.bcc' placeholder='Blind carbon copy addresses. Ex. accountant@company.org'></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td></td>
				</tr>
				<tr>
					<td>Attachments:</td>
					<td style='font-size: 10pt;'>
						<b><i class="fa fa-file-pdf-o"></i> <% "Invoice #" + invoiceData.id + ".pdf" %></b>
						<br>
						<div ng-show="supportsAsyncFileUploads()">
							<br>
							Additional attachment: <input type="file" name='additional_attachment' id='additional_attachment' onChange='return $(this).scope().validateAttachment(event)'>
						</div>
						<div ng-hide="supportsAsyncFileUploads()" style="color: red; font-weight: bold;">
							Please update your webbrowser to add additional attachments.
						</div>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td></td>
				</tr>
				<tr>
					<td>Subject:</td>
					<td><input type='text' ng-model='emailData.subject'></td>
				</tr>
				<tr>
					<td valign='top'>Message:</td>
					<td>
						<textarea style='width: 100%; height: 200px;' ng-model='emailData.message'></textarea>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<input type='checkbox' id='email_sendmeacopy' ng-model='emailData.sendmeacopy' ng-click='toggleSendCopyOfEmail()'> Send me a copy of this email (to {{ Auth::user()->companyEmail }})
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button class="btn btn-default fr" ng-click='sendEmail()' id='sendEmailButton'>
							<i class="fa fa-send"></i> Send invoice
						</button>
					</td>
				</tr>
			</table>
		</div>
		<div class="modal_content" id="view_invoice_email_modal">
			<p>
				This email has been sent to {{ $customer->getCustomername() }} on <b><% viewing_email.sentOn %></b>. You can edit the email and send it again if you wish.
			</p>
			<br>
			<table class='form'>
				<tr>
					<td style='width: 100px;'>To:</td>
					<td style='width: 100%;'><input type='text' id='email_to' ng-model='viewing_email.to'></td>
				</tr>
				<tr>
					<td>Cc:</td>
					<td><input type='text' id='email_cc' ng-model='viewing_email.cc' placeholder='Carbon copy addresses. Ex. collegue@mycompany.com'></td>
				</tr>
				<tr>
					<td>Bcc:</td>
					<td><input type='text' id='email_bcc' ng-model='viewing_email.bcc' placeholder='Blind carbon copy addresses. Ex. accountant@company.org'></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td></td>
				</tr>
				<tr>
					<td>Attachments:</td>
					<td style='font-size: 10pt; font-weight: bold;'>
						<a href='#' onclick='return false;' ng-click='downloadEmailAttachment(viewing_email)'>
							<i class="fa fa-file-pdf-o"></i> <% "Invoice #" + invoiceData.id + ".pdf" %> (click to view)
						</a>

						<div ng-show="viewing_email.attachment">
							<a href='#' onclick='return false;' ng-click='downloadEmailAdditionalAttachment(viewing_email)'>
								<i class="fa fa-file-o"></i> <% viewing_email.attachment.filename %>
							</a>
						</div>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td></td>
				</tr>
				<tr>
					<td>Subject:</td>
					<td><input type='text' ng-model='viewing_email.subject'></td>
				</tr>
				<tr>
					<td valign='top'>Message:</td>
					<td>
						<textarea style='width: 100%; height: 200px;' ng-model='viewing_email.body'></textarea>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<input type='checkbox' id='email_sendmeacopy' ng-model='viewing_email.sendmeacopy' ng-click='toggleSendCopyOfEmail()'> Send me a copy of this email (to {{ Auth::user()->companyEmail }})
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<button class="btn btn-default fr" id="sendEmailButton" ng-click='resendEmail()'>
							<i class="fa fa-send"></i> Resend email
						</button>
					</td>
				</tr>
			</table>
		</div>
		<div class="modal_content" id='invoice_change_customer'>
			<div>
				<b class='red'>
					 Changing any invoice or customer at this point is generally not permitted. Please note that you should only change the customer if you understand the accounting implications.
				</b>
				<br><br>
				Select the new customer for the invoice:
				<br>
				<select id='new_customer_select'></select>
				<br><br>
				<button class="btn btn-green" ng-click='confirmCustomerChange()'><i class="fa fa-exclamation-triangle"></i> Change customer</button>
			</div>
		</div>
		<div class="frame">
			<div class="bit-3">
				<div class="container">
					<div class="container_title blue">
						<i class="fa fa-fighter-jet"></i> Customer info
						<button class="btn btn-default fr" data-modal-id="invoice_change_customer" data-modal-title="<i class='fa fa-exclamation-triangle'></i> Change customer" data-modal-width="400" data-modal-closeable='true' data-modal-onopen='openCustomerChangeModal'><i class="fa fa-legal"></i> Change customer</button>
					</div>
					<div class="container_content" style='height: 215px;'>
						@if($customer->companyName != '')
						<i class="fa fa-building fixedwidth"></i> {{ $customer->companyName }}&nbsp;&nbsp;
						@endif
						@if ($customer->contactName != '')
						<i class="fa fa-user fixedwidth"></i> {{ $customer->getContactPerson() }}
						@endif
						&nbsp;(ID: {{ $customer->id }})
						<br>
						<i class="fa fa-credit-card fixedwidth"></i> {{ $customer->getCustomerCreditRating->type }}
						<br>
						<i class="fa fa-location-arrow fl fixedwidth"></i>
						<div class='fl' style='margin-left: 4px;'> 
							{{ trim(nl2br($customer->address)) }}<br>
							{{ $customer->postalCode }} {{ $customer->city }}, {{ $customer->region }}
						</div>
						<br clear='both'>
						<i class="fa fa-phone fixedwidth"></i> {{ $customer->phone }} &nbsp;&nbsp&nbsp; <i class="fa fa-mobile fixedwidth"></i> {{ $customer->mobile }}
						<br>
						<i class="fa fa-envelope fixedwidth"></i> <a href='mailto:{{ $customer->email }}'>{{ $customer->email }}</a>
						<br>
						<i class="fa fa-asterisk fixedwidth"></i> CIF: {{ $customer->cifnif }}
						<br>
						<i class="fa fa-car fixedwidth"></i> Visit fee: € {{ $customer->assignedVisitFee }}
					</div>
				</div>
			</div>
			<div class="bit-3">
				<div class="container">
					<div class="container_title blue">
						<i class="fa fa-info"></i> Invoice information
					</div>
					<div class="container_content" style='height: 215px;'>
						<table class="form">
							<tr>
								<td style='width: 120px;'><label>Invoice title</label></td>
								<td style='width: 100%;'><input type='text' ng-model='invoiceData.jobTitle'></td>
							</tr>
							<tr>
								<td><label>Invoice notes</label></td>
								<td><textarea ng-model='invoiceData.notes' style='height: 126px;'></textarea></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div class="bit-3">
				<div class="container">
					<div class="container_title blue">
						<i class="fa fa-info-circle"></i> Invoice essentials
					</div>
					<div class="container_content" style='height: 215px;'>
						<br>
						<table class="form" style='font-size: 12pt;'>
							<tr>
								<td class='bold' style='width: 150px;'><label>Invoice number:</label></td>
								<td style='width: 100%;'>{{ $invoice->id }}</td>
							</tr>
							<tr>
								<td class='bold'><label>Invoice date:</label></td>
								<td>{{ $invoice->createdOn }}</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="frame">
			<div class="bit-1">
				<div class="container">
					<div class="container_title orange">
						<i class="fa fa-list"></i> Invoice entries
					</div>
					<div class="container_content nop" style='overflow: visible;'>
						<table class="data less-padding tlf" id='invoice_entries'>
							<tr>
								<th style='width: 140px;'>Quote #</th>
								<th style='width: 60%;'>Description</th>
								<th style='width: 100px;'>Extra costs</th>
								<th style='width: 100px;'>Unit price</th>
								<th style='width: 80px;'>Discount</th>
								<th style='width: 50px;'>Qty</th>
								<th style='width: 100px;'>Total</th>
								<th style='width: 35px;'></th>
							</tr>
							<tr ng-repeat='entry in entries'>
								<td>
									<input type='text' ng-model='entry.quoteId' ng-blur='getQuoteData(entry);' style='width:100px;'>
									<button class="btn btn-default" ng-click='showQuotePreview(entry)'>
										<i class="fa fa-eye"></i>
									</button>
								</td>
								<td><input type='text' ng-model='entry.description'></td>
								<td><input type='text' ng-model='entry.supCosts' class='euro'></td>
								<td><input type='text' ng-model='entry.unitPrice' class='euro'></td>
								<td><input type='text' ng-model='entry.discount' class='percentage'></td>
								<td><input type='text' ng-model='entry.quantity' tabisnewentry class='ar'></td>
								<td><input type="text" ng-value='(entry.unitPrice * entry.quantity) * ((100 - entry.discount)/100)' class='euro' readonly></td>
								<td>
									<button class='btn btn-red btn-square' ng-click='deleteEntry(entry);' tabindex="-1">
										<i class="fa fa-remove"></i>
									</button>
								</td>
							</tr>
							<tr>
								<td colspan="7" style='border: 0;'></td>
								<td>
									<button class='btn btn-green btn-square' ng-click='addNewEntry();'>
										<i class="fa fa-plus"></i>
									</button>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="frame">
			<div class="bit-75">
				<div class="container">
					<div class="container_title blue">
						<i class="fa fa-comment"></i> Comments
					</div>
					<div class="container_content nop" style='max-height: 200px; overflow-y: auto;'>
						<table class="data">
							<thead>
								<tr>
									<th style='width: 130px;'>Placed on</th>
									<th style='width: 80px;'>Placed by</th>
									<th style='width: 100%;'>Comment</th>
									<th style='width: 120px;'></th>
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat='comment in comments'>
										<td><% comment.placedOn %></td>
										<td>
											
											<img ng-src='users/<% comment.placedBy %>/photo' width='30' style='margin: 0; vertical-align: middle;'>
											<% comment.placedByInitials %>
										</td>
										<td>
											<span class='comment_comment' ng-hide='comment.editing'><% comment.comment %></span>
											<input type="text" ng-model='comment.comment' class='fw' ng-show='comment.editing'>
										</td>
										<td ng-hide='comment.editing'>
											<button class="btn btn-orange btn-square" ng-click='editComment($event, comment)' ng-hide='comment.emailId'><i class="fa fa-edit"></i></button>
											<button class="btn btn-red btn-square" ng-click='deleteComment(comment)' ng-hide='comment.emailId'><i class="fa fa-trash-o"></i></button>
											<button class="btn btn-default btn-square" ng-show='comment.emailId' ng-click='openEmail(comment)'><i class="fa fa-envelope-o"></i></button>
										</td>
										<td ng-show='comment.editing'><button class="btn btn-green fw" ng-click='saveComment(comment)'><i class="fa fa-save"></i> Save</button></td>
									</tr>
									<tr class='no_highlight'>
										<td colspan='4'>
											<input type='text' style='width: 100%' id='newComment' placeholder='Type your comment and press enter...'>
										</td>
									</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="bit-4" style='float: right;'>
				<div class="container">
					<div class="container_title orange">
						<i class="fa fa-plus"></i> Totals
					</div>
					<div class="container_content nop">
						<table class='data fr'>
							<tr>
								<td style='width: 50%;'><label>Subtotal</label></td>
								<td style='width: 50%;' class='ar'>&euro; <% getSubTotal() | currency %></td>
							</tr>
							<tr>
								<td>
									 <div style="width:20%;display:inline-block;">IVA:</div><select ng-model='invoiceData.vat' ng-options='id as type for (id,type) in vats' class='fw' style="width:59%;"></select>
								</td>
								<td class='ar'>&euro; <% getVat() | currency %></td>
							</tr>
							
							@if (Settings::setting('invoiceHasIrpf'))
							<tr>
								<td>
									<div style="width:20%;display:inline-block;">IRPF:</div><select ng-model='invoiceData.irpf' ng-options='id as type for (id,type) in irpfs' class='fw' style="width:59%;"></select>
								</td>
								<td class='ar'>&euro; <% getIrpf() | currency %></td>
							</tr>
							@else
								<input type="hidden" ng.model="invoiceData.irpf" />
							@endif

							
							<tr>
								<td><label>Total</label></td>
								<td class='bold underlined ar' style='font-size: 15pt;'>&euro; <% getTotal() | currency%></td>
							</tr>
							<tr>
								<td colspan='2' align='center'>
									<button class='btn btn-green' style='width: 100%; height: 50px; float: left;' id='saveBtn' ng-show='dirty' onClick="$('#rootScopeElement').scope().saveInvoice()">
										<i class="fa fa-check"></i> Save invoice
									</button>
									<button class="btn btn-green" style='width: 49.5%; float: left; margin-bottom: 3px;' data-tooltip='Add this invoice to the direct debit queue' ng-hide='dirty'  data-modal-title='Add to Direct Debit Queue' data-modal-width='600' data-modal-closeable='true' data-modal-id='invoice_direct_debit_modal'>
										<i class="fa fa-euro"></i> Direct Debit
									</button>
									<button class='btn btn-green' style='width: 49.5%; float: right; margin-bottom: 3px;' data-tooltip='Print this invoice' ng-hide='dirty' onClick='window.open("invoicepdf/{{ $invoice->id }}/open");'>
										<i class="fa fa-print"></i> Print
									</button>
									<br>
									@if (Settings::hasValidEmailSettings())
										<button class='btn btn-green' style='width: 49.5%; float: left;' ng-hide='dirty' data-modal-title='Mail invoice' data-modal-width='600' data-modal-closeable='true' data-modal-id='invoice_email_modal' ng-click='openEmailModal()'>
													<i class="fa fa-envelope"></i> Email
										</button>
									@else
										<button class='btn btn-green' style='width: 49.5%; float: left;' onclick='showError("Emails is your Pepper is not configured yet. Please setup your email in System Settings.", 5)' ng-hide='dirty'>
													<i class="fa fa-envelope"></i> Email
										</button>
									@endif
									<button class='btn btn-green' style='width: 49.5%; float: right;' data-tooltip='Download this invoice as a PDF file' ng-hide='dirty' onClick='window.open("invoicepdf/{{ $invoice->id }}/download");'>
										<i class="fa fa-download"></i> Download
									</button>
								</td>
							</tr>
						</table>
						<br clear='both'>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop
