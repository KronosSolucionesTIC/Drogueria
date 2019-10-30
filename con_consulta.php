<? include("lib/database.php")?>
<? include("js/funciones.php")?>
<? 

if(!empty($busquedas)) { #codigo para buscar 
	$busquedas=reemplazar_1($busquedas);
	$where="where $busquedas";
}#codigo para buscar 

  $sql="SELECT 
  producto.cod_pro,
  producto.cod_fry_pro,
  referencia.desc_ref,
  producto.cod_tpro_pro,
  tipo_producto.nom_tpro,
  producto.cod_mar_pro,
  marca.nom_mar,
  peso.nom_pes,
  kardex.cant_ref_kar,
  bodega.nom_bod
FROM producto
  INNER JOIN referencia ON (referencia.cod_ref = producto.nom_pro)
  left JOIN tipo_producto ON (tipo_producto.cod_tpro = producto.cod_tpro_pro)
  left JOIN kardex ON (producto.cod_pro = kardex.cod_ref_kar)
  left JOIN bodega ON (kardex.cod_bod_kar = bodega.cod_bod)
  left JOIN marca ON (producto.cod_mar_pro = marca.cod_mar)
  left JOIN peso ON (kardex.cod_talla = peso.cod_pes)  
  $where
  order by kardex.cant_ref_kar desc  "; 
  
  
$cantidad_paginas=paginar($sql);
$cant_pag=ceil($cantidad_paginas/$cant_reg_pag);
if(!empty($act_pag)) 
	$inicio=($act_pag -1)*$cant_reg_pag  ;
else { 
	$inicio =0;
	$act_pag=1;
	}
$paginar=" limit  $inicio, $cant_reg_pag";
$sql.=$paginar;
$busquedas=reemplazar($busquedas);

?>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?=$nombre_aplicacion?></title>
<script type="text/javascript">
var tWorkPath="menus/data.files/";
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

function buscar_producto()
	{	
		if(document.getElementById('texto').value=="" )
		{
			alert("Complete los Parametros, Gracias")
			return false;
		}

		else
		{
			document.getElementById('busquedas').value= "nom_pro" + "|" + document.getElementById('texto').value ;
		document.getElementById('otrotexto').value=document.getElementById('texto').value;
		document.forma.submit();
		}	
}

function buscar_prod(){	
	if(event.keyCode==13) {
		document.getElementById('busquedas').value= "nom_pro" + "|" + document.getElementById('texto').value ;
		document.getElementById('otrotexto').value=document.getElementById('texto').value;
		document.forma.submit();
	}
}

function iSubmitEnter(oEvento, oFormulario){ 
     var iAscii; 
     if (oEvento.keyCode) 
         iAscii = oEvento.keyCode; 
     else if (oEvento.which) 
         iAscii = oEvento.which; 
     else 
         return false; 

     if (iAscii == 13) {
	 	alert(1)
	 	document.getElementById('otrotexto').value=document.getElementById('texto').value;
		document.forma.submit();
     	return false; 
	 }
} 
//--> 
</script>


<script type="text/javascript" src="js/funciones.js"></script>
 <link href="css/styles.css" rel="stylesheet" type="text/css">
</head>
<body  <?=$sis?> onLoad="cambio_1(<?=$cant_pag?>,<?=$act_pag?>);">

