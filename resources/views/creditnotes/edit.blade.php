@section('page_name')
	Credit note #{{ $creditnote->id }}
@stop

@section('breadcrumbs')
	<a href='/customers'>Customers</a> <i class="breadcrumb"></i> <a href='/customers/{{ $customer->id }}'>{{ $customer->getCustomerName() }}</a> <i class="breadcrumb"></i> Creditnote #{{ $creditnote->id }}
@stop

@section('page_header')
	<button type='button' class='btn btn-default ml20' onClick="$('#rootScopeElement').scope().backToCustomer()">
		<i class="fa fa-sitemap"></i> Back to customer overview
	</button>
	@if (Creditnote::max('id') != $creditnote->id)
		<button type='button' class='btn btn-orange' data-tooltip='Go to next quote (#{{ $creditnote->id + 1 }})' data-href='creditnotes/{{ Creditnote::where("id", ">", $creditnote->id)->orderBy("id", "ASC")->get()->first()->id }}/edit'>
			<i class="fa fa-forward"></i>
		</button>
	@endif
	<input type='text' id='quickCreditnoteId' placeholder='#' value='{{ $creditnote->id }}' class='fr' style='width: 80px; margin-top: 7px; text-align: center; font-size: 12px;'>
	@if (Creditnote::min('id') != $creditnote->id)
		<button type='button' class='btn btn-orange ml20' data-tooltip='Go to previous quote (#{{ $creditnote->id - 1 }})' data-href='creditnotes/{{ Creditnote::where("id", "<", $creditnote->id)->orderBy("id", "DESC")->get()->first()->id }}/edit'>
			<i class="fa fa-backward"></i>
		</button>
	@endif
	@if(Creditnote::max('id') == $creditnote->id)
		<button class="btn btn-red" data-tooltip='This will permanently delete this creditnote. Please note that this is only available on the last entered creditnote.' onclick='confirmDelete()'>
			<i class="fa fa-trash-o"></i> Delete credit note
		</button>
	@endif
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
			$('#quickCreditnoteId').on('keyup', function(event) {
				if (event.which != 13)
					return;

				location.assign('/creditnotes/' + $(this).val() + '/edit');
			});

			$(window).on('beforeunload', function(event) {
				if ($('#rootScopeElement').scope().dirty) {
					return 'WARNING - WARNING - WARNING - WARNING - WARNING\r\nWARNING - WARNING - WARNING - WARNING - WARNING\r\nYou have NOT saved your creditnote changes yet!\r\nIf you close this page now, you lose your changes!';
				}
			});

			$('#newComment').on('keydown', function(event) {
				if (event.which == 13) { // return
					if ($('#newComment').val() == '')
						return; // We down't want empty comments!
					var theScope = $(this).scope();
					ajaxRequest(
						'creditnote_new_comment',
						{
							creditnoteId: {{ $creditnote->id }},
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

		app.controller('creditnoteCtrl', ['$scope', '$http', '$compile', function($scope, $http, $compile) {
			// Initialize data
			$scope.creditnoteData = {
				vat: 2
			};
			$scope.vats = [];
			$scope.vatValues = {};
			$scope.entries = {};
			$scope.direct_debit = {
				applyBankCharge: false
			};
			$scope.dirty = false;
			$scope.initialized = false;

			$scope.$compile = $compile;
			
			// Receive the creditnote data
			ajaxRequest(
				'get_creditnote',
				{
					creditnoteId: {{ $creditnote->id }}
				},
				function(data) {
					$scope.vats = data.vats;
					$scope.vatValues = data.vatValues;
					$scope.entries = data.entries;
					$scope.creditnoteData = data.creditnoteData;
					$scope.settings = data.settings;
					$scope.customer = data.customer;

					$scope.comments = data.comments;

					$scope.direct_debit.description = $scope.creditnoteData.description;
					$scope.direct_debit.bankCharge = data.defaultBankCharge;

					$scope.creditnoteData.vat = $scope.creditnoteData.vat.toString();
					
					$scope.emailData = {};
					$scope.emailData.to = $scope.customer.email;
					$scope.emailData.cc = '';
					$scope.emailData.bcc = '';
					$scope.emailData.subject = 'Creditnote #' + $scope.creditnoteData.id;
					$scope.emailData.message = data.emailTemplate;
					$scope.emailData.message = $scope.emailData.message.replace('{customer_name}', $scope.customer.contactName);
					$scope.emailData.message = $scope.emailData.message.replace('{creditnote_id}', $scope.creditnoteData.id);
					$scope.emailData.sendmeacopy = false;

					// If we have chosen to automatically select the send copy checkbox, set that value
					if (localStorage.getItem('autoTickCreditnoteEmailCopy') == 1) {
						$scope.emailData.sendmeacopy = true;
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
			$scope.$watch('creditnoteData', function(newValues, oldValues, scope) {
				if (!$scope.initialized)
					return;
				$scope.dirty = true;
			}, true);

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
							entry.unitPrice = (Number(data.quote.subtotal) * (-1));
							entry.supCosts = (Number(data.quote.supCosts) * (-1));
							notify('Quote added to credit note succesfully');
						} else {
							showError(data.message);
							if (!data.success)
								entry.quoteId = ''; 
						}
						$scope.$apply();
					}
				);
			};

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

			$scope.deleteEntry = function(entry) {
				$scope.entries.splice($scope.entries.indexOf(entry), 1);
			};

			$scope.showQuotePreview = function(entry) {
				window.open('quotes/' + entry.quoteId + '/edit');
			};

			$scope.backToCustomer = function() {
				window.location.assign('/customers/' + $scope.creditnoteData.customer);
			};

			$scope.getSubTotal = function() {
				var total = 0;
				$.each($scope.entries, function(i, e) {
					total += (e.unitPrice * e.quantity) * (1 - (e.discount/100));
				});
				return total;
			};

			$scope.getVat = function() {
				return $scope.getSubTotal() * ($scope.parseFloat($scope.vatValues[$scope.creditnoteData.vat]/100));
			};

			$scope.getTotal = function() {
				var supCosts = 0;
				angular.forEach($scope.entries, function(entry) {
					supCosts += $scope.parseFloat(entry.supCosts);
				});
				return $scope.getSubTotal() + $scope.getVat() + supCosts;
			};

			$scope.saveCreditnote = function() {
				// Show loading icon
				$('#saveBtn').html('<i class="fa fa-spinner fa-spin"></i> Saving quote...');
				$('#saveBtn').removeClass('green').addClass('orange');

				ajaxRequest(
					'save_creditnote',
					{
						creditnoteId: $scope.creditnoteData.id,
						creditnote: $scope.creditnoteData,
						entries: $scope.entries
					},
					function(data) {
						if (data.success) {
							$('#saveBtn').removeClass('orange').addClass('green');
							$('#saveBtn').html('<i class="fa fa-check"></i> Creditnote saved!');
							setTimeout(function() {
								$('#saveBtn').html('<i class="fa fa-check"></i> Save credit note!');
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
						showAlert('Whoops!', 'Could not send creditnote.<br><br>In order to send emails from Pepper you have to fill in your company email in your profile.<br><br>When people respond to emails you send from Pepper, the email will go back to your email.');
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

				ajaxRequest(
					'send_creditnote_email',
					{
						creditnoteId: $scope.creditnoteData.id,
						to: $scope.emailData.to,
						cc: $scope.emailData.cc,
						bcc: $scope.emailData.bcc,
						subject: $scope.emailData.subject,
						message: $scope.emailData.message,
						sendcopy: $scope.emailData.sendmeacopy
					},
					function(data) {
						if (data.success) {
							showSuccess('Creditnote emailed succesfully');
							$('#mask').trigger('click');
						} else {
							showError('Email creditnote failed:<br>' + data.message + '<br><br>Please check your email SMTP settings.', 7);
						}
						$('#sendEmailButton').html('<i class="fa fa-send"></i> Send creditnote');
					}
				);
			};

			$scope.confirmDirectDebit = function() {
				ajaxRequest(
					'creditnote_add_to_direct_debit_queue',
					{
						creditnoteId: $scope.creditnoteData.id,
						description: $scope.direct_debit.description,
						bankCharge: $scope.direct_debit.bankCharge,
						applyBankCharge: $scope.direct_debit.applyBankCharge
					},
					function(data) {
						if (data.success) {
							showSuccess('Creditnote succesfully added to Direct Debit queue');
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
					'update_creditnote_comment',
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
							'delete_creditnote_comment',
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
							localStorage.setItem('autoTickCreditnoteEmailCopy', 1);
						}
					);
				} else {
					confirmDialog(
						'Remind setting?',
						'You have chosen not to send a copy of this email to your own email. Do you want this option to be disabled by default in the future?',
						function() {
							localStorage.setItem('autoTickCreditnoteEmailCopy', 0);
						}
					);
				}
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
					'Are you absolutely sure you want to change the customer of this creditnote? By clicking yes you agree that this action is your own responsability.',
					function() {
						ajaxRequest(
							'creditnote_change_customer',
							{
								creditnoteId: {{ $creditnote->id }},
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
				'Are you sure you want to permanently delete this creditnote?<br><br><b>Note:</b> you can only delete the <b>last</b> creditnote in the system. Please only use this in emergency mistakes.<br><br><span style="color: red; font-weight: bold;">This action is permanent. The creditnote will be gone. Poof!</span><br><br>After deletion you will be redirected to the previous creditnote.',
				function() {
					ajaxRequest(
						'delete_creditnote',
						{
							creditnoteId: {{ $creditnote->id }}
						},
						function(data) {
							if (data.success) {
								window.location = 'creditnotes/{{ $creditnote->id - 1 }}/edit';
							} else {
								showError('Could not delete creditnote.<br><br>You can only delete the last creditnote in the system. This is not the last creditnote!');
							}
						}
					);
				}
			);
		}

		function confirmVoid() {
			confirmDialog(
				'Are you sure?',
				'Are you sure you want to void this creditnote? This will create a new negative creditnote to counter this creditnote.<br><br>If you click yes you will be redirected to the new, negative creditnote.',
				function() {
					ajaxRequest(
						'void_creditnote',
						{
							creditnoteId: {{ $creditnote->id }}
						},
						function(data) {
							window.location = 'creditnotes/' + data.creditnoteId + '/edit';
						}
					);
				}
			);
		}
	</script>
@stop

@section('stylesheets')
	<style>
		#creditnote_entries input {
			width: 100%;
		}
	</style>
@stop

@section('content')
	<div ng-app='app' ng-controller='creditnoteCtrl' id='rootScopeElement'>
		<div class="modal_content" id='creditnote_direct_debit_modal'>
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
		<div class="modal_content" id='creditnote_email_modal'>
			<p>
				Here you can email this creditnote to your customer. Cc and bcc fields are available. You can enter more recipients. If you want more addresses in 1 field, use comma's or semi-colons to delimit them.
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
					<td>Attachment:</td>
					<td style='font-size: 10pt; font-weight: bold;'><i class="fa fa-file-pdf-o"></i> <% "Creditnote #" + creditnoteData.id + ".pdf" %></td>
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
							<i class="fa fa-send"></i> Send creditnote
						</button>
					</td>
				</tr>
			</table>
		</div>
		<div class="modal_content" id='creditnote_change_customer'>
			<div>
				<b class='red'>
					 Changing any creditnote or customer at this point is generally not permitted. Please note that you should only change the customer if you understand the accounting implications.
				</b>
				<br><br>
				Select the new customer for the creditnote:
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
						<button class="btn btn-default fr" data-modal-id="creditnote_change_customer" data-modal-title="<i class='fa fa-exclamation-triangle'></i> Change customer" data-modal-width="400" data-modal-closeable='true' data-modal-onopen='openCustomerChangeModal'><i class="fa fa-legal"></i> Change customer</button>
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
						<i class="fa fa-info"></i> Credit note information
					</div>
					<div class="container_content" style='height: 215px;'>
						<table class="form">
							<tr>
								<td style='width: 120px;'><label>Credit note title</label></td>
								<td style='width: 100%;'><input type='text' ng-model='creditnoteData.jobTitle'></td>
							</tr>
							<tr>
								<td><label>Credit note notes</label></td>
								<td><textarea ng-model='creditnoteData.notes' style='height: 126px;'></textarea></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div class="bit-3">
				<div class="container">
					<div class="container_title blue">
						<i class="fa fa-info-circle"></i> Credit note essentials
					</div>
					<div class="container_content" style='height: 215px;'>
						<br>
						<table class="form" style='font-size: 12pt;'>
							<tr>
								<td class='bold' style='width: 150px;'><label>Credit note number:</label></td>
								<td style='width: 100%;'>{{ $creditnote->id }}</td>
							</tr>
							<tr>
								<td class='bold'><label>Credit note date:</label></td>
								<td>{{ $creditnote->createdOn }}</td>
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
						<i class="fa fa-list"></i> Credit note entries
					</div>
					<div class="container_content nop" style='overflow: visible;'>
						<table class="data less-padding tlf" id='creditnote_entries'>
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
									<th style='width: 80px;'></th>
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
											<button class="btn btn-orange btn-square" ng-click='editComment($event, comment)'><i class="fa fa-edit"></i></button>
											<button class="btn btn-red btn-square" ng-click='deleteComment(comment)'><i class="fa fa-trash-o"></i></button>
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
									<select ng-model='creditnoteData.vat' ng-options='id as type for (id,type) in vats' class='fw'></select>
								</td>
								<td class='ar'>&euro; <% getVat() | currency %></td>
							</tr>
							<tr>
								<td><label>Total</label></td>
								<td class='bold underlined ar' style='font-size: 15pt;'>&euro; <% getTotal() | currency%></td>
							</tr>
							<tr>
								<td colspan='2' align='center'>
									<button class='btn btn-green' style='width: 100%; height: 50px;' id='saveBtn' ng-show='dirty' onClick="$('#rootScopeElement').scope().saveCreditnote()">
										<i class="fa fa-check"></i> Save creditnote
									</button>
									<button class='btn btn-green' style='width: 100%; margin-bottom: 3px;' data-tooltip='Print this creditnote' ng-hide='dirty' onClick='window.open("creditnotepdf/{{ $creditnote->id }}/open");'>
										<i class="fa fa-print"></i> Print
									</button>
									<br>
									@if (Settings::hasValidEmailSettings())
										<button class='btn btn-green' style='width: 100%; margin-bottom: 3px;' ng-hide='dirty' data-modal-title='Mail credit note' data-modal-width='600' data-modal-closeable='true' data-modal-id='creditnote_email_modal' ng-click='openEmailModal()'>
													<i class="fa fa-envelope"></i> Email
										</button>
									@else
										<button class='btn btn-green' style='width: 100%; margin-bottom: 3px;' onclick='showError("Emails is your Pepper is not configured yet. Please setup your email in System Settings.", 5)' ng-hide='dirty'>
													<i class="fa fa-envelope"></i> Email
										</button>
									@endif
									<br>
									<button class='btn btn-green' style='width: 100%;' data-tooltip='Download this creditnote as a PDF file' ng-hide='dirty' onClick='window.open("creditnotepdf/{{ $creditnote->id }}/download");'>
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