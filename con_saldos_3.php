<? include("lib/database.php")?>
<? include("js/funciones.php")?>

<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script type="text/javascript" src="calendario/javascript/calendar.js"></script>
<script type="text/javascript" src="calendario/javascript/calendar-es.js"></script>
<script type="text/javascript" src="calendario/javascript/calendar-setup.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="calendario/styles/calendar-win2k-cold-1.css" title="win2k-cold-1" />  <script src="utilidades.js" type="text/javascript"> </script>
<title><?=$nombre_aplicacion?></title>
<script type="text/javascript">
var tWorkPath="menus/data.files/";
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

function abrir() {		
	cod_bodega= document.getElementById('bodega').value;	
	imprimir_inf("inf_existencias.php",'0&codigo_bodega='+cod_bodega,'mediano');
}

</script>
<script type="text/javascript" src="js/funciones.js"></script>
<script type="text/javascript" src="informes/inf.js"></script>

<script language="javascript">

function enviar_dato(dato,caja,chec){


if(document.getElementById(chec).checked==true) {
	document.getElementById(caja).value=dato;
}

if(document.getElementById(chec).checked==false) {
	document.getElementById(caja).value="";
}


}
</script>

 <link href="css/styles.css" rel="stylesheet" type="text/css">
 
</head>
<body  <?=$sis?> >


<form action="ver_existencias3.php" method="post">

<table >
<tr>
<td valign="top" >

                  <table width="624" border="0" cellspacing="0" cellpadding="0" align="center" >
                    
                    <tr>
                      <td>
					  <table width="524" border="0"  cellpadding="0" align="center">
                        <tr>
						  <td  class="ctablasup" >EXISTENCIAS</td>
					    </tr>
						
						<tr >
							        <td > 
								  		 <table border="0" width="100%" >
					                  <? 
									  $i=1;
										$sql="SELECT cod_bod,nom_bod FROM bodega where estado_bodega = 1 order by nom_bod";
										$db->query($sql);  #consulta paginada
										while($db->next_row()) {
										echo "<tr > ";		  
										echo "<td class = 'textfield' width='260' >$db->nom_bod</td>";
										echo "<td class = 'textfield' width='60'>";
										echo "<input type='checkbox' name='checkbox_$i'  id='checkbox_$i' value='checkbox' onClick='enviar_dato(\"$db->cod_bod\",\"codigo_$i\",\"checkbox_$i\")'>";
										echo "<input type='hidden' name='codigo_$i' id='codigo_$i'  >";
										echo "</td></tr>";
										$i++;
										}
										?>
										
										
                                    </table></td>
					                          </table >
					  <p align="center">
					    <label>
					    <input type="hidden" name="val_inicial" id="val_inicial" value="<?=$i?>" >
					    <input type="submit" name="Submit" value="Ver Informe" class="ctablasup" >
					    </label>
					  </p></td>
                    </tr>
                    
                    <tr>
                      <td><img src="imagenes/lineasup3.gif" width="624" height="4" /></td>
                    </tr>
                  </table>

</td>
</tr></form>

				
<form name="forma" method="post" action="con_traza_general.php">
  <input type="hidden" name="editar" id="editar" value="<?=$editar?>">
  <input type="hidden" name="insertar" id="insertar" value="<?=$insertar?>">
  <input type="hidden" name="eliminar" id="eliminar" value="<?=$eliminar?>">
  <input type="hidden" name="cant_pag"  id="cant_pag" value="<?=$cant_pag?>">
  <input type="hidden" name="act_pag"  id="act_pag" value="<? if(!empty($act_pag)) echo $act_pag; else echo $pagina;?>">
  <input type="hidden" name="busquedas" id="busquedas" value="<?=$busquedas?>">
   <input type="hidden" name="eliminacion" id="eliminacion" >
    <input type="hidden" name="eli_codigo" id="eli_codigo" >
</form>
</body>

</html>



