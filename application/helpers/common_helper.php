<?php

function get_session_user(){
	$CI =& get_instance();
	
	if($session = $CI->session->all_userdata()){
		return $session;
	}
	
	return NULL; 
}

function validate_false_session(){
	$session_user = get_session_user();
	if(!isset($session_user['userName'])){
		redirect('login');
	}
	return true;
}

function validate_true_session(){
	$session_user = get_session_user();
	if(isset($session_user['userName'])){
		redirect('home');
	}
	return true;
}
