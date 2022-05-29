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