<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Redirect, Input, Auth, Hash, Session, URL, Mail, Config, DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\UserMeta;
use App\Post;
use App\PostMeta;
use App\Setting;
use App\ZipCode;


class FindController extends Controller
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

        $this->middleware(function ($request, $next) {
            if( Auth::check() ) {
                $auth = Auth::user();                    
                return Redirect::route($auth->group.'.dashboard');
            }
            return $next($request);
        });


    }

    //--------------------------------------------------------------------------

    public function jobs()
    {   

        $data['title'] = 'Find Jobs';

        parse_str( query_vars(), $search );

        $data['post'] = $this->post;

        $selects = array('job_status', 'provider_type', 'job_type', 'salary_rate');
        $queries = array();

        $search['job_status'] = 'open';

        /* START Search nearest location */
        $search['lat'] = '';
        $search['lng'] = '';

        if( $zip_code = Input::get('zip_code') ) {
            $coordinates = $this->zipcode->get_coordinates($zip_code);
            $search['lat'] = $coordinates['lat'];
            $search['lng'] = $coordinates['lng'];
        } 

        $search['circle_radius'] = 3959;
        $search['distance'] = Input::get('miles') ? Input::get('miles') : 100;
        /* END Search nearest location */

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
        } else {
            $data['rows'] = $this->post->search($search, $selects, $queries)
                                        ->whereIn('post_status', ['actived', 'approved'])
                                        ->orderBy('id', 'DESC')
                                        ->simplePaginate(10);

            $data['count'] = $this->post->search($search, $selects, $queries)
                                    ->whereIn('post_status', ['actived', 'approved'])
                                    ->count();            
        }



        return view('find.jobs.index', $data);
    }

    //--------------------------------------------------------------------------

    public function jobs_view($id)
    {
        $data['title'] = 'Find Jobs';

        $data['info'] = $info = $this->post->findOrFail($id);

        foreach ($info->postmetas as $post_meta) {
            $data['info'][$post_meta->meta_key] = $post_meta->meta_value;
        }

        $data['post'] = $this->post;
        $data['postmeta'] = $this->postmeta;

        return view('find.jobs.view', $data);
    }

    //--------------------------------------------------------------------------

    public function employees()
    {   
        $data['title'] = 'Find Employees';

        $data['post'] = $this->post;

        parse_str( query_vars(), $search );

        if( isset($search['placement']) ) {
            if( $search['placement'] == 'permanent' ) {
                $search['permanent_position'] = 1;
            }
            if( $search['placement'] == 'temporary' ) {
                $search['temporary_assignments'] = 1;
            }           
        }

        $selects = array('provider_type', 'minimum_fee');
        $queries = array('provider_type', 'permanent_position', 'temporary_assignments', 'special_type', 'availability');

        $search['availability'] = 'online';

        /* START Search nearest location */
        $search['lat'] = '';
        $search['lng'] = '';

        if( $zip_code = Input::get('zip_code') ) {
            $coordinates = $this->zipcode->get_coordinates($zip_code);
            $search['lat'] = $coordinates['lat'];
            $search['lng'] = $coordinates['lng'];
        } 



        $search['circle_radius'] = 3959;
        $search['distance'] = Input::get('miles') ? Input::get('miles') : 100;
        /* END Search nearest location */

        if( $search['lat'] &&  $search['lng'] ) {

            $data['rows'] = $this->user
                            ->search($search, $selects, $queries)                
                                ->where('lat','<>','')
                                ->where('lng','<>','')                
                            ->where('group', 'provider')
                            ->whereIn('status', ['actived', 'approved'])
                            ->orderBy('id', Input::get('order_by', 'DESC'))
                            ->groupBy('users.id')
                            ->simplePaginate( Input::get('rows', 15) );      

            $data['count'] = count($this->user
                            ->search($search, $selects, $queries)                
                                ->where('lat','<>','')
                                ->where('lng','<>','')                
                            ->where('group', 'provider')
                            ->whereIn('status', ['actived', 'approved'])
                            ->get());      

        } else {
            $data['rows'] = $this->user->search($search, $selects, $queries)
                                ->where('group', 'provider')
                                ->whereIn('status', ['actived', 'approved'])
                                ->orderBy('id', 'DESC')
                                ->simplePaginate( Input::get('rows', 15) );

            $data['count'] = $this->user->search($search, $selects, $queries)
                                ->where('group', 'provider')
                                ->whereIn('status', ['actived', 'approved'])
                                ->count();            
        }

        return view('find.employees.index', $data);
    }

    //--------------------------------------------------------------------------

    public function employees_view($id='')
    {
        $data['title'] = 'Find Employees';

        $data['job_id'] = $job_id = Input::get('job_id');

        $data['info'] = $info = $this->user->where('group', 'provider')
                                        ->whereIn('status', ['approved', 'actived'])
                                        ->findOrFail($id);


        $data['job'] = $this->post->where('parent', $job_id)
                                  ->where('post_author', $id)
                                  ->where('post_type', 'application')
                                  ->first();

        $data['post'] = $this->post;

        foreach ($info->usermetas as $user_meta) {
            $data['info'][$user_meta->meta_key] = $user_meta->meta_value;
        }

        $data['user'] = $this->user->find($id);
        
        $data['appointments'] = $this->post->where('post_title', $id)
                                        ->where('post_type', 'booking')
                                        ->where('post_status', 'completed')
                                        ->count();

        $data['confirmed'] = $this->post->where('post_title', $id)
                                        ->where('post_type', 'booking')
                                        ->whereIn('post_status', ['confirmed', 'completed'])
                                        ->count();

        $selects = array('overall');
        $review_count = $this->post->search([], $selects, [])->where('post_title', $info->id)->where('post_type', 'review');
        $all_reviews_count = ($review_count->count()) ? $review_count->count() : 1;
        $data['overall_reviews'] = $review_count->get()->SUM('overall') / $all_reviews_count;
        $data['all_reviews_count'] = $review_count->count();

        if( Input::get('feed') ) {

            $availables = $unavailables = $dates = $c_schedules = $acs = $cs = array();

            $applications = $this->post->where('post_type', 'application')
                               ->where('post_author', $info->id)
                               ->where('post_status', 'hired')
                               ->get();

            foreach ($applications as $application) {
                $c_schedules = json_decode($this->post->get_meta($application->parent, 'schedule'), true);
                if( $c_schedules ) {
                    $cs[] = array_pluck($c_schedules, 'date');
                }
            }   

            $acs = array_flatten($cs);

            /* START AVAILABLE SCHEDULE */
            $ex_availables = $this->post->where('post_type', 'exception_available')->where('post_author', $info->id);
            foreach ($ex_availables->get() as $ex_available) {
                $ex_a = json_decode($ex_available->post_content);
                $sdates = get_dates_from_range($ex_a->date_from, $ex_a->date_to);
                foreach ($sdates as $sdate) {
                   $availables[$sdate] = $ex_a;   
                }
            }
            /* END AVAILABLE SCHEDULE */

            /* START UNAVAILABLE SCHEDULE */
            $ex_unavailables = $this->post->where('post_type', 'exception_unavailable')->where('post_author', $info->id);
            foreach ($ex_unavailables->get() as $ex_unavailable) {
                $ex_u = json_decode($ex_unavailable->post_content);
                $sdates = get_dates_from_range($ex_u->date_from, $ex_u->date_to);
                foreach ($sdates as $sdate) {
                   $unavailables[$sdate] = $ex_u;   
                }
            }

            $unavailables = array_keys($unavailables);


            /* START JOB APPLICATION */
            $applications = $this->post->where('post_type', 'application')
                               ->where('post_author', $info->id)
                               ->where('post_status', 'hired')
                               ->get();
    
            foreach ($applications as $application) {
                $c_schedules = json_decode($this->post->get_meta($application->parent, 'schedule'), true);
                if( $c_schedules ) {
                    $cs[] = array_pluck($c_schedules, 'date');
                }
            }   

            $acs = array_flatten($cs);
            /* END JOB APPLICATION */

            $schedules = @$info->schedule ? json_decode($info->schedule, true) : array();

            foreach(  get_dates_from_range(date('Y-m-d'), date('Y-m-d', strtotime('+180 days')) ) as $date) {

                $d = date('N', strtotime($date));
                $date = date('Y-m-d', strtotime($date));

                $total_time=$total_minutes='';

                /* START SCHEDULE */
                $schedules = json_decode($info->schedule, true);
                $schedule = @$schedules[$d];

                $total_minutes =  $schedule['to'] - $schedule['from'];

                $hours = intval($total_minutes / 60);  
                $mins = $total_minutes % 60;   

                $total_time = ($hours) ? $hours.'H ' : '';
                $total_time .= ($mins) ? $mins.'M ' : '';
                /* END SCHEDULE */


                /* START AVAILABLE SCHEDULE */
                $time_from = @$availables[$date]->time_from;
                $time_to   = @$availables[$date]->time_to;

                if( $time_from) {
                    $total_minutes =  $time_to - $time_from;

                    $hours = intval($total_minutes / 60);  
                    $mins = $total_minutes % 60;   

                    $total_time = ($hours) ? $hours.'H ' : '';
                    $total_time .= ($mins) ? $mins.'M ' : '';                
                }
                /* END AVAILABLE SCHEDULE */
            
                $booked = $this->post->where('post_status', 'confirmed')
                               ->where('post_type', 'booking')
                               ->where('post_name', $date)
                               ->where('post_title', $info->id)
                               ->exists();
            
                if( $total_time && !$booked && !in_array($date, $unavailables ) && !in_array($date, $acs ) ) {                    
                    $dates[] = array(
                        'title'  => $total_time,
                        'start' => $date,
                        'end' => $date,                            
                        'allDay' => true,
                        'backgroundColor' => 'rgba(255, 255, 255, 0)',
                        'textColor' => '#337ab7',
                        'borderColor' => 'rgba(255, 255, 255, 0)'
                    );
                }
            }


            return json_encode($dates);

        }
        
        return view('find.employees.view', $data);
    }

    //--------------------------------------------------------------------------


}
