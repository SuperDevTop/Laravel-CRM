@extends('master')
<?php 
	use App\Classes\CommonFunctions;
?>@section('page_name')
	{{ $supplier->companyName }}
@stop

@section('breadcrumbs')
	Suppliers <i class="breadcrumb"></i> {{ $supplier->getDisplayName() }}
@stop

@section('page_header')
	<button class="btn btn-orange fr" data-href='suppliers/{{ $supplier->id}}/edit'>
		<i class="fa fa-edit"></i> Edit supplier
	</button>
@stop

@section('scripts')
	<script type='text/javascript' src='ckeditor/ckeditor.js'></script>
	<script type='text/javascript'>
		$(function() {
    		CKEDITOR.env.isCompatible = true;
			CKEDITOR.replace('notes', {
				toolbar: [
					['FontSize'],
					['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Paste'],
					['NumberedList','BulletedList'],
					['Image','Table','-','Link'],
					['Maximize', 'Print']
				],
				height: 187,
    			removePlugins: 'elementspath',
    			extraPlugins: 'print',
    			resize_enabled: false,
		        enterMode : CKEDITOR.ENTER_BR,
		        shiftEnterMode: CKEDITOR.ENTER_P
			});

			$('#contacthistory_newMessage').on('keyup', function(e) {
				if (e.which == 13) $('#contacthistory_saveNewMessage').trigger('click');
			});

			$('#contacthistory_saveNewMessage').on('click', function() {

				if ($('#contacthistory_newMessage').val().length == 0) return;
				var supplierId = {{ $supplier->id }};
				var message = $('#contacthistory_newMessage').val();
				var response = ajaxRequest('supplier_contacthistory_newMessage', { supplierId: supplierId, message: message }, function(response) {
					$('#contacthistory_table tr:first').after(response.new_row);
					$('#contacthistory_newMessage').val('');
				});
			});
		});

		function saveNotes() {
			ajaxRequest(
				'save_supplier_notes',
				{
					supplierId: {{ $supplier->id }},
					notes: CKEDITOR.instances.notes.getData()
				},
				function(data) {
					if (data.success) {
						showSuccess('Notes saved succesfully');
					}
				}
			);
		}
	</script>
@stop

@section('content')
	<div class="frame">
		<div class="bit-3">
			<div class="container">
				<div class="container_title blue">
					Contact information
				</div>
				<div class="container_content" style='height: 230px;'>
					<i class="fa fa-building fixedwidth"></i> {{ $supplier->getDisplayName() }}
							<br>
							<i class="fa fa-location-arrow fl fixedwidth"></i>
							<div class='fl' style='margin-left: 4px;'> 
								{{ nl2br($supplier->address) }}<br>
								{{ $supplier->postalCode }},<br>
								{{ $supplier->city }},<br>
								{{ $supplier->region }}
							</div>
							<br clear='all'>
							@if ($supplier->phone != '')
								<i class="fa fa-phone fixedwidth"></i> {{ $supplier->phone }}
								<br>
							@endif
							@if ($supplier->mobile != '')
								<i class="fa fa-mobile fixedwidth"></i> {{ $supplier->mobile }}
								<br>
							@endif
							@if ($supplier->email != '')
								<i class="fa fa-envelope fixedwidth"></i> <a href='mailto:{{ $supplier->email }}'>{{ $supplier->email }}</a>
								<br>
							@endif
							@if ($supplier->website != '')
								<i class="fa fa-at fixedwidth"></i> <a href='{{ $supplier->website }}'>{{ $supplier->website }}</a>
								<br>
							@endif
							@if ($supplier->cifnif != '')
								<i class="fa fa-star fixedwidth"></i> CIF: {{ $supplier->cifnif }}
								<br>
							@endif
							@if ($supplier->paymentTerms != 1)
								<i class="fa fa-th-list fixedwidth"></i> Payment Terms: {{ $supplier->getPaymentTerms->type }}
								<br>
							@endif
							@if ($supplier->paymentType != 1)
								<i class="fa fa-money fixedwidth"></i> Payment Method: {{ $supplier->getPaymentType->type }}
								<br>
							@endif
							<i class="fa fa-money fixedwidth"></i> Currency: {{ $supplier->getCurrency->name }}
				</div>
			</div>
		</div>
		<div class="bit-3">
			<div class="container">
				<div class="container_title blue">
					Notes
					<button class='btn btn-green fr' onclick='saveNotes();'><i class="fa fa-save"></i> Save</button>
				</div>
				<div class="container_content nop" style='height: 210px;'>
					<textarea id='notes' style='width: 100%; height: 100%;'>{{ $supplier->notes }}</textarea>
				</div>
			</div>
		</div>
	</div>
	<div class="frame">
		<div class="bit-1">
			<div class="container">
				<div class="container_title orange">
					Supplier Information
					<ul>
						<li data-tab='contacthistory' class='active'>Contact History</li>
					</ul>
				</div>
				<div class="container_content nop">
					<div class="active" style='max-height: 300px;' data-tab='contacthistory'>
						<div class="containerpadding">
							<input type="text" id='contacthistory_newMessage' placeholder='New message...' style='width: 600px'>
							<button class='btn btn-green' id='contacthistory_saveNewMessage'>
								<i class="fa fa-save"></i> Save message
							</button>
						</div>
						<table class="data highlight"  id='contacthistory_table'>
							<tr>
								<th style='width: 180px;'>Date/Time</th>
								<th style='width: 200px;'>User</th>
								<th style='width: 100%'>Message</th>
							</tr>
							@foreach($supplier->getContactHistory as $contactItem)
							<tr>
								<td>{{ CommonFunctions::formatDatetime($contactItem->placedOn) }}</td>
								<td>{{ User::find($contactItem->placedBy)->getFullname() }}</td>
								<td>{{ $contactItem->message }}</td>
							</tr>
							@endforeach
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop