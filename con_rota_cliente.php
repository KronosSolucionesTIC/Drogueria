<? 
include("lib/database.php"); 
include("js/funciones.php"); ?>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>
<?=$nombre_aplicacion?>
</title>
<script type="text/javascript" src="js/funciones.js"></script>
<script type="text/javascript" src="js/js.js"></script>
<script language="javascript">
function datos_completos()
{  
if(document.getElementById("ciudades_cliente").value==0){
alert("Seleccione una ciudad");
	return false; }

		document.forma.submit();
	
}
</script>
<? inicio() ?>
<link href="css/styles.css" rel="stylesheet" type="text/css">
<link href="css/stylesforms.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="772" align="center">
  <tr>
    <td width="764" valign="top" ><form id="forma" name="forma" method="post" action="con_rota_cliente1.php" enctype="multipart/form-data">
        <table width="710" border="0" cellspacing="0" cellpadding="0" align="center" >
          <tr>
            <td width="710" bgcolor="#D1D8DE"><table width="398" border="0" align="left" cellpadding="0" cellspacing="0">
                <tr>
                  <td></td>
                  <td height="33"></td>
                  <td width="37" class="titulosupsub">&nbsp;</td>
                  <td colspan="7" class="titulosupsub">SELECCIONE UNA CIUDAD</td>
                </tr>
                <tr>
                  <td width="1"></td>
                  <td width="1" height="33"></td>
                  <td class="ctablaform">&nbsp;</td>
                  <td width="138" class="ctablaform">Ciudades:</td>
                  <td width="19"><? combo_evento("ciudades_cliente","ciudad","cod_ciudad","desc_ciudad","","", "desc_ciudad"); 
						  ?></td>
                  <td width="4" align="center">&nbsp;</td>
                  <td width="90" align="center"><span class="ctablaform">
                    <input name="Siguiente" type="button" class="botones" onClick="datos_completos()" value="Siguiente">
                    </span></td>
                  <td width="4" class="ctablaform">&nbsp;</td>
                  <td width="91" align="center"><input type="hidden" name="editar"   id="editar"   value="<?=$editar?>" />
                    <input type="hidden" name="insertar" id="insertar" value="<?=$insertar?>">
                    <input type="hidden" name="eliminar" id="eliminar" value="<?=$eliminar?>">
                    <input type="hidden" name="codigo" id="codigo" value="<?=$codigo?>" /></td>
                  <td width="13" valign="middle">&nbsp;&nbsp;</a> </td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td><img src="imagenes/lineasup3.gif" width="710" height="5" /></td>
          </tr>
          <tr>
            <td height="30" align="center" valign="bottom">&nbsp;</td>
          </tr>
        </table>
      </form></td>
  </tr>
</table>
</body>
</html>