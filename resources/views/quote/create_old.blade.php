@section('page_name')
	New quote for '{{ $customer->getCustomerName() }}'
@stop

@section('scripts')
	<script>
		var selectedRow = null;
		var unsavedEntries = [];
		$(function() {
			$('#quote_entries').on('click', '#quoteEntry', function() {
				clickRow($(this));
			});

			$('#editEntry_visitDate').datetimepicker({
				dateFormat: 'dd-mm-yy',
				timeFormat: 'hh:mm tt',
				hourMin: 7,
				hourMax: 22
			});
			
			$('#editEntry_finishDate').datetimepicker({
				dateFormat: 'dd-mm-yy',
				timeFormat: 'hh:mm tt',
				hourMin: 7,
				hourMax: 22
			});

			$('#editEntry_description').on('change keyup paste', function() {
				var descriptionColumn = selectedRow.children()[4];
				$(descriptionColumn).html($(this).val());
				editRow($(this));
			});

			$('#editEntry_productName').on('change keyup paste', function() {
				var productNameColumn = selectedRow.children()[1];
				$(productNameColumn).html($(this).val());
				editRow($(this));
			});

			$('#editEntry_productPrice').on('change keyup paste', function() {
				var productPriceColumn = selectedRow.children()[2];
				$(productPriceColumn).html($(this).val());
				editRow($(this));
			});

			$('#editEntry_finishDate').on('change keyup', function() {
				var descriptionColumn = selectedRow.children()[5];
				$(descriptionColumn).html($(this).val());
				editRow($(this));
			});

			$('#editEntry_quantity').on('change keyup paste', function() {
				var descriptionColumn = selectedRow.children()[6];
				$(descriptionColumn).html($(this).val());
				editRow($(this));
			});

			$('#editEntry_discount').on('change keyup paste', function() {
				var descriptionColumn = selectedRow.children()[7];
				$(descriptionColumn).html($(this).val());
				editRow($(this));
			});
			
			$('#editEntry_visitDate').on('change', function() {
				var descriptionColumn = selectedRow.children()[3];
				$(descriptionColumn).html($(this).val());
				editRow($(this));
			});


			$('#editEntry_product').chosen().change(function() {
				if ($(this).val() == '0') {
					// Custom product, enable product name & price fields
					$('#editEntry_productName').removeAttr('readonly');
					$('#editEntry_productPrice').removeAttr('readonly');
					var productIdColumn = selectedRow.children()[0];
					$(productIdColumn).html('-');
					return;
				} else {
					$('#editEntry_productName').attr('readonly', 'readonly');
					$('#editEntry_productPrice').attr('readonly', 'readonly');
				}
				// Fetch unit price
				$.ajax({
					url: '/ajax/get_product_price',
					type: 'post',
					dataType: 'json',
					data: {
						productId: $(this).val()
					},
					success: function(data) {
						var productIdColumn = selectedRow.children()[0];
						var productNameColumn = selectedRow.children()[1];
						var productPriceColumn = selectedRow.children()[2];

						$('#editEntry_productName').val($('#editEntry_product :selected').html());
						$('#editEntry_productPrice').val(data.productPrice);

						$(productIdColumn).html($('#editEntry_product').val());
						$(productNameColumn).html($('#editEntry_productName').val());
						$(productPriceColumn).html($('#editEntry_productPrice').val());
						editRow($(this));
					}
				});
			});
			
			$('#entry_save').on('click', function() {
				// Save the selected row :)
				$.ajax({
					url: '/ajax/quote_update_entry',
					dataType: 'json',
					type: 'post',
					data: {
						entryId: selectedRow.attr('data-entry'),
						productId: $('#editEntry_product').val(),
						productName: $('#editEntry_productName').val(),
						productPrice: $('#editEntry_productPrice').val(),
						visitDate: $('#editEntry_visitDate').val(),
						description: $('#editEntry_description').val(),
						finishDate: $('#editEntry_finishDate').val(),
						quantity: $('#editEntry_quantity').val(),
						discount: $('#editEntry_discount').val()
					},
					success: function() {

					}
				});

				// Remove the row from the unsaved rows, and remove the row highlight from the table
				selectedRow.css('background-color', 'white');
				unsavedEntries.splice($.inArray(selectedRow, unsavedEntries), 1);
			});

			$('#newEntry').on('click', function() {
				$.ajax({
					url: '/ajax/new_quote_entry',
					type: 'post',
					dataType: 'json',
					data: {
						quoteId: {{ $quote->id }}
					},
					success: function(data) {
						var newRow = $('<tr style="cursor: pointer;" id="quoteEntry" data-entry="' + data.entryId + '"><td></td><td></td><td></td><td></td><td></td><td></td><td>1</td><td>0</td></tr>').insertAfter('#quote_entries tr:first');
						clickRow(newRow);
						editRow();
					}
				});
			});
			
			function editRow() {
				var alreadyUnsaved = $.inArray(selectedRow, unsavedEntries);
				if (alreadyUnsaved < 0) {
					unsavedEntries.push(selectedRow);
					selectedRow.css('background-color', '#FFD1B2');
				}
			}

			function clickRow(row) {
				var firstColumn = $(selectedRow).children();
				$(firstColumn).css('border-bottom', 'none');
				$(firstColumn).css('border-top', 'none');
				$(firstColumn[0]).css('border-left', 'none');
				$(firstColumn[7]).css('border-right', 'none');

				selectedRow = row;

				var firstColumn = $(selectedRow).children();
				$(firstColumn).css('border-bottom', '3px solid green');
				$(firstColumn).css('border-top', '3px solid green');
				$(firstColumn[0]).css('border-left', '6px solid green');
				$(firstColumn[7]).css('border-right', '3px solid green');

				var columns = row.children();

				var productId = $(columns[0]).html();
				var productName = $(columns[1]).html();
				var productPrice = $(columns[2]).html();
				var visitDate = $(columns[3]).html();
				var description = $(columns[4]).html();
				var finishDate = $(columns[5]).html();
				var quantity = $(columns[6]).html();
				var discount = $(columns[7]).html();


				$('#editEntry_product option[value="' + productId + '"]').prop('selected', 'selected');
				$('#editEntry_product').trigger('chosen:updated');

				$('#editEntry_productName').val(productName);
				$('#editEntry_productPrice').val(productPrice);
				$('#editEntry_visitDate').val(visitDate);
				$('#editEntry_description').val(description);
				$('#editEntry_finishDate').val(finishDate);
				$('#editEntry_quantity').val(quantity);
				$('#editEntry_discount').val(discount);

				if ($('#editEntry_product').val() != 0) {
					$('#editEntry_productName').attr('readonly', 'readonly');
					$('#editEntry_productPrice').attr('readonly', 'readonly');
				} else {
					$('#editEntry_productName').removeAttr('readonly');
					$('#editEntry_productPrice').removeAttr('readonly');
				}
			}
		});
	</script>
