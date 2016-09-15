/* ------------------
 * - Implementation -
 * ------------------
 * The next block of code implements AdminLTE's
 * functions and plugins as specified by the
 * options above.
 */
$(function () {
  "use strict";

  $("[data-mask]").inputmask();
  if($('select#state_id').val() !== undefined) {

	  $('select#country_id').change(function() {
		    var country = $(this).val();
		    getState(country);
		});
	  
	  var countryVal = $('select#country_id').val();
	  if(countryVal != "") {
		  getState(countryVal);
	  }
  }

  if($('select#roles').val() !== undefined) {

	  $('select#roles').change(function() {
		    var role = $(this).val();
		    if(role > 1 && role%2 == 0) {
		    	$('#mentor_id_view').show();
		    	getPeer(role);
		    } else {
		    	$('select#mentor_id').val('');
		    	$('#mentor_id_view').hide();
		    }
		});
	  
	  var role = $('select#roles').val();
	    if(role > 1 && role%2 == 0) {
	    	$('#mentor_id_view').show();
	    	getPeer(role);
	    } else {
	    	$('select#mentor_id').val('');
	    	$('#mentor_id_view').hide();
	    }
  }

  if($('select#work_state_id').val() !== undefined) {

	  $('select#work_state_id').change(function() {
		    var state_id = $(this).val();
		    if(state_id == 3) {
		    	$('#third_party_view').show();
		    	getThirdParty();
		    } else {
		    	$('select#third_party_id').val('');
		    	$('#third_party_view').hide();
		    }
		});
	  
	  var state_id = $('select#work_state_id').val();
	    if(state_id == 3) {
	    	$('#third_party_view').show();
	    	getThirdParty();
	    } else {
	    	$('select#third_party_id').val('');
	    	$('#third_party_view').hide();
	    }
  }
  
  function getThirdParty() {
	  $.ajax({
	        url: '/third_party/'
	    }).done(function(subcategories) {
	        // subcategories is json, loop over it and populate the subcategory select

	        var subcategoryItems = "";
	        $.each(subcategories, function(i, item)
	        {
	               subcategoryItems+= "<option value='"+ item.id+ "'>" + item.email + "</option>";
	        });
	        $('select#third_party_id').html(subcategoryItems);
	        $('select#third_party_id').val(subcategories[0].id);
	    });
  }

  function getPeer(role) {
	  $.ajax({
	        url: '/peers/' + role
	    }).done(function(subcategories) {
	        // subcategories is json, loop over it and populate the subcategory select

	        var subcategoryItems = "";
	        $.each(subcategories, function(i, item)
	        {
	               subcategoryItems+= "<option value='"+ item.user_id+ "'>" + item.user.first_name + " " + item.user.last_name + "</option>";
	        });
	        $('select#mentor_id').html(subcategoryItems);
	        $('select#mentor_id').val(subcategories[0].user_id);
	    });
  }

  $("#description").wysihtml5();
  function getState(country){
	    $.ajax({
	        url: '/states/' + country
	    }).done(function(subcategories) {
	        // subcategories is json, loop over it and populate the subcategory select
	    	
	        var subcategoryItems = "";
	        $.each(subcategories, function(i, item)
	        {
	               subcategoryItems+= "<option value='"+ item.id+ "'>" + item.state + "</option>";
	        });

	        $('select#state_id').html(subcategoryItems);

	        if($("#state").val() > 0) {

	        	$('select#state_id').val($("#state").val());
	        } else {

	        	$('select#state_id').val(subcategories[0].id);
	        }
	    });
  }

 	$('#datepicker').datepicker();
  
	// Create two variable with the names of the months and days in an array
	var monthNames = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ]; 
	var dayNames= ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]

	// Create a newDate() object
	var newDate_india = new Date();
	var newDate_usa = new Date((newDate_india.getTime() - (newDate_india.getTimezoneOffset() * 60000)) - (3600000*4));

	// Extract the current date from Date object
	//newDate_india.setDate(newDate_india.getDate());
	//newDate_usa.setDate(newDate_usa.getDate());

	// Output the day, date, month and year   
	$('#Date_india').html(dayNames[newDate_india.getDay()] + " " + newDate_india.getDate() + ' ' + monthNames[newDate_india.getMonth()] + ' ' + newDate_india.getFullYear());
	$('#Date_gmt').html(dayNames[newDate_india.getUTCDay()] + " " + newDate_india.getUTCDate() + ' ' + monthNames[newDate_india.getUTCMonth()] + ' ' + newDate_india.getUTCFullYear());
	$('#Date_usa').html(dayNames[newDate_usa.getDay()] + " " + newDate_usa.getDate() + ' ' + monthNames[newDate_usa.getMonth()] + ' ' + newDate_usa.getFullYear());

	setInterval( function() {
		var newDate_india = new Date();
		
		var newDate_usa = new Date((newDate_india.getTime() + (newDate_india.getTimezoneOffset() * 60000)) - (3600000*4));

		var seconds_india = newDate_india.getSeconds();
		var seconds_gmt = newDate_india.getUTCSeconds();
		var seconds_usa = newDate_usa.getSeconds();
		// Add a leading zero to seconds value
		$("#sec_india").html(( seconds_india < 10 ? "0" : "" ) + seconds_india);
		$("#sec_gmt").html(( seconds_gmt < 10 ? "0" : "" ) + seconds_gmt);
		$("#sec_usa").html(( seconds_usa < 10 ? "0" : "" ) + seconds_usa);
	},1000);
		
	setInterval( function() {
		var newDate_india = new Date();
		var newDate_usa = new Date((newDate_india.getTime() + (newDate_india.getTimezoneOffset() * 60000)) - (3600000*4));

		var minutes_india = newDate_india.getMinutes();
		var minutes_gmt = newDate_india.getUTCMinutes();
		var minutes_usa = newDate_usa.getMinutes();
		// Add a leading zero to the minutes value
		$("#min_india").html(( minutes_india < 10 ? "0" : "" ) + minutes_india);
		$("#min_gmt").html(( minutes_gmt < 10 ? "0" : "" ) + minutes_gmt);
		$("#min_usa").html(( minutes_usa < 10 ? "0" : "" ) + minutes_usa);
	},1000);
		
	setInterval( function() {
		var newDate_india = new Date();
		var newDate_usa = new Date((newDate_india.getTime() + (newDate_india.getTimezoneOffset() * 60000)) - (3600000*4));

		var hours_india = newDate_india.getHours();
		var hours_gmt = newDate_india.getUTCHours();
		var hours_usa = newDate_usa.getHours();
		// Add a leading zero to the hours value
		$("#hours_india").html(( hours_india < 10 ? "0" : "" ) + hours_india);
		$("#hours_gmt").html(( hours_gmt < 10 ? "0" : "" ) + hours_gmt);
		$("#hours_usa").html(( hours_usa < 10 ? "0" : "" ) + hours_usa);
	}, 1000);

}(jQuery));