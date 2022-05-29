@section('reportTitle')
	Advertising types all clients
@stop

@section('subtitle')
	(Period {{ $dateStart }} - {{ $dateEnd}})
@stop

@section('table')
	<table celspacing='0'>
		<tr>
			<td style='width: 175px' class="tar">Totals:</td>
			<td class="tar" style='width: 80px;'>{{ array_sum($extraData['monthTotals']) }}</td>
			@foreach($extraData['monthTotals'] as $total)
				<td class="tar">{{ $total }}</td>
			@endforeach
		</tr>
		<tr>
			<th>Type</th>
			<th>Sub Total</th>
			@foreach($extraData['months'] as $month)
				<th>{{ $month->format('m-Y') }}</th>
			@endforeach
		</tr>
		@foreach($entries as $adType)
			@if ($adType['subtotal'] == 0)
				<?php continue; ?>
			@endif
			<tr>
				<td>{{ $adType['type'] }}</td>
				<td class="tar">{{ $adType['subtotal'] }}</td>
				@foreach($adType['months'] as $month)
					<td class="tar">{{ $month }}</td>
				@endforeach
			</tr>
		@endforeach
	</table>
@stop