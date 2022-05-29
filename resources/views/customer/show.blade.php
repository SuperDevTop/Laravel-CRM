@extends('master')
@section('page_name')
	{{ $customer->getCustomerName() }}
@stop

<?php
	use App\Models\variables\AdType;
	use App\Models\variables\CustomerCreditRating;
	use App\Classes\CommonFunctions;
?>

@section('breadcrumbs')
	<a href='/customers'>Customers</a> <i class="breadcrumb"></i> {{ $customer->getCustomerName() }} (ID 	{{ $customer->id }})
@stop

@section('scripts')
	<script type='text/javascript' src='ckeditor/ckeditor.js'></script>
	<script type='text/javascript' src='//maps.google.com/maps/api/js?sensor=true'></script>
	<script type='text/javascript' src='/js/gmaps.js'></script>
	<script type='text/javascript' src='/js/dropzone.js'></script>
	<script type='text/javascript'>

		function saveNotes() {

			var addresses = [];

			$('[data-tab="addresses"] .address').each(function(index, address) {
				var $address = $(address);
				addresses.push({
					id: $address.attr('data-id'),
					label: $address.find('.address-label').val(),
					address: $address.find('.address-address').val(),
					city: $address.find('.address-city').val(),
					postalcode: $address.find('.address-postalcode').val(),
					province: $address.find('.address-province').val(),
					country: $address.find('.address-country').val(),
					telephone: $address.find('.address-telephone').val(),
					email: $address.find('.address-email').val()
				});
			});

			ajaxRequest(
				'save_customer_notes',
				{
					customerId: {{ $customer->id }},
					notes: CKEDITOR.instances.notes.getData(),
					notes2: CKEDITOR.instances.notes2.getData(),
					accountHolder: $('#accountHolder').val(),
					bankName: $('#bankName').val(),
					swiftCode: $('#swiftCode').val(),
					iban: $('#iban').val(),
					sepa_mandateId: $('#sepa_mandateId').val(),
					sepa_mandateDate: $('#sepa_mandateDate').val(),
					'addresses': addresses
				},
				function(data) {
					if (data.success) {
						showSuccess('Customer data saved succesfully');
					} else {
						showError('An error occured while saving the customer data. Please try again.');
					}
				}	
			);
		}

		$(function() {
    		CKEDITOR.env.isCompatible = true;
			CKEDITOR.replace('notes', {
				toolbar: [
					['Styles','Format','Font','FontSize'],
					['Bold','Italic','Underline'],
					['NumberedList','BulletedList'],
					['Maximize', 'Print']
				],
				height: 187,
    			removePlugins: 'elementspath',
    			extraPlugins: 'print',
    			resize_enabled: false,
		        enterMode : CKEDITOR.ENTER_BR,
		        shiftEnterMode: CKEDITOR.ENTER_P
			});

			CKEDITOR.replace('notes2', {
				toolbar: [
					['Styles','Format','Font','FontSize'],
					['Bold','Italic','Underline'],
					['NumberedList','BulletedList'],
					['Maximize', 'Print']
				],
				height: 187,
    			removePlugins: 'elementspath',
    			extraPlugins: 'print',
    			resize_enabled: false,
		        enterMode : CKEDITOR.ENTER_BR,
		        shiftEnterMode: CKEDITOR.ENTER_P
			});

			$('#openReport_quotes').on('click', function(event) {
				window.open('openReport?type=customer_quotes&dateStart=' + $('#startDate').val() + '&dateEnd=' + $('#endDate').val() + '&customerId={{ $customer->id }}', '_blank');
			});

			$('#openReport_invoices').on('click', function(event) {
				window.open('openReport?type=customer_invoices&dateStart=' + $('#startDate').val() + '&dateEnd=' + $('#endDate').val() + '&customerId={{ $customer->id }}', '_blank');
			});

			$('#openReport_creditnotes').on('click', function(event) {
				window.open('openReport?type=customer_creditnotes&dateStart=' + $('#startDate').val() + '&dateEnd=' + $('#endDate').val() + '&customerId={{ $customer->id }}', '_blank');
			});

			// REMEMBER SCROLL POSITION & BLINK ON BACK
			$('#main_content').on('scroll', function(event) {
				var customerShowSettings = JSON.parse(localStorage.getItem('customerShowSettings_{{ $customer->id }}'));

				if (!customerShowSettings) {
					customerShowSettings = {};
				}

				customerShowSettings.scrollPosition = $(this).scrollTop();
				customerShowSettings.timestamp = Date.now();

				localStorage.setItem('customerShowSettings_{{ $customer->id }}', JSON.stringify(customerShowSettings));

			});

			// Remove old customerShowSettings from localStorage
			for (var key in localStorage) {
				// Get the value
				var value = localStorage.getItem(key);

				// If the value is undefined, we continue
				if (!value || value == undefined || value === 'undefined')
					continue;

				// Check if the key starts with customerShowSettings
				if (key.substring(0, 21) == 'customerShowSettings_') {
					// Get the parsed value
					value = JSON.parse(value);

					// Check if the timestamp is older than 1 hour
					if (value.hasOwnProperty('timestamp') && value.timestamp < (Date.now() - 3600000)) {
						// Old data, remove
						localStorage.removeItem(key);
					}
				}
			}

			var customerShowSettings = JSON.parse(localStorage.getItem('customerShowSettings_{{ $customer->id }}'));
			if (customerShowSettings) {
				if (customerShowSettings.timestamp < (Date.now() - 3600000)) {
					localStorage.removeItem('customerShowSettings_{{ $customer->id }}');
				} else {
					if (customerShowSettings.scrollPosition)
						$('#main_content').animate({
							scrollTop: customerShowSettings.scrollPosition
						}, 1000);

					if (customerShowSettings.blinkInfo) {
						var blinkInfo = customerShowSettings.blinkInfo;

						switch(blinkInfo.type) {
							case 'quote':
								var row = $('#quote_row_' + blinkInfo.id + ' td');
								row.css('background-color', '#E1FFE4');
								setTimeout(function() {
									row.css('background-color', 'transparent');
								}, 4000);
							break;
							case 'invoice':
								var row = $('#invoice_row_' + blinkInfo.id + ' td');
								row.css('background-color', '#E1FFE4');
								setTimeout(function() {
									$('#invoice_row_' + blinkInfo.id + ' td').css('background-color', 'transparent');
								}, 4000);
							break;
						}
					}
				}
			}
		});

		function setBlinkInfo(type, id) {
			var customerShowSettings = JSON.parse(localStorage.getItem('customerShowSettings_{{ $customer->id }}'));

			if (!customerShowSettings)
				customerShowSettings = {
					time: Date.now()
				};

			customerShowSettings.blinkInfo = {
				'type': type,
				'id': id
			}

			customerShowSettings.timestamp = Date.now();

			localStorage.setItem('customerShowSettings_{{ $customer->id }}', JSON.stringify(customerShowSettings));
		}

		function confirmRenewalCancel(renewalId) {
			confirmDialog(
				'Are you sure?',
				'Are you sure you want to cancel this renewal? This cannot be undone!',
				function() {
					window.location.assign('/renewals/' + renewalId + '/cancel');
				}
			);
		}

		function confirmRenewalDeletion(renewalId) {
			confirmDialog(
				'Are you sure?',
				'Are you sure you want to delete this renewal?<br><br><b>This deletion cannot be undone!</b>',
				function() {
					window.location.assign('/renewals/' + renewalId + '/delete');
				}
			);
		}

		function confirmRenewalRenewal(renewalId) {
			confirmDialog(
				'Are you sure?',
				'Are you sure you want to renew this renewal? This will automatically renew the item and create a new quote for the new period.',
				function() {
					window.location.assign('/renewals/' + renewalId + '/quote');
				}
			);
		}

		@if ($customer->hasCoordinates())
			function resizeMap() {
				setTimeout(function() {
					locationMap = new GMaps({
						div: '#locationMap',
						zoom: 15,
						lat: {{ $customer->locationLat }},
						lng: {{ $customer->locationLng }},
						width: '100%',
						height: '500px'
					});
					locationMap.addMarker({
						lat: {{ $customer->locationLat }},
						lng: {{ $customer->locationLng }},
						draggable: true,
						dragend: function(e) {
							var location = e.latLng;
							$('#customer_newlat').val(location.lat());
							$('#customer_newlng').val(location.lng());
							$('#customer_save_location').slideDown(200);
							$('#customer_disable_location').hide();
						}
					})
				}, 200);
			}
		@else
			function resizeMap() {
				setTimeout(function() {
					locationMap = new GMaps({
						div: '#locationMap',
						zoom: 15,
						lat: 36.5439433,
						lng: -4.6255634,
						width: '100%',
						height: '500px'
					});
					locationMap.addMarker({
						lat: 36.5439433,
						lng: -4.6255634,
						draggable: true,
						dragend: function(e) {
							var location = e.latLng;
							$('#customer_newlat').val(location.lat());
							$('#customer_newlng').val(location.lng());
							$('#customer_save_location').slideDown(200);
							$('#customer_disable_location').hide();
						}
					})
				}, 200);
			}
		@endif

		$('body').on('click', '#customer_save_location', function(event) {
			ajaxRequest(
				'save_customer_location',
				{
					customerId: {{ $customer->id }},
					lat: $('#customer_newlat').val(),
					lng: $('#customer_newlng').val()
				},
				function(data) {
					if (data.success) {
						location.reload();
					}
				}
			);
		});

		$('body').on('click', '#customer_disable_location', function(event) {
			confirmDialog(
				'Are you sure?',
				'Are you sure you want to disable map location for this customer? The location will be deleted permanently.',
				function() {
					ajaxRequest(
						'disable_customer_location',
						{
							customerId: {{ $customer->id }}
						},
						function(data) {
							if (data.success) {
								location.reload();
							}
						}
					);
				}
			);
		});

		function editFileDescription(button) {
			var $button = $(button);
			var $column = $button.closest('td');
			var $row = $button.closest('tr');
			var fileId = $row.attr('file-id');

			$button.remove();

			var $columnContents = $column.html().trim();

			$column.html("<input type='text' id='file_edit_desc_input' style='width:80%;' >&nbsp;&nbsp;<button class='btn btn-default'onclick='saveFileDescription(this);'><i class='fa fa-save'></i> Save</button>");
			$column.find('input').val($columnContents.trim());
			$column.find('input').focus();
		}

		function saveFileDescription(button) {
			var $button = $(button);
			var $column = $button.closest('td');
			var $row = $button.closest('tr');
			var fileId = $row.attr('file-id');
			var $input = $row.find('input[type="text"]').first();
			var newDesc = $input.val();

			$column.html($input.val().trim() + " <button class='btn btn-orange pull-right' onclick='editFileDescription(this)'><i class='fa fa-edit'></i></button>");

			ajaxRequest(
				'customer_file_save_description',
				{
					customerId: <?= $customer->id; ?>,
					'fileId': fileId,
					description: newDesc
				},
				function(data) {
					if (data.success)
						showSuccess("Description saved succesfully");
				}
			);
		}

		function showNotes(button) {
			$(button).closest('table').find('tr').hide();
			$(button).closest('tr').next('tr').show();
		}

		function saveRenewalNotes(button) {
			$(button).closest('table').find('tr').hide();
			$(button).closest('table').find('tr:not(.notes)').show();
			ajaxRequest(
				'update_renewal_notes',
				{
					renewalId: $(button).closest('tr').attr('data-renewal-id'),
					notes: $(button).closest('tr').find('textarea').val()
				},
				function(data) {
					if (data.success) {
						notify('Notes updated succesfully');
					}
				}
			);
		}

		function cancelRenewalNotes(button) {
			$(button).closest('table').find('tr').hide();
			$(button).closest('table').find('tr:not(.notes)').show();
		}

		function saveQuickInfoSettings() {
			var fields = [];
			$('input[name="quick_info_field[]"]:checked').each(function() {
				fields.push($(this).val());
			});
			notify('info', "<i class='fa fa-spinner fa-pulse'></i> Saving...", "Hold on for a second, I'm saving your settings!", 0, 'saving');	
			ajaxRequest(
				'customer_show_save_quick_info_settings',
				{
					'fields': fields
				},
				function(data) {
					if (data.success) {
						killNotification('saving');
						notify('success', '<i class="fa fa-check"></i> Save successful', "Refreshing your browser...", 0, '-');
						setTimeout(function() {
							location.reload();
						}, 800);
					}
				}
			);
		}

		$(function() {
			$('#contacthistory_newMessage').on('keydown', function(event) {
				if (event.which == 13)
					$('#contacthistory_saveNewMessage').trigger('click');
			});
		});

		function saveNewContactHistoryItem() {
			ajaxRequest(
				'contacthistory_newMessage',
				{
					customerId: {{ $customer->id }},
					message: $('#contacthistory_newMessage').val()
				},
				function(data) {
					if (data.success) {
						$('#contacthistory_table tr:first').after(data.new_row);
						showSuccess('Contact history item saved succesfully');
						$('#contacthistory_newMessage').val('');
					}
				}
			);
		}

		function enableCustomerLocation() {
			$('#map_disabled').hide();
			$('#map_enabled').show();
			resizeMap();
			//closeCurrentModal();
			//$('#open_map_modal').trigger('click');
		}

		function confirmFileDeletion(fileHash) {
			confirmDialog(
				'Are you sure?',
				'Are you sure you want to delete this file?<br><b>This action cannot be reversed!</b>',
				function() {
					ajaxRequest(
						'delete_customer_file',
						{
							customerId: {{ $customer->id }},
							'fileHash': fileHash
						},
						function(data) {
							if (data.success) {
								location.reload(true);
							} else {
								showError('An error occured. Could not delete file');
							}
						}
					);
				}
			);
		}

		function saveNewVATConfirmation() {
			ajaxRequest(
				'new_vat_confirm',
				{
					customer: {{ $customer->id }},
					text: $('#vat_newConfirm').val()
				},
				function() {
					location.reload();
				}
			);
		}

		function saveNewAddress() {
			ajaxRequest(
				'new_customer_address',
				{
					customer: {{ $customer->id }},
					label: $('.new-address-label').val(),
					address: $('.new-address-address').val(),
					city: $('.new-address-city').val(),
					postalcode: $('.new-address-postalcode').val(),
					province: $('.new-address-province').val(),
					country: $('.new-address-country').val(),
					telephone: $('.new-address-telephone').val(),
					email: $('.new-address-email').val()
				},
				function() {
					location.reload();
				}
			);
		}

		function deleteAddress(addressId) {
			confirmDialog(
				'Are you sure?',
				'Are you sure you want to delete this address?<br><b>This action cannot be reversed!</b>',
				function() {
					ajaxRequest(
						'delete_customer_address',
						{
							'addressId': addressId
						},
						function(data) {
							if (data.success) {
								location.reload(true);
							} else {
								showError('An error occured. Could not delete address');
							}
						}
					);
				}
			);
		}
	</script>
