@extends('master')
@section('page_name')
	Reports
@stop
<?php
	use App\Classes\CommonFunctions;
?>
@section('scripts')
	<script>
	function getData() {

		location.assign('/reports?type=' + $('#reportType option:selected').val() + '&date1=' + $('#date1').val() + '&date2=' + $('#date2').val());

	}
	</script>
@stop

@section('content')
	<div ng-app='app' ng-controller='reportsCtrl'>
		<div class="frame">
			<div class="bit-2">
				<div class="container">
					<div class="container_title blue">
						Reports
					</div>
					<div class="container_content">
						<b>Date range</b>
						<br>
						<input type='text' id='date1' class='dp' value='{{ Request::get('date1') }}'> - <input type='text' id='date2' class='dp' value='{{ Request::get('date2') }}'>
						<br><br>
						<b>Report type</b>
						<br>
						<select id='reportType' style='width: 300px;'>
							<option value="invoices" <?php if(Request::get('type') == 'invoices') echo 'selected'; ?>>Invoices</option>
							<option value="quotes" <?php if(Request::get('type') == 'quotes') echo 'selected'; ?>>Quotes</option>
							<option value="renewals" <?php if(Request::get('type') == 'renewals') echo 'selected'; ?>>Renewals</option>
						</select>
						<br><br>
						<button class="styled blue" onclick='getData()'>
							<i class="fa fa-gear"></i> Get data
						</button>
						@if(Request::has('date1') && Request::has('date2') && Request::has('type'))
						<button class="styled green">
							<i class="fa fa-file-pdf-o"></i> Download report (pdf)
						</button>
						@endif
					</div>
				</div>
			</div>
		</div>
		<br clear='both'>
		
		@if(Request::get('type') == 'invoices')
		<div class="frame">
			<div class="bit-1" id='invoices'>
				<div class="container">
					<div class="container_title orange">
						Invoices
					</div>
					<div class="container_content nop">
						<table class='styled highlight'>
							<tr>
								<th style='width: 100px;'>#</th>
								<th style='width: 160px;'>Date</th>
								<th style='width: 40%;'>Customer</th>
								<th style='width: 60%;'>Title</th>
								<th style='width: 140px;'>Created by</th>
								<th style='width: 100px;'>Total</th>
								<th style='width: 60px;'>View</th>
							</tr>
							@foreach($results as $invoice)
							<tr>
								<td>{{ $invoice->id }}</td>
								<td>{{ $invoice->createdOn }}</td>
								<td>{{ $invoice->getCustomer->getCustomerName() }}</td>
								<td>{{ $invoice->jobTitle }}</td>
								<td>{{ User::find($invoice->createdBy)->getFullname() }}</td>
								<td>&euro; {{ CommonFunctions::formatNumber($invoice->getTotal()) }}</td>
								<td>
									<button class="styled blue" data-href='invoices/{{ $invoice->id }}/edit'>
										<i class="fa fa-eye"></i>
									</button>
								</td>
							</tr>
							@endforeach
						</table>
					</div>
				</div>
			</div>
		</div>
		@endif

		@if(Request::get('type') == 'quotes')
		<div class="frame">
			<div class="bit-1" id='invoices'>
				<div class="container">
					<div class="container_title orange">
						Quotes
					</div>
					<div class="container_content nop">
						<table class='styled highlight'>
							<tr>
								<th style='width: 100px;'>#</th>
								<th style='width: 160px;'>Date</th>
								<th style='width: 40%;'>Customer</th>
								<th style='width: 60%;'>Title</th>
								<th style='width: 140px;'>Created by</th>
								<th style='width: 100px;'>Total</th>
								<th style='width: 60px;'>View</th>
							</tr>
							@foreach($results as $quote)
							<tr>
								<td>{{ $quote->id }}</td>
								<td>{{ $quote->createdOn }}</td>
								<td>{{ $quote->getCustomer->getCustomerName() }}</td>
								<td>{{ $quote->description }}</td>
								<td>{{ User::find($quote->createdBy)->getFullname() }}</td>
								<td>&euro; {{ CommonFunctions::formatNumber($quote->getTotal()) }}</td>
								<td>
									<button class="styled blue" data-href='quotes/{{ $quote->id }}/edit'>
										<i class="fa fa-eye"></i>
									</button>
								</td>
							</tr>
							@endforeach
						</table>
					</div>
				</div>
			</div>
		</div>
		@endif

		@if(Request::get('type') == 'renewals')
		<div class="frame">
			<div class="bit-1" id='invoices'>
				<div class="container">
					<div class="container_title orange">
						Renewals
					</div>
					<div class="container_content nop">
						<table class="styled highlight">
							<tr>
								<th style='width: 50%;'>Customer</th>
								<th style='width: 50%;'>Product</th>
								<th style='width: 150px;'>Start date</th>
								<th style='width: 140px;'>Renewal Frequency</th>
								<th style='width: 120px;'>Renewal Count</th>
								<th style='width: 160px;'>Next renewal date</th>
								<th style='width: 95px;'>Quote</th>
								<th style='width: 95px;'></th>
							</tr>
							@foreach($results as $renewal)
								<tr>
									<td>{{ $renewal->getCustomer->getCustomerName() }}</td>
									<td>{{ $renewal->getProduct->name }}</td>
									<td>{{ $renewal->startDate }}</td>
									<td>Every {{ $renewal->renewalFreq }} {{ ($renewal->renewalFreq == 1) ? 'Month' : 'Months' }}</td>
									<td>{{ $renewal->renewalCount }}</td>
									<td>{{ $renewal->nextRenewalDate }}</td>
									<td>
										<button class="styled blue" data-href='renewals/{{ $renewal->id }}/quote'>
											<i class="fa fa-share"></i> Quote
										</button>
									</td>
									<td>
										<button class='styled red' onclick='confirmRenewalCancel({{ $renewal->id }})'>
											<i class="fa fa-minus"></i> Cancel
										</button>
									</td>
								</tr>
							@endforeach
						</table>
					</div>
				</div>
			</div>
		</div>
		@endif
	</div>
	<script>
		$(function() {
			Chart.defaults.global = {
	    // Boolean - Whether to animate the chart
	    animation: true,

	    // Number - Number of animation steps
	    animationSteps: 100,

	    // String - Animation easing effect
	    animationEasing: "easeOutQuart",

	    // Boolean - If we should show the scale at all
	    showScale: true,

	    // Boolean - If we want to override with a hard coded scale
	    scaleOverride: false,

	    // ** Required if scaleOverride is true **
	    // Number - The number of steps in a hard coded scale
	    scaleSteps: null,
	    // Number - The value jump in the hard coded scale
	    scaleStepWidth: null,
	    // Number - The scale starting value
	    scaleStartValue: null,

	    // String - Colour of the scale line
	    scaleLineColor: "rgba(0,0,0,.1)",

	    // Number - Pixel width of the scale line
	    scaleLineWidth: 1,

	    // Boolean - Whether to show labels on the scale
	    scaleShowLabels: true,

	    // Interpolated JS string - can access value
	    scaleLabel: "<%=value%>",

	    // Boolean - Whether the scale should stick to integers, not floats even if drawing space is there
	    scaleIntegersOnly: true,

	    // Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
	    scaleBeginAtZero: false,

	    // String - Scale label font declaration for the scale label
	    scaleFontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",

	    // Number - Scale label font size in pixels
	    scaleFontSize: 12,

	    // String - Scale label font weight style
	    scaleFontStyle: "normal",

	    // String - Scale label font colour
	    scaleFontColor: "#666",

	    // Boolean - whether or not the chart should be responsive and resize when the browser does.
	    responsive: false,

	    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
	    maintainAspectRatio: true,

	    // Boolean - Determines whether to draw tooltips on the canvas or not
	    showTooltips: true,

	    // Array - Array of string names to attach tooltip events
	    tooltipEvents: ["mousemove", "touchstart", "touchmove"],

	    // String - Tooltip background colour
	    tooltipFillColor: "rgba(0,0,0,0.8)",

	    // String - Tooltip label font declaration for the scale label
	    tooltipFontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",

	    // Number - Tooltip label font size in pixels
	    tooltipFontSize: 14,

	    // String - Tooltip font weight style
	    tooltipFontStyle: "normal",

	    // String - Tooltip label font colour
	    tooltipFontColor: "#fff",

	    // String - Tooltip title font declaration for the scale label
	    tooltipTitleFontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",

	    // Number - Tooltip title font size in pixels
	    tooltipTitleFontSize: 14,

	    // String - Tooltip title font weight style
	    tooltipTitleFontStyle: "bold",

	    // String - Tooltip title font colour
	    tooltipTitleFontColor: "#fff",

	    // Number - pixel width of padding around tooltip text
	    tooltipYPadding: 6,

	    // Number - pixel width of padding around tooltip text
	    tooltipXPadding: 6,

	    // Number - Size of the caret on the tooltip
	    tooltipCaretSize: 8,

	    // Number - Pixel radius of the tooltip border
	    tooltipCornerRadius: 6,

	    // Number - Pixel offset from point x to tooltip edge
	    tooltipXOffset: 10,

	    // String - Template string for single tooltips
	    tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>",

	    // String - Template string for single tooltips
	    multiTooltipTemplate: "<%= value %>",

	    // Function - Will fire on animation progression.
	    onAnimationProgress: function(){},

	    // Function - Will fire on animation completion.
	    onAnimationComplete: function(){}
	}
			var data = {
    labels: ["January", "February", "March", "April", "May", "June", "July"],
    datasets: [
        {
            label: "My First dataset",
            fillColor: "rgba(220,220,220,0.5)",
            strokeColor: "rgba(220,220,220,0.8)",
            highlightFill: "rgba(220,220,220,0.75)",
            highlightStroke: "rgba(220,220,220,1)",
            data: [65, 59, 80, 81, 56, 55, 40]
        },
        {
            label: "My Second dataset",
            fillColor: "rgba(151,187,205,0.5)",
            strokeColor: "rgba(151,187,205,0.8)",
            highlightFill: "rgba(151,187,205,0.75)",
            highlightStroke: "rgba(151,187,205,1)",
            data: [28, 48, 40, 19, 86, 27, 90]
        }
    ]
};

			var ctx = $('#myChart').get(0).getContext("2d");
			var myNewChart = new Chart(ctx).StackedBar(data, []);
		});
	</script>
	<canvas id="myChart" width="400" height="400"></canvas>
@stop