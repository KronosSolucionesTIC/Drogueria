<? include("lib/database.php")?>
<? include("js/funciones.php")?>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>
<?=$nombre_aplicacion?>
</title>
<script type="text/javascript" src="js/funciones.js"></script>
<script type="text/javascript" src="js/js.js"></script>
<link href="css/styles.css" rel="stylesheet" type="text/css">
<link href="css/stylesforms.css" rel="stylesheet" type="text/css" />
<script language="javascript">
function abrir() {		
	if(  document.getElementById('fecha').value == "" ) {
	 	alert('Seleccione una fecha');
	}
	else {
		var fechas = document.getElementById('fecha').value;
		imprimir_inf("inf_ventas_nacional.php",'0&fechas2='+fechas,'mediano');
	}
}
</script>
<script type="text/javascript" src="informes/inf.js"></script>
<? inicio() ?>
</head>
<body>
<table width="718" align="center">
  <tr>
    <td width="710" valign="top" ><table width="710" border="0" cellspacing="0" cellpadding="0" align="center" >
        <tr>
          <td width="710" bgcolor="#D1D8DE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="27" colspan="7"><div align="center" class="subfongris">INFORME NACIONAL DE VENTAS  SEGUN RUTA</div></td>
              </tr>
              
              <tr>
                <td height="20"></td>
                <td><span class="ctablaform">&nbsp;</span></td>
                <td width="160">&nbsp;</td>
                <td width="125"><span class="ctablaform">&nbsp;&nbsp;Fecha Final:</span></td>
                <td width="138"><input name="fecha" type="date" id="fecha" size="10" required />
                  <img src="imagenes/date.png" alt="Calendario" name="calendario1" width="18" height="18" id="calendario1" style="cursor:pointer" /><span class="textorojo">*</span></td>
                <td width="362" colspan="2"><span class="ctablablanc">
                  <img src='imagenes/mirar.png' width='16' height='16'  style="cursor:pointer"  onclick="abrir()" /></span></td>
              </tr>
              <tr>
                <td height="20"></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td colspan="2">&nbsp;</td>
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
      <script type="text/javascript">	
		
			Calendar.setup(
				{
					inputField  : "fecha",      
					ifFormat    : "%Y-%m-%d",    
					button      : "calendario1" ,  
					align       :"T3",
					singleClick :true
				}
			);		
</script></td>
  </tr>
</table>
</body>
</html>
