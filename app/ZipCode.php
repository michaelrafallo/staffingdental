<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth, Session;

class ZipCode extends Model
{

	protected $primaryKey = 'id';
	
	public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'zip_codes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [];

	/**
	 * The rules applied when creating a item
	 */
	public static $insertRules = [];		


    public static function get_coordinates($zip_code = '') {

        $sess_coordinates = Session::get('coordinates');

        if(  $sess_coordinates['zip_code'] == $zip_code ) {

            $data = $sess_coordinates;

        } else {

            $q_zip = ZipCode::where('zip_code', $zip_code)->first();

            if( $q_zip ) {

                $data = array(
                    'zip_code' => $zip_code,
                    'lat'      => $q_zip->lat,  
                    'lng'      => $q_zip->lng
                );
            
            } else {
                $coordinates = get_geolocation($zip_code);

                if( $coordinates['lat'] && $coordinates['lng'] ) {

                    $zip = new ZipCode();
                    $zip->zip_code   = $zip_code;
                    $zip->lat        = $coordinates['lat'];
                    $zip->lng        = $coordinates['lng'];
                    $zip->user_id    = 0;
                    $zip->created_at = date('Y-m-d H:i:s');
                    $zip->save();

                    $data = array(
                        'zip_code' => $zip_code,
                        'lat'      => $zip->lat,  
                        'lng'      => $zip->lng
                    );

                } else {
                    $data = array(
                        'zip_code' => $zip_code,
                        'lat'      => 0,  
                        'lng'      => 0
                    );                    
                }

            }

            Session::put('coordinates', $data);
            
        }

        return $data;

    }

}
