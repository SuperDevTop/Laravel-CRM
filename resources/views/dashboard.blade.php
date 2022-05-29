@extends("master")

@section("page_header")
	<h3>Welcome back, {{ Auth::user()->getFullname() }}</h3>
@stop

@section('scripts')
	<script src='js/lightbox.min.js'></script>
@stop

@section('stylesheets')
	<link rel="stylesheet" href="css/lightbox.css">
@stop

@section("content")
	<div class="frame">
		<div class="bit-1">
			<div class="container">
				<div class="container_title orange">
					<i class="fa fa-list-ol"></i> Jobs in progress
				</div>
				<div class="container_content nop">
					<table class="data highlight sortable">
						
					</table>
				</div>
			</div>
		</div>
	</div>
@stop