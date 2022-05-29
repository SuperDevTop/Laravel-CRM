<html>
	<head>
		<title>Quote #{{ $quote->id }} - Description Sheet</title>
		<base href="{{ Request::root() }}">
		<style>
			body {
				margin: 0;
				padding: 0;

				width: 215mm;
				height: 1140px;

				font-family: 'Serif';
				color: #101010;
				letter-spacing: 0px;
			}

			* {
				font-size: 10pt;
			}

			#pre_header {
				background-color: #f4f4f4;

				width: 100%;
				height: 30px;
			}

			#pre_header .mark {
				width: 45px;
				height: 30px;

				background-color: {{ $templateColor }};

				margin-left: 40px;
			}

			#wrapper {
				margin-left: 40px;
				margin-right: 30px;
			}

			#header {
				height: 100px;
			}

			#header .logo {
				margin-top: 20px;
				float: left;
				max-width: 400px;
				max-height: 80px;
			}

			#header .text {
				float: right;
				width: 350px;
				height: 100px;

				text-align: right;
			}

			#header .text h1 {
				margin-top: 30px;
				margin-bottom: 0;
				font-size: 16pt;
			}

			#header .text h3 {
				margin-top: 5px;
				font-size: 10pt;
			}

			hr {
				border: 2px solid #f0f0f0;
				margin: 0;
			}

			hr.blue {
				border: 2px solid {{ $templateColor }};
				margin: 0;
			}

			#content {

			}

			table.styled {
				table-layout: fixed;

				vertical-align: middle;

				width: 100%;

				margin-top: 3px;
				margin-bottom: 3px;

				border-collapse: collapse;
			}

			table.styled th {
				color: {{ $templateColor }};
				border-bottom: 4px solid {{ $templateColor }};
			}

			table.styled td.first {
				background-color: {{ $templateColor }};
				width: 45px;
				text-align: center;
			}

			th {
				text-align: left;
			}

			.cellpadding {
				padding: 5px 15px;
			}

			.blue {
				color: {{ $templateColor }};
				vertical-align: top;
			}

			.valign_top {
				vertical-align: top;
			}

			.row-image {
				max-width: 100%;
				max-height: 200px;
			}

			h2 {
				font-size: 22px;

				margin: 0;
				margin-left: 15px;

				color: {{ $templateColor }};
			}

			p {
				margin-left: 15px;
			}

			table tr td, table tr th {
		        page-break-inside: avoid;
		    }
		</style>
		<script src='/js/jquery.js'></script>
		<script>
			$(function() {
				$(window).on('load', function() {
					var windowH = $(window).height();
					var offset = $('#verticalSpread').offset().top;
					console.log(offset);
					$('#verticalSpread').css('height', (windowH - offset - 40) + 'px');
				});
			});
		</script>
	</head>
	<body>
		<div id="pre_header">
			<div class="mark"></div>
		</div>
		<div id="wrapper">
			<div id="header">
				<img src="logo.png" alt="Logo" class="logo">
				<div class="text">
					<h1>
						Quote #{{ $quote->id }} - Description Sheet
					</h1>
				</div>
			</div>
			<div id="content">
				<hr>
				
				@foreach($rows as $row)
				<table class='styled'>
					<tr>
						<td style='width: 45px;' class='first' rowspan='3'></td>
						<td colspan='2'><br><h2>{{ $row->title }}</h2></td>
					</tr>
					<tr>
						<td style='width: 50%;' class='cellpadding'>
							@if ($row->image1 != '' && $row->image1 != 0)
								<center><img class='row-image' src='{{ MediaItem::find($row->image1)->getExternalUrl() }}'></center>
							@endif
						</td>
						<td style='width: 50%;'>
							@if ($row->image2 != '' && $row->image2 != 0)
								<center><img class='row-image' src='{{ MediaItem::find($row->image2)->getExternalUrl() }}'></center>
							@endif
						</td>
					</tr>
					<tr>
						<td colspan='2'>
							<p>
								{{ nl2br($row->description) }}
							</p>
							<br>
						</td>
					</tr>
				</table>
				<hr>
				@endforeach
				<table class='styled'>
					<tr>
						<td style='width: 45px;' class='first' id='verticalSpread'></td>
						<td style='width: 100%;'></td>
					</tr>
				</table>
			</div>
		</div>
	</body>
</html>