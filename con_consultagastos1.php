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
function abrir() {		
	var grupo_gasto= document.getElementById('grupo_gastos').value;	
	var tipo_gastos= document.getElementById('tipo_gasto').value;	
	
	var fecha_ini= document.getElementById('fec_ini').value;	
	var fecha_fin= document.getElementById('fec_fin').value;	
	
	var ruta=  '0&grupo_gastos='+grupo_gasto+'&tipo_gasto='+tipo_gastos+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin;
	//alert(ruta)
	imprimir_inf("inf_total_gastos1.php",ruta,'mediano');
}
function busca_tipos() {
	var codigo_buscar_referencia =document.getElementById('grupo_gastos').value;	
	var combo_llenar=document.getElementById('tipo_gasto');	
	combo_llenar.options.length=0;
	var vec_productos = new Array;
	
	<?
	$dbdatos111= new  Database();
	$sql =" SELECT  * from tipo_gastos  order by nom_gas"; 
	$dbdatos111->query($sql);
	$i = 0;
	while($dbdatos111->next_row()){
		echo "vec_productos[$i]=  new Array('$dbdatos111->cod_gas','$dbdatos111->nom_gas','$dbdatos111->cod_gru_gas');\n";	
		$i++;
	}
	?>
	var cant=1;
	combo_llenar.options[0] = new Option('Seleccione','0'); 
	for (j=0; j<<?=$i?>;j++)
	{
		if(codigo_buscar_referencia==vec_productos[j][2]) 
		{
			combo_llenar.options[cant] = new Option(vec_productos[j][1],vec_productos[j][0]);  
			cant++; 	
		}
	}
}
</script>
<script type="text/javascript" src="js/funciones.js"></script>
<script type="text/javascript" src="informes/inf.js"></script>
<link href="css/styles.css" rel="stylesheet" type="text/css">
</head>
<body  <?=$sis?> >
<table width="769" align="center">
  <tr>
    <td valign="top" ><table width="729" border="0" cellspacing="0" cellpadding="0" align="center" >
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><img src="imagenes/lineasup3.gif" width="624" height="4" /></td>
        </tr>
      </table>
      <table width="480" border="0" align="center">
        <tr>
          <td width="275"  class="ctablasup" ><div align="left">GRUPO DE GASTOS</div></td>
          <td width="195"><span class="textotabla1">
            <? combo_evento("grupo_gastos","grupo_gastos","cod_gru","nom_gru",""," onchange=\"busca_tipos()\" ", "nom_gru"); ?>
            </span></td>
        </tr>
        <tr>
          <td width="275"  class="ctablasup" ><div align="left">TIPO DE GASTOS</div></td>
          <td><select size="1" id="tipo_gasto" name="tipo_gasto"  class='SELECT'>
            </select></td>
        </tr>
        <tr>
          <td width="275"  class="ctablasup" ><div align="left">FECHA INICIAL</div></td>
          <td><span class="ctablablanc">
            <input name="fec_ini" type="text" class="textotabla01" id="fec_ini" readonly="1"  />
            </span><span class="ctablablanc"><img src="imagenes/date.png" alt="Calendario" name="imageField" width="16" height="16" border="0" id="imageField" style="cursor:pointer"/></span></td>
        </tr>
        <tr>
          <td width="275"  class="ctablasup" ><div align="left">FECHA FINAL</div></td>
          <td><input name="fec_fin" type="text" class="textotabla01" id="fec_fin" readonly="1"  />
            <img src="imagenes/date.png" alt="Calendario" name="imageField1" width="16" height="16" border="0" id="imageField1" style="cursor:pointer"/></td>
        </tr>
        <tr>
          <td colspan="2"><br></td>
        </tr>
        <tr>
          <td colspan="2"><div align="center"><img src='imagenes/mirar.png' alt="." width='16' height='16'  style="cursor:pointer"  onclick="abrir()" /></div></td>
        </tr>
      </table>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p></td>
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