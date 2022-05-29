@section('page_name')
	Edit variable
@stop

@section('scripts')
	<script>
		function cancelVariable() {
			$.ajax({
				url: 'variables/delete',
				type: 'post',
				dataType: 'json',
				data: {
					type: '{{ $tableName }}',
					id: {{ $variable->id }}
				},
				success: function(data) {
					window.location.assign('/variables');
				}
			});
		}
	</script>
@stop

@section('content')
	<div class="frame">
		<div class="bit-3">
			<div class="container">
				<div class="container_title blue">
					Edit {{ $typeTitle }}
				</div>
				<div class="container_content">
					{{ Form::model($variable, array('route' => array('variables.update', $variable->id), 'method' => 'PUT')) }}
						<table class='form' style='width: 100%;'>
							<tr>
								<td style='width: 100px;'><label>Type</label></td>
								<td>{{ Form::text('type') }}</td>
							</tr>
							@foreach ($additionalFields as $additionalField)
								<tr>
									<td><label>{{ $additionalField['label'] }}</label></td>
									<td>{{ Form::text($additionalField['name']) }}</td>
								</tr>
							@endforeach
							@if($discontinuedField == true)
								<tr>
									<td><label>Discontinued</label></td>
									<td>
										{{ Form::hidden('discontinued', 0) }}
										{{ Form::checkbox('discontinued', 1) }}
									</td>
								</tr>
							@endif
						</table>
						{{ Form::hidden('varType', $tableName) }}
						{{ Form::hidden('id', $variable->id )}}
						{{ Form::submit('Save variable', array('class' => 'blue fr')) }}
						@if($id == -1)
						<button class='red fr' type='button' onClick='cancelVariable();'>Cancel</button>
						@endif
					{{ Form::close() }}
					<br><br>
				</div>
			</div>
		</div>
	</div>
@stop