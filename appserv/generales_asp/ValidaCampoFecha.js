/**********************************************************************************   
 ValidaAcasp
 *   Copyright (C) 2006 UNIVERSIDAD DISTRITAL FRANCISCO JOS� DE CALDAS
 *   Este script fue realizado en la Oficina Asesora de Sistemas
 *   Por: Pedro Luis Manjarr�s Cuello
 *********************************************************************************/
function ValidaCampoFecha() {
    if (document.forms[0].elements["FechaNac"].value != "") {
        var fecha2 = document.forms[0].elements["FechaNac"].value
        var pos1b = fecha2.indexOf("/", 0)
        var pos2b = fecha2.indexOf("/", 4)

        if (pos1b == -1 || pos2b == -1) {
            window.alert('Debe introducir una fecha del tipo 01/01/1998')
            document.forms[0].elements["FechaNac"].value = null;
            document.forms[0].elements["FechaNac"].select();

            return 0;
        } else {
            dia2 = parseInt(fecha2.substr(0, pos1b))
            mes2 = parseInt(fecha2.substr(pos1b + 1, pos2b - pos1b))
            anno2 = parseInt(fecha2.substr(pos2b + 1))
            if (mes2 == 0 || dia2 == 0 || anno2 == 0) {
                window.alert('Debe introducir una fecha del tipo 01/01/1998')
                document.forms[0].elements["FechaNac"].value =null;
                document.forms[0].elements["FechaNac"].select();
                return 0;
            }
            if (mes2 >= 13 || dia2 >= 32 || anno2 <= 999 || isNaN(mes2) || isNaN(dia2) || isNaN(anno2)) {
                window.alert('Debe introducir una fecha del tipo 01/01/1998')
                document.forms[0].elements["FechaNac"].value = null;
                document.forms[0].elements["FechaNac"].select();
                return 0;
            }

        }
    }
}

