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

class OwnerJobPostingController extends Controller
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

    public function index()
    {
        parse_str( query_vars(), $search );

        $data['user_id'] = $user_id = Auth::User()->id;

        $data['info'] = $auth = Auth::User();   
        foreach ($auth->usermetas as $user_meta) {
            $data['info'][$user_meta->meta_key] = $user_meta->meta_value;
        }

        $selects = array('job_status');
        $queries = array('status');

        $data['rows'] = $this->post
                             ->search($search, $selects, $queries)
                             ->where('post_type', 'job_posting')
                             ->where('post_author', $user_id)
                             ->orderBy(Input::get('sort_by', 'id'), Input::get('order_by', 'DESC'))
                             ->paginate(15);

        return view('owner.job-postings.index', $data);
    }

    //--------------------------------------------------------------------------

    public function add()
    {       

        $data['info'] = $auth = Auth::User();   

        foreach ($auth->usermetas as $user_meta) {
            $data['info'][$user_meta->meta_key] = $user_meta->meta_value;
        }

        $user_id = $auth->id;

        if( Input::get('_token') ) {

            $updateRules = [
                'job_title'             => 'required',
                'job_description'       => 'required|between:10, 6000',
                'provider_type'         => 'required',
                'job_type'              => 'required',
                'salary_type'           => 'required',
                'salary_rate'           => 'required|numeric',
                'years_of_experience'   => 'required',
                'practice_type'         => 'required',
                'number_of_position'    => 'required',
            ];    

            $validator = Validator::make(Input::all(), $updateRules);

            if( ! $validator->passes() ) {
                return Redirect::route('owner.job-postings.add', query_vars())
                               ->withErrors($validator)
                               ->withInput(); 
            }                

            $post = $this->post;
            
            $post->post_author  = $user_id;
            $post->post_content = Input::get('job_description');
            $post->post_title   = Input::get('job_title');
            $post->post_status  = $auth->user_status == 'verified' ? 'approved' : 'pending';
            $post->post_name    = text_to_slug(Input::get('job_title'));
            $post->post_type    = 'job_posting';

            if( $post->save() ) {
                $post_id = $post->id;

                $inputs = Input::except(['_token', 'job_title', 'job_description']);
                $inputs['job_status'] = 'open';

                if( $inputs['job_type'] == 'temporary') {
                    $inputs['schedule']      = Input::get('schedule');
                    $inputs['working_hours'] = '';
                } else {
                    $inputs['working_hours'] = Input::get('working_hours');
                    $inputs['schedule']      = '';
                }

                /* Generate geolocation */
                if( $inputs['office'] == 'main' ) {
                    $inputs['office_id']      = 'main';
                    $inputs['office_name']    = 'Main Office';
                    $inputs['office_address'] = $auth->street_address.' '.$auth->city.' '.$auth->state.' '.$auth->zip_code;

                    $inputs['lat'] = $auth->lat;
                    $inputs['lng'] = $auth->lng;   
                } else {
                    $inputs['office_id']      = Input::get('office');
                    $inputs['office_name']    = $this->post->find($inputs['office_id'])->post_name;
                    $inputs['office_address'] = $this->post->find($inputs['office_id'])->post_title;

                    $geolocation = json_decode($this->postmeta->get_meta($inputs['office_id'], 'geolocation'));

                    $inputs['lat'] = $geolocation->lat;
                    $inputs['lng'] = $geolocation->lng;                    
                }


                foreach ($inputs as $meta_key => $meta_value) {
                    $this->postmeta->update_meta($post_id, $meta_key, array_to_json($meta_value));
                }            

                if($auth->user_status != 'verified') {

	                /* START SEND EMAIL NOTIFICATION */
	                $msg['email_support'] = $this->setting->get_setting('admin_email');
	                $msg['site_title'] = $msg['site_name'] = $site_title = ucwords($this->setting->get_setting('site_title'));

	                $msg['email'] = $auth->email;
	                $msg['name'] = $auth->firstname;
	                $msg['email_title'] = 'You have posted a position on '.$site_title;


	                Mail::send('emails.owner.post-job', $msg, function($message) use ($msg)
	                {
	                    $message->from($msg['email_support'], $msg['site_title']);
	                    $message->to($msg['email'])->subject($msg['email_title']);
	                });

	                /* Send to admin */
	                $msg['carbon_copy'] = $this->setting->get_setting('carbon_copy');
	                $msg['email_title']   = $subject = 'New job posted on '.$site_title;
	                $msg['email_subject'] = $subject;
	                $msg['site_title'] = ucwords($this->setting->get_setting('site_title'));

	                $msg['msg'] = '<p>An employer has just posted a new job on Staffing Dental.</p>';
	                $msg['msg'] .= '<p>Here’s the relevant information:</p>';
	                $msg['msg'] .= 'Job title: '.$post->post_title.'<br>';
	                $msg['msg'] .= 'Date Posted: '.date('M d, Y');

	                $msg['url'] = URL::route('auth.login');

	                Mail::send('emails.notify', $msg, function($message) use ($msg)
	                {
	                    $message->from($msg['email_support'], $msg['site_title']);
	                    $message->to(explode(',', str_replace(' ', '', $msg['carbon_copy'])), $msg['site_title'])->subject($msg['email_subject']);
	                });

	                /* END SEND EMAIL NOTIFICATION */

                }

                return Redirect::route('owner.job-postings.edit', $post_id)
                               ->with('success','New job posting has been created!');

            }
        } 

        $offices = $this->post->where('post_author', $user_id)
                              ->where('post_type', 'office')
                              ->where('post_status', 'actived')
                              ->get();
        
        $data['offices']['main'] = 'Main Office @ ' . $auth->street_address.' '.$auth->city.' '.$auth->state.' '.$auth->zip_code;

        foreach ($offices as $office) {
            $data['offices'][$office->id] = $office->post_name.' @ '.$office->post_title;
        }                                      

        return view('owner.job-postings.add', $data);
    }

    //--------------------------------------------------------------------------

    public function edit($id='')
    {

        $data['user'] = $auth = Auth::User();
                         
        foreach ($auth->usermetas as $user_meta) {
            $data['user'][$user_meta->meta_key] = $user_meta->meta_value;
        }

        $user_id = $auth->id;

        $data['info'] = $info = $this->post->where('post_author', $user_id)->findOrFail($id);

        foreach ($info->postmetas as $post_meta) {
            $data['info'][$post_meta->meta_key] = $post_meta->meta_value;
        }

        if( Input::get('_token') ) {

            $updateRules = [
                'job_title'             => 'required',
                'job_description'       => 'required|between:10, 6000',
                'provider_type'         => 'required',
                'job_type'              => 'required',
                'salary_type'           => 'required',
                'salary_rate'           => 'required|numeric',
                'years_of_experience'   => 'required',
                'practice_type'         => 'required',
                'number_of_position'    => 'required',
            ];    

            $validator = Validator::make(Input::all(), $updateRules);

            if( ! $validator->passes() ) {
                return Redirect::route('owner.job-postings.edit', $id)
                               ->withErrors($validator)
                               ->withInput(); 
            }                
    
            $post = $this->post->find($id);
        
            $post->post_content = Input::get('job_description');
            $post->post_title   = Input::get('job_title');
            $post->post_name    = text_to_slug(Input::get('job_title'));
            $post->updated_at   = date('Y-m-d H:i:s');
            
            if( $post->save() ) {
                $inputs = Input::all();

                $hired = $this->post->where(['post_type' => 'application', 'post_status' => 'hired', 'parent' => $id])->count();
                if( $hired >= $this->postmeta->get_meta($id, 'number_of_position') ) {
                    $this->post->where('id', $id)->update(['post_status' => 'completed']);
                } else {
                    $this->post->where('id', $id)->update(['post_status' => 'approved']);                    
                }
                
                $inputs = Input::except(['_token', 'job_title', 'job_description']);

                if( $inputs['job_type'] == 'temporary') {
                    $inputs['schedule']      = Input::get('schedule');
                    $inputs['working_hours'] = '';
                } else {
                    $inputs['schedule']      = '';
                    $inputs['working_hours'] = Input::get('working_hours');
                }

                $inputs['hiring_status'] = Input::get('hiring_status', '');

                /* Generate geolocation */
                if( $inputs['office'] == 'main' ) {
                    $inputs['office_id']      = 'main';
                    $inputs['office_name']    = 'Main Office';
                    $inputs['office_address'] = $auth->street_address.' '.$auth->city.' '.$auth->state.' '.$auth->zip_code;

                    $inputs['lat'] = $auth->lat;
                    $inputs['lng'] = $auth->lng;   
                } else {
                    $inputs['office_id']      = Input::get('office');
                    $inputs['office_name']    = $this->post->find($inputs['office_id'])->post_name;
                    $inputs['office_address'] = $this->post->find($inputs['office_id'])->post_title;

                    $geolocation = json_decode($this->postmeta->get_meta($inputs['office_id'], 'geolocation'));

                    $inputs['lat'] = $geolocation->lat;
                    $inputs['lng'] = $geolocation->lng;                    
                }


                foreach ($inputs as $meta_key => $meta_value) {
                    $this->postmeta->update_meta($id, $meta_key, array_to_json($meta_value));
                }            

                return Redirect::route('owner.job-postings.edit', $id)
                               ->with('success','New job posting has been created!');

            }
        } 

        $offices = $this->post->where('post_author', $user_id)
                              ->where('post_type', 'office')
                              ->where('post_status', 'actived')
                              ->get();

        $data['offices']['main'] = 'Main Office @ ' . $auth->street_address.' '.$auth->city.' '.$auth->state.' '.$auth->zip_code;
    
        foreach ($offices as $office) {
            $data['offices'][$office->id] = $office->post_name.' @ '.$office->post_title;
        }                                      

        return view('owner.job-postings.edit', $data);
    }

    //--------------------------------------------------------------------------

    public function view($id)
    {

        $auth = Auth::User();
        
        $user_id = $auth->id;
        
        $data['info'] = $info = $this->post->where('post_author', $user_id)->findOrFail($id);
        $data['post'] = $this->post;

        foreach ($info->postmetas as $post_meta) {
            $data['info'][$post_meta->meta_key] = $post_meta->meta_value;
        }

        $data['rows'] = $this->post->where('parent', $id)
                                   ->where('post_type', 'application')
                                   ->orderBy('id', 'DESC')
                                   ->get();

        return view('owner.job-postings.view', $data);
    }

    //--------------------------------------------------------------------------

    public function hire($status='', $post_id='', $author_id)
    {
        $action = Input::get('action'); 

        $info = $this->post->find($post_id);

        $info->updated_at = date('Y-m-d H:i:s');
        $info->save();

        $job = $this->post->find($info->parent);

        $user_id = Auth::User()->id;

        $post = $this->post->where('post_author', $author_id)
                           ->where('id', $post_id)
                           ->where('post_type', 'application')
                           ->first();

        $message = Input::get('message');

        if( ! $message ) {
            return Redirect::back()->with('error', "Please enter your message.");            
        }        

        $post->post_status = $status;

        if( $post->save() ) {

            if( $status == 'hired') {
                $this->postmeta->update_meta($post_id, 'date_hired', date('Y-m-d H:i:s'));
            }

            $hired = $this->post->where(['post_type' => 'application', 'post_status' => 'hired', 'parent' => $job->id])->count();
            if( $hired >= $this->postmeta->get_meta($job->id, 'number_of_position') ) {
                $this->post->where('id', $job->id)->update(['post_status' => 'completed']);
            } else {
                $this->post->where('id', $job->id)->update(['post_status' => 'approved']);                
            }

            $post_content = Input::get('message');
            $post_content .= '<br><br><a href="'.URL::route('provider.job-postings.view', $job->id).'">View job posting here</a> <small>Job ID# '.$post_id.'</small>';
            $post_data = array(
                'post_name' => 'job_posting_'.$job->id,
                'post_content' => $post_content,
                'post_id'      => $post_id,
                'post_content' => $post_content,
                'user_id'      => $user_id,
                'author_id'    => $author_id
            );
            $this->post->send_message_notification($post_data);          
        }

        return Redirect::back()->with('success',"Message sent!");
    }

    //--------------------------------------------------------------------------

    public function viewLetter($id='')
    {
        $data['info'] = $this->post->find($id);

        return view('owner.job-postings.view-letter', $data);
    }

    //--------------------------------------------------------------------------

    public function delete($id='')
    {
        $p = $this->post->findOrFail($id);

        $this->postmeta->where('post_id', $id)->delete();

        $p->forceDelete();

        return Redirect::route('owner.job-postings.index', query_vars())
                       ->with('success','Selected job has been move to trashed!');
    }

    //--------------------------------------------------------------------------

}
