<?php
function is_plural($string) {
	return ($string > 1) ? 's' : '';
}

function ordinal($num) {
	if (!in_array(($num % 100),array(11,12,13))){
	  switch ($num % 10) {
	    // Handle 1st, 2nd, 3rd
	    case 1:  return $num.'st';
	    case 2:  return $num.'nd';
	    case 3:  return $num.'rd';
	  }
	}
	return $num.'th';
}

function breaktime($val ='') {

	$data = array(
		"none" => "None",
		"12" => "15 min",
		"30" => "30 min",
		"45" => "45 min",
		"60" => "1 hour",
		"75" => "1 hour 15 min",
		"90" => "1 hour 30 min",
		"105" => "1 hour 25 min",
		"120" => "2 hours",
	);

	return ($val) ? @$data[$val] : $data;
} 

function distance($lat1, $lon1, $lat2, $lon2, $unit='') {

	$url ="https://maps.googleapis.com/maps/api/distancematrix/json?mode=driving&units=imperial&origins=".$lat1.",".$lon1."&destinations=".$lat2.",".$lon2;

    $ch = curl_init();
    // Disable SSL verification

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // Will return the response, if false it print the response
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Set the url
    curl_setopt($ch, CURLOPT_URL,$url);
    // Execute
    $result=curl_exec($ch);
    // Closing
    curl_close($ch);

    $result_array=json_decode($result);
	
	return $result_array->rows[0]->elements[0]->distance->text;

/*  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
      return ($miles * 1.609344);
  } else if ($unit == "N") {
      return ($miles * 0.8684);
  } else {
      return number_format($miles, 1);
  }*/
}

function getMonths($val ='') {
 
	$data = array(
		"01" => "January",
		"02" => "February",
		"03" => "March",
		"04" => "April",
		"05" => "May",
		"07" => "July",
		"08" => "August",
		"09" => "September",
		"10" => "October",
		"11" => "November",
		"12" => "December"
	);

	return ($val) ? @$data[$val] : $data;
} 

function get_cc_years($val ='') {
	
	foreach (range(date('Y')-1, date('Y')+7) as $year) {
		$data[$year] = $year; 
	 } 

	return ($val) ? @$data[$val] : $data;
} 

function user_group($val ='') {
 
	$data = array(
		"admin" => "Admin",
		"owner" => "Owner (Employer)",
		"provider" => "Provider (Dentist)",
	);

	return ($val) ? @$data[$val] : $data;
} 


function code_to_text($val ='') {
	return ucwords(str_replace('_', ' ', $val));	
}


function array_val_formatted($key, $val) {
	
	$data = '';

	$file = [
		"profile_picture",
		"proof_of_insurance",
		"government_issued_id"
	];

	if( in_array($key, $file) ) {
		if($val) {
			$data = '<a data-href="'.asset($val).'" class="btn-view" data-toggle="modal" data-target=".view-modal">View Image</a>';
		}
	} elseif($key=='credit_card_number') {
      $data = str_mask($val, 0, 12);  
  } elseif($key=='credit_card_code') {
      $data = str_mask($val, 0, 2);  
  } elseif( $key == 'payment_terms' ) {
		if($val) $data = payment_terms($val);
	} elseif( $key == 'special_type' ) {
		if($val) $data = special_type($val);
	} elseif( $key == 'state' ) {
		if($val) $data = states($val);
	} elseif( $key == 'provider_type' ) {
		if($val) $data = provider_type($val);
	} elseif( $key == 'practice_type' ) {
		$data .= '<ul class="no-margin" style="padding-left: 20px;">'; 
		foreach(json_decode($val) as $practice_types) {
			$data .= '<li>'.practice_types($practice_types).'</li>';
		}
		$data .= '</ul>'; 
	} elseif( $key == 'skills' ) {
		$data .= '<ul class="no-margin" style="padding-left: 20px;">'; 
		foreach(json_decode($val) as $skill) {
			$data .= '<li>'.skills($skill).'</li>';
		}
		$data .= '</ul>'; 
  } elseif( $key == 'user_status' ) {
    $data .= status_ico($val);
  } elseif( $key == 'availability' ) {
    $data .= profile_status($val, true);
	} elseif( $key == 'languages' ) {
		$data .= '<ul class="no-margin" style="padding-left: 20px;">'; 
		foreach(json_decode($val) as $lang) {
			if($lang->user_languages) {				
				$data .= '<li>'.user_languages($lang->user_languages).' ('.$lang->fluency.')</li>';
			}
		}
		$data .= '</ul>'; 
	}  
	else {
		$data = $val;
	}

	return $data;
}

function editFile($file, $key, $value, $seperator ='=') {
	$fh = fopen($file,'r+');
	// string to put username and passwords
	$i=1;
	$contents = '';
	while(!feof($fh)) {
		$user = explode($seperator,fgets($fh));

		// take-off old "\r\n"
		$file_key = @trim($user[0]);
		$file_val = @trim($user[1]);
		// check for empty indexes

		if($i == 1) {
			$contents .= "TIMEZONE" . '=' . "$value\n";	    	
		}

		if ($file_key == $key) {
		    $file_val = "";	
		}

		if (!empty($file_key) AND !empty($file_val)) {
		    $contents .= $file_key . $seperator . $file_val;
		    $contents .= "\n";
		}
		if($file_key == '') {
		    $contents .= "\n";
		}
		$i++;
	 }

	// using file_put_contents() instead of fwrite()
	file_put_contents($file, $contents);
	fclose($fh);	
}

function selected($val, $post) {
	return ($val == $post) ? 'selected="selected"' : '';	
}
function actived($val, $post) {
	return ($val == $post) ? 'active' : '';	
}

function checked($val, $post) {
	return ($val == $post) ? 'checked="checked"' : '';	
}

