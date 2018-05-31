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
use App\ZipCode;

class ProviderJobPostingController extends Controller
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
    protected $zipcode;


    public function __construct(User $user, UserMeta $usermeta, Post $post, PostMeta $postmeta, Setting $setting, ZipCode $zipcode)
    {
        $this->user = $user;
        $this->usermeta = $usermeta;
        $this->post = $post;
        $this->postmeta = $postmeta;
        $this->setting = $setting;
        $this->zipcode = $zipcode;
    }

    //--------------------------------------------------------------------------

    public function index()
    {
        parse_str( query_vars(), $search );

        $data['user_id'] = $user_id = Auth::User()->id;

        $data['post'] = $this->post;

        $data['info'] = $info = $this->user->find($user_id);
        foreach ($info->usermetas as $user_meta) {
            $data['info'][$user_meta->meta_key] = $user_meta->meta_value;
        }

        $selects = array('job_status', 'provider_type', 'job_type', 'salary_rate');
        $queries = array();

        $search['job_status'] = 'open';

        /* START Search nearest location */
        $search['lat'] = $info->lat;
        $search['lng'] = $info->lng;

        if( $zip_code = Input::get('zip_code', $info->zip_code) ) {
            $coordinates = $this->zipcode->get_coordinates($zip_code);
            $search['lat'] = $coordinates['lat'];
            $search['lng'] = $coordinates['lng'];
        } 

        $search['circle_radius'] = 3959;
        $search['distance'] = Input::get('miles', $info->travel_distance ? $info->travel_distance : 100);
        /* END Search nearest location */

        $data['rows'] = $this->post->where('id', 0)->simplePaginate(10);
        $data['count'] = $this->post->where('id', 0)->count();

        if( $search['lat'] &&  $search['lng'] ) {
            $data['rows'] = $this->post
                                 ->search($search, $selects, $queries)
                                 ->where('post_type', 'job_posting')
                                 ->whereIn('post_status', ['actived', 'approved'])
                                 ->orderBy(Input::get('sort_by', 'id'), Input::get('order_by', 'DESC'))
                                 ->simplePaginate(10);

            $data['count'] = count($this->post
                                 ->search($search, $selects, $queries)
                                 ->where('post_type', 'job_posting')
                                 ->whereIn('post_status', ['actived', 'approved'])
                                 ->get());
        }

        return view('provider.job-postings.index', $data);
    }

    //--------------------------------------------------------------------------

    public function myJobs()
    {
        parse_str( query_vars(), $search );

        $user_id = Auth::User()->id;

        $data['user'] = $this->user;

        $data['rows'] = $this->post
                             ->where('post_type', 'application')
                             ->where('post_author', $user_id)
                             ->orderBy(Input::get('sort_by', 'id'), Input::get('order_by', 'DESC'))
                             ->paginate(15);

        return view('provider.job-postings.my-jobs', $data);
    }

    //--------------------------------------------------------------------------

    public function view($id)
    {
        $user_id = Auth::User()->id;

        $data['user'] = $this->user->find($user_id);

        $data['info'] = $info = $this->post->findOrFail($id);

        foreach ($info->postmetas as $post_meta) {
            $data['info'][$post_meta->meta_key] = $post_meta->meta_value;
        }

        $data['post'] = $this->post;
        $data['postmeta'] = $this->postmeta;

        return view('provider.job-postings.view', $data);
    }

    //--------------------------------------------------------------------------

    public function employer($id)
    {
        $data['user_id'] = $user_id = Auth::User()->id;

        $data['user'] = $user = $this->user->where('group', 'owner')
                                           ->whereIn('status', ['approved', 'actived'])
                                           ->findOrFail($id);

        foreach ($user->usermetas as $user_meta) {
            $data['user'][$user_meta->meta_key] = $user_meta->meta_value;
        }

        $data['info'] = $info = $this->user->find($id);

        foreach ($info->usermetas as $user_meta) {
            $data['info'][$user_meta->meta_key] = $user_meta->meta_value;
        }


        $data['appointments'] = $this->post->where('post_author', $id)
								           ->where('post_type', 'booking')
								           ->where('post_status', 'completed')
								           ->count();

        $data['hired'] = count($this->post->where('post_title', $id)
                                    ->where('post_type', 'application')
                                    ->where('post_status', 'hired')
                                    ->groupBy('post_author')
                                    ->get());

        $selects = array('overall');
        $review_count = $this->post
					         ->search([], $selects, [])
					         ->where('post_title', $info->id)
					         ->where('post_type', 'review');

        $all_reviews_count = ($review_count->count()) ? $review_count->count() : 1;
        $data['overall_reviews'] = $review_count->get()->SUM('overall') / $all_reviews_count;
        $data['all_reviews_count'] = $review_count->count();

        return view('provider.job-postings.employer', $data);
    }

    //--------------------------------------------------------------------------

    public function apply($id)
    {
        $user_id = Auth::User()->id;

        $info = $this->post->find($id);

        $parent_id = @$this->post->where('post_author', $user_id)->where('parent', $id)->first()->id;

        $post = ($parent_id) ? $this->post->find($parent_id) : $this->post;

        $post->post_author  = $user_id;
        
        if(Input::get('cover_letter')) {
            $post->post_content = Input::get('cover_letter');
        }

        $post->post_title   = $info->post_author;
        
        $post->post_status  = 'waiting';

        $post->post_type    = 'application';
        $post->parent       = $id;
        $post->created_at   = date('Y-m-d H:i:s');
    
        if( $post->save() ) {

            /* START MESSAGE NOTIFICATION */
            $post_content = Input::get('cover_letter');
            $post_content .= '<br><br><a href="'.URL::route('owner.job-postings.view', $id).'">View job posting here</a> <small>Job ID# '.$id.'</small>';
            $post_data = array(
                'post_name'    => 'job_application_'.$id,
                'post_content' => $post_content,
                'post_id'      => $id,
                'post_content' => $post_content,
                'user_id'      => $user_id,
                'author_id'    => $info->post_author,
                'subject'      => 'You have an employment notification'
            );

            $this->post->send_message_notification($post_data);  
            /* END MESSAGE NOTIFICATION */

            $engage_reward = $this->setting->get_setting('engagement_reward');

            $this->postmeta->update_meta($post->id, 'engage_reward', $engage_reward);
        }

        return Redirect::back()->with('success',"Your application to <b>JOB ID# $id </b> has been sent to the office.");

    }

    //--------------------------------------------------------------------------

}
