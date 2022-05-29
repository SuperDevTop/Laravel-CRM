
<?php 
	use App\Models\Settings;
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>{{ Settings::setting('app_name')}} - Login</title>
	<link href='//fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/login.css">
	<script src="js/sha512.js"></script>
	<script src="js/jquery.js"></script>
	<script src="js/login.js"></script>
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
</head>
<body>
	<div id="loginStatus">
		Test
	</div>
	<div id="container">
		<img src="/img/default_logo.png" id="logo" alt="Formazing">
		<p>Log in to your account</p>
		<input type="text" name="username" id="usernameTb" placeholder="Your username...">
		<br>
		<input type="password" name="password" id="passwordTb" placeholder="Your password...">
		<br>
		<input type="checkbox" id='rememberme'> Remember me
		<br>
		<button type="button" id="loginBtn"><i class="fa fa-sign-in"></i> Log in</button>
		<span id="loginMsg"></span>

		<br><br><hr><br>

		<span id="forgotPwdTitle">Pepper CRM</span>
		<p id="forgotPwdText">
			This CRM is proudly powered by Pepper CRM. Want your own CRM? Visit <a href='pepper-crm.com'>www.pepper-crm.com</a>!
		</p>
		<a href='http://www.urbytus.es'><img src="img/pepper_logo.png" id="logo" alt="Powered by Urbytus" style='width: 150px; margin-top: 5px; margin-left: 45px;'></a>
	</div>
</body>
</html>