function message_position($user_id, $id) {
	return ($user_id == $id) ? 'out' : 'in';	
}



function status($val ='') {
	return ($val == 1) ? 'Active' : 'Inactive';	
}

function genders($val ='') {

	$data['M'] = 'Male';
	$data['F'] = 'Female';

	return ($val) ? @$data[$val] : $data;
}

//----------------------------------------------------------------

function sort_by($val ='') {

	$s1 = Request::segment(1);
	$s2 = Request::segment(2);

	if($s1 == 'owner' && $s2 == 'professionals' || $s2 == 'employees' ) {
		$data = array(
			'distance' => 'Distance', 
			'hourly' => 'Hourly Rate',
			'acceptance' => 'Acceptance',
			'last_login' => 'Last Login'
		);		
	}

	if($s1 == 'provider' && $s2 == 'job-postings' || $s2 == 'jobs') {
		$data = array(
			'created_at' => 'Date Posted',
			'salary_rate' => 'Salary Rate', 
      'distance' => 'Distance',
		);
	}

	return ($val) ? @$data[$val] : $data;
}

//----------------------------------------------------------------

function order_by($val ='') {

	$data = array(
		'DESC' => 'Descending', 
		'ASC' => 'Ascending',
	);

	return ($val) ? @$data[$val] : $data;
}

//----------------------------------------------------------------

function time_zone($val='') {

	foreach(timezone_abbreviations_list() as $abbr => $timezone){
	    foreach($timezone as $val){
	        if(isset($val['timezone_id'])){
	              $data[$val['timezone_id']] = $val['timezone_id'];
	        }
	    }
	}

	return $data;
}





function sort_array_multidim(array $array, $order_by)
{
    //TODO -c flexibility -o tufanbarisyildirim : this error can be deleted if you want to sort as sql like "NULL LAST/FIRST" behavior.
    if(!is_array($array[0]))
        throw new Exception('$array must be a multidimensional array!',E_USER_ERROR);
    $columns = explode(',',$order_by);
    foreach ($columns as $col_dir)
    {
        if(preg_match('/(.*)([\s]+)(ASC|DESC)/is',$col_dir,$matches))
        {
            if(!array_key_exists(trim($matches[1]),$array[0]))
                trigger_error('Unknown Column <b>' . trim($matches[1]) . '</b>',E_USER_NOTICE);
            else
            {
                if(isset($sorts[trim($matches[1])]))
                    trigger_error('Redundand specified column name : <b>' . trim($matches[1] . '</b>'));
                $sorts[trim($matches[1])] = 'SORT_'.strtoupper(trim($matches[3]));
            }
        }
        else
        {
            throw new Exception("Incorrect syntax near : '{$col_dir}'",E_USER_ERROR);
        }
    }
    //TODO -c optimization -o tufanbarisyildirim : use array_* functions.
    $colarr = array();
    foreach ($sorts as $col => $order)
    {
        $colarr[$col] = array();
        foreach ($array as $k => $row)
        {
            $colarr[$col]['_'.$k] = strtolower($row[$col]);
        }
    }
   
    $multi_params = array();
    foreach ($sorts as $col => $order)
    {
        $multi_params[] = '$colarr[\'' . $col .'\']';
        $multi_params[] = $order;
    }
    $rum_params = implode(',',$multi_params);
    eval("array_multisort({$rum_params});");
    $sorted_array = array();
    foreach ($colarr as $col => $arr)
    {
        foreach ($arr as $k => $v)
        {
            $k = substr($k,1);
            if (!isset($sorted_array[$k]))
                $sorted_array[$k] = $array[$k];
            $sorted_array[$k][$col] = $array[$k][$col];
        }
    }
    return array_values($sorted_array);
}


function has_photo($path ='') {
	if($path) {
		if( file_exists($path) ) {
			return asset($path);		
		}
	}
	// Default
	return asset('img/avatar.png');
}

function has_image($path ='') {
  if($path) {
    if( file_exists($path) ) {
      return asset($path);    
    }
  }
  // Default
  return asset('img/no-image.jpg');
}

function date_formatted($date ='') {	
	if( $date == '' || $date == '0000-00-00' || $date == '1970-01-01') return '';
	if (preg_match("/\d{4}\-\d{2}-\d{2}/", $date)) {
	    return date('d-M-Y', strtotime($date));
	} else {
	    return date('Y-m-d', strtotime($date));
	}
}



function name_formatted($user_id ='', $format = 'f l') {	

	$d = @App\User::find($user_id);
	
	$auth = Auth::User();
	
	$package_amount = @App\Usermeta::get_meta($auth->id, 'package_amount');

	$split_format = str_split($format);
	$name ='';
	foreach ($split_format as $char) {
		
		if (preg_match('/[a-zA-Z]/', $char)) {
			$n = ($char == 'l') ? 'lastname' : 'firstname';
			$name .= @$d->$n;
		} else {
			$name .= $char;
		}
	}
	
	$data = '';

/*	if(@$package_amount == 0 && @$d->group == 'provider' && $auth->group == 'owner') {
		$name = str_mask( $name, 3, 20, 'x');
		$data .= '<span class="blurry">'.ucwords($name);
		$data .= '</span> <a href="#owner-trial-period" class="btn btn-primary btn-xs uppercase send-msg" data-toggle="modal">Show</a>';		
	} else {
	}
*/
  
  $data .= ucwords($name);    

	return $data;
}


function amount_formatted($amount) {
	return currency_symbol('USD').' '.number_format($amount, 2);
}

