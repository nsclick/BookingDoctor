<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once (APPPATH . '/third_party/nusoap/lib/nusoap.php');

class Timebooking {

	/**
	 * Server URL Host
	 *
	 * @var string
	 * @access private
	 */
	private $host = 'http://reservas.davila.cl/Age_Ws_Reserva_Horas_demo/ResHoraWeb.asmx?wsdl';
//	private $host = 'http://reservas.davila.cl/Ws_ReservaHorasWeb/ResHoraWeb/ResHoraWeb.asmx?wsdl';
	
	/**
	 * Whether uses WSDL
	 *
	 * @var boolean
	 * @access private
	 */
	private $wsdl = true;
	/**
	 * Objeto client
	 *
	 * @var object
	 * @access private
	 */
	private $client = null;
	/**
	 * Errors messages container
	 *
	 * @var string
	 * @access private
	 */
	private $error = null;
	/**
	 * Company ID
	 *
	 * @var string
	 * @access public
	 */
	public $companyID = 1;
	/**
	 * Branch ID
	 *
	 * @var string
	 * @access public
	 */
	public $branchID = 1;
	/**
	 * IP
	 *
	 * @var string
	 * @access public
	 */
	public $ip = '200.6.100.43';
	/**
	 * webUser
	 *
	 * @var string
	 * @access public
	 */
	public $webUser = 'UINTERNET';

	/**
	 * Get the process error
	 * 
	 * @return error string
	 */
	public function getError(){
		return $this->error;
	}
	
	function __construct() {
		if (!$this->client) {
			$this->client = new nusoap_client($this->host, $this->wsdl);
			$error = $this->client->getError();
			if ($error) {
				$this->error = $error;
				return false;
			}
		}

		return true;
	}

	/**
	 * Call client methods
	 * 
	 * @param string $method
	 * @param array $params
	 * @param string $action 
	 */
	private function call($method, $params = array (), $action = '') {

		//echo '<pre>',print_r($params),'</pre>';
		//$action = $this->namespace . '/' . $method;
		$result = $this->client->call($method, $params, '', '', false, true);
		
		if(isset($result['faultcode'])){
			$this->error = $result["faultstring"];
			return false;		
		}

		// Check for a fault
		if ($this->client->fault) {
			$this->error = $result;
			return false;
		}

		// Check for errors
		$error = $this->client->getError();
		if ($error) {
			$this->error = $error;
			return false;
		}
		
		return $result;
	}

	/**
	 * Convert XML string into php object
	 * 
	 * @param string $xmlStr
	 * @return object Object with XML DOM representation
	 */
	private function _xml2Object($xmlStr){		
		$xmlStr = mb_convert_encoding($xmlStr, "UTF-8");
		return new SimpleXMLElement('<?xml version="1.0" standalone="yes"?>' . $xmlStr);
	}

