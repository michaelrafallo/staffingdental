<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Redirect, Input, Auth, Hash, Session, URL, Mail, Config, DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\UserMeta;
use App\Post;
use App\PostMeta;
use App\Setting;

class MessageController extends Controller
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


    public function __construct(User $user, UserMeta $usermeta, Post $post, PostMeta $postmeta, Setting $setting, Request $request)
    {
        $this->user     = $user;
        $this->usermeta = $usermeta;
        $this->post     = $post;
        $this->postmeta = $postmeta;
        $this->setting  = $setting;
        $this->request  = $request;
    }

    //--------------------------------------------------------------------------

    public function index()
    {


        parse_str( query_vars(), $search );

        $auth = Auth::User();

        $data['user_id'] = $user_id = $auth->id;

        if( Input::get('ids') ) {

            $action = Input::get('action');

            $exact = explode('-', $action);

            if( in_array('not', $exact)) {
                list($not, $action) = $exact;

                foreach (Input::get('ids') as $post_id) {
                    $this->post->message_status($post_id, $user_id, $action, 'remove');
                }                
            } else {
                foreach (Input::get('ids') as $post_id) {
                    $this->post->message_status($post_id, $user_id, $action, 'add');
                }                
            }


            return Redirect::back()->with('success','Message status has been updated!');
        }

        $data['rows'] = $this->post->searchMsg($search)
                                    ->orderBy('posts.updated_at','DESC')
                                    ->paginate(15);

        $data['total'] = $this->post->searchMsg($search)->count();


        return view('messages.index', $data);

    }


    //--------------------------------------------------------------------------

    public function view($user_type='', $id='')
    {

        $data['auth'] = $auth = Auth::User();

        $group = ($auth->group == 'provider') ? 'owner' : 'provider';

        $data['user'] = $user = $this->user->where('group', $group)->findOrFail($id);
        $data['group'] = $user->group;

        $data['user_id'] = $user_id = $auth->id;
        $data['user_group'] = $auth->group;

        $data['uid'] = $id;

        $msg = $this->post
            ->where('post_author', $user_id)
            ->where('post_title', $id)
            ->where('post_type', 'inbox')
            ->orWhere('post_author', $id)
            ->where('post_title', $user_id)
            ->where('post_type', 'inbox')
            ->first();

        if( $msg ) {
            $post_id = $msg->id;
            $this->post->message_status($post_id, $user_id, 'read', 'add');

            $msg->updated_at = date('Y-m-d H:i:s');
            $msg->save(); 

        }


        $data['info'] = $this->post->join('users', 'users.id', '=', 'posts.post_title')
                                   ->where('post_type', 'message')
                                   ->where('users.id', '=', $id)
                                   ->first();

        $data['rows'] = $this->post->where('post_type', 'message')
                                    ->where('post_author', $id)
                                    ->where('post_title', $user_id)
                                    ->orWhere('post_type', 'message')
                                    ->where('post_author', $user_id)
                                    ->where('post_title', $id)
                                    ->orderBy('id', 'ASC')
                                    ->get();

        return view('messages.view', $data);
    }

    //--------------------------------------------------------------------------

    public function sent($user_type='', $id='')
    {

        $rules = ['message' => 'required'];

        $validator = Validator::make(Input::all(), $rules);

        if( ! $validator->passes() ) {       
            if(  $this->request->ajax() )   {
                $response = array('error' => true, 'details' => $validator->errors());
                return json_encode( $response );                              
            }  
        }

        $auth = Auth::User();

        $data['user'] = $user = $this->user->find($id);
        $data['group'] = $user->group;

        $data['user_id'] = $user_id = $auth->id;
        $data['user_group'] = $auth->group;

        $data['uid'] = $id;

        $msg = $this->post
		            ->where('post_author', $user_id)
		            ->where('post_title', $id)
		            ->where('post_type', 'inbox')
		            ->orWhere('post_author', $id)
		            ->where('post_title', $user_id)
		            ->where('post_type', 'inbox')
		            ->first();

        if( $msg ) {
            $post_id = $msg->id;

            $msg->updated_at = date('Y-m-d H:i:s');
            $msg->save(); 

        }

        if( Input::get('_token') ) {

            $insertRules = [
                'message'             => 'required',
            ];    

            $validator = Validator::make(Input::all(), $insertRules);

            if( ! $validator->passes() ) {
                return Redirect::back()
                               ->withErrors($validator)
                               ->withInput(); 
            }                

            if( $msg ) {

                $post = $this->post;
                
                $post->post_author  = $user_id;
                $post->post_content = Input::get('message');
                $post->post_title   = $id;
                $post->post_status  = 'unread';
                $post->post_name    = 'message';
                $post->post_type    = 'message';
                $post->parent       = $post_id;
                $post->created_at   = date('Y-m-d H:i:s');

                $post->save();
	


            } else {

                $to[] = $user_id;
                $to[] = $id;

                $main = new Post();
                
                $main->post_author  = $user_id;
                $main->post_content = json_encode($to);
                $main->post_title   = $id;
                $main->post_status  = 'actived';
                $main->post_name    = 'single_message';
                $main->post_type    = 'inbox';

                $main->save();

                $post = $this->post;
                
                $post->post_author  = $user_id;
                $post->post_content = Input::get('message');
                $post->post_title   = $id;
                $post->post_status  = 'unread';
                $post->post_name    = 'message';
                $post->post_type    = 'message';
                $post->parent       = $post_id = $main->id;
                $post->created_at   = date('Y-m-d H:i:s');

                $post->save();

            }


            $this->postmeta->update_meta($post_id, 'archived', '');

  			$this->post->message_status($post_id, $id, 'read', 'remove');
        } 


        $this->post->message_status($post_id, $user_id, 'read', 'add');

        $data['info'] = $this->post->join('users', 'users.id', '=', 'posts.post_title')
                                   ->where('post_type', 'message')
                                   ->where('users.id', '=', $id)
                                   ->first();

        $data['rows'] = $this->post->where('post_type', 'message')
                                    ->where('post_author', $id)
                                    ->where('post_title', $user_id)
                                    ->orWhere('post_type', 'message')
                                    ->where('post_author', $user_id)
                                    ->where('post_title', $id)
                                    ->orderBy('id', 'ASC')
                                    ->get();

        if(  $this->request->ajax() )   {            
            return view('messages.box', $data);
        }

        return Redirect::back()->with('success', 'Message sent!');
    }

    //--------------------------------------------------------------------------


}
