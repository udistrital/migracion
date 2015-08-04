<?
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

include_once ($configuracion ["raiz_documento"] . $configuracion ["clases"] . "/bloque.class.php");
include_once ("funcion.class.php");
include_once ("sql.class.php");

// Clase

if (! class_exists ( 'bloque_adminEspaciosHorarios' )) {
	class bloque_adminEspaciosHorarios extends bloque {
		public function __construct($configuracion) {
			$this->sql = new sql_adminEspaciosHorarios ();
			$this->funcion = new funciones_adminEspaciosHorarios ( $configuracion, $this->sql );
		}
		
		function html($configuracion) {
			if (isset ( $_REQUEST ['opcion'] )) {
				$accion = $_REQUEST ['opcion'];
				
				switch ($accion) {
					case "verProyectos" :
						$this->funcion->verProyectos ( $configuracion );
						break;
					
					case "horario" :
						$this->funcion->verHorarios ( $configuracion );
						break;
					
					case "verEstudiantes" :
						$this->funcion->verEstudiantes ( $configuracion );
						break;
					case "eliminarRegistro" :
						$this->funcion->eliminarEstudiante ( $configuracion );
						break;
				}
			} else {
				$accion = "nuevo";
				$this->funcion->nuevoRegistro ( $configuracion );
			}
		}
		
		function action($configuracion) {
			switch ($_REQUEST ['opcion']) {
				
				case "eliminarRegistro" :
					$pagina = $configuracion ["host"] . $configuracion ["site"] . "/index.php?";
					$variable = "pagina=adminEspaciosHorarios";
					$variable .= "&opcion=eliminarRegistro";
					$variable .= "&codEstudiante=" . $_REQUEST ["codEstudiante"];
					$variable .= "&codProyecto=" . $_REQUEST ["codProyecto"];
					$variable .= "&idEspacio=" . $_REQUEST ["idEspacio"];
					$variable .= "&grupo=" . $_REQUEST ["grupo"];
					
					include_once ($configuracion ["raiz_documento"] . $configuracion ["clases"] . "/encriptar.class.php");
					$this->cripto = new encriptar ();
					$variable = $this->cripto->codificar_url ( $variable, $configuracion );
					
					echo "<script>location.replace('" . $pagina . $variable . "')</script>";
					break;
				
				case "horario" :
					
					$pagina = $configuracion ["host"] . $configuracion ["site"] . "/index.php?";
					$variable = "pagina=adminEspaciosHorarios";
					$variable .= "&opcion=horario";
					$variable .= "&proyecto=" . $_REQUEST ["proyecto"];
					
					// var_dump($_REQUEST);exit;
					include_once ($configuracion ["raiz_documento"] . $configuracion ["clases"] . "/encriptar.class.php");
					$this->cripto = new encriptar ();
					$variable = $this->cripto->codificar_url ( $variable, $configuracion );
					
					echo "<script>location.replace('" . $pagina . $variable . "')</script>";
					break;
			}
		}
	}
}
// @ Crear un objeto bloque especifico

$esteBloque = new bloque_adminEspaciosHorarios ( $configuracion );

if (! isset ( $_REQUEST ['action'] )) {
	$esteBloque->html ( $configuracion );
} else {
	if (! isset ( $_REQUEST ['confirmar'] )) {
		$esteBloque->action ( $configuracion );
	}
}
exit;

?>