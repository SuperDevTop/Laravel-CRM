<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Report</title>
	<link rel="stylesheet" href="">
	<style>
		body {
			font-size: 9pt;
		}

		h1 {
			margin-bottom: 0;
		}

		h1>small {
			font-size: 10pt;
			font-weight: normal;
		}

		table {
			table-layout: fixed;
			width: 100%;
		}

		th {
			border-top: 1px solid black;
			border-bottom: 3px solid black;
		}

		.tar {
			text-align: right;
		}
	</style>
</head>
<body>
	<h1>
		@yield('reportTitle')
		<small>@yield('subtitle')</small>
	</h1>
	<hr>
	@yield('table')
</body>
</html>