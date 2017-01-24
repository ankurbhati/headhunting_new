/* ------------------
 * - Implementation -
 * ------------------
 * The next block of code implements AdminLTE's
 * functions and plugins as specified by the
 * options above.
 */
$(function () {
  "use strict";

  if($("#candidate-form").length > 0) {
  	$("#candidate-form").on('change', '#email', validateCandidate);
  }
  if($("#client-email").length > 0) {
  	$("#client-email").change(validateClient);
  }

	if($('#nca-group').length > 0) {

	  $('#nca_signed').change(function() {
		    var nca = $(this).val();
		    if(nca == 1) {
		    	$('#nca-group').show();
		    } else {
		    	$('#nca-group').hide();
		    }
		});
	}
if($('#msa-group').length > 0) {

	  $('#msa_signed').change(function() {
		    var msa = $(this).val();
		    if(msa == 1) {
		    	$('#msa-group').show();
		    } else {
		    	$('#msa-group').hide();
		    }
		});
	}


  $("[data-mask]").inputmask();
  $("#submitSearch").click(function(e){
  	e.preventDefault();
  	if($('#inputQuery').val() != "") {
  		var flag = setQuery();
  		console.log(flag);
  	}
  	$('#csv_download_input').val("");
  	$("#searchForm").submit();
  });
  
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
	if($('#searchedValue').length > 0 && $("#searchedValue").val() != "") {
		var searchKey = $("#searchedValue").val().split("---").join(' and ').replace('(', '').replace(')', '').replace(' or ', ' and ').split(' and ');
		console.log(searchKey);
		$(".search-view-user").unhighlight().highlight(searchKey);
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
		    	//getThirdParty();
		    } else {
		    	$('input#third_party_id').val('');
		    	$('#third_party_view').hide();
		    }
		});
	  
	  var state_id = $('select#work_state_id').val();
	    if(state_id == 3) {
	    	$('#third_party_view').show();
	    	//getThirdParty();
	    } else {
	    	$('input#third_party_id').val('');
	    	$('#third_party_view').hide();
	    }
  }


  if($('select#type_of_employment').val() !== undefined) {

	  $('select#type_of_employment').change(function() {
		    var type_of_employment_id = $(this).val();
		    if(type_of_employment_id == 3 || type_of_employment_id == 1) {
		    	$('#duration_id').show();
		    } else {
		    	$('#duration').val('');
		    	$('#duration_id').hide();
		    }
		});
	  
	  var type_of_employment_id = $('select#type_of_employment').val();
	    if(type_of_employment_id == 3 || type_of_employment_id == 1) {
	    	$('#duration_id').show();
	    } else {
	    	$('select#type_of_employment').val('');
	    	$('#duration_id').hide();
	    }
  }
 /* 
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
  }*/

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

  var optionsText = {
	  toolbar: {
	    "font-styles": true, // Font styling, e.g. h1, h2, etc.
	    "emphasis": true, // Italics, bold, etc.
	    "lists": true, // (Un)ordered lists, e.g. Bullets, Numbers.
	    "html": false, // Button which allows you to edit the generated HTML.
	    "link": true, // Button to insert a link.
	    "image": false, // Button to insert an image.
	    "color": true, // Button to change color of font
	    "blockquote": true, // Blockquote
	    "size": 'xs' // options are xs, sm, lg
	  }
	};

  /*$("#description").wysihtml5(optionsText);
  $("#disclaimer").wysihtml5(optionsText);
  $("#signature").wysihtml5(optionsText);
  CKEDITOR.replace('description');
  CKEDITOR.replace('disclaimer');
  CKEDITOR.replace('signature');*/
  

 
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
 	$('.from_date').datepicker();
 	$('.to_date').datepicker();
  
	// Create two variable with the names of the months and days in an array
	var monthNames = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ]; 
	var dayNames= ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]

	// Create a newDate() object
	var newDate_india = new Date();
	var newDate_sans_francisco = new Date((newDate_india.getTime() + (newDate_india.getTimezoneOffset() * 60000)) - (3600000*8));
	var newDate_new_york = new Date((newDate_india.getTime() + (newDate_india.getTimezoneOffset() * 60000)) - (3600000*5));
	var newDate_denver = new Date((newDate_india.getTime() + (newDate_india.getTimezoneOffset() * 60000)) - (3600000*7));
	var newDate_chicago = new Date((newDate_india.getTime() + (newDate_india.getTimezoneOffset() * 60000)) - (3600000*6));

	// Output the day, date, month and year   
	$('#Date_sans_francisco').html(dayNames[newDate_sans_francisco.getDay()] + " " + newDate_sans_francisco.getDate() + ' ' + monthNames[newDate_sans_francisco.getMonth()] + ' ' + newDate_sans_francisco.getFullYear());
	$('#Date_new_york').html(dayNames[newDate_new_york.getDay()] + " " + newDate_new_york.getDate() + ' ' + monthNames[newDate_new_york.getMonth()] + ' ' + newDate_new_york.getFullYear());
	$('#Date_denver').html(dayNames[newDate_denver.getDay()] + " " + newDate_denver.getDate() + ' ' + monthNames[newDate_denver.getMonth()] + ' ' + newDate_denver.getFullYear());
	$('#Date_chicago').html(dayNames[newDate_chicago.getDay()] + " " + newDate_chicago.getDate() + ' ' + monthNames[newDate_chicago.getMonth()] + ' ' + newDate_chicago.getFullYear());

	setInterval( function() {
		var newDate_india = new Date();
		var newDate_sans_francisco = new Date((newDate_india.getTime() + (newDate_india.getTimezoneOffset() * 60000)) - (3600000*8));
		var newDate_new_york = new Date((newDate_india.getTime() + (newDate_india.getTimezoneOffset() * 60000)) - (3600000*5));
		var newDate_denver = new Date((newDate_india.getTime() + (newDate_india.getTimezoneOffset() * 60000)) - (3600000*7));
		var newDate_chicago = new Date((newDate_india.getTime() + (newDate_india.getTimezoneOffset() * 60000)) - (3600000*6));
		
		var seconds_sans_francisco = newDate_sans_francisco.getSeconds();
		var seconds_new_york = newDate_new_york.getSeconds();
		var seconds_denver = newDate_denver.getSeconds();
		var seconds_chicago = newDate_chicago.getSeconds();
		// Add a leading zero to seconds value
		$("#sec_sans_francisco").html(( seconds_sans_francisco < 10 ? "0" : "" ) + seconds_sans_francisco);
		$("#sec_new_york").html(( seconds_new_york < 10 ? "0" : "" ) + seconds_new_york);
		$("#sec_denver").html(( seconds_denver < 10 ? "0" : "" ) + seconds_denver);
		$("#sec_chicago").html(( seconds_chicago < 10 ? "0" : "" ) + seconds_chicago);
	},1000);
	
	setInterval( function() {
		var newDate_india = new Date();
		var newDate_sans_francisco = new Date((newDate_india.getTime() + (newDate_india.getTimezoneOffset() * 60000)) - (3600000*8));
		var newDate_new_york = new Date((newDate_india.getTime() + (newDate_india.getTimezoneOffset() * 60000)) - (3600000*5));
		var newDate_denver = new Date((newDate_india.getTime() + (newDate_india.getTimezoneOffset() * 60000)) - (3600000*7));
		var newDate_chicago = new Date((newDate_india.getTime() + (newDate_india.getTimezoneOffset() * 60000)) - (3600000*6));
		
		var minutes_sans_francisco = newDate_sans_francisco.getMinutes();
		var minutes_new_york = newDate_new_york.getMinutes();
		var minutes_denver = newDate_denver.getMinutes();
		var minutes_chicago = newDate_chicago.getMinutes();
		// Add a leading zero to seconds value
		$("#min_sans_francisco").html(( minutes_sans_francisco < 10 ? "0" : "" ) + minutes_sans_francisco);
		$("#min_new_york").html(( minutes_new_york < 10 ? "0" : "" ) + minutes_new_york);
		$("#min_denver").html(( minutes_denver < 10 ? "0" : "" ) + minutes_denver);
		$("#min_chicago").html(( minutes_chicago < 10 ? "0" : "" ) + minutes_chicago);
	},1000);

	setInterval( function() {
		var newDate_india = new Date();
		var newDate_sans_francisco = new Date((newDate_india.getTime() + (newDate_india.getTimezoneOffset() * 60000)) - (3600000*7));
		var newDate_new_york = new Date((newDate_india.getTime() + (newDate_india.getTimezoneOffset() * 60000)) - (3600000*4));
		var newDate_denver = new Date((newDate_india.getTime() + (newDate_india.getTimezoneOffset() * 60000)) - (3600000*6));
		var newDate_chicago = new Date((newDate_india.getTime() + (newDate_india.getTimezoneOffset() * 60000)) - (3600000*5));
		
		var hours_sans_francisco = newDate_sans_francisco.getHours();
		var hours_new_york = newDate_new_york.getHours();
		var hours_denver = newDate_denver.getHours();
		var hours_chicago = newDate_chicago.getHours();
		// Add a leading zero to seconds value
		$("#hours_sans_francisco").html(( hours_sans_francisco < 10 ? "0" : "" ) + hours_sans_francisco);
		$("#hours_new_york").html(( hours_new_york < 10 ? "0" : "" ) + hours_new_york);
		$("#hours_denver").html(( hours_denver < 10 ? "0" : "" ) + hours_denver);
		$("#hours_chicago").html(( hours_chicago < 10 ? "0" : "" ) + hours_chicago);
	},1000);



	$('input[name="checkall"]').on('click', function() {
	    $('input.checkcandidate').prop('checked', this.checked);
	    $('input[name="checkall"]').prop('checked', this.checked);
	});

	$('#download-button').on('click', function() {
		$('#csv_download_input').val(1);
	    $(this).closest('form').submit();
	});

	$('#search-button').on('click', function() {
		$('#csv_download_input').val("");
	    $(this).closest('form').submit();
	});

	$('form[name="candidate_mass_mail"]').on('submit', function(event){
		//event.preventDefault();
		var $form = $(this);
		$('#errormsg').hide();
		var candidate_id = "";
		if(!$('input.checkcandidate:checked').length){
			$('#errormsg').show();
			return false;
		}
		$('input.checkcandidate:checked').each(function(){
			candidate_id = candidate_id+$(this).val()+",";
		});
		$('input[name="candidate_list"]').val(candidate_id);
		//$form.submit();
	});

	bkLib.onDomLoaded(function() {
		if($('#description').length>0){
			new nicEditor({fullPanel : true, iconsPath : '/nicEdit/nicEditorIcons.gif'}).panelInstance('description');
		}
		if($('#disclaimer').length>0){
			new nicEditor({fullPanel : true, iconsPath : '/nicEdit/nicEditorIcons.gif'}).panelInstance('disclaimer');
		}
		if($('#signature').length>0){
			new nicEditor({fullPanel : true, iconsPath : '/	nicEdit/nicEditorIcons.gif'}).panelInstance('signature');
		}
	});
}(jQuery));


