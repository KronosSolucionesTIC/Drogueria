<? include("lib/database.php")?>
<? include("js/funciones.php")?>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script type="text/javascript" src="calendario/javascript/calendar.js"></script>
<script type="text/javascript" src="calendario/javascript/calendar-es.js"></script>
<script type="text/javascript" src="calendario/javascript/calendar-setup.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="calendario/styles/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script src="utilidades.js" type="text/javascript"> </script>
<title>
<?=$nombre_aplicacion?>
</title>
<script type="text/javascript">
var tWorkPath="menus/data.files/";
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

function abrir() {	 // cierre normal	
	if(document.getElementById('fec_fin').value == ""  ||  document.getElementById('fec_ini').value=="") {
	 	alert('Seleccione las Fechas de consulta')
	}
	else 
	{
		var fec_ini = document.getElementById('fec_ini').value;
		var fec_fin = document.getElementById('fec_fin').value;
		var responsable = document.getElementById('responsable').value;
		var contador = document.getElementById('contador').value;
		var cadena = '';
		for(i=1; i<=contador; i++){
			if(document.getElementById('result_'+i).checked == true ){
				var cod_llamada = document.getElementById('cod_rllamada_'+i).value;
				if(cadena == ''){
					var cadena = cadena + 'cod_resultado ='+ cod_llamada;
				}
				else{
					var cadena = cadena + ' or cod_resultado ='+ cod_llamada;
				}		
			}
		}
		imprimir_inf("inf_resumen_llamadas.php",'0&fec_ini='+fec_ini+'&fec_fin='+fec_fin+'&ven='+responsable+'&cad='+cadena,'mediano');
	}
}

function abrir_pagos() {		// cierre gastos
	if(document.getElementById('fec_fin').value == ""  ||  document.getElementById('fec_ini').value=="") {
	 	alert('Seleccione las Fechas de consulta')
	}
	else 
	{
		var fec_ini = document.getElementById('fec_ini').value;
		var fec_fin = document.getElementById('fec_fin').value;
		imprimir_inf("inf_cierre_pagos1.php",'0&fec_ini='+fec_ini+'&fec_fin='+fec_fin,'mediano');
	}
}

function abrir_gastos() { // cierre pagos
	if(document.getElementById('fec_fin').value == ""  ||  document.getElementById('fec_ini').value=="") {
	 	alert('Seleccione las Fechas de consulta')
	}
	else 
	{
		var fec_ini = document.getElementById('fec_ini').value;
		var fec_fin = document.getElementById('fec_fin').value;
		imprimir_inf("inf_cierre_gastos1.php",'0&fec_ini='+fec_ini+'&fec_fin='+fec_fin,'mediano');
	}
}
</script>
<script type="text/javascript" src="js/funciones.js"></script>
<script type="text/javascript" src="informes/inf.js"></script>
<link href="css/styles.css" rel="stylesheet" type="text/css">
</head>
<body  <?=$sis?> >
<table align="center">
  <tr>
    <td valign="top" ><table width="624" border="0" cellspacing="0" cellpadding="0" align="center" >
        <tr>
          <td><table width="587" border="0"  cellpadding="0" align="center">
              <tr>
                <td colspan="4"  class="ctablasup" >INFORME DE LLAMADAS</td>
              </tr>
              <? 
						$estilo="ctablablanc";
						$estilo="ctablagris";
						//echo $sql;						
						if ($aux==0) { $estilo="ctablablanc"; $aux=1; $cambio_celda=$celda_blanca; }else { $estilo="ctablagris";  $cambio_celda=$celda_gris; $aux=0;}
							echo "<tr class='$estilo' $cambio_celda> ";
						?>
              <tr>
                <td width="91" class="ctablablanc" >Fecha Inicial </td>
                <td width="155" class="ctablablanc" ><input name="fecha" type="text" class="textotabla01" id="fec_ini" readonly  />
                  <img src="imagenes/date.png" alt="Calendario" name="imageField" width="16" height="16" border="0" id="imageField" style="cursor:pointer"/></td>
                <td width="73" class="ctablablanc" >Fecha Final </td>
                <td width="155" class="ctablablanc" ><input name="fec_fin" type="text" class="textotabla01" id="fec_fin" readonly  />
                  <img src="imagenes/date.png" alt="Calendario" name="imageField1" width="16" height="16" border="0" id="imageField1" style="cursor:pointer"/></td>
              <tr>
                <td class="ctablablanc" >Responsable</td>
                <td class="ctablablanc" ><span class="textotabla1">
                  <? 
		combo_evento("responsable","responsable","cod_respon","desc_respon",'',"","desc_respon");
		?>
                </span></td>
                <td class="ctablablanc" ><img src='imagenes/mirar.png' alt="." width='16' height='16'  style="cursor:pointer"  onClick="abrir()" /></td>
                <td class="ctablablanc" >&nbsp;</td>
              </tr>
              <tr>
                <td class="ctablablanc" >Resultado</td>
                <td class="ctablablanc" >&nbsp;</td>
                <td class="ctablablanc" >&nbsp;</td>
                <td class="ctablablanc" >&nbsp;</td>
              </tr>
              <tr>
                <td class="ctablablanc" ><span class="textotabla1">
                  <?
    $dbr = new Database();
	$sqlr = "SELECT * FROM r_llamadas order by desc_rllamada";
	$dbr->query($sqlr);
	$i = 1;
		while($dbr->next_row()){
			echo "<tr>";
    		echo "<td>&nbsp;</td>";
    		echo "<td width='323' class='ctablablanc' >$dbr->desc_rllamada<input type='hidden' id='cod_rllamada_$i' name='cod_rllamada_$i' value='$dbr->cod_rllamada'></td>";
    		echo "<td width='1012' class='ctablablanc' ><input name='result_$i' type='checkbox' id='result_$i' /></td>";
  			echo "</tr>";
       		$i++;
    	} 
?>
                </span></td>
                <td class="ctablablanc" ><input type='hidden' id='contador' name='contador' value="<?=$i-1?>" /></td>
                <td class="ctablablanc" >&nbsp;</td>
                <td class="ctablablanc" >&nbsp;</td>
              </tr>
            </table ></td>
        </tr>
        <tr>
          <td><img src="imagenes/lineasup3.gif" width="624" height="4" /></td>
        </tr>
        <tr>
          <td height="30" align="center" valign="bottom"></td>
        </tr>
      </table></td>
  </tr>
</table>
<script type="text/javascript">
Calendar.setup(
	{
	inputField  : "fec_ini",      // ID of the input field
	ifFormat    : "%Y-%m-%d",    // the date format
	button      : "imageField" ,   // ID of the button
	//align       :"T2",
	singleClick :true
	}
);

Calendar.setup(
	{
	inputField  : "fec_fin",      // ID of the input field
	ifFormat    : "%Y-%m-%d",    // the date format
	button      : "imageField1" ,   // ID of the button
	//align       :"T2",
	singleClick :true
	}
);
</script>
</body>
</html>