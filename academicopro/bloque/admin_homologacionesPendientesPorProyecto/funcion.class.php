        <script language="javascript">
            var numero = 0;
            var cadenaVerificar;

            function verificarFormulario(formulario)
            {
            if( control_vacio(formulario,'codEstudiante[0]')&&
                verificar_numero(formulario,'codEstudiante[0]'))
              {
                for(b=1;b<numero+1;b++)
                    {
                    if(verificar_numero(formulario,'codEstudiante['+b+']')&&
                      control_vacio(formulario,'codEstudiante['+b+']'))
                      {
                      }
                    else
                      {
                        return false;
                      }
                  }             
              }
              else
              {
                return false;
              }
              return true;
            }
            function nuevaFila(numeroFilas,idProyecto)
            {
                for(a=0;a<numeroFilas;a++){
                // obtenemos acceso a la tabla por su ID
                var table = document.getElementById("tabla");
                // obtenemos acceso a la fila maestra por su ID
                var trow = document.getElementById("fila");
                // tomamos la celda
                var content = trow.getElementsByTagName("td");
                // creamos una nueva fila
                var newRow = table.insertRow(-1);
                newRow.className = 'cuadro_plano centrar';
                // creamos una nueva celda
                var newCell = newRow.insertCell(newRow.cells.length)
                // creamos una nueva ID para el examinador
                newID = 'codEstudiante' + (++numero);
                newNombre = 'codEstudiante[' + (numero)+']';
                txt = table.rows.length-1+' '+'<input type="text" id="'+newID+'" name="'+newNombre+'" size="11"  onKeyPress="return solo_numero(event)" onBlur="xajax_nombreEstudiante(document.getElementById(\''+newID+'\').value,'+numero+',\'\','+idProyecto+')"/>'
                newCell.innerHTML = txt
                //tomar la celda
                var contenidoNombre=trow.getElementsByTagName("td");
                //crea una nueva celda
                var newCell = newRow.insertCell(newRow.cells.length)
                //crea la division
                division= '<div id="div_nombreEstudiante'+numero+'"></div>'
                //se asigna la division a la celda
                newCell.innerHTML = division
                var newCell = newRow.insertCell(newRow.cells.length)
                //crea la division proyecto
                division3= '<div id="div_proyecto'+numero+'"></div>'
                newCell.innerHTML = division3
                }
            }

            function removeLastRow()
            {
                // obtenemos la tabla
                var table = document.getElementById("tabla");

                // si tenemos mas de una fila, borramos
                while(table.rows.length > 2)
                {
                    table.deleteRow(table.rows.length-1);
                    --numero;
                }
            }

            function removeLastestRow()
            {
                // obtenemos la tabla
                var table = document.getElementById("tabla");

                // si tenemos mas de una fila, borramos
                if(table.rows.length > 2)
                {
                    table.deleteRow(table.rows.length-1);
                    --numero;
                }
            }


        </script>
            
