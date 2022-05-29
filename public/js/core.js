// Invoked when you click a main menu item in the navigation bar
function clickNavItem(item) {
	$subMenu = item.next("ul");
	if ($subMenu === undefined) return false;
	// If it currently slided down already, remove the active class and slide it up
	if ($subMenu.is(":visible")) {
		item.removeClass("active");
		$subMenu.slideUp(100);
		return false;
	}

	// Slide all submenu's up
	$(".navigation_sub").each(function(index) {
		if ($(this).prev("a").hasClass("current")) return;
		$(this).slideUp(100);
	});

	// Remove all active classes from the main items
	$(".navigation li").each(function(index) {
		$(this).find("a").removeClass("active");
	});

	// Slide the correct submenu down, and give the main item the active class
	$subMenu.slideDown(100);
	item.addClass("active");

	return false;
}

// Send jGeowl notification
function notify(text) {
	$.jGrowl(text, { theme: 'jgrowl_theme', header: 'Notification', position: 'bottom-right', life: 5000 });
}

// Ajax function. Makes an AJAX request to AjaxController, and returns the result
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

// Close a container
$(function() {
	$(function() {
		$('#mask').on('click', function() {
			$('#mask').fadeOut(200);
			$('.popup_window').fadeOut('fast');
			$('#dialog_popup').fadeOut('fast');
		});
	});

	// Allow data-href=url for buttons
	$("button[data-href]").click( function() {
		if ($(this).attr("data-href").substring(0, 1) == '/') {
			window.location.assign($(this).attr("data-href"));
		} else {
			window.location.assign('/' + $(this).attr("data-href"));
		}
    });

	$('.username').on("click", function() {
		$('.user_menu').slideToggle(100);
	});

	// Slidedown flash message
	$(window).on("resize", function() {
		$('.left_bar').height(window.innerHeight - 50);
		$('.page_content').height(window.innerHeight - 50 - 75);
		if (window.innerWidth > 700) {
			$('.left_bar').slideDown();
			$('.matter').slideDown();
		}
	});

	$('.left_bar').height(window.innerHeight - 50);
	$('.page_content').height(window.innerHeight - 50 - 75);

	$('.mobile_menu').on("click", function() {
		$sidebar = $('.left_bar');

		if ($sidebar.is(":visible")) {
			$sidebar.slideUp(200, function() {
				$('.matter').slideDown(200);
			});
		} else {
			$('.matter').slideUp(200, function() {
				$sidebar.slideDown(200);
			});
		}
	});

	$('.notify').on("click", function() {
		$(this).fadeOut(50, "linear");
	});
	
	$('[data-popup]').on('click', function(e) {
		$('#mask').fadeIn(200);
		$popup = $('.popup_window#' + $(this).attr('data-popup'));
		$popup.animate({top: "100px", height: "toggle"}, 300, "swing");
		$popup.slideDown();
		
		e.preventDefault();
	});
	
	$('.popup_close_btn').on('click', function(e) {
		$('#mask').fadeOut(200);
		$('.popup_window').animate({top: "0px", height: "toggle"}, 300, "swing");
		e.preventDefault();
	});

	$('.container_title.collapsable').on('click', function(event) {
		var $container = $(this).closest('.container');
		var $containerContent = $container.find('.container_content').first();
		if ($containerContent.is(':visible')) {
			$containerContent.slideUp();
		} else {
			$containerContent.slideDown();
		}
	});
});

// Takes the main- and submenu item, and gives the menu the correct classes. Also set's the document's title
function setupNavigation(main, sub) {
	$('.navigation > li > a').each(function() {
		$mainItem = $(this);
		if ($mainItem.html().toLowerCase().indexOf(main.toLowerCase()) != -1) {
			$mainItem.addClass("current");
			$mainItem.next("ul").show();
		}
	});

	$('.navigation_sub a').each(function() {
		$subItem = $(this);
		if ($subItem.html().toLowerCase().indexOf(sub.toLowerCase()) !== -1 && sub !== '') {
			$subItem.addClass("current");
		}
	});
}


function showSuccess(message) {
	$('.flash_msg').html(message);
	$('.flash_msg').slideDown({
    duration: 200, 
    easing: "swing"}).delay(1800).slideUp(200);
}

function showAlert(message) {
	showDialog('Alert', message, 'OK', '', 'green', 'white', function() {}, function() {}, true);
}

function showError(message) {
	showDialog('An error occured', message, 'OK', '', 'green', 'white', function() {}, function() {}, true);
}

function showSuccess(message) {
	showDialog('Success', message, 'OK', '', 'green', 'white', function() {}, function() {}, true);
}

function showSuccess(message, refreshAfter) {
	showDialog('Success', message, 'OK', '', 'green', 'white', function() {location.reload();}, function() {location.reload()}, true);
}

function showDialog(title, message, btn1, btn2, btn1Color, btn2Color, btn1Func, btn2Func, isAlert) {
	$mask = $('#mask');
	$popup = $('#dialog_popup');
	$title = $('#dialog_popup_title');
	$description = $('#dialog_popup_desc');
	$btn1 = $('#dialog_popup_btn1');
	$btn2 = $('#dialog_popup_btn2');
	
	$title.html(title);
	$description.html(message);
	$btn1.html(btn1);
	$btn2.html(btn2);
	$btn1.removeClass('red green orange blue white');
	$btn1.addClass(btn1Color);
	$btn2.removeClass('red green orange blue white');
	$btn2.addClass(btn2Color);

	$mask.fadeIn(200);
	$popup.animate({top: "20%", height: "toggle"}, 300, "swing");
	$popup.slideDown();

	if (isAlert) {
		$btn2.hide();
	} else {
		$btn2.show();
	}

	$btn1.unbind();
	$btn2.unbind();
	$btn1.bind("click", btn1Func).bind("click",
		function() {
			$mask.fadeOut(200);
			$popup.animate({top: "0px", height: "toggle"}, 300, "swing");
		}
	);

	$btn2.bind("click", btn2Func).bind("click",
		function() {
			$mask.fadeOut(200);
			$popup.animate({top: "0px", height: "toggle"}, 300, "swing");
		}
	);
}

function getCurrentDate() {
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();

	if(dd<10) {
	    dd='0'+dd
	} 

	if(mm<10) {
	    mm='0'+mm
	} 

	today = dd+'-'+mm+'-'+yyyy;

	return today;
}

function getCurrentDateTime() {
	return formatDateTime(new Date());
}

function formatDateTime(input) {
	var dd = input.getDate();
	var mm = input.getMonth()+1; //January is 0!
	var yyyy = input.getFullYear();
	var hours = input.getHours();
	var minutes = input.getMinutes();

	if(dd<10) {
	    dd='0'+dd
	}
	if(mm<10) {
	    mm='0'+mm
	}

	if(hours<10) {
	    hours='0'+hours
	} 
	if(minutes<10) {
	    minutes='0'+minutes
	} 

	input = dd+'-'+mm+'-'+yyyy+' '+hours+':'+minutes;

	return input;
}

function formatMysqlDateTime(input) {
	var date = new Date(input);

	// Return that shit!
	return formatDateTime(date);
}