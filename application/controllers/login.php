<?php
if (!defined('BASEPATH'))
	exit ('No direct script access allowed');

class Login extends CI_Controller {

	var $title = 'Inicio de Sesión';
	var $page_params = array();

	
	public function index() {
		validate_true_session();
		
		$this->page_params['redirect'] = 'home';
		$this->page_params['page_title'] = '<h1>Inicio de Sesi&oacute;n</h1>';
		$this->page_params['mode'] = 'default';
		
		$this->render();
	}
	
	private function render(){
		$this->template->write('title', $this->title);

		//$this->template->add_js('https://www.google.com/jsapi', 'import', FALSE, FALSE);
		$this->template->add_js('assets/js/jquery.validationEngine.js');
		$this->template->add_js('assets/js/jquery.validationEngine-es.js');
		$this->template->add_js('assets/js/jquery.Rut.js');
		$this->template->add_js('assets/js/jquery.form.min.js');
		$this->template->add_js('assets/js/login.js');
		
		$this->template->add_css('assets/css/validationEngine.jquery.css');
		
		$this->template->write_view('header', 'templates/header', $this->page_params, TRUE);
		
		$this->template->write_view('content', 'pages/login', $this->page_params, TRUE);

		$this->template->render();	

	}
	
	public function do_login(){
		
		//Getting the login values
		$rut = $this->input->post('Rut_PacienteTitular', TRUE);
		$password = $this->input->post('Clave_Paciente', TRUE);
		if(!$rut || !$password){
			die( json_encode( array( 'state' => false, 'message' => 'El rut y la clave son necesarios' ) ) );
		}
		
		$rut_parts = explode('-', $rut);
		$rut = $rut_parts[0];
		$dv = $rut_parts[1];
		
		$this->load->model('Patient_model', 'patient');
		if(!$this->patient->login($rut, $dv, $password)){
			$err = 'Fallo el inicio de sesión, Intentelo mas tarde.';
			if($this->patient->getError() == 'NE')
				$err = 'El paciente no existe.<br/>Debe registrarse primero <a href="' . site_url("registro") . '">Aqui!</a>';
				
			die( json_encode( array( 'state' => false, 'message' => $err ) ) );
		}
		
		die( json_encode( array( 'state' => true ) ) );
	}
	
	public function logout(){
		killSession();
		$this->index();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