@stop

@section('stylesheets')
	<link rel="stylesheet" href="css/dropzone.css">
	<style>
		.sidebar {
			float: left;
			width: 200px;
		}

		.left_menu {
			list-style-type: none;

			height: 230px;
			overflow-y: auto;
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
			display: none;
			border-left: 1px solid #A7A7A7;

			padding: 15px;

			margin-left: 150px;

			height: 200px;
			overflow-y: auto;
		}

		.content h1 {
			margin: 0;

			margin-bottom: 20px;
		}

		.content .tab {
			display: none;
		}
	</style>
@stop

@section('modals')
	<div class="modal_content" id='configure_quick_info'>
		<p>Please select the information you want to show in the 'Quick Info' box:</p>
		<br>
		<input type='checkbox' name='quick_info_field[]' value='company_name'> Company Name<br>
		<input type='checkbox' name='quick_info_field[]' value='contact_name'> Contact Name<br>
		<input type='checkbox' name='quick_info_field[]' value='address'> Address<br>
		<input type='checkbox' name='quick_info_field[]' value='telephone_mobile'> Telephone / Mobile<br>
		<input type='checkbox' name='quick_info_field[]' value='email'> Email<br>
		<input type='checkbox' name='quick_info_field[]' value='cif'> CIF/NIF<br>
		<input type='checkbox' name='quick_info_field[]' value='visit_fee'> Visit Fee<br>
		<input type='checkbox' name='quick_info_field[]' value='credit_rating'> Credit Rating
		<br><br>
		<button class="btn btn-green fr ml5" onclick='saveQuickInfoSettings()'><i class="fa fa-save"></i> Save settings</button>
		<button class="btn btn-red fr modal-close"><i class="fa fa-close"></i> Cancel</button>
		<br clear='both'>
	</div>
	<div class="modal_content" id='edit_customer'>
		{{ Form::model($customer, array('route' => array('customers.update', $customer->id), 'method' => 'PUT')) }}
		<div class="bit-2">
			<table class="form">
				<tr>
					<td style='width: 130px;'>Customer Type</td>
					<td style='width: 100%;'>{{ Form::select('type', CustomerType::pluck('type', 'id'), null, array('class' => 'w200', 'style' => 'width: 100px;')) }}</td>
				</tr>
				<tr>
					<td style='width: 130px;'>Customer Sector</td>
					<td style='width: 100%;'>{{ Form::select('sector', Sector::pluck('type', 'id'), null, array('class' => 'w200', 'style' => 'width: 100px;')) }}</td>
				</tr>
				<tr>
					<td>Credit rating</td>
					<td>{{ Form::select('credit', CustomerCreditRating::pluck('type', 'id'), null, array('class' => 'w200', 'style' => 'width: 100px;')) }}</td>
				</tr>
				<tr>
					<td>Payment Terms</td>
					<td>{{ Form::select('paymentTerms', PaymentTerm::pluck('type', 'id'), null, array('class' => 'w200', 'style' => 'width: 100px;')) }}</td>
				</tr>
				<tr>
					<td>Currency</td>
					<td>{{ Form::select('currency', Currency::pluck('name', 'id'), null, array('class' => 'w200', 'style' => 'width: 100px;')) }}</td>
				</tr>
				<tr>
					<td>Visit fee</td>
					<td>{{ Form::text('assignedVisitFee', null, array('class' => 'euro', 'style' => 'width: 100px;')) }}</td>
				</tr>
				<tr>
					<td>Newsletter</td>
					<td>{{ Form::select('newsletter', array(1 => 'Yes', 0 => 'No')) }}</td>
				</tr>
				<tr>
					<td>Ad type</td>
					<td>{{ Form::select('advertisingType', AdType::pluck('type', 'id')) }}</td>
				</tr>
				<tr>
					<td>Read-only</td>
					<td>{{ Form::select('readonly', array(1 => 'Yes', 0 => 'No')) }}</td>
				</tr>
				<tr>
					<td>Representative</td>
					<td>{{ 
						Form::select('managedBy', User::where('disabled', '=', 0)->get()->pluck('firstname', 'id'), null, ['class' => 'w200']) 
					}}</td>
				</tr>
			</table>
			<br>
			<hr>
			<br>
			<table class="form">
				<tr>
					<td style='width: 130px;'>Customer name</td>
					<td style='width: 100%'>{{ Form::text('companyName') }}</td>
				</tr>
				<tr>
					<td>Customer code</td>
					<td>{{ Form::text('customerCode') }}</td>
				</tr>
				<tr>
					<td>Tax Id</td>
					<td>{{ Form::text('cifnif') }}</td>
				</tr>
				<tr>
					<td>Contact title</td>
					<td>{{ Form::text('contactTitle', null, ['placeholder' => 'Mr, Mrs, Dr, Miss...']) }}</td>
				</tr>
				<tr>
					<td>Contact name</td>
					<td>{{ Form::text('contactName') }}</td>
				</tr>
				<tr>
					<td>Shop name</td>
					<td>{{ Form::text('shopName') }}</td>
				</tr>
				<tr class='pt'>
					<td>Phone</td>
					<td>{{ Form::text('phone') }}</td>
				</tr>
				<tr>
					<td>mobile</td>
					<td>{{ Form::text('mobile') }}</td>
				</tr>
				<tr>
					<td>Fax</td>
					<td>{{ Form::text('fax') }}</td>
				</tr>
				<tr>
					<td>Email address</td>
					<td>{{ Form::text('email') }}</td>
				</tr>
				<tr>
					<td>Website</td>
					<td>{{ Form::text('website') }}</td>
				</tr>
				<tr>
					<td>Skype name</td>
					<td>{{ Form::text('skype') }}</td>
				</tr>
			</table>
		</div>

		<div class="bit-2">
			<table class="form">
				<tr>
					<td class='pt'>Address</td>
					<td class='pt'>{{ Form::textarea('address') }}</td>
				</tr>
				<tr>
					<td>City</td>
					<td>{{ Form::text('city') }}</td>
				</tr>
				<tr>
					<td>Region</td>
					<td>{{ Form::text('region') }}</td>
				</tr>
				<tr>
					<td>Postal code</td>
					<td>{{ Form::text('postalCode') }}</td>
				</tr>
				<tr>
					<td>Country</td>
					<td>{{ Form::text('country') }}</td>
				</tr>
				<tr>
					<td>Visual directions</td>
					<td>{{ Form::textarea('visualDirections') }}</td>
				</tr>
			</table>

			<br><br>
			{{ Form::button('<i class="fa fa-save"></i> Save customer', array('class' => 'btn btn-green fr ml10 mr10', 'type' => 'submit'))}}
			<button type='button' class="btn btn-grey fr" onclick='closeCurrentModal()'><i class="fa fa-remove"></i> Discard changes</button>
		</div>
		<br clear='both'>
	</div>
