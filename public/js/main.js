$(function() {
	///////////////////////////
	///////////////////////////
	///// LAYOUT RESIZES //////
	///////////////////////////
	///////////////////////////
	// Function that resizes the main content to innerHeight - 40
	function handleResize(event) {
		$('#main_content').css('height', ($(window).innerHeight() - 50) + 'px');
		$('#sidebar').css('max-height', $(window).innerHeight() + 'px');
	}

	// Bind window resize event to handleResize function
	$(window).resize(handleResize);

	// Call on boot
	handleResize();



	///////////////////////////
	///////////////////////////
	/////// SIDEBAR NAV ///////
	///////////////////////////
	///////////////////////////
	$('#nav_menu li a').on('click', function(event) {
		if ($(this).hasClass('active'))
			return;
		
		// Remove .active class from every main menu item and add .active class to clicked menu item
		$('#nav_menu > li > a').removeClass('active');
		$(this).addClass('active');

		// Get the clicked element (a) so we can use it in the function below
		var $clickedElement = $(this);

		// Loop over every submenu, see if it's a sibling of the clickedElement. If it isn't, slide the menu up (hide)
		$('#nav_menu li ul.nav_submenu').each(function(index) {
			if ($clickedElement.next('ul.nav_submenu').get(0) != $(this).get(0) && !$(this).find('a').first().hasClass('current'))
				$(this).slideUp(200);
		});

		// Slide the submenu down (if it exists)
		$(this).parent().find('ul.nav_submenu').slideDown(200);
	});

	$('#nav_menu > li > a.has_sub').on('click', function(event) {
		event.preventDefault();
	});


	///////////////////////////
	///////////////////////////
	//////// USER MENU ////////
	///////////////////////////
	///////////////////////////
	$('#user_menu_sidebar span#user_name').on('click', function(event) {
		// Show the user menu if it's not open yet
		var $userMenu = $('#user_menu');

		if (!$userMenu.is(':visible')) {
			$userMenu.slideDown(100);
		} else {
			$userMenu.slideUp(100);
		}
	});

	$(document).on('click', function(event) {
		if ($('#user_menu').is(':visible') && !$('#user_menu').is(':hover') && !$('#user_menu').is(':animated'))
			$('#user_menu').slideUp(100);
	});

	///////////////////////////
	///////////////////////////
	//////// REMINDERS ////////
	///////////////////////////
	///////////////////////////
	$('.reminder button.snooze-btn').on('click', function(event) {
		$(this).closest('.reminder').find('.reminder_snooze').slideDown(100);
	});

	$(document).on('click', function(event) {
		if ($('#reminders').is(':visible') && !$('#reminders').is(':hover') && !$('#reminders').is(':animated') && !$('#ui-datepicker-div').is(':hover'))
			$('#reminders').slideUp(100);
	});

	$('.reminder button.dismiss-btn').on('click', function(event) {
		var $reminder = $(this).closest('.reminder');
		ajaxRequest(
			'dismiss_reminder',
			{
				reminderId: $reminder.attr('data-reminder-id')
			},
			function(data) {
				if (data.success) {
					$reminder.slideUp(300, function() {
						$reminder.remove();
						var currentReminderCount = parseInt($('#reminder_count').html());
						$('#reminder_count').html(currentReminderCount-1);
					});
				}
			}
		);
	});

	///////////////////////////
	///////////////////////////
	///// Product Updates /////
	///////////////////////////
	///////////////////////////
	$('#product_updates_toggle').on('click', function(event) {
		event.preventDefault();
		var $updatesContainer = $('#product_updates_container');
		$updatesContainer.fadeToggle(150);
	});

	$(document).on('click', function(event) {
		var $updatesContainer = $('#product_updates_container');
		if ($updatesContainer.is(':visible') && $('#product_updates_container:hover').length == 0 && $('#product_updates_toggle:hover').length == 0)
			$updatesContainer.fadeOut(150);
	});


	$('.reminder button.snooze-confirm').on('click', function() {
		var $reminder = $(this).closest('.reminder');
		ajaxRequest(
			'snooze_reminder',
			{
				reminderId: $reminder.attr('data-reminder-id'),
				duration: $reminder.find('select.snooze_duration option:selected').val()
			},
			function(data) {
				if (data.success) {
					$reminder.slideUp(300, function() {
						$reminder.remove();
						var currentReminderCount = parseInt($('#reminder_count').html());
						$('#reminder_count').html(currentReminderCount-1);
					});
				}
			}
		);
	});

	$('#mask_see_through').on('mouseenter', function(event) {
		var $sidebar = $('#sidebar');
		var $topbar = $('#top_bar');
		var $content = $('#main_content');

		$(this).append(' Hi!');
		$(this).animate({
			width: '70px'
		}, 200);

		if ($sidebar.hasClass('blurred') && $topbar.hasClass('blurred') && $content.hasClass('blurred')) {
			$sidebar.removeClass('blurred');
			$topbar.removeClass('blurred');
			$content.removeClass('blurred');
		}
	});

	$('#mask_see_through').on('mouseleave', function(event) {
		var $sidebar = $('#sidebar');
		var $topbar = $('#top_bar');
		var $content = $('#main_content');

		$(this).html('<i class="fa fa-eye"></i>');
		$(this).animate({
			width: '40px'
		}, 200);

		$sidebar.addClass('blurred');
		$topbar.addClass('blurred');
		$content.addClass('blurred');
	});

	$('#reminders button#reminder_create_btn').on('click', function() {
		$(this).closest('#reminders').find('#reminder_create').slideDown(300);
		$(this).closest('#reminders').find('#reminder_items').slideUp(300);
		$('#reminders input#reminder_create_title').focus();
	});

	$('#reminder_create_cancel').on('click', function() {
		$(this).closest('#reminders').find('#reminder_create').slideUp(300);
		$(this).closest('#reminders').find('#reminder_items').slideDown(300);
	});

	$('#reminder_create_save').on('click', function() {
		// Validation...
		var page = $('input#reminder_create_url').val();
		var title = $('input#reminder_create_title').val();
		var description = $('textarea#reminder_create_description').val();
		var date = $('input#reminder_create_date').val();
		var sendTo = $('select#reminder_create_send_to option:selected').val();
		var sendToOutlook = $('input#reminder_create_send_to_outlook');

		var valid = true;
		var msg = 'Could not create reminder.<br>Please repair the following problems:<br><br>';

		if (title == '') {
			valid = false;
			msg += '- The title cannot be empty<br>';
		}

		if (date == '') {
			valid = false;
			msg += '- The date cannot be empty<br>';
		}

		if (!sendTo) {
			valid = false;
			msg += "- You haven't selected any recipients!";
		}

		if (!valid) {
			showAlert('Could not create reminder', msg);
			return;
		}

		sendTo = $('select#reminder_create_send_to option:selected').map(function() {
			return $(this).val();
		}).get();

		ajaxRequest(
			'create_reminder',
			{
				page: page,
				title: title,
				description: description,
				date: date,
				sendTo: sendTo,
				sendToOutlook: sendToOutlook.is(':checked')
			},
			function(data) {
				$('#reminders #reminder_create').slideUp(300);
				$('#reminders #reminder_items').slideDown(300);

				$('#reminders #reminder_create input:not(#reminder_create_url)').val('');
				$('#reminders #reminder_create textarea').val('');
				$('#reminders #reminder_create input[type="checkbox"]').prop('checked', false);
				$('#reminders #reminder_create select').val(null).trigger('change');

				if (data.success)
					showAlert('Reminder created', 'The reminder has been created succesfully. The receivers will be reminded on ' + date + '.');
			}
		);
	});

	$('#reminders_toggle').on('click', function(event) {
		event.preventDefault();

		$('#reminders').slideToggle(300);
	});

	///////////////////////////
	///////////////////////////
	///////// SELECT2 /////////
	///////////////////////////
	///////////////////////////
	$('.select2:not(.ajax)').each(function() {
		var options = {
			width: '100%',
			sorter: function(results) { // Order the results alphabetically
				return results.sort(function(a, b) {
			    	if (a.text < b.text)
			          	return -1;
			       	if (a.text > b.text)
			         	return 1;
			       	return 0;
			  	});
			}
		};
		if ($(this).is('[placeholder]')) {
		 	options['placeholder'] = $(this).attr('placeholder');
		}
		if ($(this).is('[width]')) {
		 	options['width'] = $(this).attr('width');
		}
		$(this).select2(options);
	});
});

