@section('page_name')
View weekly statistics
@stop

@section('scripts')
{{ HTML::script('plugins/jqPlot/jquery.jqplot.min.js') }}
{{ HTML::script('plugins/jqPlot/excanvas.min.js') }}
{{ HTML::script('plugins/jqPlot/plugins/jqplot.highlighter.min.js') }}
{{ HTML::script('plugins/jqPlot/plugins/jqplot.cursor.min.js') }}
{{ HTML::script('plugins/jqPlot/plugins/jqplot.pointLabels.min.js') }}
{{ HTML::style('plugins/jqPlot/jquery.jqplot.min.css') }}

<script type='text/javascript'>
	$(document).ready(function() {
		$.jqplot('chartdiv', [[[0, 0], [1, 1], [2, 2.5], [3, 3.4], [4, 4.1], [5, 1.2], [6, 2.7]]], {
			animation: {
				speed: 2000
			},
			grid : {
				drawGridlines : false,
				shadow : false
			},
			seriesDefaults : {
				color : '#444444'
			},
			axes : {
				xaxis : {
					min: 0,
					max: 6,
					pad : 0,
					showTicks : false,
					showTickMarks : false
				},
				yaxis : {
					min: 0,
					max: 6,
					showTicks : false,
					showTickMarks : false
				}
			},
			highlighter : {
				show: true,
				sizeAdjust : 10,
				tooltipLocation : 'n',
				tooltipAxes : 'y',
				tooltipFormatString : '<b>&euro; %.2f</b>',
				useAxesFormatters : false
			},
			cursor : {
				show : false
			},
			series : [{
				lineWidth : 1,
				markerOptions : {
					show : true, // wether to show data point markers.
					shadow : false,
					size : 3
				}
			}]
		});
	}); 
</script>
@stop

@section('content')
<div class="frame">
	<div class="bit-2">
		<div class="container">
			<div class="container_title blue">
				Weekly overview
			</div>
			<div class="container_content">
				<table>
					<tr>
						<td><label>Order count</label></td>
						<td><div id="chartdiv" style="height:80px;width:200px; margin-left: 10px;"></div></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
@stop