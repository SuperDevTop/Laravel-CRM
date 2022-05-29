{{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.3/socket.io.js') }}

<?php 
	use App\Models\User;
	use Illuminate\Support\Facades\Auth;
?>

<script>
	// First, get a list of all the active users (id + fullname)
	var usernames = {
		@foreach(User::getActiveUsers() as $user)
			{{ $user->id }}: '{{ $user->getFullname() }}',
		@endforeach
	}
	// The socket
	var enableDebug = true;
	var socket;

	var ding = new Howl({
	  urls: ['/sounds/ding.wav']
	});

	var chatboxCount = 0;
	var chatboxMarginRightOffset = 280;

	var chatHistoryCount = 0;

	var connected = false;

	$(function() {

		// Connect to the chat server
		connect();

		// Set the interval that sends the heartbeat to the server every 2 seconds
		setInterval(function() {
			if (socket != undefined)
				socket.emit('hb');
		}, 2000);
	});

	$(function() {
		$('body').on('click', '.chat_box_smiley_container', function(event) {
			var $smileyWindow = $(this).closest('.chat_box_smiley_window');
			var $chatbox = $(this).closest('.chat_box');
			var $input = $chatbox.find('.chat_box_newmessage_input').first();

			$input.val($input.val() + $(this).attr('data-smiley-code') + ' ');
			//$input.html($input.html() + '<img src="img/smilies/=D.gif">');
			$smileyWindow.fadeOut(100);
			$input.focus();
			placeCaretAtEnd($input.get(0));
		});

		$('body').on('click', '.chat_box_smiley_button', function(event) {
			var $smileyWindow = $(this).closest('.chat_box').find('.chat_box_smiley_window').first();
			if ($smileyWindow.is(':visible')) {
				$smileyWindow.fadeOut(100);
			} else {
				$smileyWindow.fadeIn(100);
			}
		});

		$('#chat_navigation_ul >li>a#newchat input').on('focus', function(event) {

			$('#chat_navigation_ul>li.chat_list_user').each(function(index, element) {
				$element = $(element);

				$element.hide();
			});

			$(this).attr('placeholder', 'Search collegue...');
		});

		$('#chat_navigation_ul >li>a#newchat input').on('blur', function(event) {

			$('#chat_navigation_ul>li.chat_list_user').each(function(index, element) {
				$element = $(element);

				if ($element.hasClass('online')) {
					$element.show();
				} else {
					$element.hide();
				}
			});

			$(this).attr('placeholder', 'Offline users');
			$(this).val('');
		});

		$('#chat_navigation_ul >li>a#newchat input').on('keyup', function(event) {
			var query = $(this).val();
			$('#chat_navigation_ul>li.chat_list_user.offline').each(function(index, element) {
				$element = $(element);
				$atag = $element.find('a').first();
				if ($atag.html().indexOf(query) > -1 && query != '') {
					$element.show();
				} else {
					$element.hide();
				}
			});
		});

		$('.chat_list_user').on('mousedown', function(event) {
			if (event.button != 0 && event.button)
				return;

			event.preventDefault();

			var $chatUser = $(this);

			var $existingWindow = $('.chat_box[data-user-id="' + $chatUser.attr('data-user-id') + '"]');
			if ($existingWindow.length != 0) {
				$existingWindow.first().find('.chat_box_hidden').slideDown();
				$existingWindow.find('input[type="text"]').first().focus();
				return;
			}

			debug('Starting chat with UserId ' + $chatUser.attr('data-user-id'));

			socket.emit('start_chat', { userId: $chatUser.attr('data-user-id') });

			// Clone the template, place it, and slide it up
			var $template = $('.chat_box.template').clone();

			var $body = $('body');
			var $newChatbox = $($template).appendTo('body');

			$newChatbox.removeClass('template');
			$newChatbox.css('right', (30 + (chatboxCount * chatboxMarginRightOffset)) + 'px');
			chatboxCount++;

			$newChatbox.find('.chat_box_buddy_name').first().html(usernames[$chatUser.data('user-id')]);
			$newChatbox.find('.chat_box_hidden').first().slideDown();
			$newChatbox.attr('data-user-id', $chatUser.attr('data-user-id'));
			$newChatbox.attr('data-loaded-messages', chatHistoryCount);

			$contentArea = $newChatbox.find('.chat_box_content').first();

			setTimeout(function() {
				$contentArea.scrollTop($contentArea[0].scrollHeight);	
			}, 600);

			$contentArea.scrollTop($contentArea[0].scrollHeight);

			$newChatbox.find('.chat_box_newmessage_input').focus();
			autosize($newChatbox.find('.chat_box_newmessage_input'));

			if ($chatUser.hasClass('online')) {
				$newChatbox.find('.chat_box_header').first().addClass('online');
			} else {
				$newChatbox.find('.chat_box_header').first().addClass('offline');
			}
		});

		$('body').on('click', '.chat_box_newmessage_input, .chat_box_content', function(event) {
			$('.chat_box_smiley_window').fadeOut(100);
		});

		$('body').on('keydown', '.chat_box_newmessage_input', function(event) {
			if (event.which != 13)
				return;
			
			event.preventDefault();

			if ($(this).val() == '')
				return;

			// Find the parent chatbox element
			var $chatbox = $(this).closest('.chat_box');

			// Sanitize input
			var message = $(this).val();
			message = message.replace(/script/g, '');

			// Emit that message to the server
			socket.emit('chat_message', { receiver: $chatbox.attr('data-user-id'), 'message': message });

			// Append my text
			appendToChatWindow($chatbox.attr('data-user-id'), message, true, getCurrentDateTime());

			// Clear the textbox
			$(this).val('');
		});

		$('body').on('click', '.chat_box_load_older_messages', function(event) {
			var $chatbox = $(this).closest('.chat_box');
			socket.emit(
				'load_older_messages',
				{
					buddyId: $chatbox.data('user-id'),
					loaded: $chatbox.attr('data-loaded-messages')
				}
			);
			$(this).html('<i class="fa fa-spinner fa-spin"></i> Loading...');
			$(this).attr('disabled', 'disabled');
		});
	});

	function escapeRegExp(string) {
	    return string.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
	}

	function replaceAll(string, find, replace) {
	  return string.replace(new RegExp(escapeRegExp(find), 'g'), replace);
	}	

	function debug(text) {
		if (enableDebug)
			console.log('DEBUG: ' + text);
	}

	function connect() {
		debug('Connecting...');
		// localStorage.debug='*';
		socket = io.connect('{{ Request::root() }}:1337', { secure: true });

		socket.on('connect', function(data) {
			if (connected) {
				// Right, the server restarted. NICE. Get rid of all the chatboxes, and reset variables
				chatboxCount = 0;
				chatHistoryCount = 0;

				$('.chat_box').remove();
			}

			connected = true;
			$('#chat_status').html('Logging in to chat server...');
			debug('Connected to server, sending handshake...');
			socket.emit('handshake', {
				id: {{ Auth::id() }},
				installationId: '{{ Settings::setting("installationId") }}'
			});
		});

		// Send by the server after client handshake. Sends open chats
		socket.on('server_handshake', function(data) {
			console.log(data);
			$('#chat_user_count').html(data.onlineUsers.length);
			if (data.onlineUsers) {
				data.onlineUsers.forEach(function(user) {
					$('.chat_list_user[data-user-id="' + user.userId + '"]').removeClass('offline').addClass('online');
				});
			}

			// Set chatHistoryCount var
			chatHistoryCount = data.chatHistoryCount;

			// Open chat windows
			if (data.openChats) {
				data.openChats.forEach(function(chat) {
					var buddyId = chat.buddyId;
					$chatUser = $('.chat_list_user[data-user-id="' + buddyId + '"]');
					var $template = $('.chat_box.template').clone();

					var $body = $('body');
					var $newChatbox = $template.appendTo('body');

					$newChatbox.removeClass('template');
					$newChatbox.css('right', (30 + (chatboxCount * chatboxMarginRightOffset)) + 'px');
					chatboxCount++;

					$newChatbox.find('.chat_box_buddy_name').first().html(usernames[buddyId]);
					//$newChatbox.find('.chat_box_hidden').first().slideDown();
					$newChatbox.find('input[type="text"]').first().focus();
					$newChatbox.attr('data-user-id', buddyId);
					$newChatbox.attr('data-loaded-messages', chatHistoryCount);

					if ($chatUser.hasClass('online')) {
						$newChatbox.find('.chat_box_header').first().addClass('online');
					} else {
						$newChatbox.find('.chat_box_header').first().addClass('offline');
					}

					autosize($newChatbox.find('.chat_box_newmessage_input'));
					var $contentArea = $newChatbox.find('.chat_box_content').first();

					var flash = false;

					// If there are more messages to be loaded, show the load more messages button
					if (chat.more == true)
						$contentArea.append('<button class="btn btn-green fw chat_box_load_older_messages"><i class="fa fa-download"></i> Load older messages...</button>');

					chat.lastMessages.forEach(function(message) {
						if (message.toMe == 1) {
							appendToChatWindow(buddyId, message.message, false, formatMysqlDateTime(message.sentOn));
						} else {
							appendToChatWindow(buddyId, message.message, true, formatMysqlDateTime(message.sentOn));
						}
						if (message.read == 0 && message.toMe == true) {
							flash = true;
						}
					});
					if (flash) {
						ding.play();
						$newChatbox.find('.chat_box_hidden').first().slideDown('400');
						$contentArea.scrollTop($contentArea[0].scrollHeight);
						$newChatbox.find('.chat_box_header').first().fadeIn(150).fadeOut(150).fadeIn(150).fadeOut(150).fadeIn(150).fadeOut(150).fadeIn(150).fadeOut(150).fadeIn(150);
					}
				});
			}

			$('#chat_status').hide();
			$('#chat_main_container').show();
		});

		// Sent by server on hb
		socket.on('hb', function(data) {
			data.onlineUsers.forEach(function(user) {
				$('#chat_users_user[data-user-id="' + user + '"]').removeClass('offline').addClass('online');
				$('#chat_box[data-user-id="' + user + '"]').removeClass('offline').addClass('online');
			});
		});

		// Sent by the server after opening a chat. Contains the latest x messages
		socket.on('start_chat', function(data) {
			if (data.more == true)
				$contentArea.append('<button class="btn btn-green fw chat_box_load_older_messages"><i class="fa fa-download"></i> Load older messages...</button>');
			data.messages.forEach(function(message) {
				if (message.toMe == 1) {
					appendToChatWindow(data.userId, message.message, false, message.sentOn);
				} else {
					appendToChatWindow(data.userId, message.message, true, message.sentOn);
				}
			});
		});

		// Disconnect when connecting failed
		socket.on('connect_error', function(data) {
			debug('Could not connect to chat server');
			socket.disconnect();
		});

		socket.on('message', function(data) {

			// Check if windows exists
			$chatWindow = $('.chat_box[data-user-id="' + data.sender + '"]');
			if ($chatWindow.length == 0) {
				// Start chat
				$('.chat_users_user[data-user-id="' + data.sender + '"]').trigger('click');
				$chatWindow = $('.chat_box[data-user-id="' + data.sender + '"]');
				var $template = $('.chat_box.template').clone();

				var $body = $('body');
				var $newChatbox = $($template).appendTo('body');

				$newChatbox.removeClass('template');
				$newChatbox.css('right', (30 + (chatboxCount * chatboxMarginRightOffset)) + 'px');
				chatboxCount++;

				$newChatbox.find('.chat_box_buddy_name').first().html(usernames[data.sender]);
				$newChatbox.find('.chat_box_hidden').first().slideDown();
				$newChatbox.find('input[type="text"]').first().focus();
				$newChatbox.attr('data-user-id', data.sender);

				$newChatbox.find('.chat_box_header').first().addClass('online');
				autosize($newChatbox.find('.chat_box_newmessage_input'));

				$chatWindow = $newChatbox;
				ding.play();
			}

			console.log('Message received from UserId ' + data.sender + ': ' + data.message);
			appendToChatWindow(data.sender, data.message, false, getCurrentDateTime());

			// Check if we should play the sound...
			$chatWindow = $('.chat_box[data-user-id="' + data.sender + '"]');
			if (!$chatWindow.find('.chat_box_hidden').first().is(':visible')) {
				ding.play();
				$chatWindow.find('.chat_box_hidden').first().slideDown();
				var $contentArea = $chatWindow.find('.chat_box_content').first();
				$contentArea.scrollTop($contentArea[0].scrollHeight);
				$chatWindow.find('.chat_box_header').first().fadeIn(150).fadeOut(150).fadeIn(150).fadeOut(150).fadeIn(150).fadeOut(150).fadeIn(150).fadeOut(150).fadeIn(150);
			}
		});

		socket.on('load_older_messages', function(data) {
			var $chatbox = $('.chat_box[data-user-id="' + data.buddyId + '"]');
			if ($chatbox.length != 1)
				return;

			var $contentArea = $chatbox.find('.chat_box_content').first();
			$contentArea.find('button').remove();

			data.messages.forEach(function(message) {
				if (message.toMe == 1) {
					prependToChatWindow(data.buddyId, message.message, true, formatMysqlDateTime(message.sentOn));
				} else {
					prependToChatWindow(data.buddyId, message.message, false, formatMysqlDateTime(message.sentOn));
				}
			});


			var newLoaded = (parseInt($chatbox.attr('data-loaded-messages')) + data.messages.length);
			console.log(newLoaded);
			$chatbox.attr('data-loaded-messages', newLoaded);

			if (data.more == true)
				$contentArea.prepend('<button class="btn btn-green fw chat_box_load_older_messages"><i class="fa fa-download"></i> Load older messages...</button>');
		});

		socket.on('user_online', function(data) {
			$('#chat_navigation_ul .chat_list_user[data-user-id="' + data.userId + '"]').removeClass('offline').addClass('online');
			$('.chat_box[data-user-id="' + data.userId + '"]').find('.chat_box_header').first().removeClass('offline').addClass('online');
			$('#chat_user_count').html(parseInt($('#chat_user_count').html()) + 1);
			appendToChatWindow(data.userId, 'I just logged in!', false, getCurrentDateTime());
		});

		socket.on('user_offline', function(data) {
			$('#chat_navigation_ul .chat_list_user[data-user-id="' + data.userId + '"]').removeClass('online').addClass('offline');
			$('.chat_box[data-user-id="' + data.userId + '"]').find('.chat_box_header').first().removeClass('online').addClass('offline');
			$('#chat_user_count').html(parseInt($('#chat_user_count').html()) - 1);
			appendToChatWindow(data.userId, 'I just logged out!', false, getCurrentDateTime());
		});

		socket.on('own_message', function(data) {
			console.log('Received own message: ' + data.message);
			appendToChatWindow(data.userId, data.message, true, getCurrentDateTime());
		});
	}

	function appendToChatWindow(buddyId, content, toMe, datetime) {
		content = content.replace(/\(D\)/g, '<img src="img/smilies/(D).gif"/>');
		content = content.replace(/\(L\)/g, '<img src="img/smilies/(L).gif"/>');
		content = content.replace(/\;\)/g, '<img src="img/smilies/;).gif"/>');
		content = content.replace(/\:\$/g, "<img src=\"img/smilies/=$.gif\"/>");
		content = content.replace(/\:\(/g, '<img src="img/smilies/=(.gif"/>');
		content = content.replace(/\:\'\(/g, "<img src=\"img/smilies/='(.gif\"/>");
		content = content.replace(/\:\)/g, '<img src="img/smilies/=).gif"/>');
		content = content.replace(/\:\@/g, '<img src="img/smilies/=@.gif"/>');
		content = content.replace(/\:D/g, '<img src="img/smilies/=D.gif"/>');
		content = content.replace(/\:\|/g, '<img src="img/smilies/=I.gif"/>');
		content = content.replace(/\:O/g, '<img src="img/smilies/=O.gif"/>');
		content = content.replace(/\:P/g, '<img src="img/smilies/=P.gif"/>');
		content = content.replace(/\:S/g, '<img src="img/smilies/=S.gif"/>');
		content = content.replace(/\(C\)/g, '<img src="img/smilies/(C).gif"/>');

		$chatbox = $('.chat_box[data-user-id="' + buddyId + '"]');

		if ($chatbox.length == 0)
			return;

		var $contentArea = $chatbox.find('.chat_box_content');

		if (toMe) {
			content = '<img src="users/{{ Auth::id() }}/photo" class="me"><div class="chat_box_message me">' + content + '<div class="chat_box_message_timestamp">' + datetime + '</div></div><br clear="both">';
		} else {
			content = '<img src="users/' + buddyId + '/photo" class="buddy"><div class="chat_box_message buddy">' + content + '<div class="chat_box_message_timestamp">' + datetime + '</div></div><br clear="both">';
		}

		$contentArea = $contentArea.first();
		$contentArea.append(content);
		$contentArea.scrollTop($contentArea[0].scrollHeight);
	}

	function prependToChatWindow(buddyId, content, toMe, datetime) {
		content = content.replace(/\(D\)/g, '<img src="img/smilies/(D).gif"/>');
		content = content.replace(/\(L\)/g, '<img src="img/smilies/(L).gif"/>');
		content = content.replace(/\;\)/g, '<img src="img/smilies/;).gif"/>');
		content = content.replace(/\:\$/g, "<img src=\"img/smilies/=$.gif\"/>");
		content = content.replace(/\:\(/g, '<img src="img/smilies/=(.gif"/>');
		content = content.replace(/\:\'\(/g, "<img src=\"img/smilies/='(.gif\"/>");
		content = content.replace(/\:\)/g, '<img src="img/smilies/=).gif"/>');
		content = content.replace(/\:\@/g, '<img src="img/smilies/=@.gif"/>');
		content = content.replace(/\:D/g, '<img src="img/smilies/=D.gif"/>');
		content = content.replace(/\:\|/g, '<img src="img/smilies/=I.gif"/>');
		content = content.replace(/\:O/g, '<img src="img/smilies/=O.gif"/>');
		content = content.replace(/\:P/g, '<img src="img/smilies/=P.gif"/>');
		content = content.replace(/\:S/g, '<img src="img/smilies/=S.gif"/>');
		content = content.replace(/\(C\)/g, '<img src="img/smilies/(C).gif"/>');

		$chatbox = $('.chat_box[data-user-id="' + buddyId + '"]');

		if ($chatbox.length == 0)
			return;

		var $contentArea = $chatbox.find('.chat_box_content');

		if (toMe) {
			content = '<img src="users/{{ Auth::id() }}/photo" class="me"><div class="chat_box_message me">' + content +'<div class="chat_box_message_timestamp">' + datetime + '</div></div><br clear="both">';
		} else {
			content = '<img src="users/' + buddyId + '/photo" class="buddy"><div class="chat_box_message buddy">' + content + '<div class="chat_box_message_timestamp">' + datetime + '</div></div><br clear="both">';
		}

		$contentArea = $contentArea.first();
		$contentArea.prepend(content);
	}

	function disconnect() {
		socket.disconnect();
	}

	function toggleChatWindow(button) {
		var $button = $(button);
		var $chatWindow = $button.closest('.chat_box');
		var $hiddenPart = $chatWindow.find('.chat_box_hidden');
		if ($hiddenPart.is(':visible')) {
			$hiddenPart.slideUp();
		} else {
			$hiddenPart.slideDown(400);

			$chatWindow.find('.chat_box_input input[type="text"]').first().focus();

			var $contentArea = $chatWindow.find('.chat_box_content').first();
			$contentArea.animate({ scrollTop: $contentArea[0].scrollHeight }, 400);
		}
	}

	function closeChatWindow(button) {
		var $chatWindow = $(button).closest('.chat_box');

		// Send to server
		socket.emit('stop_chat', {
			userId: $chatWindow.attr('data-user-id')
		})

		// Remove chatbox
		removeChatbox($chatWindow);
	}

	// Set the cursor to the end of a content-editable div
	function placeCaretAtEnd(el) {
	    if (typeof window.getSelection != "undefined"
	            && typeof document.createRange != "undefined") {
	        var range = document.createRange();
	        range.selectNodeContents(el);
	        range.collapse(false);
	        var sel = window.getSelection();
	        sel.removeAllRanges();
	        sel.addRange(range);
	    } else if (typeof document.body.createTextRange != "undefined") {
	        var textRange = document.body.createTextRange();
	        textRange.moveToElementText(el);
	        textRange.collapse(false);
	        textRange.select();
	    }
	}

	// Removes the chatbox, and reallignes the existing ones
	function removeChatbox($removeChatbox) {
		var originalRight = parseInt($removeChatbox.css('right'));
		console.log('Removed right: ' + originalRight);

		$removeChatbox.remove();
		chatboxCount--;

		$('.chat_box').each(function() {
			var $chatbox = $(this);

			var right = parseInt($chatbox.css('right'));
			console.log('Chatbox right: ' + right);
			if (right > originalRight) {
				console.log('Right higher!');
				$chatbox.animate({
					'right': (right - chatboxMarginRightOffset) + 'px'
				}, {
					duration: 800,
					easing: 'easeOutBounce'
				});
			}
		});
	}

	// t: current time, b: begInnIng value, c: change In value, d: duration
	jQuery.easing['jswing'] = jQuery.easing['swing'];

	jQuery.extend( jQuery.easing,
	{
		def: 'easeOutQuad',
		easeOutBounce: function (x, t, b, c, d) {
			if ((t/=d) < (1/2.75)) {
				return c*(7.5625*t*t) + b;
			} else if (t < (2/2.75)) {
				return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
			} else if (t < (2.5/2.75)) {
				return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
			} else {
				return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
			}
		}
	});
</script>