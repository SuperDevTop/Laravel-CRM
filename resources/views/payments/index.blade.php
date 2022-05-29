@extends('master')
@section('page_name')
	List payments
@stop
<?php
	use app\Models\Payment;
	use App\Classes\CommonFunctions;
?>
@section('scripts')
	<script>
	$(function() {
		$('.payment_row').on('click', function(event) {

			if ($(this).next('.payment_row_details').is(':visible')) {
				$(this).next('.payment_row_details').hide();
			} else {
				$('.payment_row_details').hide();
				$('.payment_row').removeClass('opened');
				$(this).next('.payment_row_details').show();
				$(this).addClass('opened');
			}
		});
	});
	</script>
@stop

@section('stylesheets')
	<style>
		.payment_row_details {
			background-color: #BDF3FD;
		}

		.payment_row.opened {
			background-color: #BDF3FD !important;
		}

		.payment_notes_coins td {
			width: 70px;
			padding: 1px 3px 1px 3px;
			text-align: center;
			font-weight: bold;
			font-size: 9pt;
		}

		.payment_notes_coins input {
			width: 100%;
			text-align: center;
			margin-top: -3px;

			padding: 0;
		}
	</style>
@stop

@section('content')
	<div class="frame">
		<div class="bit-1">
			<div class="container">
				<div class="container_title red">
					Cash Register
				</div>
				<div class="container_content nop">
					<table class="data grid">
						<tr>
							<th></th>
							<th>&euro; 500</th>
							<th>&euro; 200</th>
							<th>&euro; 100</th>
							<th>&euro; 50</th>
							<th>&euro; 20</th>
							<th>&euro; 10</th>
							<th>&euro; 5</th>
							<th>&euro; 2</th>
							<th>&euro; 1</th>
							<th>&euro; 0,50</th>
							<th>&euro; 0,20</th>
							<th>&euro; 0,10</th>
							<th>&euro; 0,05</th>
							<th>&euro; 0,02</th>
							<th>&euro; 0,01</th>
						</tr>
						<tr>
							<td>Count</td>
							<td>{{ $counts['n500'] }}</td>
							<td>{{ $counts['n200'] }}</td>
							<td>{{ $counts['n100'] }}</td>
							<td>{{ $counts['n50'] }}</td>
							<td>{{ $counts['n20'] }}</td>
							<td>{{ $counts['n10'] }}</td>
							<td>{{ $counts['n5'] }}</td>
							<td>{{ $counts['c200'] }}</td>
							<td>{{ $counts['c100'] }}</td>
							<td>{{ $counts['c50'] }}</td>
							<td>{{ $counts['c20'] }}</td>
							<td>{{ $counts['c10'] }}</td>
							<td>{{ $counts['c5'] }}</td>
							<td>{{ $counts['c2'] }}</td>
							<td>{{ $counts['c1'] }}</td>
						</tr>
						<tr>
							<td>Total</td>
						    <td>&euro; {{ CommonFunctions::formatNumber($totals->n500) }}</td>
							<td>&euro; {{ CommonFunctions::formatNumber($totals->n200) }}</td>
							<td>&euro; {{ CommonFunctions::formatNumber($totals->n100) }}</td>
							<td>&euro; {{ CommonFunctions::formatNumber($totals->n50) }}</td>
							<td>&euro; {{ CommonFunctions::formatNumber($totals->n20) }}</td>
							<td>&euro; {{ CommonFunctions::formatNumber($totals->n10) }}</td>
							<td>&euro; {{ CommonFunctions::formatNumber($totals->n5) }}</td>
							<td>&euro; {{ CommonFunctions::formatNumber($totals->c200) }}</td>
							<td>&euro; {{ CommonFunctions::formatNumber($totals->c100) }}</td>
							<td>&euro; {{ CommonFunctions::formatNumber($totals->c50) }}</td>
							<td>&euro; {{ CommonFunctions::formatNumber($totals->c20) }}</td>
							<td>&euro; {{ CommonFunctions::formatNumber($totals->c10) }}</td>
							<td>&euro; {{ CommonFunctions::formatNumber($totals->c5) }}</td>
							<td>&euro; {{ CommonFunctions::formatNumber($totals->c2) }}</td>
							<td>&euro; {{ CommonFunctions::formatNumber($totals->c1) }}</td>

						</tr>
						<tr>
							<td colspan='3'>Total in cash register: <b>&euro; {{ CommonFunctions::formatNumber($totals->n500 + $totals->n200 + $totals->n100 + $totals->n50 + $totals->n20 + $totals->n10 + $totals->n5 + 
								$totals->c200 + $totals->c100 + $totals->c50 + $totals->c20 + $totals->c10 + $totals->c5 + $totals->c2 + $totals->c1) }}</b></td> 
						
							<td colspan='13'></td>
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
					Payments ordered by date (100 entries)
				</div>
				<div class="container_content nop">
					<table class="data">
						<tr>
							<th style='width: 40%;'>Customer</th>
							<th style='width: 130px;'>Date</th>
							<th style='width: 120px;'>Payment</th>
							<th style='width: 120px;'>Total</th>
							<th style='width: 120px;'>Non-cash</th>
							<th style='width: 60%;'>Notes</th>
						</tr>
						@foreach(Payment::orderBy('date', 'desc')->orderBy('id', 'desc')->take(100)->get() as $payment)
						<tr class='cp payment_row'>
							<td>
								{{ $payment->getCustomer->getCustomerName() }}
								@if($payment->outToBank == 1)
								<span style='color: #FF9600;'> (Out to bank)</span>
								@endif
							</td>
							<td>{{ CommonFunctions::formatDatetime($payment->date) }}</td>
							<td>{{ $payment->getPaymethod->type }}</td>
							<td>&euro; {{ CommonFunctions::formatNumber($payment->getTotal()) }}</td>
							<td>&euro; {{ CommonFunctions::formatNumber($payment->nonCash) }}</td> 
					
							<td>{{ $payment->notes }}</td>
						</tr>
						<tr class=' payment_row_details hidden'>
							<td colspan='5'>
								<table class='payment_notes_coins payment_notes' style='float: left;'>
									<tr>
										<td>&euro;<br>500</td>
										<td>&euro;<br>200</td>
										<td>&euro;<br>100</td>
										<td>&euro;<br>50</td>
										<td>&euro;<br>20</td>
										<td>&euro;<br>10</td>
										<td>&euro;<br>5</td>
										<td>&euro;<br>2</td>
										<td>&euro;<br>1</td>
										<td>&euro;<br>0,50</td>
										<td>&euro;<br>0,20</td>
										<td>&euro;<br>0,10</td>
										<td>&euro;<br>0,05</td>
										<td>&euro;<br>0,02</td>
										<td>&euro;<br>0,01</td>
									</tr>
									<tr>
										<td><input type='text' readonly disabled value='{{ $payment->n500 }}'></td>
										<td><input type='text' readonly disabled value='{{ $payment->n200 }}'></td>
										<td><input type='text' readonly disabled value='{{ $payment->n100 }}'></td>
										<td><input type='text' readonly disabled value='{{ $payment->n50 }}'></td>
										<td><input type='text' readonly disabled value='{{ $payment->n20 }}'></td>
										<td><input type='text' readonly disabled value='{{ $payment->n10 }}'></td>
										<td><input type='text' readonly disabled value='{{ $payment->n5 }}'></td>
										<td><input type='text' readonly disabled value='{{ $payment->c200 }}'></td>
										<td><input type='text' readonly disabled value='{{ $payment->c100 }}'></td>
										<td><input type='text' readonly disabled value='{{ $payment->c50 }}'></td>
										<td><input type='text' readonly disabled value='{{ $payment->c20 }}'></td>
										<td><input type='text' readonly disabled value='{{ $payment->c10 }}'></td>
										<td><input type='text' readonly disabled value='{{ $payment->c5 }}'></td>
										<td><input type='text' readonly disabled value='{{ $payment->c2 }}'></td>
										<td><input type='text' readonly disabled value='{{ $payment->c1 }}'></td>
									</tr>
								</table>
							</td>
							<td colspan='2'>
								@if($payment->createdBy != null && $payment->createdBy != '')
								Created by: {{ $payment->getCreatedBy->getFullname() }}
								@endif
							</td>
						</tr>
						@endforeach
					</table>
				</div>
			</div>
		</div>
	</div>
@stop