

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-full-width">
        <div class="page-wrapper">

            <!-- BEGIN HEADER -->
            @include('partials.header')
            <!-- BEGIN HEADER -->
    

            <div class="page-header navbar navbar-fixed-top">

                <!-- BEGIN HEADER INNER -->
                <div class="page-header-inner ">
                    <!-- BEGIN LOGO -->
                    <div class="page-logo">
                        <a href="//{{ $_SERVER['HTTP_HOST'] }}">
                            <img src="{{ asset(App\Setting::get_setting('logo') ) }}" alt="logo" class="logo-default"/> 
                        </a>
                    </div>
                    <!-- END LOGO -->
                    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                    <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                        <span></span>
                    </a>
                    <!-- END RESPONSIVE MENU TOGGLER -->

                    <div class="hor-menu hidden-xs">
                        <ul class="nav navbar-nav">
                            <li>
                                <a href="{{ URL::route('find.jobs.index') }}"> Find Jobs</a>
                            </li>
                            <li>
                                <a href="{{ URL::route('find.employees.index') }}"> Find Employees</a>
                            </li>
                        </ul>
                    </div>

                    <!-- BEGIN TOP NAVIGATION MENU -->
                    <div class="top-menu hidden-xs">
                        <ul class="nav navbar-nav pull-right">
                            <li><a href="{{ URL::route('auth.login') }}">Log In</a></li>
                            <li><a href="{{ URL::route('auth.register') }}">Sign Up</a></li>
                        </ul>
                    </div>
                    <!-- END TOP NAVIGATION MENU -->

                </div>
                <!-- END HEADER INNER -->

            </div>
            <!-- END HEADER -->     
            

            <!-- BEGIN HEADER & CONTENT DIVIDER -->
            <div class="clearfix"> </div>
            <!-- END HEADER & CONTENT DIVIDER -->

            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                

                <div class="page-sidebar-wrapper">
                    <!-- BEGIN SIDEBAR -->
                    <div class="page-sidebar navbar-collapse collapse" style="">
                        <!-- END SIDEBAR MENU -->
                        <div class="page-sidebar-wrapper">
                            <!-- BEGIN RESPONSIVE MENU FOR HORIZONTAL & SIDEBAR MENU -->
                            <ul class="page-sidebar-menu visible-xs  page-header-fixed" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                                <li class="nav-item start">
                                    <a href="{{ URL::route('find.jobs.index') }}"> Find Jobs</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ URL::route('find.employees.index') }}"> Find Employees</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ URL::route('auth.register', [1, 'owner']) }}"> I want to Hire</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ URL::route('auth.register', [2, 'provider']) }}"> I want to Work</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ URL::route('auth.login') }}"> Log In</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ URL::route('auth.register') }}"> Sign Up</a>
                                </li>
                            </ul>
                            <!-- END RESPONSIVE MENU FOR HORIZONTAL & SIDEBAR MENU -->
                        </div>
                    </div>
                    <!-- END SIDEBAR -->
                </div>


                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <!-- BEGIN PAGE BAR -->
                        <div class="page-bar">
                            @yield('breadcrumb')

        
                        </div>
                        <!-- END PAGE BAR -->            

                        <style>
                        .bg-1 {
                            background: #62c9f2;
                            margin: 0 -20px;
                            padding: 0px 20px;
                        }    
                        .text-white {
                            color: #fff !important;
                        }
                        .btn-find {
                            color: #fff;
                            font-weight: bold;    
                            border-radius: 3px !important;
                        }
                        .btn-hire {
                            border: 1px solid #2e94d5;
                            background: #2e94d5;
                            margin-right: 10px;
                            border-bottom: 2px solid #1075b5;                            
                        }
                        .btn-hire:hover {
                            background-color: #fff;   
                            color: #2e94d5;
                        }
                        .btn-work {
                            border: 1px solid #fff;
                            border-bottom: 2px solid #ffffff; 
                        }
                        .btn-work:hover {
                            border: 1px solid #fff;
                            border-bottom: 2px solid #ffffff; 
                            background-color: #2e94d5;   
                            color: #fff;
                        }
                        .hor-menu li a {
                            font-weight: bold !important;
                            color: #474946 !important;
                        }
                        .page-header.navbar .hor-menu .navbar-nav>li>a:hover {
                            background: #eee !important;
                        }
                        .top-menu .navbar-nav a {
                            padding:  15px;
                        }
                        .top-menu li a {
                            font-weight: bold !important;
                            color: #474946 !important;
                        }
                        .page-header.navbar .menu-toggler.responsive-toggler {
                            margin-top: 30px;
                        }
                        .sign-devider {
                            border-top: 1px solid #DEDEDE;
                            text-align: center;
                        }
                        .sign-devider span {
                            position: relative;
                            padding: 0 10px;
                            background: #fff;
                            font-weight: 700;
                            top: -11px;
                        }

                        </style>

                        <div class="bg-1">    
                            <div class="row">
                                <div class="col-md-6 col-sm-6">

                                    <!-- BEGIN PAGE TITLE-->
                                    <h1 class="page-title text-white sbold"><i class="fa fa-search"></i> {!! $title !!} </h1>
                                    <!-- END PAGE TITLE-->    
                                    
                                </div>
                                <div class="col-md-6 col-sm-6 text-right hidden-xs">
                                    <div class="margin-top-20">
                                        <a href="{{ URL::route('auth.register', [1, 'owner']) }}" class="btn btn-lg btn-find btn-hire">I want to Hire</a>
                                        <a href="{{ URL::route('auth.register', [2, 'provider']) }}" class="btn btn-lg btn-find btn-work">I want to Work</a>                    
                                    </div>
                                </div>    
                            </div>
                        </div>

                    
                        @yield('content')     


                    </div>
                    <!-- END CONTENT BODY -->
                </div>
                <!-- END CONTENT -->



                <div class="modal fade" id="basic" tabindex="-1">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <div class="text-center">
                                    <img src="{{ asset(App\Setting::get_setting('logo') ) }}" alt="logo" class="logo-default"/> 
                                </div>
                            </div>


                            <div class="modal-body">
                                <!-- BEGIN LOGIN -->
                                <form class="form-horizontal" role="form" method="post" action="{{ URL::route('auth.login') }}">
                                    
                                    {{ csrf_field() }}
                                    <input type="hidden" name="op" value="1">

                                    <div class="row">
                                        <div class="col-md-12 col-centered">
                                            <a href="{{ URL::route('socialite.connect', 'facebook') }}" class="btn btn-primary btn-facebook btn-block"><i class="fa fa-facebook"></i> Sign in with Facebook</a>
                                            <a href="{{ URL::route('socialite.connect', 'google') }}" class="btn btn-danger btn-google btn-block"><i class="fa fa-google"></i> Sign in with Google</a>   
                                        </div>
                                    </div>

                                   <div class="sign-devider margin-top-20"><span>OR</span></div>

                                    <div class="row">
                                        <div class="col-md-12 col-centered">
                                            <input type="text" class="form-control" name="email" placeholder="Email" value=""> 
                                            <div class="margin-top-10">
                                                <input type="password" class="form-control" name="password" placeholder="Password" value="">
                                            </div>
                                            <div class="margin-top-10">
                                                <label class="mt-checkbox">
                                                <input type="checkbox" name="remember" value="1"> Remember me
                                                <span></span>
                                                </label>
                                            </div>
                                            <button type="submit" class="btn blue btn-primary btn-block">Sign In</button>


                                    <p class="text-center">Become a member? <a href="{{ URL::route('auth.register') }}">Register Now!</a>
                                        <br>
                                        <a class="btn btn-link" href="{{ route('auth.forgot-password') }}">
                                        Forgot Your Password?
                                        </a>
                                    </p>
                                       
                                        </div>
                                    </div>


                                </form>
                                <!-- END LOGIN -->
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>

    
            </div>
            <!-- END CONTAINER -->

            <!-- BEGIN FOOTER -->
            @include('partials.footer')
            <!-- END FOOTER -->


        </div>

         @include('partials.script')

    </body>