<?php
/*
 * Created on 10/01/2014
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 //require_once('timebooking.php');
 
 //$tr = new Timeooking;
 
// $login = $tr->userLogin(16235269, 5, 'familia7');
// print_r($login);

 //$sp = $tr->getSpecialties(); 
 //print_r($sp);

// $docs = $tr->getDoctorsBySecondName('REYES');
// print_r($docs);
 
// $docs = $tr->getDoctorsBySpecialtyId(30101001);
 
// $tr->getFamilyMembers(948490);
// $patient = $tr->getPatient(16235269, 5);
 
//$communes = $tr->getCommunes();
//print_r($communes);

$xmlStr = '<XML>
    <MantUsuarios>
        <Datos>
            <ESTADO>S</ESTADO>
            <DESC_ESTADO>GRABACION NUEVO REGISTRO WEB EXITOSA</DESC_ESTADO>
            <ID_AMB_INGRESO>3061476</ID_AMB_INGRESO>
        </Datos>
    </MantUsuarios>
    <Mensaje>
        <CodMensaje>3</CodMensaje>
        <DescMensaje></DescMensaje>
    </Mensaje>
    <Error>
        <Error_Cod>0</Error_Cod>
        <ErrorDesc>SIN ERRORES</ErrorDesc>
    </Error>
</XML>';

/*$xmlStr = '<XML>
    <MantUsuarios>
        <Datos>
            <ESTADO>S</ESTADO>
            <DESC_ESTADO>EL REGISTRO YA EXISTE</DESC_ESTADO>
        </Datos>
    </MantUsuarios>
    <Mensaje>
        <CodMensaje>3</CodMensaje>
        <DescMensaje></DescMensaje>
    </Mensaje>
    <Error>
        <Error_Cod>0</Error_Cod>
        <ErrorDesc>SIN ERRORES</ErrorDesc>
    </Error>
</XML>';*/

$xmlStr = mb_convert_encoding($xmlStr, "UTF-8");
$xmlObject = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?>' . $xmlStr);
$id_ambulatorio = (string) $xmlObject->MantUsuarios->Datos->ID_AMB_INGRESO;
print_r($id_ambulatorio);
?>
