function az(value) {
	value = removeLeadingZero(value);
	if (value == 0)
		return '00';
	if (value < 10)
		return '0' + value;
	else
		return value;
}
function a2z(value) {
	value = removeLeadingZero(value);
	if (value.length == 4 && value.substring(0, 2) == '20')
		return value;
	if (value.toString().length == 2)
		return '20' + value;
	else if (value.toString().length == 4)
		return value;
}
function removeLeadingZero(value) {
	if (value.toString().substring(0, 1) == '0') {
		value = value.substring(1, (value.length));
	}
	return value;
}
function setOriginalContent(element) {
	$(element).data('original-data', $(element).val());
	$(element).trigger('input');
}

function applyBasInput(element) {
	$(element).data('original-data', $(element).val());
	$(element).on('click', basinputClick);
	$(element).on('blur', basinputBlur);
	if ($(element).hasClass('date')) {
		$(element).datepicker({
			constrainInput: false,
			dateFormat: 'dd-mm-yy',
			showButtonPanel: true,
			firstDay: 1 // Week starts on monday, not sunday
		});
	} else if ($(element).hasClass('datetime')) {
		$(element).datetimepicker({
			dateFormat: 'dd-mm-yy',
			timeFormat: 'HH:mm',
			hourMin: 5,
			hourMax: 23,
			firstDay: 1 // Week starts on monday, not sunday
		});
		// Set the value to the current date if empty
		if ($(element).val() == '')
			$(element).datepicker('setDate', new Date());
	}
}

function basinputClick(event) {
	$(this).select();
}

