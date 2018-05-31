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

class AdminJobPostingController extends Controller
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

        if( Input::get('action') == 'trash' ) {
            foreach( Input::get('ids') as $id ) {
                Post::find($id)->delete();
            }
            return Redirect::route('admin.job-postings.index', query_vars())
                           ->with('success','Selected job has been move to trashed!');
        }

        if( Input::get('action') == 'restore') {
            foreach( Input::get('ids') as $id ) {
                $post = Post::withTrashed()->findOrFail($id);
                $post->restore();
            }
            return Redirect::route('admin.job-postings.index', query_vars())
                           ->with('success','Selected job has been restored!');
        }

        if( Input::get('action') == 'destroy') {
            foreach( Input::get('ids') as $id ) {
                $this->post->destroy($id);
            }
            return Redirect::route('admin.job-postings.index', query_vars())
                           ->with('success','Selected job has been deleted permanently!');
        }



        $queries = array();
        $selects = array('job_status', 'job_type');
        foreach(Input::all() as $input_k => $input_v) {            
            if($input_v) {
                if( in_array($input_k, $selects) ) {
                   $queries[] = $input_k;
                }                
            }
        }

        $data['rows'] = $this->post
                ->search($search, $queries)
                ->where('posts.post_type', 'job_posting')
                ->orderBy('id', Input::get('sort', 'DESC'))
                ->paginate(Input::get('rows', 15));

        $data['count'] = $this->post
                ->search($search, $queries)
                ->where('posts.post_type', 'job_posting')
                ->count();

        $data['all'] = $this->post->where('posts.post_type', 'job_posting')->where('id', '!=', 1)->count();
        $data['trashed'] = $this->post->withTrashed()
                                      ->where('posts.post_type', 'job_posting')
                                      ->where('deleted_at', '<>', '0000-00-00')
                                      ->count();

        return view('admin.job-postings.index', $data);
    }

    //--------------------------------------------------------------------------

    public function edit($id='')
    {


        $auth = Auth::User();
        
        $user_id = $auth->id;

        $data['info'] = $info = $this->post->find($id);

        foreach ($info->postmetas as $post_meta) {
            $data['info'][$post_meta->meta_key] = $post_meta->meta_value;
        }

        if( Input::get('_token') ) {

            $updateRules = [
                'job_title'             => 'required',
                'job_description'       => 'required|between:10, 6000',
                'provider_type'         => 'required',
                'salary_type'           => 'required',
                'salary_rate'           => 'required|numeric',
                'years_of_experience'   => 'required',
                'practice_type'         => 'required',
            ];    

            $validator = Validator::make(Input::all(), $updateRules);

            if( ! $validator->passes() ) {
                return Redirect::route('admin.job-postings.edit', $id)
                               ->withErrors($validator)
                               ->withInput(); 
            }                
    
            $post = $this->post->find($id);
        
            $post->post_content = Input::get('job_description');
            $post->post_title   = Input::get('job_title');
            $post->post_name    = text_to_slug(Input::get('job_title'));

            if( Input::get('post_status') ) {
                $post->post_status  = Input::get('post_status');
            }
            
            $post->updated_at   = date('Y-m-d H:i:s');

            if( $post->save() ) {
                $inputs = Input::all();

                /* START SEND EMAIL NOTIFICATION */
                if($data['info']->post_status != 'approved' && Input::get('post_status') == 'approved' ) {


                    $msg['email_support'] = $this->setting->get_setting('admin_email');
                    $msg['site_title'] = ucwords($this->setting->get_setting('site_title'));

                    $msg['email'] = $this->user->find($info->post_author)->email;
                    $msg['name'] = $this->user->find($info->post_author)->firstname;

                    $msg['email_subject'] = 'Congratulations, your job posting has been approved!';

                    Mail::send('emails.admin.post-job-approved', $msg, function($message) use ($msg)
                    {
                        $message->from($msg['email_support'], $msg['site_title']);
                        $message->to($msg['email'])->subject($msg['email_subject']);
                    });

                }
                /* END SEND EMAIL NOTIFICATION */
 
                $inputs['zip_code'] = $this->usermeta->get_meta($user_id, 'zip_code');
                
                unset($inputs['_token']);
                unset($inputs['job_title']);
                unset($inputs['job_description']);
                unset($inputs['post_status']);

                foreach ($inputs as $meta_key => $meta_value) {
                    $this->postmeta->update_meta($id, $meta_key, array_to_json($meta_value));
                }            

                return Redirect::route('admin.job-postings.edit', $id)
                               ->with('success','Job posting has been updated!');

            }
        } 

        return view('admin.job-postings.edit', $data);
    }

    //--------------------------------------------------------------------------

    public function delete($id)
    {

        $this->post->findOrFail($id)->delete();

        return Redirect::route('admin.job-postings.index', query_vars())
                       ->with('success','Selected job has been move to trashed!');
    }

    //--------------------------------------------------------------------------

    public function restore($id)
    {   
        $user = $this->post->withTrashed()->findOrFail($id);
        $user->restore();
        return Redirect::route('admin.job-postings.index', ['type' => 'trash'])
                       ->with('success','Selected job has been restored!');
    }

    //--------------------------------------------------------------------------

  
    public function destroy($id)
    {   
        /* Delete all related records in table */
        $post = $this->post->withTrashed()->findOrFail($id);
        $this->postmeta->where('post_id', $id)->delete();

        $post->forceDelete();

        return Redirect::route('admin.job-postings.index', ['type' => 'trash'])
                       ->with('success','Selected job has been deleted permanently!');
    }

    //--------------------------------------------------------------------------

}