	/**
	 * User log in
	 * 
	 * @access public
	 * @param int $rut Chilenian patient ID without verification digit and without dots or dashes
	 * @param char $dv Chilenian RUT verification digit
	 * @param string $password
	 */
	function userLogin($rut, $dv, $password) {
		
		$params = array(
			'Cod_Empresa' 			=> $this->companyID,
			'Cod_Sucursal'			=> $this->branchID,
			'IP_Cliente'			=> $this->ip,
			'Rut_PacienteTitular' 	=> $rut,
			'Dv_PacienteTitular'	=> $dv,
			'Clave_Paciente'		=> $password
		);
		
		$result = $this->call('WM_LogeoPaciente', $params);

		//$xmlObject = $this->_xml2Object($result['WM_LogeoPacienteResult']);
//TODO: Remove this line simulating connections
		$xmlObject = $this->_xml2Object('<XML><LogeoPaciente><InformacionLogeo><ESTADO>S</ESTADO><DESC_ESTADO>PACIENTE LOGEADO CORRECTAMENTE</DESC_ESTADO><CLAVE_TEMP>0</CLAVE_TEMP><ID_AMBULATORIO>3134429</ID_AMBULATORIO><NOMBRE_PACIENTE>MORIAL</NOMBRE_PACIENTE><APEPAT_PACIENTE>MARQUEZ</APEPAT_PACIENTE><APEMAT_PACIENTE>CHANAL</APEMAT_PACIENTE></InformacionLogeo></LogeoPaciente><Error><Error_Cod>0</Error_Cod><ErrorDesc>SIN ERRORES</ErrorDesc></Error></XML>');
		
		
		if($xmlObject->Error->Error_Cod != 0){
			$this->error = $xmlObject->Error->ErrorDesc;
			return false;
		}
		
		$loginData = new stdClass();
		$loginData->state = (string) $xmlObject->LogeoPaciente->InformacionLogeo->ESTADO;
		if($loginData->state == 'NE'){
			$this->error = 'NE';
			return false;
		}
		
		$loginData->stateName = (string) $xmlObject->LogeoPaciente->InformacionLogeo->DESC_ESTADO;
		$loginData->tmpKey = (int) $xmlObject->LogeoPaciente->InformacionLogeo->CLAVE_TEMP;
		$loginData->ambulatoryID = (int) $xmlObject->LogeoPaciente->InformacionLogeo->ID_AMBULATORIO;
		$loginData->userName = (string) ucwords(strtolower($xmlObject->LogeoPaciente->InformacionLogeo->NOMBRE_PACIENTE . ' ' . 
		$xmlObject->LogeoPaciente->InformacionLogeo->APEPAT_PACIENTE . ' ' .
		$xmlObject->LogeoPaciente->InformacionLogeo->APEMAT_PACIENTE));
		
		return $loginData;
	}
	
	/**
	 * Get the specialities list
	 * 
	 * @access public
	 * 
	 */
	function getSpecialties(){
		$params = array(
			'Cod_Empresa' 			=> $this->companyID,
			'Cod_Sucursal'			=> $this->branchID,
			'Vigencia'				=> 'S',
			'Internet' 				=> 'S'
		);
		
		$result = $this->call('WM_ObtenerEspecialidades', $params);
		$xmlObject = $this->_xml2Object($result['WM_ObtenerEspecialidadesResult']);
		
		if($xmlObject->Error->Error_Cod != 0){
			$this->error = $xmlObject->Error->ErrorDesc;
			return false;
		}
		
		$specialties = array();
		
		foreach($xmlObject->Especialidades->Especialidad as $sp){
			$ob = new stdClass();
			$ob->id 	= "$sp->COD_ITEM";
			$ob->name 	= "$sp->DESC_ITEM";
			$specialties[] = $ob;
		}
		
		return $specialties;
	}
	
	/**
	 * Get doctors by second name
	 * 
	 * @access public
	 * 
	 */	
	function getDoctorsBySecondName($secondName){
		if(!$secondName)
			return false;
			
		$params = array(
			'Cod_Empresa' 			=> $this->companyID,
			'Cod_Sucursal'			=> $this->branchID,
			'ApelPat_prof'			=> strtoupper($secondName)
		);
		
		
		$result = $this->call('WM_ObtenerAgendaXApellido', $params);
		$xmlObject = $this->_xml2Object($result['WM_ObtenerAgendaXApellidoResult']);

		if($xmlObject->Error->Error_Cod != 0){
			$this->error = $xmlObject->Error->ErrorDesc;
			return false;
		}

		if(!count($xmlObject->AgendaApellido->Datos)){
			return array();
		}
		
		$doctors = array();
		foreach($xmlObject->AgendaApellido->Datos as $data){
			$doctors[] = $data;
		}
		
		return $doctors;
	}

