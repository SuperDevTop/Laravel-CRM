$(function() {
	$('li[data-tab]').on('click', function(event) {
		var $container = $(this).closest('.tab-container, .container').first();
		$container.find('li[data-tab]').removeClass('active');
		$container.find('div[data-tab]').removeClass('active');

		$(this).addClass('active');
		$container.find('div[data-tab="' + $(this).attr('data-tab') + '"]').addClass('active');

		// Sometimes we want to remember the tab we're on (customers page for example)
		if ($(this).closest('ul').is('[data-tab-remember]')) {
			var pageTitle = document.title;

			// Get tab settings
			var currentTabSettings = JSON.parse(localStorage.getItem('tabSettings'));
			if (!currentTabSettings) {
				currentTabSettings = {};
				currentTabSettings[pageTitle] = {};
			}

			if (!currentTabSettings.hasOwnProperty(pageTitle)) {
				currentTabSettings[pageTitle] = {};
			}


			currentTabSettings[pageTitle][$(this).closest('ul').attr('data-tab-remember')] = {
				timestamp: Date.now(),
				tabName: $(this).attr('data-tab')
			};

			localStorage.setItem('tabSettings', JSON.stringify(currentTabSettings));
		}
	});

	if (localStorage.getItem('tabSettings') != null) {
		var tabSettings = JSON.parse(localStorage.getItem('tabSettings'));

		if (tabSettings.hasOwnProperty(document.title)) {
			var pageSettings = tabSettings[document.title];
			for(var pageSetting in pageSettings) {
				var containerName = pageSetting;
				var containerSettings = pageSettings[containerName];


				if (containerSettings.timestamp < (Date.now() - 300000)) {
					delete tabSettings[document.title][containerName];
					localStorage.setItem('tabSettings', JSON.stringify(tabSettings));
				} else {
					// Valid, click the tab
					$('ul[data-tab-remember="' + containerName + '"] [data-tab="' + containerSettings.tabName + '"]').trigger('click');
					// And refresh the timestamp
					tabSettings[document.title][containerName].timestamp = Date.now();
					localStorage.setItem('tabSettings', JSON.stringify(tabSettings));
				}
			}
		}
	}
});