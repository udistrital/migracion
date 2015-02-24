<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description: Esta clase permite tener control sobre los diferentes modulos desarrollados
 *  por el equipo del Sistema de Gestión Académica
 *
 * @author Edwin Sanchez
 */

class administrarModulo {

    // Esta funcion retorna el promedio ponderado por periodo academico
    public function AdministrarModuloSGA($configuracion, $modulo) {
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

        $this->funcion=new funcionGeneral();

        $this->accesoOracle=$this->funcion->conectarDB($configuracion,"oraclesga");

        $this->accesoGestion=$this->funcion->conectarDB($configuracion,"mysqlsga");

        $this->acceso_db=$this->funcion->conectarDB($configuracion,"");

        $this->usuario=$this->funcion->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->nivel=$this->funcion->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        //echo $this->nivel;exit;

        if($this->nivel!=NULL)
                {

        switch($modulo){

        case '2':

        if($this->nivel==28) {
            $cadena_sql=$this->cadena_sql($configuracion,$this->accesoOracle, "carrera_coordinador",$this->usuario);
            $resultado_carrera=$this->funcion->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

            $proyecto=$resultado_carrera[0][0];
            $planEstudio=$resultado_carrera[0][1];

            $variable=array($proyecto,$planEstudio,$modulo);

            $cadena_sql=$this->cadena_sql($configuracion,$this->accesoGestion, "estado_modulo",$variable);//echo $cadena_sql;exit;
            $resultado_estado=$this->funcion->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda");

            switch($resultado_estado[0][0]) {
                case '0':
                       ?>
                        <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                            <tr align="center">
                                <td class="centrar" colspan="4">
                                    <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/OAS.png ">
                                </td>
                            </tr>
                            <tr align="center">
                                <td class="centrar" colspan="4">
                                    <h4>OFICINA ASESORA DE SISTEMAS</h4>
                                    <hr noshade class="hr">

                                </td>
                            </tr><br><br>
                            <tr>
                                <td  width="50%">
                                    <font size="2">Respetados Coordinadores de Proyectos Curriculares: Para garantizar el proceso de preinscripci&oacute;n tenga en cuenta los siguientes requisitos para habilitar este modulo:</font>
                                </td>
                            </tr>
                            <tr >
                                <td  width="50%">
                                    <font size="2">1. Deben estar generados los horarios de todos los grupos que se utilizaran en la preinscripci&oacute;n.</font>
                                    <br>
                                </td>
                            </tr>
                            <tr class="centrar">
                                <td  width="50%">
                                    <?
                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                        $variables="pagina=adminEspaciosHorariosProyecto";
                                        $variables.="&opcion=horario";

                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                        $this->cripto=new encriptar();
                                        $variables=$this->cripto->codificar_url($variables,$configuracion);
                                        ?>
                                        <a href="<?echo $pagina.$variables?>">
                                            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/vcalendar.png" width="35" height="35" border="0"><br>Ver Horarios
                                        </a>
                                </td>
                            </tr>
                            <tr>
                                <td  width="50%">
                                    <font size="2">2. Corregir las inconsistencias de los estudiantes en cr&eacute;ditos.</font>
                                </td>
                            </tr>
                            <tr class="centrar">
                                <td  width="50%">
                                    <?
                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                        $variables="pagina=registroinconsistenciasEstudiantes";
                                        $variables.="&opcion=seleccionarPlan";

                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                        $this->cripto=new encriptar();
                                        $variables=$this->cripto->codificar_url($variables,$configuracion);
                                        ?>
                                        <a href="<?echo $pagina.$variables?>">
                                            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/kchart_chrt.png" width="35" height="35" border="0"><br>Ver Inconsistencias
                                        </a>
                                </td>
                            </tr>
                            <tr>
                                <td  width="50%">
                                    <font size="2">3. Comunicarse con la oficina asesora de sistemas al telefono <b>3238400 ext 1110</b>, para habilitar el ingreso al modulo de preinscripciones y brindar la orientaci&oacute;n pertinente.</font>
                                    <br>
                                </td>
                            </tr>
                            <tr class="centrar">
                                <td  width="50%"><br><br><br>
                                    <font size="2"><b>Tenga en cuenta que la preinscripcion es el proceso automatico que inscribe los espacios acad&eacute;micos unicamente de estudiantes reprobados en los horarios creados hasta le fecha</b></font>
                                    <br>
                                </td>
                            </tr>

                        </table>
                    <?exit;
                    break;
                case '1':

                        return true;
                    break;

                case '2':
                    ?>
                        <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                            <tr align="center">
                                <td class="centrar" colspan="4">
                                    <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/OAS.png ">
                                </td>
                            </tr>
                            <tr align="center">
                                <td class="centrar" colspan="4">
                                    <h4>OFICINA ASESORA DE SISTEMAS</h4>
                                    <hr noshade class="hr">

                                </td>
                            </tr><br><br>
                            <tr class="centrar" width="50%">
                                <td>
                                    <font size="2"><b>El modulo que acaba de seleccionar aparece como completado, si no es asi y desea realizar cambio por favor comuniquese con la oficina asesora de sistemas al telefono 3238400 ext:1110 o ext:1113</b></font>
                                </td>
                            </tr>
                        </table>
                        <?exit;
                    break;

            }
                exit;


            }

            break;

        case '4':

            if($this->nivel==52) {
            $cadena_sql=$this->cadena_sql($configuracion,$this->accesoOracle, "carrera_estudiante",$this->usuario);
            $resultado_carrera=$this->funcion->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

            $proyecto=$resultado_carrera[0][0];
            $planEstudio=$resultado_carrera[0][1];

            $variable=array($proyecto,$planEstudio,$modulo);


            $cadena_sql=$this->cadena_sql($configuracion,$this->accesoGestion, "estado_modulo",$variable);
            $resultado_estado=$this->funcion->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda");

            switch($resultado_estado[0][0]) {
                case '0':
                       ?>
                        <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                            <tr align="center">
                                <td class="centrar" colspan="4">
                                    <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/OAS.png ">
                                </td>
                            </tr>
                            <tr align="center">
                                <td class="centrar" colspan="4">
                                    <h4>OFICINA ASESORA DE SISTEMAS</h4>
                                    <hr noshade class="hr">

                                </td>
                            </tr><br><br>
                            <tr class="centrar" >
                                <td  width="50%" colspan="4">
                                    <font size="1">Respetados Estudiantes de primero y segundo semestre: La Oficina Asesora de Sistemas les comunica que por este mismo medio se informar&aacute; la fecha y hora de inicio del proceso de adiciones y cancelaciones con 24 horas de anticipaci&oacute;n</font>
                                </td>
                            </tr>
                            <tr class="centrar" >
                                <td  width="50%" colspan="4">
                                    <font size="1">A continuaci&oacute;n se relaciona la fecha de adiciones y cancelaciones de su proyecto curricular.</font>
                                </td>
                            </tr>
                            
                                <?
                                    $cadena_sql=$this->cadena_sql($configuracion,$this->accesoGestion, "fechas_de_adiciones",$variable);//echo $cadena_sql;exit;
                                    $resultado_fecha=$this->funcion->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda");

                                    if($resultado_fecha[0][0]!=NULL)
                                        {
                                            ?>
                                                <tr class="centrar" >
                                                    <td>
                                                        Proyecto Curricular
                                                    </td>
                                                    <td>
                                                        Dia
                                                    </td>
                                                    <td>
                                                        Hora
                                                    </td>
                                                </tr>
                                            <?
                                    for($i=0;$i<count($resultado_fecha);$i++)
                                        {
                                            ?>
                                                <tr class="centrar">
                                                    <td class="cuadro_plano centrar">
                                                        <?echo $resultado_fecha[$i][0]?>
                                                    </td>
                                                    <td class="cuadro_plano centrar">
                                                        <?echo $resultado_fecha[$i][1]?>
                                                    </td>
                                                    <td class="cuadro_plano centrar">
                                                        <?echo $resultado_fecha[$i][2]?>
                                                    </td>
                                                </tr>
                                            <?
                                        }

                                        }else
                                            {
                                               ?>
                                                <tr>
                                                    <td class="cuadro_plano centrar" colspan="4">
                                                    No hay fechas publicadas en este momento
                                                    </td>
                                                </tr>

                                                <?
                                            }
                                ?>
                            
                        </table>
                    <?exit;
                    break;
                case '1':

                        return true;
                    break;

                case '2':
                    ?>
                        <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                            <tr align="center">
                                <td class="centrar" colspan="4">
                                    <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/OAS.png ">
                                </td>
                            </tr>
                            <tr align="center">
                                <td class="centrar" colspan="4">
                                    <h4>OFICINA ASESORA DE SISTEMAS</h4>
                                    <hr noshade class="hr">

                                </td>
                            </tr><br><br>
                            <tr class="centrar" width="50%">
                                <td>
                                    <font size="2"><b>El modulo que acaba de seleccionar aparece como cerrado</b></font>
                                </td>
                            </tr>
                        </table>
                        <?exit;
                    break;

            }
                exit;


        }else if($this->nivel==28) {
            $cadena_sql=$this->cadena_sql($configuracion,$this->accesoOracle, "carrera_coordinador",$this->usuario);
            $resultado_carrera=$this->funcion->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

            $proyecto=$resultado_carrera[0][0];
            $planEstudio=$resultado_carrera[0][1];

            $variable=array($proyecto,$planEstudio,$modulo);

            $cadena_sql=$this->cadena_sql($configuracion,$this->accesoGestion, "estado_modulo",$variable);//echo $cadena_sql;exit;
            $resultado_estado=$this->funcion->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda");

            switch($resultado_estado[0][0]) {
                case '0':
                       ?>
                        <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                            <tr align="center">
                                <td class="centrar" colspan="4">
                                    <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/OAS.png ">
                                </td>
                            </tr>
                            <tr align="center">
                                <td class="centrar" colspan="4">
                                    <h4>OFICINA ASESORA DE SISTEMAS</h4>
                                    <hr noshade class="hr">

                                </td>
                            </tr><br><br>
                            <tr>
                                <td  width="50%">
                                    <font size="2">Respetados Coordinadores de Proyectos Curriculares: Para garantizar el proceso de adiciones y cancelaciones tenga en cuenta los siguientes requisitos para habilitar este modulo:</font>
                                </td>
                            </tr>
                            <tr >
                                <td  width="50%">
                                    <font size="2">1. Deben estar generados los horarios de todos los grupos que se utilizar&aacute;n en el proceso de adiciones y cancelaciones.</font>
                                    <br>
                                </td>
                            </tr>
                            <tr class="centrar">
                                <td  width="50%">
                                    <?
                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                        $variables="pagina=adminEspaciosHorariosProyecto";
                                        $variables.="&opcion=horario";

                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                        $this->cripto=new encriptar();
                                        $variables=$this->cripto->codificar_url($variables,$configuracion);
                                        ?>
                                        <a href="<?echo $pagina.$variables?>">
                                            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/vcalendar.png" width="35" height="35" border="0"><br>Ver Horarios
                                        </a>
                                </td>
                            </tr>
                            <tr>
                                <td  width="50%">
                                    <font size="2">2. Corregir las inconsistencias de los estudiantes en cr&eacute;ditos.</font>
                                </td>
                            </tr>
                            <tr class="centrar">
                                <td  width="50%">
                                    <?
                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                        $variables="pagina=registroinconsistenciasEstudiantes";
                                        $variables.="&opcion=seleccionarPlan";

                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                        $this->cripto=new encriptar();
                                        $variables=$this->cripto->codificar_url($variables,$configuracion);
                                        ?>
                                        <a href="<?echo $pagina.$variables?>">
                                            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/kchart_chrt.png" width="35" height="35" border="0"><br>Ver Inconsistencias
                                        </a>
                                </td>
                            </tr>
                            <tr>
                                <td  width="50%">
                                    <font size="2">3. Comunicarse con la oficina asesora de sistemas al telefono <b>3238400 ext 1110</b>, para habilitar el ingreso al modulo de adiciones y cancelaciones y brindar la orientaci&oacute;n pertinente.</font>
                                    <br>
                                </td>
                            </tr>
                            <tr class="centrar">
                                <td  width="50%"><br><br><br>
                                    <font size="2"><b>Tenga en cuenta que el proceso de adiciones y cancelaciones por este sistema es unicamente de estudiantes que pertenecen a cr&eacute;ditos</b></font>
                                    <br>
                                </td>
                            </tr>

                        </table>
                    <?exit;
                    break;
                case '1':

                        return true;
                    break;

                case '2':
                    ?>
                        <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                            <tr align="center">
                                <td class="centrar" colspan="4">
                                    <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/OAS.png ">
                                </td>
                            </tr>
                            <tr align="center">
                                <td class="centrar" colspan="4">
                                    <h4>OFICINA ASESORA DE SISTEMAS</h4>
                                    <hr noshade class="hr">

                                </td>
                            </tr><br><br>
                            <tr class="centrar" width="50%">
                                <td>
                                    <font size="2"><b>El modulo que acaba de seleccionar aparece como cerrado, si desea realizar cambios por favor comuniquese con la oficina asesora de sistemas al telefono 3238400 ext:1110 o ext:1113</b></font>
                                </td>
                            </tr>
                        </table>
                        <?exit;
                    break;

            }
                exit;


            }


            break;

        case '3':

        if($this->nivel==28) {
            $cadena_sql=$this->cadena_sql($configuracion,$this->accesoOracle, "carrera_coordinador",$this->usuario);
            $resultado_carrera=$this->funcion->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_sql,"busqueda");

            $proyecto=$resultado_carrera[0][0];
            $planEstudio=$resultado_carrera[0][1];

            $variable=array($proyecto,$planEstudio,$modulo);

            $cadena_sql=$this->cadena_sql($configuracion,$this->accesoGestion, "estado_modulo",$variable);//echo $cadena_sql;exit;
            $resultado_estado=$this->funcion->ejecutarSQL($configuracion, $this->accesoGestion, $cadena_sql,"busqueda");

            switch($resultado_estado[0][0])
            {
                case '0':
                       ?>
                        <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                            <tr align="center">
                                <td class="centrar" colspan="4">
                                    <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/OAS.png ">
                                </td>
                            </tr>
                            <tr align="center">
                                <td class="centrar" colspan="4">
                                    <h4>OFICINA ASESORA DE SISTEMAS</h4>
                                    <hr noshade class="hr">

                                </td>
                            </tr><br><br>
                            <tr>
                                <td  width="50%">
                                    <font size="2">Respetados Coordinadores de Proyectos Curriculares: Para garantizar el proceso de inscripci&oacute;n de admitidos tenga en cuenta los siguientes requisitos para habilitar este modulo:</font>
                                </td>
                            </tr>
                            <tr >
                                <td  width="50%">
                                    <font size="2">1. Deben estar generados los horarios de todos los grupos que se utilizaran en la inscripci&oacute;n de admitidos.</font>
                                    <br>
                                </td>
                            </tr>
                            <tr class="centrar">
                                <td  width="50%">
                                    <?
                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                        $variables="pagina=adminEspaciosHorariosProyecto";
                                        $variables.="&opcion=horario";

                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                        $this->cripto=new encriptar();
                                        $variables=$this->cripto->codificar_url($variables,$configuracion);
                                        ?>
                                        <a href="<?echo $pagina.$variables?>">
                                            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/vcalendar.png" width="35" height="35" border="0"><br>Ver Horarios
                                        </a>
                                </td>
                            </tr>
                            <tr>
                                <td  width="50%">
                                    <font size="2">2. Corregir las inconsistencias de los estudiantes en cr&eacute;ditos.</font>
                                </td>
                            </tr>
                            <tr class="centrar">
                                <td  width="50%">
                                    <?
                                        $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                        $variables="pagina=registroinconsistenciasEstudiantes";
                                        $variables.="&opcion=seleccionarPlan";

                                        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                        $this->cripto=new encriptar();
                                        $variables=$this->cripto->codificar_url($variables,$configuracion);
                                        ?>
                                        <a href="<?echo $pagina.$variables?>">
                                            <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/kchart_chrt.png" width="35" height="35" border="0"><br>Ver Inconsistencias
                                        </a>
                                </td>
                            </tr>
                            <tr>
                                <td  width="50%">
                                    <font size="2">3. Comunicarse con la oficina asesora de sistemas al telefono <b>3238400 ext 1110</b>, para habilitar el ingreso al modulo de inscripci&oacute;n de admitidos y brindar la orientaci&oacute;n pertinente.</font>
                                    <br>
                                </td>
                            </tr>
                            <tr class="centrar">
                                <td  width="50%"><br><br><br>
                                    <font size="2"><b>Tenga en cuenta que la inscripci&oacute;n se debe realizar en caso de no haberla realizado por la aplicaci&oacute;n acad&eacute;mica</b></font>
                                    <br>
                                </td>
                            </tr>

                        </table>
                    <?exit;
                    break;
                case '1':

                        return true;
                    break;

                case '2':
                    ?>
                        <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                            <tr align="center">
                                <td class="centrar" colspan="4">
                                    <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/OAS.png ">
                                </td>
                            </tr>
                            <tr align="center">
                                <td class="centrar" colspan="4">
                                    <h4>OFICINA ASESORA DE SISTEMAS</h4>
                                    <hr noshade class="hr">

                                </td>
                            </tr><br><br>
                            <tr class="centrar" width="50%">
                                <td>
                                    <font size="2"><b>El modulo que acaba de seleccionar aparece como completado, si no es asi y desea realizar cambio por favor comuniquese con la oficina asesora de sistemas al telefono 3238400 ext:1110 o ext:1113</b></font>
                                </td>
                            </tr>
                        </table>
                        <?exit;
                    break;

            }
            
        }
        break;
        }

        }else
            {
                    ?>
                        <table class='contenidotabla centrar' background="<?echo $configuracion['site'].$configuracion['grafico']?>/escudo_fondo.png" style="background-attachment:fixed; background-repeat:no-repeat; background-position:top">
                            <tr align="center">
                                <td class="centrar" colspan="4">
                                    <h4>SISTEMA DE GESTI&Oacute;N ACAD&Eacute;MICA</h4>
                                    <img src="<?echo $configuracion['site'].$configuracion['grafico']?>/OAS.png ">
                                </td>
                            </tr>
                            <tr align="center">
                                <td class="centrar" colspan="4">
                                    <h4>OFICINA ASESORA DE SISTEMAS</h4>
                                    <hr noshade class="hr">

                                </td>
                            </tr><br><br>
                            <tr class="centrar" width="50%">
                                <td>
                                    <font size="2"><b>Por favor intente de nuevo</b></font>
                                </td>
                            </tr>
                        </table>
                        <?exit;
            }
    }

        function cadena_sql($configuracion,$conexion,$opcion,$variable="") {

            switch($opcion) {
                case "carrera_coordinador":
                    $cadena_sql="SELECT DISTINCT ";
                    $cadena_sql.="CRA_COD, ";
                    $cadena_sql.="PEN_NRO ";
                    $cadena_sql.="FROM ACCRA ";
                    $cadena_sql.="INNER JOIN GEUSUCRA ";
                    $cadena_sql.="ON ACCRA.CRA_COD = ";
                    $cadena_sql.="GEUSUCRA.USUCRA_CRA_COD ";
                    $cadena_sql.="INNER JOIN ACPEN ";
                    $cadena_sql.="ON ACCRA.CRA_COD = ";
                    $cadena_sql.="ACPEN.PEN_CRA_COD ";
                    $cadena_sql.="WHERE ";
                    $cadena_sql.="GEUSUCRA.USUCRA_NRO_IDEN = ";
                    $cadena_sql.=$variable." ";
                    //$cadena_sql.="'".$variable."' ";
                    $cadena_sql.="AND PEN_NRO > 200 ";

                    //echo $cadena_sql;
                    //exit;
                    break;

                case "carrera_estudiante":

                    $cadena_sql="select est_cra_cod, est_pen_nro ";
                    $cadena_sql.="from acest ";
                    $cadena_sql.=" where est_cod= ";
                    $cadena_sql.=$variable." ";
                    break;

                case "estado_modulo":


                    $cadena_sql="SELECT modulos_idEstado ";
                    $cadena_sql.="FROM ".$configuracion['prefijo']."modulosProyecto ";
                    $cadena_sql.=" WHERE modulos_idProyectoCurricular= ".$variable[0];
                    $cadena_sql.=" AND modulos_idPlanEstudio = ".$variable[1];
                    $cadena_sql.=" AND modulos_idModulo = ".$variable[2];
                    break;

                case "fechas_de_adiciones":


                    $cadena_sql="SELECT fecha_idProyecto, date_format(fecha_dia,'%Y/%m/%d'), fecha_hora ";
                    $cadena_sql.="FROM ".$configuracion['prefijo']."fechaAdiciones ";
                    $cadena_sql.=" WHERE fecha_idProyecto= ".$variable[0];
                    $cadena_sql.=" ORDER BY fecha_idProyecto ";
                    break;
            }


            //echo $cadena_sql."<br>";
            return $cadena_sql;

        }

    }
    ?>
