<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width,initial-scale=1,maximum-scale=1'>
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title>{{$title}}</title>

    {{ HTML::style('dist/css/main.css'); }}
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

  <body class="theme-body login-body" >
    <div class="home">
      <header>
        <div class="top-header-wrapper">
          <div class="container">
            <a class="logo" href="/home.html">
              <img src="/assets/images/logo.png" alt="logo"/>
            </a>
            <ul class="header-items">
              <li class="login-container">
                <a class="icon-member_signout member-login" href="">
                  <span class="hidden-moblet">MEMBER LOGIN</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </header>
        <div class="container">
            <div class="login-box">
              @yield('content')
            </div>
        </div>
      <footer>
        <div class="container">
          <div class="legal-nav">
            <div class="copyright">Apetan | © 2017</div>
            <ul>
              <li>
                <a href="/">Contact Us</a>
              </li>
              <li>
                <a href="/">About Us</a>
              </li>
            </ul>
          </div>
        </div>
      </footer>
    </div>
  </body>
</html>
