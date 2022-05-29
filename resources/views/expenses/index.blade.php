@extends('master')
@section('page_title')
	List expenses
@stop

<?php
	use App\Classes\CommonFunctions;
?>
@section('breadcrumbs')
	Expenses <i class="breadcrumb"></i> List expenses
@stop

@section('content')
	<div class="frame">
		<div class="bit-1">
			<div class="container">
				<div class="container_title blue">
					Last 100 expenses
				</div>
				<div class="container_content nop">
					<table class="data highlight sortable">
						<thead>
							<tr>
								<th style='width: 160px;'>Invoice number</th>
								<th style='width: 150px;' data-sortable-date>Invoice date</th>
								<th style='width: 150px;' data-sortable-date>Invoice received</th>
								<th style='width: 40%;'>Supplier name</th>
								<th style='width: 30%;'>Category</th>
								<th style='width: 30%;'>Sub-category</th>
							</tr>
						</thead>
						<tbody>
							@foreach($expenses as $expense)
							<tr class='cp' data-href='/expenses/{{ $expense->id }}'>
								<td>{{ $expense->invoiceNumber }}</td>
								<td>{{ CommonFunctions::formatDate($expense->invoiceDate) }}</td>
								<td>{{ CommonFunctions::formatDate($expense->invoiceReceivedDate) }}</td>
								<td>{{ $expense->supplierName }}</td>
								<td>{{ $expense->getCategory->english }}</td>
								<td>{{ $expense->getSubCategory->english }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@stop