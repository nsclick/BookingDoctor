<?php
/*
 * Created on 10/01/2014
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 require_once('timebooking.php');
 
 $tr = new Timeooking;
 
// $login = $tr->userLogin(16235269, 5, 'familia7');
// print_r($login);

 //$sp = $tr->getSpecialties(); 
 //print_r($sp);

// $docs = $tr->getDoctorsBySecondName('REYES');
// print_r($docs);
 
// $docs = $tr->getDoctorsBySpecialtyId(30101001);
 
// $tr->getFamilyMembers(948490);
// $patient = $tr->getPatient(16235269, 5);
 
$communes = $tr->getCommunes();
print_r($communes);
 
?>
