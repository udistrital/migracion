<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Desarrollo Por:                                                       #
#    Paulo Cesar Coronado
#    Jairo Lavado 		 2004 - 2008                                      #
#    paulo_cesar@berosa.com                                                #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
/***************************************************************************
  
log.class.php 

Paulo Cesar Coronado
Jairo Lavado
Copyright (C) 2001-2008

Última revisión 2 de Diciembre de 2008
Última revisión 28 de Junio de 2013 - Guardar el host 

*****************************************************************************
* @subpackage   
* @package	bloques
* @copyright    
* @version      0.1
* @author      	Jairo Lavado 
* @link		N/D
* @description  Script para guarda el historico de las acciones de los usuarios.
* @usage        Toda pagina tiene un id_pagina que es propagado por cualquier metodo GET, POST.
******************************************************************************/
class log
{
		
	function log_usuario($registro,$esteRecursoDB)
	{
		$miSesion = Sesion::singleton();
                if (!isset($GLOBALS["autorizado"])) {
                    include("../index.php");
                    exit;
                }
                $usuario=$miSesion->getSesionUsuarioId();
                if($usuario)
                    {  
                        $id_usuario=$usuario;
                    }
                 else   
                    {$id_usuario=1;}
                $host=$this->obtenerIP();

                $cadena_sql="INSERT INTO ";
                $cadena_sql.="admin_log_usuario "; 
                $cadena_sql.="( "; 
                $cadena_sql.="id_usuario, "; 
                $cadena_sql.="accion, ";
                $cadena_sql.="id_registro, ";
                $cadena_sql.="tipo_registro, "; 
                $cadena_sql.="nombre_registro, ";
                $cadena_sql.="fecha_log, "; 
                $cadena_sql.="descripcion ,";
                $cadena_sql.="host ";
                $cadena_sql.=") "; 
                $cadena_sql.="VALUES ";
                $cadena_sql.="( "; 
                $cadena_sql.="'".$registro[6]."', "; 
                $cadena_sql.="'".$registro[0]."', "; 
                $cadena_sql.="'".$registro[1]."', "; 
                $cadena_sql.="'".$registro[2]."', ";
                $cadena_sql.="'".$registro[3]."', ";
                $cadena_sql.="'".date("F j, Y, g:i a")."', ";
                $cadena_sql.="'".$registro[5]."', "; 
                $cadena_sql.="'".$host."' "; 
                $cadena_sql.=")"; 
                
                $resultado=$esteRecursoDB->ejecutarAcceso($cadena_sql,"");
                	
		unset($esteRecursoDB);
		unset($this->nueva_sesion);
	}
        
        
        

        function obtenerIP() {
            if (!empty($_SERVER['HTTP_CLIENT_IP']))
                return $_SERVER['HTTP_CLIENT_IP'];

            if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
                return $_SERVER['HTTP_X_FORWARDED_FOR'];

            return $_SERVER['REMOTE_ADDR'];
        }



}
?>