function currency_symbol( $currency = '' ) {
  if ( ! $currency ) {
    $currency = get_woocommerce_currency();
  }

  switch ( $currency ) {
    case 'AED' :
      $currency_symbol = 'د.إ';
      break;
    case 'AUD' :
    case 'ARS' :
    case 'CAD' :
    case 'CLP' :
    case 'COP' :
    case 'HKD' :
    case 'MXN' :
    case 'NZD' :
    case 'SGD' :
    case 'USD' :
      $currency_symbol = '$';
      break;
    case 'BDT':
      $currency_symbol = '৳&nbsp;';
      break;
    case 'BGN' :
      $currency_symbol = 'лв.';
      break;
    case 'BRL' :
      $currency_symbol = 'R$';
      break;
    case 'CHF' :
      $currency_symbol = 'CHF';
      break;
    case 'CNY' :
    case 'JPY' :
    case 'RMB' :
      $currency_symbol = '&yen;';
      break;
    case 'CZK' :
      $currency_symbol = 'Kč';
      break;
    case 'DKK' :
      $currency_symbol = 'DKK';
      break;
    case 'DOP' :
      $currency_symbol = 'RD$';
      break;
    case 'EGP' :
      $currency_symbol = 'EGP';
      break;
    case 'EUR' :
      $currency_symbol = '&euro;';
      break;
    case 'GBP' :
      $currency_symbol = '&pound;';
      break;
    case 'HRK' :
      $currency_symbol = 'Kn';
      break;
    case 'HUF' :
      $currency_symbol = 'Ft';
      break;
    case 'IDR' :
      $currency_symbol = 'Rp';
      break;
    case 'ILS' :
      $currency_symbol = '₪';
      break;
    case 'INR' :
      $currency_symbol = 'Rs.';
      break;
    case 'ISK' :
      $currency_symbol = 'Kr.';
      break;
    case 'KIP' :
      $currency_symbol = '₭';
      break;
    case 'KRW' :
      $currency_symbol = '₩';
      break;
    case 'MYR' :
      $currency_symbol = 'RM';
      break;
    case 'NGN' :
      $currency_symbol = '₦';
      break;
    case 'NOK' :
      $currency_symbol = 'kr';
      break;
    case 'NPR' :
      $currency_symbol = 'Rs.';
      break;
    case 'PHP' :
      $currency_symbol = '₱';
      break;
    case 'PLN' :
      $currency_symbol = 'zł';
      break;
    case 'PYG' :
      $currency_symbol = '₲';
  break;
    case 'RON' :
      $currency_symbol = 'lei';
      break;
    case 'RUB' :
      $currency_symbol = 'руб.';
      break;
    case 'SEK' :
      $currency_symbol = 'kr';
      break;
    case 'THB' :
      $currency_symbol = '฿';
      break;
    case 'TRY' :
      $currency_symbol = '₺';
      break;
    case 'TWD' :
      $currency_symbol = 'NT$';
      break;
    case 'UAH' :
      $currency_symbol = '₴';
      break;
    case 'VND' :
      $currency_symbol = '₫';
      break;
    case 'ZAR' :
      $currency_symbol = 'R';
      break;
    default :
      $currency_symbol = '';
      break;
  }

  return $currency_symbol;
}


//----------------------------------------------------------------

function time_ago($time_ago) {

    if( ! $time_ago ) return;

    $time_ago = strtotime($time_ago);
    $cur_time   = time();
    $time_elapsed   = $cur_time - $time_ago;
    $seconds    = $time_elapsed ;
    $minutes    = round($time_elapsed / 60 );
    $hours      = round($time_elapsed / 3600);
    $days       = round($time_elapsed / 86400 );
    $weeks      = round($time_elapsed / 604800);
    $months     = round($time_elapsed / 2600640 );
    $years      = round($time_elapsed / 31207680 );
    // Seconds
    if($seconds <= 60){
        return "just now";
    }
    //Minutes
    else if($minutes <=60){
        if($minutes==1){
            return "1 minute ago";
        }
        else{
            return "$minutes minutes ago";
        }
    }
    //Hours
    else if($hours <=24){
        if($hours==1){
            return "an hour ago";
        }else{
            return "$hours hrs ago";
        }
    }
    //Days
    else if($days <= 7){
        if($days==1){
            return "yesterday";
        }else{
            return "$days days ago";
        }
    }
    //Weeks
    else if($weeks <= 4.3){
        if($weeks==1){
            return "a week ago";
        }else{
            return "$weeks weeks ago";
        }
    }
    //Months
    else if($months <=12){
        if($months==1){
            return "a month ago";
        }else{
            return "$months months ago";
        }
    }
    //Years
    else{
        if($years==1){
            return "one year ago";
        }else{
            return "$years years ago";
        }
    }
}

//----------------------------------------------------------------

function sidebar_menu($type = '') {
    
    $auth = Auth::User();

    $user_id = $auth->id;
    $group = $auth->group;

    $menu = array();

    $msg_count = App\Post::searchMsg(['status' => 'unread'])->count();

    $msg_status = ($msg_count==0) ? [$group] : [$group, 'status' => 'unread'];

    $msg_count = ($msg_count) ? '<span class="badge badge-danger pull-right">'.$msg_count.'</span>' : '';

    if($group == 'owner') {
		$menu = array(
			array(
				'title' => 'Find Dental Professionals',
				'icon' => 'magnifier',
				'class' => '',
				'url' 	=> URL::route('owner.dentalpro.index'),
				'sub_menu' => array()
			),
			array(
				'title' => 'My Appointments',
				'icon' => 'clock',
				'class' => '',
				'url' 	=> URL::route('owner.appointments.index'),
				'sub_menu' => array()
			),
			array(
				'title' => 'My Job Postings',
				'icon' => 'briefcase',
				'class' => '',
				'url' 	=> '',
        'sub_menu' => array(
            array(
              'title' => 'All Jobs',
              'icon' => '',
              'class' => '',
              'url'   => URL::route('owner.job-postings.index'),
              'sub_menu' => array()
            ),
            array(
              'title' => 'Post New Job',
              'icon' => '',
              'class' => '',
              'url'   => URL::route('owner.job-postings.add'),
              'sub_menu' => array()
            ),
          )
			),
			array(
				'title' => 'My Messages '.$msg_count,
				'icon' => 'bubble',
				'class' => '',
				'url' 	=> URL::route('messages.index', $msg_status),
				'sub_menu' => array()
			)
		);
	}

    if($group == 'provider') {	
		$menu = array(
      array(
        'title' => 'My Schedule',
        'icon' => 'calendar',
        'class' => '',
        'url'   => URL::route('provider.accounts.schedule'),
        'sub_menu' => array()
      ),
			array(
				'title' => 'My Appointments',
				'icon' => 'clock',
				'class' => '',
				'url' 	=> URL::route('provider.appointments.index'),
				'sub_menu' => array()
			),
			array(
				'title' => 'Find Work',
				'icon' => 'magnifier',
				'class' => '',
				'url' 	=> URL::route('provider.job-postings.index'),
				'sub_menu' => array()
			),
			array(
				'title' => 'My Jobs',
				'icon' => 'layers',
				'class' => '',
				'url' 	=> URL::route('provider.job-postings.my-jobs'),
				'sub_menu' => array()
			),
			array(
				'title' => 'My Messages '.$msg_count,
				'icon' => 'bubble',
				'class' => '',
				'url' 	=> URL::route('messages.index', $msg_status),
				'sub_menu' => array()
			)
		);	
	}

    if($group == 'admin') {	
		$menu = array(
			array(
				'title' => 'Users',
				'icon' => 'users',
				'class' => '',
				'url' 	=> '',
				'sub_menu' => array(
						array(
							'title' => 'All Users',
							'icon' => '',
							'class' => '',
							'url' 	=> URL::route('admin.users.index'),
							'sub_menu' => array()
						),
						array(
							'title' => 'Create Admin',
							'icon' => '',
							'class' => '',
							'url' 	=> URL::route('admin.users.add'),
							'sub_menu' => array()
						),
						array(
							'title' => 'Actived Users',
							'icon' => '',
							'class' => '',
							'url' 	=> URL::route('admin.users.index', ['status' => 'actived']),
							'sub_menu' => array()
						),
						array(
							'title' => 'Inactived Users',
							'icon' => '',
							'class' => '',
							'url' 	=> URL::route('admin.users.index', ['status' => 'inactived']),
							'sub_menu' => array()
						),
						array(
							'title' => 'Suspended Users',
							'icon' => '',
							'class' => '',
							'url' 	=> URL::route('admin.users.index', ['status' => 'suspended']),
							'sub_menu' => array()
						)
					),
			),
			array(
				'title' => 'Plans',
				'icon' => 'handbag',
				'class' => '',
				'url' 	=> '',
				'sub_menu' => array(
						array(
							'title' => 'All Plans',
							'icon' => '',
							'class' => '',
							'url' 	=> URL::route('admin.plans.index'),
							'sub_menu' => array()
						),
						array(
							'title' => 'Add Plan',
							'icon' => '',
							'class' => '',
							'url' 	=> URL::route('admin.plans.add'),
							'sub_menu' => array()
						)
				)
			),
			array(
				'title' => 'Job Postings',
				'icon' => 'briefcase',
				'class' => '',
				'url' 	=> '',
				'sub_menu' => array(
						array(
							'title' => 'All Jobs',
							'icon' => '',
							'class' => '',
							'url' 	=> URL::route('admin.job-postings.index'),
							'sub_menu' => array()
						),
						array(
							'title' => 'Pending Jobs',
							'icon' => '',
							'class' => '',
							'url' 	=> URL::route('admin.job-postings.index', ['status' => 'pending']),
							'sub_menu' => array()
						),
						array(
							'title' => 'Approved Jobs',
							'icon' => '',
							'class' => '',
							'url' 	=> URL::route('admin.job-postings.index', ['status' => 'approved']),
							'sub_menu' => array()
						),
						array(
							'title' => 'Cancelled Jobs',
							'icon' => '',
							'class' => '',
							'url' 	=> URL::route('admin.job-postings.index', ['status' => 'cancelled']),
							'sub_menu' => array()
						)
				)
			),
      array(
        'title' => 'Offices',
        'icon' => 'grid',
        'class' => '',
        'url'   => '',
        'sub_menu' => array(
            array(
              'title' => 'All Offices',
              'icon' => '',
              'class' => '',
              'url'   => URL::route('admin.offices.index'),
              'sub_menu' => array()
            ),
            array(
              'title' => 'Pending Offices',
              'icon' => '',
              'class' => '',
              'url'   => URL::route('admin.offices.index', ['status' => 'pending']),
              'sub_menu' => array()
            ),
            array(
              'title' => 'Approved Offices',
              'icon' => '',
              'class' => '',
              'url'   => URL::route('admin.offices.index', ['status' => 'approved']),
              'sub_menu' => array()
            ),
            array(
              'title' => 'Cancelled Offices',
              'icon' => '',
              'class' => '',
              'url'   => URL::route('admin.offices.index', ['status' => 'cancelled']),
              'sub_menu' => array()
            )
        )
      ),
			array(
				'title' => 'Payments',
				'icon' => 'credit-card',
				'class' => '',
				'url' 	=> URL::route('admin.payments.index'),
				'sub_menu' => array()
			),
			array(
				'title' => 'Settings',
				'icon' => 'settings',
				'class' => '',
				'url' 	=> URL::route('admin.settings.index'),
				'sub_menu' => array()
			),
		);	
	}

	return $menu;
}

