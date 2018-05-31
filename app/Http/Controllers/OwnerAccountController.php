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

class OwnerAccountController extends Controller
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

    public function profile()
    {
        $data['user_id'] = $id = Auth::User()->id;

        $data['info'] = $info = $this->user->find($id);

        foreach ($info->usermetas as $user_meta) {
            $data['info'][$user_meta->meta_key] = $user_meta->meta_value;
        }

        $data['user'] = $this->user->find($id);


        $data['appointments'] = $this->post
							         ->where('post_author', $id)
							         ->where('post_type', 'booking')
							         ->where('post_status', 'completed')
							         ->count();
        
        $data['hired'] = count($this->post->where('post_title', $id)
                                    ->where('post_type', 'application')
                                    ->where('post_status', 'hired')
                                    ->groupBy('post_author')
                                    ->get());


        if( Input::get('_token') ) {

            $rules2=$rules=array();
        
            $inputs = Input::all();

            if(Input::get('tab')==1) {
                $rules1 = [
                    'firstname'     => 'required',
                    'lastname'      => 'required',
                    'email'         => 'required|email|max:64|unique:users,email,'.$id.',id',
                ];    
                if($inputs['new_password']) {
                    $rules2 = [
                        'new_password'              => 'required|min:4|max:64|confirmed',
                        'new_password_confirmation' => 'required|min:4',
                    ];                     
                }
                $rules = $rules1 + $rules2;
            }


            $validator = Validator::make(Input::all(), $rules);

            if( ! $validator->passes() ) {
                return Redirect::back()
                               ->withErrors($validator)
                               ->withInput(); 
            }      

            $u = $this->user->find($id);


            /* START GEOLOCATION */
            $address = array(
              @$inputs['street_address'],
              @$inputs['city'],
              @$inputs['state'],
              @$inputs['zip_code'],
            );


            if( count(array_filter($address)) >= 2 ) {
                $geolocation = get_geolocation(implode(' ', $address));
                $location = $inputs + $geolocation;

                $u->lat = $location['lat'];
                $u->lng = $location['lng'];
            }
            /* END GEOLOCATION */

            $pics = ['government_issued_id', 'profile_picture'];

            foreach($pics as $pic) {

                if( Input::hasFile($pic) ) {
                    $path = $image_path = '';

                    $filename   = str_random(16);
                    $fileUpload = Input::file($pic);
                    $imageFile  = $fileUpload->getRealPath();
                    $ext        = $fileUpload->getClientOriginalExtension();
                    $path       = 'uploads/images/users/'.$id;
                    
                    if( file_exists($info->$pic) ) unlink($info->$pic);

                    if( ! file_exists($path) ) mkdir($path);
                    $image_path = $path.'/'.$filename.'.png';  

                    $img = \Image::make($imageFile)->resize(300, '', function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->crop(300, 300, null, null)->save($image_path);
                    compress($image_path, $image_path, 70);

                    $inputs[$pic] = $image_path;
                }

            }
            

			if( isset($inputs['firstname']) ) {
	            $u->firstname = $inputs['firstname'];
	            $u->lastname  = $inputs['lastname'];
	            $u->fullname  = ucwords($inputs['firstname'].' '.$inputs['lastname']);
			}  

            if( isset($inputs['new_password']) ) {
                $u->password = Hash::make($inputs['new_password']);
            }  

            $u->save();                 

            $unsets = ['_token', 'tab', 'firstname', 'lastname'];
            foreach($unsets as $unset) {
                unset($inputs[$unset]);
            }

            foreach ($inputs as $meta_key => $meta_value) {

    			if($meta_key == 'email') {
    				$u = $this->user->find($id);
		            $u->email = $inputs['email'];
		            $u->save();    				
    			}     

                $this->usermeta->update_meta($id, $meta_key, array_to_json($meta_value));
            }

            /* Update profile completeness */
            $userinfo = $this->user->find($id);
            foreach ($userinfo->usermetas as $user_meta) {
                $userinfo[$user_meta->meta_key] = $user_meta->meta_value;
            }
            $profile_completeness = profile_completeness($userinfo, 'owner');
            $this->usermeta->update_meta($id, 'profile_completeness', $profile_completeness);

            return Redirect::back()->with('success','Your account has been updated!');

        }

        $selects = array('overall');
        $review_count = $this->post->search([], $selects, [])->where('post_title', $info->id)->where('post_type', 'review');
        $all_reviews_count = ($review_count->count()) ? $review_count->count() : 1;
        $data['overall_reviews'] = $review_count->get()->SUM('overall') / $all_reviews_count;
        $data['all_reviews_count'] = $review_count->count();
        
        return view('owner.accounts.profile', $data);
    }

    //--------------------------------------------------------------------------

    public function settings()
    {
        $id = Auth::User()->id;


        $data['info'] = $info = $this->user->find($id);

        foreach ($info->usermetas as $user_meta) {
            $data['info'][$user_meta->meta_key] = $user_meta->meta_value;
        }


        $data['offices'] = $this->post->where('post_author', $id)
                                      ->where('post_type', 'office')
                                      ->get();

        $data['requests'] = $this->post->where('post_author', $id)
                                      ->where('post_type', 'add_office')
                                      ->get();

        return view('owner.accounts.settings', $data);
    }


    //--------------------------------------------------------------------------

}
