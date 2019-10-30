<? include("../lib/database.php"); ?>
<? include("../js/funciones.php"); ?>

<link href="../styles.css" rel="stylesheet" type="text/css" />
<link href="../css/stylesforms.css" rel="stylesheet" type="text/css" />
<title>Consulta Rotacion</title>
<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<span class="textotabla01">
<?=$k_futuro?>
</span>
<table width="842" align="center">
  <tr>
    <td width="834" height="21"><table width="830" align="center">
      <tr>
        <td width="822" height="21" colspan="8"><div align="center" class="subfongris">SEMAFORO</div></td>
      </tr>
      
    </table></td>
  </tr>
</table>

  <?  
  
 
  
 if ($fec_fin==0) {

	  $fecha = " m_factura.fecha>='$fec_ini'";

}

else{


	$fecha = " m_factura.fecha>='$fec_ini' AND m_factura.fecha<='$fec_fin'";

}
 
if ($categoria == 0) {
	$cat = "";
}
else{
	$cat = "AND cod_mar = $categoria";
}
if ($tipo_producto == 0) {
	$tipo = "";
}
else{
	$tipo = "AND d_factura.cod_tpro = $tipo_producto";
}
 ?>


  <script>
function abrir(){
	window.print();
}
  </script>

<table width="833" border="1" align="center"class="textoproductos1">
  
  <tr>
    <td width="408%" colspan="6" class="boton"><?
	


 
function n_dias($fec_ini,$fec_fin)
{
$dias= (strtotime($fec_ini)-strtotime($fec_fin))/86400;
$dias = abs($dias); $dias = floor($dias);
return  $dias;
}


$diferencia=n_dias($fec_ini,$fec_fin);
	
  $sqlr="SELECT  

 
  m_factura.cod_fac,
  m_factura.cod_cli,
  m_factura.cod_bod,
  m_factura.fecha,
  d_factura.cod_mfac,
  d_factura.cod_tpro,
  d_factura.cod_cat,
  d_factura.cod_pro,
  d_factura.cod_dfac,
  d_factura.cod_peso,
  d_factura.cant_pro,
  producto.cod_pro,
  tipo_producto.cod_tpro,
  marca.cod_mar,
  peso.cod_pes,
  peso.nom_pes,
  marca.nom_mar,
  producto.cod_fry_pro,
  referencia.desc_ref,
  producto.s_cant_mc,
  producto.s_cant_ma,
  tipo_producto.nom_tpro,
  bodega.cod_bod AS bodega,
  bodega.nom_bod AS nombre_bodega,
  kardex.cod_kar,
  kardex.cod_ref_kar,
  kardex.cant_ref_kar
  

FROM

   m_factura  
  INNER JOIN d_factura ON (m_factura.cod_fac = d_factura.cod_mfac)
  INNER JOIN producto ON (d_factura.cod_pro = producto.cod_pro)
  INNER JOIN referencia ON (referencia.cod_ref = producto.nom_pro)
  INNER JOIN peso ON (d_factura.cod_peso = peso.cod_pes)
  INNER JOIN marca ON (d_factura.cod_cat = marca.cod_mar)
  INNER JOIN tipo_producto ON (d_factura.cod_tpro = tipo_producto.cod_tpro) 
  INNER JOIN bodega ON (m_factura.cod_bod = bodega.cod_bod)
  INNER JOIN kardex ON (producto.cod_pro = kardex.cod_ref_kar)

  WHERE $fecha $cat $tipo AND cod_bod_kar = 225 GROUP BY d_factura.cod_pro"; 
 

