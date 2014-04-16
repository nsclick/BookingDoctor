<?php
class Agenda_model extends CI_Model {
	
	function __construct () {
		parent::__construct();
		$this->load->library ( 'Timebooking' );
	}

	function getDoctorAgendaByRut ( $rut ) {
		// var_dump($this->timebooking);
		return $this->timebooking->getDoctorAgendaByRut( $rut );
	}

	function getDoctorListByLastName($apellido){
		return $this->timebooking->getDoctorsBySecondName($apellido);
		
	}
	
	function getDoctorListByArea($id_area){
		return $this->timebooking->getDoctorsBySpecialtyId($id_area);
	}

	function getAgendaProfesional($cod_unidad, $cod_especialidad, $cod_profesional, $corr_agenda, $fecha_aprox) {
		// return $this->timebooking->getAgendaProfesional($cod_unidad, $cod_especialidad, $cod_profesional, $corr_agenda, $fecha_aprox);
		
		// Dummie data for month 04
		$fake_data = array(

		);
	}

	function getDetalleDia($cod_unidad, $cod_especialidad, $cod_profesional, $corr_agenda, $fecha_aprox) {
		// $this->timebooking->getDetalleDia($cod_unidad, $cod_especialidad, $cod_profesional, $corr_agenda, $fecha_aprox);
		
		$fake_data = array(
			1 => array(

			)
		);
	}
}
?>