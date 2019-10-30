<? include("../lib/database.php")?>
<? include("../js/funciones.php")?>
<html>
<head>
<title>
<?=$nombre_aplicacion?>
</title>
<script type="text/javascript" src="js"></script>
<script type="text/javascript">
var tWorkPath="menus/data.files/";
function MM_jumpMenu(targ,selObj,restore){ //v3.0
   eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
   if (restore) selObj.selectedIndex=0;
}
function enviar() {
   document.form1.submit();
}
function enviar1()  {
   document.form2.submit();
}
function abrir(dato){
	var url="ver_factura_v.php?codigo="+dato;
	window.open(url,"ventana","menubar=0,resizable=1,width=800,height=600,toolbar=0,scrollbars=yes") 
}
function imprimir(){
	document.getElementById('imp').style.display="none";
	document.getElementById('cer').style.display="none";
	window.print();
}
function ver_ventas(obj,boton) {
	if(document.getElementById(obj).style.display =="none")
	{
		document.getElementById(obj).style.display ="inline";
		document.getElementById(boton).value ="Ocultar";
	}
	else {
		document.getElementById(obj).style.display ="none";
		document.getElementById(boton).value ="Ver Detalles";
	}
}
</script>
<script type="text/javascript" src="js/funciones.js"></script>
<link href="css/styles.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body,td,th {
	color: #FFFFFF;
}
body {
	background-color: #FFFFFF;
}
-->
</style>
<link href="css/print.css" rel="stylesheet" type="text/css">
<link href="css/stylesforms.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #FFFFFF}
-->
</style>
<link href="../css/styles.css" rel="stylesheet" type="text/css">
<link href="../css/styles1.css" rel="stylesheet" type="text/css">
<link href="../css/styles2.css" rel="stylesheet" type="text/css">
<link href="../css/stylesforms.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo38 {font-size: 9px; }
.Estilo32 {font-size: 10px}
-->
</style>
</head>
<body  <?=$sis?>>
<?  
if ($fechas2==0) {
	$fecha = "AND m_factura.fecha>='$fechas' ";
}
else{
	$fecha = "AND m_factura.fecha>='$fechas' AND m_factura.fecha<='$fechas2' ";
}
?>
<table width="100%"border="1" align="center" cellpadding="2" cellspacing="1"  bgcolor="#CCCCCC" class="textoproductos1">
  <tr>
    <td colspan= "10"><table width="100%" align="center" class="subfongris">
        <tr>
          <td width="200" height="21">&nbsp;</td>
          <td >INFORME DE VENTAS POR CIUDADES Y EMPRESAS </td>
          <td width="200">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td width="18%" class="boton"><div align="center">CLIENTE</div></td>
    <td width="10%" class="boton"><div align="center">NIT</div></td>
    <td width="9%" class="boton"><div align="center">N. FACTURA </div></td>
    <td width="10%" class="boton"><div align="center">F. FACTURA </div></td>
    <td width="10%" class="boton"><div align="center">DIRECCION</div></td>
    <td width="9%" class="boton"><div align="center">TELEFONO</div></td>
    <td width="13%" class="boton"><div align="center">CIUDAD</div></td>
    <td width="8%" class="boton"><div align="center">TOTAL FACTURA</div></td>
  </tr>
  <?  
