<?
include "../lib/sesion.php";
include("../lib/database.php");
include("../conf/clave.php");		

$sql="select distinct punto_venta.cod_bod as valor , nom_bod as nombre from punto_venta  inner join  bodega  on punto_venta.cod_bod=bodega.cod_bod where cod_ven=$usu";
		$dbdatos= new  Database();
		$dbdatos->query($sql);	
		while($dbdatos->next_row())
		{
			$cod = $dbdatos->valor;
		}		
?>
<script language="javascript">
function imprimir(){
	document.getElementById('imp').style.display="none";
	document.getElementById('cer').style.display="none";
	window.print();
}


</script>


<title>FACTURA DE VENTA</title>
<style type="text/css">
<!--
.Estilo4 {font-size: 16px}
.Estilo127 {font-family: Arial, Helvetica, sans-serif; font-size: 14px; }
.Estilo129 {font-family: Arial, Helvetica, sans-serif; font-size: 10px; }
.Estilo25 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
.Estilo28 {font-size: 10px}
.Estilo30 {font-family: Arial, Helvetica, sans-serif; font-size: 8px; }
.Estilo12 {
	font-size: 12px;
	font-family: "Lucida Console";
}
.Estilo121 {	font-size: 14px;
	font-family: "Lucida Console";
}

-->
</style>
<? 
			$dbent = new Database();
  			$sqlent = "SELECT * FROM m_traslado
			WHERE (fec_tras >= '$fec_ini' AND fec_tras <= '$fec_fin') AND cod_bod_ent_tras = $cod";
  			$dbent->query($sqlent);
			while($dbent->next_row()){
			$total_ent += $dbent->cantidad;
?>
<TABLE border="0" cellpadding="2" cellspacing="0"  width="306" <?=$anulacion?> >
		
			<INPUT type="hidden" name="mapa" value="<?=$mapa?>">
			<INPUT type="hidden" name="id" value="<?=$id?>">
<? 
$dbb = new Database();
$sqlbb = "SELECT nom_bod FROM bodega
WHERE cod_bod = $dbent->cod_bod_sal_tras";
$dbb->query($sqlbb);
$dbb->next_row();
$bodega = $dbb->nom_bod;
?>
			<TR>
			  <TD align="center">
                <div align="center">*************************************</div>
			    <div align="center" class="Estilo12"><strong>FECHA:<?=$dbent->fec_tras?> BODEGA:<?=$bodega?></strong></div>
                <div align="center">*************************************</div>
              <table width="250" border="0">
			    <tr>

			      <td><div align="center"><strong><span class="Estilo12">PRODUCTO </span></strong></div></td>
			      <td><div align="center"><strong><span class="Estilo12">CANTIDAD</span></strong></div></td>
			      </tr>
<? 
$dbp = new Database();
$sqlp = "SELECT * FROM d_traslado
INNER JOIN producto ON producto.cod_pro = d_traslado.cod_ref_dtra
INNER JOIN referencia ON referencia.cod_ref = producto.nom_pro
WHERE cod_mtras_dtra = $dbent->cod_tras";
$dbp->query($sqlp);
while($dbp->next_row()){
?>
			    <tr>
			      <td><div align="left"><span class="Estilo12"><?=$dbp->desc_ref?></span></div></td>
			      <td><div align="right"><span class="Estilo12"><?=$dbp->cant_dtra?></span></div></td>
			      </tr>
<? 
} 
?>
		      </table>
		        <div align="center">*************************************</div>
	          <p>&nbsp;</p></TD>
		  </TR>
</TABLE>
<? } ?>
