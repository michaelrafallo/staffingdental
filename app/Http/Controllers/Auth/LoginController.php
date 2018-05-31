<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Socialite, Auth, Input, Redirect, Session;

use App\User;
use App\UserMeta;
use App\Setting;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    protected $user;
    protected $usermeta;
    protected $setting;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user, UserMeta $usermeta, Setting $setting)
    {
        // $this->middleware('guest', ['except' => 'logout']);

        $this->user = $user;
        $this->usermeta = $usermeta;
        $this->setting = $setting;
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider='')
    {
/*        if( $provider == 'facebook' ) {
            return Socialite::driver('facebook')->scopes(['public_profile', 'email'])->asPopup()->redirect();
        }*/

        return Socialite::driver($provider)->redirect();

        // Socialite::driver('google')->scopes(['profile','email'])->redirect()
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider='')
    {

        $data = Socialite::driver($provider)->user(); 

        if($provider == 'facebook') {
            $name  = explode(" ", $data->user['name'], 2);
            $fname = @$name[0];
            $lname = @$name[1];
            $email = $data->email;
            $social_id  = $data->user['id'];
        }
        if($provider == 'google') {
            $name  = $data->user['name'];
            $fname = @$name['givenName'];
            $lname = @$name['familyName'];
            $email = $data->email;
            $social_id  = $data->user['id'];
        }

        if( Auth::check() ) {
            
            $auth = Auth::User();
            $this->usermeta->update_meta($auth->id, $provider.'_user_id', $social_id);

            return Redirect::route($auth->group.'.dashboard')->with('success',"You have successfully connected with your <b>{$provider}</b> account.");

        } else {

           $u = $this->usermeta->where('meta_key', $provider.'_user_id')
                               ->where('meta_value', $social_id)
                               ->first(); 
            
            if( $u ) {

                $user_id = $u->user_id;     

                $this->usermeta->update_meta($user_id, 'last_login', date('Y-m-d H:i:s'));

                Auth::loginUsingId($user_id, true);
                Session::put('user_id', $user_id);
                
                $auth = Auth::User();

                return Redirect::route($auth->group.'.dashboard');

            }  else {
                return Redirect::route('auth.login')
                               ->with('error',"Your <b>{$provider}</b> account is not connected to Staffing Dental.");
            }                            

        }


        // $user->token;
    }

    public function socialite_disconnect($provider='')
    {
        $auth = Auth::User();
        $this->usermeta->update_meta($auth->id, $provider.'_user_id', '');

        return Redirect::route($auth->group.'.dashboard')
                       ->with('success',"Your <b>{$provider}</b> account has been disconnected.");
    }


}
