<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
class funciones_registroAsociarEspaciosCoordinador extends funcionGeneral {     	//Crea un objeto tema y un objeto SQL.
    private $pagina;
    private $opcion;
    function __construct($configuracion, $sql) {
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
        $this->formulario="registroAsociarEspaciosCoordinador";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        if ($this->nivel==28||$this->nivel==4)
        {
            $this->pagina="adminConfigurarPlanEstudioCoordinador";
            $this->opcion="mostrar";
        }
        elseif($this->nivel==61)
        {
            $this->pagina="adminAprobarEspacioPlan";
            $this->opcion="mostrar";
        }
        $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"clasificacion","");//echo $cadena_sql;exit;
        $this->clasificaciones=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );//var_dump($resultado_planEstudio);exit;


    }
    
    function encabezadoModulo($configuracion,$planEstudio,$codProyecto,$nombreProyecto) {

        ?>

<table class='contenidotabla centrar'>
    <tr align="center">
        <td class="centrar" colspan="4">
            <h4>ESPACIOS ACAD&Eacute;MICOS CON OPCIONES PARA EL PROYECTO CURRICULAR<br><?echo $nombreProyecto?><br>PLAN DE ESTUDIOS: <?echo $planEstudio?></h4>
            <hr noshade class="hr">

        </td>
    </tr>

    <tr align="center">
        <td class="centrar" colspan="4">
                    <?
                    $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                    $variables="pagina=".$this->pagina;
                    $variables.="&opcion=".$this->opcion;
                    $variables.="&planEstudio=".$planEstudio;
                    $variables.="&codProyecto=".$codProyecto;
                    $variables.="&nombreProyecto=".$nombreProyecto;

                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                    $this->cripto=new encriptar();
                    $variables=$this->cripto->codificar_url($variables,$configuracion);
                    ?>
            <a href="<?echo $pagina.$variables?>">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/inicio.png" width="35" height="35" border="0"><br>Volver al Plan de estudios
            </a>
        </td>
    </tr>
</table><?
    }

    function clasificacion($configuracion)
    {
    //Consultamos los proyectos curriculares con su respectivo
    //  plan de estudio, y los mostramos en un <select>        
        $cadena_sql_proyectos=$this->sql->cadena_sql($configuracion,$this->accesoOracle,"proyectos_curriculares",$this->usuario);
        $resultado_proyectos=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_proyectos,"busqueda" );
        ?>
<table class='contenidotabla centrar'>
    <tr align="center">
        <th class="sigma" colspan="4">AGRUPACI&Oacute;N DE ESPACIOS ACAD&Eacute;MICOS PARA POSTERIOR APROBACI&Oacute;N DE VICERRECTORIA ACAD&Eacute;MICA
            <hr noshade class="hr">
        </th>
    </tr>
    <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
        <tr class="centrar">
            <td>
                <h3>Seleccione la clasificaci&oacute;n</h3>
            </td>
        </tr>
        <tr class="centrar">
            <td>
                <select name="clasificacion" id="clasificacion" style="width:250px">
                    <option value="1">OBLIGATORIO B&Aacute;SICO</option>
                    <option value="2">OBLIGATORIO COMPLEMENTARIO</option>
                    <option value="3">ELECTIVO INTR&Iacute;NSECO</option>
                    <option value="4">ELECTIVO EXTR&Iacute;NSECO</option>
                </select>
            </td>
        </tr>        
        <tr class="cuadro_plano centrar">
            <td>
                <input type="hidden" name="codProyecto" value="<?echo $_REQUEST["codProyecto"];?>">
                <input type="hidden" name="planEstudio" value="<?echo $_REQUEST["planEstudio"];?>">
                <input type="hidden" name="nombreProyecto" value="<?echo $_REQUEST["nombreProyecto"];?>">
                <input type="hidden" name="opcion" value="verEncabezado">
                <input type="hidden" name="action" value="<?echo $this->formulario?>">
                <input name='enviar' value='Enviar' type='submit' >
            </td>
        </tr>
    </form>
        <?
        #enlace regreso  listado de planes        

        $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
        $ruta="pagina=registroAdministrarPlanCoordinador";
        $ruta.="&opcion=registrados";
        $ruta.="&planEstudio=".$_REQUEST["planEstudio"];
        $ruta=$this->cripto->codificar_url($ruta,$configuracion);
        ?>
        <tr>
         <td class="centrar">
            <a href="javascript:history.back()">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="25" height="25" border="0"><br>Atras
            </a>
        </td>
        </tr>
