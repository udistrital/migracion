<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Copyright: Vea el archivo LICENCIA.txt que viene con la distribucion  #
############################################################################
*/
/***************************************************************************
* @name          index.php 
* @author        Jairo Lavado
* @revision      Última revisión 04 de Junio de 2013
****************************************************************************
* @subpackage   
* @package	index
* @copyright    
* @version      0.1
* @author        Jairo Lavado
* @link		
* @description  clase de index, que controla la pagina inicial, según la 
*               de accesos errados que registre el usuario.
*
******************************************************************************/
class index
{ private $cripto;
  private $varIndex;
  private $varFormLogueo;

  public function __construct()
	{   require_once("clase/encriptar.class.php");
            $this->cripto=new encriptar();
            $this->varIndex['verificador']=date("YmdH");
            $this->varIndex['enlace']='index';
            $this->varFormLogueo['usuario']=$this->cripto->codificar_variable('usuario', $this->varIndex['verificador']);
            $this->varFormLogueo['contrasena']=$this->cripto->codificar_variable('contrasena', $this->varIndex['verificador']);
            $this->varFormLogueo['numero']=$this->cripto->codificar_variable('numero', $this->varIndex['verificador']);
            $this->varFormLogueo['cifrado']=$this->cripto->codificar_variable('cifrado', $this->varIndex['verificador']);
            $this->varFormLogueo['acceso']=$this->cripto->codificar_variable('acceso', $this->varIndex['verificador']);
            $this->varFormLogueo['oas_captcha']=$this->cripto->codificar_variable('oas_captcha', $this->varIndex['verificador']);
            
        }
  function html()
	{   $acceso=$this->varFormLogueo['acceso'].'=1';
            $url=  $this->cripto->codificar_url($acceso, $this->varIndex);
            $this->redireccionar('index.php?',$url);
      	}
  function action()
	{      /*include ('formNoLogin.php');exit;*/
               /*include ('redireccionaLogin.php');*/
		
//jaider
	//$this->redireccionar('http://www.udistrital.edu.co','');exit; 

		$this->cripto->decodificar_url($_REQUEST['index'], $this->varIndex);
               //var_dump($_REQUEST);//exit;
              /*verifica si los accesos errados son menores a 5, si no redirecciona a la pagina de la Universidad*/     
		
                if(isset($_REQUEST[$this->varFormLogueo['acceso']]) && $_REQUEST[$this->varFormLogueo['acceso']]>5)
                      { $this->redireccionar('http://www.udistrital.edu.co','');
                        exit;
                       }
                 else { if(!isset($_REQUEST[$this->varFormLogueo['acceso']]))
                            { $acceso=$this->varFormLogueo['acceso'].'=3';
                              $url=  $this->cripto->codificar_url($acceso, $this->varIndex);
                              $this->redireccionar('index.php?',$url);
                              exit;
                            }
                          else
                            { include ('formCapcha.php');}
                      }
	}

  function redireccionar($host,$url)
	{   echo " <script type='text/javascript'>
                         window.location='".$host."".$url."';
                    </script>";
            exit;
	}
}
$estaClase=new index();
if(!isset($_REQUEST['index']))
    {	$estaClase->html();}
else{   $estaClase->action();}
?>
