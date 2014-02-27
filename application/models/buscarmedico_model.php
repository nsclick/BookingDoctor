<?php
class Buscarmedico_model extends CI_Model {
	function __construct() {
		// Call the Model constructor
		parent :: __construct();
		
		$this->load->library('Timebooking');
	}
	
	function por_apellido($apellido){
		return $this->timebooking->getDoctorsBySecondName($apellido);
		
	}
	
	function por_area($id_area){
		return $this->timebooking->getDoctorsBySpecialtyId($id_area);
	}
	
	
}
?>