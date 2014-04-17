<?php

function get_session_user(){
	$CI =& get_instance();
	
	if($session = $CI->session->all_userdata()){
		return $session;
	}
	
	return NULL; 
}
