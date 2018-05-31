<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Redirect, Input, Auth, Hash, Session, URL, Mail;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\UserMeta;
use App\Setting;



class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    protected $user;
    protected $usermeta;
    protected $setting;


    public function __construct(User $user, UserMeta $usermeta, Setting $setting)
    {
        $this->user = $user;
        $this->usermeta = $usermeta;
        $this->setting = $setting;
    }

    //--------------------------------------------------------------------------

    public function login()
    {

        if( Auth::check() ) {
            $auth = Auth::user();

            if( Input::get('redirect_to') ) {
                return Redirect::to( Input::get('redirect_to') );
            }
                    
            return Redirect::route($auth->group.'.dashboard');
        }

        if(Input::get('op')) {

            $insertRules = [
                'email' => 'required|email',
                'password' => 'required',
            ];

            $validator = Validator::make(Input::all(), $insertRules);

            if($validator->passes()) {

                $field = filter_var(Input::get('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
                $remember = (Input::has('remember')) ? true : false;
                
                $credentials = [
                    'email'      => Input::get('email'),
                    'password'  => Input::get('password'),                
                ];

                if(Auth::attempt($credentials, $remember)) {               
                    $auth = Auth::user();

                    $status = $auth->status;

                    if( in_array($status, ['inactived', 'suspended']) ) {               
    
                        $fullname =  $auth->fullname;
                        $email = $auth->email;

                        Auth::logout();

                        $admin_email = $this->setting->get_setting('admin_email');
                        $msg = "Your account is currently <b class='text-danger'>".strtolower(user_status($status)).'</b>. <a href="../../contact"><b>contact us</b></a> at <a href="mailto:'.$admin_email.'"><b>'.$admin_email.'</b></a> to reactivate your account.';

                        return Redirect::route('auth.login')
                                       ->with('info', $msg)
                                       ->withInput();
                    }


                    Session::put('user_id', $auth->id);
                    $this->usermeta->update_meta($auth->id, 'last_login', date('Y-m-d H:i:s'));
                    
                    if( Input::get('redirect_to') ) {
                        return Redirect::to( Input::get('redirect_to') );
                    }
                    

                    return redirect()->intended(route($auth->group.'.dashboard'));

                    return Redirect::route($auth->group.'.dashboard');

                } 

                return Redirect::route('auth.login')
                               ->with('error','Invalid email or password')
                               ->withInput();
            }

            return Redirect::route('auth.login', query_vars())
                           ->withErrors($validator)
                           ->withInput(); 
        }

        return view('auth.login');
    }

    //--------------------------------------------------------------------------

    public function register($form=1, $type='')
    {

        if( Auth::check() ) {
            $auth = Auth::user();                    
            return Redirect::route($auth->group.'.dashboard');
        }

        $user_data = (Session::get('user_data_2')) ? (array)json_decode(Session::get('user_data_2')) : array();

        if($form == 1) {               
            $user_data = (Session::get('user_data_1')) ? (array)json_decode(Session::get('user_data_1')) : array();
        }
        
        // Session::flush();
        if( $form==1 && $type=='owner' ) {
            Session::put('sos', true);            
        }

/*        $data['sos'] = Session::get('sos');
        
        Session::forget('sos');*/

        if( $form!=1 && $type != '') { // Do not allow to change provider in url
            if(@$user_data['user_type_provider'] != $type) {
               return Redirect::route('auth.register');    
            }
        }

        if( Input::get('_token') ) {

            if(@$user_data['user_type_provider']) {
                if( Input::get('user_type_provider') != $user_data['user_type_provider'] ) {
                    Session::forget('user_data_2');
                    $user_data = array();
                }
            }

            $user_data = Input::all() + $user_data;

            if( ! Input::get('practice_type') ) {
                unset($user_data['practice_type']);
            }

            if($form == 1) { 
                Session::put('user_data_1', json_encode($user_data));
            } else {
                Session::put('user_data_2', json_encode($user_data));             
            }

            $messages = ['password.regex' => "The password must contain 1 upper case letter, 1 number, 6 characters"];


            $validator = Validator::make(Input::all(), $this->user->signup_rules($form), $messages);

            if( ! $validator->passes() ) {
                return Redirect::route('auth.register', [$form, $type])
                               ->withErrors($validator)
                               ->withInput(); 
            }

            if( Input::get('form') == 'video' ) {

                $u = $this->user;

                $user = json_decode(Session::get('user_data_1'));


                /* START GEOLOCATION */
/*                $address = array(
                  @$user_data['street_address'],
                  @$user_data['city'],
                  @$user_data['state'],
                  @$user_data['zip_code'],
                );

                if( count(array_filter($address)) >= 2 ) {
                    $geolocation = get_geolocation(implode(' ', $address));

                    if(@$geolocation['lat']) {
                        $u->lat = $geolocation['lat'];
                        $u->lng = $geolocation['lng'];                        
                    }
                }*/
                $u->lat = 0;
                $u->lng = 0;   

                /* END GEOLOCATION */

                $u->firstname   = $user->firstname;
                $u->lastname    = $user->lastname;
                $u->password    = Hash::make($user_data['password']);
                $u->email       = $user->email;
                $u->status      = $this->setting->get_setting('auto_approve_account') == 1 ? 'approved' : 'pending';
                $u->group       = $user_data['user_type_provider'];
                $u->fullname    = $user->firstname.' '.$user->lastname;

                $invitation_code    = $user->invitation_code;
                $user_type_provider = $user_data['user_type_provider'];      

                $unsets = ['_token', 'form', 'user_type_provider', 'password', 'password_confirmation', 'agree', 'email'];

                foreach($unsets as $unset) {
                    unset($user_data[$unset]);
                }
  
                $u->save();
                
                    $path = $image_path = '';
                    $user_id = $u->id;


                    if( Input::hasFile('proof_of_insurance') ) {
                        $filename   = str_random(16);
                        $fileUpload = Input::file('proof_of_insurance');
                        $imageFile  = $fileUpload->getRealPath();
                        $ext        = $fileUpload->getClientOriginalExtension();
                        $path       = 'uploads/images/users/'.$user_id;
                       
                        if( ! file_exists($path) ) mkdir($path);
                        $image_path = $path.'/'.$filename.'.png';  

                        $img = \Image::make($imageFile)->save($image_path);

                        $user_data['proof_of_insurance'] = $image_path;
                    }

                    if( Input::hasFile('resume') ) {
                        $file       = Input::file('resume');
                        $ext        = $file->getClientOriginalExtension();
                        $filename   = str_random(16).'.'.$ext;
                        $path       = 'uploads/images/users/'.$user_id;
                        $file->move($path, $filename);

                        $user_data['resume'] = $path.'/'.$filename;  
                    }                  

                    /* START SET reward points */
                    if( $invitation_code && $user_type_provider ) {
                        $umeta = $this->usermeta->where('meta_key', 'my_invitation_code')->where('meta_value', $invitation_code)->first();
                        $reward_type = ($user_type_provider == 'owner') ? 'dental_office_reward' : 'dental_professional_reward';
                        $reward_point = $this->setting->get_setting($reward_type);            
                        $referrer_uid = @$umeta->user_id;

                        $referrer_reward = $this->usermeta->get_meta($referrer_uid, 'reward_points');
                        $this->usermeta->update_meta($referrer_uid, 'reward_points', $referrer_reward + $reward_point);

                        $user_data['reward_points']    = 0;
                        $user_data['referrer_user_id'] = $referrer_uid;
                        $user_data['referrer_code']    = $invitation_code;
                        $user_data['referrer_points']  = $reward_point;
                    }
                    /* END SET reward points */

                    /* Add free access period  */
                    if( $user_type_provider == 'owner' ) {
                        $free_trial_period = $this->setting->get_setting('free_trial_period');
                        $ftp = $free_trial_period ? $free_trial_period : 30;
                        
                        if( $this->setting->get_setting('enable_free_trial') == 1 ) {
                            $user_data['package_expiry'] =  date('Y-m-d H:i:s', strtotime('+'.$ftp.' days'));
                            $user_data['package_amount'] = 0;  
                        } else {
                            $user_data['package_expiry'] = '';
                            $user_data['package_amount'] = '';  
                        }

                    }

                    $user_data['last_login']         = date('Y-m-d H:i:s');
                    $user_data['my_invitation_code'] = strtoupper(str_random(8));
                    $user_data['email_notification'] = 'on';
                    $user_data['availability']       = 'online';
                    $user_data['user_status']        = $this->setting->get_setting('auto_approve_account') == 1 ? 'verified' : 'unverified';

                    foreach ($user_data as $meta_key => $meta_value) {
                        $this->usermeta->update_meta($user_id, $meta_key, array_to_json($meta_value));
                    }


                    /* START CONFIRMATION */

                    $msg['token'] = $token =  str_random(64);
 
                    $user = $this->user->find($user_id);
                    $user->verify_token = $token;

                    if( $user->save() ) {

                        $msg['email'] = $user->email;
                        $msg['name'] = $user->firstname;

                        $msg['site_name'] = $site_title = ucwords($this->setting->get_setting('site_title'));
                        $msg['copy_right'] = ucwords($this->setting->get_setting('copy_right'));
                        $msg['logo'] = ucwords($this->setting->get_setting('logo'));

                        $msg['email_support'] = $this->setting->get_setting('admin_email');

                        $msg['email_title']   = $subject = 'Please verify your '.$this->setting->get_setting('site_title').' account';
                        $msg['email_subject'] = $subject;

                        Mail::send('emails.confirm', $msg, function($message) use ($msg)
                        {
                            $message->from($msg['email_support'], $msg['copy_right']);
                            $message->to($msg['email'], $msg['copy_right'])->subject($msg['email_subject']);
                        });

                        /* Send to admin */
                        $msg['carbon_copy'] = $this->setting->get_setting('carbon_copy');
                        $msg['email_subject']  = 'New User Registration from '.$site_title;

                        $msg['msg'] = 'A new dental professional has registered with '.$site_title.'.<br><br>';
                        $msg['msg'] = 'Here’s the relevant information:<br><br>';
                        $msg['msg'] .= 'Name: '.ucwords($user->firstname.' '.$user->lastname).'<br>';
                        $msg['msg'] .= 'Email: '.$user->email.'<br>';
                        $msg['msg'] .= 'Type: '.ucwords($user_type_provider);

                        $msg['url'] = URL::route('auth.login');

                        Mail::send('emails.notify', $msg, function($message) use ($msg)
                        {
                            $message->from($msg['email_support'], $msg['site_name']);
                            $message->to(explode(',', str_replace(' ', '', $msg['carbon_copy'])), $msg['site_name'])->subject($msg['email_subject']);
                        });



                        
                    }
                    /* END CONFIRMATION */

                    Session::forget('user_data_1');
                    Session::forget('user_data_2');     

                    Auth::loginUsingId($user_id);
                    Session::put('user_id', $user_id);
                    
                    return Redirect::route($u->group.'.intro');



            }

            return Redirect::route('auth.register', [Input::get('form'), Input::get('user_type_provider')]);

        }

        $data['user_data'] = $user_data;

        $data['form'] = $form.($type ? '-'.$type : '');

        return view('auth.register.index', $data);
    }


    //--------------------------------------------------------------------------

    public function forgotPassword($token ='')
    {

        $data['token'] = $token;
        
        if($token) {

            $u = $this->user->where('forgot_password_token', $token)->first();

            if(!$u) return Redirect::route('auth.login');

            if(Input::get('op') ) {

                $validator = Validator::make(Input::all(), User::$newPassword);
    
                if($validator->passes()) {

                    $u->password = Hash::make(Input::get('new_password'));
                    $u->forgot_password_token = NULL;

                    if( $u->save() ) {              
                        $user_id = $u->id;
                        
                        Auth::loginUsingId($u->id);
                        Session::put('user_id', $user_id);

                        return Redirect::route($u->group.'.dashboard')
                                       ->with('success','You have successfully changed your password.');

                    } 
                } else {
                        
                    return Redirect::route('auth.forgot-password')
                                   ->withErrors($validator)
                                   ->withInput();
                }
            }

        } else {

            if(Input::get('op') ) {

                $validator = Validator::make(Input::all(), User::$forgotPassword);
    
                if($validator->passes()) {

                    $token = str_random(64);
                    $email = Input::get('email');

                    $u = $this->user->where('email', $email)->first();
                    $u->forgot_password_token = $token;

                    if( $u->save() ) {              
                        $data['name']      = ucwords( $u->firstname );
                        $data['email']     = $u->email;
                        $data['token_url'] = URL::route('auth.forgot-password', $u->forgot_password_token);
                        $data['base_url']  = URL::route('auth.login');
                        $data['site_name'] = $site_name = ucwords($this->setting->get_setting('site_title'));

                        $data['email_support'] = $this->setting->get_setting('admin_email');
                        $data['email_title']   = $site_name.' Support';
                        $data['email_subject'] = $site_name.' Forgotten Password!';

                        Mail::send('emails.forgot-password', $data, function($message) use ($data)
                        {
                            $message->from($data['email_support'], $data['site_name']);
                            $message->to($data['email'], $data['site_name'])->subject($data['email_subject']);
                        });

                        return Redirect::route('auth.forgot-password')
                                       ->with('success','Forgot password link has been sent to your email address. Please check your inbox or spam folder.');

                    } 
                } else {
                        
                    return Redirect::route('auth.forgot-password')
                                   ->withErrors($validator)
                                   ->withInput();
                }
            }



        }

        return view('auth.forgot-password', $data);
    }

    //--------------------------------------------------------------------------

    public function logout()
    {
        Auth::logout();
        Session::flash('success','You are now logged out!');
        return Redirect::route('auth.login');
    }


    //--------------------------------------------------------------------------
   
    public function intro()
    {
        $data['info'] = Auth::User();

        return view('auth.register.intro', $data);
    }


    //--------------------------------------------------------------------------



}
