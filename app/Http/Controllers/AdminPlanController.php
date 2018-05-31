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

class AdminPlanController extends Controller
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

        $data['rows'] = $this->post
                ->where('post_type', 'plan')
                ->search($search)
                ->paginate(15);

        $data['count'] = $this->post
                ->search($search)
                ->where('post_type', 'plan')
                ->count();

        $data['all'] = $this->post->where('posts.post_type', 'plan')->count();

        $data['trashed'] = $this->post->withTrashed()
                                      ->where('posts.post_type', 'plan')
                                      ->where('deleted_at', '<>', '0000-00-00')
                                      ->count();

        return view('admin.plans.index', $data);
    }

    //--------------------------------------------------------------------------

    public function add()
    {

        $auth = Auth::User();
        
        $user_id = $auth->id;

        if( Input::get('_token') ) {

            $updateRules = [
                'title' => 'required'
            ];    

            $validator = Validator::make(Input::all(), $updateRules);

            if( ! $validator->passes() ) {
                return Redirect::route('admin.plans.add')
                               ->withErrors($validator)
                               ->withInput(); 
            }                

            $inputs['description'] = Input::get('description');
            $inputs['total']       = Input::get('total');
    
            $post = $this->post;
        
            $post->post_content = json_encode($inputs);
            $post->post_title   = Input::get('title');
            $post->post_name    = text_to_slug(Input::get('title'));
            $post->post_type    = 'plan';
            $post->post_status  = 'actived';
            
            if( $post->save() ) {     

                return Redirect::route('admin.plans.edit', $post->id)
                               ->with('success','New plan has been created!');

            }
        } 

        return view('admin.plans.add');
    }

    //--------------------------------------------------------------------------

    public function edit($id='')
    {
        $auth = Auth::User();
        
        $user_id = $auth->id;

        $data['info'] = $info = $this->post->find($id);

        $data['description'] = json_decode($info->post_content)->description;
        $data['total']       = json_decode($info->post_content)->total;

        foreach ($info->postmetas as $post_meta) {
            $data['info'][$post_meta->meta_key] = $post_meta->meta_value;
        }

        if( Input::get('_token') ) {

            $updateRules = [
                'title' => 'required'
            ];    

            $validator = Validator::make(Input::all(), $updateRules);

            if( ! $validator->passes() ) {
                return Redirect::route('admin.plans.add')
                               ->withErrors($validator)
                               ->withInput(); 
            }                

            $inputs['description'] = Input::get('description');
            $inputs['total']       = Input::get('total');
        
            $info->post_content = json_encode($inputs);
            $info->post_title   = Input::get('title');
            $info->post_name    = text_to_slug(Input::get('title'));
            $info->post_type    = 'plan';
            $info->post_status  = Input::get('status') ? 'actived' : 'inactived';

            if( $info->save() ) {     

                return Redirect::route('admin.plans.edit', $id)
                               ->with('success','Plan has been updated!');

            }
        } 

        return view('admin.plans.edit', $data);
    }

    //--------------------------------------------------------------------------
  
    public function delete($id)
    {

        $this->post->findOrFail($id)->delete();

        return Redirect::route('admin.plans.index', query_vars())
                       ->with('success','Selected plan has been move to trashed!');
    }

    //--------------------------------------------------------------------------

    public function restore($id)
    {   
        $post = $this->post->withTrashed()->findOrFail($id);
        $post->restore();
        return Redirect::route('admin.plans.index', ['type' => 'trash'])
                       ->with('success','Selected plan has been restored!');
    }

    //--------------------------------------------------------------------------

  
    public function destroy($id)
    {   
        /* Delete all related records in table */
        $post = $this->post->withTrashed()->findOrFail($id);
        $this->postmeta->where('post_id', $id)->delete();

        $post->forceDelete();
        
        return Redirect::route('admin.plans.index', ['type' => 'trash'])
                       ->with('success','Selected plan has been deleted permanently!');
    }

    //--------------------------------------------------------------------------

}
