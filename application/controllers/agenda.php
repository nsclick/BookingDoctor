<?php
if ( !defined ( 'BASEPATH' ) )
	exit ( 'No direct script access allowed' );

class Agenda extends CI_Controller {
	public $title		= 'Agenda';
	public $page_params = array ();

	public function __construct () {
		parent::__construct ();
		$this->load->model ( 'Agenda_model' );
	}

	public function index () {
		$this->render();
	}

	private function render () {
		// $profesional_rut 	= $this->input->post ( 'pr' );
		// $profesional_agenda = $this->Agenda_model->getDoctorAgendaByRut ( $profesional_rut );

		$this->page_params['agenda'] = $this->input->post();

		$this->template->write ( 'title', $this->title );

		// $this->template->add_js ( 'assets/third_party/bootstrap/js/bootstrap3-typeahead.js' );
		$this->template->add_css ( 'assets/vendor/calendarjs/css/calendar.min.css' );
		$this->template->add_js ( 'assets/vendor/underscore/underscore-min.js' );
		$this->template->add_js ( 'assets/vendor/calendarjs/js/language/es-ES.js' );
		$this->template->add_js ( 'assets/vendor/calendarjs/js/calendar.js' );
		$this->template->add_css ( 'assets/vendor/calendarjs/js/calendar.js' );
		
		$this->template->add_js ( 'assets/js/agenda.js' );
		$this->template->add_css ( 'assets/css/agenda.css' );
		
		$this->template->write_view ( 'header', 'templates/header', $this->page_params, TRUE );
		$this->template->write_view ( 'content', 'pages/agenda', $this->page_params, TRUE );
		$this->template->render ();
	}

	public function getAgendaProfesional() {
		if ($this->input->is_ajax_request()) {
			$cod_unidad 		= $this->input->get('cu');
			$cod_especialidad	= $this->input->get('ce');
			$cod_profesional 	= $this->input->get('cp');
			$corr_agenda 		= $this->input->get('corr');
			$fecha_aprox 		= $this->input->get('fa');

			$response = array();
			$response['agenda'] = $this->Agenda_model->getAgendaProfesional($cod_unidad, $cod_especialidad, $cod_profesional, $corr_agenda, $fecha_aprox);
			echo json_encode($response);
			// echo json_encode($this->input->get());
			exit;
		} else {
			exit;
		}
	}
	
	public function confirmacion() {
		$this->template->write ( 'title', $this->title );
		$this->template->add_css ( 'assets/vendor/calendarjs/css/calendar.min.css' );
		$this->template->add_js ( 'assets/vendor/underscore/underscore-min.js' );
			
		$this->template->write_view ( 'header', 'templates/header' );
		$this->template->write_view ( 'content', 'pages/agenda-confirmacion');
		$this->template->render ();
	}


}
?>