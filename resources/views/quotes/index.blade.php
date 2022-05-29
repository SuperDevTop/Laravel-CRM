@extends('master')
@section('page_name')
	Quote list
@stop

@section('breadcrumbs')
	Quotes & invoices <i class="breadcrumb"></i> List quotes
@stop

<?php
	use App\Models\User;
	use App\Models\variables\JobStatus;
	use App\Classes\CommonFunctions;
?>

@section('scripts')
	<script>
		var userFilter = {{ (Input::has('user')) ? Input::get('user') : 'false' }};
		var statusFilter = {{ (Input::has('status')) ? Input::get('status') : 'false' }};
		var dateStartFilter = {{ (Input::get('datestart')) ? "'" . Input::get('datestart') . "'" : 'false' }};
		var dateEndFilter = {{ (Input::get('dateend')) ? "'" . Input::get('dateend') . "'" : 'false' }};	

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
									@foreach(User::where('disabled', '=', 0)->orderBy('lastName', 'ASC')->get() as $user)
										<option></option>
										<option value='{{ $user->id }}' @if (Request::get('user') == $user->id) selected="selected" @endif>{{ $user->getFullname() }}</option>
									@endforeach
								</select>
								@if (Request::has('user')) <button class="btn btn-red" filter-reset="userFilter"><i class="fa fa-minus"></i></button> @endif
							</td>
						</tr>
						<tr>
							<td>Jobstatus</td>
							<td>
								<select name='jobstatus' id='filter_jobstatus' class='select2' width="300" placeholder="Filter job status...">
									@foreach(JobStatus::orderBy('type', 'ASC')->get() as $jobstatus)
										<option></option>
										<!-- <option value='{{ $jobstatus->id }}' @if (Request::get('status') == $jobstatus->id) selected="selected" @endif>{{ $jobstatus->type }}</option> -->
									@endforeach
								</select>
								@if (Request::has('status')) <button class="btn btn-red" filter-reset="statusFilter"><i class="fa fa-minus"></i></button> @endif
							</td>
						</tr>
						<tr>
							<td>Created on</td>
							<td>
								<input type='text' id='filter_datestart' class='basinput date' style='width: 145px;' placeholder='Start date' value='{{ Request::get("datestart") }}'>
								-
								<input type='text' id='filter_dateend' class='basinput date' style='width: 144px;' placeholder='End date' value='{{ Request::get("dateend") }}'>
								@if (Request::has('datestart') || Request::has('dateend')) <button class="btn btn-red" filter-reset="dateFilter"><i class="fa fa-minus"></i></button> @endif
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
								<td><img src='/users/{{ $quote->assignedTo }}/photo' width='30' style='border-radius: 100px;'></td>
								<td>{{ $quote->id }}</td>
								<td>{{ CommonFunctions::formatDateTime($quote->createdOn) }}</td>
								@if (strtotime($quote->requiredBy) > time() || $quote->getStatus->type == 'Completed' || $quote->getStatus->type == 'Cancelled')<td>@else<td style='color: red;'>@endif{{ CommonFunctions::formatDateTime($quote->requiredBy) }}</td>
								<td>{{ $quote->getCustomer->getCustomerName() }}</td>
								<td>{{ $quote->description }}</td>
								<td>{{ $quote->getStatus->type }}</td>
								<td class='tar'>€ {{ CommonFunctions::formatNumber($quote->getTotal()) }}</td>
								<td class='tar'>€ {{ CommonFunctions::formatNumber($quote->getPaid()) }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@stop