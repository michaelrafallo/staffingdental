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

class ProviderPointController extends Controller
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
        $data['user_id'] = $id = Auth::User()->id;

        $data['info'] = $info = $this->user->find($id);

        foreach ($info->usermetas as $user_meta) {
            $data['info'][$user_meta->meta_key] = $user_meta->meta_value;
        }

        $date = date('Y-m-d H:i:s');
        $referral_7_days  = date('Y-m-d H:i:s', strtotime('-7 days'));
        $referral_30_days = date('Y-m-d H:i:s', strtotime('-30 days'));


        $selects = array('referrer_points');
        $queries = array('referrer_user_id');

        $search['referrer_user_id'] = $id;
        $referrer = $this->user->search($search, $selects, $queries);
        $data['referral_7_days'] = $referrer->whereBetween('created_at', [$referral_7_days, $date])
									        ->get()
									        ->SUM('referrer_points');

        $data['referral_30_days'] = $referrer->whereBetween('created_at', [$referral_30_days, $date])
									         ->get()
									         ->SUM('referrer_points');

        $selects = array('engage_reward');
        $referrer = $this->post->search([], $selects, [])
					           ->where('post_type', 'application')
					           ->where('post_author', $id)
					           ->where('post_status', 'hired');

        $data['engage_7_days'] = $referrer->whereBetween('created_at', [$referral_7_days, $date])
								          ->get()
								          ->SUM('engage_reward');

        $data['engage_30_days'] = $referrer->whereBetween('created_at', [$referral_30_days, $date])
								           ->get()
								           ->SUM('engage_reward');

        $selects = array('referrer_user_id');

        $provider = $this->user->search($search, $selects, $queries);
        $data['providers'] = $provider->where('group', 'provider')->get();

        $owner = $this->user->search($search, $selects, $queries);
        $data['owners'] = $owner->where('group', 'owner')->get();


        return view('provider.points.index', $data);
    }

    //--------------------------------------------------------------------------

}
