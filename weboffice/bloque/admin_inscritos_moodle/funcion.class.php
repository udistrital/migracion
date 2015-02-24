<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
//require_once('dir_relativo.cfg');
require_once($configuracion["raiz_condor"].'/script/mensaje_error.inc.php');

class funciones_adminInscritos_moodle extends funcionGeneral
{
	//Crea un objeto tema y un objeto SQL.
	function __construct($configuracion, $sql)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/estilo.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$this->sql = new sql_adminInscritos_moodle();
		$this->cripto=new encriptar();
		//$this->tema=$tema;
		$this->sql=$sql;
                
                $this->accesoOracle=$this->conectarDB($configuracion,"moodle");
                $this->conexion=$this->conectarDB($configuracion,"moodle");

                $this->formulario="admin_inscritos_moodle";
                
	}
	
	//Rescata los valores del formulario para guardarlos en la base de datos.
	
	
	function encabezado($configuracion){
        ?>
           <table class='contenidotabla centrar'>
                <tr align="center">
                    <td class="centrar"><h4>OFICINA ASESORA DE SISTEMAS</h4>
                        <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png " alt="Logo Universidad">
                    </td>
                </tr>
                <tr align="center">
                    <td class="centrar"><h4>MOODLE<br>
                        UNIVERSIDAD DISTRITAL FRANCISCO JOS&Eacute; DE CALDAS</h4>
                      <hr noshade class="hr">   
                    </td>
                </tr>
           </table>
        <?    
        }//fin funcion encabezado


        function consultaProyectos($configuracion){
            $this->encabezado($configuracion);
            $cadena_sql=$this->sql->cadena_sql($configuracion, "consultaCra");
            $craResultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
            ?>

            <table class="sigma_borde" align="center" width="80%">
                <caption>Seleccione el Proyecto que desea consultar</caption>
                <tr>
                    <th class="sigma centrar">Codigo Carrrera</th>
                    <th class="sigma centrar">Nombre Carrrera</th>
                </tr>
                <?foreach ($craResultado as $cra){?>
                <tr>
                    <?
                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=adminInscritos_moodle";
                        $variable.="&opcion=mostrarAsignaturas";
                        $variable.="&codProyecto=".$cra[0];
                        $variable.="&nombreProyecto=".$cra[1];

                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);

                    ?>

                    <td class="cuadro_plano centrar"><a href="<?echo $pagina.$variable?>"><?echo $cra[0]?></a></td>
                    <td class="cuadro_plano"><a href="<?echo $pagina.$variable?>"><?echo $cra[1]?></a></td>
                </tr>
                <?}?>
            </table>
            <br
            <?

        }// fin function consultarProyectos

        function mostrarAsignaturas($configuracion){
            $craCod=$_REQUEST['codProyecto'];
            $nombreCra=$_REQUEST['nombreProyecto'];

            $variable=$craCod;
            $cadena_sql=$this->sql->cadena_sql($configuracion, "consultaAsignaturas", $variable);
            $asiResultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

            $this->encabezado($configuracion);

            ?>
           
            <table class="sigma_borde" align="center" width="80%">
                <caption><font size="1">Seleccione la Asignatura</font></caption>
                <tr>
                    <th class="sigma centrar">Cod. Asignatura</th>
                    <th class="sigma centrar">Asignatura</th>
                    <th class="sigma centrar">Grupo</th>
                </tr>
                <?
                foreach($asiResultado as $asi){
                
                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                $variable="pagina=adminInscritos_moodle";
                $variable.="&opcion=mostrarInscritos";
                $variable.="&codProyecto=".$craCod;
                $variable.="&nombreProyecto=".$nombreCra;
                $variable.="&asiCod=".$asi[1];
                $variable.="&grupo=".$asi[3];

                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$configuracion);
                    
                ?>
                <tr>
                    <td class="cuadro_plano centrar"><a href="<?echo $pagina.$variable?>"><?echo $asi[1]?></a></td>
                    <td class="cuadro_plano centrar"><a href="<?echo $pagina.$variable?>"><?echo $asi[2]?></a></td>
                    <td class="cuadro_plano centrar"><a href="<?echo $pagina.$variable?>"><?echo $asi[0]?></a></td>
                </tr>
                <?}?>
            </table>
            <br>
            <?
        }// fin function mostrarAsignaturas


