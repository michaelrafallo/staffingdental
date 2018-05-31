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


class OwnerDentalProController extends Controller
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

        $data['user_id'] = $user_id = Auth::User()->id;
        
        $data['post'] = $this->post;

        $data['info'] = $info = $this->user->find($user_id);

        foreach ($info->usermetas as $user_meta) {
            $data['info'][$user_meta->meta_key] = $user_meta->meta_value;
        }

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
        $queries = array('provider_type', 'permanent_position', 'temporary_assignments', 'special_type', 'languages', 'availability');

        $search['availability'] = 'online';

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

        $data['rows'] = $this->user->where('id', 0)->simplePaginate(10);
        $data['count'] = $this->user->where('id', 0)->count();

        if( $search['lat'] &&  $search['lng'] ) {
            $data['rows'] = $this->user
                            ->search($search, $selects, $queries)                
                                ->where('lat','<>','')
                                ->where('lng','<>','')                
                            ->where('group', 'provider')
                            ->whereIn('status', ['actived', 'approved'])
                            ->orderBy('id', Input::get('order_by', 'DESC'))
                            ->groupBy('users.id')
                            ->simplePaginate(10);      

            $data['count'] = count($this->user
										->search($search, $selects, $queries)                
										->where('lat','<>','')
										->where('lng','<>','')                
										->where('group', 'provider')
										->whereIn('status', ['actived', 'approved'])
										->get());      
        }


        return view('owner.dentalpro.index', $data);
    }

    //--------------------------------------------------------------------------

    public function favorites($id='')
    {

        parse_str( query_vars(), $search );

        $auth = Auth::User();

        $user_id = $auth->id;

        $action = Input::get('action');

        $p = $this->post->where('post_type', 'favorite')
                        ->where('post_author', $user_id);


        if( $action == 'remove' ) {

            $p->where('post_title', $id)->delete();

            return Redirect::back()->with('success','Selected provider has been removed to favorite');        
        }

        if( $action == 'add' ) {

            $post = $this->post;
            
            $post->post_author  = $user_id;
            $post->post_content = '';
            $post->post_title   = $id;
            $post->post_status  = 'actived';
            $post->post_name    = '';
            $post->post_type    = 'favorite';
            $post->created_at   = date('Y-m-d H:i:s');

            $post->save();

            return Redirect::back()->with('success','Selected provider has been added to favorite');
        }

        $data['rows'] = $p->select('posts.*', 'f1.meta_value as provider_type')
                          ->join('usermeta AS f1', function ($join) use ($search) {
                            $join->on('posts.post_title', '=', 'f1.user_id')
                                 ->where('f1.meta_key', '=', 'provider_type');
                            if( isset($search['provider_type']) ) {
                            if($search['provider_type'] != '')
                                $join->where('f1.meta_value', '=', $search['provider_type']);
                            }
                        })->paginate(12);

        $data['usermeta'] = $this->usermeta;                         

        return view('owner.dentalpro.favorites', $data);
    }

    //--------------------------------------------------------------------------

    public function profile($id='')
    {

        $data['user_id'] = $user_id = Auth::User()->id;

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

        
        return view('owner.dentalpro.profile', $data);
    }
    //--------------------------------------------------------------------------

    function book() {
        
        $user_id = Input::get('uid');
        $data['date'] = $date = Input::get('date');

        $data['post'] = $this->post;


        $data['schedule'] = $this->post->where('post_type', 'exception_available')
                                ->where('post_author', $user_id)
                                ->where('post_content', 'LIKE', '%'.$date.'%')
                                ->first();
        
        $data['day'] = date('N', strtotime($date));

        $data['info'] = $info = $this->user->find($user_id);

        foreach ($info->usermetas as $user_meta) {
            $data['info'][$user_meta->meta_key] = $user_meta->meta_value;
        }

        return view('owner.dentalpro.book', $data);
    }

    //--------------------------------------------------------------------------
    
    function bookNow() {

        $user_id = Auth::User()->id;

        $start_time = Input::get('start_time');
        $end_time   = Input::get('end_time');
        $uid        = Input::get('uid');
        $note       = Input::get('note');
        $breaktime  = (Input::get('breaktime') == 'none') ? 0 : Input::get('breaktime');

        $post = $this->post;

        $post->post_author  = $user_id;
        $post->post_title   = $uid;
        $post->post_content = ($note) ? $note : '';
        $post->post_name    = Input::get('date');
        $post->post_status  = 'pending';
        $post->post_type    = 'booking';

        if( $post->save() ) {
            $fee = $this->usermeta->get_meta($uid, 'minimum_fee');

            $total_minutes = ($end_time - $start_time) - $breaktime;

            $hours = intval($total_minutes / 60);  
            $mins = $total_minutes % 60;   

            $total_time = ($hours) ? $hours.'H ' : '';
            $total_time .= ($mins) ? $mins.'M' : '';

            $fee = ($hours * $fee) + (($mins/60)*$fee);

            $appointment_reward = $this->setting->get_setting('appointment_reward');

            $inputs = array(
                "user_id"       => $uid,
                "start_time"    => $start_time,
                "end_time"      => $end_time,
                "date"          => Input::get('date'),
                "breaktime"     => Input::get('breaktime'),
                "total_minute"  => $total_minutes,
                "total_time"    => $total_time,
                "total_amount"  => number_format($fee, 2),
                'appointment_reward' => $appointment_reward
            );

            foreach ($inputs as $meta_key => $meta_value) {
                $this->postmeta->update_meta($post->id, $meta_key, $meta_value);
            }                  

            /* START MESSAGE NOTIFICATION */
            $post_content = message_notification('book');
            $post_content .= '<br><br><a href="'.URL::route('provider.appointments.index', ['status' => 'pending', 'id' => $post->id]).'">View appointment here</a> <small>Appointment ID# '.$post->id.'</small>';
            $post_data = array(
                'post_name'    => 'book_appointment',
                'post_content' => $post_content,
                'post_id'      => $post->id,
                'user_id'      => $user_id,
                'author_id'    => $uid
            );
            $this->post->send_message_notification($post_data);  
            /* END MESSAGE NOTIFICATION */

        }

    }
    
    //--------------------------------------------------------------------------

    function getTotal() {

        $start = Input::get('start');
        $end   = Input::get('end');
        $uid   = Input::get('uid');
        $breaktime   = (Input::get('breaktime') == 'none') ? 0 : Input::get('breaktime');

        $fee = $this->usermeta->get_meta($uid, 'minimum_fee');

        $total_minutes = ($end - $start) - $breaktime;

        $hours = intval($total_minutes / 60);  // integer division
        $mins = $total_minutes % 60;           // modulo

        $title = ($hours) ? $hours.'H ' : '';
        $title .= ($mins) ? $mins.'M' : '';

        $fee = ($hours * $fee) + (($mins/60)*$fee);

        $data = array(
            'hours' => $title, 
            'amount' => amount_formatted($fee)
        );

        return json_encode($data);
    }

    //--------------------------------------------------------------------------

}