//if(typeof(searchingText) !== undefined && typeof(replaceText) !== undefined) {
//	$(document).ready(replaceText());
//}
if($('select#region').length>0){
	getStateForSearch(840);	
}


function getStateForSearch(country){
      $.ajax({
          url: '/states/' + country
      }).done(function(subcategories) {
          // subcategories is json, loop over it and populate the subcategory select
        
          var subcategoryItems = "<option value=''>Select State</option>";
          $.each(subcategories, function(i, item)
          {
                 subcategoryItems+= "<option value='"+ item.state+ "'>" + item.state + "</option>";
          });

          $('select#region').html(subcategoryItems);
          //$('select#region').val(subcategories[0].id);
      });
  }



  function setQuery() {

  	var query = $('#inputQuery').val(),
	    finalQuery = [],
	    queryType = 1;
	if(query.indexOf(' or ') > 0) {
	  var firstPrec = query.split("(");
	  var parent = [],
	      childs = [];
	  for (i in firstPrec) {
	    if(firstPrec[i].indexOf(')') <0 ) {
	      parent.push(firstPrec[i]);
	    } else {
	      var childArr = firstPrec[i].split(')');
	      for(var j in childArr) {
	        if(childArr[j] != ''){
	          childs.push(childArr[j]);
	        }
	      }
	    }
	  }
	  for(var m in childs) {
	    if(childs[m] != ' and ') {
	      var orArr = childs[m].split(' or ');
	      for(var k in orArr) {
	        if(parent.length > 0) {
	          for(var l  in parent) {
	            var andArr = parent[l].split(' and ');
	            if(andArr.length >= 2) {
	              finalQuery.push(parent[l] + orArr[k]);
	            }
	          }
	        }
	      }
	    }
	  }
	  queryType = 2;
	} else {
	  var fin = query.replace('(', '').replace(')', '');
	  var finalQuery = fin.split(' and ');
	  queryType = 3;
	}
	$("#searchQuery").val(finalQuery);
	$("#searchType").val(queryType);

	return true;
}



function validateCandidate(e) {
	if($('#email').val() != "") {
  		$.ajax({
	        url: '/validate-candidate',
	        data: {'email':$('#email').val()},
	        method:'post'
	    }).done(function(response) {
	        var $email = $('#email');
	        if(response.error) {
	        	$email.removeClass('success').addClass('error');
	        	$email.next().text('Email Id either invalid or exists in database');
	        } else {
	        	$email.removeClass('error').addClass('success');
	        	$email.next().text('');
	        }
	    });
	}
  }

function validateClient(e) {
	if($('#client-email').val() != "") {
  		$.ajax({
	        url: '/validate-client',
	        data: {'email':$('#client-email').val()},
	        method:'post'
	    }).done(function(response) {
	        var $email = $('#client-email');
	        if(response.error) {
	        	$email.removeClass('success').addClass('error');
	        	$email.next().text('Email Id either invalid or exists in database');
	        } else {
	        	$email.removeClass('error').addClass('success');
	        	$email.next().text('');
	        }
	    });
	}
  }