<div class="page-footer grade">
    <div class="page-footer-inner"> {{ date('Y') }} &copy; {{ App\Setting::get_setting('copy_right') }}
    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>

@if( Auth::check() )
	<?php $suid = @Session::get('user_id'); ?>
	@if( @App\User::find($suid)->group == 'admin' )
	<nav class="quick-nav">
	    <a class="quick-nav-trigger" href="#0">
	        <span aria-hidden="true"></span>
	    </a>
	    <ul>
	        <li>
	            @if( request()->route()->getName() == 'admin.users.edit' )
	            	<a href="{{ URL::route('backend.users.login', $info->id) }}">	     
	                <span>Login as {{ $info->fullname }} </span>
					<i class="icon-user"></i>
					</a>
				@endif

				@if(  Auth::User()->group != 'admin' )
	            	<a href="{{ URL::route('backend.users.login', $suid) }}">	     
	                <span>Login as Admin </span>
					<i class="icon-user"></i>
					</a>
	            @endif
	        </li>
	        <li>
	            <a href="//staffingdental.com">
	                <span>View Frontend Site</span>
	                <i class="icon-home"></i>
	            </a>	        	
	        </li>
	        <li>
	            <a href="{{ URL::route('auth.logout') }}">
	                <span>Logout</span>
	                <i class="icon-logout"></i>
	            </a>
	        </li>
	    </ul>
	    <span aria-hidden="true" class="quick-nav-bg"></span>
	</nav>
	<div class="quick-nav-overlay"></div>
	@endif

	@if( Auth::User()->group == 'owner' )
		@if( App\UserMeta::get_meta(Auth::User()->id, 'package_amount') == 0 
		&& App\UserMeta::get_meta(Auth::User()->id, 'package_expiry') != 0 
		&& App\Setting::get_setting('enable_free_trial') == 1 )

		<style>
		.free-trial-bot {
			display: block;
			width: 100%;
			position: fixed;
			bottom: 0;
			padding: 10px;
			z-index: 1;
		}	
		</style>

		<div class="free-trial-bot text-center grade">
			Your <strong>Free Trial</strong> access is valid until 
			<strong>{{ date_formatted(App\UserMeta::get_meta(Auth::User()->id, 'package_expiry')) }}</strong>, 
			<a href="{{ URL::route('owner.billings.select_plan') }}" class="sbold">Buy Premium Access</a>
		</div>
		@endif
	@endif

	@if( Auth::User()->group != 'admin' )
	@include('partials.form-modal')
	@endif

@endif

