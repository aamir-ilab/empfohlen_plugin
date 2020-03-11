/**
 * jQuery DOB Picker
 * Website: https://github.com/tyea/dobpicker
 * Version: 1.0
 * Author: Tom Yeadon
 * License: BSD 3-Clause
 */

jQuery.extend({

	dobPicker: function(params) {

		// set the defaults		
		if (typeof(params.dayDefault) === 'undefined') params.dayDefault = 'Day';
		if (typeof(params.monthDefault) === 'undefined') params.monthDefault = 'Month';
		if (typeof(params.yearDefault) === 'undefined') params.yearDefault = 'Year';
		if (typeof(params.minimumAge) === 'undefined') params.minimumAge = 12;
		if (typeof(params.maximumAge) === 'undefined') params.maximumAge = 80;

		// set the default messages		
		jQuery(params.daySelector).append('<option value="">' + params.dayDefault + '</option>');
		jQuery(params.monthSelector).append('<option value="">' + params.monthDefault + '</option>');
		jQuery(params.yearSelector).append('<option value="">' + params.yearDefault + '</option>');


		var day_value 	= (typeof(jQuery(params.daySelector).data('day')) !== 'undefined')?( jQuery(params.daySelector).data('day')):0;
		var month_value = (typeof(jQuery(params.monthSelector).data('month')) !== 'undefined')?(jQuery(params.monthSelector).data('month')):0;
		var year_value 	= (typeof(jQuery(params.yearSelector).data('year')) !== 'undefined')?(parseInt(jQuery(params.yearSelector).data('year'))):0;
		


		// populate the day select
		for (i = 1; i <= 31; i++) {
			if (i <= 9) {
				var val = '0' + i;
			} else {
				var val = i;
			}
			jQuery(params.daySelector).append('<option value="' + val + '" '+((val==day_value)?('selected="selected"'):'')+' >' + i + '</option>');
		}

		// populate the month select		
		var months = [
			"January",
			"February",
			"March",
			"April",
			"May",
			"June",
			"July",
			"August",
			"September",
			"October",
			"November",
			"December"
		];
			
		for (i = 1; i <= 12; i++) {
			if (i <= 9) {
				var val = '0' + i;
			} else {
				var val = i;
			}
			jQuery(params.monthSelector).append('<option value="' + val + '"  '+((val==month_value)?('selected="selected"'):'')+' >' + months[i - 1] + '</option>');
		}

		// populate the year select
		var date = new Date();
		var year = date.getFullYear();
		var start = year - params.minimumAge;
		var count = start - params.maximumAge;
		
		for (i = start; i >= count; i--) {
			jQuery(params.yearSelector).append('<option value="' + i + '"  '+((i==year_value)?('selected="selected"'):'')+'  >' + i + '</option>');
		}
		
		// do the logic for the day select
		jQuery(params.daySelector).change(function() {
			
			jQuery(params.monthSelector)[0].selectedIndex = 0;
			jQuery(params.yearSelector)[0].selectedIndex = 0;
			jQuery(params.yearSelector + ' option').removeAttr('disabled');
			
			if (jQuery(params.daySelector).val() >= 1 && jQuery(params.daySelector).val() <= 29) {
			
				jQuery(params.monthSelector + ' option').removeAttr('disabled');
				
			} else if (jQuery(params.daySelector).val() == 30) {
			
				jQuery(params.monthSelector + ' option').removeAttr('disabled');
				jQuery(params.monthSelector + ' option[value="02"]').attr('disabled', 'disabled');
				
			} else if(jQuery(params.daySelector).val() == 31) {
			
				jQuery(params.monthSelector + ' option').removeAttr('disabled');
				jQuery(params.monthSelector + ' option[value="02"]').attr('disabled', 'disabled');
				jQuery(params.monthSelector + ' option[value="04"]').attr('disabled', 'disabled');
				jQuery(params.monthSelector + ' option[value="06"]').attr('disabled', 'disabled');
				jQuery(params.monthSelector + ' option[value="09"]').attr('disabled', 'disabled');
				jQuery(params.monthSelector + ' option[value="11"]').attr('disabled', 'disabled');
				
			}
			
		});
		
		// do the logic for the month select
		jQuery(params.monthSelector).change(function() {
			
			jQuery(params.yearSelector)[0].selectedIndex = 0;
			jQuery(params.yearSelector + ' option').removeAttr('disabled');
			
			if (jQuery(params.daySelector).val() == 29 && jQuery(params.monthSelector).val() == '02') {
			
				jQuery(params.yearSelector + ' option').each(function(index) {
					if (index !== 0) {
						var year = jQuery(this).attr('value');
						var leap = !((year % 4) || (!(year % 100) && (year % 400)));
						if (leap === false) {
							jQuery(this).attr('disabled', 'disabled');
						}
					}
				});
				
			}
			
		});
		
	}
	
});




jQuery(document).ready(function(){
  jQuery.dobPicker({
    daySelector: '#dobday', /* Required */
    monthSelector: '#dobmonth', /* Required */
    yearSelector: '#dobyear', /* Required */
    minimumAge: 8, /* Optional */
    maximumAge: 100 /* Optional */
  });
});