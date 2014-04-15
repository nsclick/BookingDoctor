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
			'ambulatoryID' => $result->ambulatoryID,
			'rut' => $rut. '-' . $dv
		);
		
		$this->session->set_userdata($sessionData);
		return true;
	}
	
	function create($data){
		
		//Compose the phones
		$data['Fono1_Paciente'] = $data['prefijo_Fono1_Paciente'] . $data['Fono1_Paciente'];
		unset($data['prefijo_Fono1_Paciente']);
		$data['Fono2_Paciente'] = ($data['Fono2_Paciente']) ? $data['prefijo_Fono2_Paciente'] . $data['Fono2_Paciente'] : NULL;
		unset($data['prefijo_Fono2_Paciente']);
		
		$result = $this->timebooking->registerPatient( $data );
		if(!$result){
			$this->error = $this->timebooking->getError();
			return false;
		}
		
		//Login the user
		$rut = $data['Rut_Paciente'];
		$dv = $data['Dv_Paciente'];
		$password = $data['Clave_Usuario'];
		
		if(!$this->login($rut, $dv, $password)){
			return false;
		}
		
		//Add to session the user name
		$this->session->set_userdata('userName', "{$data['Nombre_Paciente']} {$data['Apepat_Paciente']} {$data['Apemat_Paciente']}");
		
		return true;
	}
}