function top_nav_menu() {
    
    $group = Auth::User()->group;
    
    $menu = array();

    if($group == 'owner') {
		$menu = array(
			array(
				'title' => 'Profile',
				'icon' => 'user',
				'class' => '',
				'url' 	=> URL::route('owner.accounts.profile'),
				'sub_menu' => array()
			),
			array(
				'title' => 'My Office',
				'icon' => 'home',
				'class' => '',
				'url' 	=> URL::route('owner.accounts.settings', ['tab' => 3]),
				'sub_menu' => array()
			),
      array(
        'title' => 'My Billing',
        'icon' => 'credit-card',
        'class' => '',
        'url'   => URL::route('owner.billings.index'),
        'sub_menu' => array()
      ),
			array(
				'title' => 'Favorites',
				'icon' => 'pin',
				'class' => '',
				'url' 	=> URL::route('owner.dentalpro.favorites'),
				'sub_menu' => array()
			),
			array(
				'title' => 'Account Settings',
				'icon' => 'user',
				'class' => '',
				'url' 	=> URL::route('owner.accounts.settings', 'tab=1'),
				'sub_menu' => array()
			),
		);
    }

    if($group == 'provider') {
		$menu = array(
			array(
				'title' => 'Profile',
				'icon' => 'user',
				'class' => '',
				'url' 	=> URL::route('provider.accounts.profile'),
				'sub_menu' => array()
			),
			array(
				'title' => 'Account Settings',
				'icon' => 'user',
				'class' => '',
				'url' 	=> URL::route('provider.accounts.settings', 'tab=1'),
				'sub_menu' => array()
			)
		);
	}

    if($group == 'admin') {
		$menu = array(
			array(
				'title' => 'My Profile',
				'icon' => 'user',
				'class' => '',
				'url' 	=> URL::route('admin.users.profile'),
				'sub_menu' => array()
			),
		);
	}

	return $menu;
}


function countries($val ='') {
	$data = (array)json_decode(file_get_contents('data/countries.json'));
	return ($val) ? @$data[$val] : $data;
}

function states($val ='') {
	$data = (array)json_decode(file_get_contents('data/states.json'));
	return ($val) ? @$data[$val] : $data;
}

function practice_types($val='') {
	$data = [
		'corporate' 						=> 'Corporate',
		'private' 							=> 'Private',
		'specialty' 						=> 'Specialty',
		'multi-specialty' 		 			=> 'Multi-Specialty',
		'specialist-endodontics' 			=> 'Specialist Endodontics',
		'specialist-oral-and-maxillofacial' => 'Specialist Oral and Maxillofacial',
		'specialist-orthodontics' 			=> 'Specialist Orthodontics',
		'specialist-pediatrics' 			=> 'Specialist Pediatrics',
		'specialist-periodontics' 			=> 'Specialist Periodontics',
		'specialist-prosthodontics' 		=> 'Specialist Prosthodontics'
	];

	return ($val) ? @$data[$val] : $data;
}

function travel_distance() {
	$travel_distance = [
	    '10'  => '10',
	    '20'  => '20',
	    '30'  => '30',
	    '40'  => '40',
	    '50'  => '50',
	    '75'  => '75',
	    '100' => '100',
	    '150' => '150',
	    '200' => '200',
	];

	return $travel_distance;
}


function special_type($val='') {

	$data = [
		'general-dentist' 	  => 'General Dentist',
		'endodontics' 		    => 'Endodontics',
		'oral-surgery' 		    => 'Oral Surgery',
		'periodontist' 		    => 'Periodontist',
		'pediatric-dentistry' => 'Pediatric Dentistry',
		'prosthodontics' 	    => 'Prosthodontics',
		'orthodontics'	 	    => 'Orthodontics',
	];

  $data = [
    'front-office-staff'                => 'Front Office Staff',
    'dental-assistant'                  => 'Dental Assistant',
    'dental-hygienist'                  => 'Dental Hygienist',
    'general-dentistry'                 => 'General Dentistry',
    'specialist-endodontics'            => 'Specialist Endodontics',
    'specialist-oral-and-maxillofacial' => 'Specialist Oral and Maxillofacial',
    'specialist-orthodontics'           => 'Specialist Orthodontics',
    'specialist-pediatrics'             => 'Specialist Pediatrics',
    'specialist-periodontics'           => 'Specialist Periodontics',
    'specialist-prosthodontics'         => 'Specialist Prosthodontics',
  ];

	return ($val) ? @$data[$val] : $data;
}

function provider_type($val='') {
	$data = [
	    'dental_assistant'   => 'Dental Assistant',
	    'dental_hygienist'   => 'Dental Hygienist',
	    'front_office_staff' => 'Front Office Staff',
		  'general_dentist'    => 'General Dentist',
	    'dental_specialist'  => 'Dental Specialist',
	];

	return ($val) ? @$data[$val] : $data;
}

function provider_info($val='') {

	if( in_array($val, ['general_dentist', 'dental_specialist']) ) {
		$val = 'default';
	}

	$data = [
	    'dental_assistant' => (object)[
		    'school' 	  => 'Dental Assisting Education', 
		    'school_name' => 'Dental Assisting School Name', 
		    'student' 	  => 'I am currently pursuing a dental assisting degree/certification'
	    ],
  	    'dental_hygienist' => (object)[
  			'school' 	  => 'Dental Hygiene Education', 
  			'school_name' => 'Dental Hygiene School Name', 
  			'student' 	  => 'I am currently pursuing a dental hygiene degree'
	    ],
	    'front_office_staff' => (object)[
		    'school' 	  => 'Education', 
		    'school_name' => 'Last Degree Earned', 
		    'student' 	  => 'I am currently pursuing a degree'
	    ],
      'default' => (object)[
        'school'    => 'Dental School', 
        'school_name' => 'Dental School Name', 
        'student'     => 'I am currently pursuing a degree in the dental industry'
      ],
	];

	return ($val) ? @$data[$val] : $data;
}

