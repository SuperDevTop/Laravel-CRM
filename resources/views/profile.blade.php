@section('page_name')
	Your profile
@stop

@section('scripts')
	{{ HTML::script('js/cropper.js') }}
	{{ HTML::script('js/sha512.js') }}
	<script>
		function hashPasswords() {
			if ($('#newPassword1').val() != '') {
				if ($('#newPassword1').val() != $('#newPassword2').val()) {
					showError('Please make sure your new password and new password (confirm) match!');
					return false;
				}

				$('#newHashedPassword').val(hex_sha512($('#newPassword1').val()));
				$('#newPassword1').val('');
				$('#newPassword2').val('');
			}

			if ($('#avatar').get(0).files.length == 1) {
				var data = $('#avatar_preview').cropper('getCroppedCanvas').toDataURL();
				$('#new_avatar_data').val(data);
			}


			return true;
		}

		$(function() {
			$('#avatar').on('change', function(Event) {
				var input = this;
				if (input.files && input.files[0]) {
			        var reader = new FileReader();
			        reader.onload = function (e) {
			        	$('#new_avatar_container').show();
			            $('#avatar_preview').attr('src', e.target.result);

			            $('#current_avatar').hide();
			            $('#avatar').hide();
			            $('#new_avatar_title').show();

			            $('#avatar_preview').cropper({
			            	aspectRatio: 1
			            });
			        }
			        reader.readAsDataURL(input.files[0]);
			    }
			});

			@if (Auth::user()->companyEmail == '')
				showAlert('Whoops!', 'Please enter your email address in your profile. This is needed for Pepper to work correctly.');
			@endif
		});
	</script>
@stop

@section('stylesheets')
	{{ HTML::style('css/cropper.css') }}
@stop

@section('content')
	<div class="frame">
		<div class="bit-2">
			<div class="container">
				<div class="container_title blue">
					Edit your profile
				</div>
				<div class="container_content">
					<form action='' method='POST' enctype='multipart/form-data' onsubmit='return hashPasswords();'>
						<table class="form">
							@if (Auth::user()->hasPermission('user_edit_avatar'))
							<tr id='current_avatar'>
								<td style='width: 190px;'>Current profile photo:</td>
								<td style='width: 100%;'>
									<img src='users/{{ Auth::user()->id }}/photo?time={{ md5(Auth::user()->updated_at) }}'>
								</td>
							</tr>
							<tr>
								<td>Upload new photo:</td>
								<td>
									<input type='file' name='avatar' id='avatar'>

									<div class='hidden' id='new_avatar_container'>
										<h2 class='hide' id='new_avatar_title'>Please select the area of the photo you want to save:</h2>
										<img id='avatar_preview' id='avatar_preview'>
										<button type='button' class="btn btn-default" onClick='$("#avatar_preview").cropper("zoom", "0.1");'><i class="fa fa-search-plus"></i></button>
										<button type='button' class="btn btn-default" onClick='$("#avatar_preview").cropper("zoom", "-0.1");'><i class="fa fa-search-minus"></i></button>
										<button type='button' class="btn btn-default ml20" onClick='$("#avatar_preview").cropper("rotate", "-90");'><i class="fa fa-undo"></i></button>
										<button type='button' class="btn btn-default" onClick='$("#avatar_preview").cropper("rotate", "90");'><i class="fa fa-repeat"></i></button>
										<br>
									</div>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td></td>
							</tr>
							@endif
							@if(Auth::user()->hasPermission('user_change_password'))
							<tr>
								<td>New password:</td>
								<td><input type='password' name='newPassword1' id='newPassword1'></td>
							</tr>
							<tr>
								<td>New password (confirm):</td>
								<td><input type='password' name='newPassword2' id='newPassword2'></td>
							</tr>
							@endif
							<tr>
								<td>&nbsp;</td>
								<td></td>
							</tr>
							<tr>
								<td>Company Email</td>
								<td><input type='text' name='companyEmail' value='{{ Auth::user()->companyEmail }}'></td>
							</tr>
							<tr>
								<td></td>
								<td>
									<input type='hidden' name='newHashedPassword' id='newHashedPassword' value=''>
									<input type='hidden' name='new_avatar_data' id='new_avatar_data'>
									<button type='submit' class="btn btn-green fr" onClick=''>
										<i class="fa fa-save"></i> Save profile
									</button>
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</div>
	</div>
@stop