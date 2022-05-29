@extends('master')
@section('page_name')
	User list
@stop

@section('breadcrumbs')
	System <i class="breadcrumb"></i> Manage Users
@stop

@section('scripts')
	<script>
		function confirmUserDelete(id) {
			confirmDialog('Are you sure?',
				'Are you sure you want to delete this user?<br><br><span style="color: red; font-weight: bold;">Please note that this is a permanent action. The user will permanently be deleted.</span>',
				function() {
					window.location.assign("/users/" + id + "/delete");
				}
			);
		}

		$(function() {
			$('#newUserForm').validate();
		});
	</script>
@stop

@section('content')
	<div class="frame">
		<div class="bit-2">
			<div class="container">
				<div class="container_title blue">
					User list
				</div>
				<div class="container_content nop">
					<table class='data smallrows highlight'>
						<tr>
							<th style="width: 80px;">Initials</th>
							<th style="width: 60%;">Name</th>
							<th style="width: 40%;">Username</th>
							<th style="width: 90px;">Actions</th>
						</tr>
						@foreach($users as $user)
							<tr>
								<td>{{ $user->initials }}</td>
								<td>{{ $user->firstname . ' ' . $user->lastname }}</td>
								<td>{{ $user->username }}</td>
								<td>
									<button data-href='users/{{ $user->id }}' class="btn btn-default btn-square" title="View user">
										<i class="fa fa-eye"></i>
									</button>
									<button onClick='confirmUserDelete({{ $user->id }});' class="btn btn-red btn-square" title="Delete user">
										<i class="fa fa-trash"></i>
									</button>
								</td>
							</tr>
						@endforeach
					</table>

					<br>

					<div class="containerpadding">
						Currently showing <b>{{ (Request::has('disabled')) ? 'Disabled Users' : 'Enabled Users' }}</b>
						@if (Request::has('disabled'))
							<a href='/users'><button class="btn btn-default">Show enabled users</button></a>
						@else
							<a href='/users?disabled=true'><button class="btn btn-default">Show disabled users</button></a>
						@endif
					</div>
					
				</div>
			</div>
		</div>
		<div class="bit-2">
			<div class="container">
				<div class="container_title green">
					New user
				</div>
				<div class="container_content">
					{{ Form::open(array('method' => 'post', 'id' => 'newUserForm', 'novalidate' => 'novalidate')) }}
						<table class="form">
							<tr>
								<td style='width: 110px;'>First name:</td>
								<td style='width: 100%'>{{ Form::text('firstname', '', array('placeholder' => 'First name', 'required' => 'required', 'minlength' => '3')) }}</td>
							</tr>
							<tr>
								<td>Last name:</td>
								<td>{{ Form::text('lastname', '', array('placeholder' => 'Last name', 'required' => 'required', 'minlength' => '3')) }}</td>
							</tr>
							<tr>
								<td>Initials:</td>
								<td>{{ Form::text('initials', '', array('placeholder' => 'Initials', 'required' => 'required', 'minlength' => '1')) }}</td>
							</tr>
							<tr>
								<td class='pt'>Username:</td>
								<td class='pt'>{{ Form::text('username', '', array('placeholder' => 'Username', 'required' => 'required', 'minlength' => '3')) }}</td>
							</tr>
							<tr>
								<td class='pt'>Password:</td>
								<td class='pt'>{{ Form::password('password', array('placeholder' => 'Password', 'required' => 'required', 'minlength' => '6')) }}</td>
							</tr>
							<tr>
								<td>Password:</td>
								<td>{{ Form::password('password_confirm', array('placeholder' => 'Password (confirm)', 'required' => 'required', 'minlength' => '6', 'equalTo' => '[name=password]')) }}</td>
							</tr>
							<tr>
								<td></td>
								<td>{{ Form::button('<i class="fa fa-plus"></i> Create user', array('class' => 'btn btn-green fr', 'type'=> 'submit')) }}</td>
							</tr>
						</table>
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
@stop