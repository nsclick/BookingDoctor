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

		//$this->template->add_js('https://www.google.com/jsapi', 'import', FALSE, FALSE);
		
		$this->template->write_view('header', 'templates/header', $this->page_params, TRUE);
		
		$this->template->write_view('content', 'pages/home', $this->page_params, TRUE);

		$this->template->render();	

	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */