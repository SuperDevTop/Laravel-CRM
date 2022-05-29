<!DOCTYPE html>
<html>
<head>
	<title>Electronbox Portal - Welcome</title>
	<meta name="viewport" content="width=device-width, initial-scale=0.5">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/tablet/normalize.css">
	<link rel="stylesheet" href="css/tablet/welcome.css">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
</head>
<body>
	<h1>Welcome</h1>
	<h2>At Electronbox & PC Doctor S.L.</h2>
	<br><br><br>
	<center><img src='logo.png' height='150'></center>
	<h3>Please select an option:</h3>
	<div id='buttons_container'>
		<button onClick='location.assign("tablet/create_customer");'>
			<i class="fa fa-plus"></i>
			<br>
			New customer
		</button>
		{{-- <button onClick='location.assign("newsletter/signup");'>
			<i class="fa fa-envelope-o"></i>
			<br>
			Sign up to newsletter
		</button> --}}
	</div>
</body>
</html>