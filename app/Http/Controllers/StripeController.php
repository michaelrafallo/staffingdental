<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, Redirect, Input, Auth, Hash, Session, URL, Mail, DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\UserMeta;
use App\Setting;
use App\Post;
use App\PostMeta;
use App\Stripe;



class StripeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    protected $user;
    protected $usermeta;
    protected $setting;
    protected $post;
    protected $postmeta;
    protected $stripe;


    public function __construct(User $user, UserMeta $usermeta, Setting $setting, Post $post, PostMeta $postmeta, Stripe $stripe)
    {
        $this->user = $user;
        $this->usermeta = $usermeta;
        $this->setting = $setting;
        $this->post = $post;
        $this->postmeta = $postmeta;
        $this->stripe = $stripe;
    }

    //--------------------------------------------------------------------------

    public function charge()
    {
        require_once base_path('vendor/stripe-pay/init.php');
        
        $stripe = Stripe::$stripe_api_key[Stripe::$stripe_mode];

        \Stripe\Stripe::setApiKey($stripe['secret_key']);

        $input = @file_get_contents("php://input");
        $event_json = json_decode($input);

        if( $event_json->type == 'invoice.payment_succeeded' ) {
            $this->stripe->invoice_created($event_json);
        } 

        http_response_code(200); // PHP 5.4 or greater

    }

    //--------------------------------------------------------------------------

    public function logs()
    {

        require_once base_path('vendor/stripe-pay/init.php');
        
        
        $stripe = Stripe::$stripe_api_key[Stripe::$stripe_mode];

        \Stripe\Stripe::setApiKey($stripe['secret_key']);

        $input = @file_get_contents("php://input");
        $event_json = json_decode($input);

        $data['object_id']  = $event_json->id;
        $data['type']       = $event_json->type;
        $data['data']       = $input;
        $data['created_at'] = date('Y-m-d H:i:s');

        Db::table('stripe_logs')->insert($data);

        http_response_code(200);

    }
    
    //--------------------------------------------------------------------------

    public function cancel_subscription()
    {
        $at_period_end = Input::get('at_period_end');

        $user_id = Auth::User()->id;

        $p = $this->post->where('post_name', 'subscription_plan')
                        ->where('post_author', $user_id)
                        ->orderBy('id', 'DESC')
                        ->first();

        $payment_object = $this->postmeta->get_meta($p->id, 'payment_object');

        $data = $this->stripe->cancel_subscription(json_decode($payment_object)->subscription, $at_period_end);

        if($data['error']==false) {

            if( $at_period_end=='true' ) {
                $status = 'ended_at_period_end';
                $date_ended = $p->access_expiry_date;
            } else {
                $status = 'ended_immediately';
                $date_ended = date('Y-m-d H:i:s');
            }

            $p->post_status = $status;
            $p->save();

            $this->usermeta->update_meta($user_id, 'package_ended', $date_ended);
            $this->postmeta->update_meta($p->id, 'package_ended', date('Y-m-d H:i:s'));

        }

        return Redirect::route('owner.billings.index')->with($data['type'], $data['msg']);
    }
    //--------------------------------------------------------------------------
 

}
