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

class ProviderAppointmentController extends Controller
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
        $user_id = Auth::User()->id;

        $data['status'] = $status = Input::get('status');

        if(!$status) 
            return Redirect::route('provider.appointments.index', ['status' => 'pending']);

        parse_str( query_vars(), $search );

        $queries = array();

        $data['rows'] = $this->post
					         ->where('post_title', $user_id)
					         ->where('post_type', 'booking')
					         ->search($search, $queries)
					         ->get();

        $data['pending'] = $this->post
						        ->where('post_title', $user_id)
						        ->where('post_type', 'booking')
						        ->where('post_status', 'pending')
						        ->count();

        $data['confirmed'] = $this->post
						          ->where('post_title', $user_id)
						          ->where('post_type', 'booking')
						          ->where('post_status', 'confirmed')
						          ->count();

        $data['completed'] = $this->post
						          ->where('post_title', $user_id)
						          ->where('post_type', 'booking')
						          ->where('post_status', 'completed')
						          ->count();

        $data['for_approval'] = $this->post
							         ->where('post_title', $user_id)
							         ->where('post_type', 'booking')
							         ->where('post_status', 'for_approval')
							         ->count();

        $data['cancelled'] = $this->post
						          ->where('post_title', $user_id)
						          ->where('post_type', 'booking')
						          ->where('post_status', 'cancelled')
						          ->count();

        return view('provider.appointments.index', $data);
    }

    //--------------------------------------------------------------------------

    public function view($id='')
    {   
        $data['info'] = $info = $this->post->find($id);

        foreach ($info->postmetas as $post_meta) {
            $data['info'][$post_meta->meta_key] = $post_meta->meta_value;
        }
        return view('provider.appointments.view', $data);
    }

    //--------------------------------------------------------------------------
  
    public function status($status ='', $id='')
    {       
        $post = $this->post->find($id);

        $post->post_status = $status;
        $post->save();

        /* START MESSAGE NOTIFICATION */
        $post_content = message_notification('provider_book_update_status', $status);
        $post_content .= ' <a href="'.URL::route('owner.appointments.index', ['status' => $status, 'id' => $post->id]).'">View appointment here</a> <small>Appointment ID# '.$post->id.'</small>';
        $post_data = array(
            'post_name'    => $status.'_appointment',
            'post_content' => $post_content,
            'post_id'      => $post->id,
            'post_content' => $post_content,
            'user_id'      => $post->post_title,
            'author_id'    => $post->post_author,
            'subject'      => 'A member has accepted your appointment'
        );
        $this->post->send_message_notification($post_data);  
        /* END MESSAGE NOTIFICATION */

        return Redirect::route('provider.appointments.index')
                       ->with('success','Appointment with <b>ID#'.$id.'</b> has been deleted!');
    }

    //--------------------------------------------------------------------------

}