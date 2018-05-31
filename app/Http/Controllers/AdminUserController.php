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

class AdminUserController extends Controller
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
                User::find($id)->delete();
                Post::where('post_author', $id)->delete();
            }
            return Redirect::route('admin.users.index', query_vars())
                           ->with('success','Selected user has been move to trashed!');
        }

        if( Input::get('action') == 'restore') {
            foreach( Input::get('ids') as $id ) {
                $user = User::withTrashed()->findOrFail($id);
                $user->restore();

                Post::where('post_author', $id)->restore();
            }
            return Redirect::route('admin.users.index', query_vars())
                           ->with('success','Selected user has been restored!');
        }

        if( Input::get('action') == 'destroy') {
            foreach( Input::get('ids') as $id ) {
                $this->user->force_destroy($id);
            }
            return Redirect::route('admin.users.index', query_vars())
                           ->with('success','Selected user has been deleted permanently!');
        }


        $queries = array();
        $selects = array();
        foreach(Input::all() as $input_k => $input_v) {            
            if($input_v) {
                if( in_array($input_k, $selects) ) {
                   $queries[] = $input_k;
                }                
            }
        }

        $data['rows'] = $this->user
                ->search($search, $selects, $queries)
                ->orderBy('id', Input::get('sort', 'DESC'))
                ->paginate(Input::get('rows', 15));

        $data['count'] = $this->user
                ->search($search, $selects, $queries)
                ->count();

        $data['all'] = $this->user->where('users.id', '!=', 1)->count();
        $data['trashed'] = $this->user->withTrashed()->where('deleted_at', '<>', '0000-00-00')->count();

        return view('admin.users.index', $data);
    }

    //--------------------------------------------------------------------------

    public function profile()
    {
        $user_id = Auth::User()->id;

        $data['info'] = $user = $this->user->find($user_id);

        $data['pic'] = $pic = @$this->usermeta->get_meta($user_id, 'profile_picture');

        if( Input::get('op') == 2)
        {
            
            $updateRules = [
                'new_password'              => 'required|min:4|max:64|confirmed',
                'new_password_confirmation' => 'required|min:4',
            ];    

            $validator = Validator::make(Input::all(), $updateRules);

            if($validator->passes()) {

                $user->password = Hash::make( Input::get('new_password') );
                                    
                if( $user->save() ) {

                    return Redirect::route('admin.users.profile')
                                   ->with('success','Password has been updated!');
                } 
            }

            return Redirect::route('admin.users.profile')
                           ->withErrors($validator)
                           ->withInput(); 
        }


        if( Input::get('op') == 1)
        {
            

            $updateRules = [
                'email'          => 'required|email|max:64|unique:users,email,'.$user_id.',id',
                'firstname'      => 'required|max:25',
                'lastname'       => 'required|max:25',
            ];    

            $validator = Validator::make(Input::all(), $updateRules);

            if($validator->passes()) {

                $user->fill(Input::all());   

                $profile_pic = $pic;
   
                if( Input::hasFile('file') ) {
                    $filename   = str_random(16);
                    $fileUpload = Input::file('file');
                    $imageFile  = $fileUpload->getRealPath();
                    $ext        = $fileUpload->getClientOriginalExtension();
                    $path       = 'uploads/images/users/'.$user_id;

                    if( ! file_exists($path) ) mkdir($path);

                    $profile_pic = $path.'/'.$filename.'.png';  
                    if( file_exists($pic) ) unlink($pic);

                    $img = \Image::make($imageFile)->resize(300, '', function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->crop(300, 300, null, null)->save($profile_pic);
                    compress($profile_pic, $profile_pic, 70);
                
                }
                
                $user_metas = ["profile_picture" => $profile_pic];

                foreach ($user_metas as $meta_key => $meta_value) {
                    $this->usermeta->update_meta($user_id, $meta_key, $meta_value);
                }
                                    
                if( $user->save() ) {

                    return Redirect::route('admin.users.profile')
                                   ->with('success','Your profile has been updated!');
                } 
            }

            return Redirect::route('admin.users.profile')
                           ->withErrors($validator)
                           ->withInput(); 
        }


        return view('admin.users.profile', $data);
    }

    //--------------------------------------------------------------------------

    public function edit($id='')
    {
        $user_metas = array();
        
        $data['info'] = $info = $this->user->find($id);

        foreach ($info->usermetas as $user_meta) {
            $data['info'][$user_meta->meta_key] = $user_meta->meta_value;
        }


        $pass = array(
            'current_password',
            'new_password',
            'new_password_confirmation',
        );

        $this->usermeta->whereIn('meta_key', $pass)->delete();            

        $data['pic'] = $pic = @$this->usermeta->get_meta($id, 'profile_picture');

        $data['details'] = @$this->usermeta->select('meta_key', 'meta_value')->where('user_id', $id)->orderBy('id', 'ASC')->get();

        if( Input::get('op') == 2)
        {
            
            $updateRules = [
                'new_password'              => 'required|min:4|max:64|confirmed',
                'new_password_confirmation' => 'required|min:4',
            ];    

            $validator = Validator::make(Input::all(), $updateRules);

            if($validator->passes()) {

                $user = $this->user->find($id);
                $user->password = Hash::make( Input::get('new_password') );
                                    
                if( $user->save() ) {

                    return Redirect::route('admin.users.edit', $id)
                                   ->with('success','Password has been updated!');
                } 
            }

            return Redirect::route('admin.users.edit', $id)
                           ->withErrors($validator)
                           ->withInput(); 
        }


        if( Input::get('op') == 1)
        {
            

            $updateRules = [
                'email'          => 'required|email|max:64|unique:users,email,'.$id.',id',
                'firstname'      => 'required|max:25',
                'lastname'       => 'required|max:25',
            ];    

            $validator = Validator::make(Input::all(), $updateRules);

            if($validator->passes()) {

                $user = $this->user->find($id);

                $user->fill(Input::all());   
                $user->fullname = strtolower(Input::get('firstname').' '.Input::get('lastname'));    

                if( Input::get('status') ) {
                    $user->status = Input::get('status', 0);
                }       

                $profile_pic = $pic;
   
                if( Input::hasFile('file') ) {
                    $filename   = str_random(16);
                    $fileUpload = Input::file('file');
                    $imageFile  = $fileUpload->getRealPath();
                    $ext        = $fileUpload->getClientOriginalExtension();
                    $path       = 'uploads/images/users/'.$id;

                    if( ! file_exists($path) ) mkdir($path);

                    $profile_pic = $path.'/'.$filename.'.png';  
                    if( file_exists($pic) ) unlink($pic);

                    $img = \Image::make($imageFile)->resize(300, '', function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->crop(300, 300, null, null)->save($profile_pic);
                    compress($profile_pic, $profile_pic, 70);

                    $user_metas["profile_picture"] = $profile_pic;

                }

                if($user->group != 'admin') {
                    $user_metas['user_status']  = Input::get('user_status', 'unverified');
                }

                foreach ($user_metas as $meta_key => $meta_value) {
                    $this->usermeta->update_meta($id, $meta_key, $meta_value);
                }
                                    
                if( $user->save() ) {

                    /* START SEND EMAIL NOTIFICATION */
                    if($data['info']->status != 'approved' && Input::get('status')=='approved') {

                        $data['name']      = ucwords( $user->firstname );
                        $data['email']     = $user->email;
                        $data['site_name'] = $site_name = ucwords($this->setting->get_setting('site_title'));

                        $data['email_support'] = $this->setting->get_setting('admin_email');
                        $data['email_title']   = 'Congratulations, your account has been approved!';

                        Mail::send('emails.approved-account', $data, function($message) use ($data)
                        {
                            $message->from($data['email_support'], $data['site_name']);
                            $message->to($data['email'])->subject($data['email_title']);
                        });

                    }
                    /* END SEND EMAIL NOTIFICATION */


                    return Redirect::route('admin.users.edit', $id)
                                   ->with('success','Your account has been updated!');
                } 
            }

            return Redirect::route('admin.users.edit', $id)
                           ->withErrors($validator)
                           ->withInput(); 
        }

        return view('admin.users.edit', $data);
    }

    //--------------------------------------------------------------------------


    public function add()
    {

        if( Input::get('op') )
        {
            
            $updateRules = [
                'email'          => 'required|email|max:64|unique:users,email',
                'firstname'      => 'required|max:25',
                'lastname'       => 'required|max:25',
                'password'       => 'required',
            ];    

            $validator = Validator::make(Input::all(), $updateRules);

            if($validator->passes()) {

                $user = $this->user;

                $user->fill(Input::all());      
                $user->group    = 'admin';    
                $user->status   = 'actived';
                $user->password = Hash::make( Input::get('password') );
                $user->fullname = strtolower(Input::get('firstname').' '.Input::get('lastname'));    
                                    
                if( $user->save() ) {
                    return Redirect::route('admin.users.edit', $user->id)
                                   ->with('success','New account has been created!');
                } 
            }

            return Redirect::route('admin.users.add')
                           ->withErrors($validator)
                           ->withInput(); 
        }

        return view('admin.users.add');
    }

    //--------------------------------------------------------------------------
  
    public function delete($id)
    {

        $this->user->findOrFail($id)->delete();
        $this->post->where('post_author', $id)->delete();

        return Redirect::route('admin.users.index', query_vars())
                       ->with('success','Selected user has been move to trashed!');
    }

    //--------------------------------------------------------------------------

    public function restore($id)
    {   
        $user = $this->user->withTrashed()->findOrFail($id);
        $user->restore();

        $this->post->where('post_author', $id)->restore();


        return Redirect::route('admin.users.index', ['type' => 'trash'])
                       ->with('success','Selected user has been restored!');
    }

    //--------------------------------------------------------------------------

  
    public function destroy($id)
    {   

        $this->user->force_destroy($id);
        $this->post->where('post_author', $id)->delete();

        return Redirect::route('admin.users.index', ['type' => 'trash'])
                       ->with('success','Selected user has been deleted permanently!');
    }

    //--------------------------------------------------------------------------

    public function login($id)
    {

        Auth::loginUsingId($id);
        $group = Auth::User()->group; 
        
        return Redirect::route($group.'.dashboard');

    }

    //--------------------------------------------------------------------------

    public function updateMeta()
    {   
        $user_id = Input::get('id');
        $key     = Input::get('key');
        $value   = Input::get('value');
        
        if($user_id && $key && $value) {
            $this->usermeta->update_meta($user_id, $key, $value);
        }

    }

    //--------------------------------------------------------------------------
}
