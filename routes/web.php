<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$admin = 'admin';


Route::get('/clear-cache', function() {
	Artisan::call('cache:clear');
	Artisan::call('view:clear');
    return 'Cleared Cache!';
});

Route::get('login', function () {
    return Redirect::route('frontend.home');
});

// Socialite
Route::any('login/social/{provider?}', [
    'as'   => 'socialite.connect', 
    'uses' => 'Auth\LoginController@redirectToProvider'
]);
Route::any('login/social/{provider}/callback', [
    'as'   => 'socialite.callback', 
    'uses' => 'Auth\LoginController@handleProviderCallback'
]);
Route::any('disconnect/social/{provider?}', [
    'as'   => 'socialite.disconnect', 
    'uses' => 'Auth\LoginController@socialite_disconnect'
]);



// Frontend
Route::any('register/{form?}/{type?}', [
    'as'   => 'auth.register', 
    'uses' => 'AuthController@register'
]);

// Frontend - Find
Route::any('find/jobs', [
    'as'   => 'find.jobs.index', 
    'uses' => 'FindController@jobs'
]);
Route::any('find/jobs/{id?}', [
    'as'   => 'find.jobs.view', 
    'uses' => 'FindController@jobs_view'
]);

Route::any('find/employees', [
    'as'   => 'find.employees.index', 
    'uses' => 'FindController@employees'
]);
Route::any('find/employees/{id?}', [
    'as'   => 'find.employees.view', 
    'uses' => 'FindController@employees_view'
]);


/* Account Page */
Route::any('account/signin', [
    'as'   => 'account.signin', 
    'uses' => 'AccountController@signin'
]);
Route::any('account/signout', [
    'as'   => 'account.signout', 
    'uses' => 'AccountController@signout'
]);

Route::any('account/register/v1', [
    'as'   => 'account.register', 
    'uses' => 'AccountController@register'
]);

Route::any('email/jobs', [
    'as'   => 'email.jobs', 
    'uses' => 'GeneralController@email_jobs'
]);

Route::any('page/{page?}', [
    'as'   => 'page.term', 
    'uses' => 'GeneralController@page'
]);




Route::any('account/register/successful', [
    'as'   => 'account.successful', 
    'uses' => 'AccountController@successful'
]);

Route::any('forgot-password/{token?}', [
    'as'   => 'auth.forgot-password', 
    'uses' => 'AuthController@forgotPassword'
]);

Route::post('review/write', [
    'as'   => 'backend.review.write', 
    'uses' => 'GeneralController@writeReview'
]);
Route::get('review/delete/{id?}', [
    'as'   => 'backend.review.delete', 
    'uses' => 'GeneralController@deleteReview'
]);

Route::any('confirmation', [
    'as'   => 'frontend.confirmation', 
    'uses' => 'GeneralController@confirmation'
]);


/*Stripe */ 
Route::group(['prefix' => 'stripe'], function() {
    Route::any('charge', [
        'as'   => 'stripe.charge',
        'uses' => 'StripeController@charge'
    ]);
});

Route::group(['prefix' => 'stripe'], function() {
    Route::any('logs', [
        'as'   => 'stripe.logs',
        'uses' => 'StripeController@logs'
    ]);
});


