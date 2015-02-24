<?php


class sql_demanda { 

    function cadena_sql($tipo, $variable="") {

        switch ($tipo) {

                case "consultarPersona":
		 
                $this->cadena_sql = "SELECT id_persona, codigo_interno, id_tipo_identificacion, nume_identificacion, ";
                $this->cadena_sql.= " primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, ";
                $this->cadena_sql.= " fecha_nacimiento, lugar_nacimiento, id_sexo, id_estado_civil, ";
                $this->cadena_sql.= " direccion, ciudad, telefono, celular, correo, estado ";
                $this->cadena_sql.= " FROM  recursos.persona ";
                $this->cadena_sql.= " WHERE 1=1 ";
                
                if($variable[0] != '')
                    {
                        $this->cadena_sql.= " AND id_tipo_identificacion = ".$variable[0];
                    }
                
                if($variable[1] != '')
                    {
                        $this->cadena_sql.= " AND nume_identificacion = ".$variable[1];
                    }
                
                if($variable[2] != '')
                    {
                        $this->cadena_sql.= " AND codigo_interno = ".$variable[2];
                    }
                
                
                break;	
				
        }

        return $this->cadena_sql;
    }

}

?>
