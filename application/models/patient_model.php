<?php

class Patient_model extends CI_Model {

	private $error = NULL;
	
	function __construct () {
		parent::__construct();
		$this->load->library ( 'Timebooking' );
	}

	function getError(){
		return $this->error;
	}
	
	function login($rut, $dv, $password){	
		$result = $this->timebooking->userLogin($rut, $dv, $password);
		if(!$result){
			$this->error = $this->timebooking->getError();
			return false;
		}
		
		$sessionData = array(
			'tmpKey' => $result->tmpKey,
			'ambulatoryID' => $result->ambulatoryID
		);
		
		$this->session->set_userdata($sessionData);
		return true;
	}
	
	function create($data){
		$result = $this->timebooking->registerPatient( $data );
		if(!$result){
			$this->error = $this->timebooking->getError();
			return false;
		}
		
		//Login the user
		$rut = $data['Rut_Paciente'];
		$dv = $data['Dv_Paciente'];
		$password = $data['Clave_Usuario'];
		
		$this->login($rut, $dv, $password);
		
		return true;
	}
}
