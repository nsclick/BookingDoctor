<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Contactenos extends CI_Controller {

	var $title = 'Contactenos';
	var $registro_params = array();
	var $error;

	function __construct() {
		parent::__construct();

		$this -> load -> library('email');
		$this -> load -> library('Timebooking');
		$this -> load -> model ( 'Patient_model', 'patient' );

		$this -> registro_params['phone_prefixes'] 		= $this -> config -> item('phone_prefixes');
		$this -> registro_params['medical_services'] 	= $this -> config -> item('medical_services');

	}

	public function index() {
		
		if(!isAppBlocked())
			redirect( 'home' );
			
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

		$this -> template -> write_view('header', 'templates/header-contacus', $this -> registro_params, TRUE);

		$this -> template -> write_view('content', "pages/$page", $this -> registro_params, TRUE);

		$this -> template -> render();
	}

	public function enviar() {

		//debug_var( $_POST );

		$this->email->from('develo@nsclick.cl', 'Develop');
		$this->email->to('creyes@nsclick.cl');
		//$this->email->cc('another@another-example.com');
		//$this->email->bcc('them@their-example.com');
		
		$message = array();
		foreach( $_POST as $key => $value ){
			$key = str_replace( array('_', '-'), ' ' , $key);
			$key = ucfirst( $key );
			$message[] = "$key: $value";
		}
		
		$this->email->subject('Solicitud de reserva de hora');
		$this->email->message(implode("\n", $message));

		$this->email->send();
		
		if(!isAppBlocked())
			redirect( 'home' );
		
		$this -> registro_params['sent_header'] = true;
		$this->render('contactenos-enviado');
		
	}

}