@stop

@section('content')
<div class="modal_content nop" id='locationMapPopup'>
	@if (!$customer->hasCoordinates())
		<div id='map_disabled'>
	@else
		<div id='map_disabled' class='hidden'>
	@endif
		<div style='width: 100%; height: 500px; text-align: center; padding-top: 160px;'>
			<i class="fa fa-map-marker" style='font-size: 72pt;'></i><br>
			Oops! Map location for this customer is currently disabled.<br><br>
			<button class="btn btn-green" onclick='enableCustomerLocation()'><i class="fa fa-toggle-on"></i> Turn map location on</button>
		</div>
	</div>
	@if (!$customer->hasCoordinates())
		<div id='map_enabled' class='hidden'>
	@else
		<div id='map_enabled'>
	@endif
		<div style='height: 500px;'>
			<div id='locationMap'></div>
		</div>
		<div class='m10'>
			<i class="fa fa-info-circle"></i> To change the location of this customer, drag the droplet to a new location. When done, click the save location button.<br><br>
			<center>
				<button class="btn btn-green hidden" id='customer_save_location'>
					<i class="fa fa-save"></i> Save location
				</button>
				<button class="btn btn-grey" id='customer_disable_location'>
					<i class="fa fa-toggle-off"></i> Turn map location off
				</button>
			</center>
			<input type='hidden' id='customer_newlat'>
			<input type='hidden' id='customer_newlng'>
		</div>
	</div>
