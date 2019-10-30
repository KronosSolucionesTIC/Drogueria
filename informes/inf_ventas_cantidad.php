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
          <td colspan="6" class="subfongris">FECHA INCIAL
            <?=$fec_fin?>
            FECHA FINAL
          <?=$fec_fin?></td>
        </tr>
        <tr>
          <td width="73" class="subfongris">CATEGORIA</td>
          <td width="73" class="subfongris">TIPO PRODUCTO</td>
          <td width="73" class="subfongris">REFERENCIA</td>
          <td width="47" class="subfongris">CODIGO</td>
          <td width="39" class="subfongris">TALLA</td>
          <td width="72" class="subfongris">VENDIDOS</td>
        </tr>
        <!--/************** COLOR VERDE************************/-->
        <?    
		$sqlp="SELECT nom_mar,nom_tpro,desc_ref,cod_fry_pro,nom_pes,cod_pes,cod_pro FROM atributo
		INNER JOIN producto ON producto.cod_pro = atributo.cod_referencia
		INNER JOIN marca ON marca.cod_mar = producto.cod_mar_pro
		INNER JOIN tipo_producto ON tipo_producto.cod_tpro = producto.cod_tpro_pro
		INNER JOIN referencia ON referencia.cod_ref = producto.nom_pro
		INNER JOIN peso ON peso.cod_pes = atributo.cod_peso
		WHERE producto.estado_producto = 1 $cat $tipo ORDER BY cod_fry_pro,cod_pes ASC";		
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
			$sqlf="SELECT SUM(cant_pro) AS total_facturados FROM d_factura
			INNER JOIN m_factura ON m_factura.cod_fac = d_factura.cod_mfac
			WHERE cod_pro = '$dbp->cod_pro' AND cod_peso = '$dbp->cod_pes' AND m_factura.fecha<='$fec_fin' AND m_factura.fecha>='$fec_ini' AND estado IS NULL";		
			$dbf= new  Database();
			$dbf->query($sqlf);
			$dbf->next_row();
		?>
          <td class="textotabla01"><?=$dbf->total_facturados?></td>
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
