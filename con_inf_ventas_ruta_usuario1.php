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
<script type="text/javascript" src="informes/inf.js"></script>
<script language="javascript">
function abrir() {		
	if(document.getElementById('fechas').value == ""  || document.getElementById('fechas2').value == ""  ||  document.getElementById('usuario').value=="") {
	 	alert('Seleccione el usuario y las Fechas');
	}
	else 
	{
		var fechas = document.getElementById('fechas').value;
		var fechas2 = document.getElementById('fechas2').value;
		var usu = document.getElementById('usuario').value;
		imprimir_inf("inf_ventas_ruta_usuario.php",'0&fechas='+fechas+'&fechas2='+fechas2+'&usuario='+usu,'mediano');
	}
}
</script>
<? inicio() ?>
</head>
<body>
<table width="718" align="center">
  <tr>
    <td width="710" valign="top" ><table width="710" border="0" cellspacing="0" cellpadding="0" align="center" >
        <tr>
          <td width="710" bgcolor="#D1D8DE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="27" colspan="7"><div align="center" class="subfongris">INFORME VENTAS POR USUARIO SEGUN RUTA</div></td>
              </tr>
              <tr>
                <td width="7" height="43"></td>
                <td width="99" class="ctablaform">Usuario</td>
                <td colspan="4"><?
						   combo_evento_where("usuario","usuario","cod_usu","nom_usu","","", " where cod_usu in (select distinct usuario from ruta) order by nom_usu"); 
						  ?>
                  <span class="textorojo">* </span> </td>
              </tr>
              <tr>
                <td height="20"></td>
                <td><span class="ctablaform">&nbsp;Fecha Inicial:</span></td>
                <td width="160"><input name="fechas" type="date" id="fechas" size="10" required />
                  <img src="imagenes/date.png" alt="Calendario" name="calendario" width="18" height="18" id="calendario" style="cursor:pointer"/>&nbsp;<span class="textorojo">*</span></td>
                <td width="125"><span class="ctablaform">&nbsp;&nbsp;Fecha Final:</span></td>
                <td width="138"><input name="fechas2" type="date" id="fechas2" size="10" required />
                  <img src="imagenes/date.png" alt="Calendario" name="calendario1" width="18" height="18" id="calendario1" style="cursor:pointer" /><span class="textorojo">*</span></td>
                <td width="362" colspan="2" onClick="abrir()"> <input type='hidden' name='codigo'><span class="ctablablanc">
                  <img src='imagenes/mirar.png' width='16' height='16'  style="cursor:pointer"  onClick="abrir()" /></span> </td>
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
					inputField  : "fechas",      
					ifFormat    : "%Y-%m-%d",    
					button      : "calendario" ,  
					align       :"T3",
					singleClick :true
				}

			);		
			Calendar.setup(
				{
					inputField  : "fechas2",      
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