///////////////////////////
///////////////////////////
///////// DIALOGS /////////
///////////////////////////
///////////////////////////
function confirmDialog(header, content, confirmFunction, denyFunction, isAlert) {
	// Let's clone the template
	var $dialog = $('.dialog_window.template').clone();

	$('#dialog_windows').append($dialog);
	$dialog.removeClass('template');

	// Attach the dialog to our body
	// Set header and content html values
	$('.dialog_window_header').last().html(header);
	$('.dialog_window_content').last().html(content);

	// Unbind yes button and bind the new click event
	$('.dialog_window_yes_button').last().unbind();
	$('.dialog_window_yes_button').last().on('click', function() {
		var isLastDialog = ($('.dialog_window:not(.template)').length == 1 && $('#modal_window:visible').length == 0);
		if (isLastDialog) {
			$('#mask').fadeOut(100);
			$('#modal_window:visible').removeClass('blurred');
			$('#sidebar, #top_bar, #main_content').removeClass('blurred');
		}
		if ($('#modal_window:visible').length == 1) {
			$('#modal_window').removeClass('blurred');
		}
		$('.dialog_window').last().animate(
			{
				top: '0',
				height: 'toggle'
			},
			200,
			'swing',
			function() {
				$('.dialog_window').last().remove();
			}
		);
		if (typeof(confirmFunction) == 'function')
			confirmFunction();
	});

	// Unbind no button and bind the new click event
	$('.dialog_window_no_button').last().unbind();
	if (!isAlert) {
		$('.dialog_window_yes_button').last().html('Yep!');
		$('.dialog_window_no_button').last().show();
		$('.dialog_window_no_button').last().on('click', function() {
			var isLastDialog = ($('.dialog_window:not(.template)').length == 1 && $('#modal_window:visible').length == 0);
			if (isLastDialog) {
				$('#mask').fadeOut(100);
				$('#modal_window:visible').removeClass('blurred');
				$('#sidebar, #top_bar, #main_content').removeClass('blurred');
			}
			if ($('#modal_window:visible').length == 1) {
				$('#modal_window').removeClass('blurred');
			}
			$('.dialog_window').last().animate(
				{
					top: '0',
					height: 'toggle'
				},
				200,
				'swing',
				function() {
					$('.dialog_window').last().remove();
				}
			);
			if (denyFunction && typeof denyFunction == 'function')
				denyFunction();
		});
	} else {
		$('.dialog_window_yes_button').last().html('Understood!');
		$('.dialog_window_no_button').last().hide();
	}

	$('#mask').fadeIn(100);
	$('#modal_window:visible').addClass('blurred');
	$('#sidebar, #top_bar, #main_content').addClass('blurred');
	$('.dialog_window').last().animate(
		{
			top: '200px',
			height: 'toggle'
		},
		200, 'swing'
	);
}

