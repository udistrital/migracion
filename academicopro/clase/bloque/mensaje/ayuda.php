<?
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}


include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");



class ayuda extends funcionGeneral
{
  function nuevoRegistro($configuracion,$tema,$acceso_db){ }
  function editarRegistro($configuracion,$tema,$id_entidad,$acceso_db,$formulario){ }
  function corregirRegistro(){  }
  function mostrarRegistro($configuracion,$registro, $total, $opcion="",$variable){}
  function __construct($configuracion)
	{
              
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$this->cripto=new encriptar();

	}

   function consultarAyuda($configuracion,$acceso_db,$tema){
      $this->id_modulo=$_REQUEST['id_modulo'];
      $this->id_componente=$_REQUEST['id_componente'];
      
      $sentencia="SELECT modulo_nombre, componente_nombre,ayuda FROM "; $sentencia.=$configuracion["prefijo"]."moduloComponentes AS COMPONENTES ";
      $sentencia.="INNER JOIN ".$configuracion["prefijo"]."modulo AS MODULO ";
      $sentencia.="ON COMPONENTES.id_modulo=MODULO.id_modulo "; 
      $sentencia.="WHERE MODULO.id_modulo=".$this->id_modulo." AND ";
      $sentencia.="id_componente=".$this->id_componente;
      $resultado=$acceso_db->ejecutarAcceso($sentencia,"busqueda");
      $this->mostrarAyuda($resultado,$tema);  
   } 

   function mostrarAyuda($resultado,$tema){
       $contador=0;
       ?>
       <script> 
        window.document.body.backgroun="#CCCCCC"
       </script>
       <table width="350px" height="100%" class='bloquelateral' align="left">
        <tr class="texto_subtitulo">
            <td align="left">
               <?= utf8_encode($resultado[0][0]) ?>
                <hr class="hr_subtitulo">
            </td>
        </tr>
	<tr><td></td></tr>
        
        <tr class="texto_subtitulo">
          <td align="center">
            <strong><?= utf8_encode($resultado[0][1]) ?><strong>
         </td>
        </tr>
        <tr><td></td></tr>
        <tr class='bloquecentralcuerpo' onmouseover="setPointer(this, <? echo $contador ?>, 'over', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmouseout="setPointer(this, <? echo $contador ?>, 'out', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');" onmousedown="setPointer(this, <? echo $contador++ ?>, 'click', '<? echo $tema->celda ?>', '<? echo $tema->apuntado ?>', '<? echo $tema->seleccionado ?>');">
        <td bgcolor='<? echo $tema->celda ?>'>
            <p align="justify">
              <?= utf8_encode($resultado[0][2])?>
            </p>
            </td>
       </tr> 
       </table> 
       <?
   }
   
    
   //echo $configuracion;
   
  // $this->obj_ayuda->mostrarRegistro($configuracion,'','','','');

  function conectarDB($configuracion)
	{
		$this->acceso_db=new dbms($configuracion);
		$this->enlace=$this->acceso_db->conectar_db();
		if (is_resource($this->enlace))
		{
				return $this->acceso_db;
		}
		else
		{
			die("Imposible conectarse a la base de datos");
		}
	}	
}#Cierre de clase ayuda 
  
  $obj_ayuda=new ayuda($configuracion);
  $this->acceso_db=$obj_ayuda->conectarDB($configuracion); 
  $obj_ayuda->consultarAyuda($configuracion,$this->acceso_db,$tema);
?>