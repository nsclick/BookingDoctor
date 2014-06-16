<?php
if ( !defined ( 'BASEPATH' ) ) exit ( 'No direct script access allowed' );

class Consulta extends CI_Controller {

	public $title 		= 'Consulta';
	public $page_params	= array();

	public function __construct () {
		parent::__construct();
		// $this->load->model ( 'Consulta_model' );
	}

	public function index () {
		$this->render();
	}

	public function render () {
		$this->page_params[] = array(
			'one' => 1
		);

		$this->template->add_css ( 'assets/vendor/consulta.css' );

		$this->template->write_view ( 'header', 'templates/header', $this->page_params, true );
		$this->template->write_view ( 'content', 'pages/consulta', $this->page_params, true );
		$this->template->render();
	}

}

?>