@extends('master')
@section('page_name')
	My Jobs
@stop

<?php
	use App\Classes\CommonFunctions;
?>

@section('breadcrumbs')
	My Jobs
@stop

@section('content')
	<div class="frame">
		<div class="bit-1">
			<div class="container">
				<div class="container_title blue">
					List My Jobs
				</div>
				<div class="container_content nop">
					<table class="data highlight sortable">
						<thead>
							<tr>
								<th style='width: 150px;'>Quote #</th>
								<th style='width: 40%;'>Customer</th>
								<th style='width: 60%;'>Description</th>
								<th style='width: 150px;' data-sortable-date>Required by</th>
								<th style='width: 150px;'>Status</th>
								<th style='width: 70px;' data-sortable-no>Open</th>
							</tr>
						</thead>
						<tbody>
							@foreach($quotes as $quote)
							<tr>
								<td>{{ $quote->id }}</td>
								<td>{{ $quote->getCustomer->getCustomerName() }}</td>
								<td>{{ $quote->description }}</td>
								<td>{{ CommonFunctions::formatDate($quote->requiredBy) }}</td>
								<td>{{ $quote->getStatus->type }}</td>
								<td>
									<button class="styled blue" data-href='quotes/{{ $quote->id }}/edit'>
										<i class="fa fa-eye"></i>
									</button>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@stop