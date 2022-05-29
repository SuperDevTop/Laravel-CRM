@extends('master')
<?php
	use App\Models\User;
	use App\Models\Quote;
	use App\Models\Settings;
	use App\Models\invoices\Invoice;
	use App\Models\variables\CustomerCreditRating;
	use App\Classes\CommonFunctions;
?>
@section('page_name')
	Quote {{ $quote->id }}, created by <i>{{ User::find($quote->createdBy)->getFullname() . '</i> on <i>' . CommonFunctions::formatDate($quote->createdOn) . '</i>' }} 
	@if(User::find($quote->createdBy) != null)
		Quote {{ $quote->id }}, created by <i>{{ User::find($quote->createdBy)->getFullname() . '</i> on <i>' . $quote->createdOn . '</i>' }}
	@endif
@stop

@section('breadcrumbs')
	<a href="/customers">Customers</a> <i class="breadcrumb"></i> <a href='/customers/{{ $customer->id }}'>{{ $customer->getCustomerName() }}</a> <i class="breadcrumb"></i> Quote #{{ $quote->id }}
@stop

@section('page_header')
	<button type='button' class='btn btn-default ml20' onClick="$('#rootScopeElement').scope().backToCustomer()">
		<i class="fa fa-arrow-left"></i> Back to customer
	</button>
	@if (Quote::max('id') != $quote->id)
		<button type='button' class='btn btn-orange' data-tooltip='Go to next quote (#{{ $quote->id + 1 }})' data-href='quotes/{{ Quote::where("id", ">", $quote->id)->orderBy("id", "ASC")->take(1)->first()->id }}/edit'>
			<i class="fa fa-forward"></i>
		</button>
	@endif
	<input type='text' id='quickQuoteId' placeholder='#' value='{{ $quote->id }}' class='fr' style='width: 80px; margin-top: 7px; text-align: center; font-size: 12px;'>
	@if (Quote::min('id') != $quote->id)
		<button type='button' class='btn btn-orange' data-tooltip='Go to previous quote (#{{ $quote->id - 1 }})' data-href='quotes/{{ Quote::where("id", "<", $quote->id)->orderBy("id", "DESC")->take(1)->first()->id }}/edit'>
			<i class="fa fa-backward"></i>
		</button>
	@endif
	@if(strtotime($quote->createdOn) > (time() - 7200))
		<button type='button' class='btn btn-red mr20' data-tooltip='Delete this quote. Please note that this option is only available if the quote has been created in the last 2 hours.' onClick="$('#rootScopeElement').scope().confirmQuoteDeletion()">
			<i class="fa fa-trash-o"></i> Delete quote
		</button>
	@endif
	<button type='button' class='btn btn-red mr20' data-tooltip='Make a new credit quote for this quote. This means that it will duplicate the quote with negative quantities. After creating the credit quote you can turn it into an official credit note.' onClick="$('#rootScopeElement').scope().confirmCreditnoteCreation()">
		<i class="fa fa-arrow-circle-o-down"></i> Make credit quote
	</button>
	<button class="btn btn-default" onClick="$('#rootScopeElement').scope().confirmQuoteDuplication()" data-tooltip='Make a new quote with the same products, quantities and discounts for the same customer.'><i class="fa fa-files-o"></i> Duplicate quote</button>
@stop

