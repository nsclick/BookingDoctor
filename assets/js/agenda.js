(function(w, $) {
$(document).ready(function() {
	var calendar_container = $('#calendar');
		
		var available_days = {};
		$.each(w.calendar_agenda_days, function(index, day) {
			if (day.available) {
				available_days[day.cls] = 'available';
			}
		});

		calendar_container.calendar({
			language: 		'es-ES',
			events_source: 	[],
			tmpl_path: 		'assets/vendor/calendarjs/tmpls/',
			classes: 		{
				'months': available_days
			},
			views: {
				year: {
					enable: false
				},
				week: {
					enable: false
				}
			}
		});
	
	
});
})(window, jQuery);