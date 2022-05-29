@extends('master')
<?php
 use App\Models\DirectDebitJob;
?>

@section('page_name')
	List Direct Debit Jobs
@stop

@section('breadcrumbs')
	Direct Debits <i class="breadcrumb"></i> List
@stop

@section('content')
	<div class="frame">
		<div class="bit-1">
			<div class="container">
				<div class="container_title blue">
					Job index
				</div>
				<div class="container_content nop">
					<table class="data highlight sortable">
						<thead>
							<tr>
								<th style='width: 100%;'>Description</th>
								<th style='width: 120px;' data-sortable-date>Date created</th>
								<th style='width: 90px;'>Completed</th>
								<th style='width: 120px;'>Invoice count</th>
								<th style='width: 120px;'>Total amount</th>
								<th style='width: 100px;' data-sortable-no>Download</th>
							</tr>
						</thead>
						<tbody>
							@foreach(DirectDebitJob::orderBy('date', 'desc')->get() as $job)
								<tr onclick='$(".jobDetailRow").hide(); $(this).next("tr").toggle()' style='cursor: pointer;'>
									<td>{{ $job->description }}</td>
									<td>{{ date('d-m-Y', strtotime($job->date)) }}</td>
									<td>{{ ($job->completed == 1) ? '<i class="fa fa-check" style="color: #26B702;"></i>' : '<i class="fa fa-remove" style="color: red;"></i>' }}</td>
									<td>{{ DirectDebitDetail::where('job', '=', $job->id)->count() }}</td>
									<td class='tar'>&euro; {{ number_format($job->getTotal(), 2, ',', '.') }}</td>
									<td>
										<button data-tooltip='Download XML for the bank' class="btn btn-default btn-square" onclick='window.open("ddGetSepaXml/{{ $job->id }}")'><i class="fa fa-download"></i></button>
										<button data-tooltip='Download in Excel format' class="btn btn-green btn-square" onclick='window.open("/openReport?type=direct_debit_job&jobId={{ $job->id }}&csv=true");'><i class="fa fa-file-excel-o"></i></button>
										<button data-tooltip='Download in PDF format' class="btn btn-red btn-square" onclick='window.open("/openReport?type=direct_debit_job&jobId={{ $job->id }}");'><i class="fa fa-file-pdf-o"></i></button>
									</td>
								</tr>
								<tr style='display: none; background-color: #F7F7F7;' class='jobDetailRow no_highlight'>
									<td colspan='6' style='padding: 5px 50px 5px 50px;'>
										<table class="data grid">
											<tr>
												<th style='width: 120px;'>Invoice Id</th>
												<th style='width: 40%;'>Customer</th>
												<th style='width: 60%;'>Description</th>
												<th style='width: 120px;'>Bank Charge</th>
												<th style='width: 90px;' class='tar'>Total</th>
												<th style='width: 40px;'>View</th>
											</tr>
											@foreach($job->getInvoices() as $invoice)
												<tr>
													<td>{{ $invoice->invoice }}</td>
													<td>{{ Customer::find($invoice->customer)->getCustomerName() }}</td>
													<td>{{ $invoice->description }}</td>
													<td class='tar'>&euro; {{ number_format($invoice->bankCharge, 2, ',', '.') }}</td>
													<td class='tar'>&euro; {{ number_format($invoice->total, 2, ',', '.') }}</td>
													<td>
														<button class="btn btn-default btn-square" onclick='window.open("invoices/{{ $invoice->invoice }}/edit")'>
															<i class="fa fa-eye"></i>
														</button>
													</td>
												</tr>
											@endforeach
										</table>
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