$(function() {
	$('#search_anything_btn').focus();
	$('#search_anything_btn').on('click', function() {
		$('#search_anything').slideDown(100);
		$('#search_anything_input').focus();
		$('#search_anything_input').val('');
	});

	$('#search_anything').on('mousedown', '.search_anything_result', function(event) {
		$('.search_anything_result').removeClass('selected');
		$(this).addClass('selected');

		var e = jQuery.Event('keydown');
		e.which = 13;
		$(document).trigger(e);
	});

	$(document).on('keydown', function(event) {
		if (event.which == 13) { // ENTER
			if (!$('#search_anything').is(':visible') || $('#search_anything_input').is(':focus'))
				return;

			// Open that bitch!
			var $selectedResult = $('.search_anything_result.selected');
			if ($selectedResult.size() == 0)
				return;

			var resultType = $selectedResult.data('type');
			var resultId = $selectedResult.data('id');

			switch(resultType) {
				case 'customer':
					window.location.assign('/customers/' + resultId);
				break;
				case 'supplier':
					window.location.assign('/suppliers/' + resultId);
				break;
				case 'product':
					window.location.assign('/products/' + resultId);
				break;
				case 'quote':
					window.location.assign('/quotes/' + resultId + '/edit');
				break;
				case 'invoice':
					window.location.assign('/invoices/' + resultId + '/edit');
				break;
				case 'user':
					window.location.assign('/users/' + resultId);
				break;
				case 'contact':
					window.location.assign('/customers/' + resultId);
				break;
			}
		}
		if (event.which == 114) { // F3
			event.preventDefault();

			$('#search_anything_input').val('');
			if (!$('#search_anything').is(':visible')) {
				$('#search_anything').slideDown(100);
				$('#search_anything_input').focus();
				$('#search_anything_input').val('');
			} else {
				$('#search_anything_input').focus();
			}
		}

		// From here on we only want to capture if search anything is opened
		if (!$('#search_anything').is(':visible'))
			return;

		if (event.which == 27) { // ESCAPE
			event.preventDefault();

			if ($('#search_anything').is(':visible')) {
				$('#search_anything').slideUp(100);
				$('#search_anything_help').show();
				$('#search_anything_results').hide();
			}
		}

		// From here on we only want to capture if a result is highlighted
		if ($('.search_anything_result.selected').size() != 1)
			return;
		var $selectedResult = $('.search_anything_result.selected');

		if (event.which == 37) { // LEFT_ARROW
			// Left arrow pressed, change column
			// Get prev column
			var $prevColumn = $selectedResult.closest('.search_anything_container').prev('.search_anything_container');
			if ($prevColumn.size() == 1) {
				$selectedResult.removeClass('selected');

				// Now, get the index of the selected option in the column
				var $rowIndex = $selectedResult.index()-2;

				// Check if that index also exists in the prev column
				if ($prevColumn.find('.search_anything_result').eq($rowIndex).size() == 1) {
					$nextResult = $prevColumn.find('.search_anything_result').eq($rowIndex);
					$nextResult.addClass('selected');
				} else {
					$nextResult = $prevColumn.find('.search_anything_result').last();
					$nextResult.addClass('selected');
				}
			}
		}
		if (event.which == 39) { // RIGHT ARROW
			// Left arrow pressed, change column
			// Get next column
			var $nextColumn = $selectedResult.closest('.search_anything_container').next('.search_anything_container');
			if ($nextColumn.size() == 1) {
				$selectedResult.removeClass('selected');

				// Now, get the index of the selected option in the column
				var $rowIndex = $selectedResult.index()-2;

				// Check if that index also exists in the next column
				if ($nextColumn.find('.search_anything_result').eq($rowIndex).size() == 1) {
					$nextResult = $nextColumn.find('.search_anything_result').eq($rowIndex);
					$nextResult.addClass('selected');
				} else {
					$nextResult = $nextColumn.find('.search_anything_result').last();
					$nextResult.addClass('selected');
				}
			}
		}
		if (event.which == 40) {
			// DOWN ARROW
			var $nextResult = $selectedResult.next('.search_anything_result');
			if ($nextResult.size() == 1) {
				$selectedResult.removeClass('selected');
				$nextResult.addClass('selected');
			}
		}
		if (event.which == 38) {
			// UP ARROW
			var $prevResult = $selectedResult.prev('.search_anything_result');
			if ($prevResult.size() == 1) {
				$selectedResult.removeClass('selected');
				$prevResult.addClass('selected');
			}
		}
	});

	$('#search_anything_input_area input').on('keyup', function(event) {
		if (event.which != 13)
			return;
		$(this).blur();
		$('#search_anything_help').hide();
		$('#search_anything_loading').fadeIn(100);
		$('#search_anything_results_content').html('');

		ajaxRequest(
			'search_anything_query',
			{
				query: $('#search_anything_input').val()
			},
			function(data) {
				stopLoading();
				if (data.results.customersByPhone.length == 0 && 
					data.results.customers.length == 0 && 
					data.results.suppliers.length == 0 && 
					data.results.products.length == 0 && 
					data.results.quotes.length == 0 && 
					data.results.contacts.length == 0 && 
					data.results.invoices.length == 0) {

					// No results found, show no results message
					$('#search_anything_results_content').html('<h2>No results found</h2>');
					return;
				}

				$('#search_anything_results_content').html('');
				var contentToAdd = '';

				if (data.results.quotes.length != 0) {
					contentToAdd += "<div class='search_anything_container'><h2>Quote</h2><hr>";
					$.each(data.results.quotes, function(index, value) {
						contentToAdd += "<div class='search_anything_result' data-type='quote' data-id='" + value.id + "'># " + value.id + " (&euro; " + value.total.toFixed(2) + ")</div>";
					});
					contentToAdd += "</div>";
				}
				if (data.results.invoices.length != 0) {
					contentToAdd += "<div class='search_anything_container'><h2>Invoice</h2><hr>";
					$.each(data.results.invoices, function(index, value) {
						contentToAdd += "<div class='search_anything_result' data-type='invoice' data-id='" + value.id + "'># " + value.id + " (&euro; " + value.total.toFixed(2) + ")</div>";
					});
					contentToAdd += "</div>";
				}
				if (data.results.customersByPhone.length != 0) {
					contentToAdd += "<div class='search_anything_container'><h2>Customers (&nbsp;<i class='fa fa-phone'></i>&nbsp;)</h2><hr>";
					$.each(data.results.customersByPhone, function(index, value) {
						contentToAdd += "<div class='search_anything_result' data-type='customer' data-id='" + value.id + "'>" + value.companyName + ((value.contactName != '') ? ' (' + value.contactName + ')' : '') + "</div>";
					});
					contentToAdd += "</div>";
				}
				if (data.results.contacts.length != 0) {
					contentToAdd += "<div class='search_anything_container'><h2>Contacts</h2><hr>";
					$.each(data.results.contacts, function(index, value) {
						contentToAdd += "<div class='search_anything_result' data-type='contact' data-id='" + value.id + "'>" + value.contactName + " (" + value.companyName + ")</div>";
					});
					contentToAdd += "</div>";
				}
				if (data.results.customers.length != 0) {
					contentToAdd += "<div class='search_anything_container'><h2>Customers</h2><hr>";
					$.each(data.results.customers, function(index, value) {
						contentToAdd += "<div class='search_anything_result' data-type='customer' data-id='" + value.id + "'>" + value.companyName + "</div>";
					});
					contentToAdd += "</div>";
				}
				if (data.results.suppliers.length != 0) {
					contentToAdd += "<div class='search_anything_container'><h2>Suppliers</h2><hr>";
					$.each(data.results.suppliers, function(index, value) {
						contentToAdd += "<div class='search_anything_result' data-type='supplier' data-id='" + value.id + "'>" + value.companyName + "</div>";
					});
					contentToAdd += "</div>";
				}
				if (data.results.products.length != 0) {
					contentToAdd += "<div class='search_anything_container'><h2>Products</h2><hr>";
					$.each(data.results.products, function(index, value) {
						contentToAdd += "<div class='search_anything_result' data-type='product' data-id='" + value.id + "'>" + value.name + "</div>";
					});
					contentToAdd += "</div>";
				}
				if (data.results.users.length != 0) {
					contentToAdd += "<div class='search_anything_container'><h2>Users</h2><hr>";
					$.each(data.results.users, function(index, value) {
						contentToAdd += "<div class='search_anything_result' data-type='user' data-id='" + value.id + "'>" + value.firstname + " " + value.lastname + "</div>";
					});
					contentToAdd += "</div>";
				}

				$('#search_anything_results_content').append(contentToAdd);

				var containerCount = $('.search_anything_container').size();

				var contentWidth = (containerCount * (250 + 50));
				var ml = ($(window).width() / 2) - (contentWidth / 2);
				$('.search_anything_container').first().css('margin-left', ml + 'px');
				$('.search_anything_result').first().addClass('selected');
			}
		);
	});

	function startLoading() {
		$('#search_anything_help').fadeOut();
		$('#search_anything_loading').fadeIn(100);
		$('#search_anything_results').hide();
	}

	function stopLoading() {
		$('#search_anything_help').fadeOut();
		$('#search_anything_loading').hide();
		$('#search_anything_results').fadeIn(100);
	}
});