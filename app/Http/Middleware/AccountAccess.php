<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Setting, Redirect, URL;

class AccountAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if( Auth::check() ) {     
            
            $auth = Auth::user();

            $package_expiry = \App\UserMeta::get_meta($auth->id, 'package_expiry');
            $package_amount = \App\UserMeta::get_meta($auth->id, 'package_amount');
            $package_ended = \App\UserMeta::get_meta($auth->id, 'package_ended');
            $free_trial = \App\Setting::get_setting('enable_free_trial');
     
            if(@$package_ended) {
                if( strtotime(date('Y-m-d H:i:s')) > strtotime($package_ended) ) {
                    $date = time_ago($package_ended);

                    $msg = '<h4>Your subscription account has ended '.$date.'!</h4>';
                    $msg .= 'To keep using staffing dental. ';
                    $msg .= '<a href="'.URL::route('owner.billings.select_plan').'" class="sbold">Buy Premium Access</a>';
                    
                    return Redirect::route($auth->group.'.dashboard')->with('info', $msg);
                }                
            }


            if($package_expiry) {

                if( strtotime(date('Y-m-d H:i:s')) > strtotime($package_expiry) ) {

                    $date = time_ago($package_expiry);
                        
                    if( $package_amount==0 && $free_trial == 1 ) {
                        $msg = '<h4>Your free trial account has expired '.$date.'!</h4>';
                        $msg .= 'To keep using staffing dental. ';
                        $msg .= '<a href="'.URL::route('owner.billings.select_plan').'" class="sbold">Buy Premium Access</a>';

                        return Redirect::route($auth->group.'.dashboard')->with('info', $msg);
                    } 

                   if( $package_amount!=0 ) {
                        $msg = '<h4>Your subscription has expired '.$date.'!</h4>';
                        $msg .= 'To keep using staffing dental without interruption please pay <b>'.amount_formatted($package_amount).'</b> for your current bill to renew your subscription. ';
                        $msg .= '<a href="'.URL::route('owner.billings.index').'">View My Billing</a>';

                        return Redirect::route($auth->group.'.dashboard')->with('info', $msg);
                    }

                }

            }
        }

        return $next($request);
    }
}
