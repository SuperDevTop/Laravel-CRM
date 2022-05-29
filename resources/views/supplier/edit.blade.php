@extends('master')
@section('page_name')
	Edit supplier
@stop

@section('page_header')
	<button class="btn btn-orange fr" data-href='suppliers/{{ $supplier->id}}'>
		<i class="fa fa-sitemap"></i> Go to supplier overview
	</button>
@stop

@section('content')
	{{ Form::model($supplier, array('route' => array('suppliers.update', $supplier->id), 'method' => 'PUT')) }}
	<div class="frame">
		<div class="bit-3">
			<div class="container">
				<div class="container_title blue">
					Company information
				</div>
				<div class="container_content">
						<table class="form">
							<tr>
								<td style='width: 130px;'>Supplier code</td>
								<td style='width: 100%;'>{{ Form::text('supplierCode') }}</td>
							</tr>

							<tr>
								<td>Company name</td>
								<td>{{ Form::text('companyName') }}</td>
							</tr>
							<tr>
								<td>Trading name</td>
								<td>{{ Form::text('tradingName') }}</td>
							</tr>
							<tr>
								<td>Tax ID</td>
								<td>{{ Form::text('cifnif') }}</td>
							</tr>

							<tr class='pt'>
								<td>Address</td>
								<td>{{ Form::textarea('address') }}</td>
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
							<tr class='pt'>
								<td>Services</td>
								<td>{{ Form::textarea('services') }}</td>
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
								<td style='width: 110px;'>Contact title</td>
								<td style='width: 100%;'>{{ Form::text('contactTitle') }}</td>
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
								<td>{{ Form::text('swiftcode') }}</td>
							</tr>
							<tr>
								<td>IBAN</td>
								<td>{{ Form::text('iban') }}</td>
							</tr>
							<tr>
								<td>Account</td>
								<td>{{ Form::text('account') }}</td>
							</tr>
						</table>
					{{ Form::button('<i class="fa fa-save"></i> Save supplier', array('class' => 'btn btn-green fr', 'type' => 'submit'))}}
					<br clear='all'>
				</div>
			</div>
		</div>
	</div>
	{{ Form::close() }}
@stop