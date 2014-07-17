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
	//private $host = 'http://reservas.davila.cl/Ws_ReservaHorasWeb/ResHoraWeb/ResHoraWeb.asmx?wsdl';
	private $host = 'http://reservas.davila.cl/Age_Ws_Reserva_Horas_demo/ResHoraWeb.asmx?wsdl';
	
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

		$xmlObject = $this->_xml2Object($result['WM_LogeoPacienteResult']);
//TODO: Remove this line simulating connections
		//$xmlObject = $this->_xml2Object('<XML><LogeoPaciente><InformacionLogeo><ESTADO>S</ESTADO><DESC_ESTADO>PACIENTE LOGEADO CORRECTAMENTE</DESC_ESTADO><CLAVE_TEMP>0</CLAVE_TEMP><ID_AMBULATORIO>3134429</ID_AMBULATORIO><NOMBRE_PACIENTE>MORIAL</NOMBRE_PACIENTE><APEPAT_PACIENTE>MARQUEZ</APEPAT_PACIENTE><APEMAT_PACIENTE>CHANAL</APEMAT_PACIENTE></InformacionLogeo></LogeoPaciente><Error><Error_Cod>0</Error_Cod><ErrorDesc>SIN ERRORES</ErrorDesc></Error></XML>');
		
		
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
		//var_dump($result);
		//die();
		
	}
	
	function getFamilyMembers($rut, $dv, $familyGroupId){
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
		
		$fm = $member = array();
		
		foreach($xmlObject->CargasAsociadas->InfoCarga as $data){
			foreach($data as $i => $val){
				$member[strtolower($i)] = (string) $val;
			}
			$fm[] = $member;
			$member = array();
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
	
	
	/**
	 * Get available dates by a doctor
	 * 
	 * @access public
	 * @param int $codUnit Code of Unit 
	 * @param int $codSpec Code of Speciality
	 * @param int $codProfessional Code of the doctor
	 * @param int $corrSchedule Code of schedule
	 * @param string $nextAvDate Next Available data Ex: 2014-08-04T16:45:00-04:00
	 */	
	function getAvailableDatesByDoctor($codUnit, $codSpec, $codProfessional, $corrSchedule, $nextAvDate){

		$params = array(
			'Cod_Empresa' => $this->companyID,
			'Cod_Sucursal' => $this->branchID,
			'Cod_Unidad' => $codUnit,
			'Cod_Especialidad' => $codSpec,
			'Cod_Prof' => $codProfessional,
			'Corr_Agenda' => $corrSchedule,
			'Fecha_ProximaHora' => $nextAvDate
		);
		
		$result = $this->call('WM_ObtenerAgendaProfesional', $params);
		if(!isset($result['WM_ObtenerAgendaProfesionalResult'])){
			return null;
		}
		
		$xmlObject = $this->_xml2Object( str_replace(';90', '', $result['WM_ObtenerAgendaProfesionalResult']));
		
		//$xmlObject = $this->_xml2Object( '<XML><CalendarioProfesiona><Fecha Estado="2" Dia="04-08-2014"><DetalleDia><Hora>13:00</Hora><NombrePaciente>CAÑOLES O. MARIA IGNACIA</NombrePaciente><CodigoPaciente>2457003</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>13:15</Hora><NombrePaciente>PACHECO V. ROMINA NICOLE</NombrePaciente><CodigoPaciente>2494109</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>13:30</Hora><NombrePaciente>OSSES R. GABRIELA GIANINNA</NombrePaciente><CodigoPaciente>2452623</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>13:45</Hora><NombrePaciente>HERRERA R. MARIA ANGELICA</NombrePaciente><CodigoPaciente>1282784</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>14:00</Hora><NombrePaciente>CARMONA A. GISSELLE CAROLINA</NombrePaciente><CodigoPaciente>2220580</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>14:15</Hora><NombrePaciente>ZAMORA M. CAROLINA</NombrePaciente><CodigoPaciente>3040257</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>14:30</Hora><NombrePaciente>ORTEGA S. XAVIERA ANDREA</NombrePaciente><CodigoPaciente>2184801</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>14:45</Hora><NombrePaciente>ESPINOZA R. CATALINA CONSUELO</NombrePaciente><CodigoPaciente>2218579</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>15:00</Hora><NombrePaciente>MORALES A. KATHYA ANDREA</NombrePaciente><CodigoPaciente>2454417</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>15:15</Hora><NombrePaciente>MANZILLA M. ERITA MERY</NombrePaciente><CodigoPaciente>3162760</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>15:30</Hora><NombrePaciente>PEREZ Z. NICOLE DEL PILAR</NombrePaciente><CodigoPaciente>1926848</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>15:45</Hora><NombrePaciente>SANDOVAL D. KARLA PATRICIA</NombrePaciente><CodigoPaciente>2326510</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>16:00</Hora><NombrePaciente>ESPINOZA G. VANESSA ANGELICA</NombrePaciente><CodigoPaciente>3158148</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>16:15</Hora><NombrePaciente>MENDEZ G. MARTA ROSSANA</NombrePaciente><CodigoPaciente>979446</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>16:30</Hora><NombrePaciente>NEIRA T. ANGELICA VIVIANA</NombrePaciente><CodigoPaciente>2473829</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>16:45</Hora><NombrePaciente>RABANAL S. MELVA</NombrePaciente><CodigoPaciente>2329646</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>17:00</Hora><NombrePaciente>TAPIA C. PAULINA CRISTINA</NombrePaciente><CodigoPaciente>1049979</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>17:15</Hora><NombrePaciente>SOTO C. PATRICIA ALEJANDRA</NombrePaciente><CodigoPaciente>981060</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>17:30</Hora><NombrePaciente>CORVALAN M. PATRICIA ANGELICA</NombrePaciente><CodigoPaciente>1560050</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>17:45</Hora><NombrePaciente>SILVA P. EVELYN ANDREA</NombrePaciente><CodigoPaciente>2854385</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>18:00</Hora><NombrePaciente>RODRIGUEZ L. VIVIANA ALEJANDRA</NombrePaciente><CodigoPaciente>1551127</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>18:15</Hora><NombrePaciente>ILLANES I. MARCELA DEL CARMEN</NombrePaciente><CodigoPaciente>1732704</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>18:30</Hora><NombrePaciente>VALVERDE V. LILIANA PAOLA</NombrePaciente><CodigoPaciente>2440767</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>18:45</Hora><NombrePaciente>VERGARA V. CAROLINA PAZ</NombrePaciente><CodigoPaciente>2450128</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:00</Hora><NombrePaciente>ESPINOSA E. JULIETA VICTORIA</NombrePaciente><CodigoPaciente>2764938</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:15</Hora><NombrePaciente>CONTRERAS M. PATRICIA ALEJANDRA</NombrePaciente><CodigoPaciente>2061466</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:30</Hora><NombrePaciente>DIAZ V. KATHERINE ELIZABETH</NombrePaciente><CodigoPaciente>2449191</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:45</Hora><NombrePaciente>IBARRA P. NICOLE ALEJANDRA</NombrePaciente><CodigoPaciente>205270</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia></Fecha><Fecha Estado="0" Dia="05-08-2014"><DetalleDia/></Fecha><Fecha Estado="2" Dia="06-08-2014"><DetalleDia><Hora>13:00</Hora><NombrePaciente>LOBOS L. JEANNETTE ELIZABETH</NombrePaciente><CodigoPaciente>2426070</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>13:15</Hora><NombrePaciente>CARDENAS A. KATTIA GIULIANA</NombrePaciente><CodigoPaciente>2916805</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>13:30</Hora><NombrePaciente>ALVARADO S. ESTELA ANGELICA</NombrePaciente><CodigoPaciente>2171946</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>13:45</Hora><NombrePaciente>POZO T. CLAUDIA ALEJANDRA</NombrePaciente><CodigoPaciente>2147244</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>14:00</Hora><NombrePaciente>PEREZ F. BARBARA DEL CARMEN</NombrePaciente><CodigoPaciente>3090215</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>14:15</Hora><NombrePaciente>ALVAREZ N. MARIA SALOME</NombrePaciente><CodigoPaciente>1782171</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>14:30</Hora><NombrePaciente>FARFAN O. VICTORIA MARGARITA</NombrePaciente><CodigoPaciente>2249886</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>14:45</Hora><NombrePaciente>SANCHEZ C. CONSTANZA BELEN</NombrePaciente><CodigoPaciente>1045993</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>15:00</Hora><NombrePaciente>SEPULVEDA H. DANITZA BELEN</NombrePaciente><CodigoPaciente>2623990</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>15:15</Hora><NombrePaciente>GONZALEZ L. MURIEL CAROLINA</NombrePaciente><CodigoPaciente>2811027</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>15:30</Hora><NombrePaciente>JAÑA M. CECILIA PILAR</NombrePaciente><CodigoPaciente>3162785</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>15:45</Hora><NombrePaciente>MONSALVE G. INES</NombrePaciente><CodigoPaciente>2153350</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia></Fecha><Fecha Estado="1" Dia="07-08-2014"><DetalleDia><Hora>16:30</Hora><NombrePaciente>VEAS C. CATHERINE ANDREA</NombrePaciente><CodigoPaciente>2900734</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>16:45</Hora><NombrePaciente>ARISMENDI E. DEBORA NICOLE</NombrePaciente><CodigoPaciente>2060248</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>17:00</Hora><NombrePaciente>CACERES S. YESSENIA EUGENIA</NombrePaciente><CodigoPaciente>2399317</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>17:15</Hora><NombrePaciente>MAÑAN J. NATALY SOFIA</NombrePaciente><CodigoPaciente>2155868</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>17:30</Hora><NombrePaciente>RODRIGUEZ M. CAMILA ANDREA</NombrePaciente><CodigoPaciente>2155993</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>17:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>18:00</Hora><NombrePaciente>ARAYA G. CARLA FRANCISCA</NombrePaciente><CodigoPaciente>1363666</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>18:15</Hora><NombrePaciente>VILCHES E. CAROLINA DE LOS ANGELES</NombrePaciente><CodigoPaciente>1727868</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>18:30</Hora><NombrePaciente>OSSES R. GABRIELA GIANINNA</NombrePaciente><CodigoPaciente>2452623</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>18:45</Hora><NombrePaciente>GUTIERREZ R. KARIM JUDITH</NombrePaciente><CodigoPaciente>1887777</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:00</Hora><NombrePaciente>ILLANES I. MARCELA DEL CARMEN</NombrePaciente><CodigoPaciente>1732704</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:15</Hora><NombrePaciente>PEREZ O. ELIZABETH ALEJANDRA</NombrePaciente><CodigoPaciente>30103</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:30</Hora><NombrePaciente>DIAZ V. KATHERINE ELIZABETH</NombrePaciente><CodigoPaciente>2449191</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:45</Hora><NombrePaciente>FUENTES S. NATALIA CECILIA</NombrePaciente><CodigoPaciente>2355492</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia></Fecha><Fecha Estado="0" Dia="08-08-2014"><DetalleDia/></Fecha><Fecha Estado="0" Dia="09-08-2014"><DetalleDia/></Fecha><Fecha Estado="0" Dia="10-08-2014"><DetalleDia/></Fecha><Fecha Estado="1" Dia="11-08-2014"><DetalleDia><Hora>13:00</Hora><NombrePaciente>MIRANDA A. ALEXANDRA ARACELLI</NombrePaciente><CodigoPaciente>3130292</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>13:15</Hora><NombrePaciente>SUAREZ A. CATALINA FERNANDA</NombrePaciente><CodigoPaciente>3198865</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>13:30</Hora><NombrePaciente>GUERRERO S. CRISTINA ANDREA</NombrePaciente><CodigoPaciente>2092532</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>13:45</Hora><NombrePaciente>HERNANDEZ V. ELBA DEL PILAR</NombrePaciente><CodigoPaciente>3153408</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>14:00</Hora><NombrePaciente>HERRERA Z. CAROLINA  ANDREA</NombrePaciente><CodigoPaciente>2164066</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>14:15</Hora><NombrePaciente>FARIAS B. PALOMA</NombrePaciente><CodigoPaciente>3182670</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>14:30</Hora><NombrePaciente>ASTORGA A. GABRIELA ALEJANDRA</NombrePaciente><CodigoPaciente>2173112</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>14:45</Hora><NombrePaciente>BESSOLO L. SARA DEL CARMEN</NombrePaciente><CodigoPaciente>1472586</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>15:00</Hora><NombrePaciente>RIVERA A. JOSEFINA</NombrePaciente><CodigoPaciente>2308271</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>15:15</Hora><NombrePaciente>HERNANDEZ H. DOLLY ABIGAIL</NombrePaciente><CodigoPaciente>2157422</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>15:30</Hora><NombrePaciente>MORE M. NATALIA ADRIANA</NombrePaciente><CodigoPaciente>1040514</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>15:45</Hora><NombrePaciente>RAMIREZ G. ROXANA ESPERANZA</NombrePaciente><CodigoPaciente>3182113</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>16:00</Hora><NombrePaciente>GALVEZ S. KATERINE  ANDREA</NombrePaciente><CodigoPaciente>2646474</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>16:15</Hora><NombrePaciente>ORTIZ L. TAMARA SILVINA</NombrePaciente><CodigoPaciente>3036756</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>16:30</Hora><NombrePaciente>CERDA A. LESLIE</NombrePaciente><CodigoPaciente>2323979</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>16:45</Hora><NombrePaciente>SILVA B. KATHERINE JACQUELINE</NombrePaciente><CodigoPaciente>2060181</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>17:00</Hora><NombrePaciente>GONZALEZ M. CAROLINA ANDREA</NombrePaciente><CodigoPaciente>2945759</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>17:15</Hora><NombrePaciente>RIVERO H. FRANCIA DESIREE</NombrePaciente><CodigoPaciente>2940439</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>17:30</Hora><NombrePaciente>VEGA M. SCARLETT  DENISSE</NombrePaciente><CodigoPaciente>1790076</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>17:45</Hora><NombrePaciente>SILVA A. CAROLINA ISABEL</NombrePaciente><CodigoPaciente>3133534</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>18:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>18:15</Hora><NombrePaciente>CARREÑO B. PAMELA ALEJANDRA</NombrePaciente><CodigoPaciente>2090030</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>18:30</Hora><NombrePaciente>PEREZ A. CLAUDIA JESUS</NombrePaciente><CodigoPaciente>2467238</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>18:45</Hora><NombrePaciente>GUEVARA G. NIDYA MILENA</NombrePaciente><CodigoPaciente>2935121</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:00</Hora><NombrePaciente>QUIROZ F. PAMELA ALEJANDRA</NombrePaciente><CodigoPaciente>2639924</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:15</Hora><NombrePaciente>MEYNARD V. CONSTANZA</NombrePaciente><CodigoPaciente>3166547</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:30</Hora><NombrePaciente>VERA T. MARIA JOSE</NombrePaciente><CodigoPaciente>3028155</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:45</Hora><NombrePaciente>NUÑEZ T. LATIFE ZENAIDA</NombrePaciente><CodigoPaciente>2452370</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia></Fecha><Fecha Estado="0" Dia="12-08-2014"><DetalleDia/></Fecha><Fecha Estado="2" Dia="13-08-2014"><DetalleDia><Hora>13:00</Hora><NombrePaciente>ORELLANA L. ANA CAROL</NombrePaciente><CodigoPaciente>3122129</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>13:15</Hora><NombrePaciente>CASTILLO A. KARINA ELIZABETH</NombrePaciente><CodigoPaciente>3080936</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>13:30</Hora><NombrePaciente>BRANDT M. ANDREA CECILIA</NombrePaciente><CodigoPaciente>2093712</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>13:45</Hora><NombrePaciente>MARTINEZ S. VALENTINA PAZ</NombrePaciente><CodigoPaciente>2511491</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>14:00</Hora><NombrePaciente>CABRERA P. MARIA JOSE</NombrePaciente><CodigoPaciente>3023959</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>14:15</Hora><NombrePaciente>TRONCOSO M. MARIA OLGA</NombrePaciente><CodigoPaciente>2279312</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>14:30</Hora><NombrePaciente>DUMONT C. NICOLE</NombrePaciente><CodigoPaciente>2375059</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>14:45</Hora><NombrePaciente>ALVAREZ S. MARCELA</NombrePaciente><CodigoPaciente>3202230</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>15:00</Hora><NombrePaciente>JARAMILLO F. KATHERINE SAMANTHA</NombrePaciente><CodigoPaciente>3164980</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>15:15</Hora><NombrePaciente>INOSTROZA A. VALERIA</NombrePaciente><CodigoPaciente>2477236</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>15:30</Hora><NombrePaciente>NIÑOLES R. JAQUELINE ALEJANDRA</NombrePaciente><CodigoPaciente>3154500</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>15:45</Hora><NombrePaciente>CASANGA R. BERENICE PATRICIA</NombrePaciente><CodigoPaciente>2288570</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia></Fecha><Fecha Estado="1" Dia="14-08-2014"><DetalleDia><Hora>16:30</Hora><NombrePaciente>LILLO C. ROMINA CAROL</NombrePaciente><CodigoPaciente>1929408</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>16:45</Hora><NombrePaciente>DIAZ C. ALICIA GABRIELA</NombrePaciente><CodigoPaciente>3152390</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>17:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>17:15</Hora><NombrePaciente>PERALTA A. JOHANA DEL CARMEN</NombrePaciente><CodigoPaciente>2271256</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>17:30</Hora><NombrePaciente>DURAN O. SARAI SARA</NombrePaciente><CodigoPaciente>2144321</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>17:45</Hora><NombrePaciente>VILARO G. ISABEL</NombrePaciente><CodigoPaciente>3202171</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>18:00</Hora><NombrePaciente>CANTALLOPTS C. DANIELA PAZ</NombrePaciente><CodigoPaciente>1550940</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>18:15</Hora><NombrePaciente>VIDAL D. CLAUDIA ANDREA</NombrePaciente><CodigoPaciente>3200040</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>18:30</Hora><NombrePaciente>IBARRA A. CATALINA ANDREA</NombrePaciente><CodigoPaciente>3058727</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>18:45</Hora><NombrePaciente>PEREZ O. ELIZABETH ALEJANDRA</NombrePaciente><CodigoPaciente>30103</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:00</Hora><NombrePaciente>ARANEDA R. XIMENA DEL CARM</NombrePaciente><CodigoPaciente>1922694</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:15</Hora><NombrePaciente>ARANEDA R. XIMENA DEL CARM</NombrePaciente><CodigoPaciente>1922694</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:30</Hora><NombrePaciente>SALAZAR P. CINDY EYLEEN</NombrePaciente><CodigoPaciente>938655</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:45</Hora><NombrePaciente>IBARRA P. NICOLE ALEJANDRA</NombrePaciente><CodigoPaciente>205270</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia></Fecha><Fecha Estado="0" Dia="15-08-2014"><DetalleDia/></Fecha><Fecha Estado="0" Dia="16-08-2014"><DetalleDia/></Fecha><Fecha Estado="0" Dia="17-08-2014"><DetalleDia/></Fecha><Fecha Estado="1" Dia="18-08-2014"><DetalleDia><Hora>13:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>13:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>13:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>13:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>14:00</Hora><NombrePaciente>ORTEGA S. XAVIERA ANDREA</NombrePaciente><CodigoPaciente>2184801</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>14:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>14:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>14:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>15:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>15:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>15:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>15:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>16:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>16:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>16:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>16:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>17:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>17:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>17:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>17:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>18:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>18:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>18:30</Hora><NombrePaciente>CACERES S. YESSENIA EUGENIA</NombrePaciente><CodigoPaciente>2399317</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>18:45</Hora><NombrePaciente>FUENTES M. MELANIE FRANCISCA</NombrePaciente><CodigoPaciente>2357612</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:00</Hora><NombrePaciente>GALLEGUILLOS T. ELENA</NombrePaciente><CodigoPaciente>1401841</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:15</Hora><NombrePaciente>CATALAN S. NATHALY ANDREA</NombrePaciente><CodigoPaciente>3058906</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:30</Hora><NombrePaciente>SILVA P. EVELYN ANDREA</NombrePaciente><CodigoPaciente>2854385</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:45</Hora><NombrePaciente>QUIROZ F. PAMELA ALEJANDRA</NombrePaciente><CodigoPaciente>2639924</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia></Fecha><Fecha Estado="0" Dia="19-08-2014"><DetalleDia/></Fecha><Fecha Estado="1" Dia="20-08-2014"><DetalleDia><Hora>13:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>13:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>13:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>13:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>14:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>14:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>14:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>14:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>15:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>15:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>15:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>15:45</Hora><NombrePaciente>SILVA R. CARLA PATRICIA</NombrePaciente><CodigoPaciente>3194980</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia></Fecha><Fecha Estado="1" Dia="21-08-2014"><DetalleDia><Hora>16:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>16:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>17:00</Hora><NombrePaciente>ROJAS F. MARILYN ALEJANDRA</NombrePaciente><CodigoPaciente>1866040</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>17:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>17:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>17:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>18:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>18:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>18:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>18:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>19:00</Hora><NombrePaciente>GUERRERO S. CRISTINA ANDREA</NombrePaciente><CodigoPaciente>2092532</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:15</Hora><NombrePaciente>IBARRA P. NICOLE ALEJANDRA</NombrePaciente><CodigoPaciente>205270</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:30</Hora><NombrePaciente>DIAZ V. KATHERINE ELIZABETH</NombrePaciente><CodigoPaciente>2449191</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:45</Hora><NombrePaciente>PEREZ O. ELIZABETH ALEJANDRA</NombrePaciente><CodigoPaciente>30103</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia></Fecha><Fecha Estado="0" Dia="22-08-2014"><DetalleDia/></Fecha><Fecha Estado="0" Dia="23-08-2014"><DetalleDia/></Fecha><Fecha Estado="0" Dia="24-08-2014"><DetalleDia/></Fecha><Fecha Estado="1" Dia="25-08-2014"><DetalleDia><Hora>13:00</Hora><NombrePaciente>MIRANDA R. GERALDINE MARISOL</NombrePaciente><CodigoPaciente>2456627</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>13:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>13:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>13:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>14:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>14:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>14:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>14:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>15:00</Hora><NombrePaciente>MUÑOZ P. MARIA CATALINA</NombrePaciente><CodigoPaciente>468087</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>15:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>15:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>15:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>16:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>16:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>16:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>16:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>17:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>17:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>17:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>17:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>18:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>18:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>18:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>18:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>19:00</Hora><NombrePaciente>ILLANES I. MARCELA DEL CARMEN</NombrePaciente><CodigoPaciente>1732704</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:15</Hora><NombrePaciente>RODRIGUEZ B. CONSTANZA</NombrePaciente><CodigoPaciente>2083888</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:30</Hora><NombrePaciente>DIAZ V. KATHERINE ELIZABETH</NombrePaciente><CodigoPaciente>2449191</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:45</Hora><NombrePaciente>SILVA P. EVELYN ANDREA</NombrePaciente><CodigoPaciente>2854385</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia></Fecha><Fecha Estado="0" Dia="26-08-2014"><DetalleDia/></Fecha><Fecha Estado="1" Dia="27-08-2014"><DetalleDia><Hora>13:00</Hora><NombrePaciente>GARRIDO G. FRANCISCA NATALIA</NombrePaciente><CodigoPaciente>1809401</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>13:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>13:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>13:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>14:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>14:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>14:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>14:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>15:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>15:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>15:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>15:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia></Fecha><Fecha Estado="1" Dia="28-08-2014"><DetalleDia><Hora>16:30</Hora><NombrePaciente>DIAZ C. ALICIA GABRIELA</NombrePaciente><CodigoPaciente>3152390</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>16:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>17:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>17:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>17:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>17:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>18:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>18:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>18:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>18:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>19:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>19:15</Hora><NombrePaciente>CATALAN S. NATHALY ANDREA</NombrePaciente><CodigoPaciente>3058906</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:30</Hora><NombrePaciente>DIAZ V. KATHERINE ELIZABETH</NombrePaciente><CodigoPaciente>2449191</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:45</Hora><NombrePaciente>IBARRA P. NICOLE ALEJANDRA</NombrePaciente><CodigoPaciente>205270</CodigoPaciente><CorrelativoHorario>201417</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia></Fecha><Fecha Estado="0" Dia="29-08-2014"><DetalleDia/></Fecha><Fecha Estado="0" Dia="30-08-2014"><DetalleDia/></Fecha><Fecha Estado="0" Dia="31-08-2014"><DetalleDia/></Fecha><Fecha Estado="1" Dia="01-09-2014"><DetalleDia><Hora>13:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>13:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>13:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>13:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>14:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>14:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>14:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>14:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>15:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>15:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>15:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>15:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>16:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>16:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>16:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>16:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>17:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>17:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>17:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>17:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>18:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>18:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>18:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>18:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>19:00</Hora><NombrePaciente>IBARRA P. NICOLE ALEJANDRA</NombrePaciente><CodigoPaciente>205270</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>19:30</Hora><NombrePaciente>ILLANES I. MARCELA DEL CARMEN</NombrePaciente><CodigoPaciente>1732704</CodigoPaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>19:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201415</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia></Fecha><Fecha Estado="0" Dia="02-09-2014"><DetalleDia/></Fecha><Fecha Estado="1" Dia="03-09-2014"><DetalleDia><Hora>13:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>13:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>13:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>13:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>14:00</Hora><NombrePaciente>CARMONA A. GISSELLE CAROLINA</NombrePaciente><CodigoPaciente>2220580</CodigoPaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>1</Lleno></DetalleDia><DetalleDia><Hora>14:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>14:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>14:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>15:00</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>15:15</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>15:30</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia><DetalleDia><Hora>15:45</Hora><NombrePaciente xml:space="preserve"> </NombrePaciente><CorrelativoHorario>201416</CorrelativoHorario><Box>59</Box><Multiplicidad>1</Multiplicidad><Lleno>0</Lleno></DetalleDia></Fecha></CalendarioProfesiona></XML>' );		
		
		$available_dates = array();
		foreach($xmlObject->CalendarioProfesiona->Fecha as $date){
			$state = (int) $date['Estado'];
			if($state == 1){
				$av_date = (string) $date['Dia'];
				$available_dates[$av_date] = array();
				foreach($date as $hours){
					$full = (int) $hours->Lleno;
					if(!$full){
						$available_dates[$av_date][] = array(
							'time' => (string) $hours->Hora,
							'id_schedule' => (int) $hours->CorrelativoHorario,
							'box' => (string) $hours->Box,
							'multiplicity' => (string) $hours->Multiplicidad
						);
					}
				}
			}
		}
		
		return $available_dates;
	}

	/**
	 * Get the Companu Info list
	 * 
	 * @access public
	 * 
	 */
	function getCompanyInfo(){
		$params = array(
			'Cod_Empresa' 			=> $this->companyID
		);
		
		$result = $this->call('WM_ObtenerInfoEmpresa', $params);
		$xmlObject = $this->_xml2Object($result['WM_ObtenerInfoEmpresaResult']);
		
		if($xmlObject->Error->Error_Cod != 0){
			$this->error = $xmlObject->Error->ErrorDesc;
			return false;
		}
		
		$data = array(
			'company_name' 		=> (string) $xmlObject->Empresa->Datos->DESCRIPCION,
			'company_address' 	=> (string) $xmlObject->Empresa->Datos->DIRECCION,
			'company_phone' 	=> (string) $xmlObject->Empresa->Datos->TELEFONO,
			'company_city' 		=> (string) $xmlObject->Empresa->Datos->CIUDAD,
			'company_country' 	=> (string) $xmlObject->Empresa->Datos->PAIS,
			'company_extra' 	=> (string) $xmlObject->Empresa->Datos->DATOS_EMPRESA,
			
		);
		
		return $data;
	}
		
	/**
	 * Get reserved dates by a doctor
	 * 
	 * @access public
	 * @param array $data [rut, dv]
	 * @param int id_ambulatorio
	 */	
	function getReservedDatesByDoctor(){	
	}
	
	/**
	 * Get reserved dates by a patient
	 * 
	 * @access public
	 * @param array $data [rut, dv]
	 * @param int id_ambulatorio
	 */	
	function getReservedDatesByPatient(){
	}

	/**
	 * Reserve dates
	 * 
	 * @access public
	 * @param array $params
	 * 		[Cod_Sucursal]
	 * 		[Cod_CentroMedico]
	 * 		[Cod_Especialidad]
	 * 		[Cod_Unidad]
	 * 		[Cod_Medico]
	 * 		[Corr_Agenda]
	 * 		[Cod_Isapre]
	 * 		[Box]
	 * 		[Multi]
	 * 		[Id_Paciente_Titular]
	 * 		[Id_Paciente]
	 * 		[Corr_Horario]
	 * 		[Fecha_Reserva] Format: mm/dd/YYYY
	 * 		[Hora_Reserva] Format: hh:mm
	 * 		[Prox_HoraLibre]
	 */	


	 
	function bookAppointment($params){

		$params['Cod_Empresa'] = $this->companyID;
		$params['Usuario'] = $this->webUser;
		$params['IP_Cliente'] = $this->ip;
		$params['Mod_Reserva'] = 'R';
		
		//$result = $this->call('WM_ReservaHora', $params);
		
		$result['WM_ReservaHoraResult'] = '<XML><ReservaHora><Datos_Reserva><COD_ESTADO>80</COD_ESTADO><DESC_ESTADO>La reserva serácancelada.La cantidad de horas reservadas excede la máxima permitida.</DESC_ESTADO></Datos_Reserva></ReservaHora><Mensaje><CodMensaje>0</CodMensaje><DescMensaje></DescMensaje></Mensaje><Error><Error_Cod>0</Error_Cod><ErrorDesc>SIN ERRORES</ErrorDesc></Error></XML>';
		//$result['WM_ReservaHoraResult'] = '<XML> <ReservaHora> <PrestacionesAdicionales> <INDICADOR_PRESADIC>0</INDICADOR_PRESADIC> <COD_PRESTACION>03-91-692</COD_PRESTACION> <NOMBRE_PRES>COCAINA (DROGAS DE ABUSO)</NOMBRE_PRES> <VALOR_ACTUAL>40000</VALOR_ACTUAL> <MENSAJE_WEB xml:space="preserve"> </MENSAJE_WEB> </PrestacionesAdicionales> <PrestacionesAdicionales> <INDICADOR_PRESADIC>2</INDICADOR_PRESADIC> <COD_PRESTACION /> <NOMBRE_PRES>VALOR TOTAL CONSULTA</NOMBRE_PRES> <VALOR_ACTUAL>40000</VALOR_ACTUAL> <MENSAJE_WEB xml:space="preserve"> </MENSAJE_WEB> </PrestacionesAdicionales> <Datos_Reserva> <CORRELATIVO_RESERVA>9752851</CORRELATIVO_RESERVA> <NOMBRE_PROFESIONAL>EMA ABARZUA MUNOZ</NOMBRE_PROFESIONAL> <FECHA_RESERVA>20/02/2012</FECHA_RESERVA> <HORA_RESERVA>08:00</HORA_RESERVA> <NOMBRE_PACIENTE>ERIC SILVA LATORRE</NOMBRE_PACIENTE> <ESPECIALIDAD>CARDIOLOGIA ADULTO</ESPECIALIDAD> <SUCURSAL>TCSM_BRAGE</SUCURSAL> <ISAPRE>BANMEDICA</ISAPRE> <INDIMED>S</INDIMED> <MONTO_PAGAR>40000</MONTO_PAGAR> <COD_PRESTACION>03-91-692</COD_PRESTACION> <COD_ISAPRE_PACIENTE>193</COD_ISAPRE_PACIENTE> <IND_VALORIZA_WEB>S</IND_VALORIZA_WEB> <IND_PRESTADIC_WEB>N</IND_PRESTADIC_WEB> </Datos_Reserva> </ReservaHora> <Error> <Error_Cod>0</Error_Cod> <ErrorDesc>SIN ERRORES</ErrorDesc> </Error> </XML>';
		
		$xmlObject = $this->_xml2Object($result['WM_ReservaHoraResult']);
		
		$result = (object) $xmlObject->ReservaHora->Datos_Reserva;
		
		if( isset($result->COD_ESTADO) ){
			$this->error = (string) $result->DESC_ESTADO;
			return false;
		}
		
		$response = get_object_vars ( $result );
		
		return $response;
	}

}


?>




