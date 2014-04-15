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
	
	private function render(){
		$this->template->write('title', $this->title);

		$this->template->add_js('assets/js/moment-2.4.0.js');
		$this->template->add_js('assets/third_party/bootstrap/js/bootstrap-datetimepicker.min.js');
		$this->template->add_js('assets/js/jquery.validationEngine.js');
		$this->template->add_js('assets/js/jquery.validationEngine-es.js');
		$this->template->add_js('assets/third_party/bootstrap/js/bootstrap3-typeahead.js');
		$this->template->add_js('assets/js/registro.js');
		
		$this->template->add_css('assets/css/validationEngine.jquery.css');
		$this->template->add_css('assets/third_party/bootstrap/css/bootstrap-datetimepicker.min.css');
		$this->template->add_css('assets/css/davila.css');
		
		$this->template->write_view('header', 'templates/header', $this->registro_params, TRUE);
		
		$this->template->write_view('content', 'pages/registro', $this->registro_params, TRUE);

		$this->template->render();	

	}
	
	public function do_registration(){
		
		/* Para registrar las opciones de mensajeria usar el metodo del web service
		 * WM_ActualizaOpcionesMensajeria donde:
		 * (VIa de confirmación) Op_SmsEmail dede ser:
		 * 2^3 => SMS t Email
		 * 2^  => Sms
		 * ^3  => Email
		 * ^   => No quiere ser informado
		 * 
		 * Recibe info davila? 
		 * Op_InfoClinica:
		 * N => No
		 * S => Si*/
		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
