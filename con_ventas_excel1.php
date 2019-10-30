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
function datos_completos() {		
if(document.getElementById("combo_empresa").value==0){
    alert("Seleccione la empresa");
	return false; 
}
else
	document.forma.submit();

}
</script>
<? inicio() ?>
</head>
<body>
<table width="718" align="center">
  <tr>
    <td width="710" valign="top" ><form id="forma" name="forma" method="post" action="./informes/exportar_excel1.php" enctype="multipart/form-data">
        <table width="710" border="0" cellspacing="0" cellpadding="0" align="center" >
          <tr>
            <td width="710" bgcolor="#D1D8DE"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="27" colspan="7"><div align="center" class="subfongris">INFORME VENTAS POR EMPRESA </div></td>
                </tr>
                <tr>
                  <td width="7" height="20"></td>
                  <td width="99" class="ctablaform">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td height="20"></td>
                  <td class="ctablaform">Empresa</td>
                  <td width="160"><span class="textotabla1">
                    <? combo_evento_where("combo_empresa"," rsocial","cod_rso","nom_rso","","", "  order by nom_rso"); ?>
                     </span></td>
                  <td width="125">&nbsp;</td>
                  <td width="138">&nbsp;</td>
                  <td width="181" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td height="20"></td>
                  <td><span class="ctablaform">&nbsp;Fecha Inicial:</span></td>
                  <td><input name="fechas" type="text" id="fechas" size="10" readonly />
                    <img src="imagenes/date.png" alt="Calendario" name="calendario" width="18" height="18" id="calendario" style="cursor:pointer"/>&nbsp;<span class="textorojo">*</span></td>
                  <td><span class="ctablaform">&nbsp;&nbsp;Fecha Final:</span></td>
                  <td><input name="fechas2" type="text" id="fechas2" size="10" readonly />
                    <img src="imagenes/date.png" alt="Calendario" name="calendario1" width="18" height="18" id="calendario1" style="cursor:pointer" /><span class="textorojo">*</span></td>
                  <td colspan="2"><span class="ctablablanc">
                    <input type='hidden' name='codigo'>
                    <label>
                    <input type="submit" name="Submit" onClick="datos_completos()" value="Enviar">
                    </label>
                    </span></td>
                </tr>
                <tr>
                  <td height="20"></td>
                  <td class="ctablaform">&nbsp;</td>
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
</script>
      </form></td>
  </tr>
</table>
</body>
</html>
