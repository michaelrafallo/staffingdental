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

class ProviderAccountController extends Controller
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

        $data['user'] = $user = $this->user->find($id);

        $data['appointments'] = $this->post
							         ->where('post_title', $id)
							         ->where('post_type', 'booking')
							         ->where('post_status', 'completed')
							         ->count();

        $data['confirmed'] = $this->post
						          ->where('post_title', $id)
						          ->where('post_type', 'booking')
						          ->where('post_status', 'confirmed')
						          ->count();

        if( Input::get('_token') ) {
        
            $rules2=$rules=$rules1=array();
        
            $inputs = Input::all();

            if(Input::get('tab')==1) {
                $rules1 = [
                    'firstname'     => 'required',
                    'lastname'      => 'required',
                    'email'         => 'required|email|max:64|unique:users,email,'.$id.',id',
                ];    
                if(Input::get('new_password')) {
                    $rules2 = [
                        'new_password'              => 'required|min:4|max:64|confirmed|regex:/^(?=\S*[0-9])(?=\S*[A-Z])(?=\S*[\d])\S*$/',
                        'new_password_confirmation' => 'required|min:4'
                    ];                     
                }
                $rules = $rules1 + $rules2;
            }
            if(Input::get('tab')==2) {
                $rules = [
                    'minimum_hours'     => 'required',
                    'minimum_fee'      => 'required',
                    'travel_distance'  => 'required',
                ];    
            }
            if(Input::get('tab')==3) {
                $rules = [
                    'professional_objectives' => 'required'
                ];    
                if(Input::get('student')) {
                    $rules2 = [
                        'dental_school_name' => 'required'
                    ];                     
                }
                $rules = $rules1 + $rules2;

            }


            $messages = ['new_password.regex' => "The password must contain 1 upper case letter, 1 number, 6 characters"];

            $validator = Validator::make(Input::all(), $rules, $messages);

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
            
            $files = ['proof_of_insurance', 'profile_picture', 'government_issued_id'];

            foreach ($files as $file) {
                if( Input::hasFile($file) ) {
                    $path = $image_path = '';

                    $filename   = str_random(16);
                    $fileUpload = Input::file($file);
                    $imageFile  = $fileUpload->getRealPath();
                    $ext        = $fileUpload->getClientOriginalExtension();
                    $path       = 'uploads/images/users/'.$id;
                    
                    if( file_exists($info->$file) ) unlink($info->$file);

                    if( ! file_exists($path) ) mkdir($path);
                    $image_path = $path.'/'.$filename.'.png';  

                    $img = \Image::make($imageFile)->resize(300, '', function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->crop(300, 300, null, null)->save($image_path);
                    compress($image_path, $image_path, 70);

                    $inputs[$file] = $image_path;
                }
            }

            if( Input::hasFile('resume') ) {
                if( file_exists($info->resume) ) unlink($info->resume);

                $file       = Input::file('resume');
                $ext        = $file->getClientOriginalExtension();
                $filename   = str_random(16).'.'.$ext;
                $path       = 'uploads/images/users/'.$id;
                $file->move($path, $filename);

                $inputs['resume'] = $path.'/'.$filename;  
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

            $inputs['permanent_position'] = Input::get('permanent_position', 0);
            $inputs['temporary_assignments'] = Input::get('temporary_assignments', 0);
            $inputs['public_profile'] = Input::get('public_profile', 0);
            $inputs['student'] = Input::get('student', 'no');

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
            $profile_completeness = profile_completeness($userinfo, 'provider');
            $this->usermeta->update_meta($id, 'profile_completeness', $profile_completeness);
            

            return Redirect::back()->with('success','Your account has been updated!');

        }

        $selects = array('overall');
        $review_count = $this->post
					         ->search([], $selects, [])
					         ->where('post_title', $info->id)
					         ->where('post_type', 'review');

        $all_reviews_count = ($review_count->count()) ? $review_count->count() : 1;
        $data['overall_reviews'] = $review_count->get()->SUM('overall') / $all_reviews_count;
        $data['all_reviews_count'] = $review_count->count();

        return view('provider.accounts.profile', $data);
    }

    //--------------------------------------------------------------------------

    public function settings()
    {

        $id = Auth::User()->id;


        $data['info'] = $info = $this->user->find($id);

        foreach ($info->usermetas as $user_meta) {
            $data['info'][$user_meta->meta_key] = $user_meta->meta_value;
        }

        return view('provider.accounts.settings', $data);
    }

    //--------------------------------------------------------------------------


    public function schedule()
    {

        $user_id = Auth::User()->id;

        $data['info'] = $info = $this->user->find($user_id);

        foreach ($info->usermetas as $user_meta) {
            $data['info'][$user_meta->meta_key] = $user_meta->meta_value;
        }

        $calendar = array();

        if( $info->schedule ) {
            $days = json_decode($info->schedule);
            foreach ($days as $d => $day) {
                $calendar[] = array(
                    'title'  => get_times($day->from).' - '.get_times($day->to),                    
                    'allDay' => true,
                    'dow' => [$d],
                    'className' => 'recurring'
                );                
            }
        }

        $rows = $this->post->where('post_type', 'exception_available')->where('post_author', $user_id)->get();
        foreach ($rows as $row) {
            $content = json_decode($row->post_content);
            $calendar[] = array(
                'id'     => $row->id,
                'title'  => get_times($content->time_from).' - '.get_times($content->time_to),
                'start'  => $content->date_from,
                'end'    => $content->date_to.' 24:00:00',
                'allDay' => true,
                'overlap' => true,
                'className' => $content->time_from.'_'.$content->time_to.' schedule'
            );                
        }

        $rows = $this->post->where('post_type', 'exception_unavailable')->where('post_author', $user_id)->get();
        foreach ($rows as $row) {
            $content = json_decode($row->post_content);


            $calendar[] = array(
                'id'     => $row->id,
                'title'  => get_times($content->time_from).' - '.get_times($content->time_to),
                'start'  => $content->date_from,
                'end'    => $content->date_to.' 24:00:00',
                'allDay' => true,
                'overlap' => true,
                'className' => $content->time_from.'_'.$content->time_to.' exception'
            );                
        }


        $books = $this->post->where('post_type', 'booking')
                           ->where('post_title', $user_id)
                           ->where('post_status', 'confirmed')
                           ->get();

        foreach ($books as $book) {

            $postmeta = get_meta($book->postMetas()->get());

            $calendar[] = array(
                'url' => URL::route('provider.appointments.index', 'status=confirmed&id='.$book->id),
                'title'  => get_times($postmeta->start_time).' - '.get_times($postmeta->end_time),
                'start'  => $postmeta->date,
                'end'    => $postmeta->date.' 24:00:00',
                'allDay' => true,
                'overlap' => true,
                'className' => 'booked'
            );
        }

        $applications = $this->post->where('post_type', 'application')
                           ->where('post_author', $user_id)
                           ->where('post_status', 'hired')
                           ->get();

        foreach ($applications as $application) {

            $exceptions = json_decode($this->postmeta->get_meta($application->parent, 'schedule'));
            if( $exceptions ) {

                foreach ($exceptions as $exception) {
                    $calendar[] = array(
                        'url' => URL::route('provider.job-postings.view', $application->parent),
                        'title'  => get_times($exception->time_start).' - '.get_times($exception->time_end),
                        'start'  => $exception->date,
                        'end'    => $exception->date.' 24:00:00',
                        'allDay' => true,
                        'overlap' => true,
                        'className' => 'hired'
                    );
                }                
            }

        }


        $data['calendar'] = json_encode($calendar);

        if( $delete_id = Input::get('delete') ) {
            $this->post->find($delete_id)->forceDelete();
            $this->postmeta->where('post_id', $delete_id)->delete();
        }
        
        $inputs = Input::except(['_token', 'id', 'date', 'type']); 


        if( Input::get('type')  == 'schedule' ) {

            $inputs['schedule'] = Input::get('schedule', '');

            foreach ($inputs as $meta_key => $meta_value) {
                $this->usermeta->update_meta($user_id, $meta_key, array_to_json($meta_value));
            }

            return Redirect::back()->with('success','Schedule has been updated!');        
        }

        if( Input::get('type')  == 'exception' ) {
    
            $post = $this->post;        

            if( $id = Input::get('id') ) {
                $post = $this->post->find($id);        
            }

            $inputs['dates']        = get_dates_from_range($inputs['date_from'], $inputs['date_to']);

            $post->post_author  = $user_id;
            $post->post_content = json_encode($inputs);
            $post->post_status  = 'actived';
            $post->post_type    = Input::get('post_type') ? 'exception_available' : 'exception_unavailable';

            $post->save();

            foreach ($inputs as $meta_key => $meta_value) {
                $this->postmeta->update_meta($post->id, $meta_key, array_to_json($meta_value));
            }

            return Redirect::route('provider.accounts.schedule', 'date='.Input::get('date_to').'#schedule')->with('success','Your account has been updated!');
        }

        return view('provider.accounts.schedule', $data);
    }

    //--------------------------------------------------------------------------

    public function updateMeta()
    {   
        $user_id = Auth::User()->id;
        $key     = Input::get('key');
        $value   = Input::get('value');
        
        if($user_id && $key && $value) {
            $this->usermeta->update_meta($user_id, $key, $value);
        }

    }

    //--------------------------------------------------------------------------

}