function job_type($val='') {
	$data = [
		'full_time' => 'Full time',
		'part_time' => 'Part time',
		'temporary' => 'Temporary',
	];

	return ($val) ? @$data[$val] : $data;
}

function placement($val='') {
	$data = [
		'permanent' => 'Permanent',
		'temporary' => 'Temporary',
	];

	return ($val) ? @$data[$val] : $data;
}

function salary_type($val='') {
	$data = [
    'hourly'     => 'Hourly',
    'daily'      => 'Daily',
    'percentage' => 'Percentage'
	];

	return ($val) ? @$data[$val] : $data;
}

function text_to_code($val='') {
	$data = [
		'hourly'     => 'Hour',
		'daily'      => 'Day',
    'percentage' => '%'
	];

	return ($val) ? @$data[$val] : $data;
}

function salary_prefix($val='') {

  $data  = '%';

  if( in_array($val, ['hourly', 'daily']) ) {
    $data = 'USD';
  }
  
  return $data;
}


function salary_prefix_formatted($type='', $rate='') {

  $data = $rate.' % / Hour';

  if( in_array($type, ['hourly', 'daily']) ) {
    $data = amount_formatted($rate).' / '.text_to_code($type);
  }
  return $data;
}



function application_status($val='') {
	$data = [
		'waiting'     => 'Application has been submitted, please wait until we review your application.',
		'invited'     => 'You are <b>invited</b> for interview.',
		'hired'       => 'You are <b>hired</b> for this job.',
		'cancelled'   => 'Sorry, your application has been <b>cancelled</b>.',
	];

	return ($val) ? @$data[$val] : $data;
}

function job_status($val='') {
	$data = [
		'open' => 'Open',
		'close' => 'Close',
	];

	return ($val) ? @$data[$val] : $data;
}


function years_of_experience($val='') {
	$data = [
		'zero_years'            => 'Less than a year',
		'one_to_three_years'    => '1-3 years',
		'four_to_seven_years'   => '4-7 years',
		'more_than_eight_years' => '8+ years',
	];

	return ($val) ? @$data[$val] : $data;
}


function fluency() {

	$fluency = [
		'basic' => 'Basic',
		'conversational' => 'Conversational',
		'fluent' => 'Fluent',
		'native' => 'Native',
	];

	return $fluency;
}

function payment_terms($val='') {

	$payment_terms = [
		'1' => 'Same day',
		'7' => 'Up to 7 days',
		'14' => 'Up to 14 days',
		'bi-annually' => 'Bi-annually',
		'annually' => 'Annually',
	];

	return ($val) ? $payment_terms[$val] : $payment_terms;
}

function max_booking_days($val='') {
	
	$data = [
		"7" => "7 days",
		"14" => "14 days",
		"30" => "30 days",
		"60" => "60 days",
		"90" => "90 days",
		"180" => "180 days"
	];

	return ($val) ? @$data[$val] : $data;
}


function array_to_json($val='') {

	if( is_array($val) ) {
		$val = json_encode($val);
	}

	return $val;
}

function text_to_slug($val='') {
	return str_replace(' ', '-', strtolower($val));
}

function active_form($form, $segment) {

	if($segment == $form) {
		return 'active';
	} elseif($segment > $form) {
		return 'done';
	}
}

function form_actions($val ='') {
 
	$data = array(
		"trash" => "Move to Trash",
	);

	if( Input::get('type') ) {
		$data = array(
			"restore" => "Restore",
			"destroy" => "Delete Permanently",
		);
	}

	return ($val) ? @$data[$val] : $data;
} 

function query_vars($query ='') {

    $qs = $_SERVER['QUERY_STRING'];
    $vars = array();
	if($query == '') return $qs;

    parse_str($_SERVER['QUERY_STRING'], $qs);
    
    foreach ($qs as $key => $value) {    	
		$vars[$key] = $value;

		if($value == '0') {
			unset($vars[$key]);		
		}
    }
 
    parse_str($query, $queries);
    
    foreach ($queries as $key => $value) {    	
		$vars[$key] = $value;

		if($value == '0') {
			unset($vars[$key]);		
		}
    }

    return $vars;
}

function work_status($temp = '', $full = '') {

	if( !$temp && !$full) return '';

	$work = ($temp) ? 'Temporary' : '';
	$work .= ($temp && $full) ? ' & ' : '';
	$work .= ($full) ? 'Fulltime' : '';
	$work .= ' Work';

	return strtoupper($work);
}

function get_meta($rows = array()) {
	$data = array();
	foreach($rows as $row) { 
		$data[$row->meta_key] = $row->meta_value;
	}

	return (object)$data;
}


function user_status($val ='') {

	$data = array(
		"pending"   => "Pending",
		"approved"  => 'Approved (with Welcome Notification)',
		"actived"   => 'Activated',
		"inactived" => "Deactivated",
		"suspended" => 'Suspended',
	);
	
	return ($val) ? @$data[$val] : $data;
}

function appointment_status($val ='') {

  $data = array(
    "pending"   => "Pending Appointments",
    "confirmed"  => 'Confirmed Appointments',
    "for_approval"   => 'Completed (For Approval)',
    "completed" => "Completed Appointments",
    "cancelled" => 'Cancelled Appointments',
  );
  
  return ($val) ? @$data[$val] : $data;
}

function job_post_status($val ='') {

	$data = array(
		"pending"   => "Pending",
		'approved'  => 'Approved',
		'cancelled' => 'Cancelled',
	);
	
	return ($val) ? @$data[$val] : $data;
}

