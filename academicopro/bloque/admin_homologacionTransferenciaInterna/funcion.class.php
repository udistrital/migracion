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
                //var contenidoNombre=trow.getElementsByTagName("td");
                //crea una nueva celda
                var newCell = newRow.insertCell(newRow.cells.length)
                //crea la division
                division= '<div id="div_nombreEstudiante'+numero+'"></div>'
                //se asigna la division a la celda
                newCell.innerHTML = division
                var newCell = newRow.insertCell(newRow.cells.length)
                newID2 = 'codProyectoAnt' + (numero);
                newNombre2 = 'codProyectoAnt[' + (numero)+']';
                txt2 = '<input type="text" id="'+newID2+'" name="'+newNombre2+'" size="11"  onKeyPress="return solo_numero(event)" onBlur="xajax_nombreProyecto(document.getElementById(\''+newID2+'\').value,'+numero+',\'Ant\',\'\')"/>'
                newCell.innerHTML = txt2
                var newCell = newRow.insertCell(newRow.cells.length)
                //crea la division proyecto anterior
                division3= '<div id="div_proyectoAnt'+numero+'"></div>'
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

class funciones_adminHomologacionTransferenciaInterna extends funcionGeneral {
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
        $this->formulario="registro_homologacionTransferenciaInterna";
        $this->formulario2="admin_homologacionTransferenciaInterna";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        $this->pagina="registro_homologacionTransferenciaInterna";
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
function realizarHomologacionTransferenciaInterna($tipo){
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
           $cod_proyecto = $this->proyecto[0][0]; 
           $nom_proyecto = $this->proyecto[0][1]; 
           $indice=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
           $ruta="pagina=admin_homologacionTransferenciaInterna";
           $ruta.="&opcion=realizarHomologacionTransferenciaInterna";
           $ruta.="&cod_proyecto=".$cod_proyecto;
           

    ?>
            <script src="<? echo $this->configuracion["host"].  $this->configuracion["site"].  $this->configuracion["javascript"]  ?>/funciones.js" type="text/javascript" language="javascript"></script>
            <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
                
                <div align="center" ><b><?echo "HOMOLOGACIONES POR TRANSFERENCIA INTERNA - ".$cod_proyecto." ".$nom_proyecto; ?></b></div><hr>
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
                
        <?//opciones para agregar 5,10,15...50 estudiantes?>

                    <thead class='sigma'>
                    <th class='niveles centrar' > Código Actual</th>
                    <th class='niveles centrar' > Estudiante</th>
                    <th class='niveles centrar' > Código Proyecto Curricular Anterior</th>
                    <th class='niveles centrar' > Proyecto Curricular Anterior</th>
                    </thead>
                    <tr >
                    <td width="13%" class='cuadro_plano centrar'>
                        1 <input type="text" id="codEstudiante0" name="codEstudiante[0]" size="11"  onKeyPress="return solo_numero(event)" onBlur="xajax_nombreEstudiante(document.getElementById('codEstudiante0').value,0,<? echo $cod_proyecto;?>)">
                        <input type="hidden" name="opcion" value="registrar">
                        <input type="hidden" name="action" value="<? echo $this->formulario ?>">
                        <input type="hidden" name="tipo_homologacion" value="estudiantes">
                        <input type="hidden" name="cod_proyecto" value="<? echo $cod_proyecto; ?>">
                    </td>
                    <td width="20%" class='cuadro_plano centrar'>
                        <div id="div_nombreEstudiante0" ></div>
                    </td>
                    <td width="17%" class='cuadro_plano centrar'>
                        <input type="text" id="codProyectoAnt0" name="codProyectoAnt[0]" size="11" maxlength='3' onKeyPress="return solo_numero(event)" onBlur="xajax_nombreProyecto(document.getElementById('codProyectoAnt0').value,0)">
                    </td>
                   
                    </td>
                    <td width="30%" class='cuadro_plano centrar'>
                        <div id="div_proyectoAnt0" ></div>
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
        $variable="pagina=admin_homologacionTransferenciaInterna";
        $variable.="&opcion=realizarHomologacionTransferenciaInterna";
        if (count($carreras)>1){        ?>
          <div align="center" ><b><?echo "HOMOLOGACIONES POR TRANSFERENCIA INTERNA "; ?></b></div><hr>
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
      //echo "<br>sql proy curr ".$cadena_sql ;
      return $resultadoProyectos = $this->ejecutarSQL($this->configuracion, $this->accesoOracle, $cadena_sql, "busqueda");
  }


}


?>
