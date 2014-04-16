<?php
if (!defined('BASEPATH'))
	exit ('No direct script access allowed');

class Registro extends CI_Controller {

	var $title = 'Registro';
	var $registro_params = array();

	function __construct () {
		parent::__construct();
		$this->load->library ( 'Timebooking' );
		
		$this->registro_params['phone_prefixes'] = $this->config->item('phone_prefixes');
		$this->registro_params['medical_services'] = $this->config->item('medical_services');
		
	}
		
	public function index() {
		$this->render();
	}
	
	private function render($page = 'registro'){
		$this->template->write('title', $this->title);

		$this->template->add_js('assets/js/moment-2.4.0.js');
		$this->template->add_js('assets/third_party/bootstrap/js/bootstrap-datetimepicker.min.js');
		$this->template->add_js('assets/js/jquery.validationEngine.js');
		$this->template->add_js('assets/js/jquery.validationEngine-es.js');
		$this->template->add_js('assets/js/jquery.Rut.js');
		$this->template->add_js('assets/third_party/bootstrap/js/bootstrap3-typeahead.js');
		$this->template->add_js('assets/js/jquery.form.min.js');
		$this->template->add_js('assets/js/registro.js');
		
		$this->template->add_css('assets/css/validationEngine.jquery.css');
		$this->template->add_css('assets/third_party/bootstrap/css/bootstrap-datetimepicker.min.css');
		
		
		$this->template->write_view('header', 'templates/header', $this->registro_params, TRUE);
		
		$this->template->write_view('content', "pages/$page", $this->registro_params, TRUE);

		$this->template->render();	
	}
	
	
	
	public function guardar(){
		
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
		$validation_rules = array(
			
		);
		
		//To prvent attacks, clean the input
		$data = array();
		foreach($_POST as $f => $v){
			
			if($f == 'Email_Paciente-confirma' || $f == 'Clave_Usuario-confirma' || $f == 'Comuna_Paciente-label')
				continue;
			
			if($f == 'Rut_Paciente'){
				$v = str_replace('.', '', $this->input->post($f, TRUE));
				$rut = explode('-', $v);
				$data[$f] = $rut[0];
				$data['Dv_Paciente'] = $rut[1];
			} else {
				$data[$f] = $this->input->post($f, TRUE);
			}
			
		}
		
		$this->load->model('Patient_model', 'patient');
		
		$result = $this->patient->create($data);
		if(!$result){
			echo json_encode( array('state' => false, 'message' => $this->patient->getError() ) );
			die();
		}
		
		die(json_encode( array('state' => true, 'message' => '' ) ));
	}
}