function basinputBlur(event) {
	$(this).removeClass('invalid');

	var value = $(this).val();

	if (value == $(this).data('original-data') || (value == '' && !$(this).hasClass('notempty')))
		return;

	var now = new Date();

	if (value == 'yest')
		now.setDate(now.getDate() - 1);

	if (value == 'tomo')
		now.setDate(now.getDate() + 1);

	var day = now.getDate();
	var month = now.getMonth();
	var year = now.getFullYear();

	var hour = now.getHours();
	var minute = now.getMinutes();

	month += 1;

	if (day < 10)
		day = '0' + day;
	if (month < 10)
		month = '0' + month;
	if (hour < 10)
		hour = '0' + hour;
	if (minute < 10)
		minute = '0' + minute;

	if ($(this).hasClass('date') && !$(this).hasClass('datetime')) {
		if (value == 'today' || value == 'now' || value == 'yest') {
			$(this).val(day + '-' + month + '-' + year);
			return setOriginalContent($(this));
		}
		if (/^[0-9]{1,2}:[0-9]{1,2}$/.test(value)) {
			$(this).val(day + '-' + month + '-' + year);
			return setOriginalContent($(this));
		}

		if (/^[0-9]{1,2}-[0-9]{1,2}$/.test(value)) {
			var elements = value.split('-');
			$(this).val(az(elements[0]) + '-' + az(elements[1]) + '-' + year);
			return setOriginalContent($(this));
		}
		if (/^[0-9]{1,2}\/[0-9]{1,2}$/.test(value)) {
			var elements = value.split('/');
			$(this).val(az(elements[0]) + '-' + az(elements[1]) + '-' + year);
			return setOriginalContent($(this));
		}
		if (/^[0-9]{1,2}-[0-9]{1,2}-[0-9]{2,4}$/.test(value)) {
			var elements = value.split('-');
			$(this).val(az(elements[0]) + '-' + az(elements[1]) + '-' + a2z(elements[2]));
			return setOriginalContent($(this));
		}
		if (/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{2,4}$/.test(value)) {
			var elements = value.split('/');
			$(this).val(az(elements[0]) + '-' + az(elements[1]) + '-' + a2z(elements[2]));
			return setOriginalContent($(this));
		}
	} else if ($(this).hasClass('datetime') && !$(this).hasClass('date')) {
		if (value == 'today' || value == 'now') {
			$(this).val(day + '-' + month + '-' + year + ' ' + hour + ':' + minute);
			return setOriginalContent($(this));
		}
		if (value == 'yest') {
			$(this).val(day + '-' + month + '-' + year + ' 18:00');
			return setOriginalContent($(this));
		}
		if (value == 'tomo') {
			$(this).val(day + '-' + month + '-' + year + ' 18:00');
			return setOriginalContent($(this));
		}
		if (/^[0-9]{1,2}:[0-9]{1,2}$/.test(value)) { // 18:00
			var elements = value.split(':');
			$(this).val(day + '-' + month + '-' + year + ' ' + elements[0] + ':' + elements[1]);
			return setOriginalContent($(this));
		}
		if (/^[0-9]{1,2}-[0-9]{1,2}$/.test(value)) { // 28-11
			var elements = value.split('-');
			$(this).val(az(elements[0]) + '-' + az(elements[1]) + '-' + year + ' 18:00');
			return setOriginalContent($(this));
		}
		if (/^[0-9]{1,2}\/[0-9]{1,2}$/.test(value)) { // 28/11
			var elements = value.split('/');
			$(this).val(az(elements[0]) + '-' + az(elements[1]) + '-' + year + ' 18:00');
			return setOriginalContent($(this));
		}
		if (/^[0-9]{1,2}-[0-9]{1,2}-[0-9]{2,4}$/.test(value)) { // 28-11-14
			var elements = value.split('-');
			$(this).val(az(elements[0]) + '-' + az(elements[1]) + '-' + a2z(elements[2]) + ' 18:00');
			return setOriginalContent($(this));
		}
		if (/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{2,4}$/.test(value)) { // 28/11/14
			var elements = value.split('/');
			$(this).val(az(elements[0]) + '-' + az(elements[1]) + '-' + a2z(elements[2]) + ' 18:00');
			return setOriginalContent($(this));
		}
		if (/^[0-9]{1,2}-[0-9]{1,2}\s[0-9]{1,2}:[0-9]{1,2}$/.test(value)) { // 28-11 13:00
			var datetimeSplit = value.split(' ');

			var dateElements = datetimeSplit[0].split('-');

			var timeElements = datetimeSplit[1].split(':');

			$(this).val(az(dateElements[0]) + '-' + az(dateElements[1]) + '-' + year + ' ' + az(timeElements[0]) + ':' + az(timeElements[1]));
			return setOriginalContent($(this));
		}
		if (/^[0-9]{1,2}\/[0-9]{1,2}\s[0-9]{1,2}:[0-9]{1,2}$/.test(value)) { // 28/11 13:00
			var datetimeSplit = value.split(' ');

			var dateElements = datetimeSplit[0].split('/');

			var timeElements = datetimeSplit[1].split(':');

			$(this).val(az(dateElements[0]) + '-' + az(dateElements[1]) + '-' + year + ' ' + az(timeElements[0]) + ':' + az(timeElements[1]));
			return setOriginalContent($(this));
		}

		if (/^[0-9]{1,2}-[0-9]{1,2}-[0-9]{2,4}\s[0-9]{1,2}:[0-9]{1,2}$/.test(value)) { // 28-11-14 15:00
			var datetimeSplit = value.split(' ');

			var dateElements = datetimeSplit[0].split('-');

			var timeElements = datetimeSplit[1].split(':');

			$(this).val(az(dateElements[0]) + '-' + az(dateElements[1]) + '-' + a2z(dateElements[2]) + ' ' + az(timeElements[0]) + ':' + az(timeElements[1]));
			return setOriginalContent($(this));
		}
		if (/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{2,4}\s[0-9]{1,2}:[0-9]{1,2}$/.test(value)) { // 18/11/14 15:00
			var datetimeSplit = value.split(' ');

			var dateElements = datetimeSplit[0].split('/');

			var timeElements = datetimeSplit[1].split(':');

			$(this).val(az(dateElements[0]) + '-' + az(dateElements[1]) + '-' + a2z(dateElements[2]) + ' ' + az(timeElements[0]) + ':' + az(timeElements[1]));
			return setOriginalContent($(this));
		}
	}

	$(this).addClass('invalid');

	var element = $(this);

	if (typeof $scope !== 'undefined') {
		$scope.$apply();
	}

	setTimeout(function() {
		element.focus();
	}, 5);
}

$(function() {
	$('.basinput').each(function(index, element) {
		applyBasInput(element);
	});
	$('.basinput').on('click', basinputClick);
	$('.basinput').on('blur', basinputBlur);
});