	/**
	 * Get doctors by specialty
	 * 
	 * @access public
	 * @param string $idSpecialty
	 */
	function getDoctorsBySpecialtyId($idSpecialty){
		if(!$idSpecialty)
			return false;
			
		$params = array(
			'Cod_Empresa' 			=> $this->companyID,
			'Cod_Sucursal'			=> $this->branchID,
			'Cod_Especialidad'			=> $idSpecialty
		);
		
		$result = $this->call('WM_ObtenerAgendaEspecialidad', $params);
		$xmlObject = $this->_xml2Object($result['WM_ObtenerAgendaEspecialidadResult']);

		if($xmlObject->Error->Error_Cod != 0){
			$this->error = $xmlObject->Error->ErrorDesc;
			return false;
		}

		if(!count($xmlObject->AgendaEspecialidad->Datos)){
			return array();
		}
		
		$doctors = array();
		foreach($xmlObject->AgendaEspecialidad->Datos as $data){
			$doctors[] = $data;
		}
		
		return $doctors;
	}
	
	/**
	 * Get patient
	 * 
	 * @access public
	 * @param int $rut Without dashes, dots and verify digit
	 * @param int $dv Verify Digit
	 */
	function getPatient($rut, $dv){
		
		if(!$rut || !$dv){
			return false;
		}
		
		$params = array(
			'Cod_Empresa' 			=> $this->companyID,
			'Cod_Sucursal'			=> $this->branchID,
			'Rut_Paciente'			=> "$rut",
			'Dv_Paciente' 			=> "$dv"
		);
		
		$result = $this->call('WM_BuscaPacienteTitular', $params);
	
		var_dump($result);
		die();
		
	}
	
	function getFamilyMembers($familyGroupId){
		$params = array(
			'Cod_Empresa' 			=> $this->companyID,
			//'Cod_Sucursal'			=> $this->branchID,
			'Id_GrupoFamiliar'		=> $familyGroupId
		);
		
		$result = $this->call('WM_BuscaCargas', $params);
		
		$xmlObject = $this->_xml2Object($result['WM_BuscaCargasResult']);
		
		if($xmlObject->Error->Error_Cod != 0){
			$this->error = $xmlObject->Error->ErrorDesc;
			return false;
		}

		if(!count($xmlObject->CargasAsociadas->InfoCarga)){
			return array();
		}
		
		$fm = array();
		foreach($xmlObject->CargasAsociadas->InfoCarga as $data){
			$fm[] = $data;
		}
		
		return $fm;
	}

	/**
	 * Get the Communes list
	 * 
	 * @access public
	 * 
	 */	
	function getCommunes(){
		
		$params = array(
			'Cod_Empresa' 		=> $this->companyID,
			'Cod_Sucursal'	=> $this->branchID,
			'User'				=> ''
		);
		
		$result = $this->call('WM_ObtenerComunas', $params);
		
		$xmlObject = $this->_xml2Object($result['WM_ObtenerComunasResult']);
		
		if($xmlObject->Error->Error_Cod != 0){
			$this->error = $xmlObject->Error->ErrorDesc;
			return false;
		}
		
		$comunas = array();
		
		foreach($xmlObject->Comunas->Comuna as $sp){
			$ob = new stdClass();
			$ob->id 	= "$sp->CODCOMUNA";
			$ob->name 	= "$sp->DESCCOMUNA";
			$comunas[] = $ob;
		}
		
		return $comunas;		
	}

