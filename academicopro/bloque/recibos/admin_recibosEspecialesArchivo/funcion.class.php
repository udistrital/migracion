<?php
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/alerta.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sesion.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/log.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/reglasConsejerias.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion/validaciones.class.php");
//include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");

//include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/encriptar.class.php");
//include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/phpmailer.class.php");
//@ Clase que contiene los métodos que ejecutan tareas y crean los formularios de la pagina del bloque.
class funcion_adminRecibosEspecialesArchivo extends funcionGeneral {

  public $configuracion;
  public $numero = 0; //cuenta las filas de la tabla de estudiantes asociados
  public $usuario;
  public $nivel;
  public $datosEstudiante;
  public $periodo;

  //@ Método costructor que crea el objeto sql de la clase sql_noticia
    function __construct($configuracion) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($this->configuracion["raiz_documento"].$this->configuracion["estilo"]."/".$this->estilo."/tema.php");

    $this->configuracion = $configuracion;
    $this->cripto = new encriptar();
    $this->sql = new sql_adminRecibosEspecialesArchivo($configuracion);
    $this->log_us = new log();
    $this->reglasConsejerias = new reglasConsejerias();
    $this->validacion=new validarInscripcion();
    $this->formulario="registro_cargarArchivoRecibosEspeciales";//nombre del bloque que procesa el formulario
    $this->bloque="recibos/registro_cargarArchivoRecibosEspeciales";//nombre del bloque que procesa el formulario

    //Conexion General
    $this->acceso_db = $this->conectarDB($this->configuracion, "");
    $this->accesoGestion = $this->conectarDB($this->configuracion, "mysqlsga");

    //Datos de sesion
    $this->usuario = $this->rescatarValorSesion($this->configuracion, $this->acceso_db, "id_usuario");
    $this->identificacion = $this->rescatarValorSesion($this->configuracion, $this->acceso_db, "identificacion");
    $this->nivel = $this->rescatarValorSesion($this->configuracion, $this->acceso_db, "nivelUsuario");
    //Conexion ORACLE
    if($this->nivel==80)
        {
            $this->accesoOracle = $this->conectarDB($this->configuracion, "oraclesga");
        }else
            {
                echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
                EXIT;
            }
        
    if($this->usuario=="")
    {
        echo "¡IMPOSIBLE RESCATAR EL USUARIO, SU SESION HA EXPIRADO, INGRESE NUEVAMENTE!",
        EXIT;
    }

    #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
    $obj_sesion = new sesiones($this->configuracion);
    $this->resultadoSesion = $obj_sesion->rescatar_valor_sesion($this->configuracion, "acceso");
    $this->id_accesoSesion = $this->resultadoSesion[0][0];

    
    $this->verificar="control_vacio(".$this->formulario.",'codigo','')";   
  }
    
  function formCargaArchivoRecibos(){
        $this->formulario="registro_cargarArchivoRecibosEspeciales";
        $this->bloque="recibos/registro_cargarArchivoRecibosEspeciales";
        $verificar="control_vacio(".  $this->formulario.",'archivo')";		

        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
        $cripto=new encriptar();	
        $tab=0;
        ?>
        <form enctype="multipart/form-data" method='POST' action='index.php' name='<? echo $this->formulario ?>'>
                	
	<table class="tablaMarco">
		<tbody>
			<tr>
				<td align="center" valign="middle">
					<table style="width: 100%; text-align: left;" border="0" cellpadding="6" cellspacing="0">
						<tr class="bloquecentralcuerpo">
							<td colspan="2" rowspan="1">
								<span class="encabezado_normal">Recibos especiales - cargar archivo</span>
								<hr class="hr_division">
							</td>		
						</tr>	
						<tr class="bloquecentralcuerpo">
							<td>
								<table style="width: 100%; text-align: left;" border="0" cellpadding="2" cellspacing="1">
									<tr class="bloquecentralcuerpo">
										<td>
											Archivo
										</td>
										<td>
											<input type="file" name="archivo" tabindex="<? echo $tab++ ?>">
										</td>
									</tr>
									<tr align="center">
										<td colspan="2">
											<table width="80%" align="center" border="0">
												<tr>
													<td align="center">
														<input type="hidden" name="opcion" value="cargarArchivo">
                                                                                                                <input type="hidden" name="action" value="<? echo $this->bloque ?>">

														<input type="submit" value="Aceptar" title="Cargar Archivo" />
													</td>
													
												</tr>
											</table>	
										</td>
									</tr>	
                                                                        <tr class="bloquecentralcuerpo">
										<td colspan="2">
											<hr class="hr_division">
										</td>									
									</tr>							
						
								</table>
							</td>	
						</tr>
                                                <tr>
                                                    <td>
                                                        <a href="<?echo $this->configuracion["host"].'/weboffice/documento/plantilla_recibos_especiales.xlsx';?>"><img src="<? echo $this->configuracion["site"].$this->configuracion["grafico"];?>/acroread.png" border="0" width="30" height="30">  <b>PLANTILLA </b>PARA GENERAR RECIBOS ESPECIALES</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Nota: Descargue la plantilla y coloque los datos correspondientes a todas las columnas requeridas, por favor VERIFIQUE que: <br> - Las celdas no contengan funciones, ni formatos especiales. <br>- Las celdas de las fechas deben estar en formato de texto. 
                                                    </td>
                                                </tr>
					</table>
				</td>
			</tr>							
		</tbody>
	</table>
	</form><?

  }
  
    
}
?>