function showAlert(header, content, confirmFunction) {
	confirmDialog(header, content, confirmFunction, null, true);
}

///////////////////////////
///////////////////////////
////////// MODALS /////////
///////////////////////////
///////////////////////////
function openModal(header, content, width, closeable) {
	$('#modal_window_header_title').html(header);

	// If we are in angular we want to compile the content first so we can use the angular attributes
	if (typeof angular !== 'undefined') {
		if (angular.element(content).scope()) {
			var $scope = angular.element(content).scope();
			var compiled = $scope.$compile(content.html())($scope);
			$('#modal_window_content').html(compiled);
			$scope.$apply();
		} else {
			$('#modal_window_content').html(content.html());
		}
	} else {
		$('#modal_window_content').html(content.html());
	}

	if (closeable == 'true' || closeable == true) {
		$('#modal_window_header_close').show();
		$('#mask').unbind();
		$('#modal_window_header_close').unbind();
		$('#mask').on('click', function() {
			if ($('#mask_see_through:hover').length == 1)
				return;

			if ($('.dialog_window:visible').length == 0)
				$('#modal_window_header_close').trigger('click');
		});
		$('#modal_window_header_close').on('click', function() {
			$('#mask').fadeOut(100);
			$('#sidebar, #top_bar, #main_content').removeClass('blurred');
			$('#modal_window').animate(
				{
					top: '0',
					height: 'toggle'
				},
				200, 'swing'
			);
		});

	} else {
		$('#mask').unbind();
		$('#modal_window_header_close').hide();
		$('#modal_window_header_close').unbind();
	}

	if ($(content).hasClass('nop'))
		$('#modal_window_content').addClass('nop');
	else
		$('#modal_window_content').removeClass('nop');

	$('#mask').fadeIn(100);
	$('#sidebar, #top_bar, #main_content').addClass('blurred');
	$('#modal_window').css('margin-top', '0');
	$('#modal_window').css('left', '50%');
	$('#modal_window').css('width', width + 'px');
	$('#modal_window').css('margin-left', '-' + (width/2) + 'px');

	var windowHeight = $(window).outerHeight();
	var modalHeight = $('#modal_window').height();
	var modalTop = (windowHeight / 2) - (modalHeight / 2);

	$('#modal_window').animate(
		{
			top: modalTop + 'px',
			height: 'toggle'
		},
		200, 'swing'
	);
}

