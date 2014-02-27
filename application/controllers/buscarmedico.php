<?php
if (!defined('BASEPATH'))
	exit ('No direct script access allowed');

class Buscarmedico extends CI_Controller {

	var $title = 'Lista de medicos';
	var $page_params = array();

	function __construct() {
		// Call the Model constructor
		parent :: __construct();
		
		$this->load->model('Buscarmedico_model');
	}
		
	public function index() {
				
		//Get the search params
		$apellido = $this->input->post('apellido', TRUE);
		
		$area = $this->input->post('area', TRUE);

		//Perform the search		
		if($area){
			$arealbl = $this->input->post('area-label', TRUE);
			$msg = "área: $arealbl";
			
			$doctors = $this->Buscarmedico_model->por_area($area);
			
		} else {
			$apellido = strtoupper($apellido);
			$msg = "nombre : $apellido";
			
			$doctors = $this->Buscarmedico_model->por_apellido($apellido);
		}
		
		$this->page_params['doctors'] = $doctors;
		$this->page_params['title'] = "Listado de médicos por $msg";

		
		
		$this->render();
	}
	
	private function render(){
		$this->template->write('title', $this->title);

		//$this->template->add_js('https://www.google.com/jsapi', 'import', FALSE, FALSE);
		
		$this->template->write_view('header', 'templates/header', $this->page_params, TRUE);
		
		$this->template->write_view('content', 'pages/listamedicos', $this->page_params, TRUE);

		$this->template->render();	

	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */