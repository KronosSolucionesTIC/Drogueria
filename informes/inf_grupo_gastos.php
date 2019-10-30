<? include("../lib/database.php"); ?>

<link href="../styles.css" rel="stylesheet" type="text/css" />
<link href="../css/stylesforms.css" rel="stylesheet" type="text/css" />
<title>Consulta Rotacion</title>
<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo1 {color: #FFFFFF}
-->
</style>
<table width="738" align="center">
  <tr>
    <td width="730" height="21"><div align="center" class="subfongris">INFORME GRUPO GASTOS </div></td>
  </tr>
</table>

<?  
 if ($fechas2==0) {

	  $fecha = "AND resumen_gastos.fec_res>='$fechas' group by tipo_gas";

}

else{


	$fecha = "AND resumen_gastos.fec_res>='$fechas' AND resumen_gastos.fec_res<='$fechas2' group by tipo_gas";

}
 
  $aaa = split("\,",$arreglo_gastos);
 for($g=0; $g<=$val_inicial; $g++)
{  
  $ttt = $aaa[$g]; 


if($ttt!="") {  


$sqls = " select * 
from grupo_gastos 
where cod_gru='$ttt'";
$dbs = new Database();
$dbs->query($sqls);
while($dbs->next_row()){

$nombre=$dbs->nom_gru;
} 
  
  
 
  
 ?>
<table width="725" border="1" align="center" cellpadding="2" cellspacing="1"  bgcolor="#CCCCCC" class="textoproductos1">
  <tr>
    <td colspan="7" ><div align="center"></div></td>
  </tr>
  <tr>
    <td class="subfongris"><div align="left"><strong><span class="Estilo1">PERIODO</span></strong></div></td>
    <td class="subfongris"><div align="left">
      <?=$fechas?>
    </div></td>
    <td class="subfongris"><div align="left">FECHA FINAL </div></td>
    <td colspan="2" class="subfongris"><div align="left">
      <?=$fechas2?>
    </div></td>
  </tr>
  <tr>
    <td class="subfongris"><div align="left">GASTO</div></td>
    <td colspan="4" class="subfongris"><div align="left">
      <?=$nombre?>
    </div>      <div align="left"></div></td>
  </tr>
  
  <tr>
    <td width="15%" class="subfongris">TIPO</td>
    <td width="15%" class="subfongris">USUARIO</td>
    <td width="15%" class="subfongris">BODEGA</td>
    <td width="14%" class="subfongris">VALOR</td>
    <td width="22%" class="subfongris">OBSERVACIONES</td>
  </tr>
  <?			
	
	
	 $sql = " SELECT * ,sum(val_res) as total
	 
   
FROM
  resumen_gastos
  INNER JOIN tipo_gastos ON (resumen_gastos.tipo_gas = tipo_gastos.cod_gas)
  INNER JOIN grupo_gastos ON (tipo_gastos.cod_gru_gas = grupo_gastos.cod_gru)
  INNER JOIN bodega ON (resumen_gastos.pun_res = bodega.cod_bod)
  INNER JOIN usuario ON (resumen_gastos.resp_res = usuario.cod_usu)where
  $where   grupo_gastos.cod_gru='$ttt' $fecha ORDER BY resumen_gastos.fec_res DESC ";
	

		$db->query($sql);
		$total_gastos=0;
		while($db->next_row()){
		
		$total_gastos=	$total_gastos +$db->total;	
			
			echo "<FORM action='agr_traslado.php' method='POST'> ";

			echo "<TR><TD class='txtablas' width='10%'>$db->nom_gas</TD>";
						
				
			echo "<TD class='txtablas' align='center' width='15%'>$db->nom_usu</TD>";
			
			echo "<TD class='txtablas' align='center' width='15%'>$db->nom_bod</TD>";
			
			echo "<TD class='txtablas' align='center' width='15%'>".number_format($db->total,0,".",".")."</TD>";
			
			echo "<TD class='txtablas' align='center' width='15%'>$db->obs_res</TD>";

			
			
			echo "</TR></FORM>";
		}
	?>
  <script>
function ver_documento(codigo,mapa)
{
	 window.open("ver_factura.php?codigo="+codigo,"ventana","menubar=0,resizable=1,width=700,height=400,toolbar=0,scrollbars=yes")
	// window.open("ver_traslado.php?codigo="+codigo,"ventana") 
} 
</script>
  <form method="post" action="../agr_prin_factura.php">
  </form>
</table>
<table width="726"  border="1" align="center" cellpadding="2" cellspacing="0"   class="textoproductos1">
  <tr>
    <td width="520" class="textotabla01"><div align="right">TOTAL</div></td>
    <td width="192"><span class="textotabla1">$
        <span class="textotabla01">
        <?=number_format($total_gastos,0,".",".")?>
    </span>    </span></td>
  </tr>
</table><br/>



  <?  $gran_total_gastos=$gran_total_gastos+$total_gastos; } }  ?>
  
  <table width="726"  border="1" align="center" cellpadding="2" cellspacing="0"   class="textoproductos1">
  <tr>
    <td width="520" class="textotabla01"><div align="right"> TOTAL GASTOS </div></td>
    <td width="192"><span class="textotabla1">$
      <?=number_format($gran_total_gastos,0,".",".")?>
    </span></td>
  </tr>
</table>
<br/>

  <div align="center">
    <input type="hidden" name="mapa" value="<?=$mapa?>" />
    <input name="button" type="button" class="botones" onclick="window.print()" value="Imprimir" />
    <input name="button" type="button" class="botones" onclick="window.close()" value="Cerrar" />
    
    
  </div>