Route::group(['prefix' => 'owner', 'middleware' => ['auth', 'owner']], function() {

	Route::any('intro', [
	    'as'   => 'owner.intro', 
	    'uses' => 'AuthController@intro'
	]);

	Route::group(['prefix' => 'stripe'], function() {
	    Route::any('subscription/cancel', [
	        'as'   => 'stripe.subscription.cancel',
	        'uses' => 'StripeController@cancel_subscription'
	    ]);
	});

	Route::any('dashboard', [
	    'as'   => 'owner.dashboard', 
	    'uses' => 'OwnerAccountController@profile'
	]);

	Route::group(['middleware' => ['account.access']], function() {

		Route::group(['prefix' => 'offices'], function() {

			Route::any('add', [
			    'as'   => 'owner.offices.add', 
			    'uses' => 'OwnerOfficeController@add'
			]);

			Route::any('edit/{id?}', [
			    'as'   => 'owner.offices.edit', 
			    'uses' => 'OwnerOfficeController@edit'
			]);

			Route::any('view/{id?}', [
			    'as'   => 'owner.offices.view', 
			    'uses' => 'OwnerOfficeController@view'
			]);
			
			Route::any('destroy/{id?}', [
			    'as'   => 'owner.offices.destroy', 
			    'uses' => 'OwnerOfficeController@destroy'
			]);
		});

		Route::group(['prefix' => 'professionals'], function() {

			Route::any('/', [
			    'as'   => 'owner.dentalpro.index', 
			    'uses' => 'OwnerDentalProController@index'
			]);
			Route::any('favorites/{id?}', [
			    'as'   => 'owner.dentalpro.favorites', 
			    'uses' => 'OwnerDentalProController@favorites'
			]);
			Route::any('profile/{id?}', [
			    'as'   => 'owner.dentalpro.profile', 
			    'uses' => 'OwnerDentalProController@profile'
			]);
			Route::any('book', [
			    'as'   => 'dentalpro.book', 
			    'uses' => 'OwnerDentalProController@book'
			]);
			Route::any('book/now', [
			    'as'   => 'dentalpro.book.now', 
			    'uses' => 'OwnerDentalProController@bookNow'
			]);
			Route::any('get-total', [
			    'as'   => 'dentalpro.get-total', 
			    'uses' => 'OwnerDentalProController@getTotal'
			]);
		});

		Route::group(['prefix' => 'appointments'], function() {

			Route::any('/', [
			    'as'   => 'owner.appointments.index', 
			    'uses' => 'OwnerAppointmentController@index'
			]);
			Route::any('view/{id?}', [
			    'as'   => 'owner.appointments.view', 
			    'uses' => 'OwnerAppointmentController@view'
			]);
			Route::any('status/{status?}/{id?}', [
			    'as'   => 'owner.appointments.status', 
			    'uses' => 'OwnerAppointmentController@status'
			]);
		});

		Route::group(['prefix' => 'job-postings'], function() {

			Route::any('/', [
			    'as'   => 'owner.job-postings.index', 
			    'uses' => 'OwnerJobPostingController@index'
			]);
			Route::any('view/{id?}', [
			    'as'   => 'owner.job-postings.view', 
			    'uses' => 'OwnerJobPostingController@view'
			]);
			Route::any('hire/{status?}/{id?}/{user_id?}', [
			    'as'   => 'owner.job-postings.hire', 
			    'uses' => 'OwnerJobPostingController@hire'
			]);

			Route::any('view-letter/{id?}', [
			    'as'   => 'owner.job-postings.view-letter', 
			    'uses' => 'OwnerJobPostingController@viewLetter'
			]);
			
			Route::any('add', [
			    'as'   => 'owner.job-postings.add', 
			    'uses' => 'OwnerJobPostingController@add'
			]);
			Route::any('edit/{id}', [
			    'as'   => 'owner.job-postings.edit', 
			    'uses' => 'OwnerJobPostingController@edit'
			]);
			Route::any('delete/{id}', [
			    'as'   => 'owner.job-postings.delete', 
			    'uses' => 'OwnerJobPostingController@delete'
			]);

		});


	});


	Route::group(['prefix' => 'reward-points'], function() {

		Route::any('/', [
		    'as'   => 'owner.reward-points.index', 
		    'uses' => 'OwnerRewardPointController@index'
		]);

	});
	
	Route::group(['prefix' => 'billings'], function() {

		Route::any('/', [
		    'as'   => 'owner.billings.index', 
		    'uses' => 'OwnerBillingController@index'
		]);
		Route::any('select-plan', [
		    'as'   => 'owner.billings.select_plan', 
		    'uses' => 'OwnerBillingController@select_plan'
		]);

	});

	Route::group(['prefix' => 'accounts'], function() {

		Route::any('profile', [
		    'as'   => 'owner.accounts.profile', 
		    'uses' => 'OwnerAccountController@profile'
		]);
		Route::any('settings', [
		    'as'   => 'owner.accounts.settings', 
		    'uses' => 'OwnerAccountController@settings'
		]);

	});


});


		

