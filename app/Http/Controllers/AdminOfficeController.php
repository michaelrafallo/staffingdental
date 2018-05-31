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

class AdminOfficeController extends Controller
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

        parse_str( query_vars(), $search );

        $data['requests'] = $this->post->search($search)
                                    ->where('post_type', 'add_office')
                                       ->orderBy('id', 'DESC')
                                       ->paginate(15);        

        return view('admin.offices.index', $data);
    }

    //--------------------------------------------------------------------------

    public function view($id='')
    {
        $office = $this->post->find($id);

        $user_id = $office->post_author;

        $data['user'] = $user = $this->user->find($user_id);
        foreach ($user->usermetas as $user_meta) {
            $data['user'][$user_meta->meta_key] = $user_meta->meta_value;
        }

        $data['info'] = $this->post->find($id);

        $data['offices'] = $this->post->where('post_author', $user_id)
                                      ->where('post_type', 'office')
                                      ->get();

        if( Input::get('_token') ) {
            
            $office_data = json_decode($office->post_content, true);
            $total = 0;
            foreach (Input::get('amount') as $amount_k => $amount_v) {
                $office_data[$amount_k]['amount'] = $amount_v;
                $total += $amount_v;
            }

            $office->post_content = json_encode($office_data);
            $office->post_name    = number_format($total, 2);
            $office->post_title   = Input::get('notes');
            $office->post_status  = $total ? 'processed' : 'pending';
            $office->save();

            return Redirect::back()->with('success','Office request been updated!');
        }

        return view('admin.offices.view', $data);
    }

    //--------------------------------------------------------------------------

    public function edit($id='')
    {
        $offices = array();

        $user_id = Auth::User()->id;
        
        if( Input::get('_token') ) {
            $inputs = Input::except('_token');

            $office = $this->post->find($id);

            foreach ($inputs['offices'] as $o) {
                if( count(array_filter($o)) != 0 ) {
                    $offices[] = $o;
                }
            }

            $office->post_author  = $user_id;
            $office->post_content = json_encode($offices);
            $office->save();

            return Redirect::route('admin.offices.view', $id)->with('success','Office request been updated!');
        }

        $data['info'] = $this->post->find($id);

        return view('admin.offices.edit', $data);
    }

    //--------------------------------------------------------------------------

}
