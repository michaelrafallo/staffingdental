<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->

    <!-- BEGIN HEAD -->
    @include('partials.header')
    <!-- END HEAD -->

    <style>
    .col-centered {
        float: none;
        margin: 0 auto;
    }   
    </style>

    <body class="page-sidebar-closed-hide-logo page-content-white page-full-width">

        <div class="page-wrapper">
           
            <!-- BEGIN HEADER & CONTENT DIVIDER -->
            <div class="clearfix"> </div>
            <!-- END HEADER & CONTENT DIVIDER -->
            <!-- BEGIN CONTAINER -->
            <div class="page-container">
    
                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                            
                        <div class="margin-top-20">
                        @yield('content')                            
                        </div>


                    </div>
                </div>
                <!-- END QUICK SIDEBAR -->
            </div>
            <!-- END CONTAINER -->

            <!-- BEGIN FOOTER -->
            @include('partials.footer')
            <!-- END FOOTER -->

        </div>

         @include('partials.script')


    </body>

</html>