function array_to_text($rows=array(), $type='') {
	$data = array();

	foreach($rows as $row) {

		if($type == 'practice_types') {
			$data[] = practice_types($row);
		}

	}


	return implode(', ', $data);
}


function user_languages($val='') {
	$languages = json_decode(file_get_contents('data/languages.json'));

	foreach($languages as $lang) {
		$data[$lang->code] = $lang->name;
	}

	return ($val) ? @$data[$val] : $data;
}

function skills($val='') {
	$skills = json_decode(file_get_contents('data/skills.json'));

	if( $val ) {
	    foreach($skills as $s) {
	      foreach($s as $k => $v) {
	        $data[$k] = $v;
	      }
	    }
		return $data[$val];
	} else {
		foreach($skills as $k => $v) {
			$data[$k] = (array)$v;
		}

		return $data;
	}
}

function get_times($val='') {

	$data = json_decode(file_get_contents('data/times.json'));

	return is_numeric($val) ? @$data->$val : $data;

}

function get_days($val='') {

	$data = array(
	    1 => 'Monday', 
	    2 => 'Tuesday', 
	    3 => 'Wednesday', 
	    4 => 'Thursday', 
	    5 => 'Friday', 
	    6 => 'Saturday', 
	    7 => 'Sunday'
	);

	return ($val) ? @$data[$val] : $data;

}

function profile_status($status ='', $text = false) {

  $data = '<i class="online"></i> '. ($text ? 'Available for work' : '');

  if($status == 'invisible') {
    $data = '<i class="offline"></i> '. ($text ? 'Not available for work' : '');  
  }

  return $data;
}

function status_ico($val) {
	$data[''] = '';

	$data[1] = '<span class="badge badge-primary uppercase sbold">Approved</span>';
	$data[0] = '<span class="badge badge-danger uppercase sbold">Pending</span>';
	$data[2] = '<span class="badge badge-success uppercase sbold">Unpaid</span>';

	$data['publish'] = '<span class="badge badge-primary uppercase sbold">Published</span>';

	$data["approved"] = '<span class="badge badge-primary uppercase sbold">Approved</span>';
	$data['completed']  = '<span class="badge badge-primary uppercase sbold">Completed</span>';
	$data['pending']    = '<span class="badge badge-danger uppercase sbold">Pending</span>';
	$data["cancelled"]  = '<span class="badge badge-default uppercase sbold">Cancelled</span>';
	$data["processing"] = '<span class="badge badge-warning uppercase sbold">Processing</span>';
  $data["processed"] = '<span class="badge badge-warning uppercase sbold">Processed</span>';

	$data["waiting"] = '<span class="badge badge-warning uppercase sbold">Waiting</span>';
	$data["invited"] = '<span class="badge badge-success uppercase sbold">Sent Invitation</span>';
	$data["hired"] = '<span class="badge badge-primary uppercase sbold">Hired</span>';

  $data["verified"]  = '<span class="text-primary"><i class="icon-badge"></i> Verified</span>';
  $data["unverified"] = '<span class="text-muted">Unverified</span>';

              

	$data["open"] = '<span class="badge badge-primary uppercase sbold">Open</span>';
	$data["close"] = '<span class="badge badge-success uppercase sbold">Closed</span>';

	$data["actived"] = '<span class="badge badge-primary uppercase sbold">Actived</span>';
	$data["inactived"] = '<span class="badge badge-default uppercase sbold">Inactived</span>';
	$data["suspended"] = '<span class="badge badge-warning uppercase sbold">Suspended</span>';

	$data["available"] = '<span class="badge badge-primary uppercase sbold">Available</span>';
	$data["not-available"] = '<span class="badge badge-default uppercase sbold">Not Available</span>';

	$data["paid"] = '<span class="badge badge-primary uppercase sbold">Paid</span>';
	$data["ended_immediately"] = '<span>Ended immediately</span>';
	$data["ended_at_period_end"] = '<span>Ended at period end</span>';

	echo $data[$val];
}

function stars_review($count = 0) {
	$star = '';
	
	if($count) {
		$c = explode('.', $count);
		foreach(range(1, $c[0]) as $s) {
			$star .= '<i class="fa fa-star"></i> ';  
		}

		if(@$c[1]) $star .= '<i class="fa fa-star-half"></i> ';  	
	}

	echo $star;
}

function str_mask( $str, $start = 0, $length = null , $char ="*") {
    $mask = preg_replace ( "/\S/", $char, $str );
    if( is_null ( $length )) {
        $mask = substr ( $mask, $start );
        $str = substr_replace ( $str, $mask, $start );
    }else{
        $mask = substr ( $mask, $start, $length );
        $str = substr_replace ( $str, $mask, $start, $length );
    }
    return $str;
}
 
function badge_level($user_id = '') {
	
	$level = 0;

    $selects = array('overall');
    $review_count = App\Post::search([], $selects, [])->where('post_title', $user_id)->where('post_type', 'review');
    $all_reviews_count = ($review_count->count()) ? $review_count->count() : 1;
    $overall_reviews = $review_count->get()->SUM('overall') / $all_reviews_count;

    $selects = array('appointment_reward', 'user_id');
    $search['user_id'] =  $user_id;
    $referrer = App\Post::search($search, $selects, [])->where('post_status', 'completed')->where('post_type', 'booking');
    $appointment = $referrer->get()->SUM('appointment_reward');

    $selects = array('engage_reward');
    $engage_reward = App\Post::search($search, $selects, [])->where('post_title', $user_id);
    $engage = $engage_reward->get()->SUM('engage_reward');

	if( $overall_reviews >= 4 ) {
		if( $appointment >= 5 && $engage >= 50) {
			$level = 1;					
		} 
		if( $appointment >= 20 && $engage >= 200) {
			$level = 2;					
		} if( $appointment >= 100 && $engage >= 1000) {
			$level = 3;		
		}	
	}

	if( $level ) {
		echo '<img src="'.asset('img/level-'.$level.'.png').'" class="img-profile-badge">';
	}
}

