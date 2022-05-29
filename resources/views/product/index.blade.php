@extends('master')
@section('page_name')
	Manage products
@stop

@section('breadcrumbs')
	Products <i class="breadcrumb"></i> List Products
@stop

@section('scripts')
	<script>
		$(function() {
			$('#searchQuery').focus();
			$('#searchQuery').bind('input', function(e) {
				$('#resultsTable tr').each(function(index, row) {
					if (index == 0) return;
					var resultFound = false;

					$(this).find('td').each(function(i, e) {
						if ($(e).html().toLowerCase().indexOf($('#searchQuery').val().toLowerCase()) != -1) resultFound = true;
					});

					if (resultFound && $('#searchQuery').val() != '') {
						$(row).show();
					} else {
						$(row).hide();
					}
				});
			});

			$('#category').on('change', function(e) {
				$('#searchQuery').val('');
				var category = $('#category option:selected').text();
				$('#resultsTable tr').each(function(index, row) {
					if (index == 0) return;
					if ($(this).find('td:nth-child(2)').html() == category) {
						$(row).show();
					} else {
						$(row).hide();
					}
				});
			});
		});

		function showAz(letter) {
			$('#searchQuery').val('');
			if (letter == 'all') {
				$('#resultsTable tr').each(function(index, row) {
					$(row).show();
				});
				return;
			}
			$('#resultsTable tr').each(function(index, row) {
				if (index == 0) return;
				var resultFound = false;

				$(this).find('td').each(function(i, e) {
					if ($(e).html().substring(0, 1) == letter) resultFound = true;
				});

				if (resultFound) {
					$(row).show();
				} else {
					$(row).hide();
				}
			});
		}

		function confirmProductRemoval(productId) {
			confirmDialog(
				'Are you sure?',
				'Are you sure you want to delete this product? Please note that you can only delete products that are not used in any quotes!<br><br><span style="color: red; font-weight: bold;">This action is permanent and cannot be undone!</span>',
				function() {
					ajaxRequest(
						'product_remove',
						{
							productId: productId
						},
						function(data) {
							if (data.success) {
								showSuccess('The product has been deleted succesfully.<br><br>The browser will now refresh.');
								location.reload(true);
							} else {
								showError('Could not delete product.<br><br>You can only delete a product if it is not used in any quotes or renewals! This product is used in <b>' + data.quoteCount + '</b> quotes or renewals!');
							}
						}
					);
				}
			);
		}

		function confirmProductArchival(productId) {
			confirmDialog(
				"Are you sure?",
				"Are you sure you want to discontinue this product?",
				function() {
					ajaxRequest(
						'product_archive',
						{
							productId: productId
						},
						function(data) {
							if (data.success) {
								showSuccess('The product has been archived succesfully.<br><br>The browser will now refresh.');
								location.reload(true);
							} else {
								showError('Could not archive product. Please try again. If the problem persists, please contact support.');
							}
						}
					);
				}
			);
		}

		function confirmMultiArchival() {
			var $selectedCheckboxes = $('[name="selected_products[]"]:checked');
			var productIds = [];

			$selectedCheckboxes.each(function(index) {
				productIds.push($(this).val());
			});

			confirmDialog(
				"Are you sure?",
				"Are you sure you want to archive the selected " + productIds.length + " products?",
				function() {
					ajaxRequest(
						'product_multi_archive',
						{
							productIds: productIds
						},
						function(data) {
							if (data.success) {
								showSuccess('The products have been archived succesfully.<br><br>The browser will now refresh.');
								location.reload(true);
							} else {
								showError('Could not archive products. Please try again. If the problem persists, please contact support.');
							}
						}
					);
				}
			)
			
		}

		function confirmMultiRemoval() {
			var $selectedCheckboxes = $('[name="selected_products[]"]:checked');
			var productIds = [];

			$selectedCheckboxes.each(function(index) {
				productIds.push($(this).val());
			});

			confirmDialog(
				"Are you sure?",
				"Are you sure you want to delete the selected " + productIds.length + " products?",
				function() {
					ajaxRequest(
						'product_multi_remove',
						{
							productIds: productIds
						},
						function(data) {
							if (data.success) {
								showSuccess(data.removed + " products have been removed, " + data.not_removed + " products could not be removed. If products cannot be removed it's most probably because they are used in quotes.");
								location.reload(true);
							} else {
								showError('Could not delete products. Please try again. If the problem persists, please contact support.');
							}
						}
					);
				}
			)
			
		}
	</script>
