<?php
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

include_once ("core/manager/Configurador.class.php");
include_once ("core/connection/Sql.class.php");

// Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
// en camel case precedida por la palabra sql
class SqldireccionTrabajos extends sql {
	var $miConfigurador;
	function __construct() {
		$this->miConfigurador = Configurador::singleton ();
	}
	function cadena_sql($tipo, $variable = "", $variable1 = "", $variable2 = "") {
		
		/**
		 * 1.
		 * Revisar las variables para evitar SQL Injection
		 */
		$prefijo = $this->miConfigurador->getVariableConfiguracion ( "prefijo" );
		$idSesion = $this->miConfigurador->getVariableConfiguracion ( "id_sesion" );
		
		switch ($tipo) {
			
			case "tipo_titulo" :
				
				$cadena_sql = "SELECT id_nivel, descripcion_nivel ";
				$cadena_sql .= "FROM docencia.nivel_formacion ";
				$cadena_sql .= "ORDER BY id_nivel";
				break;
                            
                        case "buscarProyectos" :

                            $cadena_sql = "SELECT codigo_proyecto, nombre_proyecto ";
                            $cadena_sql .= "FROM docencia.proyectocurricular ";
                            $cadena_sql .= " WHERE id_facultad = '".$variable."' ";
                            $cadena_sql .= "ORDER BY nombre_proyecto";
                            break;    
			
			case "tipo" :
				
				$cadena_sql = "SELECT id_tipodireccion, nombre_tipodireccion ";
				$cadena_sql .= "FROM docencia.direccion_tipo ";
				$cadena_sql .= "ORDER BY id_tipodireccion";
				break;
			
			case "categoria" :
				
				$cadena_sql = "SELECT id_categoriadireccion, nombre_categoriadireccion ";
				$cadena_sql .= "FROM docencia.direccion_categoria ";
				$cadena_sql .= "ORDER BY id_categoriadireccion";
				break;
			
			case "numDireccion" :
				
				$cadena_sql = "	SELECT count(*) ";
				$cadena_sql .= "FROM docencia.direccion_trabajos  ";
				$cadena_sql .= "WHERE docente_direccion = '" . $variable [0] . "' ";
				$cadena_sql .= "AND anio_direccion ='" . $variable [1] . "' ";

				break;
			
			case "buscarNombreDocente" :
				$cadena_sql = "SELECT ";
				$cadena_sql .= "informacion_numeroidentificacion, ";
				$cadena_sql .= "informacion_numeroidentificacion || ' - ' || UPPER(informacion_nombres)|| ' ' ||UPPER(informacion_apellidos) ";
				$cadena_sql .= "FROM ";
				$cadena_sql .= "docencia.docente_informacion ";
				$cadena_sql .= "WHERE ";
				$cadena_sql .= "informacion_estadoregistro = TRUE  ";
                                
                                if($variable != '')
                                    {
                                        if(is_numeric($variable))
                                        {
                                            $cadena_sql .= " AND  informacion_numeroidentificacion like '%".$variable."%'  ";
                                        }else
                                            {
                                                $cadena_sql .= " AND  ((UPPER(informacion_nombres) like '%".strtoupper($variable)."%') OR (UPPER(informacion_apellidos) like '%".strtoupper($variable)."%'))  ";
                                            }
                                    }
                                
				
				break;
			
			case "consultarDocente" :
				
				$cadena_sql = "SELECT informacion_numeroidentificacion, ";
				$cadena_sql .= "(informacion_nombres || ' ' || informacion_apellidos) AS Nombres ";
				$cadena_sql .= "FROM docencia.docente_informacion ";
				$cadena_sql .= "WHERE informacion_numeroidentificacion = '" . $variable . "'";
				break;
			
			case "insertarautor" :
				
				$cadena_sql = "INSERT INTO docencia.autors_direccion( ";
				$cadena_sql .= "id_direccion, nom_autor) ";
				$cadena_sql .= " VALUES ('" . $variable1 . "',";
				$cadena_sql .= " '" . $variable [0] . "')";
				
				break;
			
			case "insertarDireccion" :
				
				$cadena_sql = "INSERT INTO docencia.direccion_trabajos( ";
				$cadena_sql .= "docente_direccion, titulo_direccion, num_autores, ";
				$cadena_sql .= "tipo_direccion, categoria_direccion, ";
				$cadena_sql .= "anio_direccion, numacta_direccion, ";
				$cadena_sql .= "fechacta_direccion, numcaso_direccion, puntaje_direccion, detalledocencia ) ";
				$cadena_sql .= " VALUES (" . $variable [0] . ",";
				$cadena_sql .= " '" . $variable [1] . "',";
				$cadena_sql .= " '" . $variable [2] . "',";
				$cadena_sql .= " '" . $variable [3] . "',";
				$cadena_sql .= " '" . $variable [4] . "',";
				$cadena_sql .= " '" . $variable [5] . "',";
				$cadena_sql .= " '" . $variable [6] . "',";
				$cadena_sql .= " '" . $variable [7] . "',";
				$cadena_sql .= " '" . $variable [8] . "',";
				$cadena_sql .= " '" . $variable [9] . "',";
				$cadena_sql .= " '" . $variable [10] . "')";
				$cadena_sql .= " RETURNING id_direccion ";
				
				break;
			
			case "facultad" :
				
				$cadena_sql = "SELECT codigo_facultad, nombre_facultad ";
				$cadena_sql .= "FROM docencia.facultades ";
				$cadena_sql .= "ORDER BY nombre_facultad";
				break;
			
			case "proyectos" :
				
				$cadena_sql = "SELECT codigo_proyecto, nombre_proyecto ";
				$cadena_sql .= "FROM docencia.proyectocurricular ";
				$cadena_sql .= "ORDER BY nombre_proyecto";
				break;
			
			// SELECT id_autors, id_direccion, nom_autor
			// FROM docencia.autors_direccion;
			
			case "consultarAutoresDireccion" :
				$cadena_sql = " SELECT id_autors, id_direccion, nom_autor";
				$cadena_sql .= "   FROM docencia.autors_direccion ";
				$cadena_sql .= "WHERE id_direccion='" . $variable . "';";
				
				break;
			
			case "consultarDireccionModificar" :
				$cadena_sql = "SELECT docente_direccion, titulo_direccion, num_autores,";
				$cadena_sql .= "tipo_direccion, categoria_direccion, anio_direccion, numacta_direccion,";
				$cadena_sql .= "fechacta_direccion, numcaso_direccion, puntaje_direccion, detalledocencia";
				$cadena_sql .= "  FROM docencia.direccion_trabajos ";
				$cadena_sql .= "WHERE id_direccion='" . $variable . "';";
				
				break;
			
			case "consultarAutores" :
				$cadena_sql = "SELECT id_autors";
				$cadena_sql .= "  FROM docencia.autors_direccion ";
				$cadena_sql .= "WHERE id_direccion='" . $variable . "';";
				
				break;
                            
			case "consultarAutoresMod" :
				$cadena_sql = "SELECT id_autors";
				$cadena_sql .= "  FROM docencia.autors_direccion ";
				$cadena_sql .= "WHERE id_direccion='" . $variable . "' ";
				
				break;
			
			case "consultarDireccion" :
				
				$cadena_sql = "SELECT DISTINCT docente_direccion, titulo_direccion, num_autores, anio_direccion,nombre_tipodireccion, ";
				$cadena_sql .= "nombre_categoriadireccion, ";
				$cadena_sql .= "numacta_direccion, fechacta_direccion, numcaso_direccion,puntaje_direccion,";
				$cadena_sql .= " informacion_nombres, informacion_apellidos, id_direccion, detalledocencia ";
				$cadena_sql .= "FROM docencia.dependencia_docente ";
				$cadena_sql .= " JOIN docencia.docente_informacion ON informacion_numeroidentificacion = dependencia_iddocente ";
				$cadena_sql .= "JOIN docencia.direccion_trabajos ON docente_direccion = dependencia_iddocente ";
				$cadena_sql .= "LEFT JOIN docencia.direccion_tipo ON id_tipodireccion = tipo_direccion ";
				$cadena_sql .= " LEFT JOIN docencia.direccion_categoria  ON id_categoriadireccion = categoria_direccion ";
				
				$cadena_sql .= "WHERE 1=1";
				if ($variable [0] != '') {
					$cadena_sql .= " AND dependencia_iddocente = '" . $variable [0] . "'";
				}
				if ($variable [1] != '') {
					$cadena_sql .= " AND dependencia_facultad = '" . $variable [1] . "'";
				}
				if ($variable [2] != '') {
					$cadena_sql .= " AND dependencia_proyectocurricular = '" . $variable [2] . "'";
				}
				
				break;
			
			//
			
			// UPDATE docencia.direccion_trabajos
			// SET id_direccion=?, docente_direccion=?, titulo_direccion=?, num_autores=?,
			// tipo_direccion=?, categoria_direccion=?, anio_direccion=?, numacta_direccion=?,
			// fechacta_direccion=?, numcaso_direccion=?, puntaje_direccion=?
			// WHERE <condition>;
			
			case "actualizarDireccion" :
				$cadena_sql = "UPDATE ";
				$cadena_sql .= "docencia.direccion_trabajos ";
				$cadena_sql .= "SET ";
				$cadena_sql .= "titulo_direccion = '" . $variable [1] . "', ";
				$cadena_sql .= "num_autores = '" . $variable [2] . "', ";
				$cadena_sql .= "tipo_direccion = '" . $variable [3] . "', ";
				$cadena_sql .= "categoria_direccion = '" . $variable [4] . "', ";
				$cadena_sql .= "anio_direccion = '" . $variable [5] . "', ";
				$cadena_sql .= "numacta_direccion = '" . $variable [6] . "', ";
				$cadena_sql .= "fechacta_direccion = '" . $variable [7] . "', ";
				$cadena_sql .= "numcaso_direccion = '" . $variable [8] . "', ";
				$cadena_sql .= "puntaje_direccion = '" . $variable [9] . "', ";
				$cadena_sql .= "detalledocencia = '" . $variable [10] . "' ";
				$cadena_sql .= "WHERE ";
				$cadena_sql .= "id_direccion ='" . $variable [0] . "' ";
				break;
			
			case "actualizarautor" :
				$cadena_sql = "UPDATE ";
				$cadena_sql .= "docencia.autors_direccion ";
				$cadena_sql .= "SET ";
				$cadena_sql .= "nom_autor = '" . $variable [0] . "' ";
				$cadena_sql .= "WHERE ";
				$cadena_sql .= "id_direccion ='" . $variable1 . "' ";
				$cadena_sql .= "AND ";
				$cadena_sql .= "id_autors ='" . $variable2 . "' ";
				
				break;
			
			//
			
			// UPDATE docencia.autors_direccion
			// SET id_autors=?, id_direccion=?, nom_autor=?
			// WHERE <condition>;
			
			case "registrarEvento" :
				$cadena_sql = "INSERT INTO ";
				$cadena_sql .= $prefijo . "logger( ";
				$cadena_sql .= "id_usuario, ";
				$cadena_sql .= "evento, ";
				$cadena_sql .= "fecha) ";
				$cadena_sql .= "VALUES( ";
				$cadena_sql .= $variable [0] . ", ";
				$cadena_sql .= "'" . $variable [1] . "', ";
				$cadena_sql .= "'" . time () . "') ";
				break;
			
			/**
			 * Clausulas genéricas.
			 * se espera que estén en todos los formularios
			 * que utilicen esta plantilla
			 */
			
			case "iniciarTransaccion" :
				$cadena_sql = "START TRANSACTION";
				break;
			
			case "finalizarTransaccion" :
				$cadena_sql = "COMMIT";
				break;
			
			case "cancelarTransaccion" :
				$cadena_sql = "ROLLBACK";
				break;
			
			case "eliminarTemp" :
				
				$cadena_sql = "DELETE ";
				$cadena_sql .= "FROM ";
				$cadena_sql .= $prefijo . "tempFormulario ";
				$cadena_sql .= "WHERE ";
				$cadena_sql .= "id_sesion = '" . $variable . "' ";
				break;
			
			case "insertarTemp" :
				$cadena_sql = "INSERT INTO ";
				$cadena_sql .= $prefijo . "tempFormulario ";
				$cadena_sql .= "( ";
				$cadena_sql .= "id_sesion, ";
				$cadena_sql .= "formulario, ";
				$cadena_sql .= "campo, ";
				$cadena_sql .= "valor, ";
				$cadena_sql .= "fecha ";
				$cadena_sql .= ") ";
				$cadena_sql .= "VALUES ";
				
				foreach ( $_REQUEST as $clave => $valor ) {
					$cadena_sql .= "( ";
					$cadena_sql .= "'" . $idSesion . "', ";
					$cadena_sql .= "'" . $variable ['formulario'] . "', ";
					$cadena_sql .= "'" . $clave . "', ";
					$cadena_sql .= "'" . $valor . "', ";
					$cadena_sql .= "'" . $variable ['fecha'] . "' ";
					$cadena_sql .= "),";
				}
				
				$cadena_sql = substr ( $cadena_sql, 0, (strlen ( $cadena_sql ) - 1) );
				break;
			
			case "rescatarTemp" :
				$cadena_sql = "SELECT ";
				$cadena_sql .= "id_sesion, ";
				$cadena_sql .= "formulario, ";
				$cadena_sql .= "campo, ";
				$cadena_sql .= "valor, ";
				$cadena_sql .= "fecha ";
				$cadena_sql .= "FROM ";
				$cadena_sql .= $prefijo . "tempFormulario ";
				$cadena_sql .= "WHERE ";
				$cadena_sql .= "id_sesion='" . $idSesion . "'";
				break;
		}
		return $cadena_sql;
	}
}
?>
