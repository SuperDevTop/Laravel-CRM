@extends('master')
@section('page_name')
	Edit '{{ $customer->companyName }}'
@stop
<?php 
	use App\Models\variables\CustomerCreditRating;
?>
@section('breadcrumbs')
	<a href='/customers'>Customers</a> <i class="breadcrumb"></i> <a href='/customers/{{ $customer->id }}'>{{ $customer->getCustomerName() }}</a> <i class="breadcrumb"></i> Edit
@stop

@section('page_header')
	<button class="btn btn-orange fr" data-href='customers/{{ $customer->id }}'>
		<i class="fa fa-sitemap"></i>
		Go to customer overview
	</button>
@stop

@section('scripts')
	<script type='text/javascript' src='//maps.google.com/maps/api/js?sensor=true'></script>
	<script type='text/javascript' src='js/gmaps.js'></script>
	<script type='text/javascript' src='js/iban.js'></script>

	<script>
		// MAP STUFF
		var locationMap;
		$(function() {
			locationMap = new GMaps({
				div: '#locationMap',
				lat: {{ ($customer->locationLat) ? $customer->locationLat : 0 }},
				lng: {{ ($customer->locationLng) ? $customer->locationLng : 0 }},
				width: '100%',
				height: '200px',
				zoom: 15,
                disableDefaultUI: true,
                zoomControl: true,
                streetViewControl: true
			});
			locationMap.addMarker({
				lat: {{ ($customer->locationLat) ? $customer->locationLat : 0 }},
				lng: {{ ($customer->locationLng) ? $customer->locationLng : 0 }},
				draggable: true,
				dragend: function(e) {
					var location = e.latLng;
					$('#locationLat').val(location.G);
					$('#locationLng').val(location.K);
				}
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

						// Set the center of the map
						locationMap.setCenter(location.lat(), location.lng());

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
								$('#locationLat').val(location.G);
								$('#locationLng').val(location.K);
							}
						});
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
			// Set the center of the map
			locationMap.setCenter(location.lat(), location.lng());

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
					$('#locationLat').val(location2.G);
					$('#locationLng').val(location2.K);
				}
			});
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
		}
	</script>
@stop

@section('content')
	{{ Form::model($customer, array('route' => array('customers.update', $customer->id), 'method' => 'PUT', 'onsubmit' => 'return validateForm()')) }}
	<div class="frame">
		<div class="bit-1">
			<div class="container">
				<div class="container_content">
					<div><span>Created on</span> {{ $customer->created_at }}
					<span>by</span> {{ $customer->getCreator->getFullname() }}</div>
					<div><span>Credit rating</span>&nbsp;&nbsp;{{ Form::select('credit', CustomerCreditRating::pluck('type', 'id')) }}</div>
					<div><span>Visit fee</span>&nbsp;&nbsp;{{ Form::text('assignedVisitFee', null, array('class' => 'euro', 'style' => 'width: 100px;')) }}</div>
					<div><span>Newsletter</span>&nbsp;&nbsp;{{ Form::select('newsletter', array(1 => 'Yes', 0 => 'No')) }}</div>
					<div><span>Ad type</span>&nbsp;&nbsp;{{ Form::select('advertisingType', AdType::pluck('type', 'id')) }}</div>
					<div><span>Read-only</span>&nbsp;&nbsp;{{ Form::select('readonly', array(1 => 'Yes', 0 => 'No')) }}</div>
					<div style='float: right; padding-top: 5px;'>
						{{ Form::button('<i class="fa fa-save"></i> Save customer', array('class' => 'btn btn-green fr', 'type' => 'submit'))}}
					</div>
					<br clear='all'>
				</div>
			</div>
		</div>
	</div>
	<div class="frame">
		<div class="bit-3">
			<div class="container">
				<div class="container_title blue">
					Company information
				</div>
				<div class="container_content" style='max-height: none'>
						<table class="form">
							<tr>
								<td style='width: 130px;'>Customer code</td>
								<td style='width: 100%'>{{ Form::text('customerCode') }}</td>
							</tr>

							<tr>
								<td>Company name</td>
								<td>{{ Form::text('companyName') }}</td>
							</tr>

							<tr>
								<td>Tax Id</td>
								<td>{{ Form::text('cifnif') }}</td>
							</tr>
							
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
							<tr>
								<td>Map location</td>
								<td>
									{{ Form::hidden('locationLat', null, array('id' => 'locationLat')) }}
									{{ Form::hidden('locationLng', null, array('id' => 'locationLng')) }}
									<div id='location_information' {{ (!$customer->hasCoordinates()) ? 'class="hidden"' : '' }}>
										<div id='locationMap' style='width: 100%; height: 160px;'></div>
										<br>
										<button type='button' class='btn btn-default fr' onclick='showFullscreenMap()' data-tooltip='This will make the map full-screen for easy navigating.'><i class="fa fa-expand"></i> Larger map</button>
										<button type='button' class="btn btn-default fr" style='margin-right: 5px;' onclick='resolveAddress()' data-tooltip='This button will try to find the address you entered on the map.'><i class="fa fa-search"></i> Find address</button>
									</div>
									<div id='location_disabled' {{ ($customer->hasCoordinates()) ? 'class="hidden"' : '' }}>
										<button type='button' class="btn btn-green" onclick='enableMapLocation()'><i class="fa fa-check"></i> Enable map location</button>
									</div>
								</td>
							</tr>
						</table>
				</div>
			</div>
		</div>

		<div class="bit-3">
			<div class="container">
				<div class="container_title blue">
					Contact details
				</div>
				<div class="container_content">
						<table class="form">
							<tr>
								<td style='width: 120px;'>Contact title</td>
								<td style='width: 100%'>{{ Form::text('contactTitle') }}</td>
							</tr>
							<tr>
								<td>Contact name</td>
								<td>{{ Form::text('contactName') }}</td>
							</tr>
							<tr>
								<td class='pt'>Phone</td>
								<td class='pt'>{{ Form::text('phone') }}</td>
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
							<!-- <tr>
								<td class="pt">Notes</td>
								<td class="pt">{{ Form::textarea('notes', null, array('style' => 'width: 100%; height: 170px;')) }}</td>
							</tr> -->
						</table>
				</div>
			</div>
		</div>
		<div class="bit-3">
			<div class="container">
				<div class="container_title blue">
					Bank details
				</div>
				<div class="container_content">
						<table class="form">
							<tr>
								<td style='width: 120px;'>Account holder</td>
								<td style='width: 100%;'>{{ Form::text('accountHolder') }}</td>
							</tr>
							<tr>
								<td>Bank name</td>
								<td>{{ Form::text('bankName') }}</td>
							</tr>
							<tr>
								<td>Swiftcode</td>
								<td>{{ Form::text('swiftCode') }}</td>
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
					<br clear='all'>
					<b>Bank stuff</b>: {{ $customer->bankId }} {{ $customer->branchId }} {{ $customer->dc }} {{ $customer->accountId }}
					<br>
					<b>Bank stuff</b>: {{ $customer->bankId }}{{ $customer->branchId }}{{ $customer->dc }}{{ $customer->accountId }}
					<br>
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
@stop