@stop

@section('content')
	<div class="frame">
		<div class="bit-4">
			<div class="container">
				<div class="container_title blue">
					Search products
				</div>
				<div class="container_content">
					<h3 class='underlined'>Search by name</h3>
					<input type="text" id='searchQuery' style='width: 100%;' placeholder='Search...' id='searchQuery'>
					<br><br>
					<h3 class='underlined'>Search by category</h3>
					<select name='category'id='category' style='width: 100%;'>
						<option value="" disabled selected='selected'>Select a category</option>
						@foreach($categories as $category)
							<option value="{{ $category->id }}">{{ $category->type }}</option>
						@endforeach
					</select>
					<br><br>
					<h3 class='underlined'>A-Z listing</h3>
					@foreach($azAll as $val)
						<button style='width: 25px; font-size: 10pt; margin: 3px; font-weight:bold; height: 25px; padding: 0;' class='btn btn-default fl' onClick='showAz("{{ $val }}");'>{{ $val }}</button>
					@endforeach
					<button style='width: 35px; font-size: 10pt; margin: 3px; font-weight:bold; height: 25px; padding: 0;' class='btn btn-default' onClick='showAz("all");'>ALL</button>
					<br clear='all'><br>
					@if(Auth::user()->hasPermission('product_create'))
						<button class="btn btn-green" data-href='products/create'>
							<i class="fa fa-plus"></i>
							Create new product
						</button>
					@endif
				</div>
			</div>
		</div>
		<div class="bit-75">
			<div class="container" id='product_results_container'>
				<div class="container_title green">
					Product results
					<button class="btn btn-red btn-square fr" onclick="confirmMultiRemoval()" data-tooltip="Delete all the selected products"><i class="fa fa-trash-o"></i></button>
					<button class="btn btn-orange btn-square fr mr5" onclick="confirmMultiArchival()" data-tooltip="Discontinue all the selected products"><i class="fa fa-archive"></i></button>
					<span class="fr">Selected:&nbsp;&nbsp;</span>
				</div>
				<div class="container_content nop" id='product_results'>
					<table class="data highlight" id='resultsTable'>
						<tr>
							<th style='width: 65%;'>Product name</th>
							<th style='width: 35%;'>Category</th>
							<th style='width: 130px;'>Price</th>
							<th style='width: 130px;'>Actions</th>
						</tr>
						@foreach ($products as $product)
							<tr class='hide'>
								<td>{{ $product->name . (($product->discontinued) ? ' <span style="color:red;">DISCONTINUED</span>' : '') }}</td>
								<td>{{ $categoriesById[$product->category] }}</td>
								<td>&euro; {{ $product->salesPrice }}</td>
								<td>
									<input type="checkbox" name="selected_products[]" value="{{ $product->id }}">
									&nbsp;&nbsp;
									<button onClick='window.location.assign("/products/{{ $product->id }}");' data-tooltip="Edit product" class='btn btn-default btn-square'>
										<i class="fa fa-edit"></i>
									</button>
									<button data-tooltip="Discontinue product" onclick="confirmProductArchival({{ $product->id }});" class='btn btn-orange btn-square'>
										<i class="fa fa-archive"></i>
									</button>
									<button class="btn btn-red btn-square" data-tooltip="Delete product" onClick='confirmProductRemoval({{ $product->id }});'>
										<i class="fa fa-trash-o"></i>
									</button>
								</td>
							</tr>
						@endforeach
					</table>
				</div>
			</div>
		</div>
	</div>
@stop