<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mail, Auth, Request, URL, DB;

class Post extends Model
{
    use SoftDeletes;

	protected $primaryKey = 'id';
	
	public $timestamps = true;

    protected $dates = ['deleted_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [
		'post_author',
		'post_date',
		'post_content',
		'post_title',
		'post_status',
		'post_name',
		'post_modified',
		'post_type',
	];

	/**
	 * The rules applied when creating a item
	 */
	public static $insertRules = [
		'post_content' => 'required',
	];		


    public function scopeSearch($query, $data = array(), $queries = array()) {

        $q = array();

        if( $queries ) {
            /* Select */
            $s=1;
            foreach($queries as $select) {
                $s_data = array('select' => $select, 's' => $s);
                $query->join("postmeta AS m{$s}", function ($join) use ($s_data) {            
                    $select = $s_data['select'];
                    $s = $s_data['s'];
                    $join->on("posts.id", '=', "m{$s}.post_id")
                         ->where("m{$s}.meta_key", '=', $select);
                });
                $select_data[] = "m{$s}.meta_value as ".$select;
                $s++;
            }

            /* Search */
            foreach($queries as $q) {
                if( isset($data[$q]) ) {
                    if($data[$q] != '') {
                        $s_data = array('select' => $q, 's' => $s, 'data' => $data);
                        $query->join("postmeta AS m{$s}", function ($join) use ($s_data) {
                            $select = $s_data['select'];
                            $where = @$s_data['data'][$select];
                            $s = $s_data['s'];
                            $join->on("posts.id", '=', "m{$s}.post_id")
                                 ->where("m{$s}.meta_key", '=', $select)
                                 ->where("m{$s}.meta_value", '=', $where);
                        });                    
                    }
                }
                $s++;
            }

            $select_data[] = 'posts.*';



            /* START Get nearby results */
            if(@$data['lat'] && @$data['lng']) {

                $select_data[] = 'users.lat AS lat';
                $select_data[] = 'users.lng As lng';

                $query->join('users', function ($join)  use ($data) {
                    $join->on('posts.post_author', '=', 'users.id');
                });


                $lat = $data['lat'];
                $lng = $data['lng'];

                $circle_radius = $data['circle_radius'];
                $distance = $data['distance'];

                $select='(' . $circle_radius . ' * acos(cos(radians(' . $lat . ')) * cos(radians(dentist_users.lat)) *
                cos(radians(dentist_users.lng) - radians(' . $lng . ')) +
                sin(radians(' . $lat . ')) * sin(radians(dentist_users.lat))))
                AS distance';
                $select_data[] = DB::raw($select);

                $query->having('distance', '<=', $distance);            
            }
            /* END Get nearby results */


