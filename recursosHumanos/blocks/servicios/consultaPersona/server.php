<?php
//ini_set("display_errors", "1");
$ruta = "/usr/local/apache/htdocs/recursosHumanos/blocks/servicios/consultaPersona/";
include('../nusoap-0.9.5/lib/nusoap.php');
//include('../lib/nusoap.php');
include($ruta.'funcion/funcion.class.php');

$server = new soap_server(); 

$ns="https://oas.udistrital.edu.co/recursosHumanosLocal/blocks/servicios/consultaPersona/";

$server->configureWSDL('Consulta Personas',$ns);

$server->wsdl->schemaTargetNamespace=$ns;


//Definimos la estructura parametros de salida
	$server->wsdl->addComplexType(
		'arrayDatos',
		'complexType',
		'struct',
		'all',
		'',
                array(
                        'error'=>array('name' => 'error', 'type' => 'xsd:int'),
                        'descripcionError'=>array('name' => 'descripcionError', 'type' => 'xsd:string'),
                        'id_persona'=>array('name' => 'id_persona', 'type' => 'xsd:int'),
                        'codigo_interno'=>array('name' => 'codigo_interno', 'type' => 'xsd:int'),
                        'tipo_identificacion'=>array('name' => 'tipo_identificacion', 'type' => 'xsd:string'),
                        'nume_identificacion'=>array('name' => 'nume_identificacion', 'type' => 'xsd:string'),
                        'primer_nombre'=>array('name' => 'primer_nombre', 'type' => 'xsd:string'),
                        'segundo_nombre'=>array('name' => 'segundo_nombre', 'type' => 'xsd:string'),
                        'primer_apellido'=>array('name' => 'primer_apellido', 'type' => 'xsd:string'),
                        'segundo_apellido'=>array('name' => 'segundo_apellido', 'type' => 'xsd:string'),
                        'fecha_nacimiento'=>array('name' => 'fecha_nacimiento', 'type' => 'xsd:string'),
                        'lugar_nacimiento'=>array('name' => 'lugar_nacimiento', 'type' => 'xsd:int'),
                        'sexo'=>array('name' => 'sexo', 'type' => 'xsd:string'),
                        'estado_civil'=>array('name' => 'estado_civil', 'type' => 'xsd:string'),
                        'direccion'=>array('name' => 'direccion', 'type' => 'xsd:string'),
                        'ciudad'=>array('name' => 'ciudad', 'type' => 'xsd:int'),
                        'telefono'=>array('name' => 'telefono', 'type' => 'xsd:string'),
                        'celular'=>array('name' => 'celular', 'type' => 'xsd:string'),
                        'correo'=>array('name' => 'correo', 'type' => 'xsd:string'),
                        'estado'=>array('name' => 'estado', 'type' => 'xsd:string')
                )
	); 

$server->register('consultarPersona',
			array(
			'tipo_identificacion' => 'xsd:string',
			'nume_identificacion' => 'xsd:string',
			'codi_interno' => 'xsd:string'
                            ),
			array('return' => 'tns:arrayDatos'),
			$ns,
                        $ns,   										   //Soapaction para el metodo	
                        'rpc',                 						   //Estilo
                        'encoded',             
                        'consultaPersona'
        );

if (isset($HTTP_RAW_POST_DATA))
{
    	$input = $HTTP_RAW_POST_DATA;
}
else
{
    	$input = implode("\r\n", file('php://input'));
}
$server->service($input);

?>
