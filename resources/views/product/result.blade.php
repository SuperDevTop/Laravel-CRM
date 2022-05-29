<table class="form styled">
	<tr>
		<th style='width: 40%;'>Product name</th>
		<th style='width: 20%;'>Category</th>
		<th style='width: 20%;'>Price</th>
		<th style='width: 20%;'>Actions</th>
	</tr>
	@foreach ($products as $product)
		<tr>
			<td>{{ $product->name }}</td>
			<td>{{ $product->getCategory->type }}</td>
			<td>&euro;{{ $product->salesPrice }}</td>
			<td>
				<button onClick='window.location.assign("/products/{{ $product->id }}");' class='styledbutton view'></button>
				<button class='styled red'>
					<i class="fa fa-trash"></i>
				</button>
			</td>
		</tr>
	@endforeach
</table>
