<?php
if (!defined('BASEPATH'))
	exit ('No direct script access allowed');

class Modificardatos extends CI_Controller {
	
	var $title = 'Registro';
	var $view_params = array();	

	function __construct () {
		parent::__construct();
		$this->load->model('Patient_model', 'patient');
		
		$this->registro_params['phone_prefixes'] = $this->config->item('phone_prefixes');
		$this->registro_params['medical_services'] = $this->config->item('medical_services');
		
		
	}

	public function index() {
		validate_false_session();
		
		//Load the user info
		$session_user = get_session_user();
		$user_data = $this->patient->get($session_user['rut'], $session_user['dv']);
		$this->view_params['user_data'] = $user_data;
		if(!$user_data)
			$this->view_params['error_msg'] = $this->patient->getError();
		
		$this->render();
	}
	
	private function render($page = 'modificardatos'){
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
		
		
		$this->template->write_view('header', 'templates/header', $this->view_params, TRUE);
		
		$this->template->write_view('content', "pages/$page", $this->view_params, TRUE);

		$this->template->render();	
	}		
	
}
