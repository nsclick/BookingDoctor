<?php
if ( !defined ( 'BASEPATH' ) ) exit ( 'No direct script access allowed' );

class Consulta extends CI_Controller {

	public $title 		= 'Consulta';
	public $page_params	= array();

	public function __construct () {
		parent::__construct();
		$this->load->model ( 'Patient_model', 'patient' );
		
		validate_false_session();
		
	}

	public function index () {
		
		$rd = $this->patient->getReservedDates();
		if( !is_array($rd) )
			$rd = array();
		
		$anulacionRespuesta = getSessionValue( 'anula-respuesta' );		
		removeSessionVar( 'anula-respuesta' );
		
		$this->page_params['reserved'] = $rd;
		$this->page_params['anulacionRespuesta'] = $anulacionRespuesta;
		
		$this->render();
	}

	public function render () {
		
		$this->template->add_css ( 'assets/vendor/consulta.css' );
		$this->template->add_js ( 'assets/js/consulta.js' );
		
		$this->template->write_view ( 'header', 'templates/header', $this->page_params, true );
		$this->template->write_view ( 'content', 'pages/consulta', $this->page_params, true );
		$this->template->render();
	}

}

?>
