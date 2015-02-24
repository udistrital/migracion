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

class funciones_adminHomologacionesPendientes extends funcionGeneral {
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
        $this->accesoOracle=$this->conectarDB($configuracion,"coordinador");

        //Datos de sesion
        $this->formulario="registro_homologacionPendientes";
        $this->formulario2="admin_homologacionesPendientes";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        $this->pagina="registro_homologacionPendientes";
        $this->opcion="registrar";
        //Conexion sga
        $this->configuracion = $configuracion;
        
        if (isset($_REQUEST['cod_proyecto'])?$_REQUEST['cod_proyecto']:'')
            $this->proyecto = $this->consultarProyectosCoordinador($_REQUEST['cod_proyecto']);
   
    }

     /**
     * Funcion que valida si existe un proyecto curricular seleccionado para mostrar el formulario de registro
     * @param <array> $_REQUEST (pagina,opcion,codProyecto)
     */
function realizarHomologacionPendientes($tipo){  
       if (isset($_REQUEST['cod_proyecto'])?$_REQUEST['cod_proyecto']:''){ 
           $this->mostrarFormulario($tipo);
         }else{
            $this->formSeleccionarProyecto($tipo);
        }
    }
    
     /**
     * Funcion que muestra en formulario general para el registro de homologaciones
     * @param <array> $this->verificar
     * @param <array> $this->formulario
     * @param <array> $_REQUEST (pagina,opcion,cod_proyecto)
      * Utiliza los metodos camposBusquedaEspaciosPadre, camposBusquedaEspaciosHijo
     */
    function mostrarFormulario($tipo){
        if($tipo=='cohorte'){
            $this->mostrarFormularioCohorte();
        }else{
            $this->mostrarFormularioEstudiantes();
        }
    
    }
    
     /**
     * Funcion que muestra el formulario para ingresar los codigos de los estudiantes para el registro de homologaciones
     * Utiliza los metodos enlaceEjecutarHomologacionesPendientesCohorte
     */
    
 function mostrarFormularioEstudiantes(){
           $cod_proyecto = $this->proyecto[0][0]; 
           $nom_proyecto = $this->proyecto[0][1];     
           $this->enlaceEjecutarHomologacionesPendientesCohorte();
    ?>
            <script src="<? echo $this->configuracion["host"].  $this->configuracion["site"].  $this->configuracion["javascript"]  ?>/funciones.js" type="text/javascript" language="javascript"></script>
            <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
                <table>
                    <tr id="fila" >
                    <td class="sigma centrar" colspan="6" width="100%">Seleccione el n&uacute;mero de estudiantes:
                        <select id="filas" name="filas" onchange="removeLastRow(),nuevaFila(document.getElementById('filas').value-1,<?echo $_REQUEST['cod_proyecto']?>)">
                        <?$opciones=1?>
                        <option selected class="boton" value="1" onClick="removeLastRow(),nuevaFila(0,<?echo $_REQUEST['cod_proyecto']?>)">1</option>
                        <?for($opciones=5;$opciones<=50;$opciones+=5){?>
                        <option id="<?echo $opciones-1?>" class="boton" value="<?echo $opciones?>"><?echo $opciones?></option>
                        <?}?>
                        </select>&nbsp;&nbsp;
                        <?//opciones para agregar 1 estudiante y opcion para borrar todas las filas?>
                        <input type="button" value="Adicionar filas" onClick="nuevaFila(1,<?echo $_REQUEST['cod_proyecto']?>)" alt="Adicionar">
                        <input type="button" value="Reiniciar filas" onClick="removeLastRow()" alt="Remover">
                        <input type="button" value="Borrar fila" onClick="removeLastestRow()" alt="Remover">
                    </td>
                    </tr></table>
                <table id="tabla"  class="contenidotabla" width="100%">
                <div align="center" ><b><?echo "HOMOLOGACIONES PENDIENTES - ".$cod_proyecto." ".$nom_proyecto; ?></b></div><hr>
        <?//opciones para agregar 5,10,15...50 estudiantes?>

                    <thead class='sigma'>
                    <th class='niveles centrar' > Código Actual</th>
                    <th class='niveles centrar' > Estudiante</th>
                    <th class='niveles centrar' > Proyecto Curricular</th>
                    </thead>
                    <tr >
                    <td width="13%" class='cuadro_plano centrar'>
                        1 <input type="text" id="codEstudiante0" name="codEstudiante[0]" size="11"  onKeyPress="return solo_numero(event)" onBlur="xajax_nombreEstudiante(document.getElementById('codEstudiante0').value,0,'',<? echo $cod_proyecto;?>)">
                        <input type="hidden" name="opcion" value="registrar">
                        <input type="hidden" name="action" value="<? echo $this->formulario ?>">
                        <input type="hidden" name="tipo_homologacion" value="estudiantes">
                        <input type="hidden" name="cod_proyecto" value="<? echo $cod_proyecto; ?>">
                    </td>
                    <td width="20%" class='cuadro_plano centrar'>
                        <div id="div_nombreEstudiante0" ></div>
                    </td>
                    <td width="30%" class='cuadro_plano centrar'>
                        <div id="div_proyecto0" ></div>
                    </td>

                    </tr>
                </table>
                <table width="100%">
                <tr>
                    <td align="center">
                    <input type="button" value="Registrar" onclick="if(verificarFormulario(<?echo $this->formulario?>)){document.forms['<? echo $this->formulario?>'].submit()}else{false}">
                    </td>
                </tr>
                </table>
            </form>
<?

    }
    
    
    /**
     * Funcion que muestra el formulario con el listado de proyectos curriculares asociados, para que se seleccione un espacio
     * @param <int> $this->identificacion
     * @param <int> $this->configuracion
     * @param <int> $this->crypto
     * Utiliza el metodo consultarProyectosCoordinador
     */

    function formSeleccionarProyecto($tipo) { 
        $carreras = $this->consultarProyectosCoordinador();
        $indiceAcademico=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=admin_homologacionesPendientes";
        $variable.="&opcion=realizarHomologacionPendientes";
        $titulo= "POR ESTUDIANTE";
        
        if (count($carreras)>1){        ?>
          <div align="center" ><b><?echo "HOMOLOGACIONES PENDIENTES ".$titulo; ?></b></div><hr>
          <p>Seleccione un proyecto curricular:&nbsp;</p>
            <div align="center">
                <table border="0" width="530" cellpadding="0">
                <tr>
                <table border="0" cellpadding="0" cellspacing="0" width="530">
                    
        <?                    
            $i=0;
            
            while(isset($carreras[$i][0]))
            {
                $variable_enlace=$variable."&cod_proyecto=".$carreras[$i][0];
                $variable_enlace=$this->cripto->codificar_url($variable_enlace,$this->configuracion);
                $enlaceHomologaciones=$indiceAcademico.$variable_enlace;
                echo'<tr><td width="100%"><a href="'.$enlaceHomologaciones.'">'.$carreras[$i][0].' - '.$carreras[$i][1].'</a></td></tr>';
                $i++;
            }
        }elseif(isset($carreras[0][0])){ 
            $variable_enlace=$variable."&cod_proyecto=".$carreras[0][0];
            $variable_enlace=$this->cripto->codificar_url($variable_enlace,$this->configuracion);
            $enlaceHomologaciones=$indiceAcademico.$variable_enlace;
            echo "<script>location.replace('".$enlaceHomologaciones."')</script>";

        }else{
            echo "No existen proyectos curriculares asociados.";
        }
           
    }     
    
    /**
     * Funcion que consulta en la base de datos los proyectos curriculares asociados a un coordinador
     * @param <int> $identificacion
     * @param <array> $this->configuracion
     * @param $this->accesoOracle
     * @param  $sql
     * Utiliza el metodo ejecutarSQL
     */
  function consultarProyectosCoordinador($cod_proyecto="") {
      $datos['identificacion']= $this->identificacion;
      $datos['cod_proyecto']=$cod_proyecto;
      $cadena_sql = $this->sql->cadena_sql("consultaProyectosCoordinador",$datos); 
      return $resultadoProyectos = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }

     /**
     * Funcion que muestra el enlace para redireccionar y realizar todas las homologaciones pendientes del proyecto curricular
     */
    function enlaceEjecutarHomologacionesPendientesCohorte() {
        $pagina = $this->configuracion["host"].$this->configuracion["site"]."/index.php?";
        $variable="pagina=admin_homologacionesPendientes";
        $variable.="&opcion=realizarHomologacionCohortePendientes";
        $variable.="&cod_proyecto=".$_REQUEST['cod_proyecto'];
        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
       echo "<br><div align='right' > <a href='".$pagina.$variable."' class='enlaceHomologaciones'>::Ejecutar Homologaciones Pendientes por Cohorte</a></div><br>";
      
}

     /**
     * Funcion que muestra los estudiantes encontrados de la cohorte para realizar todas las homologaciones pendientes del proyecto curricular
     */

    function mostrarCohorte(){

        $cohorte = isset($_REQUEST['cohorte'])?$_REQUEST['cohorte']:0;
                
        if ($cohorte > 0){
            $cod_proyecto = isset($_REQUEST['cod_proyecto'])?$_REQUEST['cod_proyecto']:0;
            //iniciamos las validaciones
            $estudiantes_pendientes = $this->consultarEstudiantesCohorteParaHomologacion($cod_proyecto,$cohorte);
            if(is_array($estudiantes_pendientes) && count($estudiantes_pendientes)>0){
                $this->mostrarEstudiantesCohorte($estudiantes_pendientes,$cohorte);   
                 
            }
            
        }else{
            $mensaje = "Cohorte no valida";
            echo $mensaje;

        }
       
    }

    /**
     * Funcion que consulta los datos de los estudiantes de una carrera, para realizarles el proceso de homologacion
     * @param <int> $cod_proyecto
     */
    function consultarEstudiantesCohorteParaHomologacion($cod_proyecto,$cohorte){
        $datos = array('cod_proyecto'=>$cod_proyecto,
                       'cohorte'=> $cohorte
        );
        $cadena_sql = $this->sql->cadena_sql("consultarEstudiantesCohorte",$datos);
        return $resultadoEspacio = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");

    }
    
    /**
     * Funcion que muestra los estudiantes de una cohorte
     * @param <array> $codEstudiante
     * @param <array> $estudiantes
     */
   
    function mostrarEstudiantesCohorte($estudiantes,$cohorte){
        $cod_proyecto= $_REQUEST['cod_proyecto'];
        ?>
             <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario2 ?>'>
                <table id="tabla"  class="contenidotabla" width="100%">
                <div align="center" ><b><?echo "Estudiantes a Homologar por Pendientes"; ?></b></div><hr>
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
                        <input type="hidden" name="tipo_homologacion" value="cohorte">
                        <input type="hidden" name="cod_proyecto" value="<? echo $cod_proyecto; ?>">
                        <input type="hidden" name="cohorte" value="<? echo $cohorte; ?>">
                        </td>
                </tr>
                </table>
            </form>

        <?
    }
    
    /**
     * Funcion que muestra el formulario para seleccionar una cohorte 
     * Utiliza el metodo cuadro_lista
     */
    function mostrarFormularioCohorte(){
        include_once($this->configuracion["raiz_documento"] . $this->configuracion["clases"] . "/html.class.php");
           $html = new html();
           $this->verificar = "seleccion_valida(".$this->formulario2.",'cohorte')";
                
           $cod_proyecto = $this->proyecto[0][0]; 
           $nom_proyecto = $this->proyecto[0][1];
           $tmp_cohortes = $this->consultarCohortes($cod_proyecto);
           for($i=0;$i<count($tmp_cohortes);$i++) {
                $cohortes[$i][0]=$tmp_cohortes[$i]['COHORTE'];
                $cohortes[$i][1]=$tmp_cohortes[$i]['COHORTE'];
           }
           $_REQUEST['cohorte']=isset($_REQUEST['cohorte'])?$_REQUEST['cohorte']:'';
           
    ?>
            <script src="<? echo $this->configuracion["host"].  $this->configuracion["site"].  $this->configuracion["javascript"]  ?>/funciones.js" type="text/javascript" language="javascript"></script>
            <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario2 ?>'>
                <table id="tabla"  class="contenidotabla" width="100%">
                <div align="center" ><b><?echo "HOMOLOGACIONES PENDIENTES POR COHORTE - ".$cod_proyecto." ".$nom_proyecto; ?></b></div><hr>
        <?//opciones para agregar 5,10,15...50 estudiantes?>

                    <thead class='sigma'>
                    <th class='niveles centrar' > Cohorte</th>
                    </thead>
                    <tr >
                    <td width="20%" class='cuadro_plano centrar'>
                        <?
                            $mi_cuadro = $html->cuadro_lista($cohortes, "cohorte", $this->configuracion,$_REQUEST['cohorte'], 0, FALSE, 1, "",100);
                            echo $mi_cuadro ;
                        ?>
                        <input type="hidden" name="opcion" value="consultarCohorte">
                        <input type="hidden" name="pagina" value="<? echo $this->formulario2 ?>">
                        <input type="hidden" name="tipo_homologacion" value="cohorte">
                        <input type="hidden" name="cod_proyecto" value="<? echo $cod_proyecto; ?>">
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
     * Funcion que consulta las cohortes de un proyecto curricular
     * Utiliza el metodo cuadro_lista
     */
function consultarCohortes($cod_proyecto="") {
      $cadena_sql = $this->sql->cadena_sql("consultaCohortesProyecto",$cod_proyecto);
      return $resultadoProyectos = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }
     
}


?>
