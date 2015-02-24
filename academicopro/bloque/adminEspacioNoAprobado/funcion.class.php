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

class funciones_adminEspacioNoAprobado extends funcionGeneral
{
	//Crea un objeto tema y un objeto SQL.
	function __construct($configuracion, $sql)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

		$this->cripto=new encriptar();
		$this->tema=$tema;
		$this->sql=$sql;

		//Conexion ORACLE
		$this->accesoOracle=$this->conectarDB($configuracion,"oraclesga");

		//Conexion General
		$this->acceso_db=$this->conectarDB($configuracion,"");
		$this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

		//Datos de sesion
		$this->formulario="adminEspacioNoAprobado";
		$this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
		$this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
		$this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");                
		
	}
        
       function verProyectos($configuracion)
       {   $cadena_sql_proyectos=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"proyectos_curriculares","");//echo $cadena_sql_estudiantes;exit;
            $resultado_proyectos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_proyectos,"busqueda" );
            ?>
                <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                        <tr align="center">
                            <td class="centrar" colspan="4">
                                <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png ">
                           </td>
                        </tr>
                        <tr align="center">
                            <td class="centrar" colspan="4">
                                <h4>ESPACIOS ACAD&Eacute;MICOS QUE NO ESTAN APROBADOS EN LA ACAD&Eacute;MICA</h4>
                                          <hr noshade class="hr">

                          </td>
                        </tr><br><br>
                        <tr class="centrar">
                            <td>
                                Seleccione el proyecto curricular
                            </td>
                        </tr>
                        <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                        <tr class="centrar">
                            <td>
                                <select name="proyecto" id="proyecto" style="width:380px">
            <?

            for($i=0;$i<count($resultado_proyectos);$i++)
                {
                ?>
                    <option value="<?echo $resultado_proyectos[$i][2]."-".$resultado_proyectos[$i][0]?>"><?echo $resultado_proyectos[$i][2]." - ".$resultado_proyectos[$i][1]?></option>
                        <?
                }
                ?>
                        </select>
                        </td>
                        </tr>

                        <tr class="cuadro_plano centrar">
                            <td>

                                <input type="hidden" name="opcion" value="NOaprobados">
                                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                                <input name='seleccionar' value='Seleccionar' type='submit' >
                                </form>
                            </td>
                        </tr>
                </table>

                <?
        }
        
       function verNOaprobados($configuracion)
       {
            ?>
             <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                        <tr align="center">
                            <td class="centrar" colspan="4">
                                <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png ">
                           </td>
                        </tr>
                        <tr align="center">
                            <td class="centrar" colspan="4">
                                <h4>ESPACIOS ACAD&Eacute;MICOS QUE NO ESTAN APROBADOS</h4>
                                          <hr noshade class="hr">

                          </td>
                        </tr>
                        <tr align="center">
                            <td class="centrar" colspan="4">
                                <a href="javascript:history.back()">
                                      <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/go-first.png" width="35" height="35" border="0"><br>Regresar
                                </a>
                            </td>
                        </tr><br>
                        <tr class="cuadro_color centrar">
                            <td>
                                ID Espacio
                            </td>
                            <td>
                                Nombre Espacio
                            </td>
                            <td>
                                Nivel
                            </td>                           
                        </tr>
            <?
            $planEstudio=substr($_REQUEST['proyecto'], 0, 3);
            $codProyecto=substr($_REQUEST['proyecto'], 4,3);

            $variable=array($planEstudio,$codProyecto);

            $cadena_sql_espaciosNOaprobados=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"espaciosNOaprobados",$variable);
            $resultado_espaciosNOaprobados=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_espaciosNOaprobados,"busqueda" );
            /*echo $cadena_sql_espaciosNOaprobados;
            exit;*/
            for($i=0;$i<count($resultado_espaciosNOaprobados);$i++)
                {
                    ?>
                        <tr>
                            <td class="cuadro_plano centrar">
                                <?echo $resultado_espaciosNOaprobados[$i][0]?>
                            </td>
                            <td class="cuadro_plano">
                                <?echo $resultado_espaciosNOaprobados[$i][1]?>
                            </td>
                            <td class="cuadro_plano centrar">
                                <?echo $resultado_espaciosNOaprobados[$i][2]?>
                            </td>
                                <?if($resultado_espaciosNOaprobados[$i][3]==0)
                                    {
                                       ?>
                                            <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                                                <input type="hidden" name="opcion" value="cargar">
                                                <input type="hidden" name="action" value="<?echo $this->formulario?>"
                                                <input type="hidden" name="idEspacio" value="<?echo $resultado_espaciosNOaprobados[$i][0]?>">
                                                <input type="hidden" name="planEstudio" value="<?echo $variable[0]?>">
                                                
                                            </form>
                                       <?
                                    }
                                ?>
                           </tr>
               
                    <?
                } 
              ?>  
            </table>
          <?
        }


       function aprobadosNocargados($configuracion)
       {
       $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"plan_estudio","");//echo $cadena_sql;exit;
       $resultado_plan=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

       ?>
