<?php

function loadCompanyInfo(){
	
	if( getSessionValue('company_name') ){
		return true;
	}

	$CI =& get_instance();	
	$CI->load->library ( 'Timebooking' );
	$company = $CI->timebooking->getCompanyInfo();
	
	foreach($company as $varName => $varValue){
		setSessionValue($varName, $varValue);
	}
	
	return true;
	
}

function loadSession(){
	if(! session_id() )
		session_start();
}

function chechStatus(){

	$CI =& get_instance();
	$controller = $CI->uri->segment(1);
	
	if( isAppBlocked() && $controller != 'contactenos' ){
		redirect( 'contactenos' );
	}
}