function closeCurrentModal() {
	$('#mask').fadeOut(100);
	$('#sidebar, #top_bar, #main_content').removeClass('blurred');
	$('#modal_window').animate(
		{
			top: '0',
			height: 'toggle'
		},
		200, 'swing'
	);
}

$(function() {
	$('[data-modal-id]').on('click', function(event) {
		var $button = $(this);
		openModal($(this).attr('data-modal-title'),
			$('#' + $(this).attr('data-modal-id')),
			$(this).attr('data-modal-width'),
			$(this).attr('data-modal-closeable')
		);
		setTimeout(function() {
			if ($button.is('[data-modal-onopen]')) {
				if ($button.scope()) {
					$button.scope()[$button.attr('data-modal-onopen')]();
				} else {
					alert('DIE');
				}
			}
		}, 100);
	});

	$('[data-ajax-modal]').on('click', function() {
		$('#ajax_modal').remove();
		var $button = $(this);

		// Get the button html, so we can set it later on
		var buttonContent = $button.html();

		$button.html('<i class="fa fa-spinner fa-spin"></i> Loading...');

		// Get the content first
		ajaxRequest(
			$button.attr('data-ajax-modal'),
			{

			},
			function(data) {
				// Set the modal content and open the modal
				var $modal = $('#page_modals').append('<div class="modal_content" id="ajax_modal">' + data.modal + '</div>');
				setTimeout(function() {
					openModal($button.attr('data-modal-title'), $('#ajax_modal').first(), $button.attr('data-modal-width'), true);
				}, 100);
				$button.html(buttonContent);
			}
		);
	});

	$(document).on('click', '.modal-close', function(event) {
		$('#mask').fadeOut(100);
		$('#modal_window').animate(
			{
				top: '0',
				height: 'toggle'
			},
			200, 'swing'
		);
		$('#sidebar, #top_bar, #main_content').removeClass('blurred');
	});
});


