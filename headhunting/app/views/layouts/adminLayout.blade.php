<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title>{{$title}} - Headhunting</title>

	{{ HTML::style('bootstrap/css/bootstrap.min.css'); }}
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Theme style -->
    {{ HTML::style('dist/css/AdminLTE.min.css'); }}
	{{ HTML::style('dist/css/skins/skin-blue.min.css'); }}
	<!-- iCheck -->
	{{ HTML::style('plugins/iCheck/flat/blue.css'); }}
    {{ HTML::style('plugins/datepicker/datepicker3.css'); }}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    {{ HTML::style('plugins/daterangepicker/daterangepicker-bs3.css'); }}
    {{ HTML::style('plugins/datatables/dataTables.bootstrap.css'); }}
	{{ HTML::style('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css'); }} 
	{{ HTML::style('dist/css/style.css'); }}

  </head>
     <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
			@include('shared.header')
			@if(Auth::check())
				@include('shared.leftAside')
			@endIf
			<div class="content-wrapper">
			
			    <section class="content-header">
		          <h1>
		            {{$title}}
		            <small>Control panel</small>
		          </h1>
		          <ol class="breadcrumb">
		            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		            <li class="active">Dashboard</li>
		          </ol>
		        </section>
		        <div id="status-area" style="text-align: center; margin-top: 10px;">
	        		<!--<span class="flash_message " style="display: inline;">This is a message!</span>-->
	        	</div>
        		@yield('content')
        	</div>
        	@include('shared.footer')
        </div>
        
        <!-- jQuery 2.1.4 -->
    	{{ HTML::script("plugins/jQuery/jQuery-2.1.4.min.js")}}
    	{{ HTML::script("plugins/jQuery/jQuery-highlighter.js")}}
	    <!-- jQuery UI 1.11.4 -->
	    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
	    <script>
	      $.widget.bridge('uibutton', $.ui.button);
	    </script>
	    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	    {{ HTML::script("bootstrap/js/bootstrap.min.js")}}
	    <!-- daterangepicker -->
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
	    {{ HTML::script("plugins/daterangepicker/daterangepicker.js")}}
	    <!-- datepicker -->
	    {{ HTML::script("plugins/datepicker/bootstrap-datepicker.js")}}
	    <!-- Bootstrap WYSIHTML5 -->
	
	    {{ HTML::script("plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js")}}
	    <!-- Slimscroll -->
		{{ HTML::script("plugins/datatables/jquery.dataTables.min.js")}}
	    {{ HTML::script("plugins/datatables/dataTables.bootstrap.min.js")}}
	    {{ HTML::script("plugins/slimScroll/jquery.slimscroll.min.js")}}
	    {{ HTML::script("plugins/fastclick/fastclick.min.js")}}
	    {{ HTML::script("dist/js/app.min.js")}}
	    {{ HTML::script("dist/js/pages/dashboard.js")}}
    	{{ HTML::script("plugins/input-mask/jquery.inputmask.js")}}
	    {{ HTML::script("plugins/input-mask/jquery.inputmask.date.extensions.js")}}
	    {{ HTML::script("dist/js/script.js")}}
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
	        		var table = employees.DataTable({"aLengthMenu": [ 100, 50, 25, 10]});
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
