<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width,initial-scale=1,maximum-scale=1'>
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title>{{$title}} - Headhunting</title>
    {{ HTML::style('dist/css/main.css'); }}
    {{ HTML::style('glyphicons/css/bootstrap.min.css'); }}
    {{ HTML::style('plugins/datepicker/datepicker3.css'); }}
	{{ HTML::style('plugins/datatables/dataTables.bootstrap.css'); }}
	{{ HTML::style('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css'); }} 
	{{ HTML::style('plugins/ckeditor/skins/moono/editor.css'); }} 
	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
    <body class="theme-body">
    	<div class="home">
			@include('shared.new-header')
			<div class="content-wrapper ofBody">
				<div class="fill container-fluid">
					<div class="container-body">
				        <div id="status-area">
			        		<!--<span class="flash_message " style="display: inline;">This is a message!</span>-->
			        	</div>
			        	<ol class="breadcrumb">
				            <li><a href="{{ URL::route('dashboard-view') }}"><i class="fa fa-dashboard"></i> Home</a></li>
				            <li class="active">Dashboard</li>
						</ol>
		        			@yield('content')
	        		</div>
        		</div>
        	</div>
        </div>

{{ HTML::script("plugins/jQuery/jQuery-2.1.4.min.js")}}
{{ HTML::script("plugins/jQuery/jQuery-highlighter.js")}}
{{ HTML::script("dist/js/vendor/vendor/jquery-ui.js")}}
{{ HTML::script("dist/js/vendor/vendor/modernizr.custom.js")}}
{{ HTML::script("dist/js/vendor/vendor/jquery.ui.widget.js")}}
{{ HTML::script("dist/js/vendor/vendor/jquery-file-upload.js")}}
{{ HTML::script("bootstrap/js/bootstrap.min.js")}}
<!-- datepicker -->
{{ HTML::script("plugins/daterangepicker/daterangepicker.js")}}
<!-- datepicker -->
{{ HTML::script("plugins/datepicker/bootstrap-datepicker.js")}}
<!-- Bootstrap WYSIHTML5 -->

{{ HTML::script("plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js")}}
{{ HTML::script("plugins/ckeditor/ckeditor.js")}}
<!-- Slimscroll -->
{{ HTML::script("plugins/datatables/jquery.dataTables.min.js")}}
{{ HTML::script("plugins/datatables/dataTables.bootstrap.min.js")}}
{{ HTML::script("plugins/slimScroll/jquery.slimscroll.min.js")}}
{{ HTML::script("plugins/fastclick/fastclick.min.js")}}
{{ HTML::script("plugins/input-mask/jquery.inputmask.js")}}
{{ HTML::script("plugins/input-mask/jquery.inputmask.date.extensions.js")}}
{{ HTML::script("dist/js/vendor/vendor/moment.js")}}
<!-- endbuild -->

{{ HTML::script("/nicEdit/nicEdit.js")}}
<!-- build:js({source, dist}) js/main.js -->
{{ HTML::script("dist/js/script.js")}}
{{ HTML::script("dist/js/modules/util.js")}}
{{ HTML::script("dist/js/modules/header.js")}}
{{ HTML::script("dist/js/modules/plugins.js")}}

	    <script>
	    @if(Session::has('flashmessagetxt'))
			var message_text = {{ "'".Session::get('flashmessagetxt') ."'"}};
			//alert(message_text);

			(function($) {
			    $.fn.flash_message = function(options) {
			      
			      options = $.extend({
			        text: 'Done',
			        time: 1000,
			        how: 'before',
			        class_name: ''
			      }, options);
			      
			      return $(this).each(function() {
			        if( $(this).parent().find('.flash_message').get(0) )
			          return;
			        
			        var message = $('<span />', {
			          'class': 'flash_message ' + options.class_name,
			          text: options.text
			        }).hide().fadeIn('fast');
			        
			        $(this)[options.how](message);
			        
			        message.delay(options.time).fadeOut('normal', function() {
			          $(this).remove();
			        });
			        
			      });
			    };
			})(jQuery);

		    $('#status-area').flash_message({
		        text: message_text,
		        how: 'append'
		    });
		@endif


	      (function () {
	          var employees = $('#employeeList');
	            if(employees.attr('id')) {
	        		//var table = employees.DataTable();
	        		var table = employees.DataTable({"aLengthMenu": [ 100], paging: false, bInfo : false, searching: false, "bSort": false});
			      	table.on( 'draw', function () {
				        var body = $( table.table().body() );
				 
				        body.unhighlight();
				        body.highlight( table.search() );  
			    	});
	            }
	      })();
    </script>
    </body>
</html>