</div>
<div class="frame">
	<div class="bit-2">
		<div class="container">
			<div class="container_title blue">
				<i class="fa fa-fighter-jet"></i> Quick Info
				<i class="container_title_button fa fa-map-marker" style='color: #51AD00' id="open_map_modal"
					data-modal-id='locationMapPopup'
					data-modal-title='<i class="fa fa-map-marker"></i> Customer location'
					data-modal-width='800'
					data-modal-closeable='true'
					onclick='resizeMap();'
					data-tooltip="View customer's location on map"
					data-modal-no-padding='true'></i>
			</div>
			<div class="container_content" style='height: 230px;'>
				<div class="bit-2 nop">
					@if($customer->companyName != '')
						<i class="fa fa-building fixedwidth"></i> {{ $customer->companyName }}&nbsp;&nbsp;
					@endif
					@if ($customer->contactName != '')
					<i class="fa fa-user fixedwidth"></i> {{ $customer->getContactPerson() }}
					@endif
					<br>
					@if ($customer->shopName != '')
						<i class="fa fa-shopping-bag fixedwidth"></i> {{ $customer->shopName }}
						<br>
					@endif
					@if ($customer->address != '')
						<i class="fa fa-location-arrow fl fixedwidth"></i>
						<div class='fl' style='margin-left: 4px;'> 
							{{ trim(nl2br($customer->address)) }}<br>
							{{ $customer->postalCode }} {{ $customer->city }}, {{ $customer->region }}
						</div>
						<br clear='both'>
					@endif
					<i class="fa fa-phone fixedwidth"></i> {{ $customer->phone }} &nbsp;&nbsp&nbsp; <i class="fa fa-mobile fixedwidth"></i> {{ $customer->mobile }}
					<br>
					<i class="fa fa-envelope fixedwidth"></i> <a href='mailto:{{ $customer->email }}'>{{ $customer->email }}</a> {{ ($customer->newsletter == 0) ? ' (unsubscribed on ' . date('d-m-Y', strtotime($customer->newsletter_unsubscribe)) . ')' : ""; }}
					<br>
					<i class="fa fa-asterisk fixedwidth"></i> CIF: {{ $customer->cifnif }}
				</div>
				<div class="bit-2 nop">
					@if ($customer->createdBy != 0)
						<i class="fa fa-user fixedwidth"></i> <b>Created by:</b> {{ User::find($customer->createdBy)->getFullname() }}<br>
					@endif
					@if ($customer->type != 1)
						<i class="fa fa-circle fixedwidth"></i> <b>Type:</b> {{ $customer->getType->type }}
						<br>
					@endif
					<i class="fa fa-circle fixedwidth"></i> <b>Sector:</b> {{ Sector::find($customer->sector)->type; }}
					<br>
					@if ($customer->managedBy != 0)
						<i class="fa fa-user fixedwidth"></i> <b>Representative:</b> {{ User::find($customer->managedBy)->getFullname() }}
						<br>
					@endif
					<i class="fa fa-credit-card fixedwidth"></i> <b>Rating:</b> {{ $customer->getCustomerCreditRating->type }}
					<br>
					<i class="fa fa-th-list fixedwidth"></i> <b>Payment Terms:</b> {{ $customer->getPaymentTerms->type }}
					<br>
					<i class="fa fa-money fixedwidth"></i> <b>Currency:</b> {{ $customer->getCurrency->name }}
					<br>
					<i class="fa fa-car fixedwidth"></i> <b>Visit fee:</b> € {{ $customer->assignedVisitFee }}
					<br>
					<i class="fa fa-newspaper-o fixedwidth"></i> <b>Ad source:</b> {{ $customer->getAdType->type }}
					@if (!empty($customer->skype))
						<br>
						<a href="skype:{{ $customer->skype }}?call"><img src="http://www.skypeassets.com/i/scom/images/skype-buttons/callbutton_24px.png"></a>
					@endif
					<br><br>
					@if (Auth::user()->hasPermission('customer_edit'))
						<button class="btn btn-orange" data-modal-id='edit_customer' data-modal-title='Edit customer' data-modal-width='900' data-modal-closeable='false' onclick='refreshEditMap()'>
							<i class="fa fa-edit"></i>
							Edit customer details
						</button>
					@endif
				</div>
			</div>
		</div>
	</div>
	<div class="bit-2">
		<div class="container">
			<div class="container_title blue">
				<ul style='float: left;' data-tab-remember='customer_top_info'>
					<li data-tab='notes' class='active'>{{ Settings::setting('notes1Name') }}</li>
					<li data-tab='notes2'>{{ Settings::setting('notes2Name') }}</li>
					<li data-tab="bank_details">Bank details</li>
					<!-- <li data-tab='details'>Additional details</li> -->
					<li data-tab='reports'>Reports</li>
					<li data-tab='addresses'>Addresses</li>
				</ul>
				<button class='btn btn-green fr' onclick='saveNotes()'><i class="fa fa-save"></i> Save</button>
			</div>
			<div class="container_content nop" style='height: 230px;'>
				<div class="active" data-tab='notes'>
					<textarea style='width: 100%; height: 100%;' id='notes'>{{ $customer->notes }}</textarea>
				</div>
				<div data-tab='notes2'>
					<textarea style='width: 100%; height: 100%;' id='notes2'>{{ $customer->notes2 }}</textarea>
				</div>
				<div data-tab="bank_details">
						<table class="form">
							<tr>
								<td style='width: 150px;'>Account Holder</td>
								<td style='width: 100%;'>{{ Form::text('accountHolder', null, ['id' => 'accountHolder', 'style' => 'width: 200px;']) }}</td>
							</tr>
							<tr>
								<td>Bank Name</td>
								<td>{{ Form::text('bankName', null, ['id' => 'bankName', 'style' => 'width: 200px;']) }}</td>
							</tr>
							<tr>
								<td>SwiftCode</td>
								<td>{{ Form::text('swiftCode', null, ['id' => 'swiftCode', 'style' => 'width: 200px;']) }}</td>
							</tr>
							<tr>
								<td>IBAN</td>
								<td>{{ Form::text('iban', null, ['id' => 'iban', 'style' => 'width: 300px;']) }}</td>
							</tr>
							<tr>
								<td>SEPA Mandate ID</td>
								<td>{{ Form::text('sepa_mandateId', null, ['id' => 'sepa_mandateId', 'style' => 'width: 200px;']) }}</td>
							</tr>
							<tr>
								<td>SEPA Mandate Date</td>
								<td>{{ Form::text('sepa_mandateDate', null, ['id' => 'sepa_mandateDate', 'style' => 'width: 200px;', 'class' => 'basinput date']) }}</td>
							</tr>
						</table>
					@if (!empty($customer->accountId))
						Bank: {{ $customer->bankId . ' ' . $customer->branchId . ' ' . $customer->dc . ' ' . $customer->accountId }}<br>
						Bank: {{ $customer->bankId . $customer->branchId . $customer->dc . $customer->accountId }}
					@endif
				</div>
				<!-- <div data-tab='details'>
					<div class="containerpadding">
						<table style='width: 100%;'>
							<tr>
								<td class='bold'>Customer Credit Rating</td>
								<td>{{ $customer->getCustomerCreditRating->type }}</td>
							</tr>
							<tr>
								<td class='bold'>Quote count</td>
								<td>{{ $customer->getQuoteCount() }}</td>
							</tr>
							<tr>
								<td class='bold'>Invoice count</td>
								<td>{{ $customer->getInvoiceCount() }}</td>
							</tr>
							<tr>
								<td class="bold">Unpaid quotes</td>
								<td><span class="fcred">{{ $customer->getUnpaidInvoiceCount() }}</span></td>
							</tr>
						</table>
					</div>
				</div> -->
				<div data-tab='reports'>
					<div class="containerpadding">
						<p>
							Please select a date range and click the quote or invoice button to generate reports in PDF format.
						</p>
						<table class='form'>
							<tr>
								<td>Start date:</td>
								<td><input type='text' name='startDate' id='startDate' class='basinput date'></td>
							</tr>
							<tr>
								<td>End date:</td>
								<td><input type='text' name='endDate' id='endDate' class='basinput date'></td>
							</tr>
						</table>
						<br>
						<button class="btn btn-default" id='openReport_quotes' style='width: 32%;'>
							<i class="fa fa-download"></i> Quotes
						</button>
						<button class="btn btn-default" id='openReport_invoices' style='width: 32%;'>
							<i class="fa fa-download"></i> Invoices
						</button>
						<button class="btn btn-default" id='openReport_creditnotes' style='width: 32%;'>
							<i class="fa fa-download"></i> Credit notes
						</button>
					</div>
				</div>
				<div data-tab="addresses" class="tab-container">
					<div class="sidebar">
						<ul class="left_menu">
							@if ($customer->addresses)
								<?php $index = 0; ?>
								@foreach($customer->addresses as $index => $address)
									<li data-tab="{{ $address->getKey() }}" <?php if ($index == 0) echo 'class="active"'; ?>><i class="fa fa-map-marker"></i> {{ $address->label }}</li>
									<?php $index++; ?>
								@endforeach
							@endif
							<li data-tab="new|address"><i class="fa fa-plus"></i> New Address</li>
						</ul>
					</div>
					@if ($customer->addresses)
						@foreach($customer->addresses as $address)
							<div class="content address" data-tab="{{ $address->getKey() }}" data-id="{{ $address->getKey() }}">
								<table class="form">
									<tr>
										<td style="width: 110px;">Label</td>
										<td style="width: 100%;"><input type="text" class="address-label" value="{{ $address->label }}"></td>
									</tr>
									<tr>
										<td>Address</td>
										<td><textarea class="address-address">{{ $address->address }}</textarea></td>
									</tr>
									<tr>
										<td>City</td>
										<td><input type="text" class="address-city" value="{{ $address->city }}"></td>
									</tr>
									<tr>
										<td>Postal Code</td>
										<td><input type="text" class="address-postalcode" value="{{ $address->postalcode }}"></td>
									</tr>
									<tr>
										<td>Province</td>
										<td><input type="text" class="address-province" value="{{ $address->province }}"></td>
									</tr>
									<tr>
										<td>Country</td>
										<td><input type="text" class="address-country" value="{{ $address->country }}"></td>
									</tr>
									<tr>
										<td>Telephone</td>
										<td><input type="text" class="address-telephone" value="{{ $address->telephone }}"></td>
									</tr>
									<tr>
										<td>Email</td>
										<td><input type="text" class="address-email" value="{{ $address->email }}"></td>
									</tr>
									<tr>
										<td>Delivery Address</td>
										<td><input type="checkbox" class="address-delivery" value="1" <?php if ($customer->deliveryAddress == $address->id) echo "checked='checked'"; ?>></td>
									</tr>
									<tr>
										<td></td>
										<td><button class="btn btn-red" onclick="deleteAddress({{ $address->id }})"><i class="fa fa-remove"></i> Delete address</a></td>
									</tr>
								</table>
							</div>
						@endforeach
					@endif
					<div class='content new-address' data-tab='new|address' style='height: 231px; overflow: auto;'>
						<h2>New Address</h2>
						<table class="form">
							<tr>
								<td style="width: 110px;">Label</td>
								<td style="width: 100%;"><input type="text" class="new-address-label"></td>
							</tr>
							<tr>
								<td>Address</td>
								<td><textarea class="new-address-address"></textarea></td>
							</tr>
							<tr>
								<td>City</td>
								<td><input type="text" class="new-address-city"></td>
							</tr>
							<tr>
								<td>Postal Code</td>
								<td><input type="text" class="new-address-postalcode"></td>
							</tr>
							<tr>
								<td>Province</td>
								<td><input type="text" class="new-address-province"></td>
							</tr>
							<tr>
								<td>Country</td>
								<td><input type="text" class="new-address-country"></td>
							</tr>
							<tr>
								<td>Telephone</td>
								<td><input type="text" class="new-address-telephone"></td>
							</tr>
							<tr>
								<td>Email</td>
								<td><input type="text" class="new-address-email"></td>
							</tr>
							<tr>
								<td></td>
								<td><button type="button" class="btn btn-green" onclick="saveNewAddress()"><i class="fa fa-plus"></i> Add</button></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<br clear='all'>
