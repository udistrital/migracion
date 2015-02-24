<SELECT id ="<? echo $nombre_combo; ?>" NAME="<? echo $nombre_combo; ?>" SIZE=1> 
    <? for ($i = 0; $i < count($lista_opciones); $i++) { 
            if(strpos($valorSeleccionado,$lista_opciones[$i][1]) != false){
                $seleccionado = "selected=selected";
            }else{
                $seleccionado = "";
            }                
        ?>                                  
    <OPTION VALUE='<? echo "*".$lista_opciones[$i][0]; ?>'<?=$seleccionado?> ><? echo $lista_opciones[$i][1]; ?></OPTION>               
    <? } ?>
</SELECT>