</table>
    <?
    }

    function ver_Encabezados($configuracion)
    {
        $nombreProyecto=$_REQUEST["nombreProyecto"];
        $codProyecto=$_REQUEST["codProyecto"];
        $planEstudio=$_REQUEST["planEstudio"];
        $clasificacion=$_REQUEST['clasificacion'];

        ?>
<table class='contenidotabla centrar'>
    <tr align="center">
        <td class="centrar" colspan="10">
            <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
            <img src="<?echo
                         $configuracion['site'].$configuracion['grafico']?>/pequeno_universidad.png
                 " alt="Logo Universidad">
        </td>
    </tr>
    <tr align="center">
        <td class="centrar" colspan="10">
            <h4>AGRUPACI&Oacute;N DE ESPACIOS ACAD&Eacute;MICOS PARA
                POSTERIOR APROBACI&Oacute;N DE VICERRECTORIA ACAD&Eacute;MICA</h4>
            <hr noshade class="hr">
        </td>
    </tr>
      <?
        #enlace regreso  listado de planes

        $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
        $ruta="pagina=registroAgruparEspaciosCoordinador";
        $ruta.="&opcion=determinarClasificacion";
        $ruta.="&planEstudio=".$_REQUEST["planEstudio"];
        $ruta.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
        $ruta.="&codProyecto=".$_REQUEST["codProyecto"];
        $ruta=$this->cripto->codificar_url($ruta,$configuracion);
        ?>
        <tr>
         <td class="centrar">
            <a href="javascript:history.back()">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="25" height="25" border="0"><br>Atras
            </a>
        </td>
        </tr>
    <tr align="center">
        <td>
            Plan de Estudios:<br> <? echo $planEstudio." - ".$_REQUEST["nombreProyecto"];?>
        </td>
    </tr>
    <tr align="center">
        <td class="centrar" colspan="10">
                       <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
                            <input type="hidden" name="nombreProyecto" value="<?echo $nombreProyecto?>">
                            <input type="hidden" name="codProyecto" value="<?echo $codProyecto?>">
                            <input type="hidden" name="planEstudio" value="<?echo $planEstudio?>">
                            <input type="hidden" name="clasificacion" value="<?echo $clasificacion?>">
                            <input type="hidden" name="opcion" value="crear">
                            <input type="hidden" name="action" value="<?echo $this->formulario?>">
                            <input type="image" value="Crear Encabezado" src="<?echo $configuracion['site'].$configuracion['grafico']?>/kate.png" width="40" height="40"><br>
                            <font size="2">Crear nombre general <br>de espacio acad&eacute;mico con opciones</font>
                            </form>
        </td>
    </tr>   
    <tr align="center">
        <td> <hr noshade class="hr"> </td>
    </tr>
    <?
          $variables=array($planEstudio, $codProyecto, $clasificacion);
            $cadena_sql_encabezados=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarEncabezados", $variables);
            $resultado_encabezados=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_encabezados,"busqueda" );

      if($resultado_encabezados==true)
       {
    ?>
    <tr align="center">
        <td class="centrar" colspan="10">
          <font size="2">Grupos con clasificaci&oacute;n: <?
              foreach ($this->clasificaciones as $key=>$value)
              {
                if($this->clasificaciones[$key][0]==$clasificacion)
                {
                  $clas=strtr(strtoupper($this->clasificaciones[$key][1]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");
                }else{}
              }
                echo $clas;
                    ?><br><br>
                Para agrupar Espacios Acad&eacute;micos<br>
                seleccione un grupo de opciones</font>
        </td>
    </tr>    
</table>
            <?
       }
       else
          {
              echo "<tr class='cuadro_plano centrar'><td class='cuadro_plano centrar'><font size='2'>NO EXISTEN ENCABEZADOS CREADOS</font></td></tr>";
          }
     for($i=0; $i<count($resultado_encabezados);$i++)
            {
            ?>
    <table class='contenidotabla centrar'>
    <tr class="cuadro_plano centrar">
        <td class="cuadro_color centrar">
            Agrupar Espacio
        </td>
        <td class="cuadro_color centrar">
            Nombre
        </td>
        <td class="cuadro_color centrar">
            Descripci&oacute;n
        </td>
        <td class="cuadro_color centrar">
            Estado 1
        </td>
        <td class="cuadro_color centrar">
            Estado 2
        </td>
        <td class="cuadro_color centrar">
            Creditos
        </td>
        <td class="cuadro_color centrar">
            Nivel
        </td>               
    </tr>
            <?            
                 $id_encabezado=$resultado_encabezados[$i][0];
                 $nivel=$resultado_encabezados[$i][7];
                 $creditos=$resultado_encabezados[$i][6];
                ?>
    <tr class="cuadro_plano centrar">
        <td class="cuadro_plano centrar">
                        <?

                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                        $variable="pagina=registroAgruparEspaciosCoordinador";
                        $variable.="&opcion=ver_registrados";
                        $variable.="&codProyecto=".$codProyecto;
                        $variable.="&planEstudio=".$planEstudio;
                        $variable.="&nombreProyecto=".$nombreProyecto;
                        $variable.="&id_encabezado=".$id_encabezado;
                        $variable.="&clasificacion=".$clasificacion;
                        $variable.="&nivel=".$nivel;
                        $variable.="&creditos=".$creditos;


                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$configuracion);
                        ?>
            <a href="<?= $pagina.$variable ?>" >
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/favorito.png" width="30" height="30" border="0" alt="Editar Requisito">
            </a>
        </td>
        <td class="cuadro_plano centrar">
                        <? echo $resultado_encabezados[$i][1]; ?>
        </td>
        <td class="cuadro_plano centrar">
                        <? echo $resultado_encabezados[$i][2]; ?>
        </td>
        <td class="cuadro_plano centrar">
                        <? if ($resultado_encabezados[$i][3]==0) {
                            echo "Inactivo";
                        }
                        else if($resultado_encabezados[$i][3]==1) {
                                echo "Activo";
                            }
                        ?>
        </td>
        <td class="cuadro_plano centrar">
                        <? if ($resultado_encabezados[$i][4]==0 or $resultado_encabezados[$i][5]==0) {
                            echo "En proceso";
                        }
                        else if($resultado_encabezados[$i][4]==1 and $resultado_encabezados[$i][5]==1) {
                                echo "Aprobado";
                            }
                        ?>
        </td>
        <td class="cuadro_plano centrar">
                        <? echo $resultado_encabezados[$i][6];   ?>
        </td>
        <td>
                        <? echo $resultado_encabezados[$i][7]; ?>
        </td>                
    </tr>
       <?
         $cadena_sql_espaciosAsociados=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarEspacioAsociados", $id_encabezado);
         $resultado_espaciosAsociados=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_espaciosAsociados,"busqueda" );
           if($resultado_espaciosAsociados==true)
           {
           ?>
     <tr class="cuadro_plano centrar">
        <td class="cuadro_plano centrar" colspan="10">
            ESPACIOS ACAD&Eacute;MICOS AGRUPADOS
        </td>
    </tr>
           <?
             for($j=0; $j<count($resultado_espaciosAsociados); $j++)
            {
        ?>
    <tr>
        <td class="cuadro_plano centrar" colspan="2">
            Codigo:  <? echo $resultado_espaciosAsociados[$j][0];?>
        </td>
        <td class="cuadro_plano centrar" colspan="8">
            Espacio Acad&eacute;mico:  <? echo $resultado_espaciosAsociados[$j][1];?>
        </td>
    </tr>
        <?
            }
           }
           else
           {
           ?>
       <tr class="cuadro_plano centrar">
        <td class="cuadro_plano centrar" colspan="10">
            NO TIENE ESPACIOS ACAD&Eacute;MICOS AGRUPADOS
        </td>
       </tr>    
        <?
           }
        ?>
       <tr>
        <td align="center" colspan="10">
            --------------------------
        </td>
    </tr>
</table>
    <?
      }
    }

    function verEspaciosxEstado($configuracion)
    {
        $nombreProyecto=$_REQUEST["nombreProyecto"];
        $codProyecto=$_REQUEST["codProyecto"];
        $planEstudio=$_REQUEST["planEstudio"];
        $id_encabezado=$_REQUEST["id_encabezado"];
        $clasificacion=$_REQUEST["clasificacion"];
        $nivel=$_REQUEST["nivel"];
        $creditos=$_REQUEST["creditos"];
        $this->encabezadoModulo($configuracion, $planEstudio, $codProyecto, $nombreProyecto);

        ?>
<table class='contenidotabla centrar'>
    <tr align="center">
        <th class="sigma_a" colspan="9">
            AGRUPACI&Oacute;N DE ESPACIOS ACAD&Eacute;MICOS PARA POSTERIOR APROBACI&Oacute;N DE VICERRECTORIA ACAD&Eacute;MICA
        </th>
    </tr>
    <?
        #enlace regreso  listado de planes

        $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
        $ruta="pagina=registroAgruparEspaciosCoordinador";
        $ruta.="&opcion=verEncabezado";
        $ruta.="&planEstudio=".$_REQUEST["planEstudio"];
        $ruta.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
        $ruta.="&codProyecto=".$_REQUEST["codProyecto"];
        $ruta.="&clasificacion=".$_REQUEST["clasificacion"];

        $ruta=$this->cripto->codificar_url($ruta,$configuracion);
        ?>
        <tr>
         <td class="centrar" colspan="9">
            <a href="javascript:history.back()">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="25" height="25" border="0"><br>Atras
            </a>
        </td>
        </tr>
            <?
            $variables=array($planEstudio, $clasificacion, $nivel, $creditos);
            $cadena_sql_espacios=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarEspaciosxEstado", $variables);//echo $cadena_sql_espacios;exit;
            $resultado_espacios=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_espacios,"busqueda" );

            if(is_array($resultado_espacios))
                {
              foreach ($this->clasificaciones as $key=>$value)
              {
                if($this->clasificaciones[$key][0]==$clasificacion)
                {
                  $clas=strtr(strtoupper($this->clasificaciones[$key][1]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");
                }else{}
              }

                    ?>
    <tr>
      <td colspan="9" align="center">
        <font size="2">Seleccione los espacios <b><?echo $clas;?></b> que desea ASOCIAR del <b>Nivel <?echo $nivel?></b><br></font>
        </td>
    </tr>
                        <tr class="sigma centrar">
                            <th class="sigma centrar">
                                C&oacute;digo
                            </th>
                            <th class="sigma centrar">
                                Nombre
                            </th>
                            <th class="sigma centrar">
                                Cr&eacute;ditos
                            </th>
                            <th class="sigma centrar">
                                HTD
                            </th>
                            <th class="sigma centrar">
                                HTC
                            </th>
                            <th class="sigma centrar">
                                HTA
                            </th>
                            <th class="sigma centrar">
                                Estado
                            </th>
                            <th class="sigma centrar">
                                Aprobaci&oacute;n
                            </th>
                            <th class="sigma centrar">
                                Seleccionar
                            </th>
                        </tr>
    <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='GET,POST' action='index.php' name='<?echo $this->formulario?>'>
          <?
             for($i=0; $i<count($resultado_espacios);$i++)
             {
                 if($i%2==0)
                     {
                        $claseFila="sigma_a";
                     }else
                         {
                            $claseFila="sigma";
                         }

            $variables=array($planEstudio, $codProyecto, $resultado_espacios[$i][0], $id_encabezado);
            $cadena_sql_espacioGrupo=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarEspacioGrupo", $variables);
            $resultado_espacioGrupo=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_espacioGrupo,"busqueda" );

            if($resultado_espacioGrupo==true)
            {
          ?>
            <tr class="<?echo $claseFila;?> centrar">
                <td class="sigma">
                    <? echo $resultado_espacios[$i][0]; ?>
                </td>
                <td class="sigma">
                    <? echo $resultado_espacios[$i][1]; ?>
                </td>
                <td class="sigma">
                    <? echo $resultado_espacios[$i][2]; ?>
                </td>
                <td class="sigma">
                    <? echo $resultado_espacios[$i][5]; ?>
                </td>
                <td class="sigma">
                    <? echo $resultado_espacios[$i][6]; ?>
                </td>
                <td class="sigma">
                    <? echo $resultado_espacios[$i][7]; ?>
                </td>
                <td class="sigma">
                    <? if ($resultado_espacios[$i][8]==0) {
                        echo "Inactivo";
                        }
                        else if($resultado_espacios[$i][8]==1) {
                            echo "Activo";
                            }
                    ?>
                </td>
                    <? if ($resultado_espacios[$i][9]==0 or $resultado_espacios[$i][10]==0) {
                    ?><td class="sigma">
                        En Proceso
                      </td>
                      <td class="sigma">
                        Este Espacio es una opci&oacute;n seleccionada
                      </td><?
                      }
                      else if($resultado_espacios[$i][9]==1 and $resultado_espacios[$i][10]==1) {
                      ?><td class="sigma">
                      Aprobado
                      </td>
                      <td class="sigma">
                        Este Espacio es una opci&oacute;n seleccionada
                      </td><?
                      }else
                      {
                      ?><td class="sigma">
                            No Aprobado
                        </td>
                        <td class="sigma">
                          Este Espacio es una opci&oacute;n seleccionada
                        </td><?
                      }
                            ?>
            </td>

            
        </tr>
          <?
            }
            else
            {
           ?>
                               <tr class="<?echo $claseFila;?> centrar">
            <td class="sigma">
                            <? echo $resultado_espacios[$i][0]; ?>
            </td>
            <td class="sigma">
                            <? echo $resultado_espacios[$i][1]; ?>
            </td>
            <td class="sigma">
                            <? echo $resultado_espacios[$i][2]; ?>
            </td>
            <td class="sigma">
                            <? echo $resultado_espacios[$i][5]; ?>
            </td>
            <td class="sigma">
                            <? echo $resultado_espacios[$i][6]; ?>
            </td>
            <td class="sigma">
                            <? echo $resultado_espacios[$i][7]; ?>
            </td>
            <td class="sigma">
                            <? if ($resultado_espacios[$i][8]==0) {
                                echo "Inactivo";
                            }
                            else if($resultado_espacios[$i][8]==1) {
                                    echo "Activo";
                                }
                            ?>
            </td>
                                <? if ($resultado_espacios[$i][9]==0 or $resultado_espacios[$i][10]==0) {
                               ?><td class="sigma">
                                    En Proceso
                                  </td>
                                  <td class="sigma">
                                     <input type=checkbox name="<? echo "codEspacio".$i;?>" value="<?echo $resultado_espacios[$i][0];?>" >
                                  </td><?
                            }
                            else if($resultado_espacios[$i][9]==1 and $resultado_espacios[$i][10]==1) {
                                    ?><td class="sigma">
                                        Aprobado
                                      </td>
                                      <td class="sigma">
                                        <input type=checkbox name="<? echo "codEspacio".$i;?>" value="<?echo $resultado_espacios[$i][0];?>" >
                                      </td><?
                                }else
                                    {
                                        ?><td class="sigma">
                                            No Aprobado
                                          </td>
                                          <td class="sigma">
                                             El E.A. no se puede asociar
                                          </td><?
                                    }
                            ?>
            
        </tr>
          <?
                   }
           }
                // cierra for para ver todos los niveles
                // }
                ?>
        </table>
        <table class='contenidotabla centrar'>
            <tr class="centrar">
                <td>
                    <input type="hidden" name="nombreProyecto" value="<? echo $nombreProyecto; ?>">
                    <input type="hidden" name="planEstudio" value="<? echo $planEstudio; ?>">
                    <input type="hidden" name="codProyecto" value="<? echo $codProyecto; ?>">
                    <input type="hidden" name="id_encabezado" value="<? echo $id_encabezado; ?>">
                    <input type="hidden" name="clasificacion" value="<? echo $clasificacion; ?>">
                    <input type="hidden" name="nivel" value="<? echo $nivel; ?>">
                    <input type="hidden" name="creditos" value="<? echo $creditos; ?>">
                    <input type="hidden" name="opcion" value="agrupar">
                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                    <input class="boton" name='agrupar' value='Asociar' type='submit' >&nbsp;&nbsp;
                    <?
        $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
        $ruta="pagina=registroConsultarAgrupacionEspaciosCoordinador";
        $ruta.="&opcion=verEncabezado";
        $ruta.="&planEstudio=".$_REQUEST["planEstudio"];
        $ruta.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
        $ruta.="&codProyecto=".$_REQUEST["codProyecto"];
        $ruta.="&clasificacion=".$_REQUEST["clasificacion"];

        $ruta=$this->cripto->codificar_url($ruta,$configuracion);
        
        ?>
            <a href="<?= $indice.$ruta ?>">
                <input src="<?echo $configuracion['site'].$configuracion['grafico']?>" name='cancelar' class="boton" value='Cancelar' type='submit' >

            </a>

                </td>
            </tr>
        </table>
    </form>
        <?
                }else
                    {
                    ?>
        <tr>
            <td class="cuadro_plano centrar">
                NO EXISTEN ESPACIOS ACAD&Eacute;MICOS PARA ASOCIAR CON LAS SIGUIENTES CARACTER&Iacute;STICAS:
                <BR> CLASIFICACI&Oacute;N:<b>
                    <?
              foreach ($this->clasificaciones as $key=>$value)
              {
                if($this->clasificaciones[$key][0]==$clasificacion)
                {
                  $clas=strtr(strtoupper($this->clasificaciones[$key][1]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");
                }else{}
              }
                echo $clas;
                    ?></b>
                <BR>NIVEL: <?echo $nivel?>
                <BR>CR&Eacute;DITOS: <?echo $creditos?>
            </td>
        </tr>
        </table>
                    <?
                    }
?>
        

        

        <?
        }

    function agruparEspacios($configuracion)
    {
          //var_dump($_REQUEST);exit;
          $usuario=$this->usuario;
          $planEstudio=$_REQUEST["planEstudio"];
          $codProyecto=$_REQUEST["codProyecto"];
          $id_encabezado=$_REQUEST["id_encabezado"];
          $band=0;
         for($i=0;$i<=100;$i++)
            { 
              if($_REQUEST['codEspacio'.$i])
                {$band=1;
                 $codEspacio=$_REQUEST['codEspacio'.$i];
                 $variables=array($planEstudio, $codProyecto, $codEspacio, $id_encabezado);

                 $cadena_sql=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarEspacio", $variables);//echo $cadena_sql;exit;
                 $resultado_buscarEspacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda" );
                 
                 if(is_array($resultado_buscarEspacio)){

                 $cadena_sql_agruparEspacio=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"actualizarEstadoEspacio", $variables);
                 $resultado_agruparEspacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_agruparEspacio,"" );

                 $cadena_sql_bimestreActual=$this->sql->cadena_sql($configuracion, $this->accesoOracle, "bimestreActual", '');
                 $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_bimestreActual,"busqueda");
                 $ano=$resultadoPeriodo[0][0];
                 $periodo=$resultadoPeriodo[0][1];

                 $variablesRegistro=array($usuario, date('YmdHis'), $ano, $periodo, $id_encabezado, $planEstudio, $codProyecto,$codEspacio );
                 $cadena_sql_evento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroAgrupar",$variablesRegistro);
                 $registroEvento==$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_evento,"");
                 }else
                     {
                         $cadena_sql_agruparEspacio=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"agruparEspacio", $variables);
                         $resultado_agruparEspacio=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_agruparEspacio,"" );

                         $cadena_sql_bimestreActual=$this->sql->cadena_sql($configuracion, $this->accesoOracle, "bimestreActual", '');
                         $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_bimestreActual,"busqueda");
                         $ano=$resultadoPeriodo[0][0];
                         $periodo=$resultadoPeriodo[0][1];

                         $variablesRegistro=array($usuario, date('YmdHis'), $ano, $periodo, $id_encabezado, $planEstudio, $codProyecto );
                         $cadena_sql_evento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroAgrupar",$variablesRegistro);
                         $registroEvento==$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_evento,"");
                     }
                }
           }
           if($band==1)
                { 
                   echo "<script>alert ('La asociación de los Espacios Académicos ha sido exitosa');</script>";
                   $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                   $variable="pagina=registroConsultarAgrupacionEspaciosCoordinador";
		   $variable.="&opcion=verEncabezado";
                   $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                   $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                   $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                   $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                   
                   include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                   $this->cripto=new encriptar();
                   $variable=$this->cripto->codificar_url($variable,$configuracion);

                   echo "<script>location.replace('".$pagina.$variable."')</script>";
                }
                else 
                { 
                   echo "<script>alert ('Debe seleccionar por lo menos un Espacio Académico');</script>";
                   $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                   $variable="pagina=registroAsociarEspaciosCoordinador";
		   $variable.="&opcion=ver_registrados";
                   $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                   $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                   $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                   $variable.="&id_encabezado=".$_REQUEST["id_encabezado"];
                   $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                   $variable.="&nivel=".$_REQUEST["nivel"];
                   $variable.="&creditos=".$_REQUEST["creditos"];
                   

                   include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                   $this->cripto=new encriptar();
                   $variable=$this->cripto->codificar_url($variable,$configuracion);

                   echo "<script>location.replace('".$pagina.$variable."')</script>";
                }       
        }

    function crearEncabezado($configuracion)
    {
      
       $nombreProyecto=$_REQUEST["nombreProyecto"];
       $codProyecto=$_REQUEST["codProyecto"];
       $planEstudio=$_REQUEST["planEstudio"];       
       $clasificacion=$_REQUEST["clasificacion"];
       
        ?>
<table class='contenidotabla centrar'>
    <tr align="center">
        <td class="centrar" colspan="9">
            <h4>FORMULARIO DE CREACI&Oacute;N DE GRUPOS DE OPCIONES DE ESPACIOS ACAD&Eacute;MICOS</h4>
            <hr noshade class="hr">
        </td>
    </tr>
    <?
        #enlace regreso  listado de planes

        $indice=$configuracion["host"].$configuracion["site"]."/index.php?";
        $ruta="pagina=registroAgruparEspaciosCoordinador";
        $ruta.="&opcion=verEncabezado";
        $ruta.="&planEstudio=".$_REQUEST["planEstudio"];
        $ruta.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
        $ruta.="&codProyecto=".$_REQUEST["codProyecto"];
        $ruta.="&clasificacion=".$_REQUEST["clasificacion"];

        $ruta=$this->cripto->codificar_url($ruta,$configuracion);
        ?>
        <tr>
         <td class="centrar" colspan="9">
            <a href="javascript:history.back()">
                <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/atras.png" width="25" height="25" border="0"><br>Atras
            </a>
        </td>
        </tr>
    <tr align="center">
        <td class="centrar" colspan="9">
          Grupos con clasificaci&oacute;n: <?
              foreach ($this->clasificaciones as $key=>$value)
              {
                if($this->clasificaciones[$key][0]==$clasificacion)
                {
                  $clas=strtr(strtoupper($this->clasificaciones[$key][1]), "àáâãäåæçèéêëìíîïðñòóôõöøùüú", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÜÚ");
                }else{}
              }
                echo $clas;
                    ?><br>                        
        </td>
    </tr>     
    <tr align="center">
        <td class="cuadro_color centrar" colspan="9">
            -
        </td>
    </tr>
</table>
   <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain'
          method='GET,POST' action='index.php' name='<?echo
                  $this->formulario?>'>
    <table class='contenidotabla centrar'>
    <tr class="cuadro_plano centrar">
        <td class="cuadro_color centrar">
           Plan de Estuios:
        </td>
        <td class="cuadro_plano">
            <? echo $planEstudio." - ".$_REQUEST["nombreProyecto"];?>
        </td>
    </tr>
    <tr class="cuadro_plano centrar">
        <td class="cuadro_color centrar">
           Proyecto Curricular:
        </td>
        <td class="cuadro_plano">
            <? echo $planEstudio." - ".$_REQUEST["nombreProyecto"];?>
        </td>
    </tr>
    <tr class="cuadro_plano centrar">
        <td class="cuadro_color centrar">
           Nombre del grupo de opciones:
        </td>
        <td class="cuadro_plano">
            <input type="text" name="encabezadoNombre">
        </td>  
    </tr>
     <tr class="cuadro_plano centrar">
        <td class="cuadro_color centrar">
            Descripci&oacute;n del grupo de opciones:
        </td>
        <td class="cuadro_plano">
            <textarea name="encabezadoDescripcion"></textarea>
        </td>
    </tr>
    <tr class="cuadro_plano centrar">
        <td class="cuadro_color centrar">
            Cr&eacute;ditos del grupo:
        </td>
        <td class="cuadro_plano">
            <select name="encabezadoCreditos" id="encabezadoCreditos" style="width:50px">
                            <?                            
                            $cadena_sql_creditos=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarCreditos", $planEstudio);
                            
                            $resultadoCreditos=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_creditos,"busqueda" );

                            for($i=0;$i<count($resultadoCreditos);$i++) {
                                ?>
                    <option value="<?echo $resultadoCreditos[$i][0]?>"><?echo $resultadoCreditos[$i][0]?></option>
                                <?
                            }
                            ?>
           </select>
        </td>       
    </tr>
    <tr class="cuadro_plano centrar">
        <td class="cuadro_color centrar">
            Nivel del grupo:
        </td>
        <td class="cuadro_plano">
            <select name="encabezadoNivel" id="encabezadoNivel" style="width:50px">
                            <?

                            $cadena_sql_nivel=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"buscarNivel", $planEstudio);
                            $resultadoNivel=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_nivel,"busqueda" );

                            for($i=0;$i<count($resultadoNivel);$i++) {
                                ?>
                    <option value="<?echo $resultadoNivel[$i][0]?>"><?echo $resultadoNivel[$i][0]?></option>
                                <?
                            }
                            ?>
           </select>
        </td>
    </tr>
    <tr class="centrar">
        <td class="cuadro_plano centrar" colspan="2">
                    <input type="hidden" name="nombreProyecto" value="<? echo $nombreProyecto; ?>">
                    <input type="hidden" name="planEstudio" value="<? echo $planEstudio; ?>">
                    <input type="hidden" name="codProyecto" value="<? echo $codProyecto; ?>">                    
                    <input type="hidden" name="clasificacion" value="<? echo $clasificacion; ?>">                    
                    <input type="hidden" name="opcion" value="generar">
                    <input type="hidden" name="action" value="<?echo $this->formulario?>">
                    <input name='crear' value='Crear' type='submit' >
                </td>
            </tr>


        </table>
    </form>

        <?
        }

    function generarEncabezado($configuracion)
    {
          //var_dump($_REQUEST);exit;
          $usuario=$this->usuario;
          $planEstudio=$_REQUEST["planEstudio"];
          $codProyecto=$_REQUEST["codProyecto"];          
          $nombreProyecto=$_REQUEST["nombreProyecto"];
          $clasificacion=$_REQUEST["clasificacion"];
          $encabezadoNombre=$_REQUEST["encabezadoNombre"];
          $encabezadoDescripcion=$_REQUEST["encabezadoDescripcion"];
          $encabezadoCreditos=$_REQUEST["encabezadoCreditos"];
          $encabezadoNivel=$_REQUEST["encabezadoNivel"];
               
          if($_REQUEST['encabezadoNombre'] and $_REQUEST["encabezadoDescripcion"] and $_REQUEST["encabezadoCreditos"] and $_REQUEST["encabezadoNivel"])
                {$band=1;                 
                 
                }
           
           if($band==1)
                {                 
                 $variables=array($encabezadoNombre, $encabezadoDescripcion, $planEstudio, $codProyecto, $clasificacion, $encabezadoCreditos, $encabezadoNivel);
                 $cadena_sql_crearEncabezado=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"crearEncabezado", $variables);
                 $resultadoCrearEncabezado=$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_crearEncabezado,"" );
                   
                 $cadena_sql_bimestreActual=$this->sql->cadena_sql($configuracion, $this->accesoOracle, "bimestreActual", '');                 
                 $resultadoPeriodo=$this->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql_bimestreActual,"busqueda");
                 $ano=$resultadoPeriodo[0][0];
                 $periodo=$resultadoPeriodo[0][1];

                 $variablesRegistro=array($usuario, date('YmdGis'), $ano, $periodo, $encabezadoNombre, $planEstudio, $codProyecto );
                 $cadena_sql_evento=$this->sql->cadena_sql($configuracion,$this->accesoGestion,"registroNombreGeneral",$variablesRegistro);
                 $registroEvento==$this->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql_evento,"");

                   echo "<script>alert ('El Encabezado de Espacios Académicos ha sido registrado de forma exitosa');</script>";
                   $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                   $variable="pagina=registroAgruparEspaciosCoordinador";
		   $variable.="&opcion=verEncabezado";
                   $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                   $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                   $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                   $variable.="&clasificacion=".$_REQUEST["clasificacion"];

                   include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                   $this->cripto=new encriptar();
                   $variable=$this->cripto->codificar_url($variable,$configuracion);

                   echo "<script>location.replace('".$pagina.$variable."')</script>";
                }
                else
                {
                   echo "<script>alert ('Debe utilizar todos los campos');</script>";
                   $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                   $variable="pagina=registroAgruparEspaciosCoordinador";
		   $variable.="&opcion=crear";
                   $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                   $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                   $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                   $variable.="&clasificacion=".$_REQUEST["clasificacion"];


                   include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                   $this->cripto=new encriptar();
                   $variable=$this->cripto->codificar_url($variable,$configuracion);

                   echo "<script>location.replace('".$pagina.$variable."')</script>";
                }
        }

    }

?>