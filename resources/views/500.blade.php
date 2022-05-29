<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Pepper CRM - 500</title>
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,500' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
	<style>
		body {
			margin: 0;
			font-family: 'Roboto', sans-serif;
			background-image: url('error-page-bg.png');
			background-repeat: repeat;
		}

		* {
			box-sizing: border-box;
		}

		#main-wrapper {
			position: fixed;

			width: 600px;
			height: 250px;
			
			left: 50%;
			top: 50%;

			margin-top: -125px;
			margin-left: -300px;
		}

		#cogs-wrapper {
			float: left;
			width: 150px;
		}

		#cogs-wrapper img {
			height: 125px;
			margin-top: 20px;
		}

		#text-wrapper {
			float: left;

			width: 450px;

			padding-left: 20px;
		}

		#text-wrapper h1 {
			margin: 0;
			font-size: 18px;
			font-weight: medium;
			color: #616161;
		}

		#text-wrapper p {
			margin: 10px 0 0 0;
			font-size: 14px;
			color: #8A8A8A;
		}

		#text-wrapper button {
			margin-top: 20px;
			border: 0;

			padding: 10px 20px;

			transition: background-color 0.1s linear;

			font-size: 12px;
			font-weight: bold;

			border-radius: 3px;

			color: white;

			margin-bottom: 4px;

			background-color: #E07833;

			cursor: pointer;
		}

		#text-wrapper button:focus {
			outline: 0;
		}

		#text-wrapper button:active {
				box-shadow: inset 0px 2px 2px 0px rgba(0,0,0,0.4);
		}

		#text-wrapper button:hover {
				background-color: #F88234;
		}

		#text-wrapper img#logo {
			display: block;
			float: right;

			width: 90px;
			margin-top: 25px;
			margin-left: 15px;
		}

		.clearfix {
			clear: both;
		}

		.caseid {
			display: block;
			color: #838383;
			font-size: 12px;

			margin-top: 20px;
		}
	</style>
</head>
<body>
	<div id="main-wrapper">
		<div id="cogs-wrapper">
			<img src='/img/layout/500-cogs.gif'>
		</div>
		<div id="text-wrapper">
			<h1>We're sorry. Something went terribly wrong.</h1>
			<p>
				Don't panic. Although it seems Pepper has exploded, our highly skilled team or tech-monkeys are already fixing the problem. In the meantime, please try again. If the error persists, please contact us via the orange 'Help & feedback' button at the bottom-left of Pepper.
			</p>
			<a href='/'><button type='button'><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Back to Pepper</button></a>
			<img id='logo' src='/img/pepper_logo.png'>
			<div class="clearfix"></div>
			<span class="caseid">
				If you contact support, please provide us with the following case number: <b>#{{ $case_id }}</b>.
			</span>
		</div>
		<div class="clearfix"></div>
	</div>
</body>
</html>