// Make modals and dialog boxes draggable
$(function() {
	$('#modal_window_header').on('mousedown', function(event) {
		var $modalHeader = $(this);
		var $modal = $modalHeader.closest('#modal_window');

		// Get the offset of the modal window
		var leftOffset = $modal.offset().left;
		var topOffset = $modal.offset().top;

		// Get the offset of the mouse relative to the dialog window
		var relativeMLeftOffset = (event.pageX - leftOffset);
		var relativeMTopOffset = (event.pageY - topOffset);

		// Store the data inside the modal window, so we can access it in the mousemove event
		$modal.data('mlOffset', relativeMLeftOffset);
		$modal.data('mtOffset', relativeMTopOffset);

		// Set the modal CSS left and top to 0 and set the correct margins
		$modal.css('left', '0px');
		$modal.css('top', '0px');
		$modal.css('margin-left', leftOffset + 'px');
		$modal.css('margin-top', topOffset + 'px');

		// Add the class dragging so we can check this in the mousemove event
		$modal.addClass('dragging');
	});

	$('#modal_window_header').on('mouseup', function(event) {
		var $modalHeader = $(this);
		var $modal = $modalHeader.closest('#modal_window');

		// Simply remove the dragging class
		$modal.removeClass('dragging');
	});

	$(window).on('mousemove', function(event) {
		var $modalHeader = $('#modal_window');
		var $modal = $modalHeader.closest('#modal_window');

		// We only want to move the modal if we are dragging it...
		if (!$('#modal_window').first().hasClass('dragging'))
			return;

		// Set the correct margin left and top
		$modal.css('margin-left', (event.pageX - $modal.data('mlOffset')) + 'px');
		$modal.css('margin-top', (event.pageY - $modal.data('mtOffset')) + 'px');
	});

	$('body').on('mousedown', '.dialog_window_header', function(event) {
		var $dialogHeader = $(this);
		var $dialog = $dialogHeader.closest('.dialog_window');

		// Get the offset of the modal window
		var leftOffset = $dialog.offset().left;
		var topOffset = $dialog.offset().top;

		// Get the offset of the mouse relative to the dialog window
		var relativeMLeftOffset = (event.pageX - leftOffset);
		var relativeMTopOffset = (event.pageY - topOffset);

		// Store the data inside the modal window, so we can access it in the mousemove event
		$dialog.data('mlOffset', relativeMLeftOffset);
		$dialog.data('mtOffset', relativeMTopOffset);

		// Set the modal CSS left and top to 0 and set the correct margins
		$dialog.css('left', '0px');
		$dialog.css('top', '0px');
		$dialog.css('margin-left', leftOffset + 'px');
		$dialog.css('margin-top', topOffset + 'px');

		// Add the class dragging so we can check this in the mousemove event
		$dialog.addClass('dragging');
	});

	$('body').on('mouseup', '.dialog_window_header', function(event) {
		var $dialogHeader = $(this);
		var $dialog = $dialogHeader.closest('.dialog_window');

		// Simply remove the dragging class
		$dialog.removeClass('dragging');
	});

	$(window).on('mousemove', function(event) {
		var $dialogHeader = $('.dialog_window').last().find('.dialog_window_header').first();
		var $dialog = $dialogHeader.closest('.dialog_window');

		// We only want to move the modal if we are dragging it...
		if (!$dialog.hasClass('dragging'))
			return;

		// Set the correct margin left and top
		$dialog.css('margin-left', (event.pageX - $dialog.data('mlOffset')) + 'px');
		$dialog.css('margin-top', (event.pageY - $dialog.data('mtOffset')) + 'px');
	});
});

function openMediaGallery(selectFunction) {
	$('#mask').fadeIn(100);
	$('#media_library').fadeIn(100);

	ajaxRequest(
		'get_media_library_popup',
		{},
		function(data) {
			$('#media_library').html(data.modal);

			if (selectFunction) {
				$('#media_library #library_wrapper').scope().selectMode = true;
				$('#media_library #library_wrapper button#select-btn').on('click', selectFunction);
			}
		}
	);
}


$(function() {
	$('#nav_open_media_gallery').on('click', function(event) {
		openMediaGallery();

		event.preventDefault();
	});
});