<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_adminHomologacionesPendientesPorProyecto extends funcionGeneral {
    //Crea un objeto tema y un objeto SQL.
    private $pagina;
    private $opcion;
    private $configuracion;
    function __construct($configuracion, $sql) {
        //[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

        $this->cripto=new encriptar();
        //$this->tema=$tema;
        $this->sql=$sql;

        //Conexion General
        $this->acceso_db=$this->conectarDB($configuracion,"");

        //Conexion sga
        $this->accesoGestion=$this->conectarDB($configuracion,"mysqlsga");

        //Conexion Oracle
        $this->accesoOracle=$this->conectarDB($configuracion,"administrador");

        //Datos de sesion
        $this->formulario="registro_homologacionPendientesPorProyecto";
        $this->formulario2="admin_homologacionesPendientesPorProyecto";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        $this->pagina="registro_homologacionPendientes";
        $this->opcion="registrar";
        //Conexion sga
        $this->configuracion = $configuracion;

    }

     /**
     * Funcion que valida si existe un proyecto curricular seleccionado para mostrar el formulario de registro
     * @param <array> $_REQUEST (pagina,opcion,codProyecto)
     */
    function realizarHomologacionPendientes(){  
            $this->mostrarFormularioProyecto();
     }
    
 
     /**
     * Funcion que consulta y muestra los estudiantes encontrados de un proyecto para realizar todas las homologaciones pendientes del proyecto curricular
     */

    function mostrarProyecto(){

        $cod_proyecto = isset($_REQUEST['cod_proyecto'])?$_REQUEST['cod_proyecto']:0;
//                var_dump($_REQUEST);exit;
        if ($cod_proyecto > 0){
            //iniciamos las validaciones
            $estudiantes_pendientes = $this->consultarEstudiantesParaHomologacion($cod_proyecto);
            if(is_array($estudiantes_pendientes) && count($estudiantes_pendientes)>0){
                for($i=0;$i<count($estudiantes_pendientes);$i++){
                    $codEstudiante[$i] = $estudiantes_pendientes[$i]['COD_ESTUDIANTE'];
                 }
                 $this->mostrarEstudiantesProyecto($estudiantes_pendientes,$codEstudiante);   
                 
            }
            
        }else{
            $mensaje = "Proyecto Curricular no valido";
            echo $mensaje;

        }
       
    }

    /**
     * Funcion que consulta los datos de los estudiantes de una carrera, para realizarles el proceso de homologacion
     * @param <int> $cod_proyecto
     */
    function consultarEstudiantesParaHomologacion($cod_proyecto){
        $cadena_sql = $this->sql->cadena_sql("consultarEstudiantesProyecto",$cod_proyecto);
        return $resultadoEspacio = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
    }
    
    /**
     * Funcion que muestra los estudiantes de un proyecto
     * @param <array> $codEstudiante
     * @param <array> $estudiantes
     */
   
    function mostrarEstudiantesProyecto($estudiantes,$codEstudiante){
        $cod_proyecto= $_REQUEST['cod_proyecto'];
        
?>
             <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario2 ?>'>
                <table id="tabla"  class="contenidotabla" width="100%">
                <div align="center" ><b><?echo "Estudiantes a Homologar por Pendientes - Cod. Proyecto ".$cod_proyecto; ?></b></div><hr>
        <?//opciones para agregar 5,10,15...50 estudiantes?>

                    <thead class='sigma'>
                    <th class='niveles centrar' > No.</th>
                    <th class='niveles centrar' > Código Actual</th>
                    <th class='niveles centrar' > Nombre</th>
                    </thead>
                    <?
                    for($i=0;$i<count($estudiantes);$i++){
                        $cod_estudiante = isset($estudiantes[$i]['COD_ESTUDIANTE'])?$estudiantes[$i]['COD_ESTUDIANTE']:'';                        
                        $nombre = isset($estudiantes[$i]['NOMBRE'])?$estudiantes[$i]['NOMBRE']:'';                        
                        ?>

                        <tr >
                        <td width="5%" class='cuadro_plano centrar'><? echo $i+1;?></td>
                        <td width="20%" class='cuadro_plano centrar'><? echo $cod_estudiante;?></td>
                        <td width="50%" class='cuadro_plano'><? echo $nombre;?></td>
                        </td>
                        </tr>
                        <?
                    }
                    ?>
                </table>
                <table width="100%">
                <tr>
                    <td align="center">
                        <input type="button" value="Homologar" onclick="document.forms['<? echo $this->formulario2?>'].submit()">         
                        <input type="hidden" name="opcion" value="registrar">
                        <input type="hidden" name="action" value="<? echo $this->formulario ?>">
                        <input type="hidden" name="tipo_homologacion" value="estudiantes">
                        <input type="hidden" name="cod_proyecto" value="<? echo $cod_proyecto; ?>">
                        </td>
                </tr>
                </table>
            </form>

        <?
    }
    
    /**
     * Funcion que muestra el formulario para seleccionar un proyecto
     * Utiliza el metodo cuadro_lista
     */
    function mostrarFormularioProyecto(){
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/html.class.php");
           $html = new html();
           $this->verificar = "seleccion_valida(".$this->formulario2.",'cod_proyecto')";
                
           //$cod_proyecto = $this->proyecto[0][0]; 
           //$nom_proyecto = $this->proyecto[0][1];
           $tmp_proyectos = $this->consultarProyectos();
           for($i=0;$i<count($tmp_proyectos);$i++) {
                $proyectos[$i][0]=$tmp_proyectos[$i]['CRA_COD'];
                $proyectos[$i][1]=$tmp_proyectos[$i]['NOMBRE'];
           }
           $_REQUEST['cod_proyecto']=isset($_REQUEST['cod_proyecto'])?$_REQUEST['cod_proyecto']:'';
           
    ?>
            <script src="<? echo $this->configuracion["host"].  $this->configuracion["site"].  $this->configuracion["javascript"]  ?>/funciones.js" type="text/javascript" language="javascript"></script>
            <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario2 ?>'>
                <table id="tabla"  class="contenidotabla" width="100%">
                <div align="center" ><b><?echo "HOMOLOGACIONES PENDIENTES POR PROYECTO CURRICULAR "; ?></b></div><hr>
        <?//opciones para agregar 5,10,15...50 estudiantes?>

                    <thead class='sigma'>
                    <th class='niveles centrar' > Proyecto Curricular</th>
                    </thead>
                    <tr >
                    <td width="20%" class='cuadro_plano centrar'>
                        <?
                            $mi_cuadro = $html->cuadro_lista($proyectos, "cod_proyecto", $this->configuracion,$_REQUEST['cod_proyecto'], 0, FALSE, 1, "",400);
                            echo $mi_cuadro ;
                        ?>
                        <input type="hidden" name="opcion" value="consultarProyecto">
                        <input type="hidden" name="pagina" value="<? echo $this->formulario2 ?>">
                        
                    </td>
                    
                    </tr>
                </table>
                <table width="100%">
                <tr>
                    <td align="center">
                    <input type="button" value="Homologar" onclick="if(<? echo $this->verificar; ?>){document.forms['<? echo $this->formulario2?>'].submit()}else{false}">                    </td>
                </tr>
                </table>
            </form>
<?

    }
    
      /**
     * Funcion que consulta los proyectos curriculares
     * Utiliza el metodo cuadro_lista
     */
function consultarProyectos() {
      $cadena_sql = $this->sql->cadena_sql("consultaProyectos","");
      return $resultadoProyectos = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }
     
}


?>
