<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Mail, Auth, Request, DB;

class Stripe extends Model
{

    public static $stripe_mode = 'live';

    public static $stripe_api_key = [
        'live' => array(
            'publishable_key' => 'pk_live_poNtFfhexPzfsfxPiiHaHHUk', // Dental Account
            'secret_key'      => 'sk_live_hm7gmbIXm6rMLRgU7g4hLt0d',
        ),
        'test' => array(
            'publishable_key' => 'pk_test_sZk3yslDfroWjiE2RzLCr76Y',
            'secret_key'      => 'sk_test_Jgdf2bnX8qIZcpBB0lGI4cZe'
        )
    ];      

    public static function invoice_created($event_json) {

        require_once base_path('vendor/stripe-pay/init.php');
        
        $stripe = Stripe::$stripe_api_key[Stripe::$stripe_mode];

        \Stripe\Stripe::setApiKey($stripe['secret_key']);


        $data = $event_json->data->object;

        $sub = \Stripe\Subscription::retrieve($data->subscription);


        $user = User::select('users.*', 'm1.meta_value as stripe_customer')
                ->from('users')
                ->join('usermeta AS m1', function ($join) use ($data) {
                    $join->on('users.id', '=', 'm1.user_id')
                         ->where('m1.meta_key', '=', 'stripe_customer')
                         ->where('m1.meta_value', $data->customer);
                })->first();

        $user_id = $user->id;                    
        
        $plan_id = $sub->plan->id;

        $plan = packages($plan_id);

        $plan->access_expiry_date = date('Y-m-d H:i:s', strtotime('+30 days'));

        $post = new Post();
        $post->post_author = $user_id;
        $post->post_title  = $plan->name.' subscription for 1 month valid until '.date_formatted($plan->access_expiry_date); 
        $post->post_type   = 'payment';
        $post->post_name   = 'subscription_plan';
        $post->post_status = 'paid';
        $post->created_at  = date('Y-m-d H:i:s'); 

        $post->save();

        $post_id = $post->id;
        $plan->reference_no = 'PS-'.sprintf('%06d', $post_id);
        
        $plan->payment_object = array(
            'payment'      => 'stripe',
            'token'        => $data->id,
            'customer'     => $data->customer,
            'subscription' => $data->subscription,
            'charge'       => $data->charge
        );

        unset($plan->features);
        unset($plan->actions);

        foreach ($plan as $meta_key => $meta_value) {
            PostMeta::update_meta($post_id, $meta_key, array_to_json($meta_value));
        }   

        $usermetas = array(
            'package_id'     => $plan_id,
            'package_amount' => $plan->amount,
            'package_expiry' => $plan->access_expiry_date,
            'package_ended'  => ''
        );     

        foreach ($usermetas as $u_meta_key => $u_meta_value) {
            UserMeta::update_meta($user_id, $u_meta_key, $u_meta_value);
        } 


    }


    public function stripe_update_charge($data = array(), $metadata = array()) {
        
        require_once base_path('vendor/stripe-pay/init.php');

        $plan_id = $metadata['plan'];


        $plan = packages($plan_id);
        
        $stripe = Stripe::$stripe_api_key[Stripe::$stripe_mode];

        \Stripe\Stripe::setApiKey($stripe['secret_key']);
        

        $payment_object = json_decode($data->payment_object);

        try {

            try {
                $plan = packages($plan_id);

                $plan_id = "staffing-dental-".$plan_id;

                \Stripe\Plan::create(array(
                  "name"           => 'Staffing Dental - '.$plan->name,
                  "id"             => $plan_id,
                  "interval"       => "month",
                  "interval_count" => 1,
                  "currency"       => "usd",
                  "amount"         => str_replace('.', '', $plan->amount),
                ));
            } catch(\Exception $e) {

            }


            try {
                /* Cancel Subscription */
                $sub = \Stripe\Subscription::retrieve($payment_object->subscription);
                $sub->cancel();
            } catch(\Exception $e) {

            }

            /* Generate Token */
            $token =  \Stripe\Token::create(array(
                "card" => array(
                "number"    => $metadata['credit_card_number'],
                "exp_month" => $metadata['credit_card_month'],
                "exp_year"  => $metadata['credit_card_year'],
                "cvc"       => $metadata['credit_card_code']
              )
            ));

            /* Create Suibscription */
            $subscription = \Stripe\Subscription::create(array(
              "customer" => $payment_object->customer,
              "plan" => $plan_id
            ));

            $stripe_data = array(
                'payment'      => 'stripe',
                'token'        => $token->id,
                'customer'     => $payment_object->customer,
                'subscription' => $subscription->id
            );

            return $stripe_data; 


        } catch(\Exception $e) {

            return array(
                'payment' => false,
                'msg' => $e->getMessage()
            );
        }
    }