///////////////////////////
///////////////////////////
////// NOTIFICATIONS //////
///////////////////////////
///////////////////////////
function notify(type, title, message, duration, id) {
	var $notification = $('#notification_template').clone();

	$notification.removeClass('dn').removeAttr('id');

	switch(type) {
		case 'success':
			$notification.addClass('success');
		break;
		case 'warning':
			$notification.addClass('warning');
		break;
		case 'error':
			$notification.addClass('error');
		break;
		case 'info':
			$notification.addClass('info');
		break;
	}

	if (id)
		$notification.attr('data-notification-id', id);

	$notification.find('.notification_title').html(title);
	$notification.find('.notification_message').html(message);

	if (duration != 0) {
		$notification.find('.notification_close').on('click', function() {
			$(this).parent().fadeOut(300, function() {
				$notification.remove();
			});
		});
	} else {
		$notification.find('.notification_close').hide();
	}

	$notification.hide();
	$('#notification_center').append($notification);
	$notification.fadeIn(300);

	if (duration != 0) {
		$notification.find('.notification_progress').animate({
			width: '100%'
		}, (duration * 1000), 'linear', function() {
			$notification.fadeOut(300, function() {
				$notification.remove();
			});
		});
	}
}

function showSuccess(message, duration) {
	if (duration) {
		notify('success', 'Success!', message, duration);
	} else {
		notify('success', 'Success!', message, 3);
	}
}

function showError(message, duration) {
	if (duration) {
		notify('error', 'Whoops!', message, duration);
	} else {
		notify('error', 'Whoops!', message, 3);
	}
}

// Close a notification
function killNotification(id) {
	$('.notification[data-notification-id="' + id + '"]').slideUp(300, function() {
		$(this).remove();
	});
}

///////////////////////////
///////////////////////////
/////// AJAX HELPERS //////
///////////////////////////
///////////////////////////
function ajaxRequest(ajax_id, data, onSuccess, onError) {
	if (typeof(onSuccess) === 'undefined') onSuccess = function(response) {};
	var returnData;
	$.ajax({
		type: 'post',
		url: 'ajax/' + ajax_id,
		data: data,
		dataType: 'json',
		success: function(data) {
			returnData = data;
			onSuccess(data);
			if (data.msg) {
				notify(data.msg);
			}
		},
		error: function(request, status, errorThrown) {
			if (onError)
				onError();
			// Log error
			ajaxRequest(
				'log_ajax_error',
				{
					'ajaxId': ajax_id,
					'data': data,
					'status': status,
					'errorThrown': errorThrown,
					'body': request.responseText
				},
				function(data) {}
			);
		}
	});
	return returnData;
}

function ajaxRequestHtml(ajax_id, data, onSuccess) {
	if (typeof(onSuccess) === 'undefined') onSuccess = function(response) {};
	var returnData;
	$.ajax({
		type: 'post',
		url: 'ajax/' + ajax_id,
		data: data,
		dataType: 'html',
		success: function(data) {
			returnData = data;
			onSuccess(data);
			if (data.msg) {
				notify(data.msg);
			}
		},
		error: function(request, status, errorThrown) {
			// Log error
			ajaxRequest(
				'log_ajax_error',
				{
					'ajaxId': ajax_id,
					'data': data,
					'status': status,
					'errorThrown': errorThrown,
					'body': request.responseText
				},
				function(data) {}
			);
		}
	});
	return returnData;
}

///////////////////////////
///////////////////////////
////// TABLE SORTING //////
///////////////////////////
///////////////////////////
function enableTableSorting($table) {
	var options = {
		dateFormat: 'ddmmyyyy',
		headers: {}
	};

	$table.find('th').each(function(index) {
		if (!options.headers[index])
			options.headers[index] = {};
		if ($(this).is('[data-sortable-no]'))
			options.headers[index]['sorter'] = false;

		if ($(this).is('[data-sortable-date]'))
			options.headers[index]['sorter'] = 'date';

		if ($(this).is('[data-sortable-datetime]')) {
			options.headers[index]['sorter'] = 'datetime';
		}
	});

	$table.tablesorter(options);
}

