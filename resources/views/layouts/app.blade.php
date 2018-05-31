
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="{{ config('app.locale') }}">
    <!--<![endif]-->


    <!-- BEGIN HEAD -->
    @include('partials.header')
    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
        <div class="page-wrapper">

            <!-- BEGIN HEADER -->
           @include('partials.header-nav')        
            <!-- END HEADER -->

            

            <!-- BEGIN HEADER & CONTENT DIVIDER -->
            <div class="clearfix"> </div>
            <!-- END HEADER & CONTENT DIVIDER -->

            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                

                <!-- BEGIN SIDEBAR -->
                <div class="page-sidebar-wrapper">

                <!-- BEGIN SIDEBAR -->
                <div class="page-sidebar navbar-collapse collapse">
                    <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                        <li class="sidebar-toggler-wrapper ">
                            <div class="sidebar-toggler">
                                <span></span>
                            </div>
                        </li>         
                        <li class="margin-top-10"></li>
                        @foreach(sidebar_menu() as $menu)
                        <li class="nav-item">
                            <a href="{{ $menu['url'] }}" class="nav-link nav-toggle">
                                <i class="icon-{{ $menu['icon'] }}"></i>
                                <span class="title">{!! $menu['title'] !!}</span>
                                 <span class="selected"></span>
                                 @if( $menu['sub_menu'] )
                                 <span class="arrow"></span>
                                @endif
                            </a>

                            @if( $menu['sub_menu'] )
                            <ul class="sub-menu">
                                @foreach($menu['sub_menu'] as $sub_menu)
                                <li class="nav-item">
                                    <a href="{{ $sub_menu['url'] }}" class="nav-link ">
                                        <span class="title">{!! $sub_menu['title'] !!}</span>
                                        <span class="selected"></span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            @endif

                        </li>
                        @endforeach
                    </ul>
                    <!-- END SIDEBAR MENU -->

                </div>
                <!-- END SIDEBAR -->
            </div>               
            <!-- END SIDEBAR -->

                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <!-- BEGIN PAGE BAR -->
                        <div class="page-bar">
                            @yield('breadcrumb')

        
                        </div>
                        <!-- END PAGE BAR -->            



                        @yield('content')     


                    </div>
                    <!-- END CONTENT BODY -->
                </div>
                <!-- END CONTENT -->

    
            </div>
            <!-- END CONTAINER -->

            <!-- BEGIN FOOTER -->
            @include('partials.footer')
            <!-- END FOOTER -->


        </div>

         @include('partials.script')

    </body>
</html>