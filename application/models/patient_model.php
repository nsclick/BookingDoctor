<?php

class Patient_model extends CI_Model {

	private $error = NULL;
	
	function __construct () {
		parent::__construct();
		$this->load->library ( 'Timebooking' );
		
		if( $this->is_logged_in () ){
			$rut = getSessionValue('rut');
			$dv = getSessionValue('dv');
			$patient = $this->get($rut, $dv);
			foreach($patient as $key => $val){
				$this->$key = $val;
			}
		}
		
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
			'ambulatoryID' => (string) $result->ambulatoryID,
			'rut' => $rut ,
			'dv' => $dv,
			'userName' => $result->userName
		);
		
		foreach($sessionData as $key => $val){
			setSessionValue($key, $val);
		}
		
		return true;
	}

	function is_logged_in () {
		
		$tmpKey = getSessionValue ( 'userName' );
		return !empty ( $tmpKey );
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
		//$this->session->set_userdata('userName', "{$data['Nombre_Paciente']} {$data['Apepat_Paciente']} {$data['Apemat_Paciente']}");
		
		return true;
	}
	
	function get($rut, $dv){
		$patient = getSessionValue( 'cache_patient' );
		if($patient)
			return $patient; 
			
		$result = $this->timebooking->getUserInfo( array( 'rut' => $rut, 'dv' => $dv ) );
		
		if(!$result){
			$this->error = $this->timebooking->getError();
			return false;
		}
		
		setSessionValue( 'cache_patient', $result );
		
		return $result;
	}
	
	function getFamilyMembers(){
		
		if(!isset($this->id_grupo_familiar))
			return null;
		
		$rut = $this->session->userdata('rut');
		$dv = $this->session->userdata('dv');
		
		$patient = get_object_vars($this);
		$patient['desc_parentesco'] = 'Titular';
		
		$fm = $this->timebooking->getFamilyMembers($rut, $dv, $this->id_grupo_familiar);
		
		array_unshift($fm, $patient);
		
		return $fm;
	}
	
	function getReservedDates(){

		if(!isset($this->id_grupo_familiar))
			return null;
		
		$rd = $this->timebooking->getReservedDatesByFamily( $this->id_grupo_familiar );
		return $rd;
		
	}

}