$total = 0;
$total_dev = 0;  
$arrCiudad = split("\,",$arreglo_ciudad);  
for($g=0; $g<= count($arrCiudad); $g++){  
   $clbod = $arrCiudad[$g]; 
   if ($clbod!=""){  
		  $sqlg="SELECT distinct CL.ciu_bod, C.desc_ciudad FROM bodega1 CL INNER JOIN ciudad C ON  CL.ciu_bod  = C.cod_ciudad  WHERE   CL.ciu_bod='$clbod' order by C.desc_ciudad ";
		  $dbg= new  Database();
		  $dbg->query($sqlg);
		  while ($dbg->next_row()){ //inicio while ciudades
		  
				$ciudad=$dbg->ciu_bod;
				$nomciudad=$dbg->desc_ciudad;
							
				$arrEmpresa = split("\,",$combo_empresa);	
				for($j=0; $j<= count($arrEmpresa); $j++){ 	
					
					 $codEmp = $arrEmpresa[$j]; 
					 if ($codEmp!=""){  
						$sqlEmp="SELECT cod_rso, nom_rso, nit_rso FROM rsocial where cod_rso = '$codEmp'";
						$dbEmp= new  Database();
						$dbEmp->query($sqlEmp);	
								
						while ($dbEmp->next_row()){ //inicio while empresas		
						
							$codEmp = $dbEmp->cod_rso;
							$nomEmp = $dbEmp->nom_rso;
							//echo "EMPRESA".$nomEmp;
							$nitEmp = $dbEmp->nit_rso;
							$total_empresa = 0;
							$total_empresa_dev = 0;
							
							//filtrado de clientes por ciudad y empresa
							$sqlt="SELECT distinct  bodega1.cod_bod as cliente, bodega1.nom_bod
							  FROM bodega1
							  LEFT JOIN m_factura ON (bodega1.cod_bod = m_factura.cod_cli) 
							  LEFT JOIN rsocial ON (rsocial.cod_rso=m_factura.cod_razon_fac)
							  WHERE bodega1.ciu_bod='$ciudad'  AND  rsocial.cod_rso = '$codEmp' AND m_factura.estado is NULL    $fecha  order by m_factura.cod_cli"; 
					  /*  LEFT JOIN punto_venta ON (  bodega1.cod_bod_cli = punto_venta.cod_bod ) 
							  LEFT JOIN rsocial ON (punto_venta.cod_rso = rsocial.cod_rso)
							*/
							$dbClient= new  Database();
							$dbClient->query($sqlt);
							
							if ($dbClient->num_rows() >0 ) {
								?>
								  <tr>
									<td colspan= "10"><table width="100%" align="left"   bgcolor="#999999"  class="textoproductos1">
										<tr >
										  <td width="82" height="21">EMPRESA:</td>
										  <td width="489"><b><? echo $nitEmp ?>-<? echo $nomEmp ?><b></td>
										  <td width="427">&nbsp;</td>
										</tr>
									  </table></td>
								  </tr>
								  <?                         
							 }
							 while ($dbClient->next_row()) {  //inicio while clientes			 
								      $nombre=$dbClient->nom_bod;
									  $code=$dbClient->cliente;  
									  
									  $sqlr="SELECT bodega1.iden_bod, 
									  bodega1.dir_bod, 
									  bodega1.tel_bod, 
									  m_factura.num_fac, 
									  m_factura.fecha, 
									  m_factura.tot_fac,
									  m_factura.tot_dev_mfac,  
									  rsocial.reg_rso	  
									  FROM m_factura
									  
									  left join bodega1 on (bodega1.cod_bod=m_factura.cod_cli) 
			   						  left join rsocial on (rsocial.cod_rso=m_factura.cod_razon_fac)
			   						  left join usuario on (usuario.cod_usu=m_factura.cod_usu)
			   						  left join bodega on (bodega.cod_bod =m_factura.cod_bod) 
									  left join ciudad on  bodega1.ciu_bod = ciudad.cod_ciudad
									  
									  WHERE m_factura.cod_cli='$code' AND bodega1.ciu_bod='$ciudad'  AND rsocial.cod_rso='$codEmp' AND m_factura.estado IS NULL  $fecha
									  ORDER BY m_factura.fecha ASC"; 
									
									  $dbr= new  Database();
									  $dbr->query($sqlr);
									  $i==1;
						
									  while ($dbr->next_row())  //inicio while facturas
									  {
										  $regimen = $dbr->reg_rso;

										  $total_empresa +=$dbr->tot_fac;
										  $total_empresa_dev +=$dbr->tot_dev_mfac;
										  
										  if($i==1) {$color='#F2F4F7'; $color_est='#FFFFFF';} else {$color='#FFFFFF'; $color_est='#F2F4F7';}
																				  ?>
										  <tr bgcolor="<?=$color?>">
											<td class="textotabla1"><div align="left">
												<?=$nombre?>
											  </div></td>
											<td class="textotabla1"><div align="left">
												<?=$dbr->iden_bod?>
											  </div></td>
											<td class="textotabla1"><div align="center">
												<?=$dbr->num_fac?>
											  </div></td>
											<td class="textotabla1"><div align="center">
												<?=$dbr->fecha?>
											  </div></td>
											<td align="center" class="textotabla1"><div align="left">
												<?=$dbr->dir_bod?>
											  </div></td>
											<td align="center" class="textotabla1"><div align="center">
												<?=$dbr->tel_bod?>
											  </div></td>
											<td align="center" class="textotabla1"><div align="left">
												<?=$nomciudad?>
											  </div></td>
											<td align="center" class="textotabla1"><div align="center">
												<?=($dbr->tot_fac - $dbr->tot_dev_mfac)?>
											  </div></td>
										  </tr>
										  <? 
									   } //fin del while facturas
									  //$dbr->close(); 
							 } //fin del while clientes
							 if ($dbClient->num_rows() >0 ) {
							 	 ?>
								  <tr>
									<td colspan="10"><table width="100%"  border="1" align="center" cellpadding="2" cellspacing="0"  bgcolor="#FFFFFF" class="textoproductos1">
										<tr>
										  <td width="842" class="textotabla01"><div align="right">TOTAL VENTAS EMPRESA</div></td>
										  <td width="128"><div align="left" class="textotabla1">$
											  <?=number_format($total_empresa - $total_empresa_dev,0,".",".")?>
											</div></td>
										</tr>
									  </table></td>
								  </tr>
								  <?
							 } //del if	 
							 // 
							 
						} //fin del while empresas	
						//
						$total +=$total_empresa;
						$total_dev += $total_empresa_dev;
					 } 	//fin del if
					 
				}// fin del for de empresas
				
		  } // fin del while ciudades
		  if ($i==1) { $i=2; }
		  else {$i=1;} 
	
   } // fin del if
} //fin del for de ciudades
$dbClient->close();
$dbEmp->close();
$dbg->close();	
?>
</table>
</br>
<table width="100%"  border="1" align="center" cellpadding="2" cellspacing="0"   class="textoproductos1">
  <tr>
    <td width="842" class="textotabla01"><div align="right"><b>TOTAL VENTAS</b></div></td>
    <td width="128"><div align="left" class="textotabla1"><b>$
        <?=number_format($total - $total_dev,0,".",".")?></b>
      </div></td>
  </tr>
</table>
<table width="200" border="0" align="center">
  <tr>
    <td><div align="center" class="tituloproductos">
        <input type="hidden" name="mapa" value="<?=$mapa?>" />
        <input name="button3" type="button" class="botones"  onclick="window.print()" value="Imprimir" />
      </div></td>
  </tr>
</table>
</body>
</html>