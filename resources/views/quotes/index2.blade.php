@extends("master")

@section('page_name')
	Quote list
@stop

@section('breadcrumbs')
	Quotes & invoices <i class="breadcrumb"></i> List quotes
@stop

<?php
	use App\Models\User;
	use App\Models\Settings;
	use App\Models\variables\JobStatus;
?>

@section('scripts')
	<script>
	var userFilter = {{ (\Request::has('user')) ?  Request::get('user') : 'false' }};
	var statusFilter = {{ (\Request::has('status')) ? \Request::get('status') : 'false' }};
	var dateStartFilter = {{ (\Request::get('datestart')) ? "'" .\Request::get('datestart') . "'" : 'false' }};
	var dateEndFilter = {{ (\Request::get('dateend')) ? "'".\Request::get('dateend')."'" : 'false' }};
	var dateEndFilter ;
	var dateStartFilter ;	

		$(function() {
			$('#filter_user').on('change', function() {
				var userId = $('#filter_user option:selected').val();
				userFilter = userId;
				refreshPage();
				
			});

			$('#filter_jobstatus').on('change', function() {
				var statusId = $('#filter_jobstatus option:selected').val();
				statusFilter = statusId;
				refreshPage();
			});

			$('#filter_datestart').on('change', function() {
				dateStartFilter = $(this).val();
				refreshPage();
			});

			$('#filter_dateend').on('change', function() {
				dateEndFilter = $(this).val();
				refreshPage();
			});

			$('[filter-reset]').on('click', function() {
				if ($(this).attr('filter-reset') == 'dateFilter') {
					dateStartFilter = false;
					resetFilter('dateEndFilter');
					return;
				}

				resetFilter($(this).attr('filter-reset'));
			});
		});

		function resetFilter(filter) {
			window[filter] = false;
			refreshPage();
		}

		function refreshPage() {
			var parAdded = false;
			var url = 'quotes?filter=true';

			if (userFilter)
				url += '&user=' + encodeURIComponent(userFilter);

			if (statusFilter)
				url += '&status=' + encodeURIComponent(statusFilter);

			if (dateStartFilter)
				url += '&datestart=' + encodeURIComponent(dateStartFilter);

			if (dateEndFilter)
				url += '&dateend=' + encodeURIComponent(dateEndFilter);

			location.replace(url);
		}
	</script>
@stop


