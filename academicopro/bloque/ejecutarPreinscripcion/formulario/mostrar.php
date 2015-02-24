<?

$this->forma="realizarPreinscripcion";



?>


<form enctype='multipart/form-data' method='POST' action='index.php' name='<? echo $this->forma?>'>
    <table class='formulario' align='center'>
        <tr  class='bloquecentralencabezado'>
            <td align='center'>
                <p>Reporte del Proceso de Preinscripci&oacute;n</p>
            </td>
        </tr>
    </table>
    <br>
    <table class='formulario' align='center'>
        <tr class='bloquecentralencabezado'>
            <td colspan="2" align='center'>
                <p>Proyecto Curricular</p>
            </td>
        </tr>
        <tr class='cuadro_color'>
            <td width="50%" align="center">
                <p>C&oacute;digo :<? echo " ".$datos["proyectoCurricular"];?></p>
            </td>
            <td width="50%" align="center">
                <p>Plan de Estudios : <? echo " ".$datos["planEstudios"];?></p>
            </td>
        </tr>
        <tr class='cuadro_color'>
            <td colspan="2" align="center">
                <p>Pre-inscripci&oacute;n realizada para el periodo <? echo $datos["anno"];?> - <? echo $datos["periodo"];?></p>
            </td>
        </tr>
    </table>
    <br>
    <table class='formulario' align='center'>
        <tr class='bloquecentralencabezado'>
            <td colspan="2" align='center'>
                <p>Reporte</p>
            </td>
        </tr>
        <tr class='cuadro_color'>
            <td>
                <p>Cantidad de C&oacute;digos procesados: <? echo " ".$datos["totalEstudiantes"];?></p>
            </td>
        </tr>
        <tr class='cuadro_color'>
            <td>
                <p>N&uacute;mero de estudiantes con Espacios Acad&eacute;micos inscritos :<? echo " ".$datos["totalEstudiantesConEA"];?></p>
            </td>
        </tr>
        <tr class='cuadro_color'>
            <td>
                <p>N&uacute;mero de Espacios Acad&eacute;micos inscritos :<? echo " ".$datos["totalEspacios"];?></p>
            </td>
        </tr>
    </table>
    <br>
    <table class='formulario' align="center" >
        <tr>
            <td align='center'>
                <p>Si los datos presentados son satisfactorios y desea oficializar el Proceso de Preinscripci&oacute;n</p>
                <p>seleccione <font class='bloquecentralencabezado'>Publicar</font>, de lo contrario seleccione <font class='bloquecentralencabezado'>Borrar Datos</font></p>
            </td>
        </tr>
        <tr class='cuadro_color'>
            <td>
                <table class='cuadro_color' align='center' width='100%'>
                    <tr class='bloquecentralencabezado'>
                        <td align="center" width='50%'>
                            <input type='hidden' name='action' value='<? echo $this->forma ?>'>
                            <input type='hidden' name='id_usuario' value='<? echo $id_usuario ?>'>
                            <input type='hidden' name='guardar' value='guardar'>
                            <input type="hidden" name='carrera' value="<?echo $datos["proyectoCurricular"]?>">
                            <input type="hidden" name='planEstudio' value="<?echo $datos["planEstudios"]?>">
                            <input type="hidden" name='anno' value="<?echo $datos["anno"]?>">
                            <input type="hidden" name='periodo' value="<?echo $datos["periodo"]?>">
                            <input value="Publicar" name="aceptar" tabindex='<? echo $tab++ ?>' type="submit"/><br>
                        </td>
                        <td align="center" width='50%'>
                            <input name='borrar' value='Borrar Datos' type="submit" tabindex='<? echo $tab++ ?>'  /><br>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br>
    <table class='formulario' align='center'>
        <tr class='bloquecentralencabezado'>
            <td colspan="4" align='center'>
                <p>Observaciones del proceso</p>
            </td>
        </tr>
        <?
                if($resultado_errores[0][0]) {
                    ?>
        <tr class='cuadro_color' align='center'>
            <td width="15%">
                <p>C&oacute;digo del estudiante</p>
            </td>
            <td width="15%">
                <p>C&oacute;digo Espacio Acad&eacute;mico</p>
            </td>
            <td width="15%">
                <p>Grupo</p>
            </td>
            <td width="55%">
                <p>Observaciones</p>
            </td>
        </tr>
            <?
                    include_once($configuracion["raiz_documento"].$configuracion["clases"]."/cadenas.class.php");
                    $cadenas=new cadenas();

                    $er=0;
                    while($resultado_errores[$er][0]) {
                        ?>
        <tr align="center" >
            <td width="15%">
                <?
                                echo $resultado_errores[$er][0];
                                ?>
            </td>
            <td width="15%">
                <?
                                echo $resultado_errores[$er][1];
                                ?>
            </td>
            <td width="15%">
                <?
                                echo $resultado_errores[$er][2];
                                ?>
            </td>
            <td width="55%">
                <?
                                if($resultado_errores[$er][3]=='1')
                                {
                                    echo "Inscrito";
                                }
                                else{
                                echo $tipo=$cadenas->formatohtml(($resultado_errores[$er][3]));}
                                ?>
            </td>
        </tr>
                <?
                        $er++;
                    }
                    if($resultado_cuposDisponibles[0][0]){
                        ?>
        <table class='formulario centrar' width="70%">
            <tr class='bloquecentralencabezado'>
                <td colspan="4" align='center'>Observaciones de Cupos Disponibles</td></tr>

                    <tr class='cuadro_color' align='center'>
            <td width="25%">
                <p>C&oacute;digo del Espacio</p>
            </td>
            <td width="25%">
                <p>Grupo</p>
            </td>
            <td width="25%">
                <p>Cupos Antes</p>
            </td>
            <td width="25%">
                <p>Cupos Despu&eacute;s</p>
            </td>
        </tr>

                        <?
                        $cup=0;
                        while ($resultado_cuposDisponibles[$cup][0]){


                        ?>
        <tr align="center" >
            <td width="25%">
                <?
                                echo $resultado_cuposDisponibles[$cup][0];
                                ?>
            </td>
            <td width="25%">
                <?
                                echo $resultado_cuposDisponibles[$cup][1];
                                ?>
            </td>
            <td width="25%">
                <?
                                echo $resultado_cuposDisponibles[$cup][2];
                                ?>
            </td>
            <td width="25%">
                <?
                                echo $resultado_cuposDisponibles[$cup][3];
                                ?>
            </td>
        </tr>
                <?
                        $cup++;
                    }
                    ?>    </table>
        <?
        
        }
                    
                    ?>
        <table class='formulario centrar' width="70%">
            <tr class='bloquecentralencabezado'>
                <td colspan="2" align='center'>Cupos sin asignar por Espacio Acad&eacute;mico</td></tr>
            
            <?  $k=0;
                        //var_dump($resultado_erroresConteo);
        $cadena_sql=$this->sql->cadena_ejPre_sql($configuracion, $conexion, "buscarErroresEspacios",$datos);//echo "errores".$cadena_sql;exit;
        $resultado_erroresEspacios=$this->ejecutarSQL($configuracion, $conexion, $cadena_sql, "busqueda");
if($resultado_erroresEspacios){




for($i=0;$i<count($resultado_erroresEspacios);$i++) {
        $datos["idEspacio"]=$resultado_erroresEspacios[$i][0];
        $cadena_sql=$this->sql->cadena_ejPre_sql($configuracion, $conexion, "buscarErroresConteo",$datos);//echo "errores".$cadena_sql;exit;
        $resultado_erroresConteo=$this->ejecutarSQL($configuracion, $conexion, $cadena_sql, "busqueda");

                            ?><tr>
                <td class="cuadro_plano">
                <?echo $resultado_erroresEspacios[$i][0]." - ".$resultado_erroresEspacios[$i][1]?>
                </td>
                <td class="cuadro_plano centrar">
                <?echo $resultado_erroresConteo[0][0]?>
                </td>
            </tr>
                <?
                        }

                    
                    }
                    else {
                        ?>
            <tr class='cuadro_color' align='center'>
                <td>
                    <p><font class='bloquecentralencabezado'>No hay observaciones del proceso</font></p>
                </td>
            </tr>
        <?
                    }


                    ?>


        </table>
    </table>    
</form>
<?
                }