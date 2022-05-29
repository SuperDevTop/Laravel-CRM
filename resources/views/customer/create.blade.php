@extends('master')
@section('page_name')
Create new customer
@stop

<?php 
	use App\Models\variables\CustomerCreditRating;
	use App\Models\variables\AdType;
	use App\Models\variables\CustomerType;
	use App\Models\variables\Sector;
	use App\Models\variables\PaymentTerm;
	use App\Models\Currency;
	use App\Models\User;

?>

@section('breadcrumbs')
<a href='/customers'>Customers</a> <i class="breadcrumb"></i> Create Customer
@stop

@section('stylesheets')
<style>
	div[class*='step'] {
		display: none;
	}

	div[class*='step'].active {
		display: block !important;
	}
</style>
@stop

@section('scripts')
<script type='text/javascript' src='//maps.google.com/maps/api/js?sensor=true'></script>
<script type='text/javascript' src='js/gmaps.js'></script>
<script type='text/javascript' src='js/iban.js'></script>
<script>
	/*var lat = 36.5445126;
	var lng = -4.6247328;
		// MAP STUFF
		var locationMap;
		$(function() {
			locationMap = new GMaps({
				div: '#locationMap',
				lat: 0,
				lng: 0,
				width: '100%',
				height: '200px',
				zoom: 15,
				disableDefaultUI: true,
				zoomControl: true,
				streetViewControl: true
			});

			$('body').on('keyup', function(e) {
				var code = e.keyCode || e.which;
				if (code != 27)
					return;

				$('#locationMap').css('position', 'relative');
				$('#locationMap').css('height', '160px');
				$('#locationMap').css('z-index', '1');
				setTimeout(function() {
					locationMap.refresh();
					locationMap.setCenter($('#locationLat').val(), $('#locationLng').val());
				}, 100);
			});
		});

		function showFullscreenMap() {
			$('#locationMap').css('position', 'fixed');
			$('#locationMap').css('left', '0');
			$('#locationMap').css('top', '0');
			$('#locationMap').css('height', '100%');
			$('#locationMap').css('z-index', '100');
			showAlert('Note', 'Press escape when you are finished selecting the location.');
			setTimeout(function() {
				locationMap.refresh();
				locationMap.setCenter($('#locationLat').val(), $('#locationLng').val());
			}, 10);
		}

		function resolveAddress() {
			GMaps.geocode({
				address: $('textarea[name="address"]').val() + ' ' + $('input[name="region"]').val() + ' ' + $('input[name="postalCode"]').val() + ' ' + $('input[name="country"]').val(),
				callback: function(results, status) {
					if (status == 'OK') {
						// Get the location
						var location = results[0].geometry.location;

						// Remove the markers
						locationMap.removeMarkers();

						// Set the value of the 2 hidden input fields
						$('#locationLat').val(location.lat());
						$('#locationLng').val(location.lng());

						// Add the new marker with the dragend events
						locationMap.addMarker({
							lat: location.lat(),
							lng: location.lng(),
							draggable: true,
							dragend: function(e) {
								var location = e.latLng;
								$('#locationLat').val(location.lat());
								$('#locationLng').val(location.lng());
							}
						});

						locationMap.setCenter(location.lat(), location.lng());

						showAlert("Success!", "We've been able to find a location for the entered address. Please note that the location found can be incorrect / inaccurate.");
					} else {
						showAlert("Sorry!", "Sadly Google wasn't able to find the address on the map. Please double-check the address, city, region, postcode and country.");
					}
				}
			});
}

		// Try to find the location of the customer. First on the address + postcode + region + country, then on postcode + region + country, then on country
		function enableMapLocation() {
			GMaps.geocode({
				address: $('textarea[name="address"]').val() + ' ' + $('input[name="postalCode"]').val() + ' ' + $('input[name="region"]').val() + ' ' + $('input[name="country"]').val(),
				callback: function(results, status) {
					if (status == 'OK') {
						enableMapLocationSuccess(results[0].geometry.location);
					} else {
						GMaps.geocode({
							address: $('input[name="postalCode"]').val() + ' ' + $('input[name="region"]').val() + ' ' + $('input[name="country"]').val(),
							callback: function(results, status) {
								if (status == 'OK') {
									enableMapLocationSuccess(results[0].geometry.location);
								} else {
									GMaps.geocode({
										address: $('input[name="region"]').val() + ' ' + $('input[name="country"]').val(),
										callback: function(results, status) {
											if (status == 'OK') {
												enableMapLocationSuccess(results[0].geometry.location);
											} else {
												showAlert('Whoops!', 'Google Maps was not able to find the location you entered on the map. Please double-check the address, city, region, postcode and country.');
											}
										}
									});
								}
							}
						});
					}
				}
			});
}

		// Enable location was a success
		function enableMapLocationSuccess(location) {

			// Remove the markers
			locationMap.removeMarkers();

			// Set the value of the 2 hidden input fields
			$('#locationLat').val(location.lat());
			$('#locationLng').val(location.lng());

			// Add the new marker with the dragend events
			locationMap.addMarker({
				lat: location.lat(),
				lng: location.lng(),
				draggable: true,
				dragend: function(e) {
					var location2 = e.latLng;
					$('#locationLat').val(location2.lat());
					$('#locationLng').val(location2.lng());
				}
			});

			// Set the center of the map
			locationMap.refresh();
			setTimeout(function() {
				locationMap.setCenter(location.lat(), location.lng());
			}, 1);

			showAlert("Success!", "We've been able to find a location for the entered address. Please note that the location found can be incorrect.");

			$('#location_information').removeClass('hidden');
			$('#location_disabled').addClass('hidden');
			locationMap.refresh();
		}

		

		function validateForm() {
			var iban = $('input[name="iban"]').val();

			if (iban != '') {
				if (!IBAN.isValid(iban)) {
					showError("Entered IBAN is invalid. Please make sure you don't have dashes (-) or whitespaces in the IBAN.<br><br>Example: <b>ES2821030194680030035903</b>" , 8);
					return false;
				}
			}

			return true;
		}*/

		$(function() {
			$('button.next-step').on('click', function(e) {
				e.preventDefault();
				var $button = $(this);

				if ($button.closest('.step').hasClass('step1')) {
					// Customer name can't be empty!
					if ($('[name="companyName"]').val() == '') {
						showError("Please enter a customer name!");
						return;
					}
					// Check if phone number, mobile number or email address already exists in the database
					ajaxRequest(
						'create_customer_exists_check',
						{
							phone: $('[name="phone"]').first().val(),
							mobile: $('[name="mobile"]').first().val(),
							email: $('[name="email"]').first().val()
						},
						function(data) {
							if (data.exists == true) {
								if (data.details.length == 1) {
									var customerDetails = data.details[0].companyName;
									if (customerDetails == '')
										customerDetails = data.details[0].contactName;
									customerDetails = '<a href="/customers/' + data.details[0].id + '">' + customerDetails + '</a>';

									confirmDialog(
										"Customer already exists!?",
										"We've found a customer in the database with these details: <br><br>" + customerDetails + "<br><br>Are you sure you want to continue creating this customer?",
										function() {
											$button.closest('.step').attr('style', 'display: none !important');
											$button.closest('.step').next('[class*="step"]').show();
										}
									);
								 } else { 
								 	var customerDetails = '';
								 	$.each(data.details, function(index, customer) {
								 		var currentDetails = customer.companyName;
								 		if (currentDetails == '')
											currentDetails = customer.contactName;
										customerDetails += '<a href="/customers/' + customer.id + '">' + currentDetails + '</a><br>';
								 	});
									
									confirmDialog(
										"Customer already exists!?",
										"We've found multiple customers in the database with these details: <br><br>" + customerDetails + "<br>Are you sure you want to continue creating this customer?",
										function() {
											$button.closest('.step').attr('style', 'display: none !important');
											$button.closest('.step').next('[class*="step"]').show();
										}
									);
								}
							} else {
								$button.closest('.step').attr('style', 'display: none !important');
								$button.closest('.step').next('[class*="step"]').show();
							}
						}
					);
				} else {
					$button.closest('.step').attr('style', 'display: none !important');
					$button.closest('.step').next('[class*="step"]').show();
				}
			});
			$('button.prev-step').on('click', function(e) {
				e.preventDefault();
				$(this).closest('.step').attr('style', 'display: none !important');
				$(this).closest('.step').prev('[class*="step"]').show();
			});
		});
	</script>
	@stop

	@section('content')
	{{ Form::open(array('route' => 'customers.store', 'method' => 'POST', 'onsubmit' => 'return validateForm()')) }}
	<div class="frame">
		<div class="bit-2 step step1 active">
			<div class="container">
				<div class="container_title blue">
					Step 1/4: Contact details
				</div>
				<div class="container_content" style='max-height: none'>
					<table class="form">
						<tr>
							<td style='width: 130px;'>Customer name</td>
							<td style='width: 100%;'>{{ Form::text('companyName') }}</td>
						</tr>
						<tr>
							<td>Tax / Personal ID</td>
							<td>{{ Form::text('cifnif') }}</td>
						</tr>
						<tr>
							<td>Contact title</td>
							<td>{{ Form::text('contactTitle', null, array('placeholder' => 'Mr, Mrs, Dr, Miss...')) }}</td>
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
							<td class='pt'>Phone</td>
							<td class='pt'>{{ Form::text('phone') }}</td>
						</tr>
						<tr>
							<td>Mobile</td>
							<td>{{ Form::text('mobile') }}</td>
						</tr>
						<tr>
							<td>Fax</td>
							<td>{{ Form::text('fax') }}</td>
						</tr>
						<tr class='pt'>
							<td>Email address</td>
							<td>{{ Form::text('email') }}</td>
						</tr>
						<tr>
							<td>Website</td>
							<td>{{ Form::text('website') }}</td>
						</tr>
					</table>

					<br>

					<button class="btn btn-default fr next-step">
						<i class="fa fa-arrow-right"></i>
						Next Step
					</button>
					<br clear='both'>
				</div>
			</div>
		</div>
		<div class="bit-2 step step2">
			<div class="container">
				<div class="container_title blue">
					Step 2/4: General information
				</div>
				<div class="container_content" style='max-height: none'>

					<b>How did the client hear about you?</b>
					{{ Form::select('advertisingType', AdType::pluck('type', 'id'), null, ['style' => 'width: 222px;']) }}
					<p style='font-size: 11px;'>It is important that you ask and add this information, so that you can track your response rate from each advertising source.</p>

					<br>

					<b>Designated Employee</b>
					{{ Form::select('managedBy', User::where('disabled', '=', 0)->get()->pluck('firstname', 'id'), Auth::id(), ['style' => 'width: 296px;']) }}
					<p style='font-size: 11px;'>Which employee represents this customer?</p>

					<br><hr><br>

					<b>Visit fee</b>
					{{ Form::text('assignedVisitFee', '0', array('class' => 'euro', 'style' => 'width: 100px;')) }}
					<p style='font-size: 11px;'>If you need to deliver or visit the client, you can specify a fixed fee so that you can add this fee easily to your quotes.</p>

					<br>

					<b>What type of customer is this?</b>
					{{ Form::select('type', CustomerType::pluck('type', 'id'), null, ['style' => 'width: 250px;']) }}

					<br><br>

					<b>What sector is this customer?</b>
					{{ Form::select('sector', Sector::pluck('type', 'id'), null, ['style' => 'width: 250px;']) }}

					<br><br>

					<b>What is the currency for this customer?</b>
					{{ Form::select('currency', Currency::pluck('name', 'id'), null, ['style' => 'width: 200px;']) }}

					<br><br>

					<b>What are the payment terms for this customer?</b>
					{{ Form::select('paymentTerms', PaymentTerm::pluck('type', 'id'), null, ['style' => 'width: 154px;']) }}


					<br><br><hr><br>


					<b>How would you rate this client?</b>
					{{ Form::select('credit', CustomerCreditRating::pluck('type', 'id'), null, ['style' => 'width: 243px;']) }}
					<p style='font-size: 11px;'>This rating is only for your personal use, so that when quoting client or accepting payments you can sweiftly request payment.</p>

					<br>

					<b>Newsletter</b>
					{{ Form::select('newsletter', array(1 => 'Yes', 0 => 'No')) }}
					<p style='font-size: 11px;'>Under the Anti Spamming protection act, they must accept and acknowledge their wish to receive promotional material from you.</p>

					<br>

					<b>Customer Code</b>
					{{ Form::text('customerCode', null, ['class' => 'w200']) }}
					<p style='font-size: 11px;'>Do you want to add any specific reference number for this client?</p>

					<button class="btn btn-default fr next-step">
						<i class="fa fa-arrow-right"></i>
						Next Step
					</button>
					<button class="btn btn-grey fr prev-step mr10">
						<i class="fa fa-arrow-left"></i>
						Previous Step
					</button>
					<br clear='both'>
				</div>
			</div>
		</div>
		<div class="bit-2 step step3">
			<div class="container">
				<div class="container_title blue">
					Step 3/4: Address
				</div>
				<div class="container_content" style='max-height: none'>
					<table class="form">
						<tr>
							<td style='width: 130px;'>Address</td>
							<td style='width: 100%;'>{{ Form::textarea('address') }}</td>
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

					<br>

					<button class="btn btn-default fr next-step">
						<i class="fa fa-arrow-right"></i>
						Next Step
					</button>
					<button class="btn btn-grey fr prev-step mr10">
						<i class="fa fa-arrow-left"></i>
						Previous Step
					</button>
					<br clear='both'>
				</div>
			</div>
		</div>

		<div class="bit-2 step step4">
			<div class="container">
				<div class="container_title blue">
					Step 4/4: Bank details <span style='color: #9F9F9F'>(For Direct Debiting)</span>
				</div>
				<div class="container_content">
					<table class="form">
						<tr>
							<td style='width: 150px;'>Account holder</td>
							<td style='width: 100%;'>{{ Form::text('accountHolder') }}</td>
						</tr>
						<tr>
							<td>Bank name</td>
							<td>{{ Form::text('bankName') }}</td>
						</tr>
						<tr>
							<td>Swiftcode</td>
							<td>{{ Form::text('swiftcode') }}</td>
						</tr>
						<tr>
							<td>IBAN</td>
							<td>{{ Form::text('iban') }}</td>
						</tr>
						<tr>
							<td>SEPA Mandate ID</td>
							<td>{{ Form::text('sepa_mandateId') }}</td>
						</tr>
						<tr>
							<td>SEPA Mandate Date</td>
							<td>{{ Form::text('sepa_mandateDate', null, array('class' => 'date basinput')) }}</td>
						</tr>
					</table>

					<br>

					{{ Form::button('<i class="fa fa-save"></i> Create customer', array('class' => 'btn btn-green fr', 'type' => 'submit'))}}
					<button class="btn btn-grey fr prev-step mr10">
						<i class="fa fa-arrow-left"></i>
						Previous Step
					</button>
					<br clear='both'>
				</div>
			</div>
		</div>
	</div>
	{{ Form::close() }}
	@stop