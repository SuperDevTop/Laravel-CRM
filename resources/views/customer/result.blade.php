<table class="data highlight sortable">
	<thead>
		<tr>
			<th style='width: 100px;'>Code</th>
			<th style='width: auto;'>Company name</th>
			<th style='width: auto'>Contact name</th>
			<th style='width: 90px;'>Credit</th>
			<th style='width: 100px;' data-sortable-no>Actions</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($customers as $customer)
			<tr>
				<td>{{ $customer->customerCode }}</td>
				<td>{{ $customer->companyName }}</td>
				<td>{{ $customer->contactTitle . ' ' . $customer->contactName }}</td>
				<td>{{ $customer->getCustomerCreditRating->abbreviation }}</td>
				<td>
					@if (Auth::user()->hasPermission('customer_view'))
						<button onClick='window.location.assign("/customers/{{ $customer->id }}");' class='btn btn-default btn-square'>
							<i class="fa fa-eye"></i>
						</button>
					@endif
					@if (Auth::user()->hasPermission('customer_delete') && 1 == 2)
						<button onClick='confirmDelete("{{ $customer->id }}");' class='btn btn-red btn-square'>
							<i class="fa fa-trash"></i>
						</button>
					@endif
				</td>
			</tr>
		@endforeach
	</tbody>
</table>
