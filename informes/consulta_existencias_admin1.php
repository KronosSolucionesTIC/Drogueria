<? include("../lib/database.php");?>
<link href="styles.css" rel="stylesheet" type="text/css" />
<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<title>EXISTENCIAS EN BODEGA</title>
<?
$gran_total_bodega = 0;
//echo "lista precios".$combo_lista;
$bodegas = split("\,",$arreglo_bodegas);
//print_r($bodegas);
for($g=0; $g<= count($bodegas); $g++)
{  
	 $codBod = $bodegas[$g]; 
	 if ($codBod!="") { 

		$db1 = new Database();
		$sql = " select nom_bod from bodega where cod_bod=".$codBod;
		$db1->query($sql);
		while($db1->next_row()){ //inicio query de bodegas
		
		    $total_bodega = 0;
			$nombre=$db1->nom_bod;
?>
<TABLE width="99%" border="1" align="center" cellpadding="2" cellspacing="1"   class="textoproductos1">
  <TR>
    <TD colspan="6" class="ctablasup"><div align="center">NOMBRE BODEGA:
        <?=$nombre?>
      </div></TD>
  </TR>
  <TR>
    <TD width="10%"  class="subtitulosproductos">CODIGO</TD>
    <TD width="34%" class="subtitulosproductos">REFERENCIA</TD>
    <TD width="17%"  class="subtitulosproductos">CANTIDAD </TD>
    <TD width="12%"  class="subtitulosproductos">TALLA </TD>
    <TD width="14%"  class="subtitulosproductos">VALOR UNIDAD</TD>
    <TD width="13%" class="subtitulosproductos">VALOR TOTAL</TD>
  </TR>
  <?
		$db = new Database();	
		if($codBod>=0){
			$sql = "SELECT 				  
				  kardex.cant_ref_kar,
				  producto.cod_fry_pro,
				  producto.cod_pro,
				  producto.nom_pro,
				  det_lista.pre_list,
				  peso.nom_pes,  
				  (kardex.cant_ref_kar * det_lista.pre_list) AS total_producto
				  
				  FROM kardex
				  LEFT JOIN producto ON (kardex.cod_ref_kar = producto.cod_pro)
				  LEFT JOIN bodega ON (kardex.cod_bod_kar = bodega.cod_bod)
				  LEFT JOIN det_lista ON (producto.cod_pro = det_lista.cod_pro)
				  LEFT JOIN peso ON (kardex.cod_talla = peso.cod_pes)
				  LEFT JOIN listaprecio ON (det_lista.cod_lista = listaprecio.cos_list) 
				  WHERE kardex.cod_bod_kar=$codBod and listaprecio.cos_list=$combo_lista"; 
  
			$db->query($sql);
			$total_bodega=0;
			while($db->next_row()){
			    $total_bodega+=$db->total_producto;
				echo "<TR><TD class='textoproductos1' >$db->cod_fry_pro</TD>";
				echo "<TD class='textoproductos1' >$db->nom_pro</TD>";
				echo "<TD class='textoproductos1' >$db->cant_ref_kar</TD>";
				echo "<TD class='textoproductos1' >$db->nom_pes</TD>";
				//TOTAL DE COSTO
				$prod = $db->cod_pro;
				$db2 = new database();
				$sql2 = "SELECT SUM(cos_dent) as tot_cos FROM d_entrada
				WHERE cod_ref_dent = $prod";
				$db2->query($sql2);
				$db2->next_row();

				//CANTIDAD DE REGISTROS
				$db3 = new database();
				$sql3 = "SELECT count(*) as cant FROM d_entrada
				WHERE cod_ref_dent = $prod";
				$db3->query($sql3);
				$db3->next_row();

				//PROMEDIO DE COSTO
				$tot_uni = $db2->tot_cos / $db3->cant;

				//VALOR DE COSTO * CANTIDADES
				$total_producto = $tot_uni * $db->cant_ref_kar;

				echo "<TD class='textoproductos1' >".number_format($tot_uni,0,".",".")."</TD>";
				echo "<TD class='textoproductos1' >".number_format($total_producto,0,".",".")."</TD>";
				echo "</TR>";
			}
		}
	?>
  <TR>
    <TD colspan="4" align="center" class="tituloproductos">&nbsp;</TD>
    <TD align="center" class="tituloproductos"><div align="left" class="textotabla01"><b>Total de la Bodega:</b> </div></TD>
    <TD align="center" class="tituloproductos"><div align="left" class="textotabla01"><b>$
        <?=number_format($total_bodega,0,".",".")  ?>
        </b> </div></TD>
  </TR>
  <?        
         $gran_total_bodega+=$total_bodega;
		
		 } //fin query de bodegas ?>
</TABLE>
<?	 }  
} //fin del for?>
<table width="99%" height="25"  border="1" align="center" cellpadding="2" cellspacing="0"   class="textoproductos1">
  <tr>
    <td width="854" class="textotabla01"><div align="right"><b> Total: </b></div></td>
    <td width="125"><span class="textotabla1"><b>$
      <?=number_format($gran_total_bodega,0,".",".")?>
      </b> </span></td>
  </tr>
</table>
<p><br/>
</p>
<div align="center">
  <input name="button" type="button" class="botones"  onclick="window.print()" value="Imprimir" />
  <input name="button" type="button" class="botones"  onclick="window.close()" value="Cerrar" />
</div>