	/**
	 * Register a patient
	 * 
	 * @access public
	 * @param array $data (
	 *					[Rut_Paciente]
	 *					[Dv_Paciente]
	 *					[Fechanac_Paciente]
	 *					[Nombre_Paciente]
	 *					[Apepat_Paciente]
	 *					[Apemat_Paciente]
	 * 					[Direccion_Paciente]
	 *					[Comuna_Paciente]
	 *					[Ciudad_Paciente]
	 *					[prefijo_Fono1_Paciente]
	 *					[Fono1_Paciente]
	 *					[prefijo_Fono2_Paciente]
	 *					[Fono2_Paciente]
	 *					[PrefMovil1]
	 *					[FonoMovil1]
	 *					[PrefMovil2]
	 *					[FonoMovil2]
	 *					[Email_Paciente]
	 *					[Sexo_Paciente]
	 *					[Prevision_Paciente]
	 *					[SMS_notificacion]
	 *					[EMAIL_notificacion]
	 *					[Op_InfoClinica]
	 *					[Clave_Usuario]
	 *					[Pregunta_Clave]
	 *					[RespuestaClave]
	 *				)
	 **/	
	function registerPatient($data){
		
		$params = array(
			'Tipo_Usuario' => 'PACIENTE',
			'IP_Cliente' => $this->ip,
			'Usuario' => $this->webUser,
			'Accion' => 'I',
			'Cod_Empresa' => $this->companyID,
			'Cod_Sucursal' => $this->branchID,
			'Id_Ambulatorio' => '11111111',
			'Estado' => 'D'
		);
		
		$Op_InfoClinica = $data['Op_InfoClinica'];
		$SMS_notificacion = $data['SMS_notificacion'];
		$EMAIL_notificacion = $data['EMAIL_notificacion'];
		unset($data['SMS_notificacion']);
		unset($data['EMAIL_notificacion']);
		unset($data['Op_InfoClinica']);
		
		foreach($data as $field => $value){
			$params[$field] = ($field != 'Clave_Usuario') ? strtoupper($value) : $value;
		}	
		
		//debug_var($params);
		$result = $this->registerUser($params);
		
		if(!$result){
			return false;
		}
		
		if(!$result->estado){
			$this->error = $result->descEstado;
			return false;	
		}
		
		return $result->idAmbulatorio;
	}
	
	private function registerUser($params){
		$result = $this->call('WM_MantUsuario', $params);
		
		//TODO: Remove this assignation 
		$result['WM_MantUsuarioResult'] = '<XML>
					<MantUsuarios>
						<Datos>
							<ESTADO>S</ESTADO>
							<DESC_ESTADO>GRABACION NUEVO REGISTRO WEB EXITOSA</DESC_ESTADO>
							<ID_AMB_INGRESO>3061476</ID_AMB_INGRESO>
						</Datos>
					</MantUsuarios>
					<Mensaje>
						<CodMensaje>3</CodMensaje>
						<DescMensaje></DescMensaje>
					</Mensaje>
					<Error>
						<Error_Cod>0</Error_Cod>
						<ErrorDesc>SIN ERRORES</ErrorDesc>
					</Error>
				</XML>';
		
		if(!$result){
			return false;
		}
		
		$xmlObject = $this->_xml2Object($result['WM_MantUsuarioResult']);
		
		//echo '<pre>',print_r($xmlObject),'</pre>';
		$result = new stdClass();
		$result->idAmbulatorio = (string) $xmlObject->MantUsuarios->Datos->ID_AMB_INGRESO;
		$result->descEstado = (string) $xmlObject->MantUsuarios->Datos->DESC_ESTADO;
		$result->estado = ($result->idAmbulatorio) ? TRUE : FALSE;
		return $result;		
	}
	
	
	/**
	 * Updates the user messagin options
	 * 
	 * @access public
	 * @param array $data [Id_Paciente, ]
	 * @param int id_ambulatorio
	 */	
	private function updateMessagingOptions($data){
		
		$result = $this->call('WM_ActualizaOpcionesMensajeria', $params);
		
	}

	/**
	 * Get the user data
	 * 
	 * @access public
	 * @param array $data [rut, dv]
	 * @param int id_ambulatorio
	 */	
	public function getUserInfo($data){

		$params = array(
			'Usuario' => $this->webUser,
			'Accion' => 'I',
			'Cod_Empresa' => $this->companyID,
			'Cod_Sucursal' => $this->branchID,
			'Rut_Paciente' => $data['rut'],
			'Dv_Paciente' => $data['dv']
		);
		
		$result = $this->call('WM_BuscaPacienteTitular', $params);
		
		if(!$result){
			return false;
		}
		
		$xmlObject = $this->_xml2Object($result['WM_BuscaPacienteTitularResult']);
		
		
		$vars = get_object_vars ( $xmlObject->Paciente->DatosPaciente );
		
		if($vars['ESTADO'] == 'NE'){
			$this->error = $vars['DESC_ESTADO'];
			return false;
		}
		
		foreach($vars as $i => $val){
			$userData[strtolower($i)] = (string) $val;
		}
		
		return $userData;
		
	}
}
?>