            $query->select($select_data)
            ->from('posts');




        }



        if( isset($data['s']) ) {
            if($data['s'] != '')
            $query->where('posts.post_title', 'LIKE', '%'.$data['s'].'%');
        }

        if( isset($data['status']) ) {
            if($data['status'] != '')
            $query->where('posts.post_status', $data['status']);
        }

        if( isset($data['post_type']) ) {
            if($data['post_type'] != '')
            $query->where('posts.post_type', $data['post_type']);
        }

        if( isset($data['type']) ) {
            if($data['type'] == 'trash')
            $query->withTrashed()->where('posts.deleted_at', '<>', '0000-00-00');
        }

        return $query;
    }

    public function scopeSearchMsg($query, $data = array()) {

        $q = array();

        $auth = Auth::User();

        if( isset($data['status']) ) {
            if($data['status'] == 'unread') {
   
                $query->select('posts.*', 
                    'm1.meta_value as read'
                )
                ->from('posts')
                ->join('postmeta AS m1', function ($join)  use ($auth) {
                    $join->on('posts.id', '=', 'm1.post_id')
                         ->where('m1.meta_key', '=', 'read')
                         ->where('m1.meta_value', 'NOT LIKE', '%'.json_encode($auth->id).'%');
                })->where('posts.post_title', $auth->id)
                  ->orWhere('posts.post_author', $auth->id);   


            } elseif($data['status'] == 'archived') {

                $query->select('posts.*', 
                    'm1.meta_value as archived'
                )
                ->from('posts')
                ->join('postmeta AS m1', function ($join)  use ($auth) {
                    $join->on('posts.id', '=', 'm1.post_id')
                         ->where('m1.meta_key', '=', 'archived')
                         ->where('m1.meta_value', 'LIKE', '%'.json_encode($auth->id).'%');
                });   

            } elseif($data['status'] == 'important') {

                $query->select('posts.*', 
                    'm1.meta_value as important'
                )
                ->from('posts')
                ->join('postmeta AS m1', function ($join)  use ($auth) {
                    $join->on('posts.id', '=', 'm1.post_id')
                         ->where('m1.meta_key', '=', 'important')
                         ->where('m1.meta_value', 'LIKE', '%'.json_encode($auth->id).'%');
                });  
            } 
        } else {

            $query->select('posts.*', 
                'm1.meta_value as archived'
            )
            ->from('posts')
            ->join('postmeta AS m1', function ($join)  use ($auth) {
                $join->on('posts.id', '=', 'm1.post_id')
                     ->where('m1.meta_key', '=', 'archived')
                     ->where('m1.meta_value', 'NOT LIKE', '%'.json_encode($auth->id).'%');
            });  

            $query->where('post_content', 'LIKE', '%'.json_encode($auth->id).'%');
//            $query->where('post_type', 'inbox');


        }


        return $query;
    }


    public function user() {
        return $this->belongsTo('App\User', 'post_author', 'id');
    }

    public function get_meta($id, $key)
    {
        return PostMeta::get_meta($id, $key);
    }    

    public function get_usermeta($id, $key)
    {
        return UserMeta::get_meta($id, $key);
    }   

    public function PostMetas()
    {
        return $this->hasMany('App\PostMeta', 'post_id');
    }

    public function message_status($post_id, $user_id, $action, $method = 'remove') {

        if( $method == 'remove' ) {
            $status = (array)json_decode(PostMeta::get_meta($post_id, $action));                
            unset($status[array_search($user_id, $status)]);
            PostMeta::update_meta($post_id, $action, json_encode(array_values($status)));
        } else {
            $status = (array)json_decode(PostMeta::get_meta($post_id, $action));                    
            $status[] = (string)$user_id; 
            $status = array_unique($status);
            PostMeta::update_meta($post_id, $action, json_encode($status));  

        }

    }

    public function send_message_notification($data=array())
    {

        // $post_id      = $data['post_id'];
        $post_content = $data['post_content'];
        $user_id      = $data['user_id'];
        $author_id    = $data['author_id'];
        $post_name    = $data['post_name'];
        $subject      = @$data['subject'];


        $msg = Post::where('post_author', $user_id)
            ->where('post_title', $author_id)
            ->where('post_type', 'inbox')
            ->orWhere('post_author', $author_id)
            ->where('post_title', $user_id)
            ->where('post_type', 'inbox')
            ->first();

        if( $msg ) {

            $msg->updated_at = date('Y-m-d H:i:s');
            $msg->save(); 

            $post_id = $msg->id;

            $post = new Post();

            $post->post_author  = $user_id;
            $post->post_content = $post_content;;
            $post->post_title   = $author_id;
            $post->post_status  = 'unread';
            $post->post_name    = $post_name;
            $post->post_type    = 'message';
            $post->parent       = $post_id;
            $post->created_at   = date('Y-m-d H:i:s');

            $post->save();

        } else {

            $to[] = $user_id;
            $to[] = $author_id;

            $main = new Post();
            
            $main->post_author  = $user_id;
            $main->post_content = json_encode($to);
            $main->post_title   = $author_id;
            $main->post_status  = 'actived';
            $main->post_name    = 'single_message';
            $main->post_type    = 'inbox';

            $main->save();

            $post = new Post();

            $post->post_author  = $user_id;
            $post->post_content = $post_content;
            $post->post_title   = $author_id;
            $post->post_status  = 'unread';
            $post->post_name    = $post_name;
            $post->post_type    = 'message';
            $post->parent       = $post_id = $main->id;
            $post->created_at   = date('Y-m-d H:i:s');

            $post->save();

        }

        $read_id = json_encode(array((string)$user_id));

        
        PostMeta::update_meta($post_id, 'hide', $read_id);
        PostMeta::update_meta($post_id, 'read', $read_id);
        PostMeta::update_meta($post_id, 'archived', '');

        if(UserMeta::get_meta($author_id, 'email_notification') == 'on') {

            $user = User::find($user_id);

            $message = decode_message($user->group, $user->firstname, $post_content);

            User::send_notification($author_id, $message, $subject);
            
        }

    }

    public function get_plan($pid='')
    {
        $rows = Post::where('post_type', 'plan')->get();

        foreach($rows as $row) {
            $post = json_decode($row->post_content);
            $t=1;
            foreach($post->total as $total) {
                $plan_id = $row->id.'-'.$t;
                $total->title = $row->post_title;
                $total->plan_id = $plan_id;
                $total->month = $total->number_of_day / 30;

                $plan[$plan_id] = $total;
                $t++;
            }            
        }

        return @$plan[$pid];
    }

    public static function send_notification($from, $to, $subject, $template) {

        $mail['from']          = $from;
        $mail['to']            = $to;
        $mail['email_title']   = $subject;
        $mail['email_subject'] = $subject;
        $mail['logo']          = asset(Setting::get_setting('logo'));
        $mail['url']           = URL::route('auth.login');
        $mail['copy_right']    = Setting::get_setting('copy_right');
        $mail['site_title']    = ucwords(Setting::get_setting('site_title'));

        Mail::send('emails.'.$template, $mail, function($message) use ($mail)
        {
            $message->from($mail['from'], $mail['site_title']);
            $message->to($mail['to'], $mail['site_title'])->subject($mail['email_subject']);
        });        
    }

}
