<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Contactenos extends CI_Controller {

	var $title = 'Conttactenos';
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

	private function render($page = 'contactenos') {
		$this -> template -> write('title', $this -> title);

		$this -> template -> add_js('assets/js/moment-2.4.0.js');
		$this -> template -> add_js('assets/third_party/validationEngine/jquery.validationEngine.js');
		$this -> template -> add_js('assets/third_party/validationEngine/jquery.validationEngine-es.js');
		$this -> template -> add_js('assets/js/jquery.Rut.js');
		$this -> template -> add_js('assets/third_party/bootstrap/js/bootstrap3-typeahead.js');
		$this -> template -> add_js('assets/js/jquery.form.min.js');
		$this -> template -> add_js('assets/js/contactenos.js');

		$this -> template -> add_css('assets/css/validationEngine.jquery.css');
		$this -> template -> add_css('assets/third_party/bootstrap/css/bootstrap-datetimepicker.min.css');

		$this -> template -> write_view('header', 'templates/header-contacus', $this -> registro_params, TRUE);

		$this -> template -> write_view('content', "pages/$page", $this -> registro_params, TRUE);

		$this -> template -> render();
	}

	public function enviar() {

	}

}

