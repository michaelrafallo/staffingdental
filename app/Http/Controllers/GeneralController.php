<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Redirect, Input, Auth, Hash, Session, URL, Mail, Config;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\UserMeta;
use App\Post;
use App\PostMeta;
use App\Setting;

class GeneralController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    protected $user;
    protected $usermeta;
    protected $post;
    protected $postmeta;
    protected $setting;


    public function __construct(User $user, UserMeta $usermeta, Post $post, PostMeta $postmeta, Setting $setting)
    {
        $this->user = $user;
        $this->usermeta = $usermeta;
        $this->post = $post;
        $this->postmeta = $postmeta;
        $this->setting = $setting;
    }

    //--------------------------------------------------------------------------

    public function writeReview()
    {   
        $user_id   = Input::get('uid');
        $review    = Input::get('review');
        $auth      = Auth::User();
        $author_id = $auth->id;

        if( Input::get('_token') ) {

        $r = $this->post->where('post_title', $user_id)->where('post_author', $author_id)->where('post_type', 'review')->first();

        $post = ($r) ? $r : $this->post;

        $post->post_author  = $author_id;
        $post->post_content = ($review) ? $review : '';
        $post->post_title   = $user_id;
        $post->post_status  = 'actived';
        $post->post_type    = 'review';
        $post->created_at   = date('Y-m-d H:i:s');

        if( $post->save() ) {
            $inputs = Input::all();
            unset($inputs['_token']);
            unset($inputs['review']);
            unset($inputs['uid']);

            if( $auth->group == 'owner' ) {
                $overall = number_format(($inputs['medical_competence'] + $inputs['punctuality'] + $inputs['beside_manner']) / 3, 1);
            } else {
                $overall = $inputs['rating'];
            }

            $inputs['overall'] = $overall;
            
            foreach ($inputs as $meta_key => $meta_value) {
                $this->postmeta->update_meta($post->id, $meta_key, $meta_value);
            }   
        }

        return Redirect::back()->with('success','Your review has been posted!');

        }

    }

    //--------------------------------------------------------------------------
 
    public function deleteReview($id='')
    {   
        $this->post->find($id)->delete();
        $this->postmeta->where('post_id', $id)->delete();

        return Redirect::back()->with('success','Your review has been deleted!');
    }

    //--------------------------------------------------------------------------
  
    public function confirmation()
    {   
        $token = Input::get('token');

        if( $token ) {
            
            $user = $this->user->where('verify_token', $token)->firstOrFail();
            $user->verify_token = NULL;
            $user->save();

            $user_id = $user->id;
            
            Auth::loginUsingId($user_id);
            Session::put('user_id', $user_id);

            $this->usermeta->update_meta($user_id, 'email_verified', date('Y-m-d H:i:s'));

            return Redirect::route($user->group.'.dashboard')
                           ->with('success','Email address has been confirmed!');

        } else {
            if( Auth::check() ) {
                $msg['token'] = $token =  str_random(64);

                $auth = Auth::User();

                $user = $this->user->find($auth->id);
                $user->verify_token = $token;

                if( $user->save() ) {

                    $user = $this->user->find($auth->id);

                    $msg['email'] = $auth->email;
                    $msg['name'] = $user->firstname;
                    
                    $msg['site_name'] = $site_name = ucwords($this->setting->get_setting('site_title'));

                    $msg['copy_right'] = ucwords($this->setting->get_setting('copy_right'));
                    $msg['logo'] = ucwords($this->setting->get_setting('logo'));

                    $msg['email_support'] = $this->setting->get_setting('admin_email');

                    $msg['email_title']   = 'Please verify your'.$site_name.' Account';

                    Mail::send('emails.confirm', $msg, function($message) use ($msg)
                    {
                        $message->from($msg['email_support'], $msg['site_name']);
                        $message->to($msg['email'])->subject($msg['email_title']);
                    });
                    
                    $this->usermeta->update_meta($auth->id, 'email_confirmed', date('Y-m-d H:i:s'));
                }

                return Redirect::back()->with('success', 'Confirmation sent!, Please check your email.');

            }

        }

//     
    }

    //--------------------------------------------------------------------------

    public function email_jobs()
    {   

        $users = $this->user->where('group', 'provider')->get();

        $queries = array('provider_type');
        $selects = array('provider_type');

        foreach($users as $user) {

            $usermeta = get_meta($user->userMetas()->get());

            $search['provider_type'] = @$usermeta->provider_type;

            /* START Search nearest location */
            $search['lat'] = $user->lat;
            $search['lng'] = $user->lng;
            $search['circle_radius'] = 3959;
            $search['distance'] = @$usermeta->travel_distance ? $usermeta->travel_distance : 100;
            /* END Search nearest location */


            $msg['rows'] = $rows = $this->post
                                        ->search($search, $selects, $queries)
                                        ->where('post_type', 'job_posting')
                                        ->where('post_status', 'approved')
                                        ->where('posts.updated_at', '>=', date('Y-m-d H:i:s', strtotime('-5 mins')) )
                                        ->orderBy('id', 'DESC')
                                        ->limit(10)
                                        ->get();


            if( count($rows) ) {

                $msg['email'] = $user->email;
                $msg['name'] = $user->firstname;

                $msg['copy_right'] = ucwords($this->setting->get_setting('copy_right'));
                $msg['site_title'] = ucwords($this->setting->get_setting('site_title'));

                $msg['logo'] = ucwords($this->setting->get_setting('logo'));

                $msg['email_support'] = $this->setting->get_setting('admin_email');

                $msg['email_title']   = 'New Job Opening That Matches Your Criteria';
                $msg['email_subject'] = 'New Job Opening That Matches Your Criteria';


                Mail::send('emails.jobs', $msg, function($message) use ($msg)
                {
                    $message->from($msg['email_support'], $msg['site_title']);
                    $message->to($msg['email'], $msg['site_title'])->subject($msg['email_subject']);
                });
            }                                        
        }


    //  return view('emails.jobs', $msg);

    }

    //--------------------------------------------------------------------------
    
    public function page($page ='')
    {   

      $data['page'] = $page;
      
      return view('partials.terms.index', $data);

    }

    //--------------------------------------------------------------------------

    public function remider_profile_completeness()
    {   

        $msg['email_support'] = $this->setting->get_setting('admin_email');
        $msg['site_title'] = $site_title = ucwords($this->setting->get_setting('site_title'));
        $msg['email_title']   = $subject = 'Reminder: Your profile is incomplete';
        $msg['email_subject'] = $subject;

        $msg['url'] = URL::route('auth.login');

        $users = $this->user
                    ->select('users.*', 'm1.meta_value as profile_completeness')
                    ->from('users')
                    ->join('usermeta AS m1', function ($join) {
                        $join->on('users.id', '=', 'm1.user_id')
                             ->where('m1.meta_key', '=', 'profile_completeness')
                             ->where('m1.meta_value', '!=', 100);
                    })
                    ->whereIn('group', ['owner', 'provider'])
                    ->whereIn('status', ['approved', 'actived'])
                    ->get();

        foreach ($users as $user) {
            $info = get_meta($user->userMetas()->get());

            Mail::send('emails.complete-profile', compact('info', 'user', 'site_title'), 
                function($message) use ($msg) {
                $message->from($msg['email_support'], $msg['site_title']);
                $message->to($msg['email_support'], $msg['site_title'])->subject($msg['email_subject']);
            });

        }

    }

    //--------------------------------------------------------------------------

    public function test()
    {   

        /* Send to admin */
        $msg['email_support'] = $this->setting->get_setting('admin_email');
        $msg['site_name'] = $site_title = ucwords($this->setting->get_setting('site_title'));
        $msg['carbon_copy'] = $this->setting->get_setting('carbon_copy');
        $msg['email_title']   = $subject = 'New job posted on '.$site_title;
        $msg['email_subject'] = $subject;

        $msg['msg'] = 'Email Test';


        $msg['url'] = URL::route('auth.login');

        Mail::send('emails.notify', $msg, function($message) use ($msg)
        {
            $message->from($msg['email_support'], $msg['site_name']);
            $message->to(explode(',', str_replace(' ', '', $msg['carbon_copy'])), $msg['site_name'])->subject($msg['email_subject']);
        });

      return view('emails.notify', $msg);

    }

    //--------------------------------------------------------------------------


}