function message_notification($type ='', $status ='') {

	$post_content = '';

	if($type == 'book') {
		$post_content = '[owner] sent a booking appointment to [provider]';
	}
	if($type == 'provider_book_update_status') {
		$post_content = '[provider] '.$status.' [owner_2] booking appointment';
	}
	if($type == 'owner_book_update_status') {
		$post_content = '[owner] '.$status.' [provider_2] booking appointment';
	}

	return $post_content;
}

function decode_message($group='', $name='', $message='') {

	if($group == 'owner') {
		$find = [
			'[owner]', 
			'[provider]', 
			'[owner_2]', 
			'[provider_2]', 
			'/owner/'
		];
		$replace = [
			'<b>'.$name.'</b>', 
			'you',
			$name."'s", 
			'your', 
			'/provider/'
		];

	    $data = str_replace($find, $replace, $message); 
	} else {
		$find = [
			'[owner]', 
			'[provider]', 
			'[owner_2]', 
			'[provider_2]', 
			'/provider/'
		];
		$replace = [
			'<b>You</b>', 
			$name, 
			'your', 
			$name."'s", 
			'/owner/'
		];

	    $data = str_replace($find, $replace, $message); 
	}   

	return ucfirst($data);
}

function compress($source, $destination, $quality) {

	$info = getimagesize($source);

	if ($info['mime'] == 'image/jpeg') 
		$image = imagecreatefromjpeg($source);

	elseif ($info['mime'] == 'image/gif') 
		$image = imagecreatefromgif($source);

	elseif ($info['mime'] == 'image/png') 
		$image = imagecreatefrompng($source);

	imagejpeg($image, $destination, $quality);

	return $destination;
}

function test_print($item, $key)
{
    echo "$key holds $item\n";
}

function get_geolocation($address='')
{
    $apikey = config('services.google.apikey');

    $address = str_replace(" ", "+", $address);
    $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&key=".$apikey);

    $json = json_decode($json);

    $data['lat'] = @$json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
    $data['lng'] = @$json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};    

    return $data;
}


function packages($id='')
{   
	$posts = App\Post::where('post_type', 'plan')->get();

	$id = str_replace('staffing-dental-', '', $id);

	list($a, $b) = explode('-', $id);

	foreach ($posts as $post) {

		$features = json_decode($post->post_content);

		foreach ($features->total as $total) {
			$data[$post->id][] = array(
					'name' => $post->post_title,
					'amount' => $total->amount, 
					'term' => $total->number_of_day.' days', 
			);
		}

	}

	return ($id) ? (object)$data[$a][$b] : $data;
}

function get_distance($distance=0) {
	$distance = number_format($distance);
	$type = 'miles';
	if( $distance < 1 ) {
		$distance  = $distance/0.00062137;
		$distance = ($distance==0) ? 'Near' : $distance;
		$type = 'from your location!';  		
	}
	return $distance.' '.$type;
}

function acceptance_rate($total=0, $done=0) {
	$rate = $total ? number_format(($done/$total) * 100) : 0;
	return $rate.'%';
}

function has_dental_license($provider='') {
  $providers = ['general_dentist', 'dental_specialist'];
  if( in_array( $provider, $providers ))
    return true;
}


function get_dates_from_range($start, $end, $format = 'Y-m-d') {
    $array = array();
    $interval = new DateInterval('P1D');

    $realEnd = new DateTime($end);
    $realEnd->add($interval);

    $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

    foreach($period as $date) { 
        $array[] = $date->format($format); 
    }

    return $array;
}

function profile_completeness($info, $group) {

  $complete = 0; 

  if( $group == 'owner') {

    $profile_count = 7;

    $address = array(
        $info->phone_number,
        $info->street_address,
        $info->zip_code,
        $info->state,
        $info->city,
    );

    if( count(array_filter($address)) != 5) {
        $complete += 1;
    }

    if( ! $info->practice_name) {
        $complete += 1;
    }

    if( ! $info->company_name) {
        $complete += 1;
    }

    if( ! $info->practice_description) {
        $complete += 1;
    }

    if( ! $info->profile_picture) {
        $complete += 1;
    }

    if( ! $info->government_issued_id) {
        $complete += 1;
    }

    if( ! $info->email_verified) {
        $complete += 1;
    }
  }


if( $group == 'provider') {

  $profile_count = 9;

   if(has_dental_license($info->provider_type)) {
      $profile_count = 10;
   }

  $complete = 0; 

  $address = array(
      $info->phone_number,
      $info->street_address,
      $info->zip_code,
      $info->state,
      $info->city,
  );

  if( count(array_filter($address)) != 5) {
      $complete += 1;
  }

  if( ! $info->background_description) {
      $complete += 1;
  }

  if(has_dental_license($info->provider_type)) {
      if( ! @$info->dental_license) {
          $complete += 1;
      }
  }

  if( ! $info->minimum_fee) {
      $complete += 1;
  }

  if( ! $info->graduation_year || ! $info->dental_school_name ) {
      $complete += 1;
  }

  if( ! $info->availability) {
      $complete += 1;
  }

  if( ! $info->profile_picture) {
      $complete += 1;
  }

  if( ! $info->resume) {
      $complete += 1;
  }

  if( ! $info->government_issued_id) {
      $complete += 1;
  }

  if( ! $info->email_confirmed) {
      $complete += 1;
  }

}

return number_format((($profile_count-$complete)/$profile_count) * 100);

}