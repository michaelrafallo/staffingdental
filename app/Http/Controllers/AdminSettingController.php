<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Redirect, Input, Auth, Hash, Session, URL, Mail;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\UserMeta;
use App\Post;
use App\PostMeta;
use App\Setting;

class AdminSettingController extends Controller
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

        $data = array();
        
        $data['post'] = $this->post;                            

        $data['info'] = (object)$this->setting->get()->pluck('value', 'key')->toArray();

        if ( Input::get('_token') ) 
        {   
            $inputs = Input::all();

            if( Input::hasFile('logo') ) {
                $pic = upload_image(Input::file('logo'), 'uploads/images', @$data['info']->logo, false);
                $inputs['logo'] = $pic;
            }         

            $inputs = Input::except(['_token', 'op']);

            foreach($inputs as $key => $val) {

                $setting = Setting::where('key', $key)->first();

                if( ! $setting ) {
                    $setting = new Setting();
                }

                if( $val ) {
                    $setting->key   = $key;
                    $setting->value = $val;

                    $setting->save();                    
                }

            }   


            return Redirect::route('admin.settings.index', query_vars())
                           ->with('success','Changes saved.');

        }

        return view('admin.settings.index', $data);


    }

    //--------------------------------------------------------------------------

}
