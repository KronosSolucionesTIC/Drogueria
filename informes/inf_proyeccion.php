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
   <? if($guardar_todo==1) {

	$texto =$contexto;
	$sql="select cod_usu, nom_usu from usuario  where cod_usu=$codigo_usuario";
	$dbdatos= new  Database();
	$dbdatos->query($sql);
	if ($dbdatos->next_row())
		$nombre=$dbdatos->nom_usu;
		echo "Enviando alerta de cierre a usuario ".$nombre;
		enviar_alerta("Alerta Cierre de $nombre " , "  $texto  ");
	}
	?>

<?  
$date = strtotime($fechas2);
$ano = date("Y", $date);
$mes = date("m", $date); 
$fechas1 = $ano."-".$mes."-01";

	$contexto.="<table width='100%'border='1' align='center' cellpadding='2' cellspacing='1'>
	        <tr>
          <td height='21' colspan='5' class='subfongris'>INFORME DE PROYECCION</td>
        </tr>
  <tr>
    <td colspan= '9'><table width='100%' align='center' >
        <tr>
          <td width='14%' height='21' class='boton'>FECHA INICIAL</td>
          <td width='14%' class='boton'>".$fechas1."</td>
          <td width='14%' class='boton'>FECHA FINAL</td>
          <td width='14%' class='boton'>".$fechas2."</td>
          <td class='boton'>".$dias."</td>
        </tr>
</table>
</td>
</tr>
  <tr>
    <td align='center'>VENDEDOR</td>
    <td class='boton'><div align='center'>VENTA MES</div></td>
    <td class='boton'><div align='center'>VENTA DIARIA</div></td>
    <td class='boton'><div align='center'>ACUMULADO</div></td>
    <td class='boton'><div align='center'>META AL DIA</div></td>
    <td class='boton'><div align='center'>CUMPLIMIENTO ACUMULADO</div></td>
    <td class='boton'><div align='center'>CUMPLIMIENTO PROYECTADO</div></td>
  </tr>
 
 
"
?>


<table width="100%"border="1" align="center" cellpadding="2" cellspacing="1"  bgcolor="#CCCCCC" class="textoproductos1">
  <tr>
    <td colspan= "9"><table width="100%" align="center" >
        <tr>
          <td width="14%" height="21" class="boton">FECHA INICIAL</td>
          <td width="14%" class="boton"><?=$fechas1?></td>
          <td width="14%" class="boton">FECHA FINAL</td>
          <td width="14%" class="boton"><?=$fechas2?></td>
          <td class="boton"><?=$dias?></td>
        </tr>
        <tr>
          <td height="21" colspan="5" class="subfongris">INFORME DE PROYECCION</td>
        </tr>
</table>
</td>
</tr>
  <tr>
    <td class="boton"><div align="center">VENDEDOR</div></td>
    <td class="boton"><div align="center">VENTA MES</div></td>
    <td class="boton"><div align="center">VENTA DIARIA</div></td>
    <td class="boton"><div align="center">ACUMULADO</div></td>
    <td class="boton"><div align="center">META AL DIA</div></td>
    <td class="boton"><div align="center">CUMPLIMIENTO ACUMULADO</div></td>
    <td class="boton"><div align="center">CUMPLIMIENTO PROYECTADO</div></td>
  </tr>
  <? 
 
