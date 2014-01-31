<?php
/*
 * Created on 10/01/2014
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 require_once('TimeReservation.class.php');
 
 $tr = new TimeReservation;
 
// $login = $tr->userLogin(16235269, 5, 'familia7');
// print_r($login);

 //$sp = $tr->getSpecialties(); 
 //print_r($sp);

// $docs = $tr->getDoctorsBySecondName('REYES');
// print_r($docs);
 
// $docs = $tr->getDoctorsBySpecialtyId(30101001);
 
// $tr->getFamilyMembers(948490);
// $patient = $tr->getPatient(16235269, 5);
 
 $members = $tr->getFamilyMembers(948490);
 print_r($members);
 
?>