$(function() {
	$.tablesorter.addParser({
		id: 'date',
	    is: function(s) {
	        return false;
	    },
	    format: function(s) {
	    	var date = s.split('-');
	    	var number = date[2] + "" + date[1] + "" + date[0];
	        return number;
	    },
	    type: 'numeric'
	});

	$.tablesorter.addParser({
		id: 'datetime',
	    is: function(s) {
	        return false;
	    },
	    format: function(s) {
	    	if (s == '')
	    		return 500001010000;

	        var dateAndTime = s.split(' ');
	        var date = dateAndTime[0];
	        date = date.split('-');
	        var time = dateAndTime[1];
	        if (time) {
		        time = time.split(':');
		        return date[2] + date[1] + date[0] + time[0] + time[1];
	        } else {
	        	return date[2] + date[1] + date[0];
	        }
	    },
	    type: 'numeric'
	});

	$('table.sortable').each(function() {
		enableTableSorting($(this));
	});
});

///////////////////////////
///////////////////////////
//////// TOOLTIPS /////////
///////////////////////////
///////////////////////////
$(function() {
	$('[data-tooltip]').on('mouseenter', function(event) {
		var $tooltip = $('#tooltip');

		$tooltip.html('<i class="fa fa-info-circle"></i> ' + $(this).attr('data-tooltip'));
		$tooltip.fadeIn(50);

	});

	$('[data-tooltip]').on('mouseleave', function(event) {
		$('#tooltip').fadeOut(50);
	});

	$('[data-tooltip]').on('mousemove', function(event) {
		var $tooltip = $('#tooltip').first();

		// The jQuery window
		var jWindow = $(window);

		// Calculate the right and bottom offset of the cursor
		var cursorRightOffset = (jWindow.outerWidth() - event.clientX);
		var cursorBottomOffset = (jWindow.outerHeight() - event.clientY);

		// Check if we are outside the browser viewport, either on the X or Y axis. If we are, adjust positioning
		if (cursorRightOffset < 320) {
			$tooltip.css('left', (event.clientX - $tooltip.outerWidth()) + 'px');
		} else {
			$tooltip.css('left', (event.clientX + 15) + 'px');
		}

		if (event.clientY < 100) {
			$tooltip.css('top', (event.clientY + 15) + 'px');
		} else {
			$tooltip.css('top', (event.clientY - $tooltip.outerHeight() - 15) + 'px');
		}
		
	});
});

///////////////////////////
///////////////////////////
//// VARIOUS FUNCTIONS ////
///////////////////////////
///////////////////////////
$(function() {
	$("body").on('mousedown', '[data-href]', function(event) {
		if ($(this).find('.no-click-area:hover').length > 0)
			return;
			
		var url = '';
		if ($(this).attr("data-href").substring(0, 1) == '/') {
			url = $(this).attr("data-href");
		} else {
			url = '/' + $(this).attr("data-href");
		}

		// If we start with http or https, we know it's an absolute url
		if ($(this).attr('data-href').substring(0, 7) == 'http://' || $(this).attr('data-href').substring(0, 8) == 'https://') {
			url = $(this).attr('data-href');
		}

		if (event.button == 0) {
			location.assign(url);
		} else if (event.button == 1) {
			window.open(url);
		}
  });

  $('.tagcloud span').each(function() {
  	var size = Math.floor(Math.random()*(33-12+1)+12);
  	var color = (size / 33) * 255;
  	color = Math.max(150, color);
  	color = 'rgba(' + color.toFixed(0) + ',' + (color.toFixed(0) / 2).toFixed(0) + ',' + 80 + ')';

  	$(this).css('font-size', size + 'px');
  	$(this).css('color', color);
  	if (size > 29)
  		$(this).css('text-decoration', 'underline');
  });
});

///////////////////////////
///////////////////////////
///// NAVIGATION SETUP ////
///////////////////////////
///////////////////////////
function setupNavigation(main, sub) {
	$('ul#nav_menu > li > a').each(function() {
		var $link = $(this);
		var linkHtml = $link.html();

		if (linkHtml.indexOf(main) > -1) {
			$link.addClass('current');
			$link.next('.nav_submenu').show();

			$link.next('.nav_submenu').find('a').each(function() {
				var sublinkHtml = $(this).html();

				if (sublinkHtml.indexOf(sub) > -1 && sub != '') {
					$(this).addClass('current');
				}
			});
		}
	});
}