@stop

@section('content')
	<div class="frame">
		<div class="bit-3">
			<div class="container">
				<div class="container_title blue">
					General information
				</div>
				<div class="container_content">
					{{ Form::open() }}
						<table class="form">
							<tr>
								<td style='width: 150px;'><label>Job Title</label></td>
								<td style='width: 100%;'>{{ Form::text('jobTitle', null, array('placeholder' => 'Job Title')) }}</td>
							</tr>
							<tr>
								<td><label>Customer</label></td>
								<td>{{ Form::text('customer', $customer->getCustomerName(), array('readonly' => 'readonly')) }}</td>
							</tr>
							<tr>
								<td><label>Advertisement Type</label></td>
								<td>{{ Form::select('adType', AdType::pluck('type', 'id')) }}</td>
							</tr>
							<tr>
								<td><label>Job status</label></td>
								<td>{{ Form::select('jobStatus', JobStatus::pluck('type', 'id')) }}</td>
							</tr>
						</table>
					<button type='button' id='newEntry' class='styledbutton add text fr' id='quote_create'>New entry</button><br>
					{{ Form::close() }}
				</div>
			</div>
		</div>
		<div class="bit-3">
			<div class="container">
				<div class="container_title green">
					Edit quote entry
				</div>
				<div class="container_content">
					<table class="form tlf">
						<tr>
							<td style='width: 150px;'><label>Product:</label></td>
							<td style='width: 100%;'>
								<select id='editEntry_product' data-placeholder='Select a product...'>
									<option></option>
									<option value='0'>Custom product</option>
									@foreach(Product::where('discontinued', '=', '0')->get() as $product)
										<option value='{{ $product->id }}'>{{ $product->name }}</option>
									@endforeach
								</select>
							</td>
						</tr>
						<tr>
							<td><label>Product name:</label></td>
							<td><input type="text" id='editEntry_productName' readonly='readonly'></td>
						</tr>
						<tr>
							<td><label>Product price (pcs):</label></td>
							<td><input type="text" class='euro' id='editEntry_productPrice' readonly='readonly'></td>
						</tr>
						<tr>
							<td><label>Visit Date:</label></td>
							<td><input type="text" id='editEntry_visitDate' readonly='readonly'></td>
						</tr>
						<tr>
							<td><label>Description:</label></td>
							<td><input type="text" id='editEntry_description'></td>
						</tr>
						<tr>
							<td><label>Finish time:</label></td>
							<td><input type="text" id='editEntry_finishDate' readonly='readonly'></td>
						</tr>
						<tr>
							<td><label>Quantity:</label></td>
							<td><input type="text" id='editEntry_quantity'></td>
						</tr>
						<tr>
							<td><label>Discount:</label></td>
							<td><input type="text" id='editEntry_discount'></td>
						</tr>
						<tr>
							<td></td>
							<td><button class='blue fr' id='entry_save'>Save</button></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="frame">
		<div class="bit-1">
			<div class="container">
				<div class="container_title red">
					Quote products
				</div>
				<div class="container_content nop">
					<table class="styled tlf highlight" id='quote_entries'>
						<tr>
							<th style='width: 100px;'>Product ID</th>
							<th style='width: 40%;'>Product Name</th>
							<th style='width: 130px;'>Unit price</th>
							<th style='width: 130px;'>Visit Date</th>
							<th style='width: 60%;'>Description</th>
							<th style='width: 130px;'>Finished</th>
							<th style='width: 80px;'>Quantity</th>
							<th style='width: 80px;'>Discount</th>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
@stop