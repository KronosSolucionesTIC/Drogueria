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
    <td width="730" height="21"><div align="center" class="subfongris">ROTACI&Oacute;N GENERAL </div></td>
  </tr>
</table>

<?  
 if ($fechas2==0) {

	  $fecha = "AND m_factura.fecha>='$fechas' group by  d_factura.cod_pro, d_factura.cod_peso , m_factura.fecha ,m_factura.cod_bod";

}

else{


	$fecha = "AND m_factura.fecha>='$fechas' AND m_factura.fecha<='$fechas2' group by  d_factura.cod_pro, d_factura.cod_peso , m_factura.fecha ,m_factura.cod_bod";

}
 $arreglo_referencia."<br>";

 $arreglo_talla."<br>";
  $aaa = split("\,",$arreglo_referencia);
  $bbb = split("\,",$arreglo_talla);
  
for($g=0; $g<=$val_inicial; $g++)
{  
  $ttt = $aaa[$g]; 


if($ttt!="") {  

   /*   for($j=0; $j<=$val_inicial2; $j++)
      {  
       $tall = $bbb[$j]; 
      if($tall!="") {  */
 
 $sqls="SELECT nom_pro, cod_pro,cod_fry_pro
	FROM
		producto
	WHERE cod_pro=$ttt";
$dbs= new  Database();
$dbs->query($sqls);
$dbs->next_row();
$nombre=$dbs->nom_pro;
$codigo_ref=$dbs->cod_fry_pro;


 

 
  
 ?>
<table width="725" border="1" align="center" cellpadding="2" cellspacing="1"  bgcolor="#CCCCCC" class="textoproductos1">
  <tr>
    <td colspan="7" ><div align="center"></div></td>
  </tr>
  <tr>
    <td colspan="2" class="subfongris"><div align="left"><strong><span class="Estilo1">PERIODO</span></strong></div></td>
    <td class="subfongris"><div align="left"><strong><span class="Estilo1">FECHA INICIAL </span></strong>:
      <?=$fechas?>
    </div></td>
    <td colspan="2" class="subfongris"><div align="left">FECHA FINAL
      <?=$fechas2?>
</div></td>
  </tr>
  <tr>
    <td colspan="2" class="subfongris"><div align="left">Codigo</div></td>
    <td colspan="3" class="subfongris"><div align="left"><?=$codigo_ref?>
    </div></td>
  </tr>
  <tr>
    <td colspan="2" class="subfongris"><div align="left">Referencia</div></td>
    <td colspan="3" class="subfongris"><div align="left">
      <?=$nombre?>
    </div></td>
  </tr>
  <tr>
    <td width="19%" class="subfongris">CANTIDAD</td>
    <td width="20%" class="subfongris">FECHA</td>
    <td width="24%" class="subfongris">TALLA</td>
    <td width="19%" class="subfongris">CATEGORIA</td>
    <td width="18%" class="subfongris">BODEGA</td>
  </tr>
  <?			
	
  $sql = " Select
  m_factura.cod_fac,
  m_factura.cod_usu,
  m_factura.cod_cli,
  m_factura.cod_bod,
  m_factura.fecha,
  m_factura.num_fac,
  m_factura.cod_razon_fac,
  m_factura.tipo_pago,
  m_factura.tot_fac,
  m_factura.tot_dev_mfac,
  m_factura.bod_cli_fac,
  d_factura.cod_cat,
  d_factura.cod_peso,
  d_factura.cod_pro,
  sum(d_factura.cant_pro)as cant_pro ,
  d_factura.cod_mfac,
  d_factura.cod_dfac,
  producto.cod_pro,
  producto.nom_pro,
  producto.cod_fry_pro,
  tipo_producto.cod_tpro,
  tipo_producto.nom_tpro,
  d_factura.cod_tpro,
  marca.cod_mar,
  marca.nom_mar,
  peso.cod_pes,
  peso.nom_pes,
  usuario.cod_usu,
  usuario.nom_usu,
  bodega1.cod_bod,
  bodega1.nom_bod,
  bodega.cod_bod,
  bodega.nom_bod as bodega,
  rsocial.cod_rso,
  rsocial.nom_rso
  
FROM  m_factura 
  INNER JOIN d_factura ON (m_factura.cod_fac = d_factura.cod_mfac)
  INNER JOIN producto ON (d_factura.cod_pro = producto.cod_pro)
  INNER JOIN tipo_producto ON (d_factura.cod_tpro = tipo_producto.cod_tpro)
  INNER JOIN marca ON (d_factura.cod_cat = marca.cod_mar)
  INNER JOIN peso ON (d_factura.cod_peso = peso.cod_pes)
  INNER JOIN usuario ON (m_factura.cod_usu = usuario.cod_usu)
  INNER JOIN bodega1 ON (m_factura.cod_cli = bodega1.cod_bod)
  INNER JOIN bodega ON (m_factura.cod_bod = bodega.cod_bod)
  INNER JOIN rsocial ON (m_factura.cod_razon_fac = rsocial.cod_rso ) where producto.cod_pro='$ttt' $fecha ORDER BY fecha DESC ";


		$db->query($sql);
		$total_cantidad=0;
		while($db->next_row()){
			
		$total_cantidad=$total_cantidad+$db->cant_pro;	
			
			echo "<FORM action='agr_traslado.php' method='POST'> ";

			echo "<TR><TD class='txtablas' width='10%'>$db->cant_pro</TD>";
						
			echo "<TD class='txtablas' align='center' width='15%'>$db->fecha</TD>";
				
			echo "<TD class='txtablas' align='center' width='15%'>$db->nom_pes</TD>";
			
			echo "<TD class='txtablas' align='center' width='15%'>$db->nom_mar</TD>";
			
			echo "<TD class='txtablas' align='center' width='15%'>$db->bodega</TD>";

			
			
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
    <td width="562" class="textotabla01"><div align="right">TOTAL CANTIDAD </div></td>
    <td width="150"><span class="textotabla1">$
        <span class="textotabla01">
        <?=number_format($total_cantidad,0,".",".")?>
    </span>    </span></td>
  </tr>
</table><br/>



  <?  $gran_total_referencia=$gran_total_referencia+$total_cantidad; } }  ?>
  
  <table width="726"  border="1" align="center" cellpadding="2" cellspacing="0"   class="textoproductos1">
  <tr>
    <td width="562" class="textotabla01"><div align="right"> Total rotaci&oacute;n referencias </div></td>
    <td width="150"><span class="textotabla1">$
      <?=number_format($gran_total_referencia,0,".",".")?>
    </span></td>
  </tr>
</table>
<br/>

  <div align="center">
    <input type="hidden" name="mapa" value="<?=$mapa?>" />
    <input name="button" type="button" class="botones" onclick="window.print()" value="Imprimir" />
    <input name="button" type="button" class="botones" onclick="window.close()" value="Cerrar" />
    
    
  </div>
