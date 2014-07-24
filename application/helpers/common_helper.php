<?php

function get_session_user(){
	
	if($session = getAllSession()){
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

function killSession(){
	session_destroy();
}

function getAllSession(){
	return $_SESSION;
}

function setSessionValue($varName, $varValue){
	$_SESSION[$varName] = $varValue;
}

function getSessionValue($varName){
	return isset($_SESSION[$varName]) ? $_SESSION[$varName] : NULL;
}

function removeSessionVar($varName){
	if(!$varName)
		return false;
		
	if(!is_array($varName) && is_string($varName) )
		$varName = array($varName);
		
	foreach($varName as $v){
		unset($_SESSION[$v]);
	}
	return true;
}