<div class="frame">
	<div class="bit-1">
		<div class="container">
			<div class="container_title orange">
				<ul data-tab-remember='customer_bottom_info' style='float: left;'>
					@if (Auth::user()->hasPermission('customer_quote')) <li class="active" data-tab='quotes'>Quotes</li> @endif
					@if (Auth::user()->hasPermission('customer_history')) <li data-tab='contacthistory'>Contact History</li> @endif
					@if (Auth::user()->hasPermission('customer_invoice')) <li data-tab='invoices'>Invoices</li> @endif
					@if (Auth::user()->hasPermission('customer_invoice')) <li data-tab='creditnotes'>Credit Notes</li> @endif
					@if (Auth::user()->hasPermission('renewal_list_customer')) <li data-tab='renewals'>Renewals</li> @endif
					@if (Auth::user()->hasPermission('payment_list')) <li data-tab='payments'>Payments</li> @endif
					@if (Auth::user()->hasPermission('payment_list')) <li data-tab='payment_details'>Payment Details</li> @endif
					<li data-tab='files'>Files</li>
					<li data-tab='vatconfirms'>VAT Confirms</li>
				</ul>
			</div>
			<div class="container_content">
				<div data-tab='quotes' class="active">
					@if($customer->readonly == 0)
					<button data-href='/quotes/create/{{ $customer->id }}' class='btn btn-green' id='quote_create'>
						<i class="fa fa-plus"></i> Create new Quote
					</button>
					@else
					<span style='color: #9F0000;'>You cannot create quotes on read-only customers</span>
					@endif
					<table class="data highlight sortable" id='quote_table'>
						<thead>
							<tr>
								<th style='width: 90px;' data-sortable-date>Date</th>
								<th style='width: 90px;'>Quote</th>
								<th style='width: 100%' data-sortable-no>Description</th>
								<th style='width: 200px'>Status</th>
								<th style='width: 90px'>Price</th>
								<th style='width: 90px'>Received</th>
								<th style='width: 90px'>Pending</th>
								<th style='width: 100px'>Invoice</th>
							</tr>
						</thead>
						<tbody>
							@foreach($customer->getQuotes as $quote)
							<tr style='cursor: pointer;' data-href='/quotes/{{ $quote->id }}/edit' onclick='setBlinkInfo("quote", {{ $quote->id }});' id='quote_row_{{ $quote->id }}'>
								<td>{{ CommonFunctions::formatDate($quote->createdOn) }}</td>
								<td>{{ $quote->id }}</td>
								<td>{{ $quote->description }}</td>
								<td>{{ $quote->getStatus->type }}</td>
								<td class='tar'>€ {{ number_format($quote->getTotal(), 2, ',', '.') }}</td>
								<td class='tar'>€ {{ number_format($quote->getPaid(), 2, ',', '.') }}</td>
								@if((($quote->getTotal() - $quote->getPaid()) > 0.02) && in_array($quote->status, explode(',', Settings::setting('awaitingPaymentJobStatusses'))))
									<td style='color: red'; class='tar bold'>€ {{ number_format(($quote->getTotal() - $quote->getPaid()), 2, ',', '.') }}</td>
								@else
									<td style='color: #004A02;' class='tar'>€ {{ number_format(($quote->getTotal() - $quote->getPaid()), 2, ',', '.') }}</td>
								@endif
								<td class='no-click-area'>
									@if ($quote->hasBeenInvoiced())
										<a href="/invoices/{{ $quote->getInvoiceId() }}/edit">{{ $quote->getInvoiceId() }}</a>
									@endif
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				<div data-tab='contacthistory'>
					<input type="text" id='contacthistory_newMessage' placeholder='New message...' style='width: 600px'>
					<button class='btn btn-green' id='contacthistory_saveNewMessage' onclick='saveNewContactHistoryItem()'>
						<i class="fa fa-save"></i> Save message
					</button>
					<table class="data" id='contacthistory_table'>
						<tr>
							<th style='width: 180px;'>Date/Time</th>
							<th style='width: 200px;'>User</th>
							<th style='width: 100%'>Message</th>
						</tr>
						@foreach($customer->getContactHistory as $contactItem)
						<tr>
							<td>{{ CommonFunctions::formatDatetime($contactItem->placedOn) }}</td>
							<td>{{ $contactItem->getPlacedBy->getFullname() }}</td>
							<td>{{ $contactItem->message }}</td>
						</tr>
						@endforeach
					</table>
				</div>
				<div data-tab='invoices'>
					@if(!$customer->readonly)
					<button class="btn btn-green" data-href='/invoices/create/{{ $customer->id }}'>
						<i class="fa fa-plus"></i> New invoice
					</button>
					@else
					<span style='color: #9B0000;'>You can not create invoices for read-only customers</span>
					@endif
					<table class="data highlight sortable" id='invoice_table'>
						<thead>
							<tr>
								<th style='width: 100px;' data-sortable-date>Date</th>
								<th style='width: 110px;'>Invoice</th>
								<th style='width: 100%;'>Description</th>
								<th style='width: 130px;'>Total</th>
							</tr>
						</thead>
						<tbody>
							@foreach($customer->getInvoices as $invoice)
							<tr style='cursor: pointer;' data-href='/invoices/{{ $invoice->id }}/edit' onclick='setBlinkInfo("invoice", {{ $invoice->id }});' id='invoice_row_{{ $invoice->id }}'>
								<td>{{ CommonFunctions::formatDate($invoice->createdOn) }}</td>
								<td>{{ $invoice->id }}</td>
								<td>{{ $invoice->jobTitle }}</td>
								<td>&euro; {{ CommonFunctions::formatNumber($invoice->getTotal()) }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				<div data-tab='creditnotes'>
					@if(!$customer->readonly)
						<button class="btn btn-green" data-href='/invoices/create/{{ $customer->id }}'>
							<i class="fa fa-plus"></i> New credit note
						</button>
					@else
						<span style='color: #9B0000;'>You can not create credit notes for read-only customers</span>
					@endif
					<table class="data highlight sortable" id='invoice_table'>
						<thead>
						<tr>
							<th style='width: 100px;' data-sortable-date>Date</th>
							<th style='width: 110px;'>Credit Note</th>
							<th style='width: 100%;'>Description</th>
							<th style='width: 130px;'>Total</th>
						</tr>
						</thead>
						<tbody>
						@foreach($customer->getCreditnotes as $creditnote)
							<tr style='cursor: pointer;' data-href='/creditnotes/{{ $creditnote->id }}/edit' onclick='setBlinkInfo("creditnote", {{ $creditnote->id }});' id='creditnote_row_{{ $creditnote->id }}'>
								<td>{{ CommonFunctions::formatDate($creditnote->createdOn) }}</td>
								<td>{{ $creditnote->id }}</td>
								<td>{{ $creditnote->jobTitle }}</td>
								<td>&euro; {{ CommonFunctions::formatNumber($creditnote->getTotal()) }}</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
				@if(Auth::user()->hasPermission('renewal_list_customer'))
					<div data-tab='renewals' id='renewals'>
						@if(!$customer->readonly)
						<button data-href='/customers/{{ $customer->id }}/createRenewal' class='btn btn-green' id='quote_create'>
							<i class="fa fa-plus"></i> Create new renewal
						</button>
						@else
						<span style='color: #9F0000;'>You cannot create renewals for read-only customers</span>
						@endif
						<table class="data highlight sortable" id='renewals_table'>
							<thead>
								<tr>
									<th style='width: 40%;'>Product name</th>
									<th style='width: 60%;' data-sortable-date>Notes</th>
									<th style='width: 100px;' data-sortable-date>Start date</th>
									<th style='width: 110px;' data-sortable-no>Frequency</th>
									<th style='width: 130px;'>Next renewal</th>
									<th style='width: 50px;' data-sortable-no>D%</th>
									<th style='width: 80px;' data-sortable-no>Active</th>
									<th style='width: 40px' data-sortable-no></th>
									<th style='width: 40px;' data-sortable-no></th>
									<th style='width: 40px;' data-sortable-no></th>
									<th style='width: 40px;' data-sortable-no></th>
								</tr>
							</thead>
							<tbody>
								@foreach($customer->getRenewals as $renewal)
								<tr>
									<td>{{ $renewal->getProduct->name }}</td>
									<td>{{ $renewal->notes }}</td>
									<td>{{ $renewal->startDate }}</td>
									<td>{{ $renewal->renewalFreq }} {{ ($renewal->renewalFreq == 1) ? 'Month' : 'Months' }}</td>
									<td>{{ $renewal->nextRenewalDate }}</td>
									<td>{{ $renewal->discount }}%</td>
									<td align='center'>
										@if($renewal->cancelled == 0)
											<i class="fa fa-check" style='color: #0CD301;'></i>
										@else
											<i class="fa fa-remove" style='color: #F40000;'></i>
										@endif
									</td>
									<td>
										<button class="btn btn-orange" data-tooltip='Edit notes for this renewal' onclick='showNotes(this)'>
											<i class="fa fa-comment"></i>
										</button>
									</td>
									<td>
										@if($renewal->cancelled == 0)
											@if (Auth::user()->hasPermission('renewal_quote'))
												<button class="btn btn-default" data-tooltip='Renew & quote' onclick='confirmRenewalRenewal({{ $renewal->id }})'>
													<i class="fa fa-share"></i>
												</button>
											@endif
										@else
											Cancelled on: {{ CommonFunctions::formatDate($renewal->cancelDate) }}
										@endif
									</td>
									<td>
										@if($renewal->cancelled == 0)
											@if (Auth::user()->hasPermission('renewal_cancel'))
												<button class="btn btn-red" data-tooltip='Cancel this renewal' onclick='confirmRenewalCancel({{ $renewal->id }})'>
													<i class="fa fa-minus"></i>
												</button>
											@endif
										@else
											{{ CommonFunctions::formatDate($renewal->cancelDate) }}
										@endif
									</td>
									<td>
										@if (Auth::user()->hasPermission('renewal_cancel'))
											<button class="btn btn-red" data-tooltip='Permanently delete this renewal' onclick='confirmRenewalDeletion({{ $renewal->id }})'>
												<i class="fa fa-trash-o"></i>
											</button>
										@endif
									</td>
								</tr>
								<tr data-renewal-id="{{ $renewal->id }}" class='notes hide'>
									<td colspan='8'>
										<p>Here you can edit the notes for this renewal. Don't forget to click save!</p>
										<textarea class='fw'>{{ $renewal->notes }}</textarea>
										<button class="btn btn-green fr" style='margin-top: 10px; margin-left: 10px;' onclick='saveRenewalNotes(this)'>
											<i class="fa fa-save"></i> Save notes
										</button>
										<button class="btn btn-red fr" style='margin-top: 10px;' onclick='cancelRenewalNotes(this)'>
											<i class="fa fa-arrow-left"></i> Back
										</button>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				@endif
				@if(Auth::user()->hasPermission('payment_list'))
					<div data-tab='payments'>
						@if(!$customer->readonly)
						<button class="btn btn-green" data-href='payments/create/{{ $customer->id }}'>
							<i class="fa fa-plus"></i> New payment
						</button>
						@else
						<span style='color: #9F0000;'>You cannot create payments for read-only customers</span>
						@endif
						<table class="data highlight sortable">
							<thead>
								<tr>
									<th style='width: 130px;' data-sortable-date>Date</th>
									<th style='width: 200px;'>Type</th>
									<th style='width: 120px;'>Total</th>
									<th style='width: 120px;'>Non-cash</th>
									<th style='width: 100%;' data-sortable-no>Notes</th>
								</tr>
							</thead>
							<tbody>
								@foreach($customer->getPayments()->orderBy('date', 'DESC')->get() as $payment)
									<tr>
										<td>{{ CommonFunctions::formatDatetime($payment->date) }}</td>
										<td>{{ $payment->getPayMethod->type }}</td>
										<td>&euro; {{ CommonFunctions::formatNumber($payment->getTotal()) }}</td>
										<td>&euro; {{ CommonFunctions::formatNumber($payment->nonCash) }}</td>
										<td>{{ $payment->notes }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				@endif
				<div data-tab='payment_details'>
					<table class="data highlight sortable">
						<thead>
							<tr>
								<th style='width: 140px;' data-sortable-date>Date</th>
								<th style='width: 90px;'>Quote #</th>
								<th style='width: 100px;'>Amount</th>
								<th style='width: 100%;'>Paid with</th>
							</tr>
						</thead>
						<tbody>
							@foreach($customer->getPaymentDetails() as $detail)
								<tr>
									<td>{{ CommonFunctions::formatDate($detail->date) }}</td>
									<td>{{ $detail->quoteId }}</td>
									<td>€ {{ CommonFunctions::formatNumber($detail->amount) }}</td>
									<td>
										@if ($detail->getPayment)
											{{ $detail->getPayment->getPayMethod->type }}
										@endif
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				<div data-tab='files'>
					<button class="btn btn-green files_btn" onclick='$(".files_upload").show(); $(".files_list").hide(); $(this).hide();'><i class="fa fa-plus"></i> Upload file</button>
					<div class="files_upload hidden" style='background-color: #E7E7E7; border: 3px dotted #8E8E8E; padding: 10px;'>
						<div class="bit-2">
							<form action='customerfiles-upload/{{ $customer->id }}' method='POST' enctype="multipart/form-data">
								<h3 style='margin-bottom: 5px;'>Upload new file</h3>
								<table class='form'>
									<tr>
										<td style='width: 100px;'>File</td>
										<td style='width: 100%;'><input type='file' name='file'></td>
									</tr>
									<tr>
										<td>Description</td>
										<td><input type='text' name='description'></td>
									</tr>
									<tr>
										<td></td>
										<td>
											<button type='submit' class='btn btn-green'><i class="fa fa-upload"></i> Start upload</button>
											<button type='button' class="btn btn-grey" onclick='$(".files_upload").hide(); $(".files_list").show(); $(".files_btn").show();'><i class="fa fa-arrow-left"></i> Back to file list</button>
										</td>
									</tr>
								</table>
							</form>
						</div>
						<div class="bit-2">
							<h3>Or Drop 'n Drop!</h3>
							<form action='customerfiles-upload/{{ $customer->id }}' method='POST' enctype="multipart/form-data" class='dropzone'>
							</form>
						</div>
						<br clear='both'>
					</div>
					<div class='files_list'>
						<table class="data highlight">
							<thead>
								<tr>
									<th style='width: 35%;'>File name</th>
									<th style='width: 65%;'>File Description</th>
									<th style='width: 140px;'>File Type</th>
									<th style='width: 100px;'>Size</th>
									<th style='width: 140px;'>Uploaded on</th>
									<th style='width: 60px;'>Delete</th>
								</tr>
								@foreach($customer->getFiles as $file)
									<tr file-id="<?= $file->id; ?>">
										<td><a href='customerfiles-download/{{ $customer->id }}/{{ $file->hash }}'>{{ $file->filename }}</a></td>
										<td>{{ $file->description }} <button class="btn btn-orange pull-right" onclick="editFileDescription(this)"><i class="fa fa-edit"></i></button></td>
										<td>{{ $file->filetype }}</td>
										<td>{{ CommonFunctions::formatBytes($file->size) }}</td>
										<td>{{ CommonFunctions::formatDatetime($file->addedOn) }}</td>
										<td>
											<button class="btn btn-red" onclick='confirmFileDeletion("{{ $file->hash }}")'><i class="fa fa-remove"></i></button>
										</td>
									</tr>
								@endforeach
							</thead>
						</table>
					</div>
				</div>
				<div data-tab='vatconfirms'>
					<input type="text" id='vat_newConfirm' placeholder='Confirmation message' style='width: 600px'>
					<button class='btn btn-green' onclick='saveNewVATConfirmation()'>
						<i class="fa fa-save"></i> Save confirmation
					</button>
					<table class="data" id='contacthistory_table'>
						<tr>
							<th style='width: 180px;'>Date/Time</th>
							<th style='width: 200px;'>User</th>
							<th style='width: 100%'>Message</th>
						</tr>
						@foreach($customer->vatConfirms as $confirm)
						<tr>
							<td>{{ CommonFunctions::formatDatetime($confirm->created_at) }}</td>
							<td>{{ $confirm->getUser->getFullname() }}</td>
							<td>{{ nl2br($confirm->text) }}</td>
						</tr>
						@endforeach
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

@stop