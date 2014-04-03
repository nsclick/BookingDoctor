<?php
if (!defined('BASEPATH'))
	exit ('No direct script access allowed');

class Home extends CI_Controller {

	var $title = 'Home';
	var $page_params = array();

	
	public function index() {
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
