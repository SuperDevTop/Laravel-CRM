<?php 
	use App\Models\Settings;
	use App\Models\Changelog;
	use App\Models\User;
	use App\Classes\CommonFunctions;
	use Illuminate\Support\Facades\Auth;

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<base href="{{ Request::root() }}">
	<title>{{ Settings::setting('app_name') }} - @yield('page_name')</title>
	<link rel="shortcut icon" type="/image/png" href="/icon.png"/>
	<link rel="stylesheet" href="/css/normalize.css">
	<link rel="stylesheet" href="/css/layout.css">
	<link rel="stylesheet" href="/css/ui_elements.css">
	<link rel="stylesheet" href="/css/grid.css">
	<link rel="stylesheet" href="/css/select2.css">
	<link rel="stylesheet" href="/css/jquery-ui.min.css">
	<link rel="stylesheet" href="/css/search_anything.css">
	<link rel="stylesheet" href="/css/chat.css">
	
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href='//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800'>

	@yield('stylesheets')

	<script type="text/javascript" src="//code.jquery.com/jquery-1.11.2.js"></script>
	<script type="text/javascript" src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.full.js'></script>
	<script type="text/javascript" src='/js/tablesorter.js'></script>
	<script type="text/javascript" src='/js/jquery-ui.min.js'></script>
	<script type="text/javascript" src='/js/jquery.timepicker.min.js'></script>
	<script type="text/javascript" src='/js/howler.min.js'></script>
	<script type="text/javascript" src='/js/autosize.min.js'></script>
	<script type="text/javascript" src='/js/datefunctions.js'></script>
	<script type="text/javascript" src='/js/angularjs.min.js'></script>

	<script type="text/javascript" src="/js/main.js"></script>
	<script type="text/javascript" src='/js/tabs.js'></script>
	<script type="text/javascript" src="/js/basinput.js"></script>
	<script type="text/javascript" src="/js/search_anything.js"></script>
	
	@if(Settings::setting('chatEnabled'))
		
	@endif
	
</head>
<body>
	<div id="tooltip"></div>
	<div id="mask"><div id='mask_see_through'><i class="fa fa-eye"></i></div></div>
	<div id="modal_window">
		<div id="modal_window_header">
			<span id="modal_window_header_title"></span>
			<span id="modal_window_header_close"><i class="fa fa-close"></i></span>
		</div>
		<div id="modal_window_content"></div>
	</div>
	<div class="dialog_window template">
		<div class="dialog_window_header"></div>
		<div class="dialog_window_content"></div>
		<div class="dialog_window_buttons">
			<button class="btn btn-green dialog_window_yes_button">Yep!</button>
			<button class="btn btn-red dialog_window_no_button">Oops, no!</button>
		</div>
	</div>
	<div id="dialog_windows"></div>
	<div id="notification_center">
		<div class="notification dn" id='notification_template'>
			<div class="notification_close"><i class="fa fa-close"></i></div>
			<span class="notification_title"></span><br>
			<span class="notification_message"></span>
			<div class='notification_progress'></div>
		</div>
	</div>
	<div id="media_library">
		<img class='preloader' src='/img/preloader_2.gif' alt="Loading...">
	</div>
	<div id="search_anything">
		<div id="search_anything_input_area">
                	<h1>What are you looking for, {{ Auth::user()->firstname }}?</h1>
			<br>
			<input type="text" id='search_anything_input' placeholder="Don't forget to press enter!">
		</div>
		<div id="search_anything_help">
			<h1><i class="fa fa-exclamation-circle"></i> It really is Search <u>Anything</u> <i class="fa fa-exclamation-circle"></i></h1>
			<h2>You can search with:</h2>
			<br><br>
			<div class='tagcloud'>
				<span>Customer Name</span>
				<span>Supplier Address</span>
				<span>CIF Number</span>
				<span>Supplier Contact</span>
				<span>Telephone Number</span>
				<span>Product Category</span>
				<span>Product Price</span>
				<span>Invoice Total</span>
				<span>Customer IBAN</span>
				<span>Supplier Name</span>
				<span>Initials</span>
				<span>Fax</span>
				<span>Customer Email</span>
			</div>
		</div>
		<div id="search_anything_results">
			<div id="search_anything_loading">
				<img src="img/preloader.gif" alt="Loading...">
			</div>
			<div id="search_anything_results_content"></div>
		</div>
	</div>
	
	@if(Settings::setting('chatEnabled'))
		<div class='chat_box template'>
			<div class="chat_box_header" onclick='toggleChatWindow(this)'>
				<span class='chat_box_buddy_name'></span>
				<span class="close_button" onclick='closeChatWindow(this)'><i class="fa fa-remove"></i></span>
			</div>
			<div class="chat_box_hidden">
				<div class="chat_box_content"></div>
				<div class="chat_box_input">
					<textarea class='chat_box_newmessage_input' rows='1' placeholder='Type your message here...'></textarea>
					<img src='img/smilies/smiley.png' class='chat_box_smiley_button'>
					<div class='chat_box_smiley_window'>
						<div class='chat_box_smiley_container' data-smiley-code=':)'><img src="img/smilies/=).gif"></div>
						<div class='chat_box_smiley_container' data-smiley-code=':D'><img src="img/smilies/=D.gif"></div>
						<div class='chat_box_smiley_container' data-smiley-code=';)'><img src="img/smilies/;).gif"></div>
						<div class='chat_box_smiley_container' data-smiley-code=':P'><img src="img/smilies/=P.gif"></div>
						<div class='chat_box_smiley_container' data-smiley-code=':O'><img src="img/smilies/=O.gif"></div>
						<div class='chat_box_smiley_container' data-smiley-code=':('><img src="img/smilies/=(.gif"></div>
						<div class='chat_box_smiley_container' data-smiley-code=':S'><img src="img/smilies/=S.gif"></div>
						<div class='chat_box_smiley_container' data-smiley-code=":'("><img src="img/smilies/='(.gif"></div>
						<div class='chat_box_smiley_container' data-smiley-code='(L)'><img src="img/smilies/(L).gif"></div>
						<div class='chat_box_smiley_container' data-smiley-code=':$'><img src="img/smilies/=$.gif"></div>
						<div class='chat_box_smiley_container' data-smiley-code=':@'><img src="img/smilies/=@.gif"></div>
						<div class='chat_box_smiley_container' data-smiley-code='(D)'><img src="img/smilies/(D).gif"></div>
						<div class='chat_box_smiley_container' data-smiley-code=':|'><img src="img/smilies/=I.gif"></div>
					</div>
				</div>
			</div>
		</div>
	@endif

	<div id="sidebar">
		<div id="user_menu_sidebar">
		<img id='user_avatar' src="/users/{{ Auth::id() }}/photo?updated={{ md5(Auth::user()->updated_at) }}" alt="Profile Picture">
		<br>
		<span id='user_name'>{{ Auth::user()->firstname . ' ' . Auth::user()->lastname }} <i class="fa fa-caret-down"></i></span>
		<br>
		<span id='user_role'>{{ Auth::user()->getCompanyRole->type }}</span>
		<div id="user_menu">
				<ul>
					<li><a href='/profile'><i class='fa fa-user'></i> My Profile</a></li>
					<li><a href='/myjobs'><i class='fa fa-list'></i> My Jobs</a></li>
					<li><a href='/logout'><i class='fa fa-sign-out'></i> Sign Out</a></li>
				</ul>
			</div>	
		</div>

		<button class="btn btn-default ml10" id='search_anything_btn' style='width: 200px; margin-top: 10px;'><i class="fa fa-search"></i> Search Anything</button>
		<button class="btn btn-orange ml10" data-href='quick-sell' style='width: 200px; margin-top: 10px;'><i class="fa fa-fighter-jet"></i> Quick Sale</button>

		{{ View::make('navigation', array('user' => Auth::user())) }}

		<div id="sidebar_footer">	
			<center>
				<button class="btn btn-orange" data-ajax-modal='get_support_popup' data-tooltip='Are you stuck? Found a bug? Have a question? Click here and we will be happy to help!' data-modal-title='Pepper support' data-modal-width='800' data-modal-closeable='true'>Help & feedback</button>
				<br>
				Powered by <a href='http://businessdevelopment.es/'>Business Development</a>
				<br>
				All Rights Reserved &copy; 2014-2016
			</center>
		</div>
	</div>
	<div id="main_wrapper">
		<div id="top_bar">
			<span style='margin-left: 20px; color: #2f4050; font-weight: bold;'><img src='/img/pepper_logo.png' width='70' style='vertical-align: middle;'> <span style='color: #5598D1; margin-right: 20px;'>&beta;eta</span> {{ Settings::setting('app_name') }}</span>
		 	@if(Request::secure()) 
				<span style="padding: 2px 8px; border-radius: 4px; background-color: #239352; color: white; font-size: 12px; margin-left: 20px;"><i class="fa fa-lock"></i> Secure connection</span>
		 	@endif 
			<div id="top_menu_wrapper">
				<span class='fr'>Good 
				@if(date('G') < 12)
					morning
				@elseif(date('G') >= 12 && date('G') < 19)
					afternoon
				@elseif(date('G') < 23)
					evening
				@else
					night
				@endif
				{{ Auth::user()->firstname }}!</span>
				<ul id="top_menu">
					{{-- <li><a href="#"><i class="fa fa-tasks"></i><span class="badge green">7</span></a></li> --}}
					<li>
						<a href="#" id='product_updates_toggle'><i class="fa fa-bullhorn"></i></a>
						<div id='product_updates_container'>
							<div id="product_updates_title">
								What's new?
							</div>
							<div id='product_updates_inner'>
								<table id="product_updates_table">
									@foreach(Changelog::orderBy('id', 'DESC')->take(10)->get() as $item)
									<tr class='{{ $item->type }}'>
										<td>
											@if ($item->type == 'feature')
												<i class="fa fa-star"></i>
											@elseif ($item->type == 'bug')
												<i class="fa fa-bug"></i>
											@elseif ($item->type == 'speed-improvement')
												<i class="fa fa-rocket"></i>
											@elseif ($item->type == 'visual-update')
												<i class="fa fa-eye"></i>
											@endif
										</td>
										<td>
											<span class='title'>{{ $item->title }}</span> <span class='type'>({{ $item->getType() }})</span><br>
											<span class='created_on'>Added on {{CommonFunctions::formatDate($item->createdOn) }}</span>
											<p>
												{{ $item->description }}
											</p>
											@if ($item->image != null)
											<br>
											<a href='data:image/png;base64,{{ base64_encode($item->image) }}' data-lightbox="image-1" data-title="{{ $item->title }}"><img src='data:image/png;base64,{{ base64_encode($item->image) }}' width='200' style='border-radius: 5px;'></a>
											@endif
										</td>
									</tr>
									@endforeach
								</table>
							</div>
						</div>
					</li>
					<li>
                                		<a href="#" id='reminders_toggle'><i class="fa fa-clock-o"></i><span class="badge red" id="reminder_counter">{{ Auth::user()->getActiveReminderCount() }}</span></a>
						<div id="reminders" class='hidden'>
							<div id="reminder_create" style='display: none; margin-top: 10px;'>
								<div class="containerpadding">
									<h2 class='ml10'>Create reminder</h2>
									<br>
									<table class='form'>
										<tr>
											<td style='width: 100px;'>Page</td>
											<td style='width: 100%;'><input type='text' id='reminder_create_url' readonly='readonly' value='{{ Request::url() }}'></td>
										</tr>
										<tr>
											<td>Title</td>
											<td><input type='text' id='reminder_create_title' maxlength="30"></td>
										</tr>
										<tr>
											<td>Description</td>
											<td><textarea id='reminder_create_description'></textarea></td>
										</tr>
										<tr>
											<td>Date</td>
											<td><input type='text' id='reminder_create_date' class='basinput datetime required' style='background-color: white;'></td>
										</tr>
										<tr>
											<td>Send to</td>
											<td>
												<select id='reminder_create_send_to' class='select2' multiple>
													@foreach(User::getActiveUsers() as $user)
													<option value="{{ $user->id }}">{{ $user->getFullName() }}</option>
													@endforeach	
												</select>
											</td>
										</tr>
										<tr>
											<td>Send to outlook</td>
											<td><input type='checkbox' id='reminder_create_send_to_outlook' value='1'></td>
										</tr>
										<tr>
											<td></td>
											<td>
												<button class="btn btn-green fr ml10" id="reminder_create_save"><i class="fa fa-save"></i> Save reminder</button>
												<button class="btn btn-red fr" id="reminder_create_cancel"><i class="fa fa-remove"></i> Cancel</button>
											</td>
										</tr>
									</table>
								</div>
							</div>
							<div id='reminder_items'>
								<div class="containerpadding">
									<button class="btn btn-green" style='display: block; width: 100%;' id='reminder_create_btn'><i class="fa fa-plus"></i> Create reminder</button>
								</div>
								<div>
								@foreach(Auth::user()->getActiveReminders()->orderBy('reminderDate', 'DESC')->get() as $reminder)
									<div class="reminder" data-reminder-id="{{ $reminder->id }}">
										<table class="tlf">
											<tr>
												<td style='width: 100%;'>
													<span class="reminder_title">{{ $reminder->title }} ({{ CommonFunctions::formatDateTime($reminder->reminderDate) }})</span>
													<p class="reminder_desc">
														{{ nl2br($reminder->description) }}
														<br><br>
														<i class="fa fa-envelope-o"></i> Sent to {{ implode(', ', $reminder->getSentTo()) }}
													</p>
												</td>
												<td style='width: 88px;' style='vertical-align: middle;'>
													<button class="btn btn-default open-btn btn-square" data-tooltip='Open Page' style='margin-top: 5px;' data-href='{{ $reminder->url }}'><i class="fa fa-external-link"></i></button>
													<button class="btn btn-orange snooze-btn btn-square" data-tooltip='Snooze Reminder' style='margin-top: 5px;'><i class="fa fa-bed"></i></button>
													<button class="btn btn-red dismiss-btn btn-square" data-tooltip='Dismiss Reminder' style='margin-top: 5px;'><i class="fa fa-remove"></i></button>
												</td>
											</tr>
											<tr class='reminder_snooze hide'>
												<td colspan='2'>
													<br>
													Snooze for:
													<select class='snooze_duration'>
														<option value="1800">30 minutes</option>
														<option value="3600">1 hour</option>
														<option value="7200">2 hours</option>
														<option value="
														86400">1 day</option>
														<option value="172800">2 days</option>
														<option value="604800">1 week</option>
														<option value="1209600">2 week</option>
														<option value="4233600">1 month</option>
													</select>
													<button class="btn btn-green snooze-confirm"><i class="fa fa-check"></i> Snooze</button>
												</td>
											</tr>
										</table>
									</div>
									@endforeach
								</div>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>
		<div id="main_content">
			<div id="content_header">
				@yield('page_header')
			</div>
			@yield('content')
		</div>
	</div>
	<div id='page_modals'>
		@yield('modals')
	</div>
	@yield('scripts')
</body>
</html>