$arrVendedor = split("\,",$combo_ciudad);	
for($j=0; $j<= count($arrVendedor); $j++){ //TODAS LOS VENDEDORES DEL COMBO					
	$vendedor = $arrVendedor[$j]; 
	if ($vendedor!=""){ 
		  $sqlv="SELECT * FROM vendedor 
		  WHERE cod_ven ='$vendedor'";
		  $dbv= new  Database();
		  $dbv->query($sqlv);
		  		  
		while ($dbv->next_row()){ //INICIO WHILE VENDEDORES
		
			$sqlp="SELECT * FROM m_proyeccion 
		  	WHERE MONTH(`fecha_proyeccion`) = '$mes' AND estado_proyeccion = 1";
		  	$dbp= new  Database();
		  	$dbp->query($sqlp);
			$dbp->next_row();
			$cod_proyeccion = $dbp->cod_proyeccion;
			
			$sqldp="SELECT * FROM d_proyeccion 
		  	WHERE cod_proyeccion = '$cod_proyeccion' AND cod_vendedor ='$vendedor'";
		  	$dbdp= new  Database();
		  	$dbdp->query($sqldp);
			$dbdp->next_row();
			
			$sqlr="SELECT * FROM ruta 
		  	WHERE cod_vendedor ='$vendedor'";
		  	$dbr= new  Database();
		  	$dbr->query($sqlr);
			$total_ventas = 0;
			
			while ($dbr->next_row()){
				
				$ruta = $dbr->cod_ruta;
				$sqlc="SELECT SUM(tot_fac) AS total FROM `m_factura`
				INNER JOIN bodega1 ON bodega1.cod_bod = m_factura.cod_cli
				WHERE bodega1.cod_ruta='$ruta' AND m_factura.estado is NULL AND m_factura.fecha>='$fechas1' AND m_factura.fecha<='$fechas2'";
		  		$dbc = new  Database();
		  		$dbc->query($sqlc);
				$dbc->next_row();
				$total_ventas = $total_ventas + $dbc->total;
			}
			
			echo "<tr>";
			$contexto.= "<tr>";
			
   			echo "<td class='boton'><div align='center'>$dbv->nom_ven</div></td>";
			$contexto.= "<td class='boton'><div align='center'>".$dbv->nom_ven."</div></td>";
			
			$total_mes = $total_mes + $dbdp->proyeccion_individual;
			$valor = number_format($dbdp->proyeccion_individual,0,".",".");
    		echo "<td class='boton'><div align='right'>$ $valor</div></td>";
			$contexto.="<td class='boton'><div align='right'>$ ".$valor."</div></td>";
			
			$venta_diaria = $dbdp->proyeccion_individual / $dbp->dias_habiles;
			$total_venta_diaria = $total_venta_diaria + $venta_diaria;
			$venta_diaria_texto = number_format($venta_diaria,0,".",".");
			echo "<td class='boton'><div align='right'>$ $venta_diaria_texto</div></td>";
			$contexto.="<td class='boton'><div align='right'>$ ".$venta_diaria_texto."</div></td>";
			
			$total_acumulado = $total_acumulado + $total_ventas;
			$acumulado_texto = number_format($total_ventas,0,".",".");
    		echo "<td class='boton'><div align='right'>$ $acumulado_texto</div></td>";
			$contexto.="<td class='boton'><div align='right'>$ ".$acumulado_texto."</div></td>";
			
			$meta_dia = $venta_diaria * $dias;
			$total_meta_dia = $total_meta_dia + $meta_dia;
			$meta_dia_texto = number_format($meta_dia,0,".",".");
    		echo "<td class='boton'><div align='right'>$ $meta_dia_texto</div></td>";
			$contexto.="<td class='boton'><div align='right'>$ ".$meta_dia_texto."</div></td>";
			
			$cum_acumulado = $total_ventas * 100 / $dbdp->proyeccion_individual;
			$cum_acumulado_texto = number_format($cum_acumulado,0,".",".");
			echo "<td class='boton'><div align='center'>$cum_acumulado_texto %</div></td>";
			$contexto.="<td class='boton'><div align='center'>".$cum_acumulado_texto." %</div></td>";
			
			$cum_proyectado = $total_ventas * 100 / $meta_dia;
			$cum_proyectado_texto = number_format($cum_proyectado,0,".",".");
			echo "<td class='boton'><div align='center'>$cum_proyectado_texto %</div></td>";
			$contexto.="<td class='boton'><div align='center'>".$cum_proyectado_texto." %</div></td>";
  			echo "</tr>";
			$contexto.="</tr>";
		}
	}
}//FIN DEL FOR VENDEDORES
?>
  <tr>
    <td width="14%" class="boton"><div align="center">TOTAL EMPRESA</div></td>
    <? $total_mes_texto = number_format($total_mes,0,".","."); ?>
    <td width="14%" class="boton"><div align="right">$ <?=$total_mes_texto?></div></td>
    <? $total_venta_diaria_texto = number_format($total_venta_diaria,0,".","."); ?>
    <td width="14%" class="boton"><div align="right">$ <?=$total_venta_diaria_texto?></div></td>
    <? $total_acumulado_texto = number_format($total_acumulado,0,".","."); ?>
    <td width="14%" class="boton"><div align="right">$ <?=$total_acumulado_texto?></div></td>
    <? $total_meta_dia_texto = number_format($total_meta_dia,0,".","."); ?>
    <td width="14%" class="boton"><div align="right">$ <?=$total_meta_dia_texto?></div></td>
    <? $total_cum_acumulado = $total_acumulado * 100 / $total_mes; ?>
    <? $total_cum_acumulado_texto = round($total_cum_acumulado);?>
    <td width="14%" class="boton"><div align="center"><?=$total_cum_acumulado_texto?> %</div></td>
    <? $total_cum_proyectado = $total_acumulado * 100 / $total_meta_dia; ?>
    <? $total_cum_proyectado_texto = round($total_cum_proyectado);?>
    <td width="16%" class="boton"><div align="center"><?=$total_cum_proyectado_texto?> %</div></div></td>
  </tr>
</table>
<?
	$contexto.="<tr>
    <td width='14%' class='boton'><div align='center'>TOTAL EMPRESA</div></td>
    <td width='14%' class='boton'><div align='right'>$ ".$total_mes_texto."</div></td>
    <td width='14%' class='boton'><div align='right'>$ ".$total_venta_diaria_texto."</div></td>
    <td width='14%' class='boton'><div align='right'>$ ".$total_acumulado_texto."</div></td>
    <td width='14%' class='boton'><div align='right'>$ ".$total_meta_dia_texto."</div></td>
    <td width='14%' class='boton'><div align='center'>".$total_cum_acumulado_texto." %</div></td>
    <td width='16%' class='boton'><div align='center'>".$total_cum_proyectado_texto." %</div></div></td>
  	</tr>
	</table>"
?>
</br>
  <form method="POST" action="inf_proyeccion.php" name="enviar_correo">
<textarea name="contexto" cols="30" rows="4" style="display:none"><?=$contexto?></textarea>
    <tr>
      <td align="center" colspan="8">
          <tr>
            <td><input type="hidden"  value="0" name="guardar_todo" id="guardar_todo"/></td>
          </tr>
       </td>
    </tr>
  </form>
<table width="200" border="0" align="center">
  <tr>
    <td><div align="center" class="tituloproductos">
      <input name="button" type="button" class="botones"  onclick="guardar_correo()" value="Enviar" />
      <input name="button3" type="button" class="botones"  onclick="window.print()" value="Imprimir" />
      <input type="hidden"  value="0" name="guardar_todo" id="guardar_todo"/>
    </div></td>
  </tr>
</table>
<script language="javascript">
function guardar_correo(){
	document.getElementById('guardar_todo').value=1
	document.enviar_correo.submit();
	alert('Se ha enviado el informe');
	window.close();
}
</script>
</body>
</html>