<table class="contenidotabla">

       <?
       for($i=0;$i<count($resultado_plan);$i++)
       {
           $contador=0;
           ?>
    <tr>
        <td colspan="8" class="cuadro_brownOscuro centrar">
            <b>PLAN DE ESTUDIOS <?echo $resultado_plan[$i][0]?></b>
        </td>
    </tr>
    
           <?
           $variablesPensum=array($resultado_plan[$i][0]);
           $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"espacios_proyectos",$variablesPensum);//echo $cadena_sql;exit;
           $resultado_proyectos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );

           if(is_array($resultado_proyectos))
               {
               ?>
               <tr class="cuadro_brownOscuro centrar">
                   <td width="15%">ID Espacio</td><td width="30%">Nombre de Espacio</td><td width="15%">Nivel</td><td width="20%">Descripci&oacute;n</td>
               </tr>
               <?
                    for($j=0;$j<count($resultado_proyectos);$j++)
                    {
                        $variablesBusqueda=array($resultado_proyectos[$j][0], $resultado_plan[$i][0]);

                        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"buscar_acasi",$variablesBusqueda);//echo $cadena_sql_estudiantes;exit;
                        $resultado_acasi=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                        if(!is_array($resultado_acasi))
                            {

                            $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"buscar_acpen",$variablesBusqueda);//echo $cadena_sql_estudiantes;exit;
                            $resultado_acpen=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                            if(!is_array($resultado_acpen))
                                {
                                ?>
                                <tr>
                                    <td class="centrar">
                                        <?echo $resultado_proyectos[$j][0]?>
                                    </td>
                                    <td>
                                        <?echo $resultado_proyectos[$j][1]?>
                                    </td>
                                    <td class="centrar">
                                        <?echo $resultado_proyectos[$j][2]?>
                                    </td>
                                    <td>
                                        No cargado (ACASI, ACPEN)
                                    </td>
                                </tr>
                                <?
                                }else
                                    {
                                    ?>
                                    <tr>
                                        <td class="centrar">
                                            <?echo $resultado_proyectos[$j][0]?>
                                        </td>
                                        <td>
                                            <?echo $resultado_proyectos[$j][1]?>
                                        </td>
                                        <td class="centrar">
                                            <?echo $resultado_proyectos[$j][2]?>
                                        </td>
                                        <td>
                                            No cargado (ACASI)
                                        </td>
                                    </tr>
                                    <?
                                    }
                            
                            }else
                                {
                                    $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"buscar_acpen",$variablesBusqueda);//echo $cadena_sql_estudiantes;exit;
                                    $resultado_acpen=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda" );

                                    if(!is_array($resultado_acpen))
                                        {
                                        ?>
                                        <tr>
                                            <td class="centrar">
                                                <?echo $resultado_proyectos[$j][0]?>
                                            </td>
                                            <td>
                                                <?echo $resultado_proyectos[$j][1]?>
                                            </td>
                                            <td class="centrar">
                                                <?echo $resultado_proyectos[$j][2]?>
                                            </td>
                                            <td>
                                                No cargado (ACPEN)
                                            </td>
                                        </tr>
                                        <?
                                        }
                                }
                    }
               }

           

       }
?>
</table>
       <?
       
   }



}	

?>