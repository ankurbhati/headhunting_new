<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title>{{$title}}</title>

	
	{{ HTML::style('bootstrap/css/bootstrap.min.css'); }}
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Theme style -->
    {{ HTML::style('dist/css/AdminLTE.min.css'); }}
	{{ HTML::style('dist/css/skins/skin-blue.min.css'); }}
	<!-- iCheck -->
	{{ HTML::style('plugins/iCheck/square/blue.css'); }}

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="/"><b>Headhunting</b></a>
      </div><!-- /.login-logo -->
        @yield('content')
    </div><!-- /.login-box -->

	<!-- jQuery 2.1.4 -->
    {{ HTML::script("plugins/jQuery/jQuery-2.1.4.min.js")}}
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	{{ HTML::script("bootstrap/js/bootstrap.min.js")}}
    <!-- iCheck -->4
    {{ HTML::script("plugins/iCheck/icheck.min.js")}}
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>
</html>