<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<base href="{{ Request::root() }}">
	<title>{{ Settings::setting('app_name') }} - @yield('page_name')</title>
	<link rel="shortcut icon" type="image/png" href="/icon.png"/>
	<link rel="stylesheet" href="/css/normalize.css">
	<link rel="stylesheet" href="/css/layout.css">
	<link rel="stylesheet" href="/css/ui_elements.css">
	<link rel="stylesheet" href="/css/grid.css">
	<link rel="stylesheet" href="/css/select2.css">
	<link rel="stylesheet" href="/css/jquery-ui.min.css">
	<link rel="stylesheet" href="/css/search_anything.css">
	<link rel="stylesheet" href="/css/chat.css">
	
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href='//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800'>

	@yield('stylesheets')

	<script type="text/javascript" src="//code.jquery.com/jquery-1.11.2.js"></script>
	<script type="text/javascript" src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.full.js'></script>
	<script type="text/javascript" src='/js/tablesorter.js'></script>
	<script type="text/javascript" src='/js/jquery-ui.min.js'></script>
	<script type="text/javascript" src='/js/jquery.timepicker.min.js'></script>
	<script type="text/javascript" src='/js/howler.min.js'></script>
	<script type="text/javascript" src='/js/autosize.min.js'></script>
	<script type="text/javascript" src='/js/datefunctions.js'></script>
	<script type="text/javascript" src='/js/angularjs.min.js'></script>

	<script type="text/javascript" src="/js/main.js"></script>
	<script type="text/javascript" src='/js/tabs.js'></script>
	<script type="text/javascript" src="/js/basinput.js"></script>
	<script type="text/javascript" src="/js/search_anything.js"></script>

	<body>

	@section('content')
	<div class="frame">
		<div class="bit-2">
			<div class="container">
				<div class="container_title orange">
					<i class="fa fa-filter"></i> Filter options
				</div>
				<div class="container_content">
					<table class="tlf">
						<tr>
							<td style='width: 100px;'>Assigned to</td>
							<td style='width: 100%;'>
								<select name='user' id='filter_user' class='select2' width="300" placeholder="Filter user...">
									
								</select>
								 	@if (\Request::has('user')) <button class="btn btn-red" filter-reset="userFilter"><i class="fa fa-minus"></i></button> @endif
							</td>
						</tr>
						<tr>
							<td>Jobstatus</td>
							<td>
								<select name='jobstatus' id='filter_jobstatus' class='select2' width="300" placeholder="Filter job status...">
									@foreach(JobStatus::orderBy('type', 'ASC')->get() as $jobstatus)
										<option></option>
										<option value='{{ $jobstatus->id }}' @if (\Request::get('status') == $jobstatus->id) selected="selected" @endif>{{ $jobstatus->type }}</option>

									@endforeach
								</select>
									@if (\Request::has('status')) <button class="btn btn-red" filter-reset="statusFilter"><i class="fa fa-minus"></i></button> @endif
							</td>
						</tr>
						<tr>
							<td>Created on</td>
							<td>
								<input type='text' id='filter_datestart' class='basinput date' style='width: 145px;' placeholder='Start date' value='{{ \Request::get("datestart") }}'>
								-
								<!-- <input type='text' id='filter_dateend' class='basinput date' style='width: 144px;' placeholder='End date' value=' Request::get("dateend") '> -->
								<input type='text' id='filter_dateend' class='basinput date' style='width: 144px;' placeholder='End date' value='{{ \Request::get("dateend") }}'>
								@if (\Request::has('datestart') || \Request::has('dateend')) <button class="btn btn-red" filter-reset="dateFilter"><i class="fa fa-minus"></i></button> @endif
							</td>
						</tr>
					</table>	
				</div>
			</div>
		</div>
	</div>
	<div class="frame">
		<div class="bit-1">
			<div class="container">
				<div class="container_title blue">
					<i class="fa fa-list"></i> Quote list
				</div>
				<div class="container_content nop">
					<table class="data highlight sortable">
						<thead>
							<tr>
								<th style='width: 40px;' data-sortable-no></th>
								<th style='width: 80px;'>Quote</th>
								<th style='width: 130px;' data-sortable-datetime>Date created</th>
								<th style='width: 130px;' data-sortable-datetime>Required by</th>
								<th style='width: 50%;'>Customer</th>
								<th style='width: 50%;'>Title</th>
								<th style='width: 200px;'>Status</th>
								<th style='width: 100px;'>Total</th>
								<th style='width: 100px;'>Received</th>
							</tr>
						</thead>
						<tbody>
							@foreach($quotes as $quote)
							<tr data-href='/quotes/{{ $quote->id }}/edit' class='cp'>
								<!-- <td><img src='/users/{{ $quote->assignedTo }}/photo' width='30' style='border-radius: 100px;'></td> -->
								<td><img src='/img/default_icon.png' width='30' style='border-radius: 100px;'></td>
								<td>{{ $quote->id }}</td>
								<td>{{ $quote->createdOn }}</td>
								@if (strtotime($quote->requiredBy) > time() || $quote->getStatus->type == 'Completed' || $quote->getStatus->type == 'Cancelled')<td>@else<td style='color: red;'>@endif{{ ($quote->requiredBy) }}</td>
								<td>{{ $quote->getCustomer->getCustomerName() }}</td>
								<td>{{ $quote->description }}</td>
								<td>{{ $quote->getStatus->type }}</td>
								<td class='tar'>€ {{ $quote->getTotal() }}</td>
								<td class='tar'>€ {{$quote->getPaid()}}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	</body>
	@stop