
            <!-- BEGIN HEADER -->
        
<div class="page-header navbar navbar-fixed-top">

	<?php $auth = Auth::User(); ?>


    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="{{ URL::route($auth->group.'.dashboard') }}">
                <img src="{{ asset(App\Setting::get_setting('logo') ) }}" alt="logo" class="logo-default"/> 
            </a>


        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
            <span></span>
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->

        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">

            @if($auth->group=='owner' && $auth->lng)
            <style>
            .pulsate-crazy-target {
                display: inline-block;
                z-index: 1;
                margin: 8px 0;
            }                            
            </style>

            <div class="pulsate-crazy-target">

            <a href="{{ URL::route('owner.job-postings.add', ['hiring_status' => 'urgent']) }}" class="btn btn-danger sbold btn-md">IMMEDIATE STAFF NEEDED</a>

            @if( App\User::get_meta($auth->id, 'package_amountx') )
                <a href="#owner-trial-period" data-toggle="modal" class="btn btn-danger sbold btn-md">IMMEDIATE STAFF NEEDED</a>
            @endif
            </div>    

            @endif


            <ul class="nav navbar-nav pull-right">


                <!-- BEGIN USER LOGIN DROPDOWN -->
                <li class="dropdown dropdown-user">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <img alt="" class="img-circle" src="{{ has_photo($auth->get_meta($auth->id, 'profile_picture')) }}" width="30" />
                        <span class="username">{{ ucwords($auth->firstname) }}</span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">


                        @foreach(top_nav_menu() as $menu)
                        <li class="nav-item">
                            <a href="{{ $menu['url'] }}" class="nav-link nav-toggle">
                                <i class="icon-{{ $menu['icon'] }}"></i>
                                <span class="title">{{ $menu['title'] }}</span>
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
                                        <span class="title">{{ $sub_menu['title'] }}</span>
                                        <span class="selected"></span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            @endif

                        </li>
                        @endforeach


                        <li class="divider"> </li>
                        <li>
                            <a href="{{ URL::route('auth.logout') }}">
                                <i class="icon-logout"></i> Log Out </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->

    </div>
    <!-- END HEADER INNER -->




</div>

            <!-- END HEADER -->