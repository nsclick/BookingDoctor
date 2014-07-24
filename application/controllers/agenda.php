<?php
if ( !defined ( 'BASEPATH' ) )
	exit ( 'No direct script access allowed' );

class Agenda extends CI_Controller {
	public $title		= 'Agenda';
	public $page_params = array ();
	
	public function __construct () {
		parent::__construct ();
		$this->load->model ( 'Agenda_model', 'agenda' );
		$this->load->model ( 'Patient_model', 'patient' );
	}

	public function index () {
		
		//Loading the available dates
		$cod_unidad 		= $this->input->post ( 'cod_unidad' );
		$cod_especialidad 	= $this->input->post ( 'cod_especialidad' );
		$cod_profesional 	= $this->input->post ( 'cod_prof' );
		$corr_agenda 		= $this->input->post ( 'corragenda' );
		$fecha_prox 		= $this->input->post ( 'proxima_hora_disponible' );
		
		$available_dates = $this->agenda->getAvailableDatesByDoctor($cod_unidad, $cod_especialidad, $cod_profesional, $corr_agenda, $fecha_prox);
		
		//debug_var($available_dates);
		
		$this->page_params['available_dates'] = $available_dates;
		$this->page_params['post'] = $this->input->post();
		
		$this->render();
	}

	private function render () {

		$this->template->write ( 'title', $this->title );
		$this->template->add_js ( 'assets/js/agenda.js' );
		
		$this->template->write_view ( 'header', 'templates/header', $this->page_params, TRUE );
		$this->template->write_view ( 'content', 'pages/agenda', $this->page_params, TRUE );
		$this->template->render ();
	}
	
	public function confirmacion() {
		
		if(! $this->patient->is_logged_in () ){
			$this->login();
			return false;
		}
		
		$step = 3;
		$patient 		= $this->input->post ( 'patient' );
		$post = getSessionValue('agenda_post');
		$company_addr = getSessionValue('company_address');
		
		if($patient){ //Comes from patient page
			$step = 4;
			$familyMembers = $this->patient->getFamilyMembers();
			foreach($familyMembers as $member){
				if($member['id_ambulatorio'] == $patient){
					$post['patient'] = $member;
				}
			}
		} else { //Set the patient as the main user
			$rut = getSessionValue('rut');
			$dv = getSessionValue('dv');
			$post['patient'] = $this->patient->get($rut, $dv);
		}
		
		$this->page_params['step'] = $step;
		$this->page_params['post'] = $post;	
		$this->page_params['company_addr'] = $company_addr;	
		$this->page_params['reserva_error'] = getSessionValue('reserva_error');
		
		setSessionValue('agenda_post', $post);
		
		$this->template->write ( 'title', $this->title );
			
		$this->template->write_view ( 'header', 'templates/header',  $this->page_params, TRUE );
		$this->template->write_view ( 'content', 'pages/agenda-confirmacion', $this->page_params, TRUE);
		$this->template->render ();
	}
	
	public function login(){
		
		setSessionValue('agenda_post', $_POST);
		
		$this->page_params['redirect'] = 'agenda/paciente';
		$this->page_params['page_title'] = '<h2>Primero debe iniciar sesión</h2>';
		$this->page_params['mode'] = 'agenda';
		
		
		$this->template->write ( 'title', $this->title );

		$this->template->add_js('assets/js/jquery.validationEngine.js');
		$this->template->add_js('assets/js/jquery.validationEngine-es.js');
		$this->template->add_js('assets/js/jquery.Rut.js');
		$this->template->add_js('assets/js/jquery.form.min.js');
		$this->template->add_js('assets/js/login.js');
		
		$this->template->add_css('assets/css/validationEngine.jquery.css');
			
		$this->template->write_view ( 'header', 'templates/header', $this->page_params, TRUE );
		$this->template->write_view ( 'content', 'pages/login', $this->page_params, TRUE);
		$this->template->render ();	
	}
	
	public function paciente(){

		if(! $this->patient->is_logged_in () ){
			$this->login();
			return false;
		}
		
		//loads the main user
		$post = getSessionValue('agenda_post');
		if(!$post)
			$post = $_POST;
		
		$rut = getSessionValue('rut');
		$dv = getSessionValue('dv');
		$post['main'] = $this->patient->get($rut, $dv);
		
		setSessionValue('agenda_post', $post);
		
		//Get the patient family members
		$fm = $this->patient->getFamilyMembers();
		
		//Whether there isn't family members uses the main user as patient
		if(count($fm) == 1){
			redirect('agenda/confirmacion');
			return;
		}
		
		$this->page_params['familyMembers'] = $fm;
		$this->template->write ( 'title', $this->title );
		$this->template->write_view ( 'header', 'templates/header', $this->page_params, TRUE );
		$this->template->write_view ( 'content', 'pages/paciente', $this->page_params, TRUE);
		$this->template->render ();	

	}
	
	public function reservar(){
		
		if(! $this->patient->is_logged_in () ){
			$this->login();
			return false;
		}
		
		$post = getSessionValue('agenda_post');
		
		$result = $this->agenda->reservar($post);
		
		if(!$result){
			setSessionValue('reserva_error', $this->agenda->getError() );
			redirect('/agenda/confirmacion');
			return false;
		}
		
		$this->page_params['result'] = $result;
		$this->page_params['company_address'] = getSessionValue('company_address');
 		
 		//Clean the session
 		
 		$this->template->add_js('assets/js/jquery.PrintArea.js');
 		$this->template->add_js('assets/js/horaAsignada.js');
 		
 		removeSessionVar( array('agenda_post', 'reserva_error') ); 
		$this->template->write ( 'title', $this->title );
		$this->template->write_view ( 'header', 'templates/header', $this->page_params, TRUE );
		$this->template->write_view ( 'content', 'pages/hora-asignada', $this->page_params, TRUE);
		$this->template->render ();	
		
	}
	
	public function anular(){
		
		$result = $this->agenda->anulaReserva($_POST);
		
		if($result)
		setSessionValue('anula-respuesta', 'success' );
		else
		setSessionValue('anula-respuesta', 'error' );
		
		redirect('consulta');

		
	}
}
?>