function mostrarInscritos($configuracion){
            $asiCod=$_REQUEST['asiCod'];
            $grupo=$_REQUEST['grupo'];
            $craCod=$_REQUEST['codProyecto'];
            $nombreCra=$_REQUEST['nombreProyecto'];

            $variable=array($craCod, $asiCod, $grupo);
            $cadena_sql=$this->sql->cadena_sql($configuracion, "consultaInscritos", $variable); 
            $insResultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

            $this->encabezado($configuracion);

            if($insResultado){
            ?>
             <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario;?>'>
             <table class="contenidotabla centrar">
                <tr>
                    <td>
                        <input type="submit" name="ok" value="Exportar">
                        <input type="hidden" name="opcion" value="exportar">
                        <input type="hidden" name="action" value="<?echo $this->formulario?>">
                        <input type="hidden" name="craCod" value="<?echo $craCod?>">
                        <input type="hidden" name="asiCod" value="<?echo $asiCod?>">
                        <input type="hidden" name="grupo" value="<?echo $grupo?>">
                   </td>
                </tr>
            </table>

            <table class="contenidotabla centrar" width="80%">
                <tr class="sigma_cuadro_plano">
                    <td width="10%">Carrera:</td>
                    <td width="90%"><?echo $insResultado[0][4]?></td>
                </tr>
                <tr>
                    <td>Asignatura:</td>
                    <td><?echo $insResultado[0][5]." - ".$insResultado[0][7]?></td>
                </tr>
                <tr>
                    <td>Grupo:</td>
                    <td><?echo $insResultado[0][6]?></td>
                </tr>
            </table>

            <br>
            <table class="sigma_borde" width="100%">
                <caption><font size="1">ESTUDIANTES</font></caption>
                <tr>
                    <th class="sigma centrar">No.</th>
                    <th class="sigma centrar">C&oacute;digo</th>
                    <th class="sigma centrar">Doc. Identidad</th>
                    <th class="sigma centrar">Nombres</th>
                    <th class="sigma centrar">Apellidos</th>
                    <th class="sigma centrar">E-mail</th>
                </tr>
                <?
                $i=1;
                foreach ($insResultado as $ins){?>
                <tr>
                    <td class="cuadro_plano centrar"><?echo $i?></td>
                    <td class="cuadro_plano centrar"><?echo $ins[0]?></td>
                    <td class="cuadro_plano centrar"><?echo $ins[8]?></td>
                    <td class="cuadro_plano"><?echo $ins[1]?></td>
                    <td class="cuadro_plano"><?echo $ins[2]?></td>
                    <td class="cuadro_plano centrar"><?echo $ins[3]?></td>
                </tr>
                <?$i++;}?>
            </table>
            <br>
           
            <?
            }
            else{
            ?>
              <table class="contenidotabla centrar">
                  <tr>
                      <td class="centrar"><font size="1" color="red">**No hay estudiantes inscritos**</font></td>
                  </tr>
              </table>
            <?
            }
            ?>
            
            </form>
    <?
   }

   function consultaAsignatura($configuracion){
        $this->encabezado($configuracion);
        $cadena_sql=$this->sql->cadena_sql($configuracion, "consultaAsignaturasTotales");
        $craAsig=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
        ?>
            <table class="sigma_borde" align="center" width="80%">
                <caption><font size="1">SELECCIONE LA ASIGNATURA</font></caption>
                <tr>
                    <th class="sigma centrar">C&oacute;digo</th>
                    <th class="sigma centrar">Asignatura</th>
                </tr>
                <?
                foreach ($craAsig as $asig){
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variable="pagina=adminInscritos_moodle";
                    $variable.="&opcion=mostrarInscritosAsig";
                    $variable.="&asiCod=".$asig[0];
                    
                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variable=$this->cripto->codificar_url($variable,$configuracion);
                ?>
                <tr>
                    <td class="cuadro_plano centrar"><a href="<?echo $pagina.$variable?>"><?echo $asig[0]?></a></td>
                    <td class="cuadro_plano"><a href="<?echo $pagina.$variable?>"><?echo $asig[1]?></a></td>
                </tr>
                <?}?>
            </table>
            <br>
        <?
   }//fin function consultaAsignturas

   function mostrarInscritosAsig($configuracion){
       $asiCod=$_REQUEST['asiCod'];
       $this->encabezado($configuracion);

       $variable=$asiCod;
       $cadena_sql=$this->sql->cadena_sql($configuracion, "consultaInscritosAsig", $variable);
       $insResultado=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

       if ($insResultado){
       ?>
            <form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->formulario;?>'>
            <br>
            <table>
                <tr>
                    <td>
                        <input type="submit" name="ok" value="Exportar">
                        <input type="hidden" name="opcion" value="exportar">
                        <input type="hidden" name="action" value="<?echo $this->formulario?>">
                        <input type="hidden" name="asiCod" value="<?echo $asiCod?>">
                   </td>
                </tr>
            </table>
            <table class="sigma_borde" width="100%">
                <caption><font size="1">ESTUDIANTES INSCRITOS A <?echo $insResultado[0][7]?></font></caption>
                <tr>
                    <th class="sigma centrar">No.</th>
                    <th class="sigma centrar">C&oacute;digo</th>
                    <th class="sigma centrar">Doc. Identidad</th>
                    <th class="sigma centrar">Nombres</th>
                    <th class="sigma centrar">Apellidos</th>
                    <th class="sigma centrar">Carrera</th>
                    <th class="sigma centrar">E-mail</th>
                    <th class="sigma centrar">Grupo</th>
                </tr>
                <?
                $i=1;
                foreach ($insResultado as $ins){?>
                <tr>
                    <td class="cuadro_plano centrar"><?echo $i?></td>
                    <td class="cuadro_plano centrar"><?echo $ins[0]?></td>
                    <td class="cuadro_plano centrar"><?echo $ins[8]?></td>
                    <td class="cuadro_plano"><?echo $ins[1]?></td>
                    <td class="cuadro_plano"><?echo $ins[2]?></td>
                    <td class="cuadro_plano"><?echo $ins[4]?></td>
                    <td class="cuadro_plano centrar"><?echo $ins[3]?></td>
                    <td class="cuadro_plano centrar"><?echo $ins[6]?></td>
                </tr>
                <?$i++;}?>
            </table>
            <br>
       <?
       }
       else{
       ?>
          <table class="contenidotabla centrar">
              <tr>
                  <td class="centrar"><font size="1" color="red">**No hay estudiantes inscritos**</font></td>
              </tr>
          </table>
       <?
       }

   } //fin mostrarInscritosAsig
}//fin de la clase
        
	

