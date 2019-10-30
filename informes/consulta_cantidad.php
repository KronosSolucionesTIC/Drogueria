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
        <td width="822" height="21" colspan="8"><div align="center" class="subfongris">INFORME DE VENTAS X CANTIDADES</div></td>
      </tr>
      
    </table></td>
  </tr>
</table>

  <?  
  
 
  
 
if ($categoria == 0) {
	$cat = "";
}
else{
	$cat = "AND cod_mar_pro = $categoria";
}
if ($tipo_producto == 0) {
	$tipo = "";
}
else{
	$tipo = "AND cod_tpro = $tipo_producto";
}
 ?>


  <script>
function abrir(){
	window.print();
}
  </script>

<table width="833" border="1" align="center"class="textoproductos1">
  
  <tr>
    <td width="408%" colspan="6" class="boton"><table width="823" border="0" align="center">
        <tr>
          <td colspan="10" class="subfongris">FECHA FINAL            <?=$fec_fin?></td>
        </tr>
        <tr>
          <td width="73" class="subfongris">CATEGORIA</td>
          <td width="73" class="subfongris">TIPO PRODUCTO</td>
          <td width="73" class="subfongris">REFERENCIA</td>
          <td width="47" class="subfongris">CODIGO</td>
          <td width="39" class="subfongris">TALLA</td>
          <td width="58" class="subfongris">ENTRADAS </td>
          <td width="72" class="subfongris">FACTURADOS</td>
          <td width="83" class="subfongris">ACTUAL</td>
          <td width="59" class="subfongris">SISTEMA</td>
          <td width="90" class="subfongris">DIFERENCIA</td>
        </tr>
        <!--/************** COLOR VERDE************************/-->
        <?    
		$sqlp="SELECT * FROM atributo
		INNER JOIN producto ON producto.cod_pro = atributo.cod_referencia
		INNER JOIN marca ON marca.cod_mar = producto.cod_mar_pro
		INNER JOIN tipo_producto ON tipo_producto.cod_tpro = producto.cod_tpro_pro
		INNER JOIN referencia ON referencia.cod_ref = producto.nom_pro
		INNER JOIN peso ON peso.cod_pes = atributo.cod_peso
		WHERE producto.estado_producto = 1 $cat $tipo";		
		$dbp= new  Database();
		$dbp->query($sqlp);
		?>
        <? while ($dbp->next_row()) {			
			 ?>
        <tr >
          <td class="textotabla01"><?=$dbp->nom_mar?></td>
          <td class="textotabla01"><?=$dbp->nom_tpro?></td>
          <td class="textotabla01"><?=$dbp->desc_ref?>          </td>
          <td class="textotabla01"><div align="center">
              <?=$dbp->cod_fry_pro?>
          </div></td>
          <td class="textotabla01"><div align="center">
              <?=$dbp->nom_pes?>
          </div></td>
       	<?    
			$sqle="SELECT SUM(cant_dent) AS total_entrada FROM d_entrada
			INNER JOIN m_entrada ON m_entrada.cod_ment = d_entrada.cod_ment_dent
			WHERE cod_ref_dent = '$dbp->cod_pro' AND cod_pes_dent = '$dbp->cod_pes' AND fec_ment<='$fec_fin'";		
			$dbe= new  Database();
			$dbe->query($sqle);
			$dbe->next_row();
		?>
          <td class="textotabla01"><?=$dbe->total_entrada?></td>
        <?    
			$sqlf="SELECT SUM(cant_pro) AS total_facturados FROM d_factura
			INNER JOIN m_factura ON m_factura.cod_fac = d_factura.cod_mfac
			WHERE cod_pro = '$dbp->cod_pro' AND cod_peso = '$dbp->cod_pes' AND m_factura.fecha<='$fec_fin' AND estado IS NULL";		
			$dbf= new  Database();
			$dbf->query($sqlf);
			$dbf->next_row();
		?>
          <td class="textotabla01"><?=$dbf->total_facturados?></td>
        <?
			$actual = $dbe->total_entrada-$dbf->total_facturados;
		?>
          <td  class="textotabla01"><?=$actual?></td>
        <?    
			$sqls="SELECT SUM(cant_ref_kar) AS total_sistema FROM kardex
			WHERE cod_ref_kar = '$dbp->cod_pro' AND cod_talla = '$dbp->cod_pes'";		
			$dbs= new  Database();
			$dbs->query($sqls);
			$dbs->next_row();
		?>
          <td  class="textotabla01"><?=$dbs->total_sistema?></td>
        <?
			$diferencia = $dbs->total_sistema-$actual;
		?>
        <?
			if($diferencia == 0){
		?>
          <td bgcolor="#009900" class="textotabla01"><div align="center">
            <?=$diferencia?>
          </div></td>
        </tr>
        <?   } ?>
       	<?
			if($diferencia < 0){
		?>
           <td bgcolor="#CC0033" class="textotabla01"><div align="center">
            <?=$diferencia?>
          </div></td>
        </tr>
        <?   } ?>
               	<?
			if($diferencia > 0){
		?>
           <td bgcolor="#FFFF99" class="textotabla01"><div align="center">
            <?=$diferencia?>
          </div></td>
        </tr>
        <?   } ?>
        <?   } ?> 
      </table>
    <br /></td>
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
