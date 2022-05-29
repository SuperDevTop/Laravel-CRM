@extends('master')
@section('page_name')
	Visual Reports
@stop

@section('scripts')
	<script src="/js/Chart.js"></script>
	<script>
	var chart;
	function resizeChart() {
		var windowHeight = $(window).innerHeight() - 80;
		var $chart = $('#chart').first();
		var newWidth = $chart.closest('.container_content').width() - 20;
		var newHeight = windowHeight - $chart.offset().top;
		$chart.width(newWidth);
		$chart.height(newHeight);
		$chart.attr('width', newWidth);
		$chart.attr('height', newHeight);
	}

	$(function() {

		$('.refresh-btn').on('click', function(event) {
			$(this).closest('.container').find('.container_content').toggle();
		});

		resizeChart();
		$('#generateChartBtn').on('click', function(event) {
			if (
				$('#startDate').val() == '' ||
				$('#endDate').val() == '' ||
				$('#chartType option:selected').val() == '-' ||
				$('#chartInterval option:selected').val() == '-'
			) {
				showError('Please fill in the entire form before clicking the generate button.');
				return;
			}

			$(this).closest('.container_content').toggle();
			$('.refresh-btn').show();
			resizeChart();

			Chart.defaults.global = {
				// Boolean - Whether to animate the chart
			    animation: true,
			    // Number - Number of animation steps
			    animationSteps: 60,
			    // String - Animation easing effect
			    animationEasing: "easeOutQuart",
			    // Boolean - If we should show the scale at all
			    showScale: true,
			    // Boolean - If we want to override with a hard coded scale
			    scaleOverride: false,
			    // ** Required if scaleOverride is true **
			    // Number - The number of steps in a hard coded scale
			    scaleSteps: null,			    // Number - The value jump in the hard coded scale
			    scaleStepWidth: null,			    // Number - The scale starting value
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
			};

			$.ajax({
				url: 'chart_api',
				type: 'POST',
				dataType: 'json',
				data: {
					startDate: $('#startDate').val(),
					endDate: $('#endDate').val(),
					chartType: $('#chartType option:selected').val(),
					chartInterval: $('#chartInterval option:selected').val()
				},
				success: function(data) {
					$('#chartTitle').html(data.chartTitle);
					var chartData = {
						labels: data.labels,
						datasets: [
							{
								label: "My First dataset",
					            fillColor: "rgba(255,153,0,0.2)",
					            strokeColor: "rgba(255,153,0,1)",
					            pointColor: "rgba(102,51,0,1)",
					            pointStrokeColor: "#fff",
					            pointHighlightFill: "#fff",
					            pointHighlightStroke: "rgba(220,220,220,1)",
					            data: data.values
							}
						]
					};
					var ctx = $("#chart").get(0).getContext("2d");
					if (chart != null)
						chart.destroy();

					if (data.type == 'line') {
						chart = new Chart(ctx).Line(chartData, {
							pointHitDetectionRadius: 1
						});
					} else if (data.type == 'bar') {
						chart = new Chart(ctx).Bar(chartData, {
							pointHitDetectionRadius: 1
						});
					} else if (data.type == 'pie') {

					}
				}
			});
		});
	});
	</script>
@stop

@section('content')
	<div class="frame">
		<div class="bit-3">
			<div class="container">
				<div class="container_title blue collapsable">
					Chart options
					<button class="btn btn-default pull-right hidden refresh-btn">Open</button>
				</div>
				<div class="container_content nop">
					<table class="styled form">
						<tr>
							<td style='width: 120px;'>Start date</td>
							<td style='width: 100%;'><input type="text" name='startDate' id='startDate' class='basinput date notempty'></td>
						</tr>
						<tr>
							<td style='width: 120px;'>End date</td>
							<td style='width: 100%;'><input type="text" name='endDate' id='endDate' class='basinput date notempty'></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>Chart type</td>
							<td>
								<select name="chartType" id="chartType">
									<option value="-" selected disabled>Select a chart type...</option>
									<option value="new_quotes_line">New quotes (line)</option>
									<option value="new_quotes_bar">New quotes (bar)</option>
									<option value="payment_count_line">Payment count (line)</option>
									<option value="payment_count_bar">Payment count (bar)</option>
									<option value="payments_sum_line">Payment sum (line)</option>
									<option value="payments_sum_bar">Payment sum (bar)</option>
									<option value="payments_avg_line">Payment average (line)</option>
									<option value="payments_avg_bar">Payment average (bar)</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Interval</td>
							<td>
								<select name="chartInterval" id="chartInterval">
									<option value="-" selected disabled>Select a chart interval...</option>
									<option value="day_calendar">Calender day</option>
									<option value="day_week">Week day</option>
									<option value="week">Week</option>
									<option value="month">Month</option>
									<option value="quarter">Quarter</option>
									<option value="year">Year</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<button class="btn btn-green" id='generateChartBtn'>
									<i class="fa fa-gear"></i> Generate chart
								</button>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	<br clear='both'>
	<div class='frame'>
		<div class="bit-1">
			<div class="container">
				<div class="container_title orange">Chart</div>
				<div class="container_content no_max_height">
					<h1 id="chartTitle" style='margin-top: 0; text-align: center;'></h1>
					<canvas id="chart" width="0" height="0"></canvas>
				</div>
			</div>
		</div>
	</div>
@stop