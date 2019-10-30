<? include("../lib/database.php")?>
<? include("../js/funciones.php")?>
<html>
<head>
<title>
<?=$nombre_aplicacion?>
</title>
<script type="text/javascript" src="js"></script>
<script type="text/javascript">
function imprimir(){
	document.getElementById('imp').style.display="none";
	document.getElementById('cer').style.display="none";
	window.print();
}

function ver_facturacion(obj,boton)
{
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
          <td >INFORME DE VENTAS POR VENDEDOR Y RUTAS</td>
          <td width="200">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
              <tr>
                <td colspan="9" class="subfongris"><div align="center">TOTAL VENTAS</div></td>
              </tr>
   <? $sqlgt="SELECT SUM(`tot_fac`-`tot_dev_mfac`) AS total_facturas FROM `m_factura` 
WHERE `estado` IS NULL $fecha";
$dbgt= new  Database();
$dbgt->query($sqlgt);
$dbgt->next_row();
$gran_total = $dbgt->total_facturas; ?>             
  <?
$arrCiudad = split("\,",$combo_ciudad);	
	for($j=0; $j<= count($arrCiudad); $j++){ //TODAS LOS VENDEDORES DEL COMBO					
		$vendedor = $arrCiudad[$j]; 
		if ($vendedor!=""){
			$sqlv="SELECT nom_ven FROM vendedor 
			WHERE cod_ven ='$vendedor'";
			$dbv= new  Database();
			$dbv->query($sqlv);
			while($dbv->next_row()){
				$sqltv="SELECT SUM(`tot_fac`-`tot_dev_mfac`) AS total_facturas FROM `m_factura`
				INNER JOIN bodega1 ON bodega1.cod_bod = m_factura.cod_cli 
				INNER JOIN ruta ON ruta.cod_ruta = bodega1.cod_ruta
				WHERE `estado` IS NULL AND cod_vendedor='$vendedor' $fecha";
				$dbtv= new  Database();
				$dbtv->query($sqltv);
				$dbtv->next_row();
				$total_vendedor = $dbtv->total_facturas;
?>
<tr>
    <td colspan="2" class="subfongris"><div align="left">VENDEDOR</div></td>
    <td colspan="2" class="subfongris"><div align="left">
      <?=$dbv->nom_ven?>
    </div></td>
    <td colspan="2" class="subfongris"><div align="center">TOTAL VENTAS X VENDEDOR</div></td>
    <td colspan="2" class="subfongris"><div align="right"><?=number_format($total_vendedor,0,".",".")?></div></td>
    </tr>
<?
				$sqlr="SELECT * FROM ruta 
				WHERE cod_vendedor ='$vendedor'";
				$dbr= new  Database();
				$dbr->query($sqlr);
				while($dbr->next_row()){
					$ruta = $dbr->cod_ruta;
					$sqltr="SELECT SUM(`tot_fac`-`tot_dev_mfac`) AS total_facturas FROM `m_factura`
					INNER JOIN bodega1 ON bodega1.cod_bod = m_factura.cod_cli 
					WHERE `estado` IS NULL AND cod_ruta=$ruta $fecha";
					$dbtr= new  Database();
					$dbtr->query($sqltr);
					$dbtr->next_row();
					$total_ruta = $dbtr->total_facturas;
?>
<tr>
    <td colspan="2" class="subfongris">RUTA</td>
    <td colspan="2" class="subfongris"><?=$dbr->desc_ruta?></td>
    <td colspan="2" class="subfongris"><div align="center">TOTAL VENTAS X RUTA</div></td>
    <td colspan="2" class="subfongris"><div align="right"><?=number_format($total_ruta,0,".",".")?></div></td>
</tr>
<?
					$sqlciu="SELECT * FROM bodega1
					INNER JOIN ciudad ON bodega1.ciu_bod = ciudad.cod_ciudad 
					WHERE cod_ruta ='$ruta' GROUP BY ciu_bod";
					$dbciu= new  Database();
					$dbciu->query($sqlciu);
					while($dbciu->next_row()){
						$ciudad = $dbciu->cod_ciudad;
						$sqltciu="SELECT SUM(`tot_fac`-`tot_dev_mfac`) AS total_facturas FROM `m_factura`
						INNER JOIN bodega1 ON bodega1.cod_bod = m_factura.cod_cli 
						WHERE `estado` IS NULL AND cod_ruta=$ruta AND ciu_bod=$ciudad $fecha";
						$dbtciu= new  Database();
						$dbtciu->query($sqltciu);
						$dbtciu->next_row();
						$total_ciudad = $dbtciu->total_facturas;
						
?>
  <tr>
    <td colspan="2" class="subfongris"><div align="left">CIUDAD</div></td>
    <td colspan="2" class="subfongris"><div align="left">
      <?=$dbciu->desc_ciudad?>
    </div></td>
    <td colspan="2" class="subfongris"><div align="center">TOTAL VENTAS X CIUDAD</div></td>
    <td colspan="2" class="subfongris"><div align="right">
      <?=number_format($total_ciudad,0,".",".")?>
    </div></td>
  </tr>
   <tr>
    <td width="12%" class="subfongris"><DIV align="center">CLIENTE</DIV></td>
    <td width="12%" class="subfongris"><DIV align="center">NIT</DIV></td>
    <td width="11%" class="subfongris"><DIV align="center">N. FACTURA </DIV></td>
    <td width="13%" class="subfongris"><div align="center">F. FACTURA</div></td>
    <td width="14%" class="subfongris"><div align="center">
      <DIV align="center">DIRECCION</DIV>
    </div></td>
    <td width="12%" class="subfongris"><DIV align="center">TELEFONO</DIV></td>
    <td width="14%" class="subfongris"><div align="center">
      <DIV align="center">CIUDAD</DIV>
    </div></td>
    <td width="12%" class="subfongris"><DIV align="center">TOTAL FACTURA</DIV></td>
  </tr>
<?
						$sqlc="SELECT *,CONCAT(nom_bod,' ',apel_bod) as nombre FROM m_factura
						INNER JOIN bodega1 ON bodega1.cod_bod = m_factura.cod_cli
						WHERE estado IS NULL AND ciu_bod ='$ciudad' $fecha AND cod_ruta = '$ruta'";
						$dbc= new  Database();
						$dbc->query($sqlc);
						while($dbc->next_row()){
							$total_factura = $dbc->tot_fac - $dbc->tot_dev_mfac;
?> 
    <tr>
    <td class="boton"><DIV align="center"><?=$dbc->nombre?></DIV></td>
    <td class="boton"><DIV align="center"><?=$dbc->iden_bod?></DIV></td>
    <td class="boton"><DIV align="center"><?=$dbc->num_fac?></DIV></td>
    <td class="boton"><DIV align="center"><?=$dbc->fecha?></DIV></td>
    <td class="boton"><DIV align="center"><?=$dbc->dir_bod?></DIV></td>
    <td class="boton"><DIV align="center"><?=$dbc->tel_bod?></DIV></td>
    <td class="boton"><DIV align="center"><?=$dbciu->desc_ciudad?></DIV></td>
    <td class="boton"><div align="right"><?=number_format($total_factura,0,".",".")?></div></td>
  </tr>
<?
						}
					}
				}
			}
			$total_vendedor = 0;
		}
	}//FIN DE TODOS LOS VENDEDORES DEL COMBO
?>
  <tr>
    <td colspan="8" class="boton"><div align="center">
      <input name="button2" type="button" class="botones1"  id="btn_ven" onClick="ver_facturacion('vendedor','btn_ven')" value="Ver Detalles" />
    </div></td>
  </tr>
  <tr>
    <td colspan="4" class="boton">&nbsp;</td>
    <td colspan="2" class="boton"><div align="center">TOTAL VENTAS</div></td>
    <td colspan="2" class="boton"><div align="center"><?=number_format($gran_total,0,".",".")?></div></td>
  </tr>
</table>
</br>
<table width="200" border="0" align="center">
  <tr>
    <td><div align="center" class="tituloproductos">
      <input name="button3" type="button" class="botones"  onclick="window.print()" value="Imprimir" />
      </div></td>
  </tr>
</table>
</body>
</html>