<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Registro extends CI_Controller {

	var $title = 'Registro';
	var $registro_params = array();
	var $error;

	function __construct() {
		parent::__construct();
		
		$this -> load -> library('Timebooking');
		$this -> load -> model ( 'Patient_model', 'patient' );

		$this -> registro_params['phone_prefixes'] 		= $this -> config -> item('phone_prefixes');
		$this -> registro_params['medical_services'] 	= $this -> config -> item('medical_services');

	}

	public function index() {
		$this -> render();
	}

	private function render($page = 'registro') {
		$this -> template -> write('title', $this -> title);

		$this -> template -> add_js('assets/js/moment-2.4.0.js');
		$this -> template -> add_js('assets/third_party/bootstrap/js/bootstrap-datetimepicker.min.js');
		$this -> template -> add_js('assets/js/jquery.validationEngine.js');
		$this -> template -> add_js('assets/js/jquery.validationEngine-es.js');
		$this -> template -> add_js('assets/js/jquery.Rut.js');
		$this -> template -> add_js('assets/third_party/bootstrap/js/bootstrap3-typeahead.js');
		$this -> template -> add_js('assets/js/jquery.form.min.js');
		$this -> template -> add_js('assets/js/registro.js');

		$this -> template -> add_css('assets/css/validationEngine.jquery.css');
		$this -> template -> add_css('assets/third_party/bootstrap/css/bootstrap-datetimepicker.min.css');

		$this -> template -> write_view('header', 'templates/header', $this -> registro_params, TRUE);

		$this -> template -> write_view('content', "pages/$page", $this -> registro_params, TRUE);

		$this -> template -> render();
	}

	/**
	 * guardar
	 *
	 * Register a new user
	 */
	public function guardar() {

		/* Para registrar las opciones de mensajeria usar el metodo del web service
		 * WM_ActualizaOpcionesMensajeria donde:
		 * (VIa de confirmación) Op_SmsEmail dede ser:
		 * 2^3 => SMS y Email
		 * 2^  => Sms
		 * ^3  => Email
		 * ^   => No quiere ser informado
		 *
		 * Recibe info davila?
		 * Op_InfoClinica:
		 * N => No
		 * S => Si*/

		//Define the validation rules
		$validation_rules = array();

		//To prvent attacks, clean the input
		$data = array();
		foreach ($_POST as $f => $v) {

			if ($f == 'Email_Paciente-confirma' || $f == 'Clave_Usuario-confirma' || $f == 'Comuna_Paciente-label')
				continue;

			if ($f == 'Rut_Paciente') {
				$v = str_replace('.', '', $this -> input -> post($f, TRUE));
				$rut = explode('-', $v);
				$data[$f] = $rut[0];
				$data['Dv_Paciente'] = $rut[1];
			} else {
				$data[$f] = $this -> input -> post($f, TRUE);
			}

		}

		$this -> load -> model('Patient_model', 'patient');

		$result = $this -> patient -> create($data);
		if (!$result) {
			echo json_encode(array('state' => false, 'message' => $this -> patient -> getError()));
			die();
		}

		die(json_encode(array('state' => true, 'message' => '')));
	}

	/**
	 * modificardatos
	 */
	public function modificardatos() {
		validate_false_session();

		$action = isset($_POST['action']) ? $_POST['action'] : null;
		switch ($action) {
			case 'save_personal_info':
				$this -> save_personal_info($_POST);
				$this->registro_params['active_tab'] = 'personal_info';
				break;
			case 'save_contact_info':
				$this -> save_contact_info ($_POST);
				$this->registro_params['active_tab'] = 'contact_info';
				break;
			case 'save_security_info':
				$this -> save_security_info ($_POST);
				$this->registro_params['active_tab'] = 'security_info';
				break;
			case 'save_family_info':
				$this -> save_family_info ( $_POST );
				$this->registro_params['active_tab'] = 'family_info';
				break;
		}

		$this -> template -> write('title', $this -> title);
		$this -> template -> add_css('assets/third_party/validationEngine/validationEngine.jquery.css');
		$this -> template -> add_js('assets/js/moment-2.4.0.js');
		$this -> template -> add_js('assets/third_party/bootstrap/js/bootstrap-datetimepicker.min.js');
		$this -> template -> add_js('assets/js/jquery.validationEngine.js');
		$this -> template -> add_js('assets/js/jquery.validationEngine-es.js');
		$this -> template -> add_js('assets/js/jquery.Rut.js');

		$this -> template -> add_js('assets/third_party/bootstrap/js/bootstrap.min.js');
		$this -> template -> add_js('assets/js/modificardatos.js');

		$rut = getSessionValue('rut');
		$dv  = getSessionValue('dv');
		$this -> registro_params['user_data'] 	= $this -> patient -> get($rut, $dv);
		setSessionValue('user_data', $this -> registro_params['user_data']);

		$this -> registro_params['user_access']	= $this -> patient -> getUserAccess($rut, $dv);

		$id_grupo_familiar = $this -> registro_params['user_data']['id_grupo_familiar'];
		$this -> registro_params['family_data'] = $this -> patient -> getFamilyMembers ($rut, $dv, $id_grupo_familiar);

		foreach ( $this->registro_params['family_data'] as &$family_member_data ) {
			if (isset ( $family_member_data['parentesco'] ) )
				switch ( $family_member_data['parentesco'] ) {
					case 'C':
						$family_member_data['desc_parentesco'] = 'Cónyugue';
						break;
				}
		}

		if ($this->registro_params['user_access'])
			setSessionValue('user_access', $this -> registro_params['user_access']);

		$this -> template -> write_view('header', 'templates/header');
		$this -> template -> write_view('content', 'pages/modificardatos', $this -> registro_params, TRUE);
		$this -> template -> render();
	}

	/**
	 * save_personal_info
	 */
	protected function save_personal_info ( $data = array() ) {
		$session_user_data 		= getSessionValue ( 'user_data' );
		$session_user_access 	= getSessionValue ( 'user_access' );

		$rut_paciente 			= isset ( $data['Rut_Paciente'] ) ? $data['Rut_Paciente'] : $session_user_data['rut_paciente'] . '-' . $session_user_data['dv_paciente'];
		$nombre_paciente 		= isset ( $data['Nombre_Paciente'] ) ? $data['Nombre_Paciente'] : $session_user_data['nombre_paciente'];
		$apepat_paciente 		= isset ( $data['Apepat_Paciente'] ) ? $data['Apepat_Paciente'] : $session_user_data['apepat_paciente'];
		$apemat_paciente 		= isset ( $data['Apemat_Paciente'] ) ? $data['Apemat_Paciente'] : $session_user_data['apemat_paciente'];
		$comuna_paciente 		= isset ( $data['Comuna_Paciente'] ) ? $data['Comuna_Paciente'] : $session_user_data['comuna_paciente'];
		$fechanac_paciente 		= isset ( $data['Fechanac_Paciente'] ) ? $data['Fechanac_Paciente'] : $session_user_data['fecha_nac_paciente'];
		$direccion_paciente 	= isset ( $data['Direccion_Paciente'] ) ? $data['Direccion_Paciente'] : $session_user_data['direccion_paciente'];
		$sexo_paciente 			= isset ( $data['Sexo_Paciente'] ) ? $data['Sexo_Paciente'] : $session_user_data['sexo_paciente'];
		$ciudad_paciente 		= isset ( $data['Ciudad_Paciente'] ) ? $data['Ciudad_Paciente'] : $session_user_data['ciudad_paciente'];
		$prevision_paciente		= isset ( $data['Prevision_Paciente'] ) ? $data['Prevision_Paciente'] : $session_user_data['prevision_paciente'];
		$fono1_paciente 		= isset ( $data['Fono1_Paciente'] ) ? $data['Fono1_Paciente'] : $session_user_data['fono_princ_paciente'];
		$fono2_paciente 		= isset ( $data['Fono2_Paciente'] ) ? $data['Fono2_Paciente'] : $session_user_data['fono_alter_paciente'];
		$prefmovil1_paciente 	= isset ( $data['PrefMovil1'] ) ? $data['PrefMovil1'] : $session_user_data['prefijo_celular1'];
		$fonomovil1_paciente 	= isset ( $data['FonoMovil1'] ) ? $data['FonoMovil1'] : $session_user_data['numero_celular1'];
		$prefmovil2_paciente 	= isset ( $data['PrefMovil2'] ) ? $data['PrefMovil2'] : $session_user_data['prefijo_celular2'];
		$fonomovil2_paciente 	= isset ( $data['FonoMovil2'] ) ? $data['FonoMovil2'] : $session_user_data['numero_celular2'];
		$email_paciente 		= isset ( $data['Email_Paciente'] ) ? $data['Email_Paciente'] : $session_user_data['email_paciente'];
		$clave_paciente 		= isset ( $data['Clave_Usuario'] ) ? $data['Clave_Usuario'] : $session_user_access['clave_usuario'];
		$pregunta_paciente 		= isset ( $data['Pregunta_Clave'] ) ? $data['Pregunta_Clave'] : $session_user_access['pregunta_clave'];
		$respuesta_paciente 	= isset ( $data['Respuesta_Clave'] ) ? $data['Respuesta_Clave'] : $session_user_access['respuesta_clave'];

		$rut 			= explode ( '-', $rut_paciente );
		$Rut_Paciente 	= $rut[0];
		$Dv_Paciente 	= $rut[1];

		$params = array(
			'Tipo_Usuario'			=> 'Paciente',
			'Rut_Paciente'			=> $Rut_Paciente,
			'Dv_Paciente'			=> $Dv_Paciente,
			'Nombre_Paciente'		=> $nombre_paciente,
			'Apepat_Paciente'		=> $apepat_paciente,
			'Apemat_Paciente'		=> $apemat_paciente,
			'Fechanac_Paciente'		=> $fechanac_paciente,
			'Direccion_Paciente'	=> $direccion_paciente,
			'Comuna_Paciente'		=> $comuna_paciente,
			'Ciudad_Paciente'		=> $ciudad_paciente,
			'Sexo_Paciente'			=> $sexo_paciente,
			'Prevision_Paciente'	=> $prevision_paciente,
			'Id_Ambulatorio'		=> $session_user_data['id_ambulatorio'],
			'Fono1_Paciente'		=> $fono1_paciente,
			'Fono2_Paciente'		=> $fono2_paciente,
			'PrefMovil1'			=> $prefmovil1_paciente,
			'FonoMovil1'			=> $fonomovil1_paciente,
			'PrefMovil2'			=> $prefmovil2_paciente,
			'FonoMovil2'			=> $fonomovil2_paciente,
			'Email_Paciente'		=> $email_paciente,
			'Clave_Usuario'			=> $clave_paciente,
			'Pregunta_Clave'		=> $pregunta_paciente,
			'RespuestaClave'		=> $respuesta_paciente,
			'Estado'				=> 'C',
			'Accion'				=> 'M'
		);

		$result = $this -> patient -> update ( $params );

		if ( !empty ( $result ) ) {
			$this->registro_params['message'] = array(
				'text'	=> $result,
				'class'	=> 'success'
			);
		} else {
			$this->registro_params['message'] = array(
				'text'	=> $this->patient->getError(),
				'class'	=> 'danger'
			);
		}

		return $result;
	}

	/**
	 * save_contact_info
	 */
	protected function save_contact_info ( $data ) {
		$session_user_data 		= getSessionValue('user_data');
		$session_user_access 	= getSessionValue('user_access');

		$sms_mail 			= $data['SMS_notificacion'] . '^' . $data['EMAIL_notificacion'];
		$fono1_2			= !empty ( $data['FonoMovil1'] ) ? '1' : '2';
		$modifica_moviles	= ( $session_user_data['numero_celular1'] != $data['FonoMovil1']
								|| $session_user_data['numero_celular2'] != $data['FonoMovil2'] )
								? 'S' : 'N';

		$personal_params	= array(
			'Fono1_Paciente'	=> $data['Fono1_Paciente'],
			'Fono2_Paciente'	=> $data['Fono2_Paciente'],
			'Email_Paciente'	=> $data['Email_Paciente'],
		);

		$resultPatient = $this -> save_personal_info ( $personal_params );

		$params = array(
			'Id_Paciente'			=> $session_user_data['id_ambulatorio'],
			'Op_SmsEmail'			=> $sms_mail,
			'Op_Fono1o2'			=> $fono1_2,
			'Op_InfoClinica'		=> $data['Op_InfoClinica'],
			'ModificaMoviles'		=> $modifica_moviles,
			'PrefMovil1'			=> $data['PrefMovil1'],
			'Movil1'				=> $data['FonoMovil1'],
			'PrefMovil2'			=> $data['PrefMovil2'],
			'Movil2'				=> $data['FonoMovil2']
		);

		$result 		= $this -> patient -> updateUserMessagingOptions ( $params );

		if ( !empty ( $result ) ) {
			if ( !empty ( $resultPatient ) ) {
				$this->registro_params['message'] = array(
					'text'	=> $result,
					'class'	=> 'success'
				);
			} else {
				$this->registro_params['message'] = array(
					'text'	=> $this->patient->getError(),
					'class'	=> 'danger'
				);
			}
		} else {
			$this->registro_params['message'] = array(
				'text'	=> $this->patient->getError(),
				'class'	=> 'danger'
			);
		}
	}

	/**
	 * save_security_info
	 */
	protected function save_security_info($data) {
		$session_user_data 		= getSessionValue('user_data');
		$session_user_access 	= getSessionValue('user_access');

		// Change password if given
		if ( !empty ( $data['Clave_Usuario'] ) && !empty ( $data['Clave_Usuario-confirma'] ) ) {
			$update_user_access_params = array(
				'Id_Paciente'		=> $data['user']['id_ambulatorio'],
				'Pregunta_Clave'	=> $data['Pregunta_Clave'],
				'Respuesta_Clave'	=> $data['Respuesta_Clave'],
				'Clave_Actual'		=> $session_user_access['clave_usuario'],
				'Clave_Nueva'		=> $data['Clave_Usuario']
			);

			$result = $this -> patient -> updateUserAccess ( $update_user_access_params );

			if ( !empty ( $result ) ) {
				$this->registro_params['message'] = array(
					'text'	=> $result,
					'class'	=> 'success'
				);
			} else {
				$this->registro_params['message'] = array(
					'text'	=> $this->patient->getError(),
					'class'	=> 'danger'
				);
			}
		}
	}

	/**
	 * save_family_info
	 */
	public function save_family_info ( $data ) {
		$session_user_data 		= getSessionValue ( 'user_data' );
		$session_user_access 	= getSessionValue ( 'user_access' );

		$id_carga 		= isset ( $data['Carga_Id_Ambulatorio'] ) ? $data['Carga_Id_Ambulatorio'] : 0;

		$rut 		= explode ( '-', $data['Carga_Rut_Paciente'] );
		$Rut_Carga 	= $rut[0];
		$Dv_Carga 	= $rut[1];

		$params = array(
			'Id_Titular'		=> $session_user_data['id_ambulatorio'],
			'Id_Grupofamiliar'	=> $session_user_data['id_grupo_familiar'],
			'Id_Carga'			=> $id_carga,
			'Rut_Carga'			=> $Rut_Carga,
			'Dv_Carga'			=> $Dv_Carga,
			'Nombre_Carga'		=> $data['Carga_Nombre_Paciente'],
			'Apepat_Carga'		=> $data['Carga_Apepat_Paciente'],
			'Apemat_Carga'		=> $data['Carga_Apemat_Paciente'],
			'FechaNac_Carga'	=> $data['Carga_Fechanac_Paciente'],
			'Parentesco_Carga'	=> $data['Carga_Parentesco_Paciente'],
			'Sexo_Carga'		=> $data['Carga_Sexo_Paciente'],
			'Prevision_Carga'	=> $data['Carga_Prevision_Paciente'],
			'Accion'			=> $data['member_action']
		);

		$result = $this -> patient -> addFamilyMember ( $params );

		if ( !empty ( $result ) ) {
			$this->registro_params['message'] = array(
				'text'	=> $result,
				'class'	=> 'success'
			);
		} else {
			$this->registro_params['message'] = array(
				'text'	=> $this->patient->getError(),
				'class'	=> 'danger'
			);
		}
		
	}
	
	public function adicionafamilia() {
		$this -> template -> write('title', $this -> title);
		$this -> template -> add_css('assets/vendor/calendarjs/css/calendar.min.css');
		$this -> template -> add_js('assets/vendor/underscore/underscore-min.js');

		$this -> template -> write_view('header', 'templates/header');
		$this -> template -> write_view('content', 'pages/adicionafamilia');
		$this -> template -> render();
	}

}
