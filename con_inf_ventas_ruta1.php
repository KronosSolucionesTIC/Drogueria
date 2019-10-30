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
	if(document.getElementById('fechas').value == ""  || document.getElementById('fechas2').value == ""  ||  document.getElementById('ruta').value=="") {
	 	alert('Seleccione la ruta  y las Fechas')
	}
	else 
	{
		var fechas = document.getElementById('fechas').value;
		var fechas2 = document.getElementById('fechas2').value;
		var ruta = document.getElementById('ruta').value;
		imprimir_inf("inf_ventas_ruta1.php",'0&fechas='+fechas+'&fechas2='+fechas2+'&ruta='+ruta,'mediano');
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
                <td height="27" colspan="7"><div align="center" class="subfongris">INFORME VENTAS POR RUTA </div></td>
              </tr>
              <tr>
                <td width="7" height="43"></td>
                <td width="99" class="ctablaform">Ruta</td>
                <td colspan="4"><?
						   combo_evento_where("ruta","m_ruta","cod_rut","nom_rut","","", " order by nom_rut"); 
						  ?>
                  <span class="textorojo">* </span> </td>
              </tr>
              <tr>
                <td height="20"></td>
                <td><span class="ctablaform">&nbsp;Fecha Inicial:</span></td>
                <td width="160"><input name="fechas" type="text" id="fechas" size="10" readonly="1" />
                  <img src="imagenes/date.png" alt="Calendario" name="calendario" width="18" height="18" id="calendario" style="cursor:pointer"/>&nbsp;<span class="textorojo">*</span></td>
                <td width="125"><span class="ctablaform">&nbsp;&nbsp;Fecha Final:</span></td>
                <td width="138"><input name="fechas2" type="text" id="fechas2" size="10" readonly="1" />
                  <img src="imagenes/date.png" alt="Calendario" name="calendario1" width="18" height="18" id="calendario1" style="cursor:pointer" /><span class="textorojo">*</span></td>
                <td width="362" colspan="2"><span class="ctablablanc">
                  <input type='hidden' name='codigo'>
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