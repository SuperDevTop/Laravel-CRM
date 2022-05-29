@section('reportTitle')
	Customers without CIF number
@stop

@section('subtitle')
	
@stop

@section('table')
	<table celspacing='0'>
		<tr>
			<th style='width: 120px;'>Customer ID</th>
			<th style='width: 50%;'>Customer name</th>
			<th style='width: 50%;'>Contact name</th>
			
		</tr>
		@foreach($entries as $customer)
			<tr>
				<td>{{ $customer->id }}</td>
				<td>{{ $customer->getCustomerName() }}</td>
				<td>{{ $customer->contactTitle . ' ' . $customer->contactName }}</td>
			</tr>
		@endforeach
	</table>
@stop