Route::group(['prefix' => '{usertype?}/messages', 'middleware' => ['auth', 'account.access']], function() {

	Route::any('/', [
	    'as'   => 'messages.index', 
	    'uses' => 'MessageController@index'
	]);
	Route::any('view/{id?}', [
	    'as'   => 'messages.view', 
	    'uses' => 'MessageController@view'
	]);
	Route::any('sent/{id?}', [
	    'as'   => 'messages.sent', 
	    'uses' => 'MessageController@sent'
	]);
});


Route::group(['prefix' => 'provider', 'middleware' => ['auth', 'provider']], function() {

	Route::any('dashboard', [
	    'as'   => 'provider.dashboard', 
	    'uses' => 'ProviderAccountController@profile'
	]);

	Route::any('owner/profile/{id?}', [
	    'as'   => 'provider.owner.profile', 
	    'uses' => 'ProviderAccountController@dashboard'
	]);

	Route::any('intro', [
	    'as'   => 'provider.intro', 
	    'uses' => 'AuthController@intro'
	]);

	Route::group(['prefix' => 'appointments'], function() {

		Route::any('/', [
		    'as'   => 'provider.appointments.index', 
		    'uses' => 'ProviderAppointmentController@index'
		]);
		Route::any('view/{id?}', [
		    'as'   => 'provider.appointments.view', 
		    'uses' => 'ProviderAppointmentController@view'
		]);
		Route::any('status/{status?}/{id?}', [
		    'as'   => 'provider.appointments.status', 
		    'uses' => 'ProviderAppointmentController@status'
		]);
	});

	Route::group(['prefix' => 'billings'], function() {

		Route::any('/', [
		    'as'   => 'provider.billings.index', 
		    'uses' => 'ProviderBillingController@index'
		]);
		Route::any('select-plan', [
		    'as'   => 'provider.billings.select_plan', 
		    'uses' => 'ProviderBillingController@select_plan'
		]);

	});

	Route::group(['prefix' => 'accounts'], function() {

		Route::any('profile', [
		    'as'   => 'provider.accounts.profile', 
		    'uses' => 'ProviderAccountController@profile'
		]);
		Route::any('settings', [
		    'as'   => 'provider.accounts.settings', 
		    'uses' => 'ProviderAccountController@settings'
		]);
		Route::any('schedule', [
		    'as'   => 'provider.accounts.schedule', 
		    'uses' => 'ProviderAccountController@schedule'
		]);
		Route::post('ajax/updatemeta', [
		    'as'   => 'provider.accounts.updatemeta', 
		    'uses' => 'ProviderAccountController@updateMeta'
		]);

	});

	Route::group(['prefix' => 'job-postings'], function() {

		Route::any('/', [
		    'as'   => 'provider.job-postings.index', 
		    'uses' => 'ProviderJobPostingController@index'
		]);
		Route::any('my-jobs', [
		    'as'   => 'provider.job-postings.my-jobs', 
		    'uses' => 'ProviderJobPostingController@myJobs'
		]);

		Route::any('view/{id?}', [
		    'as'   => 'provider.job-postings.view', 
		    'uses' => 'ProviderJobPostingController@view'
		]);
		Route::any('apply/{id?}', [
		    'as'   => 'provider.job-postings.apply', 
		    'uses' => 'ProviderJobPostingController@apply'
		]);
	});

	Route::any('employer/{id?}', [
	    'as'   => 'provider.job-postings.employer', 
	    'uses' => 'ProviderJobPostingController@employer'
	]);

	Route::group(['prefix' => 'points'], function() {

		Route::any('/', [
		    'as'   => 'provider.points.index', 
		    'uses' => 'ProviderPointController@index'
		]);

	});

});




Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function() {


	Route::any('dashboard', [
	    'as'   => 'admin.dashboard', 
	    'uses' => 'AdminUserController@index'
	]);

	Route::group(['prefix' => 'offices'], function() {

		Route::any('/', [
		    'as'   => 'admin.offices.index', 
		    'uses' => 'AdminOfficeController@index'
		]);

		Route::any('edit/{id?}', [
		    'as'   => 'admin.offices.edit', 
		    'uses' => 'AdminOfficeController@edit'
		]);

		Route::any('view/{id?}', [
		    'as'   => 'admin.offices.view', 
		    'uses' => 'AdminOfficeController@view'
		]);

	});


	Route::group(['prefix' => 'users'], function() {

		Route::any('/', [
		    'as'   => 'admin.users.index', 
		    'uses' => 'AdminUserController@index'
		]);
		Route::any('add', [
		    'as'   => 'admin.users.add', 
		    'uses' => 'AdminUserController@add'
		]);
		Route::any('edit/{id?}', [
		    'as'   => 'admin.users.edit', 
		    'uses' => 'AdminUserController@edit'
		]);
		Route::any('delete/{id?}', [
		    'as'   => 'admin.users.delete', 
		    'uses' => 'AdminUserController@delete'
		]);
		Route::any('restore/{id?}', [
		    'as'   => 'admin.users.restore', 
		    'uses' => 'AdminUserController@restore'
		]);
		Route::any('destroy/{id?}', [
		    'as'   => 'admin.users.destroy', 
		    'uses' => 'AdminUserController@destroy'
		]);
		Route::any('profile', [
		    'as'   => 'admin.users.profile', 
		    'uses' => 'AdminUserController@profile'
		]);

	});

	Route::group(['prefix' => 'plans'], function() {

		Route::any('/', [
		    'as'   => 'admin.plans.index', 
		    'uses' => 'AdminPlanController@index'
		]);
		Route::any('add', [
		    'as'   => 'admin.plans.add', 
		    'uses' => 'AdminPlanController@add'
		]);
		Route::any('edit/{id?}', [
		    'as'   => 'admin.plans.edit', 
		    'uses' => 'AdminPlanController@edit'
		]);
		Route::any('delete/{id?}', [
		    'as'   => 'admin.plans.delete', 
		    'uses' => 'AdminPlanController@delete'
		]);
		Route::any('restore/{id?}', [
		    'as'   => 'admin.plans.restore', 
		    'uses' => 'AdminPlanController@restore'
		]);
		Route::any('destroy/{id?}', [
		    'as'   => 'admin.plans.destroy', 
		    'uses' => 'AdminPlanController@destroy'
		]);
	});

	Route::group(['prefix' => 'job-postings'], function() {

		Route::any('/', [
		    'as'   => 'admin.job-postings.index', 
		    'uses' => 'AdminJobPostingController@index'
		]);
		Route::any('edit/{id?}', [
		    'as'   => 'admin.job-postings.edit', 
		    'uses' => 'AdminJobPostingController@edit'
		]);
		Route::any('delete/{id?}', [
		    'as'   => 'admin.job-postings.delete', 
		    'uses' => 'AdminJobPostingController@delete'
		]);
		Route::any('restore/{id?}', [
		    'as'   => 'admin.job-postings.restore', 
		    'uses' => 'AdminJobPostingController@restore'
		]);
		Route::any('destroy/{id?}', [
		    'as'   => 'admin.job-postings.destroy', 
		    'uses' => 'AdminJobPostingController@destroy'
		]);
	});

	Route::group(['prefix' => 'payments'], function() {

		Route::any('/', [
		    'as'   => 'admin.payments.index', 
		    'uses' => 'AdminPaymentController@index'
		]);

	});

	Route::group(['prefix' => 'settings'], function() {

		Route::any('/', [
		    'as'   => 'admin.settings.index', 
		    'uses' => 'AdminSettingController@index'
		]);

	});

});




Route::any('email/remider/profile-completeness', [
    'as'   => 'email.remider.profile-completeness', 
    'uses' => 'GeneralController@remider_profile_completeness'
]);


Route::any('email/test', [
    'as'   => 'email.test', 
    'uses' => 'GeneralController@test'
]);


//  Display login page
Route::any('login', [
    'as'   => 'auth.login', 
    'uses' => 'AuthController@login'
]);
Route::any('logout', [
    'as'   => 'auth.logout', 
    'uses' => 'AuthController@logout'
]);

Route::group(['prefix' => $admin, 'middleware' => ['auth']], function() {
	Route::any('users/login/{id?}', [
	    'as'   => 'backend.users.login', 
	    'uses' => 'AdminUserController@login'
	]);
});

Route::any('ajax/updatemeta', [
    'as'   => 'backend.users.updatemeta', 
    'uses' => 'AdminUserController@updateMeta'
]);
