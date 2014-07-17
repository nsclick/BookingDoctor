<?php
class Agenda_model extends CI_Model {
	
	var $error = NULL;
	
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

	function getAvailableDatesByDoctor($cod_unidad, $cod_especialidad, $cod_profesional, $corr_agenda, $fecha_prox) {
		return $this->timebooking->getAvailableDatesByDoctor($cod_unidad, $cod_especialidad, $cod_profesional, $corr_agenda, $fecha_prox);
	}
	
	function reservar($data){
		//Map the data to make the reservation
		$nfh = explode(' ', $data['proxima_hora_disponible_char']);
		$input = array(
			'Cod_Sucursal' => $data['cod_sucursal'],
			'Cod_CentroMedico' => $data['cod_unidad'],
			'Cod_Especialidad' => $data['cod_especialidad'],
			'Cod_Unidad' => $data['cod_unidad'],
			'Cod_Medico' => $data['cod_prof'],
			'Corr_Agenda' => $data['corragenda'],
			'Cod_Isapre' => $data['patient']['prevision_paciente'] ? $data['patient']['prevision_paciente'] : $data['patient']['prevision'],
			'Box' => $data['box'],
			'Multi' => (bool) $data['multiplicity'],
			'Id_Paciente_Titular' => $data['main']['id_ambulatorio'],
			'Id_Paciente' => $data['patient']['id_ambulatorio'],
			'Corr_Horario' => $data['id_schedule'],
			'Fecha_Reserva' => str_replace('-', '/', $data['available-days']),
			'Hora_Reserva' => $data['time'],
			'Prox_HoraLibre' => $nfh[1] //Uses only the time
		);
		
		//debug_var($input);
		
		$result = $this->timebooking->bookAppointment($data);
		if(!$result){
			$this->error = $this->timebooking->getError();
			return false;
		}
		
		return $result;
	}
	
	function getError(){
		return $this->error;
	}
		
}

?>
