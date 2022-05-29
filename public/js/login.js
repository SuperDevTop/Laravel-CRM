$(function() {
	// Focus the username field
	$("#usernameTb").focus();

	// Bind the login button to out login action
	$("#loginBtn").on("click", attemptLogin);

	// Bind the keydown for the return key to the login action
	$(window).on('keydown', function(event) {
		if (event.which == 13)
			attemptLogin();
	})
});

function attemptLogin() {
	var $loginBtn = $('#loginBtn');

	if ($loginBtn.is(':disabled'))
		return;

	var $loginMessage = $('#loginMsg');

	$loginBtn.attr('disabled', 'disabled');
	$loginBtn.css('background-color', '#F1BE0E');
	$loginBtn.html("<i class='fa fa-circle-o-notch fa-spin'></i> Logging in....");
	$loginMessage.slideUp();

	var hashedPassword = hex_sha512($('#passwordTb').val());
	$('#passwordTb').val('');

	$.ajax(
	    {
		url: 'login',
		type: 'POST',
		dataType: 'json',
		data: {
			username: $('#usernameTb').val(),
			password: hashedPassword,
			rememberme: $('#rememberme').is(':checked')
		},
		success: function(data) {
			if (data.valid) {

				$loginBtn.css('background-color', '#56DB4F');
				$loginBtn.html("<i class='fa fa-circle-o-notch fa-spin'></i> Redirecting...");
				location.assign(data.url);
			} else {
				$loginBtn.css('background-color', '#FF3535');
				$loginBtn.html('<i class="fa fa-remove"></i> Login failed');
				$loginMessage.html('<br>' + data.message);
				$loginMessage.slideDown();
				$('#passwordTb').focus();
				setTimeout(function() {
					$loginBtn.removeAttr('disabled');
					$loginBtn.css('background-color', '#2EBEFC');
					$loginBtn.html('<i class="fa fa-sign-in"></i> Log in');
				}, 3000);
			}
		}
	});
}