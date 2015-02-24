
/**********************************************************************************   
 ValidaAcasp
 *   Copyright (C) 2006 UNIVERSIDAD DISTRITAL FRANCISCO JOS� DE CALDAS
 *   Este script fue realizado en la Oficina Asesora de Sistemas
 *********************************************************************************/
var fechaNac = document.forms["acasp"].elements["FechaNac"].value;

function nacdate(curp) {
    var m = fechaNac.match(/^\w{4}(\w{2})(\w{2})(\w{2})/);
    //miFecha = new Date(año,mes,dia) 
    var anyo = parseInt(m[1], 10) + 1900;
    if (anyo < 1950)
        anyo += 100;
    var mes = parseInt(m[2], 10) - 1;
    var dia = parseInt(m[3], 10);
    return (new Date(anyo, mes, dia));
}

Date.prototype.toString = function() {
    var anio = this.getFullYear();
    var mes = this.getMonth() + 1;
    if (mes <= 9)
        mes = "0" + mes;
    var dia = this.getDate();
    if (dia <= 9)
        dia = "0" + dia;
    return dia + "/" + mes + "/" + anio;
}

fechanac = document.write(nacdate(fechaNac));

window.alert(fechanac);

function ValidaInscripcionAspirante() {

    //1. Medio de publicidad.
    if (isNaN(document.forms["acasp"].elements["MedPub"].value)) {
        window.alert("Por que medio se enter&oacute; de la Universidad Distrital:, el dato debe ser num&eacute;rico");
        document.forms["acasp"].elements["MedPub"].focus();
        return 0;
    }
    if (document.forms["acasp"].elements["MedPub"].value == "0") {
        window.alert("Debe ingresar el medio de publicidad.");
        document.forms["acasp"].elements["MedPub"].focus();
        return 0;
    }
    //2. Carrera en la que se inscribe.
    if (isNaN(document.forms["acasp"].elements["CraCod"].value)) {
        window.alert("C&oacute;digo de la carrera en la que se inscribe:, el dato debe ser num&eacute;rico");
        document.forms["acasp"].elements["CraCod"].focus();
        return 0;
    }

    if (document.forms["acasp"].elements["CraCod"].value == "0") {
        window.alert("Debe seleccionar una carrera.");
        document.forms["acasp"].elements["CraCod"].focus();
        return 0;
    }
    if (document.forms["acasp"].elements["acta"].value.length < 3) {
        window.alert("Debe ingresar numero de acta de Grado del Titulo de Tecnologo del cual es egresado.");
        document.forms["acasp"].elements["acta"].focus();
        return 0;
    }

    cartec = document.forms["acasp"].elements["CraCod"].value;
    if ((cartec == "383") || (cartec == "373") || (cartec == "375") || (cartec == "377") || (cartec == "678") || (cartec == "579") || (cartec == "372"))
    {
        var statusConfirm = confirm("Esta carrera requiere titulo de tecnologo. En el evento de no acreditar el titulo favor consultar los requisitos de inscripcion de la facultad tecnologica, programas de ciclo profesional de ingenieria. ¿Desea continuar?")
        if (statusConfirm == true)
        {
            window.alert("Continue...");
        }
        else
        {
            window.alert("La carrera seleccionada requiere un titulo de tecnologo.");
            document.forms["acasp"].elements["CraCod"].focus();
            return 0;
        }
    }

    //3. Departamento de nacimiento.
    if (isNaN(document.forms["acasp"].elements["DptoNac"].value)) {
        window.alert("'Departamento:', el dato debe ser numerico");
        document.forms["acasp"].elements["DptoNac"].focus();
        return 0;
    }
    if (document.forms["acasp"].elements["DptoNac"].value == "0") {
        window.alert("Debe seleccionar un departamento.");
        document.forms["acasp"].elements["DptoNac"].focus();
        return 0;
    }
    //4. Ciudad de nacimiento.
    if (isNaN(document.forms["acasp"].elements["CiudadNac"].value)) {
        window.alert("'Ciudad:', el dato debe ser numerico");
        document.forms["acasp"].elements["CiudadNac"].focus();
        return 0;
    }
    if (document.forms["acasp"].elements["CiudadNac"].value == "") {
        window.alert("Debe seleccionar una ciudad de nacimiento.");
        document.forms["acasp"].elements["CiudadNac"].focus();
        return 0;
    }
    //5. Fecha de nacimiento.
    if (document.forms["acasp"].elements["FechaNac"].value == "") {
        window.alert("Debe ingresar una fecha de nacimiento.");
        document.forms["acasp"].elements["FechaNac"].focus();
        document.forms["acasp"].elements["FechaNac"].value="";
        return 0;
    }

    fechacomp = ("01/01/2005");
    fechanac = document.forms["acasp"].elements["FechaNac"].value
    var xMonth = fechanac.substring(3, 4);
    var xDay = fechanac.substring(0, 2);
    var xYear1 = fechanac.substring(6, 10);
    var xYear2 = fechanac.substring(4, 8);
    var xYear3 = fechanac.substring(5, 9);

    if (!isNaN(xYear1)) {
        if (xYear1 >= 2005) {
            window.alert("El ano de nacimiento no esta correcto.");
            document.forms["acasp"].elements["CraCod"].focus();
            return 0
        }
    }
    else
        true;

    if (!isNaN(xYear2)) {
        if (xYear2 >= 2005) {
            window.alert("El ano de nacimiento no esta correcto.");
            document.forms["acasp"].elements["CraCod"].focus();
            return 0
        }
    }
    else
        true;

    if (!isNaN(xYear3)) {
        if (xYear3 >= 2005) {
            window.alert("El ano de nacimiento no esta correcto.");
            document.forms["acasp"].elements["CraCod"].focus();
            return 0
        }
    }
    else
        true;


//6. Documento actual.
    if (isNaN(document.forms["acasp"].elements["DocActual"].value)) {
        window.alert("'Documento actual:', el dato debe ser numerico");
        document.forms["acasp"].elements["DocActual"].focus();
        return 0;
    }
    if (document.forms["acasp"].elements["DocActual"].value == "") {
        window.alert("Debe ingresar el numero de su documento actual.");
        document.forms["acasp"].elements["DocActual"].focus();
        return 0;
    }
    //7. Documento Icfes.
    if (isNaN(document.forms["acasp"].elements["DocIcfes"].value)) {
        window.alert("'Documento de identidad con el que presento el ICFES:', el dato debe ser numerico");
        document.forms["acasp"].elements["DocIcfes"].focus();
        return 0;
    }
    if (document.forms["acasp"].elements["DocIcfes"].value == "") {
        window.alert("Debe ingresar el numero del documento con que presento el ICFES.");
        document.forms["acasp"].elements["DocIcfes"].focus();
        return 0;
    }
    //8. Icfes.
    if (isNaN(document.forms["acasp"].elements["NroIcfes"].value)) {
        window.alert("'Numero del registro del icfes (SNP):', el dato debe ser numerico");
        document.forms["acasp"].elements["NroIcfes"].focus();
        return 0;
    }
    if (document.forms["acasp"].elements["NroIcfes"].value == "") {
        window.alert("Debe ingresar el numero del registro del icfes.");
        document.forms["acasp"].elements["NroIcfes"].focus();
        return 0;
    }
    if (isNaN(document.forms["acasp"].elements["CNroIcfes"].value)) {
        window.alert("'Numero del registro del icfes (SNP):', el dato debe ser numerico");
        document.forms["acasp"].elements["CNroIcfes"].focus();
        return 0;
    }
    if (document.forms["acasp"].elements["CNroIcfes"].value == "") {
        window.alert("Debe ingresar el numero del registro del icfes.");
        document.forms["acasp"].elements["CNroIcfes"].focus();
        return 0;
    }

//DATOS SOCIO-ECON�MICOS
    //12. Direcci�n actual de residencia.
    if (document.forms["acasp"].elements["dir"].value == "") {
        window.alert("Debe digitar la direccion actual de residencia.");
        document.forms["acasp"].elements["dir"].focus();
        return 0;
    }
    //14. Tel�fono de residencia.
    if (document.forms["acasp"].elements["tel"].value == "") {
        window.alert("Debe ingresar el numero de telefono de residencia.");
        document.forms["acasp"].elements["tel"].focus();
        return 0;
    }
    //El teléfono debe ser numérico sin espacios.
    if (isNaN(document.forms["acasp"].elements["tel"].value)) {
        window.alert("El número de teléfono debe de ser digitado sin espacios.");
        document.forms["acasp"].elements["tel"].focus();
        return 0;
    }
    //15. Valida cuenta de correo
    if (document.forms["acasp"].elements["CtaCorreo"].value == "") {
        window.alert("Debe ingresar un correo electronico.");
        document.forms["acasp"].elements["CtaCorreo"].focus();
        return 0;
    }

    if ((document.forms["acasp"].elements["CtaCorreo"].value.indexOf('@', 0) == -1) || (document.forms["acasp"].elements["CtaCorreo"].value.length < 5)) {
        window.alert("Escriba una direccion de correo valida.");
        return (false);
    }

    //16. Localidad del colegios
    if (document.forms["acasp"].elements["LocCol"].value == "") {
        window.alert("Debe ingresar la localidad del colegio.");
        document.forms["acasp"].elements["LocCol"].focus();
        return 0;
    }

    // 17. Tipo de Colegio
    if (document.forms["acasp"].elements["LocCol"].value == "") {
        window.alert("Debe ingresar la localidad del colegio.");
        document.forms["acasp"].elements["TipCol"].focus();
        return 0;
    }

    // 18. Discapacidad
    if (document.forms["acasp"].elements["LocCol"].value == "") {
        window.alert("Debe ingresar la localidad del colegio.");
        document.forms["acasp"].elements["Discap"].focus();
        return 0;
    }

    // Validar SNP
    var str = document.forms["acasp"].elements["NroIcfes"].value;
    var str_carrera = parseInt(document.forms["acasp"].elements["Select1"].value);
    var res = str.substring(0, 5);

    if ((str_carrera !== 373)) {
        if ((str_carrera !== 678)) {
            if ((str_carrera !== 579)) {
                if ((str_carrera !== 383)) {
                    if ((str_carrera !== 372)) {
                        if ((str_carrera !== 375)) {
                            if ((str_carrera !== 377)) {
                                if (res < 20092) {
                                    window.alert("Ingrese un SNP válido. Recuerde que debe ser mínimo del 20092");
                                    document.forms["acasp"].elements["NroIcfes"].focus();
                                    document.forms["acasp"].elements["NroIcfes"].value = '';
                                    return false;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    //Compara SNP
    if (document.forms["acasp"].elements["TipoIcfes"].value != document.forms["acasp"].elements["CVTipoIcfes"].value) {
        window.alert("El Tipo de registro del icfes (SNP) es diferente.");
        document.forms["acasp"].elements["TipoIcfes"].focus();
        return 0;
    }
    if (document.forms["acasp"].elements["NroIcfes"].value != document.forms["acasp"].elements["CNroIcfes"].value) {
        window.alert("El Numero del registro del icfes (SNP) es diferente.");
        document.forms["acasp"].elements["CNroIcfes"].focus();
        document.forms["acasp"].elements["CNroIcfes"].value = '';
        return 0;
    }
    
    

    document.forms["acasp"].submit();
}



function ValidaSNP() {
    //Compara SNP
    if (document.forms["acasp"].elements["TipoIcfes"].value != document.forms["acasp"].elements["CVTipoIcfes"].value) {
        window.alert("El Tipo de registro del icfes (SNP) es diferente.");
        document.forms["acasp"].elements["TipoIcfes"].focus();
        return 0;
    }
    if (document.forms["acasp"].elements["NroIcfes"].value != document.forms["acasp"].elements["CNroIcfes"].value) {
        window.alert("El Numero del registro del icfes (SNP) es diferente.");
        document.forms["acasp"].elements["CNroIcfes"].focus();
        document.forms["acasp"].elements["CNroIcfes"].value = '';
        return 0;
    }
}