<table width="681" align="center">
<tr>
<td valign="top" >

                  <table width="671" border="0" cellspacing="0" cellpadding="0" align="center" >
                    <tr>
                      <td bgcolor="#D1D8DE"><table width="429" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="20" height="33" class="ctablaform">&nbsp;</td>
                          <td width="53" class="ctablaform">Buscar: </td>
                          <td width="103"><input name="text" type="text" class="textfield" size="12" id="texto" /></td>
                          <td width="19" class="ctablaform">en</td>
                          <td width="53" class="ctablaform"><select name="campos" class="textfieldlista" id="campos" >
                            <option value="0">Seleccion</option>
                            <option value="cod_fry_pro">Codigo</option>
                            <option value="-1">Lista Completa</option>
                          </select></td>
                          <td width="103"><img src="imagenes/lupa.png" alt="" width="16" height="16" style="cursor:pointer"  onClick="buscar()"/></td>
                          <td width="19" class="ctablaform">&nbsp;</td>
                          <td width="160" valign="middle">&nbsp;</td>
                          <td width="74" valign="middle">&nbsp;</td>
                          <td width="160" valign="middle">&nbsp;</td>
                          <td width="74" valign="middle">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0"  cellpadding="0">
                        <tr>
                          
  						  <td   class="ctablasup">CODIGO</td>
						  <td   class="ctablasup">NOMBRE </td>
						  <td   class="ctablasup">TIPO PRODUCTO</td>
						  <td   class="ctablasup">MARCA </td>
						  <td   class="ctablasup">TALLA </td>
						  <td   class="ctablasup">CANTIDAD </td>
						  <td   class="ctablasup">BODEGA </td>
                        </tr>
						<? 
						
						echo "<tr style='display:none'><td ><form name='forma_0' action='con_consulta.php'>";
						echo "  </form> </td></tr>  ";						  
						$estilo="ctablablanc";
						$estilo="ctablagris";

						$db->query($sql);  #consulta paginada
						
						
						
						while($db->next_row()) {
							if ($aux==0) { $estilo="ctablablanc"; $aux=1; $cambio_celda=$celda_blanca; }else { $estilo="ctablagris";  $cambio_celda=$celda_gris; $aux=0;}
							echo "<tr class='$estilo' $cambio_celda> <form name='forma_$db->cod_pro' action='con_consulta.php' >  ";
							echo "<td >$db->cod_fry_pro </td>";
							echo "<td >$db->desc_ref</td>";
							echo "<td >$db->nom_tpro </td>";
							echo "<td >$db->nom_mar </td>";
							echo "<td >$db->nom_pes </td>";
							echo "<td >$db->cant_ref_kar </td>";
							echo "<td >$db->nom_bod </td>";
							
							echo "<input type='hidden' name='editar' value=".$editar."> <input type='hidden' name='insertar' value=".$insertar."> <input type='hidden' name='eliminar' value=".$eliminar.">";
							echo "  </form></tr>  ";
						
						}
						//}
						 ?>
                        
                      </table ></td>
                    </tr>
                    
                    <tr>
                      <td><img src="imagenes/lineasup3.gif" width="624" height="4" /></td>
                    </tr>
                    <tr>
                      <td height="30" align="center" valign="bottom"><table>
                        <tr>
                          <td> <span class="ctablaform" > <?  if ($cant_pag>0) echo "Pagina ".$act_pag." de ".$cant_pag ; else echo "No hay Resultados"  ?> </span>
                            <img src="imagenes/primero.png" alt="Inicio" width="16" height="16" id="primero" style="cursor:pointer; display:inline"  onClick="cambio(1)"/> <img src="imagenes/regresar.png" alt="Anterior" width="16" height="16" id="regresar" style="cursor:pointer; display:inline" onClick="cambio(2)"/> <img src="imagenes/siguiente.png" alt="Siguiente" width="16" height="16"  id="siguiente" style="cursor:pointer; display:inline" onClick="cambio(3)"/> <img src="imagenes/ultimo.png" alt="Ultimo" width="16" height="16" id="ultimo" style="cursor:pointer; display:inline" onClick="cambio(4)"/> </td>
                        </tr>
                      </table></td>
                    </tr>
      </table>

</td>
</tr>
</table>						
<form name="forma" method="post" action="con_consulta.php" >
  <input type="hidden" name="editar" id="editar" value="<?=$editar?>">
  <input type="hidden" name="insertar" id="insertar" value="<?=$insertar?>">
  <input type="hidden" name="eliminar" id="eliminar" value="<?=$eliminar?>">
  <input type="hidden" name="cant_pag"  id="cant_pag" value="<?=$cant_pag?>">
  <input type="hidden" name="act_pag"  id="act_pag" value="<? if(!empty($act_pag)) echo $act_pag; else echo $pagina;?>">
  <input type="hidden" name="busquedas" id="busquedas" value="<?=$busquedas?>">
   <input type="hidden" name="eliminacion" id="eliminacion" >
   <input type="hidden" name="otrotexto" id="otrotexto" >
    <input type="hidden" name="eli_codigo" id="eli_codigo" >
</form>
</body>

</html>