$dbr= new  Database();
$dbr->query($sqlr);
$i==1;
$a=1;
echo $cod_pro;
	
 while ($dbr->next_row()) {
	 ?>
      <table width="823" border="0" align="center">
        <tr>
          <td colspan="10" class="subfongris"><?=$dbr->nom_pro?></td>
        </tr>
        <tr>
          <td colspan="3" class="subfongris">FECHA INICIAL </td>
          <td class="subfongris"><?=$fec_ini?></td>
          <td colspan="2" class="subfongris">FECHA FINAL </td>
          <td colspan="4" class="subfongris"><?=$fec_fin?></td>
        </tr>
        <tr>
          <td width="73" class="subfongris">REFERENCIA</td>
          <td width="47" class="subfongris">CODIGO</td>
          <td width="39" class="subfongris">TALLA</td>
          <td class="subfongris">CANTIDAD EXISTENTE </td>
          <td class="subfongris">DIAS ANALIS </td>
          <td class="subfongris">T DESPACHO </td>
          <td class="subfongris">CANTIDAD VENDIDA </td>
          <td class="subfongris">PROM POR DIA  </td>
          <td class="subfongris">Rojo</td>
          <td class="subfongris">Verde</td>
        </tr>
        <!--/************** COLOR VERDE************************/-->
        <?    $sqlt="SELECT desc_ref,cod_fry_pro,nom_pes, sum(d_factura.cant_pro) AS cant_pro , (select  sum(cant_ref_kar)  from kardex where cod_ref_kar=$dbr->cod_pro and  cod_talla=d_factura.cod_peso AND cod_bod_kar = 225 GROUP BY cod_fry_pro
 ) as cant_ref_kar
		
		FROM d_factura
			INNER JOIN m_factura ON (m_factura.cod_fac = d_factura.cod_mfac)
			INNER JOIN producto ON (d_factura.cod_pro = producto.cod_pro)
			INNER JOIN referencia ON (referencia.cod_ref = producto.nom_pro)
			INNER JOIN tipo_producto ON (d_factura.cod_tpro = tipo_producto.cod_tpro) 
			INNER JOIN peso ON (d_factura.cod_peso = peso.cod_pes)
			WHERE d_factura.cod_pro=$dbr->cod_pro AND $fecha GROUP BY cod_peso,cod_fry_pro order by desc_ref"; 
			
	
			
			$dbt= new  Database();
			$dbt->query($sqlt);
			 
			
			//  kardex ON (producto.cod_pro = kardex.cod_ref_kar)
		?>
        <? while ($dbt->next_row()) { 
			 $bandera=0;
			 $cant_tengo=$dbt->cant_ref_kar;
			//  $cant_tengo=1;
			 $cant_alerta=$dbt->s_cant_ma;
			 $codigo=$dbt->cod_fry_pro;
			
			
			 $prom1=0;	
			 $prom1=$dbt->cant_pro/$diferencia;
		     $v_futuro=number_format($prom1,0,".",".")*$t_despacho;
			 $v_futuro_30=number_format($prom1,0,".",".")*30;
			 $v_futuro_60=number_format($prom1,0,".",".")*60;			 
			 $v_futuro_90=number_format($prom1,0,".",".")*90;
		     
			
			 $prom_ver=$v_futuro+$v_futuro_30;
				
			 if ($dbt->cant_ref_kar>$prom_ver) {
			
			 ?>
        <tr >
          <td class="textotabla01"><?=$dbt->desc_ref?>          </td>
          <td class="textotabla01"><div align="center">
              <?=$dbt->cod_fry_pro?>
          </div></td>
          <td class="textotabla01"><div align="center">
              <?=$dbt->nom_pes?>
          </div></td>
          <td bgcolor="#009900" class="textotabla01"><div align="center">
            <?=$dbt->cant_ref_kar?>
          </div></td>
          <td class="textotabla01"><?=$diferencia?></td>
          <td class="textotabla01"><?=$t_despacho?></td>
          <td  class="textotabla01"><?=$dbt->cant_pro?></td>
          <td  class="textotabla01"><?=number_format($prom1,2,".",".")  ?></td>
          <td  class="textotabla01"><?=$v_futuro?></td>
          <td  class="textotabla01"><?=$prom_ver?></td>
        </tr>
        <?  
		
		

		
		
		} } ?>
		
		
        <!--/************** COLOR AMARILLO************************/-->
        <? $sqlt="SELECT desc_ref,cod_fry_pro,nom_pes, sum(d_factura.cant_pro) AS cant_pro , (select  sum(cant_ref_kar)  from kardex where cod_ref_kar=$dbr->cod_pro and  cod_talla=d_factura.cod_peso AND cod_bod_kar = 225 GROUP BY cod_fry_pro
 ) as cant_ref_kar
		
		FROM d_factura
			INNER JOIN m_factura ON (m_factura.cod_fac = d_factura.cod_mfac)
			INNER JOIN producto ON (d_factura.cod_pro = producto.cod_pro)
			INNER JOIN referencia ON (referencia.cod_ref = producto.nom_pro)
			INNER JOIN tipo_producto ON (d_factura.cod_tpro = tipo_producto.cod_tpro) 
			INNER JOIN peso ON (d_factura.cod_peso = peso.cod_pes)
			WHERE d_factura.cod_pro=$dbr->cod_pro and $fecha GROUP BY cod_peso,cod_fry_pro order by desc_ref";  
			
			$dbt= new  Database();
			$dbt->query($sqlt);
			
			
			?>
        <? while ($dbt->next_row()) {
				
				   $cant_tengo=$dbt->cant_ref_kar;
				
				   $prom2=0;  
				   $prom2=$dbt->cant_pro/$diferencia;
		           $v_futuro=number_format($prom2,0,".",".")*$t_despacho;		 
				   $v_futuro_30=number_format($prom2,0,".",".")*30;		   
			       $v_futuro_60=number_format($prom2,0,".",".")*60;	
			       $v_futuro_90=number_format($prom2,0,".",".")*90;
		           $prom_ver=$v_futuro+$v_futuro_30;
			
			 if ($dbt->cant_ref_kar>$v_futuro and $dbt->cant_ref_kar<=$prom_ver) {		   
			
			?>
        <tr>
          <td class="textotabla01"><?=$dbt->desc_ref?> </td>
          <td class="textotabla01"><div align="center">
              <?=$dbt->cod_fry_pro?>
          </div></td>
          <td class="textotabla01"><div align="center">
              <?=$dbt->nom_pes?>
          </div></td>
          <td width="90" bgcolor="#FFFF99"class="textotabla01"><div align="center">
            <?=$dbt->cant_ref_kar?>
          </div></td>
          <td width="58"class="textotabla01"><?=$diferencia?></td>
          <td width="72"class="textotabla01"><?=$t_despacho?></td>
          <td width="83" class="textotabla01"><?=$dbt->cant_pro?></td>
          <td width="59" class="textotabla01"><?=number_format($prom2,0,".",".")  ?></td>
          <td width="20" class="textotabla01"><?=$v_futuro?></td>
          <td width="22" class="textotabla01"><?=$prom_ver?></td>
        </tr>
        <?  
		

		
		} }?>
		
		
        <!--/************** COLOR ROJO************************/-->
        <?  $sqlt="SELECT desc_ref,cod_fry_pro,nom_pes, sum(d_factura.cant_pro) AS cant_pro , (select  sum(cant_ref_kar)  from kardex where cod_ref_kar=$dbr->cod_pro and  cod_talla=d_factura.cod_peso AND cod_bod_kar = 225 GROUP BY cod_fry_pro
 ) as cant_ref_kar
		
		FROM d_factura
			INNER JOIN m_factura ON (m_factura.cod_fac = d_factura.cod_mfac)
			INNER JOIN producto ON (d_factura.cod_pro = producto.cod_pro)
			INNER JOIN referencia ON (referencia.cod_ref = producto.nom_pro)
			INNER JOIN tipo_producto ON (d_factura.cod_tpro = tipo_producto.cod_tpro) 
			INNER JOIN peso ON (d_factura.cod_peso = peso.cod_pes)
			WHERE d_factura.cod_pro=$dbr->cod_pro and $fecha GROUP BY cod_peso,cod_fry_pro order by desc_ref"; 
			
			
			
			$dbt= new  Database();
			$dbt->query($sqlt);
			
		?>
        <? while ($dbt->next_row()) {
				
				 $cant_tengo=$dbt->cant_ref_kar;		 
				 $prom3=0; 
				 $prom3=$dbt->cant_pro/$diferencia;
		         $v_futuro=number_format($prom3,0,".",".")*$t_despacho;
		   		 $v_futuro_30=number_format($prom3,0,".",".")*30;
				 $v_futuro_60=number_format($prom3,0,".",".")*60;
            	 $v_futuro_90=number_format($prom3,0,".",".")*90;
             	$prom_ver=$v_futuro+$v_futuro_30;
		
				 if ($dbt->cant_ref_kar<=$v_futuro)
				 
				  {?>
        <tr >
          <td class="textotabla01"><?=$dbt->desc_ref?>          </td>
          <td class="textotabla01"><div align="center">
              <?=$dbt->cod_fry_pro?>
          </div></td>
          <td class="textotabla01"><div align="center">
              <?=$dbt->nom_pes?>
          </div></td>
          <td width="90" bgcolor="#CC0033"class="textotabla01"><div align="center">
            <?  if ($dbt->cant_ref_kar==NULL) { echo "0"; } else {?>   <?=$dbt->cant_ref_kar?> <? }?>
          </div></td>
          <td width="58"class="textotabla01"><?=$diferencia?></td>
          <td width="72"class="textotabla01"><?=$t_despacho?></td>
          <td width="83" class="textotabla01"><?=$dbt->cant_pro?></td>
          <td width="59" class="textotabla01"><?=number_format($prom3,0,".",".")  ?></td>
          <td width="20" class="textotabla01"><?=$v_futuro?></td>
          <td width="22" class="textotabla01"><?=$prom_ver?></td>
        </tr>
        <?  
		
		} }?>
      </table>
    <br /><? }?>	</td>
  </tr>
</table>
</br>
<script>
function abrir(){
	window.print();
}
</script>
<input type="hidden" name="fechas" id="fechas" value="<?=$fechas?>" />
<input type="hidden" name="fechas2" id="fechas2" value="<?=$fechas2?>" />
</form>
<!--FIN SELECCION PROVEEDOR Y FECHA-->
<!--SELECCION FECHA-->
<script>
function abrir(){
	window.print();
}
</script>

<input type="hidden" name="fechas" id="fechas" value="<?=$fechas?>" />
<input type="hidden" name="fechas2" id="fechas2" value="<?=$fechas2?>" />
<input type="hidden" name="referencia" id="referencia" value="<?=$referencia?>" />
</form>
<table width="200" border="0" align="center">
  <tr>
    <td><div align="center"><span class="tituloproductos">
      <input name="button2" type="button" class="botones"  onclick="abrir()" value="Imprimir" />
    </span></div></td>
  </tr>
</table>
