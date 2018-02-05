<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="@yield('keywordseo')">
    <meta name="description" content="@yield('descseo')">

    @if($setting !== '')
    <meta name="author" content="{{ $setting->author }}">
    <link href="{{ $setting->favicon }}" rel="shortcut icon" type="image/x-icon" />

    <meta name="google-site-verification" content="{{ $setting->googlewebmaster }}" />
    <meta name="msvalidate.01" content="{{ $setting->bingwebmaster }}" />
    <meta name='alexaVerifyID' content="{{ $setting->alexa }}" />

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $setting->googleanalytic }}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', '{{ $setting->googleanalytic }}');
    </script>


    <meta name="revisit-after" content="{{ $setting->revistafter }}">
    <meta name="robots" content="{{ $setting->robots }}">
    @else
    <meta name="author" content="Abed Putra">
    <link href="" rel="shortcut icon" type="image/x-icon" />

    <meta name="revisit-after" content="">
    <meta name="robots" content="">
    @endif
    <title>@yield('titleseo')</title>

    @if($setting !== '')
    <!-- Bootstrap Core CSS -->
      <link href="{{ $setting->theme }}" rel="stylesheet">
    @else
      <link href="{{ asset('public/css/app.css') }}" rel="stylesheet">
    @endif

    <!-- Custom CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
    <link href="{{ asset('public/css/landing-page.css') }}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{ asset('public/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top topnav" role="navigation">
        <div class="container topnav">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                @if($setting !== '')
                <a class="navbar-brand topnav" href="{{ url('/') }}">{{ $setting->title_site }}</a>
                @else
                <a class="navbar-brand topnav" href="{{ url('/') }}">Default Theme</a>
                @endif

            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                <ul class="nav navbar-nav navbar-right">
                  @foreach($menu as $item)
                    @if($item->mainmenu_link != '#')
                      <li>
                          <a href="{{ $item->mainmenu_link }}">{{ $item->mainmenu_name }}</a>
                      </li>
                    @endif
                  @if ($item->submenu->count())
                    <li class="dropdown">
                      <a href="" role="button" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle" data-toggle="dropdown">{{ $item->mainmenu_name }} <b class="caret"></b></a>
                      <ul class="dropdown-menu">
                        @foreach ($item->submenu as $subitem)
                            <li><a href="{{$subitem->submenu_link}}">{{ $subitem->submenu_name }}</a></li>
                        @endforeach
                      </ul>
                    </li>
                  @endif
                  @endforeach

                  @if (Route::has('login'))
                    @auth
                        <li>
                          <a href="{{ action('HomeController@index') }}" style="background: #cfcfcf;">Dashboard</a>
                        </li>
                    @endauth
                  @endif
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    @yield('content')

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <p class="copyright text-muted small">Copyright &copy; <?php echo date("Y"); ?> Powered by <a href="http://abedputra.com/">Abed Putra</a>, <a href="http://abedputra.com/golavacms">GoLavaCMS</a> & <a href="https://laravel.com/">Laravel</a>, design by <a href="https://startbootstrap.com/">Start Bootstrap</a>.<br>All Rights Reserved</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Javascript -->
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="{{ asset('public/js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
    <script type="text/javascript">
    	$(document).ready(function(){
            //FANCYBOX
            //https://github.com/fancyapps/fancyBox
            $(".fancybox").fancybox({
                openEffect: "none",
                closeEffect: "none"
            });
        });
    </script>
</body>

</html>
