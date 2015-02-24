<script languaje="javascript">
function bloqDesbloq()
{
a = login.usuario.value


    if (a != "") { a = true; }
    else { a = false; }
    if (a == true) { login.clave.disabled = false; }
    else { login.clave.disabled = true; }
}
</script>