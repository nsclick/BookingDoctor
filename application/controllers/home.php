<?php
if (!defined('BASEPATH'))
	exit ('No direct script access allowed');

class Home extends CI_Controller {

	var $title = 'Home';
	var $page_params = array();

	function __construct() {
		// Call the Model constructor
		parent :: __construct();
		
		$this->load->model('Agenda_model', 'agenda');
	}
		
	public function index() {
	
		//Get the search params
		$apellido = $this->input->post('apellido', TRUE);
		$area = $this->input->post('area', TRUE);
		$this->page_params['apellido'] = $apellido;
		$this->page_params['area'] = $area;

		$doctors = NULL;
		
		//Perform the search		
		if($area){
			$arealbl = $this->input->post('area-label', TRUE);
			$msg = "área: $arealbl";
			
			$doctors = $this->agenda->getDoctorListByArea($area);
			
		} elseif($apellido) {
			$apellido = strtoupper($apellido);
			$msg = "nombre : $apellido";
			
			$doctors = $this->agenda->getDoctorListByLastName($apellido);
		}
		
		if($doctors){
			$this->page_params['doctors'] = $doctors;
			$this->page_params['title'] = "Listado de médicos por $msg";
		}
		
		$this->render();
	}
	
	private function render(){
		$this->template->write('title', $this->title);

		$this->template->add_js('assets/js/home.js');
		$this->template->add_js('assets/third_party/bootstrap/js/bootstrap3-typeahead.js');
		
		$this->template->write_view('header', 'templates/header', $this->page_params, TRUE);
		
		$this->template->write_view('content', 'pages/home', $this->page_params, TRUE);

		$this->template->render();

	}
}

/* End of file home.php */
/* Location: ./application/controllers/welcome.php */
