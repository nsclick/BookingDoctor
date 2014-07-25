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
	
	/**
	 * create a new patient
	 */
	function create($data){
		//Compose the phones
		$data['Fono1_Paciente'] = $data['prefijo_Fono1_Paciente'] . $data['Fono1_Paciente'];
		unset($data['prefijo_Fono1_Paciente']);
		$data['Fono2_Paciente'] = ($data['Fono2_Paciente']) ? $data['prefijo_Fono2_Paciente'] . $data['Fono2_Paciente'] : NULL;
		unset($data['prefijo_Fono2_Paciente']);
		
		$result = $this->timebooking->registerPatient ( $data );
		if(!$result){
			$this->error = $this->timebooking->getError ();
			return false;
		}

		$rut 				= $data['Rut_Paciente'];
		$dv 				= $data['Dv_Paciente'];
		// $user_data 			= $this -> get( $rut, $dv );
		$sms_mail 			= $data['SMS_notificacion'] . '^' . $data['EMAIL_notificacion'];
		$fono1_2			= !empty ( $data['FonoMovil1'] ) ? '1' : '2';
		$modifica_moviles	= 'N';

		$messagingParams	= array(
			'Id_Paciente'			=> $result,
			'Op_SmsEmail'			=> $sms_mail,
			'Op_Fono1o2'			=> $fono1_2,
			'Op_InfoClinica'		=> $data['Op_InfoClinica'],
			'ModificaMoviles'		=> $modifica_moviles,
			'PrefMovil1'			=> $data['PrefMovil1'],
			'Movil1'				=> $data['FonoMovil1'],
			'PrefMovil2'			=> $data['PrefMovil2'],
			'Movil2'				=> $data['FonoMovil2']
		);

		$messagingResult 	= $this -> patient -> updateUserMessagingOptions ( $messagingParams );
		if ( !$messagingResult ) {
			$this->error = $this->timebooking->getError();
			return false;
		}

		//Login the user
		$password = $data['Clave_Usuario'];
		
		if(!$this->login($rut, $dv, $password)){
			return false;
		}
		
		//Add to session the user name
		//$this->session->set_userdata('userName', "{$data['Nombre_Paciente']} {$data['Apepat_Paciente']} {$data['Apemat_Paciente']}");
		
		return true;
	}

	function update ( $data ) {
		$result = $this -> timebooking -> updatePatient ( $data );
		
		if ( !$result ) {
			$this -> error = $this -> timebooking -> getError ();
			return false;
		}

		return $result;
	}
	
	function get($rut, $dv){
		$result = $this->timebooking->getUserInfo( array( 'rut' => $rut, 'dv' => $dv ) );
		if(!$result){
			$this->error = $this->timebooking->getError();
			return false;
		}
		
		return $result;
	}

	function getUserAccess($rut, $dv) {
		$result = $this->timebooking->getUserAccess( array( 'rut' => $rut, 'dv' => $dv ) );
		if(!$result){
			$this->error = $this->timebooking->getError();
			return false;
		}

		return $result;
	}

	function updateUserAccess ( $data ) {
		$result = $this -> timebooking -> updateUserAccess ( $data );
		if (!$result) {
			$this -> error = $this -> timebooking -> getError();
			return false;
		}

		return $result;
	}

	function updateUserMessagingOptions ( $data ) {
		$result = $this -> timebooking -> updateMessagingOptions ( $data );

		if ( !$result ) {
			$this -> error = $this -> timebooking -> getError ();
			return false;
		}

		return $result;
	}
	
	function getFamilyMembers($rut = null, $dv = null, $id_grupo_familiar = null){
		

		if(is_null($id_grupo_familiar) && !isset($this->id_grupo_familiar))
			return null;

		if (is_null($rut))
			$rut = $this->session->userdata('rut');

		if (is_null($dv))
			$dv = $this->session->userdata('dv');
		
		$patient = get_object_vars($this);
		$patient['desc_parentesco'] = 'Titular';
		
		$fm = $this->timebooking->getFamilyMembers($rut, $dv, $id_grupo_familiar);
		
		array_unshift($fm, $patient);
		
		return $fm;
	}

	function addFamilyMember ( $data ) {
		$result = $this -> timebooking -> addUserFamilyMember ( $data );

		if ( !$result ) {
			$this -> error = $this -> timebooking -> getError ();
			return false;
		}

		return $result;
	}

}
