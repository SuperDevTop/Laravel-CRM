<script>
	$(function() {
		$('.left_menu li').on('click', function(event) {
			$('.tab').hide();
			$('.tab#' + $(this).data('tab')).show();
			$('.left_menu li').removeClass('active');
			$(this).addClass('active');
		});

		$('#quick-support').show();

		$('#support_chat_open_btn').on('click', function() {
			$('#modal_window_header_close').trigger('click');
		});

		$('#send_question_btn').on('click', function() {
			ajaxRequest(
				'save_support_question',
				{
					question: $('#question_textarea').val()
				},
				function(data) {
					if (data.success) {
						$('#modal_window_header_close').trigger('click')
						showAlert('<i class="fa fa-check"></i> Question sent', 'Your question has been sent to the Pepper CRM support team. The email address we will contact you on is {{ Auth::user()->companyEmail }}. Need immediate support? Call us on workdays between 10 AM and 7 PM.');
					}
				}
			);
		});

		$('#send_bug_btn').on('click', function() {
			ajaxRequest(
				'save_support_feedback',
				{
					type: $('input[name="feedback-type"]:checked').val(),
					feedback: $('#feedback_textarea').val()
				},
				function(data) {
					if (data.success) {
						$('#modal_window_header_close').trigger('click')
						showAlert('<i class="fa fa-check"></i> Feedback sent', 'Thank you for your feedback. We\'re always trying to improve and your feedback is an invaluable part of that.<br><br>If we respond we\'ll contact you on {{ Auth::user()->companyEmail }}.');
					}
				}
			);
		});
	});
	</script>

<style>
	.sidebar {
		float: left;
		width: 200px;
	}

	.left_menu {
		list-style-type: none;
	}

	.left_menu li {
		padding-left: 10px;
		display: block;
		height: 30px;
		line-height: 30px;
		width: 100%;
		border-bottom: 1px solid #A7A7A7;

		cursor: pointer;
	}

	.left_menu li.active {
		background-color: #F88234;
		color: white;
		font-weight: bold;
	}

	.left_menu li i {
		width: 20px;
		text-align: center;
	}

	.left_menu li:hover {
		background-color: #D6D6D6;
	}

	.content {
		position: relative;
	
		border-left: 1px solid #A7A7A7;

		padding: 15px;
	}

	.content input[type='text'], .content select, .content input[type='password'] {
		width: 400px;
	}

	.content h1 {
		margin: 0;

		margin-bottom: 20px;
	}

	.content .tab {
		display: none;
	}

	.left_menu td {
		height: 50px;
	}

	.template-thumb {
		float: left;
		border: 2px solid gray;
		margin-bottom: 10px;
	}

	.template-thumb:not(:last-child) {
		margin-right: 10px;
	}

	.template-thumb button.active {
		background-color: #00b102;
	}

	.template-thumb h2 {
		font-size: 12pt;
		text-align:center;
		margin: 5px;
	}
</style>

<div class='sidebar'>
	<ul class='left_menu'>
		<li data-tab='quick-support' class='active'>
			<i class="fa fa-fighter-jet"></i> Quick support
		</li>
		<li data-tab='question'>
			<i class="fa fa-question-circle"></i> Ask a question
		</li>
		<li data-tab='feedback'>
			<i class="fa fa-bullhorn"></i> Give feedback
		</li>
	</ul>
</div>
<div class='content' id='config_content' style='overflow: auto;'>
	<div class="tab" id="quick-support">
		<h1><i class="fa fa-fighter-jet"></i> Quick support</h1>
		<p>
			During workdays we're always ready to help you here at Pepper. If you need quick support, please use one of the following options:
		</p>

		<br>

		<div class="tile green w-200 fl ml10" style='cursor: pointer;' onclick='location.href="mailto:support@pepper-crm.com";'>
			<i class="tile_icon fa fa-envelope-o"></i>
			<span class="tile_title">Email us</span>
			<span class='tile_content'>support@pepper-crm.com</span>
		</div>

		@if(date('H') >= 10 && date('H') <= 19 && date('N') < 6)
		<div class="tile green w-200 fl ml10">
			<i class="tile_icon fa fa-phone"></i>
			<span class="tile_title">Call us</span>
			<span class='tile_content'>902 007 202 / 952 591 071</span>
		</div>
		@else
		<div class="tile grey w-200 fl ml10">
			<i class="tile_icon fa fa-phone"></i>
			<span class="tile_title">Call us</span>
			<span class='tile_content'>You can call us on workdays from 10 till 7</span>
		</div>
		@endif

		<br clear='both'><br>

		<p>
			<i class="fa fa-info-circle"></i> You can also send us an email by clicking 'Ask a question' on the left hand side menu.
		</p>
		<p>Pepper CRM version <b>{{ file_get_contents(base_path() . '/version.txt') }}</b></p>
	</div>

	<div class="tab" id="question">
		<h1><i class="fa fa-question-circle"></i> Ask a question</h1>
		<p>
			Do you have a question about Pepper? Ask us here! We'll try to answer your question as soon as possible (usually within 1 working day).
		</p>
		<br>
		<table class="form">
			<tr>
				<td style='width: 100px;'>Question</td>
				<td style='width: 100%;'>
					<textarea name='question' id='question_textarea' style='width: 100%; height: 100px;'></textarea>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<button class="btn btn-green" id='send_question_btn'>
						<i class="fa fa-paper-plane"></i> Send question
					</button>
				</td>
			</tr>
		</table>
	</div>

	<div class="tab" id="feedback">
		<h1><i class="fa fa-bullhorn"></i> Give feedback</h1>
		<p>
			Here you can report a bug, request a feature or give us general feedback about Pepper. We're always improving and expanding and we would love your feedback!
		</p>
		<br>

		<table class="form">
			<tr>
				<td style='width: 130px; vertical-align: top;'>Feedback type</td>
				<td>
					<input type='radio' name='feedback-type' value='Bug' checked='checked'> I've found a bug!
					<br>
					<input type='radio' name='feedback-type' value='New Feature'> I would like to request a new feature
					<br>
					<input type='radio' name='feedback-type' value='Suggestion'> I have a suggestion!
					<br>
					<input type='radio' name='feedback-type' value='Other/Praise'> Other / praise
				</td>
			</tr>
			<tr>
				<td>Feedback</td>
				<td>
					<textarea name='feedback' id='feedback_textarea' style='width: 100%; height: 90px;'></textarea>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<button class="btn btn-green" id='send_bug_btn'>
						<i class="fa fa-paper-plane"></i> Send feedback
					</button>
				</td>
			</tr>
		</table>
	</div>
</div>