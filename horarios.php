<? include("conf/clave.php")?>
<? 
if(!isset($verifica_seg)) {
	setcookie("verifica_seg", "1", time() + 86400); 
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?=$nombre_aplicacion?></title>

</head>
<body onLoad="mueveReloj()">
<script language="javascript">
<?
if ($confirmacion==1){
	include ("validar.php");
}
?>
<?
if ($usu_atu==1 && $confirmacion==1){

$usu_atu==0; 
$confirmacion==0;
?>
menu=window.open('tirilla.php',"");
menu.focus();
<?
$refresca=1;
}

if($usu_atu==0 && $confirmacion==1){ 
	echo "alert('Usuario No Autorizado, Rectifique sus Datos ');";
}
?>

function DetectaBloqueoPops()
{

  var popup
  try
  {
    if(!(popup = window.open('about:blank','_blank','width=0,height=0')))
      throw "ErrPop"
    popup.close()
  }
  catch(err)
  {
    if(err=="ErrPop"){
      msj = "Esta Pagina funciona con ventanas emergentes recuerde habilitarlas"
   	   alert(msj);	
   }
    else
    {
      msj1="Hubo un erro en la página.nn"
      msj1+="Descripción del error: " + err.description + "nn"
     }
  }
 
 
}
function salta(e)
{
tecla = (document.all) ? e.keyCode : e.which
	if(tecla==13)
	{
	  //window.e.keyCode=0; 
	  document.getElementById("txt_clave").focus();
	}
}

function enviar(e,conf)
{
tecla = (document.all) ? e.keyCode : e.which
if(tecla==13 || conf==1)
{

  if (document.getElementById("txt_usuario").value !="" & document.getElementById("txt_clave").value != "") {
  	document.getElementById("confirmacion").value =1;
	var valor = document.getElementById('reloj').value;
	document.forma.submit();
  }
  else 
  {
	  	alert("Datos Incompletos");
		document.getElementById("txt_usuario").focus();
  }
  return false;
}


}


<? if ($confirmacion!=1 || $confirmacion!=1){ ?>
	DetectaBloqueoPops();
<? } ?>


function mueveReloj(){ 
   	momentoActual = new Date() 
   	hora = momentoActual.getHours() 
   	minuto = momentoActual.getMinutes() 
   	segundo = momentoActual.getSeconds() 

   	horaImprimible = hora + " : " + minuto + " : " + segundo 

   	document.forma.reloj.value = horaImprimible 

   	setTimeout("mueveReloj()",1000) 
} 
</script>
<style type="text/css">
td img {display: block;}
</style>
<link href="css/styles.css" rel="stylesheet" type="text/css">
<form  action="horarios.php"  name="forma" method="post" >
<table  border="0" align="center" cellpadding="0" cellspacing="0" >  
  <tr>
    <td colspan="3"  >
	<img src="imagenes/cabezotelog2.jpg" width="860" height="380">	</td>
  </tr>
  <tr>
    <td width="544"  align="left"><img src="imagenes/logoparte1.jpg" ></td>
    <td width="216"   valign="bottom" >
      <table width="100%" border="0" cellpadding="0" cellspacing="0" >
        <tr>
          <td colspan="6"><table width="80%" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td colspan="5"><img name="registro" src="imagenes/registro.gif" width="212" height="42" border="0" id="registro" alt="" /></td>
              </tr>
            <tr>
              <td width="9%" rowspan="3" align="left" valign="top"><img src="imagenes/login_izq.png"></td>
			  <!--TABLA INTERNA DE USUARIO Y CLAVE-->
              <td colspan="3" align="left"><table width="90%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="53" class="textotabla1">Usuario</td>
                   <td width="97" class="textotabla1"><input name="txt_usuario" id="txt_usuario" type="text" class="textotabla01" size="15" onKeyDown="salta(event)"/></td>
                </tr>
                <tr>
                  <td class="textotabla1">Clave:</td>
                    <td class="textotabla1"><input name="txt_clave" id="txt_clave" type="password" class="textotabla01" size="15" onKeyDown="enviar(event,0)" />
<input name="confirmacion" type="hidden" id="confirmacion" value="0" /></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="2" align="right"><img src="imagenes/entrar_boton.png" width="116" height="29" onClick="enviar(event,1)" style="cursor:pointer"></td>
                  </tr>
              </table></td>
              <!--FIN TABLA INTERNA DE USUARIO Y CLAVE-->
			  <td width="13%" rowspan="3" valign="top" ><img src="imagenes/login_der.png" height="133"></td>
            </tr>
            
            <tr>
              <td colspan="3" valign="bottom" >&nbsp;</td>
              </tr>
            <tr>
              <td colspan="3" ><img src="imagenes/entrar_base.png" width="166"></td>
            </tr>
            
            <tr>
              <td>&nbsp;</td>
              <td width="7%">&nbsp;</td>
              <td width="71%" align="right"><img src="imagenes/olvido_clave.png" width="108" height="17"  style="cursor:pointer"></td>
              <td colspan="2">&nbsp;</td>
              </tr>
          </table></td>
        </tr>
        
        <tr>
          <td colspan="6"><img src="imagenes/logoparte3.jpg" width="216" height="11"></td>
        </tr>
      </table></td>
    <td width="100"  ><img src="imagenes/logoparte2.jpg" width="100" height="213"></td>
  </tr>
  <tr>
  <td colspan="3" align="right">
        <FONT color="#BDBCBA" size="1" ><STRONG style="text-align:right">
        <input type="hidden" name="reloj" id="reloj" size="10">
        Copyright  
        <?=date("Y");?>
        , Dise&ntilde;o y Desarrollo 
      Kaome Estudio</STRONG></FONT>          </div>  </td>
  <!--INFORMACION DE DESARROLLO-->
  <!--FIN INFORMACION DE DESARROLLO-->
  </tr>
</table>
</form>
</body>
<script language="javascript">
<?
if($refresca==1)
{
?>
document.forma.submit()

<?
}
else 
{
?>
document.forma.txt_usuario.focus();
<?
}
?>

</script>