<?php
/* 
 * Funcion que tiene todas metodos para registrar la inscripcion de un espacio academico de Posgrados
 * 
 */

/**
 * Inserta el registro de inscripcion en la base de datos ORACLE y MYSQL
 * Cada funcion recibe unos parametros especificos
 *
 * @author Luis Fernando Torres
 * Fecha 25 de Abril de 2011
 */

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

class registrarInscripcionCI{

  private $configuracion;
  private $usuario;

  function __construct($usuario,$conexion) {

        require_once("clase/config.class.php");
        $esta_configuracion=new config();    
        $configuracion=$esta_configuracion->variable();
        $this->configuracion=$configuracion;
        $this->usuario=$usuario;

        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/funcionGeneral.class.php");
        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/procedimientosCI.class.php");

        $this->funcionGeneral=new funcionGeneral();
        $this->procedimientos=new procedimientosCI();

        //Conexion General
        $this->acceso_db=$this->funcionGeneral->conectarDB($this->configuracion,"");

        //Conexion sga
        $this->accesoGestion=$this->funcionGeneral->conectarDB($this->configuracion,"mysqlsga");

        //Conexion Oracle
        $this->accesoOracle=$conexion;

        //Datos de sesion
        $this->usuario=$this->funcionGeneral->rescatarValorSesion($this->configuracion, $this->acceso_db, "id_usuario");

        $this->identificacion=$this->funcionGeneral->rescatarValorSesion($this->configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->funcionGeneral->rescatarValorSesion($this->configuracion, $this->acceso_db, "nivelUsuario");


    }

    /**
     * Funcion que registra cada estudiante por grupo
     * @param <array> $datosInscripcion
     * @return <bool>
     */
    function inscribirEstudiante($datosInscripcion) {
      $resultado_registroMysql=$this->insertarRegistroMysql($datosInscripcion);
      if($resultado_registroMysql==1){
          $resultado_registroOracle=$this->insertarRegistroOracle($datosInscripcion);
          if($resultado_registroOracle==1){
              
            $this->procedimientos->actualizarCupo($datosInscripcion);
            $resultado_registroEvento=$this->procedimientos->registrarEvento($datosInscripcion);
            if($resultado_registroEvento==true)
              {

              }
            else
              {
              echo 'No se registro el log del evento';
              
              }
            return true;
          }
          else{
              $resultado_borrarRegistroMysql=$this->borrar_registroMysql($datosInscripcion);
              echo 'La base de datos se encuentra ocupada. ¡No se pudo realizar la Inscripci&oacute;n!';
              exit;


            return FALSE;
          }
      }
      else{
        return FALSE;
      }

    }

    /**
     * Funcion que crea el registro de inscripcion en la base de datos mysql
     *
     * @param <array> $datosInscripcion
     */
    function insertarRegistroMysql($datosInscripcion) {

      $cadena_sql=$this->cadena_sql("adicionar_espacio_mysql", $datosInscripcion);
      $resultado_adicionarMysql=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );

      return $registrosAfectados=$this->funcionGeneral->totalAfectados($this->configuracion ,$this->accesoGestion);

    }

    /**
     * Registra inscripcion en Oracle
     * @param <type> $datosInscripcion
     * @return <type> 
     */
    function insertarRegistroOracle($datosInscripcion) {

      $cadena_sql=$this->cadena_sql("adicionar_espacio_oracle", $datosInscripcion);//echo "<br>cadena". $cadena_sql;
      $resultado_adicionarOracle=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql,"" );

      return $registrosAfectados=$this->funcionGeneral->totalAfectados($this->configuracion ,$this->accesoOracle);
      

    }

    /**
     * Registra inscripcion en MySQL
     * @param <type> $datosInscripcion
     * @return <type> 
     */
    function borrar_registroMysql($datosInscripcion) {

      $cadena_sql=$this->cadena_sql("borrar_datos_mysql_no_conexion", $datosInscripcion);
      $resultado_adicionarMysql=$this->funcionGeneral->ejecutarSQL($this->configuracion, $this->accesoGestion, $cadena_sql,"" );

      return $registrosAfectados=$this->funcionGeneral->totalAfectados($this->configuracion ,$this->accesoGestion);

    }

    /**
     * Esta funcion construye las cadenas SQL para ejecutar
     * @param <string> $tipo
     * @param <array> $variable
     */
    function cadena_sql($tipo,$variable){
            switch ($tipo)
                {

                  case 'adicionar_espacio_mysql':

                      $cadena_sql="INSERT INTO ".$this->configuracion['prefijo']."horario_estudiante ";
                      $cadena_sql.="VALUES ('".$variable['codEstudiante']."',";
                      $cadena_sql.="'".$variable['codProyectoEstudiante']."',";
                      $cadena_sql.="'".$variable['planEstudioEstudiante']."',";
                      $cadena_sql.="'".$variable['ano']."',";
                      $cadena_sql.="'".$variable['periodo']."',";
                      $cadena_sql.="'".$variable['codEspacio']."',";
                      $cadena_sql.="'".$variable['idGrupo']."',";
                      $cadena_sql.="'4')";
                      break;

                  case 'adicionar_espacio_oracle':

                      //$cadena_sql="INSERT INTO MNTAC.ACINS ";
                      $cadena_sql="INSERT INTO ACINS ";
                      $cadena_sql.="(INS_CRA_COD, INS_EST_COD, INS_ASI_COD, INS_GR, INS_OBS, INS_ANO, INS_PER, INS_ESTADO, INS_CRED, INS_NRO_HT, INS_NRO_HP, INS_NRO_AUT, INS_CEA_COD, INS_TOT_FALLAS,INS_HOR_ALTERNATIVO) ";
                      $cadena_sql.="VALUES ('".$variable['codProyectoEstudiante']."',";
                      $cadena_sql.="'".$variable['codEstudiante']."',";
                      $cadena_sql.="'".$variable['codEspacio']."',";
                      $cadena_sql.="'".$variable['idGrupo']."',";
                      $cadena_sql.="'0',";
                      $cadena_sql.="'".$variable['ano']."',";
                      $cadena_sql.="'".$variable['periodo']."',";
                      $cadena_sql.="'A',";
                      $cadena_sql.="'".$variable['creditos']."',";
                      $cadena_sql.="'".$variable['htd']."',";
                      $cadena_sql.="'".$variable['htc']."',";
                      $cadena_sql.="'".$variable['hta']."',";
                      $cadena_sql.="'".$variable['cea']."',";
                      $cadena_sql.="'0',";                      
                      $cadena_sql.="'0')";                      
                      break;

                  case 'borrar_datos_mysql_no_conexion':

                      $cadena_sql="DELETE FROM ".$this->configuracion['prefijo']."horario_estudiante";
                      $cadena_sql.=" WHERE horario_codEstudiante = ".$variable['codEstudiante'];
                      $cadena_sql.=" AND horario_estado = 4";
                      $cadena_sql.=" AND horario_idProyectoCurricular = ".$variable['codProyectoEstudiante'];
                      $cadena_sql.=" AND horario_ano = ".$variable['ano'];
                      $cadena_sql.=" AND horario_periodo = ".$variable['periodo'];
                      $cadena_sql.=" AND horario_idEspacio = ".$variable['codEspacio'];
                      break;


                }
                return $cadena_sql;
        }
}
?>
