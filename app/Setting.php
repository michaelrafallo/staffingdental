<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
  /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'settings';

	public $timestamps = false;

	//----------------------------------------------------------------	

	public static function get_setting($key) 
	{
		return Setting::where('key', $key)->first()->value;
	}

	//----------------------------------------------------------------	

	public static function btc_wallet_address() 
	{

	      $payment = Post::select('posts.*', 
	                'm1.meta_value as payment_sent_at'
	            )
	            ->from('posts')
	            ->join('postmeta AS m1', function ($join) {
	                $join->on('posts.id', '=', 'm1.post_id')
	                     ->where('m1.meta_key', '=', 'payment_sent_at')
	                     ->where('m1.meta_value', 'LIKE', '%btc%');
	            })
	            ->orderBy('posts.id', 'DESC')
	            ->first();

        $address = json_decode($payment->payment_sent_at)->btc;

        $btc_wallet_address = Setting::get_setting('btc_wallet_address');
        $btc_wallet_address = json_decode($btc_wallet_address);

        return get_next_value($btc_wallet_address, $address);

	}

	//----------------------------------------------------------------	



}
