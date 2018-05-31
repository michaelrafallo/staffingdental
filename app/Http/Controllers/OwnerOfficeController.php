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

class OwnerOfficeController extends Controller
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

    public function edit($id='')
    {

        $user_id = Auth::User()->id;

        if( Input::get('_token') ) {

            $rules = [
                'company_name'      => 'required',
                'street_address'    => 'required',
                'city'              => 'required',
                'state'             => 'required',
                'zip_code'          => 'required',
            ];    

            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::back()
                               ->withErrors($validator)
                               ->withInput(); 
            }     


            $inputs = Input::except('_token');

            $office = $id ? $this->post->find($id) : $this->post;

            $address = implode(' ', [Input::get('street_address'), Input::get('city'), states(Input::get('state')), Input::get('zip_code')]);

            $office->post_author  = $user_id;
            $office->post_title   = $address;
            $office->post_name    = Input::get('company_name');
            $office->post_content = json_encode($inputs);
            $office->post_status  = Input::get('post_status');
            $office->post_type    = 'office';
            $office->save();

            $geolocation = get_geolocation($address);
            $this->postmeta->update_meta($office->id, 'geolocation', json_encode($geolocation));

            return Redirect::back()->with('success','Office information been updated!');
        }


        $data['info'] = $info = $this->post
                                     ->where('post_author', $user_id)
                                     ->where('post_type', 'office')
                                     ->where('id', $id)
                                     ->first();

        foreach (json_decode($info->post_content) as $post_meta_k => $post_meta_v) {
            $data['info'][$post_meta_k] = $post_meta_v;
        }

        return view('owner.offices.edit', $data);
    }

    //--------------------------------------------------------------------------

    public function add()
    {

        $user_id = Auth::User()->id;
        
        if( Input::get('_token') ) {
            $inputs = Input::except('_token');

            foreach($inputs['offices'] as $request) {
                $r = new Post();
                $r->post_author  = $user_id;
                $r->post_content = json_encode($request);
                $r->post_status  = 'actived';
                $r->post_type    = 'office';
                $r->post_name    = $request['company_name'];
                $r->post_title   = $address = $request["street_address"].' '.$request["city"].' '.states($request["state"]).' '.$request["zip_code"];
                $r->save();

                $geolocation = get_geolocation($address);
                $this->postmeta->update_meta($r->id, 'geolocation', json_encode($geolocation));
            }

            return Redirect::route('owner.accounts.settings', ['tab' => 3])->with('success','New office address been added!');
        }


        return view('owner.offices.add');
    }

    //--------------------------------------------------------------------------

    public function view($id='')
    {

        $user_id = Auth::User()->id;
        
        if( Input::get('_token') ) {
            $inputs = Input::except('_token');

            $office = $this->post->find($id);

            if( $office->post_status != 'actived' ) {
                if( Input::get('agree') ) {
                    $office->post_status = 'actived';
                } 

                 $requests = json_decode($office->post_content, true);

                foreach ($requests as $request) {
                    $r = new Post();
                    $r->post_author  = $user_id;
                    $r->post_content = json_encode($request);
                    $r->post_status  = 'actived';
                    $r->post_type    = 'office';
                    $r->post_title   = $request['company_name'];
                    $r->post_name    = $request["street_address"].' '.$request["city"].' '.states($request["state"]).' '.$request["zip_code"];
                    $r->save();
                }

                if( Input::get('offices') ) {
                    foreach ($inputs['offices'] as $o) {
                        if( count(array_filter($o)) != 0 ) {
                            $offices[] = $o;
                        }
                    }                
        
                    $office->post_author  = $user_id;
                    $office->post_content = json_encode($offices);
                    $office->post_status  = 'pending';
                    $office->post_name    = '0.00';

                } 
            }

            $office->save();

            return Redirect::back()->with('success','Office address been updated!');
        }

        $data['info'] = $this->post->find($id);

        return view('owner.offices.view', $data);
    }

    //--------------------------------------------------------------------------

    public function destroy($id)
    {   
        /* Delete all related records in table */
        $post = $this->post->findOrFail($id);
        $this->postmeta->where('post_id', $id)->delete();

        $post->forceDelete();

        return Redirect::back()
                       ->with('success','Selected office has been deleted!');
    }

    //--------------------------------------------------------------------------

}