@section('scripts')
	<script src='/js/ui-select.js'></script>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.0-beta.1/angular-sanitize.min.js'></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-select/0.13.2/select.css">
	<link rel="stylesheet" href="/css/selectize.css">
	<script>
		$(function() {
			// Enter binding for the quick quote ID
			$('#quickQuoteId').on('keyup', function(event) {
				if (event.which != 13)
					return;

				location.assign('/quotes/' + $(this).val() + '/edit');
			});
		});
		
		var app = angular.module('app', ['ui.select', 'ngSanitize'])
		.config(function($interpolateProvider, uiSelectConfig){
		        $interpolateProvider.startSymbol('<%').endSymbol('%>');
		        uiSelectConfig.theme = 'selectize';
		    }
		);

		app.controller('QuoteController', function($rootScope, $scope, $http, $compile) {
			$scope.$compile = $compile;
			$scope.entries = {};
			$scope.quoteData = {};
			$scope.vatValues = {};
			$scope.vats = {};
			$scope.dirty = false;
			$scope.initialized = false;
			$scope.employees = [];

			$scope.add_to_invoice_number = '';

			$scope.viewing_email = {};

			$scope.searchProductResults = [];

			// Get the quote data
			ajaxRequest(
				'get_quote',
				{
					quoteId: {{ $quote->id }}
				},
				function(data) {
					$scope.entries = data.entries;
					$scope.quoteData = data.quoteData;
					$scope.employees = data.employees;

					$scope.adTypes = data.adTypes;
					$scope.jobStatuses = data.jobStatuses;
					$scope.vats = data.vats;
					$scope.vatValues = data.vatValues;
					$scope.comments = data.comments;
					$scope.customer = data.customer;

					$scope.emailData = {};
					$scope.emailData.to = $scope.customer.email;
					$scope.emailData.cc = '';
					$scope.emailData.bcc = '';
					$scope.emailData.subject = 'Quote #' + $scope.quoteData.id;
					$scope.emailData.message = data.emailTemplate;
					$scope.emailData.message = $scope.emailData.message.replace('{customer_name}', $scope.customer.contactName);
					$scope.emailData.message = $scope.emailData.message.replace('{quote_id}', $scope.quoteData.id);

					// If we have chosen to automatically select the send copy checkbox, set that value
					if (localStorage.getItem('autoTickQuoteEmailCopy') == 1) {
						$scope.emailData.sendmeacopy = true;
					} else {
						$scope.emailData.sendmeacopy = false;
					}

					$scope.quoteData.status = $scope.quoteData.status.toString();
					$scope.quoteData.vat = $scope.quoteData.vat.toString();
					$scope.quoteData.adType = $scope.quoteData.adType.toString();
					$scope.quoteData.assignedTo = $scope.quoteData.assignedTo.toString();

					$scope.searchProductResults = data.productSelections;

					$scope.$apply();

					$('#commentTable').animate({
						scrollTop: $('#commentTable').get(0).scrollHeight
					}, 2000);

					$scope.initialized = true;
				}
			);

			// Watches for quoteData and entries to detect a dirty page
			$scope.$watch('entries', function(newValues, oldValues, scope) {
				if (!$scope.initialized)
					return;
				$scope.dirty = true;
			}, true);
			$scope.$watch('quoteData', function(newValues, oldValues, scope) {
				if (!$scope.initialized)
					return;
				$scope.dirty = true;
			}, true);

			$scope.refreshProductResults = function(query) {
				if (query == '') {
					if ($scope.searchProductResults.length == 0)
						$scope.searchProductResults = [];
					return;
				}

				ajaxRequest(
					'search_product',
					{
						query: query
					},
					function(results) {
						$scope.searchProductResults = results;
						$scope.$apply();
					}
				);
			};

			$scope.updateProductRow = function(item, model, entry) {
				entry.productId = item.id;
				entry.productName = item.text;
				entry.unitPrice = item.salesPrice;
			};

			$scope.$watch('quoteData.status', function(newValue, oldValue) {
				if (!$scope.initialized)
					return;

				var completedJobStatusses = {{ json_encode(explode(',', Settings::setting('completedJobStatusses'))) }};
				if ($scope.quoteData.completedOn == '') {
					if (completedJobStatusses.indexOf(oldValue) === -1 && completedJobStatusses.indexOf(newValue) !== -1) {
						var currentDate = new Date();
						$scope.quoteData.completedOn = currentDate.getDate() + '-' + (currentDate.getMonth()+1) + '-' + currentDate.getFullYear() + ' ' + currentDate.getHours() + ':' + currentDate.getMinutes();
					}
				} else {
					if (completedJobStatusses.indexOf(oldValue) === -1 && completedJobStatusses.indexOf(newValue) !== -1) {
						confirmDialog(
							'Update completion date?',
							'Do you want to update the completion date of this quote to now?<br><br>Note: If you change the completion date it will affect your job reporting!',
							function() {
								var currentDate = new Date();
								$scope.quoteData.completedOn = currentDate.getDate() + '-' + (currentDate.getMonth()+1) + '-' + currentDate.getFullYear() + ' ' + currentDate.getHours() + ':' + currentDate.getMinutes();
								$scope.$apply();
							}
						);
					} else if (completedJobStatusses.indexOf(oldValue) !== -1 && completedJobStatusses.indexOf(newValue) === -1) {
						confirmDialog(
							'Clear completion date?',
							'Do you want to clear the completion date of this quote?<br><br>Note: If you clear the completion date it will affect your job reporting!',
							function() {
								$scope.quoteData.completedOn = '';
								$scope.$apply();
							}
						);
					}
				}
			});

			$scope.getSubTotal = function() {
				var total = 0;
				$.each($scope.entries, function(i, e) {
					total += (e.unitPrice * e.quantity) * (1 - (e.discount/100));
				});
				return total;
			};

			$scope.getVat = function() {
				return $scope.getSubTotal() * ($scope.parseFloat($scope.vatValues[$scope.quoteData.vat]) / 100);
			};

			$scope.getTotal = function() {
				return $scope.getSubTotal() + $scope.getVat() + $scope.parseFloat($scope.quoteData.supCosts);
			}

			$scope.backToCustomer = function() {
				window.location.assign('/customers/' + $scope.quoteData.customer);
			};

			$scope.deleteEntry = function(entry) {
				$scope.entries.splice($scope.entries.indexOf(entry), 1);
			};

			$scope.saveQuote = function() {
				if ($scope.quoteData.adType == 0) {
					showAlert('Whoops!', 'Please select an advertisement type before saving the quote.');
					return;
				}

				@if (Settings::setting('jobMonitoringEnabled'))
					if ($scope.quoteData.requiredBy == '') {
						showAlert('Whoops!', 'Please select a required-by date for this quote.');
						return;
					}
				@endif

				// Check if we have products without product id...
				var productsOK = true;
				angular.forEach($scope.entries, function(product) {
					if (product.productId == -1) {
						productsOK = false;
					}
				});
				if (!productsOK) {
					showError('Please make sure you have selected a product for every entry in the quote.<br><br>You have one or multiple products not selected.');
					return;
				}

				// Show loading icon
				$('#saveBtn').html('<i class="fa fa-spinner fa-spin"></i> Saving quote...');
				$('#saveBtn').removeClass('green').addClass('orange');

				// Save changes to db
				ajaxRequest(
					'save_quote',
					{
						quoteId: {{ $quote->id }},
						entries: $scope.entries,
						quoteData: $scope.quoteData
					},
					function() {
						showSuccess('Quote saved succesfully');
						$('#saveBtn').html('<i class="fa fa-save"></i> Save quote');
						$scope.dirty = false;
						$scope.$apply();
					},
					function() {
						showError('An error occured while saving this quote.<br><br>This can be caused by several problems. Please make sure everything is filled in correctly. You for example can\'t have empty products in the quote and you can\'t have letters in the price fields.');
						$('#saveBtn').removeClass('orange').addClass('green');
						$('#saveBtn').html('<i class="fa fa-check"></i> Save quote!');
					}
				);
			};

			$scope.addNewEntry = function() {
				$scope.entries.push(
					{
						id: -1,
						productId: -1,
						quantity: 1,
						unitPrice: 0,
						discount: 0,
						description: ''
					}
				);
			};

			$scope.addQuoteToNewInvoice = function() {
				if ($scope.creatingInvoice)
					return;
				
				$scope.creatingInvoice = true;
				ajaxRequest(
					'create_new_quote_invoice',
					{
						quoteId: $scope.quoteData.id
					},
					function(data) {
						if (data.success) {
							location.assign('/invoices/' + data.invoiceId + '/edit');
						}
					}
				);
			};

			$scope.addQuoteToNewCreditnote = function() {
				ajaxRequest(
					'create_new_quote_creditnote',
					{
						quoteId: $scope.quoteData.id
					},
					function(data) {
						if (data.success) {
							location.assign('/creditnotes/' + data.creditnoteId + '/edit');
						}
					}
				);
			};

			$scope.addQuoteToExistingInvoice = function() {
				if ($scope.creatingInvoice)
					return;
				
				$scope.creatingInvoice = true;
				ajaxRequest(
					'add_quote_to_existing_invoice',
					{
						quoteId: $scope.quoteData.id,
						invoiceId: $scope.add_to_invoice_number
					},
					function(data) {
						if (data.success) {
							location.assign('/invoices/' + $scope.add_to_invoice_number + '/edit');
						} else {
							showError(data.msg);
						}
					}
				);
			};

			$scope.addQuoteToExistingCreditnote = function() {
				ajaxRequest(
					'add_quote_to_existing_creditnote',
					{
						quoteId: $scope.quoteData.id,
						creditnoteId: $scope.add_to_creditnote_number
					},
					function(data) {
						if (data.success) {
							location.assign('/creditnotes/' + $scope.add_to_creditnote_number + '/edit');
						} else {
							showError(data.msg);
						}
					}
				);
			};

			$scope.sendEmail = function() {

				// Don't allow sending emails when the user doesn't have his email filled in...
				@if (Auth::user()->companyEmail == '')
					$('#mask').trigger('click');
					setTimeout(function() {
						showAlert('Whoops!', 'Could not send quote.<br><br>In order to send emails from Pepper you have to fill in your company email in your profile.<br><br>When people respond to emails you send from Pepper, the email will go back to your email.');
					}, 100);
					return;
				@endif

				// Check if everything looks OK
				var emailExp = new RegExp('[A-z0-9._%+-]+@[A-z0-9.-]+\.[A-z]{2,4}');
				
				var toEmails = $scope.emailData.to.split(/[;,]+/);
				var ccEmails = $scope.emailData.cc.split(/[;,]+/);
				var bccEmails = $scope.emailData.bcc.split(/[;,]+/);

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

				// 
				// Change button text to sending and disable the button
				$('#sendEmailButton').html('<i class="fa fa-spinner fa-spin"></i> Sending email...');

				var formData = new FormData();
				formData.append('quoteId', $scope.quoteData.id);
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
					url: '/ajax/send_quote_email',
					type: 'POST',
					data: formData,
					contentType: false,
					processData: false,
					success: function(data) {
						if (data.success) {
							showSuccess('Quote emailed succesfully');
							$('#mask').trigger('click');
						} else {
							showError('Email quote failed:<br>' + data.message + '<br><br>Please check your email SMTP settings.', 7);
						}
						$('#sendEmailButton').html('<i class="fa fa-send"></i> Send quote');
					}
				});
			};

			$scope.toggleGeneralInformation = function() {
				$content = $('#general_information_content');

				if ($content.is(':visible')) {
					$content.slideUp();
					$('#general_information_toggle').html('<i class="fa fa-expand"></i> Maximize');
				} else {
					$content.slideDown();
					$('#general_information_toggle').html('<i class="fa fa-compress"></i> Minimize');
				}
			};

			$scope.editComment = function(event, comment) {
				comment.editing = true;
				setTimeout(function() {
						$(event.target).closest('tr').find('input[type="text"]').focus();
				}, 1);
			};

			$scope.saveComment = function(comment) {
				ajaxRequest(
					'update_quote_comment',
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
							'delete_quote_comment',
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
			}

			$scope.parseFloat = function(input) {
				if (!input || input == '')
					return 0;

				return parseFloat(input);
			}

			$scope.confirmQuoteDeletion = function() {
				confirmDialog(
					'Are you sure?',
					'Are you sure you want to delete this quote?<br><br><strong>This will delete the quote together with all the comments, entries and information. This cannot be undone!</strong>',
					function() {
						ajaxRequest(
							'delete_quote',
							{
								quoteId: $scope.quoteData.id
							},
							function(data) {
								if (data.success) {
									location.assign('customers/{{ $quote->customer }}');
								}
							}
						);
					}
				);
			};

			$scope.confirmCreditnoteCreation = function() {
				confirmDialog(
						'Are you sure?',
						'Are you sure you want to create a new credit quote for this quote?<br><br>This will create a <b>new</b> quote just like this one, but with negative quantities.',
						function() {
							ajaxRequest(
									'create_credit_quote_from_quote',
									{
										quoteId: $scope.quoteData.id
									},
									function(data) {
										if (data.success) {
											location.assign('quotes/' + data.quoteId + '/edit');
										}
									}
							);
						}
				);
			}

			$scope.confirmQuoteDuplication = function() {
				confirmDialog(
					"Are you sure?",
					"Are you sure you want to duplicate this quote? This will create an identical quote for " + <?= json_encode($customer->getCustomername()); ?>,
					function() {
						ajaxRequest(
							'duplicate_quote',
							{
								quoteId: $scope.quoteData.id
							},
							function(data) {
								if (data.success) {
									location.assign('quotes/' + data.quoteId + '/edit');
								} else {
									showError("Could not duplicate quote. Please try again");
								}
							}
						);
					}
				);
			}

			$scope.toggleSendCopyOfEmail = function() {
				if ($scope.emailData.sendmeacopy == 0) {
					confirmDialog(
						'Remind setting?',
						'You have chosen to send a copy of this email to your own email. Do you want this option to be selected by default in the future?',
						function() {
							localStorage.setItem('autoTickQuoteEmailCopy', 1);
						}
					);
				} else {
					confirmDialog(
						'Remind setting?',
						'You have chosen not to send a copy of this email to your own email. Do you want this option to be disabled by default in the future?',
						function() {
							localStorage.setItem('autoTickQuoteEmailCopy', 0);
						}
					);
				}
			};

			$scope.openEmail = function(comment) {
				ajaxRequest(
					'get_quote_email',
					{
						emailId: comment.emailId
					},
					function(data) {
						$scope.viewing_email = data.email;
						if (data.attachment)
							$scope.viewing_email.attachment = data.attachment;

						$scope.$apply();
						openModal('View email', $('#view_quote_email_modal'), 600, true);
						$scope.$apply();
					}
				);
			};

			$scope.downloadEmailAttachment = function(email) {
				window.open('/quoteemailattachment/' + email.id);
			}

			$scope.downloadEmailAdditionalAttachment = function(email) {
				window.open('/quoteemailadditionalattachment/' + email.id);
			}

			$scope.resendEmail = function() {
				// Don't allow sending emails when the user doesn't have his email filled in...
				@if (Auth::user()->companyEmail == '')
					$('#mask').trigger('click');
					setTimeout(function() {
						showAlert('Whoops!', 'Could not send quote.<br><br>In order to send emails from Pepper you have to fill in your company email in your profile.<br><br>When people respond to emails you send from Pepper, the email will go back to your email.');
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
					'resend_quote_email',
					{
						quoteId: $scope.quoteData.id,
						emailId: $scope.viewing_email.id,
						to: $scope.viewing_email.to.trim(),
						cc: $scope.viewing_email.cc.trim(),
						bcc: $scope.viewing_email.bcc.trim(),
						subject: $scope.viewing_email.subject,
						message: $scope.viewing_email.message,
						sendcopy: $scope.viewing_email.sendmeacopy
					},
					function(data) {
						if (data.success) {
							showSuccess('Quote emailed succesfully');
							$('#mask').trigger('click');
						} else {
							showError('Email quote failed:<br>' + data.message + '<br><br>Please check your email SMTP settings.', 7);
						}
						$('#sendEmailButton').html('<i class="fa fa-send"></i> Resend quote');
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
					'Are you absolutely sure you want to change the customer of this quote? By clicking yes you agree that this action is your own responsability.',
					function() {
						ajaxRequest(
							'quote_change_customer',
							{
								quoteId: {{ $quote->id }},
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
			};

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
		});

		app.directive('productSelect', function() {
			return {
				restrict: 'A',
        		scope: false,
				link: function(scope, element, attrs) {
					/*$(element).select2({
						width: '100%',
						selectOnClose: true,
						ajax: {
							url: 'ajax/search_product',
							dataType: 'json',
							type: 'POST',
							delay: 200,
							data: function(params) {
								return {
									query: params.term
								}
							},
							processResults: function(data) {
								return {
									results: $.map(data, function (product) {
					                    return {
					                        text: product.name,
					                        price: product.salesPrice,
					                        id: product.id
					                    }
					                })
								}
							},
							cache: true
						},
						minimumInputLength: 2,
						initSelection: function(element, callback) {
							if (scope.entry.id == -1) {
								callback({
									text: "Please select a product...",
									id: -1
								});
							} else {
								callback({
									text: scope.entry.productName,
									id: scope.entry.id
								});
							}
						}
					}).on('change', function() {
						scope.entry.productId = $(this).select2('data')[0].id;
						scope.entry.productName = $(this).select2('data')[0].text;
						scope.entry.unitPrice = $(this).select2('data')[0].price;
						scope.$apply();
					});*/
				}
			}
		});

		app.directive('tabisnewentry', function() {
			return {
				restrict: 'A',
				link: function(scope, element, attrs) {
					$(element).on('keydown', function(event) {
						if (event.which == 9) { // tab
							// Only react if its the last one in the array
							if (!scope.$last) return;

							// Push a new entry into the entries array
							scope.entries.push(
								{
									id: -1,
									productId: -1,
									quantity: 1,
									unitPrice: 0,
									discount: 0,
									description: ''
								}
							);

							scope.$apply();

							var $dropdown = $('#quote_entries').find('.ui-select-container').last();
							$dropdown.controller('uiSelect').focusser[0].focus();

							event.preventDefault();
						}
					});
				}
			};
		});

		app.directive('basinputdate', function() {
			return {
				restrict: 'A',
				link: function(scope, element, attrs) {
					applyBasInput(element);
				}
			}
		});

		$(function() {
			$('#newComment').on('keydown', function(event) {
				if (event.which == 13) { // return
					if ($('#newComment').val() == '')
						return; // We down't want empty comments!
					var theScope = $(this).scope();
					ajaxRequest(
						'quote_new_comment',
						{
							quoteId: {{ $quote->id }},
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
								scrollTop: $('#commentTable').get(0).scrollHeight
							}, 2000);
							showSuccess('Your comment has been placed');
						}
					);
				}
			});
		});

		app.filter('currency', ['$filter', function($filter) {
			return function(input) {
				var curSymbol = '€ ';
				var decPlaces = 2;
				var thouSep = '';
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

		app.filter('dateToISO', function() {
		  return function(input) {
		  	var goodTime = badTime.replace(/(.+) (.+)/, "$1T$2Z");
		    var date = new Date(goodTime);
		    return date.toISOString();
		  };
		});


	</script>
@stop

@section('stylesheets')
	<style>
		#quote_entries input {
			width: 100%;
		}

		#quote_entries td {
			overflow: visible;
		}
	</style>
@stop

@section('content')
	<div ng-app='app' ng-controller='QuoteController as quoteCtrl' id='rootScopeElement'>
		<div class="modal_content" id="mail_quote_modal">
			<p>
				Here you can email this quote to your customer. Cc and bcc fields are available. You can enter more recipients. If you want more addresses in 1 field, use comma's or semi-colons to delimit them.
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
						<b><i class="fa fa-file-pdf-o"></i> <% "Quote #" + quoteData.id + ".pdf" %></b>
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
						<button class="btn btn-default fr" id="sendEmailButton" ng-click='sendEmail()'>
							<i class="fa fa-send"></i> Send quote
						</button>
					</td>
				</tr>
			</table>
		</div>
		<div class="modal_content" id="view_quote_email_modal">
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
							<i class="fa fa-file-pdf-o"></i> <% "Quote #" + quoteData.id + ".pdf" %> (click to view)
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
						<textarea style='width: 100%; height: 200px;' ng-model='viewing_email.message'></textarea>
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
		<div class="modal_content" id="add_to_invoice_modal">
			<div class='bit-2 nom'>
				<h2>New invoice</h2>
				<p>This option will create a new invoice for {{ $customer->getCustomerName() }} with this quote as it's contents.</p>
				<br>
				<button class="btn btn-default" ng-click='addQuoteToNewInvoice()'>Create new invoice</button>
			</div>
			<div class="bit-2" style='border-left: 1px solid #E5E5E5;'>
				<h2>Existing invoice</h2>
				<p>This will add this quote to an existing invoice. This existing invoice doesn not have to be of the same customer.</p>
				<br>
				<table class="form nom">
					<tr>
						<td style='width: 130px;'>Invoice number</td>
						<td><input type='text' ng-model='add_to_invoice_number'></td>
					</tr>
				</table>
				<br>
				<button class="btn btn-default" ng-show='add_to_invoice_number' ng-click='addQuoteToExistingInvoice()'>Add this quote to specified invoice</button>
			</div>
		</div>
		<div class="modal_content" id="add_to_creditnote_modal">
			<div class='bit-2 nom'>
				<h2>New credit note</h2>
				<p>This option will create a new credit note for {{ $customer->getCustomerName() }} with this quote as it's contents.</p>
				<br>
				<button class="btn btn-default" ng-click='addQuoteToNewCreditnote()'>Create new credit note</button>
			</div>
			<div class="bit-2" style='border-left: 1px solid #E5E5E5;'>
				<h2>Existing credit note</h2>
				<p>This will add this quote to an existing credit note. This existing credit note does not have to be of the same customer.</p>
				<br>
				<table class="form nom">
					<tr>
						<td style='width: 150px;'>Credit note number</td>
						<td><input type='text' ng-model='add_to_creditnote_number'></td>
					</tr>
				</table>
				<br>
				<button class="btn btn-default" ng-show='add_to_creditnote_number' ng-click='addQuoteToExistingCreditnote()'>Add this quote to specified credit note</button>
			</div>
		</div>
		<div class="modal_content" id='quote_change_customer'>
			<div>
				<b class='red'>
					 Changing the customer of a quote may create accounting implications. Please note that changing the customer is your own responsability!
				</b>
				<br><br>
				Select the new customer for the quote:
				<br>
				<select id='new_customer_select'></select>
				<br><br>
				<button class="btn btn-green" ng-click='confirmCustomerChange()'>Change customer</button>
			</div>
		</div>
			<div class="frame">
				<div class="bit-1">
					<div class="container">
						<div class="container_title blue">
							<i class="fa fa-info"></i> General information
							<button class="btn btn-orange fr" id="general_information_toggle" ng-click='toggleGeneralInformation()'>
								<i class="fa fa-compress"></i> Minimize
							</button>
							<button class="btn btn-default fr mr10" data-modal-id="quote_change_customer" data-modal-title="<i class='fa fa-exclamation-triangle'></i> Change customer" data-modal-width="400" data-modal-closeable='true' data-modal-onopen='openCustomerChangeModal'>
								<i class="fa fa-legal"></i> Change customer
							</button>
						</div>
						<div class="container_content" id='general_information_content'>
							<div style='width: 33%; height: 120px; float: left;'>
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
								@if ($quote->createdBy != 0)
									<i class="fa fa-user fixedwidth"></i> Created by: {{ User::find($quote->createdBy)->getFullname() }}
								@endif
							</div>
							<div style='width: 33%; height: 135px; float: right;'>
								<table class="form smallrows">
									<tr>
										<td style='width: 120px;'><label>Started</label></td>
										<td style='width: 100%;'>
											<input type='text' ng-model='quoteData.startedOn' class='basinput datetime'>
										</td>
									</tr>
									<tr>
										<td><label>Required</label></td>
										<td>
											<input type='text' ng-model='quoteData.requiredBy' class='basinput datetime'>
										</td>
									</tr>
									<tr>
										<td><label>Completed</label></td>
										<td>
											<input type='text' ng-model='quoteData.completedOn' class='basinput datetime'>
										</td>
									</tr>
								</table>
							</div>
							<div style='width: 33%; height: 120px; float: right; position: relative;'>
								<table class="form smallrows">
									<tr>
										<td style='width: 120px;'><label>Assigned to</label></td>
										<td style='width: 100%;'>
											<ui-select ng-model="quoteData.assignedTo" style='width: 100%;'>
											    <ui-select-match placeholder='Search employee...'>
											        <span ng-bind="$select.selected.name"></span>
											    </ui-select-match>
											    <ui-select-choices repeat="item.id as item in (employees | filter: $select.search) track by item.id">
											        <span ng-bind="item.name"></span>
											    </ui-select-choices>
											</ui-select>
										</td>
									</tr>
									<tr>
										<td><label>Ad Type</label></td>
										<td>
											<ui-select ng-model="quoteData.adType" style='width: 100%;'>
											    <ui-select-match placeholder='Search advertising type...'>
											        <span ng-bind="$select.selected.type"></span>
											    </ui-select-match>
											    <ui-select-choices repeat="item.id as item in (adTypes | filter: $select.search) track by item.id">
											        <span ng-bind="item.type"></span>
											    </ui-select-choices>
											</ui-select>
										</td>
									</tr>
									<tr>
										<td><label>Job status</label></td>
										<td>
											<ui-select ng-model="quoteData.status" style='width: 100%;'>
											    <ui-select-match placeholder='Search job status...'>
											        <span ng-bind="$select.selected.type"></span>
											    </ui-select-match>
											    <ui-select-choices repeat="item.id as item in (jobStatuses | filter: $select.search) track by item.id">
											        <span ng-bind="item.type"></span>
											    </ui-select-choices>
											</ui-select>
										</td>
									</tr>
								</table>
							</div>
							<br clear='both'>
							<textarea ng-model='quoteData.description' placeholder="Quote notes / description" style='width: 66%; float: right; height: 80px;'></textarea>
							<br clear='both'>
						</div>
					</div>
				</div>
			</div>
		<div class="frame">
			<div class="bit-1">
				<div class="container">
					<div class="container_title orange">
						<i class="fa fa-list"></i> Quote entries
					</div>
					<div class="container_content nop" style='overflow: visible; max-height: none;'>
						<table class="data less-padding" id='quote_entries'>
							<tr>
								<th style='width: 40%;'>Product</th>
								<th style='width: 60%;'>Description</th>
								@if(Settings::setting('quoteHasVisitDate') == 1)
									<th style='width: 110px;'>Visit Date</th>
								@endif
								<th style='width: 50px;'>Qty</th>
								<th style='width: 100px;'>Unit price</th>
								<th style='width: 60px;'>Discount</th>
								<th style='width: 100px;'>Total</th>
								<th style='width: 35px;'></th>
							</tr>
							<tr ng-repeat='entry in entries'>
								<td>
									<ui-select ng-model="entry.productId" style='width: 100%;' on-select='updateProductRow($item, $model, entry)'>
									    <ui-select-match placeholder='Search a product...'>
									        <span ng-bind="$select.selected.name"></span>
									    </ui-select-match>
									    <ui-select-choices repeat="item.id as item in (searchProductResults | filter: $select.search) track by item.id" refresh='refreshProductResults($select.search)' refresh-delay='200'>
									        <span ng-bind="item.name"></span>
									    </ui-select-choices>
									</ui-select>
								</td>
								<td><input type='text' ng-model='entry.description'></td>
								@if(Settings::setting('quoteHasVisitDate') == 1)
									<td><input type='text' ng-model='entry.visitDate' class='date' basinputdate></td>
								@endif
								<td><input type='text' ng-model='entry.quantity' class='ar'></td>
								<td><input type='text' ng-model='entry.unitPrice' class='euro'></td>
								<td><input type='text' ng-model='entry.discount' tabisnewentry class='percentage'></td>
								<td><input type="text" ng-value='(entry.unitPrice * entry.quantity) * ((100 - entry.discount)/100) | currency' class='euro' readonly></td>
								<td>
									<button class='btn btn-red btn-square' ng-click='deleteEntry(entry);' tabindex="-1">
										<i class="fa fa-minus"></i>
									</button>
								</td>
							</tr>
							<tr>
								@if(Settings::setting('quoteHasVisitDate') == 1)
								<td colspan="7" style='border: 0;'></td>
								@else
								<td colspan="6" style='border: 0;'></td>
								@endif
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
				<div class='container fl'>
					<div class="container_title blue">
						<i class="fa fa-comment"></i> Comments
					</div>
					<div class="container_content nop" id='commentTable' style='max-height: 200px; overflow-y: auto;'>
						<table class="data highlight sticky_header" id='commentTable'>
							<tr>
								<th style='width: 130px;'>Placed on</th>
								<th style='width: 80px;'>Placed by</th>
								<th style='width: 100%;'>Comment</th>
								<th style='width: 80px;'></th>
							</tr>
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
						</table>
					</div>
				</div>
			</div>
			<div class="bit-4">
				<div class="container fl">
					<div class="container_title orange">
						<i class="fa fa-plus"></i> Totals
					</div>
					<div class="container_content nop">
						<table class='data smallrows'>
							<tr>
								<td style='width: 50%;'><label>Subtotal</label></td>
								<td style='width: 50%;' class='ar'><% getSubTotal() | currency %></td>
							</tr>
							<tr>
								<td>
									<select ng-model='quoteData.vat' ng-options='k as v for (k,v) in vats' class='fw'></select>
								</td>
								<td class='ar'>
									<% getVat() | currency %>
								</td>
							</tr>
							<tr>
								<td><label>Extra costs</label></td>
								<td>
									<input type='text' ng-model='quoteData.supCosts' class='euro' style='width: 100%;'>
								</td>
							</tr>
							<tr>
								<td><label>Total</label></td>
								<td class='bold underlined ar' style='font-size: 15pt;'>&euro; <% getTotal() | currency %></td>
							</tr>
							<tr>
								<td colspan='2'>
									<button class='btn btn-green' style='width: 100%; height: 50px;' ng-show='dirty' id='saveBtn' ng-click="saveQuote()">
										<i class="fa fa-save"></i> Save quote
									</button>
									<button class='btn btn-green' style='width: 100%; float: left; margin-bottom: 3px;' ng-hide='dirty' onClick='window.open("quotepdf/{{ $quote->id }}/open");'>
										<i class="fa fa-print"></i> Print
									</button>
									<br>
									<button class='btn btn-green' style='width: 49.5%; float: right; margin-bottom: 3px;' ng-hide='dirty' onClick='window.open("quotepdf/{{ $quote->id }}/receipt");'>
										<i class="fa fa-money"></i> Receipt
									</button>
									@if (Settings::hasValidEmailSettings())
										<button class='btn btn-green' data-tooltip='Email this quote to {{ $customer->getCustomerName() }}' style='width: 49.5%; float: left; margin-bottom: 3px;' ng-hide='dirty' data-modal-title='Mail quote' data-modal-width='600' data-modal-closable='true' data-modal-id='mail_quote_modal' data-modal-closeable='true'>
											<i class="fa fa-envelope"></i> Email
										</button>
									@else
										<button class="btn btn-green" style='width: 49.5%; float: left; margin-bottom: 3px;' onclick='showError("Emails is your Pepper is not configured yet. Please setup your email in System Settings.", 5)' ng-hide='dirty'>
											<i class="fa fa-envelope"></i> Email
										</button>
									@endif
									<button class='btn btn-green' style='width: 49.5%; float: right; margin-bottom: 3px;' ng-hide='dirty' onClick='window.open("quotepdf/{{ $quote->id }}/download");'>
										<i class="fa fa-download"></i> Download
									</button>
									@if ($quote->hasBeenInvoicedAndNotVoided())
									 <button class='btn btn-orange' data-tooltip='This quote has been invoiced on<br>{{ CommonFunctions::formatDate(Invoice::find($quote->getInvoiceId())->createdOn) }}. Click this button to open that invoice.' style='width: 49.5%; float: left; margin-bottom: 3px;' ng-hide='dirty' data-href='invoices/{{ $quote->getInvoiceId() }}/edit'>
										<i class="fa fa-folder-open"></i> Invoice (#{{$quote->getInvoiceId() }})
									</button>
									@else
									<button class='btn btn-green' data-tooltip="Create a new invoice for {{ $customer->getCustomerName() }} with this quote in it" style='width: 49.5%; float: left; margin-bottom: 3px;' ng-hide='dirty || getTotal() < 0' data-modal-id='add_to_invoice_modal' data-modal-title='Add quote to invoice' data-modal-width='600' data-modal-closeable='true'>
										<i class="fa fa-plus"></i> Add to invoice
									</button>
									<button class='btn btn-green' data-tooltip="Create a new credit note for {{ $customer->getCustomerName() }} with this quote in it" style='width: 49.5%; float: left; margin-bottom: 3px;' ng-hide='dirty || getTotal() >= 0' data-modal-id='add_to_creditnote_modal' data-modal-title='Add quote to credit note' data-modal-width='600' data-modal-closeable='true'>
										<i class="fa fa-plus"></i> Add to credit note
									</button>
									@endif
									<br clear='both'>
									<button class="btn btn-default" ng-hide='dirty' style='width: 100%;' data-href='quotes/{{ $quote->id }}/edit/description-sheet'><i class="fa fa-list"></i> Quote Description Page</button>
								</td>	
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop