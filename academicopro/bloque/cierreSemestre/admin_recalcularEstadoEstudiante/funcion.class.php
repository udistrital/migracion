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
            function nuevaFila(numeroFilas,nivel,usuario)
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
                txt = table.rows.length-1+' '+'<input type="text" id="'+newID+'" name="'+newNombre+'" size="11" maxlength="11"  onKeyPress="return solo_numero(event)" onBlur="xajax_nombreEstudiante(document.getElementById(\''+newID+'\').value,'+numero+','+nivel+','+usuario+')"/>'
                newCell.innerHTML = txt
                //tomar la celda
                //var contenidoNombre=trow.getElementsByTagName("td");
                //crea una nueva celda
                var newCell = newRow.insertCell(newRow.cells.length)
                //crea la division
                division= '<div id="div_nombreEstudiante'+numero+'"></div>'
                //se asigna la division a la celda
                newCell.innerHTML = division
                //crea una nueva celda
                var newCell = newRow.insertCell(newRow.cells.length)
                //crea la division
                division2= '<div id="div_estadoEstudiante'+numero+'"></div>'
                //se asigna la division a la celda
                newCell.innerHTML = division2
                var newCell = newRow.insertCell(newRow.cells.length)
                //crea la division proyecto anterior
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

class funciones_adminRecalcularEstadoEstudiante extends funcionGeneral {
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

        //Datos de sesion
        $this->formulario="registro_aplicarReglamento";
        $this->bloque="cierreSemestre/registro_aplicarReglamento";
        //$this->formulario2="admin_recalcularEstadoEstudiante";
        $this->usuario=$this->rescatarValorSesion($configuracion, $this->acceso_db, "id_usuario");
        $this->identificacion=$this->rescatarValorSesion($configuracion, $this->acceso_db, "identificacion");
        $this->nivel=$this->rescatarValorSesion($configuracion, $this->acceso_db, "nivelUsuario");
        
        //Conexion ORACLE
        if($this->nivel==4){
            $this->accesoOracle=$this->conectarDB($configuracion,"coordinador");
        }elseif($this->nivel==110){
            $this->accesoOracle=$this->conectarDB($configuracion,"asistente");
        }elseif($this->nivel==114){
            $this->accesoOracle=$this->conectarDB($configuracion,"secretario");
        }else{
            echo "NO TIENE PERMISOS PARA ESTE MODULO";
            exit;
        }
        $this->pagina="registro_aplicarReglamento";
        $this->opcion="recalcularListado";
        //Conexion sga
        $this->configuracion = $configuracion;
        
    }

     /**
     * Funcion que valida si existe un proyecto curricular seleccionado para mostrar el formulario de registro
     * 
     */
    function mostrarFrmRecalcularEstado(){
        $this->mostrarFormularioEstudiantes();
    }
   
            
    /**
     * Función para mostrar el formulario para el ingreso del listado de códigos de los estudiantes a los que se desea recalcular el estado
     */
    function mostrarFormularioEstudiantes(){
           
    ?>
            <script src="<? echo $this->configuracion["host"].  $this->configuracion["site"].  $this->configuracion["javascript"]  ?>/funciones.js" type="text/javascript" language="javascript"></script>
            <form enctype='tipo:multipart/form-data,application/x-www-form-urlencoded,text/plain' method='POST' action='index.php' name='<? echo $this->formulario ?>'>
                <table>
                    <tr id="fila" >
                    <td class="sigma centrar" colspan="6" width="100%">Seleccione el n&uacute;mero de estudiantes:
                        <select id="filas" name="filas" onchange="removeLastRow()">
                        <?$opciones=1?>
                        <option selected class="boton" value="1" onClick="removeLastRow()">1</option>
                        <?for($opciones=5;$opciones<=50;$opciones+=5){?>
                        <option id="<?echo $opciones-1?>" class="boton" value="<?echo $opciones?>"><?echo $opciones?></option>
                        <?}?>
                        </select>&nbsp;&nbsp;
                        <?//opciones para agregar 1 estudiante y opcion para borrar todas las filas?>
                        <input type="button" value="Adicionar filas" onClick="nuevaFila(1,<?echo $this->nivel;?>,<?echo $this->usuario;?>)" alt="Adicionar">
                        <input type="button" value="Reiniciar filas" onClick="removeLastRow()" alt="Remover">
                        <input type="button" value="Borrar fila" onClick="removeLastestRow()" alt="Remover">
                    </td>
                    </tr></table>
                <table id="tabla"  class="contenidotabla" width="100%">
                <div align="center" ><b><?echo "RECALCULAR ESTADO DE ESTUDIANTE "; ?></b></div><hr>
        <?//opciones para agregar 5,10,15...50 estudiantes?>

                    <thead class='sigma'>
                    <th class='niveles centrar' > Código</th>
                    <th class='niveles centrar' > Estudiante</th>
                    <th class='niveles centrar' > Estado Actual</th>
                    <th class='niveles centrar' > Proyecto Curricular</th>
                    </thead>
                    <tr >
                    <td width="13%" class='cuadro_plano centrar'>
                        1 <input type="text" id="codEstudiante0" name="codEstudiante[0]" size="11" maxlength="11" onKeyPress="return solo_numero(event)" onBlur="xajax_nombreEstudiante(document.getElementById('codEstudiante0').value,0,<? echo $this->nivel;?>,<? echo $this->usuario;?>)">
                        <input type="hidden" name="opcion" value="<? echo $this->opcion;?>">
                        <input type="hidden" name="action" value="<? echo $this->bloque; ?>">
                        
                    </td>
                    <td width="20%" class='cuadro_plano centrar'>
                        <div id="div_nombreEstudiante0" ></div>
                    </td>
                    <td width="15%" class='cuadro_plano centrar'>
                        <div id="div_estadoEstudiante0" ></div>
                    </td>
                    <td width="30%" class='cuadro_plano centrar'>
                        <div id="div_proyecto0" ></div>
                    </td>

                    </tr>
                </table>
                <table width="100%">
                <tr>
                    <td align="center">
                    <input type="button" value="Recalcular" onclick="if(verificarFormulario(<?echo $this->formulario?>)){document.forms['<? echo $this->formulario?>'].submit()}else{false}">
                    </td>
                </tr>
                </table>
            </form>
<?

    }
  
}


?>
