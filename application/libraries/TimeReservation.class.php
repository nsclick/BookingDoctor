<?php
require_once ('nusoap/lib/nusoap.php');

class TimeReservation {

	/**
	 * Server URL Host
	 *
	 * @var string
	 * @access private
	 */
	private $host = 'http://reservas.davila.cl/Ws_ReservaHorasWeb/ResHoraWeb/ResHoraWeb.asmx?wsdl';
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
	public $error = null;
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

		//$action = $this->namespace . '/' . $method;
		$result = $this->client->call($method, $params, '', '', false, true);

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
		
		if($xmlObject->Error->Error_Cod != 0){
			$this->error = $xmlObject->Error->ErrorDesc;
			return false;
		}
		
		$loginData = new stdClass();
		$loginData->state = $xmlObject->LogeoPaciente->InformacionLogeo->ESTADO;
		$loginData->stateName = $xmlObject->LogeoPaciente->InformacionLogeo->DESC_ESTADO;
		$loginData->tmpKey = $xmlObject->LogeoPaciente->InformacionLogeo->CLAVE_TEMP;
		$loginData->ambulatoryID = $xmlObject->LogeoPaciente->InformacionLogeo->ID_AMBULATORIO;
		
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
	
}
?>