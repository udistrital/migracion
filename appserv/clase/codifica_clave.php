<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Copyright: Vea el archivo LICENCIA.txt que viene con la distribucion  #
############################################################################
*/
/***************************************************************************
* @name          accesos.class.php 
* @author        Jairo Lavado
* @revision      Última revisión 22 de Diciembre de 2011
****************************************************************************
* @subpackage   
* @package	clase
* @copyright    
* @version      0.2
* @author       Jairo Lavado
* @link		
* @description  
*
******************************************************************************/
require_once("funcionGeneral.class.php");

class codificar_clave extends funcionGeneral
{
    private $acceso_db;
    private $acceso_MY;
    private $cripto;
    private $semilla;
    
	public function __construct()
	{       
                require_once("encriptar.class.php");
                $this->cripto=new encriptar();
                $this->semilla="condor";
	}
        
	/**
         * @name registrar
         * @param type $user
         * @param type $apli 
         * @descripcion Registra en el log, los accesos de los usuarios a las diferentes aplicaciones.
         */
	 function registrar()
	{   
            echo "<br>Datos Codificado:<br> ";    
            $usuario='SICGEFAD';
            $pwd='g3sti0nF2013';		
                
	     echo "<br>usuario: ".$usuario." => ".$this->cripto->codificar_variable($usuario,  $this->semilla);
             echo "<br>clave: ".$pwd." => ".$this->cripto->codificar_variable($pwd,  $this->semilla);	

            echo "<br>Datos Decodificado:<br> ";    
            $usuario2='NDIOkU36LVqaEzcTYAkt81DQsNyCQPISGilNzWOIQ0E';
            $pwd2='NDIOkU36LVqaEzcTYAkt81DQsNyCQPISGilNzWOIQ0E';		
                
	     echo "<br>usuario: ".$usuario2." => ".$this->cripto->decodificar_variable($usuario2,  $this->semilla);
             echo "<br>clave: ".$pwd2." => ".$this->cripto->decodificar_variable($pwd2,  $this->semilla);	
          


          
                
	}
        
       
}//Fin de la clase db_admin
$esteBloque = new codificar_clave();
$esteBloque->registrar();
?>
