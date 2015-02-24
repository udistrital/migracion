
<?php
/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */

if (!isset($GLOBALS["autorizado"])) {
  include("../index.php");
  exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/alerta.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/navegacion.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/sesion.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/log.class.php");

//@ Esta clase presenta el horario registrado para el estudiante y los enlaces para realizar inscripcion por busqeda
//@ Tambien se puede realizar cambio de grupo y cancelacion si hay permisos para inscripciones

class funcion_adminMenuFlotanteVinculacion extends funcionGeneral {

  private $configuracion;
  private $usuario;
  
  //@ Método costructor que crea el objeto sql de la clase sql_noticia
  function __construct($configuracion) {
    //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
    //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
    //include ($configuracion["raiz_documento"] . $configuracion["estilo"] . "/basico/tema.php");
    $this->configuracion = $configuracion;
    include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
    include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/validar_fechas.class.php");
    include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/encriptar.class.php");
    $this->fechas = new validar_fechas();
    $this->cripto = new encriptar();
    //$this->tema = $tema;
    $this->sql = new sql_adminMenuFlotanteVinculacion();
    $this->log_us = new log();
    $this->parametrosHoras=array();
    $this->formulario = "admin_adminMenuFlotanteVinculacion";
    $this->cripto = new encriptar();
    


    //Conexion General
    $this->acceso_db = $this->conectarDB($configuracion, "");

    //Conexion sga
    //$this->accesoGestion = $this->conectarDB($configuracion, "mysqlsga");

    //Conexion Oracle
    $this->accesoOracle = $this->conectarDB($configuracion, "docente");
    
     #Define un objeto de clase sesion para rescatar la sesion actual y realizar las validaciones correspondientes de los links
    $obj_sesion = new sesiones($configuracion);
    $this->resultadoSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "acceso");
    $this->id_accesoSesion = $this->resultadoSesion[0][0];

    $this->usuarioSesion = $obj_sesion->rescatar_valor_sesion($configuracion, "id_usuario");
    $this->usuario = $this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
  }

  /**
   * Funcion que genera variables y presenta el menu flotante.
   * Utiliza los metodos codificar_url y generarMenuFlotante
   */
  function mostrarMenuFlotante() {
	
        $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
	$ruta="pagina=adminDocumentosVinculacion";
	$ruta.="&opcion=pagos";
	$ruta.="&usuario=".$this->usuario;
        $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
        $enlace[]=array('ruta'=>$indice.$ruta, 'nombre'=>'Desprendibles de pago');
        
        $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
	$ruta="pagina=adminDocumentosVinculacion";
	$ruta.="&opcion=historial";
	$ruta.="&usuario=".$this->usuario;
        $ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
        $enlace[]=array('ruta'=>$indice.$ruta, 'nombre'=>'Vinculaciones');
	
	$ruta="pagina=adminDocumentosVinculacion";
	$ruta.="&opcion=actos";
        $ruta.="&usuario=".$this->usuario;
	$ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
        $enlace[]=array('ruta'=>$indice.$ruta, 'nombre'=>'Actos Administrativos');
        
        $ruta="pagina=adminDocumentosVinculacion";
	$ruta.="&opcion=normatividad";
	$ruta.="&usuario=".$this->usuario;
	$ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
        $enlace[]=array('ruta'=>$indice.$ruta, 'nombre'=>'Norma Institucional');
		
        $ruta="pagina=adminDocumentosVinculacion";
	$ruta.="&opcion=convocatoria";
        $ruta.="&usuario=".$this->usuario;
	$ruta=$this->cripto->codificar_url($ruta,$this->configuracion);
        $enlace[]=array('ruta'=>$indice.$ruta, 'nombre'=>'Convocatorias');

        
	$this->generarMenuFlotante($enlace);

    }

    /**
     * Funcion que arma el menu flotante
     * utiliza el metodo crearEnlace
     * @param type $enlace
     */
    function generarMenuFlotante($enlace) {
      ?>
        <header>
            <div class="top">
                <table class="sigma_borde"  width="100%">
                    <tr>
                        <td class="cuadro_plano ">
                            <div id="navbar">
                                <span class="inbar">
                                    <ul>
                                    <?
                                    foreach ($enlace as $celda)
                                    {
                                        $this->crearEnlace($celda);
                                    }
                                    ?>
                                    </ul>
                                </span>
                            </div>
                        </td>
                    </tr>
              </table>
            </div>
        </header>
        <br>
    <?
        
    }
    /**
     * Funcion que cra un enlace a un módulo de homologaciones
     * @param type $datosEnlace
     */
    function crearEnlace($datosEnlace) {
        ?>
        <li><a href="<?echo $datosEnlace['ruta'];?>"><span>:: <?echo $datosEnlace['nombre'];?> </span></a></li>
        <?
        
    }
    

}
?>
