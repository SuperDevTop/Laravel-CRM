@section('page_name')
	Create renewal
@stop

@section('scripts')
	<script>
		function validateForm() {
			var product = $('#product option:selected').val();
			
			if (product == '' || !product) {
				showError("Please select a product first!");
				return false;
			}

			return true;
		}
	</script>
@stop

@section('content')
	<div class="frame">
		<div class="bit-2">
			<div class="container">
				<div class="container_title blue">
					Create new renewal
				</div>
				<div class="container_content" style='overflow: visible;'>
					<form action='' method='POST' onsubmit='return validateForm()'>
						<table class="form">
							<tr>
								<td style='width: 130px;'>Product</td>
								<td style='width: 100%;'>
									<select name='product' id="product" class='select2' placeholder='Please select a product...'>
										<option></option>
										@foreach(Product::where('discontinued', '=', 0)->orderBy('name')->get() as $product)
											<option value='{{ $product->id }}'>{{ $product->name }}</option>
										@endforeach
									</select>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td></td>
							</tr>
							<tr>
								<td>Start date</td>
								<td>
									<input type='text' name='startDate' class='basinput notempty date' style='width: 160px;' value='{{ date("d-m-Y") }}'>
								</td>
							</tr>
							<tr>
								<td>Renewal frequency</td>
								<td>
									<input type='number' name='renewalFreq' min="1" max="12" style='width: 160px;'> months
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td></td>
							</tr>
							<tr>
								<td>Renewal count</td>
								<td>
									<input type='number' name='renewalCount' value='0' min="0" max="100" style='width: 160px;'><br>
									(Renewal count is the amount of times this renewal has been RENEWED (so paid again for the new period). 0 Means that the renewal is currently in it's first period of x months. 1 means that this renewal has been RENEWED once (so it's in it's second period))
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td></td>
							</tr>
							<tr>
								<td>Discount</td>
								<td><input type='number' step='any' name='discount' min='0' max='100' value='0' style='width: 160px'> %</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td></td>
							</tr>
							<tr>
								<td>Notes</td>
								<td><textarea name="notes" class='fw' style='height: 100px;'></textarea></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td></td>
							</tr>
							<tr>
								<td>Create quote</td>
								<td>
									<input type='hidden' name='createQuote' value='0'>
									<input type='checkbox' name='createQuote' value='1'>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>
									<button type='submit' class="btn btn-green fr">
										<i class="fa fa-save"></i> Create renewal	
									</button>
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</div>
	</div>
@stop