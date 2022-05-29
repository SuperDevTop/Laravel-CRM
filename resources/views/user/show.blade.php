@extends('master')
@section('page_name')
	System '{{ $user->getFullname() }}'
@stop

@section('breadcrumbs')
	System <i class='breadcrumb'></i>  Manage Users  <i class="breadcrumb"></i>  {{ $user->getFullname() }}
@stop

<?php
	use App\Models\variables\CompanyRole;
	use App\Models\variables\JobStatus;
 ?>

@section('scripts')
 {{-- {{ HTML::script('js/sha512.js') }}  --}}
	<script type='text/javascript'>
		$(function() {
			$('#userForm').on('submit', function(e) {
				if ($('[name=newpassword]').val() != '') {
					$('[name=hashedPassword]').val(hex_sha512($('[name=newpassword]').val()));
					$('[name=newpassword]').val('');
					$('[name=newpassword_confirm]').val('');
				}
			});
		});
	</script>
@stop

@section('content')
	<div class="frame">
		{{ Form::model($user, array('files' => 'true', 'route' => array('users.update', $user->id), 'method' => 'PUT', 'id' => 'userForm', 'files' => true)) }}
			<div class="bit-3">
				<div class="container">
					<div class="container_title blue	">
						User information	
					</div>
					<div class="container_content">
						<table style='width: 100%;'>
							<tr>
								<td>
									<img src='users/{{ $user->id }}/photo'/>
								</td>
								<td style='padding-left: 20px; vertical-align: top; padding-top: 20px;'>
									<h1>{{ $user->getFullname() }}</h1>
									@if($user->getCompanyRole != null)
										<span>{{ $user->getCompanyRole->type }}</span>
									@endif
								</td>
							</tr>
						</table>
						<br>
						<hr>
						<br>
						<table class='form tlf'>
							<tr>
								<td style='width: 160px;'>New photo</td>
								<td style='width: 100%;'>{{ Form::file('photo') }}</td>
							</tr>
							<tr>
								<td>First name</td>
								<td>{{ Form::text('firstname') }}</td>
							</tr>
							<tr>
								<td>Last name</td>
								<td>{{ Form::text('lastname') }}</td>
							</tr>
							<tr>
								<td class='pt'>Username</td>
								<td class='pt'>{{ Form::text('username') }}</td>
							</tr>
							<tr>
								<td>3 letter initials</td>
								<td>{{ Form::text('initials') }}</td>
							</tr>

							<tr>
								<td class='pt'>New password</td>
								<td class='pt'>{{ Form::password('newpassword', array('placeholder' => 'New password')) }}</td>
							</tr>
							<tr>
								<td>Repeat password</td>
								<td>
									{{ Form::password('newpassword_confirm', array('placeholder' => 'New password (confirm)')) }}
									{{ Form::hidden('hashedPassword') }}
								</td>
							</tr>
							@if (Auth::user()->hasPermission('edit_user_group'))
								<tr>
									<td class='pt' valign='top'>User Group</td>
									<td class="pt">
										{{ Form::select('userGroup', UserGroup::all()->pluck('name', 'id')) }}
									</td>
								</tr>
								<tr>
									<td class='pt' valign='top'>User disabled</td>
									<td class="pt">
										{{ Form::hidden('disabled', 0) }}
										{{ Form::checkbox('disabled', 1) }}
									</td>
								</tr>
							@endif
						</table>
					</div>
				</div>
			</div>		
			<div class="bit-3">
				<div class="container">
					<div class="container_title blue">
						Company information
					</div>
					<div class="container_content">
						<table class='form tlf'>
							<tr>
								<td style='width: 130px;'>Email</td>
								<td style='width: 100%;'>{{ Form::text('companyEmail') }}</td>
							</tr>
							<tr>
								<td>Mobile</td>
								<td>{{ Form::text('companyMobile') }}</td>
							</tr>
							<tr>
								<td>Extension</td>
								<td>{{ Form::text('extension') }}</td>
							</tr>

							<tr>
								<td class='pt'>Hire date</td>
								<td class='pt'>{{ Form::text('hireDate', date('d-m-Y', strtotime($user->hireDate)), array('class' => 'dp', 'readonly' => 'readonly')) }}</td>
							</tr>

							<tr>
								<td class='pt'>Company Role</td>
								<td class='pt'>{{ Form::select('companyRole', CompanyRole::pluck('type', 'id')) }}</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="container mt">
					<div class="container_title blue">
						Job Statusses
					</div>
					<div class="container_content">
						<p>Here you can select the job statusses that this user will see in 'My Jobs'.</p>
						@foreach(JobStatus::all() as $status)
							{{ Form::checkbox('myJobStatusses[' . $status->id . ']', '1', in_array($status->id, explode(',', $user->myJobStatusses))) }} {{ $status->type }}<br>
						@endforeach
					</div>
				</div>
			</div>

			<div class="bit-3">
				<div class="container">
					<div class="container_title blue">
						Personal information
					</div>
					<div class="container_content">
						<table class='form tlf'>
							<tr>
								<td style='width: 110px;'>Address</td>
								<td style='width: 100%;'>{{ Form::text('address') }}</td>
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
								<td>Post Code</td>
								<td>{{ Form::text('postcode') }}</td>
							</tr>
							<tr>
								<td class='pt'>Email</td>
								<td class='pt'>{{ Form::text('personalEmail') }}</td>
							</tr>
							<tr>
								<td>Phone</td>
								<td>{{ Form::text('homePhone') }}</td>
							</tr>
							<tr>
								<td>Mobile</td>
								<td>{{ Form::text('homeMobile') }}</td>
							</tr>
							<tr>
								<td class='pt'>Date of Birth</td>
								<td class='pt'>{{ Form::text('dob', date('d-m-Y', strtotime($user->dob)), array('class' => 'dp')) }}</td>
							</tr>
							<tr>
								<td>DNI</td>
								<td>{{ Form::text('dni') }}</td>
							</tr>
							<tr>
								<td>Notes</td>
								<td>{{ Form::textarea('notes', null, array('style' => 'height: 90px; width: 100%;')) }}</td>
							</tr>
							<tr>
								<td></td>
								<td>{{ Form::submit('Save', array('class' => 'btn btn-green fr')) }}</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		{{ Form::close() }}
	</div>
@stop