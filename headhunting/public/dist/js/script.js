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
  
}(jQuery));