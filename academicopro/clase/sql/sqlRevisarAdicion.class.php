<?php


class sqlRevisarAdicion {
    function cadena_revisarAdicion($configuracion, $tipo, $variable) {


        switch($tipo) {

        //----------------Creditos-------------------------

            case "revisarInfoCreditos":
                $cadena_sql="SELECT ";
                $cadena_sql.="semestre_nroCreditosEstudiante ";
                $cadena_sql.="FROM ";
                $cadena_sql.=$configuracion["prefijo"]."semestre_creditos_estudiante ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="semestre_codEstudiante = ";
                $cadena_sql.="'".$variable["codigoEstudiante"]."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="semestre_ano = ";
                $cadena_sql.="'".$variable["anno"]."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="semestre_periodo = ";
                $cadena_sql.="'".$variable["periodo"]."'";
                break;

            case "insertarCreditos":
                $cadena_sql="INSERT INTO ";
                $cadena_sql.=$configuracion["prefijo"]."semestre_creditos_estudiante ";
                $cadena_sql.="(semestre_codEstudiante, ";
                $cadena_sql.="semestre_idProyectoCurricular, ";
                $cadena_sql.="semestre_idPlanEstudio, ";
                $cadena_sql.="semestre_ano, ";
                $cadena_sql.="semestre_periodo, ";
                $cadena_sql.="semestre_nroCreditosEstudiante ";
                $cadena_sql.=") ";
                $cadena_sql.="VALUES ";
                $cadena_sql.="(";
                $cadena_sql.="'".$variable["codigoEstudiante"]."', ";
                $cadena_sql.="'".$variable["proyectoCurricular"]."', ";
                $cadena_sql.="'".$variable["planEstudios"]."', ";
                $cadena_sql.="'".$variable["anno"]."', ";
                $cadena_sql.="'".$variable["periodo"]."', ";
                $cadena_sql.="'".$variable["creditos"]."')";
                //$cadena_sql.=" ";

                break;


            case "actualizarCreditos":
                $cadena_sql="UPDATE ";
                $cadena_sql.=$configuracion["prefijo"]."semestre_creditos_estudiante ";
                $cadena_sql.="SET ";
                $cadena_sql.="semestre_nroCreditosEstudiante = ";
                $cadena_sql.="'".$variable["creditos"]."' ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="semestre_codEstudiante = ";
                $cadena_sql.="'".$variable["codigoEstudiante"]."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="semestre_idProyectoCurricular = ";
                $cadena_sql.="'".$variable["proyectoCurricular"]."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="semestre_idPlanEstudio = ";
                $cadena_sql.="'".$variable["planEstudios"]."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="semestre_ano = ";
                $cadena_sql.="'".$variable["anno"]."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="semestre_periodo = ";
                $cadena_sql.="'".$variable["periodo"]."'";
                //$cadena_sql.=" ";

                break;

            case "revisaCreditos":
                $cadena_sql="SELECT ";
                $cadena_sql.="`nota_creditos` ";
                $cadena_sql.="FROM ";
                $cadena_sql.=$configuracion["prefijo"]."nota_reprobados ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="nota_codEstudiante = ".$variable;
                break;


            //------------------------------------Espacios Academicos---------------------------------

            case "verificarCupoEA":
                $cadena_sql="SELECT ";
                $cadena_sql.="cupos_despues ";
                $cadena_sql.="FROM ";
                $cadena_sql.=$configuracion["prefijo"]."cupos_preinscripcion ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="cupos_idEspacio = ";
                $cadena_sql.="'".$variable[0]."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="cupos_grupo = ";
                $cadena_sql.="'".$variable[1]."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="cupos_idProyectoCurricular = ";
                $cadena_sql.="'".$variable[2]."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="cupos_idPlanestudio = ";
                $cadena_sql.="'".$variable[3]."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="cupos_ano = ";
                $cadena_sql.="'".$variable[4]."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="cupos_periodo = ";
                $cadena_sql.="'".$variable[5]."' ";
                //                                $cadena_sql.=" ";
                //                                $cadena_sql.=" ";
                //                                $cadena_sql.=" ";
                break;



            case "rescatarHorario":
            //ORACLE
                $cadena_sql="SELECT ";
                $cadena_sql.="HOR_NRO, ";
                $cadena_sql.="HOR_DIA_NRO, ";
                $cadena_sql.="HOR_HORA, ";
                $cadena_sql.="HOR_ASI_COD ";

                $cadena_sql.="FROM ";
                $cadena_sql.="ACHORARIO ";
                $cadena_sql.="INNER JOIN ";
                $cadena_sql.="ACCURSO ";
                $cadena_sql.="ON (";
                $cadena_sql.="ACHORARIO.HOR_NRO = ";
                $cadena_sql.="ACCURSO.CUR_NRO) ";
                $cadena_sql.="AND (";
                $cadena_sql.="ACHORARIO.HOR_ASI_COD = ";
                $cadena_sql.="ACCURSO.CUR_ASI_COD) ";
                $cadena_sql.="WHERE ";

                $total=count($variable);

                for($i=0;$i<$total;$i++) {

                    $cadena_sql.="( ";
                    $cadena_sql.="ACHORARIO.HOR_ASI_COD = ";
                    $cadena_sql.="'".$variable[$i][0]."' ";//Cod Esp Acad
                    $cadena_sql.="AND ";
                    $cadena_sql.="ACCURSO.CUR_CRA_COD = ";
                    $cadena_sql.="'".$variable[$i][1]."' ";//Cod Cra
                    $cadena_sql.="AND ";
                    $cadena_sql.="ACHORARIO.HOR_NRO = ";
                    $cadena_sql.="'".$variable[$i][2]."' ";//Cod Grupo
                    $cadena_sql.=") ";
                    $cadena_sql.="OR ";

                }

                $cadena_sql=substr($cadena_sql,0,strlen(($cadena_sql))-3);
                $cadena_sql.="ORDER BY ";
                $cadena_sql.="2, 3";
                //				$cadena_sql.=" ";
                //				$cadena_sql.="id_mes=".$mes." ";
                //echo $cadena_sql;
                //exit;

                break;


            case "buscarGruposEA":
            //ORACLE
                $cadena_sql="SELECT ";
                $cadena_sql.="CUR_NRO ";
                $cadena_sql.="FROM ";
                $cadena_sql.="ACCURSO ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="ACCURSO.CUR_ASI_COD = ";
                $cadena_sql.="'".$variable[5]."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="ACCURSO.CUR_APE_ANO = ";
                $cadena_sql.="'".$variable[2]."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="ACCURSO.CUR_APE_PER = ";
                $cadena_sql.="'".$variable[3]."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="ACCURSO.CUR_CRA_COD = ";
                $cadena_sql.="'".$variable[0]."' ";
                break;



            case "buscarGrupoEA":
            //ORACLE
                $cadena_sql="SELECT DISTINCT ";
                $cadena_sql.="HOR_DIA_NRO, ";
                $cadena_sql.="HOR_HORA, ";
                $cadena_sql.="HOR_NRO ";
                $cadena_sql.="FROM ";
                $cadena_sql.="ACCURSO ";
                $cadena_sql.="INNER JOIN ";
                $cadena_sql.="ACHORARIO ";
                $cadena_sql.="ON (";
                $cadena_sql.="ACCURSO.CUR_ASI_COD = ";
                $cadena_sql.="ACHORARIO.HOR_ASI_COD) ";
                $cadena_sql.="AND ";
                $cadena_sql.="(ACCURSO.CUR_NRO = ";
                $cadena_sql.="ACHORARIO.HOR_NRO) ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="ACCURSO.CUR_ASI_COD = ";
                $cadena_sql.="'".$variable[5]."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="ACCURSO.CUR_APE_ANO = ";
                $cadena_sql.="'".$variable[2]."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="ACCURSO.CUR_APE_PER = ";
                $cadena_sql.="'".$variable[3]."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="ACCURSO.CUR_CRA_COD = ";
                $cadena_sql.="'".$variable[0]."' ";
                $cadena_sql.="ORDER BY 3, 1, 2";
                break;


            case "rescatarGrupoEstudiante":
                $cadena_sql="SELECT ";
                $cadena_sql.="horario_idEspacio, ";
                $cadena_sql.="horario_idProyectoCurricular, ";
                $cadena_sql.="horario_grupo ";
                $cadena_sql.="FROM ";
                $cadena_sql.=$configuracion["prefijo"]."horario_estudiante_provisional ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="horario_codEstudiante=".$variable." ";//cod est
                break;



            case "insertarGrupoEA":
                $cadena_sql="INSERT INTO ";
                $cadena_sql.=$configuracion["prefijo"]."horario_estudiante_provisional ";
                $cadena_sql.="(";
                $cadena_sql.="horario_codEstudiante, ";
                $cadena_sql.="horario_idProyectoCurricular, ";
                $cadena_sql.="horario_idPlanEstudio, ";
                $cadena_sql.="horario_ano, ";
                $cadena_sql.="horario_periodo, ";
                $cadena_sql.="horario_idEspacio, ";
                $cadena_sql.="horario_grupo, ";
                $cadena_sql.="horario_estado";
                $cadena_sql.=") ";
                $cadena_sql.="VALUES ";
                $cadena_sql.="(";
                $cadena_sql.="'".$variable[0]."', ";//cod est
                $cadena_sql.="'".$variable[3]."', ";//proyecto
                $cadena_sql.="'".$variable[4]."', ";//planest
                $cadena_sql.="'".$variable[5]."', ";//ano
                $cadena_sql.="'".$variable[6]."', ";//periodo
                $cadena_sql.="'".$variable[1]."', ";//cod espacio
                $cadena_sql.="'".$variable[2]."', ";//grupo
                $cadena_sql.="'".$variable[7]."' ";//estado
                $cadena_sql.="'')";
                //$cadena_sql.="";
                break;

            case "insertarRegistroError":

                $cadena_sql="INSERT INTO ";
                $cadena_sql.=$configuracion["prefijo"]."errores_preinscripcion ";
                $cadena_sql.="(";
                $cadena_sql.="errores_idProyectoCurricular, ";
                $cadena_sql.="errores_idPlanEstudio, ";
                $cadena_sql.="errores_ano, ";
                $cadena_sql.="errores_periodo, ";
                $cadena_sql.="errores_codEstudiante, ";
                $cadena_sql.="errores_idEspacio, ";
                $cadena_sql.="errores_grupo, ";
                $cadena_sql.="errores_observaciones";
                $cadena_sql.=") ";
                $cadena_sql.="VALUES ";
                $cadena_sql.="( ";
                $cadena_sql.="'".$variable["proyectoCurricular"]."', ";//cra
                $cadena_sql.="'".$variable["planEstudios"]."', ";//planest
                $cadena_sql.="'".$variable["anno"]."', ";//ano
                $cadena_sql.="'".$variable["periodo"]."', ";//periodo
                $cadena_sql.="'".$variable["codigoEstudiante"]."', ";//Cod estud
                $cadena_sql.="'".$variable["espacioAcademico"]."', ";//cod EA
                $cadena_sql.="'".$variable["grupo"]."', ";//grupo sem ant
                $cadena_sql.="'".$variable["observaciones"]."'";//observaciones
                $cadena_sql.=")";
                //                                echo $cadena_sql;
                //                                exit;
                //$cadena_sql.="";
                break;

            case "buscarNombreEA":
            //ORACLE
                $cadena_sql="SELECT ";
                $cadena_sql.="ASI_NOMBRE ";
                $cadena_sql.="FROM ";
                $cadena_sql.="ACASI ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="ASI_COD = ";
                $cadena_sql.="'".$variable."'";//observaciones
                //$cadena_sql.=" ";
                break;

            case "gruposCuposEA":
                $cadena_sql="SELECT DISTINCT ";
                //$cadena_sql.="horario_cod_estudiante, ";
                $cadena_sql.="horario_idEspacio ";
                //$cadena_sql.="horario_cod_grupo ";
                $cadena_sql.="FROM ";
                $cadena_sql.=$configuracion["prefijo"]."horario_estudiante_provisional ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="horario_idProyectoCurricular = ";
                $cadena_sql.="'".$variable[0]."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="horario_idPlanEstudio = ";
                $cadena_sql.="'".$variable[1]."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="horario_ano = ";
                $cadena_sql.="'".$variable[2]."' ";
                $cadena_sql.="AND ";
                $cadena_sql.="horario_periodo = ";
                $cadena_sql.="'".$variable[3]."' ";
                $cadena_sql.="ORDER BY 1";

                //$cadena_sql.=" ";
                break;


            case "buscarCuposEA":
            //ORACLE
                $cadena_sql="SELECT ";
                $cadena_sql.="CUR_NRO_CUPO, ";
                $cadena_sql.="CUR_NRO_INS, ";
                $cadena_sql.="CUR_SEMESTRE, ";
                $cadena_sql.="CUR_NRO ";
                $cadena_sql.="FROM ";
                $cadena_sql.="ACCURSO ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="CUR_APE_ANO = ";
                $cadena_sql.="'".$variable[2]."' ";//Año
                $cadena_sql.="AND ";
                $cadena_sql.="CUR_APE_PER = ";
                $cadena_sql.="'".$variable[3]."' ";//Período
                $cadena_sql.="AND ";
                $cadena_sql.="CUR_ASI_COD = ";
                $cadena_sql.="'".$variable[4]."' ";//Cod EA
                $cadena_sql.="AND ";
                $cadena_sql.="CUR_NRO = ";
                $cadena_sql.="'".$variable[5]."' ";//Grupo
                $cadena_sql.="AND ";
                $cadena_sql.="CUR_CRA_COD = ";
                $cadena_sql.="'".$variable[0]."'";//Cra
                //$cadena_sql.=" ";
                break;

            case "buscarNoRegistrados":

                $cadena_sql="SELECT ";
                $cadena_sql.="horario_codEstudiante, ";
                $cadena_sql.="horario_idEspacio, ";
                $cadena_sql.="horario_grupo ";
                $cadena_sql.="FROM ";
                $cadena_sql.=$configuracion["prefijo"]."horario_estudiante_provisional ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="horario_idProyectoCurricular = ";
                $cadena_sql.="'".$variable[0]."' ";
                $cadena_sql.="and ";
                $cadena_sql.="horario_idPlanEstudio = ";
                $cadena_sql.="'".$variable[1]."' ";
                $cadena_sql.="and ";
                $cadena_sql.="horario_ano = ";
                $cadena_sql.="'".$variable[2]."' ";
                $cadena_sql.="and ";
                $cadena_sql.="horario_periodo = ";
                $cadena_sql.="'".$variable[3]."' ";
                $cadena_sql.="and ";
                $cadena_sql.="horario_estado != '2'";
                //$cadena_sql.=$variable[5];//no asignados

                //$cadena_sql.=" ";
                break;

            case "insertarCupos":

                $cadena_sql="INSERT INTO ";
                $cadena_sql.=$configuracion["prefijo"]."cupos_preinscripcion ";
                $cadena_sql.="VALUES ";
                $cadena_sql.="(";
                $cadena_sql.="'".$variable[1]."', ";//cod EA
                $cadena_sql.="'".$variable[2]."', ";//nivel
                $cadena_sql.="'".$variable[3]."', ";//grupo
                $cadena_sql.="'".$variable[0]."', ";//cupo
                $cadena_sql.="'".$variable[9]."', ";//cupos antes
                $cadena_sql.="'".$variable[9]."', ";//cupos despues
                $cadena_sql.="'".$variable[5]."', ";//proyecto
                $cadena_sql.="'".$variable[6]."', ";//planest
                $cadena_sql.="'".$variable[7]."', ";//ano
                $cadena_sql.="'".$variable[8]."', ";//periodo
                $cadena_sql.="''";//semestre
                $cadena_sql.=")";
                $cadena_sql.=" ";
                break;



            case "actualizarCupoEA":
                $cadena_sql="UPDATE ";
                $cadena_sql.=$configuracion["prefijo"]."cupos_preinscripcion ";
                $cadena_sql.="SET ";
                $cadena_sql.="cupos_despues = '".($variable["cupo"]-1)."' ";
                $cadena_sql.="WHERE ";
                $cadena_sql.="cupos_idEspacio=".$variable["espacio"]." ";//EA
                $cadena_sql.="AND ";
                $cadena_sql.="cupos_idProyectoCurricular=".$variable["proyecto"]." ";//CRA
                $cadena_sql.="AND ";
                $cadena_sql.="cupos_grupo=".$variable["grupo"]." ";//CRA
                $cadena_sql.="AND ";
                $cadena_sql.="cupos_ano=".$variable["anno"]." ";//Anno
                $cadena_sql.="AND ";
                $cadena_sql.="cupos_periodo=".$variable["periodo"]." ";//Periodo
                $cadena_sql.="AND ";
                $cadena_sql.="cupos_idPlanestudio=".$variable["planEstudio"]." ";//Periodo
                break;

            default:
                break;
        }
        //echo $cadena_sql."<hr>";
        return $cadena_sql;
    }
}

?>