    public function stripe_create_charge($data = array(), $metadata = array()) {

        require_once base_path('vendor/stripe-pay/init.php');

        $plan_id = $data->plan_id;

        $plan = packages($plan_id);

        
        $stripe = Stripe::$stripe_api_key[Stripe::$stripe_mode];

        \Stripe\Stripe::setApiKey($stripe['secret_key']);
    
        try {

            $plan_id = "staffing-dental-".$plan_id;

            try {
                \Stripe\Plan::create(array(
                  "name"           => 'Staffing Dental - '.$plan->name,
                  "id"             => $plan_id,
                  "interval"       => "month",
                  "interval_count" => 1,
                  "currency"       => "usd",
                  "amount"         => str_replace('.', '', $plan->amount),
                ));
            } catch(\Exception $e) {

            }



            /* Generate Token */
            $token =  \Stripe\Token::create(array(
                "card" => array(
                "number"    => $metadata['credit_card_number'],
                "exp_month" => $metadata['credit_card_month'],
                "exp_year"  => $metadata['credit_card_year'],
                "cvc"       => $metadata['credit_card_code']
              )
            ));

            if($token) {

                unset($data->_token);
                unset($data->new_password);
                unset($data->new_password_confirmation);

                /* Create Customer */
                $customer = \Stripe\Customer::create(array(
                    'email' => $data->email,
                    'card'  => $token->id,
                    'metadata' => (array)$data
                ));

                /* Create Suibscription */
                $subscription = \Stripe\Subscription::create(array(
                  "customer" => $customer->id,
                  "plan" => $plan_id
                ));

                $stripe_data = array(
                    'payment'      => 'stripe',
                    'token'        => $token->id,
                    'customer'     => $customer->id,
                    'subscription' => $subscription->id
                );

                return $stripe_data;
            }

        } catch(\Exception $e) {

            return array(
                'payment' => false,
                'msg' => $e->getMessage()
            );
        }


    }

    public function cancel_subscription($subscription_id='', $at_period_end = '') {

        require_once base_path('vendor/stripe-pay/init.php');

        $stripe = Stripe::$stripe_api_key[Stripe::$stripe_mode];
        \Stripe\Stripe::setApiKey($stripe['secret_key']);

        try {
            $subscription = \Stripe\Subscription::retrieve($subscription_id);

            if($at_period_end == 'true') {
                $subscription->cancel(array('at_period_end' => true));
            } else {
                $subscription->cancel();                
            }

            return array(
                'error' => false,
                'type' => 'success',
                'msg' => 'Your subscription has been sucessfully ended.'
            );
      
       } catch(\Exception $e) {
            return array(
                'error' => true,
                'type' => 'error',
                'msg' => $e->getMessage()
            );
        }


    }

    public function stripe_update_card($customer, $data = array()) {


        try {

            require_once base_path('vendor/stripe-pay/init.php');

        
            $stripe = Stripe::$stripe_api_key[Stripe::$stripe_mode];

            \Stripe\Stripe::setApiKey($stripe['secret_key']);

            /* Generate Token */
            $token =  \Stripe\Token::create(array(
                "card" => array(
                "number"    => $data['credit_card_number'],
                "exp_month" => $data['credit_card_month'],
                "exp_year"  => $data['credit_card_year'],
                "cvc"       => $data['credit_card_code']
              )
            ));

            $customer = \Stripe\Customer::retrieve($customer);
            $customer->source = $token->id;
            $customer->save();

        } catch(\Exception $e) {

            return array(
                'payment' => false,
                'msg' => $e->getMessage()
            );
        }

    
    }

}
