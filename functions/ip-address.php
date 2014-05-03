<?php
	function ip() {
		if(function_exists('apache_request_headers')) {
			$headers = apache_request_headers(); 
			if( isset($_SERVER['HTTP_CF_CONNECTING_IP']) ) {
				return $_SERVER['HTTP_CF_CONNECTING_IP'];
			}
			elseif (!isset($_SERVER['REMOTE_ADDR']) && !isset($headers['X-Forwarded-For']) && !isset($_SERVER['True-Client-IP'])&& !isset($_SERVER['HTTP_INCAP_CLIENT_IP'])){ 
				$realIP = '0.0.0.0';
		    }
			if (isset($_SERVER['True-Client-IP'])) {
				$realIP = filter_var($_SERVER['HTTP_INCAP_CLIENT_IP'], FILTER_VALIDATE_IP);
			}
			elseif (isset($_SERVER['HTTP_INCAP_CLIENT_IP'])) {
				$realIP = filter_var($_SERVER['HTTP_INCAP_CLIENT_IP'], FILTER_VALIDATE_IP);
			} 
			else {
				$userIP = isset($headers['X-Forwarded-For']) ? $headers['X-Forwarded-For'] : $_SERVER['REMOTE_ADDR'];
				$userIP = explode(',', $userIP);
				$realIP = filter_var($userIP[0], FILTER_VALIDATE_IP);
        	}
        	list($q1,$q2,$q3,$q4) = explode('.',$realIP);
        	if($q1 == '10' || $q1 == '192' || $q1 == '127') {
        		$ip_feedback = wp_remote_get('http://httpbin.org/ip');
        		if(!is_wp_error($ip_feedback)) {
					$ip_feedback_array = json_decode($ip_feedback['body'],true);
				} else {
					$ip_feedback_array['origin'] = '81.157.49.99';
				}
        	        $ip = $ip_feedback_array['origin'];
        	} else {
        	        $ip = $realIP;
        	}
		} else {
			$ip = '81.157.49.99';
		}
		return $ip;
	}
?>