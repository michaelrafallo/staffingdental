<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Input, Request, Auth, Mail, URL, DB;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'email',
        'activation_key',
        'status',
        'group',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function userMetas()
    {
        return $this->hasMany('App\UserMeta', 'user_id');
    }

    
    public function scopeSearch($query, $data = array(), $selects = array(), $queries = array()) {

        $q = array();

        $query->where('users.id', '!=', '1');


        /* Select */
        $s=1;
        foreach($selects as $select) {
            $s_data = array('select' => $select, 's' => $s);
            $query->join("usermeta AS m{$s}", function ($join) use ($s_data) {            
                $select = $s_data['select'];
                $s = $s_data['s'];
                $join->on("users.id", '=', "m{$s}.user_id")
                     ->where("m{$s}.meta_key", '=', $select);
            });
            $select_data[] = "m{$s}.meta_value as ".$select;
            $s++;
        }

        /* Search */
        foreach($queries as $q) {
            if( isset($data[$q]) ) {
                if($data[$q] != '') {

    if($q == 'languages') {

        $s_data = array('select' => $q, 'data' => $data, 's' => $s);
        $query->join("usermeta AS m{$s}", function ($join) use ($s_data) {
            $select = $s_data['select'];
            $where = @$s_data['data'][$select];
            $s = $s_data['s'];

            foreach ($where as $w) {
                $join->on("users.id", '=', "m{$s}.user_id")
                     ->where("m{$s}.meta_key", '=', $select)
                     ->where("m{$s}.meta_value", "RLIKE", '"user_languages":"[[:<:]]'.$w.'[[:>:]]"');

            }
        });  


    } else {
        $s_data = array('select' => $q, 's' => $s, 'data' => $data);
        $query->join("usermeta AS m{$s}", function ($join) use ($s_data) {
            $select = $s_data['select'];
            $where = @$s_data['data'][$select];
            $s = $s_data['s'];
            $join->on("users.id", '=', "m{$s}.user_id")
                 ->where("m{$s}.meta_key", '=', $select)
                 ->where("m{$s}.meta_value", '=', $where);
        });    
    }



                
                }
            }
            $s++;
        }

        $select_data[] = 'users.*';

        /* START Get nearby results */
        if(@$data['lat'] && @$data['lng']) {
            $lat = $data['lat'];
            $lng = $data['lng'];

            $circle_radius = $data['circle_radius'];
            $distance = $data['distance'];

            $select='(' . $circle_radius . ' * acos(cos(radians(' . $lat . ')) * cos(radians(lat)) *
            cos(radians(lng) - radians(' . $lng . ')) +
            sin(radians(' . $lat . ')) * sin(radians(lat))))
            AS distance';
            $select_data[] = DB::raw($select);

            $query->having('distance', '<=', $distance);            
        }
        /* END Get nearby results */


        $query->select($select_data)
        ->from('users');

        if( isset($data['s']) ) {
            if($data['s'] != '')
            $query->where('users.fullname', 'LIKE', '%'.$data['s'].'%');
        }


        if( isset($data['status']) ) {
            if($data['status'] != '')
            $query->where('users.status', $data['status']);
        }

        if( isset($data['group']) ) {
            if($data['group'] != '')
            $query->where('users.group', $data['group']);
        }

        if( isset($data['email']) ) {
            if($data['email'] != '')
            $query->where('users.email', 'LIKE', '%'.$data['email'].'%');
        }

        if( isset($data['type']) ) {
            $query->withTrashed()->where('users.deleted_at', '<>', '0000-00-00');
        }


        return $query;
    }

    public static $forgotPassword = [
        'email' => 'required|email|max:64|exists:users,email',
    ];

    public static $newPassword = [
        'new_password'              => 'required|min:4|max:64|confirmed',
        'new_password_confirmation' => 'required|min:4',
    ];

    public static function get_meta($key, $value)
    {
        return UserMeta::get_meta($key, $value);
    }

    function signup_rules($form=1) {

        $rules = [
            'firstname' => 'required|max:64',
            'lastname'  => 'required|max:64',
            'email'     => 'required|email|unique:users,email',
            'password'            => 'required|min:6|max:16|confirmed|regex:/^(?=\S*[0-9])(?=\S*[A-Z])(?=\S*[\d])\S*$/',
            'password_confirmation'    => 'required|min:6|max:16',
            'agree'               => 'required',
            'zip_code'               => 'required',
        ]; 

        if( $form == 4 ) {
            $rules = [
                'minimum_fee'                       => 'required',
                'minimum_hours'                     => 'required',
                'weekly_hours_of_dental_services'   => 'required',
                'travel_distance'                   => 'required',
            ];     

/*            if( ! Input::get('student') ) {
                $rules = $rules  + ['dental_license' => 'required'];
            }
*/       
        }


        return $rules;
    }
    
    public function is_favorite($id = '') {

        $auth = Auth::User();

        $post = Post::where('post_type', 'favorite')
                    ->where('post_author', $auth->id)
                    ->where('post_title', $id)
                    ->first();
        
        if($post) return true;

    }

  
    public static function send_notification($user_id='', $msg ='', $title='') {

        $info = User::find($user_id); 

        $mail['url']  =  URL::route('auth.login', ['redirect_to' => URL::route('messages.index', $info->group)]);
        $mail['site_name'] = $site_name = ucwords(Setting::get_setting('site_title'));

        $mail['email_support'] = Setting::get_setting('admin_email');

        $title = ($title) ? $title : 'You have a notification in your '.$site_name.' account';

        $mail['email_title']   = $title;
        $mail['email_subject'] = $title;

        $mail['msg'] = $msg;

        $mail['email'] = $info->email;
        
        Mail::send('emails.notify', $mail, function($message) use ($mail)
        {
            $message->from($mail['email_support'], $mail['site_name']);
            $message->to($mail['email'], $mail['site_name'])->subject($mail['email_subject']);
        });        
    }

    public static function force_destroy($id='') {

        $user = User::withTrashed()->findOrFail($id);

        /* Delete all related records in usermeta table */
        UserMeta::where('user_id', $id)->delete();

        $posts = Post::where('post_author', $id);

        foreach($posts->get() as $post) {
            /* Delete all related records in postmeta table */
            PostMeta::where('post_id', $post->id)->delete();
        }
        
        /* Delete all related records in posts table*/
        $posts->forceDelete();

        /* Delete all related images */
        $dir = 'uploads/images/users/'.$id;
        if( file_exists($dir) ) { 
            array_map('unlink', glob("$dir/*.*"));
            rmdir($dir);    
        }

        /* Delete records in user table */
        $user->forceDelete();
  
    }


}

