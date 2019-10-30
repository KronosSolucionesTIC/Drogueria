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

<TABLE border="0" cellpadding="2" cellspacing="0"  width="306" <?=$anulacion?> >
		
			<INPUT type="hidden" name="mapa" value="<?=$mapa?>">
			<INPUT type="hidden" name="id" value="<?=$id?>">
			<TR>
			  <TD align="center"><div align="center"></div>
<table width="250" border="0">
	    <tr>
			      <td><div align="center"><strong><span class="Estilo12">PRODUCTO </span></strong></div></td>
			      <td><div align="center"><strong><span class="Estilo12">CANTIDAD</span></strong></div></td>
			      <td><div align="center"><strong><span class="Estilo12">VALOR</span></strong></div></td>
			      <td><div align="center"><strong><span class="Estilo12">TOTAL</span></strong></div></td>
			      </tr>
			    <tr>
<? 
			$db = new Database();
  			$sql = "SELECT val_uni,desc_ref,SUM(cant_pro) as cantidad FROM `m_factura`
			INNER JOIN d_factura ON d_factura.cod_mfac = m_factura.cod_fac 
			INNER JOIN producto ON producto.cod_pro = d_factura.cod_pro
			INNER JOIN referencia ON referencia.cod_ref  = producto.nom_pro
			WHERE (`fecha`>='$fec_ini' AND `fecha` <='$fec_fin')AND cod_bod = $cod AND (producto.cod_pro = 783 or producto.cod_pro = 1555 OR producto.cod_pro = 1556) AND estado is NULL 
			GROUP BY producto.cod_pro
			HAVING cantidad > 0";
  			$db->query($sql);
			while($db->next_row()){
			$total = $db->cantidad * $db->val_uni;
			$gran = $gran + $total;
			$valor = number_format($db->val_uni,0,",",".");
			$valor_total = number_format($total,0,",",".");
			$gran_total = number_format($gran,0,",",".");
?>
			      <td><div align="left"><span class="Estilo12">
			        <?=$db->desc_ref?>
		          </span></div></td>
			      <td><div align="right"><span class="Estilo12">
			        <?=$db->cantidad?>
		          </span></div></td>
			      <td><div align="right"><span class="Estilo12">
			        <?=$valor?>
		          </span></div></td>
			      <td><div align="right"><span class="Estilo12">
			        <?=$valor_total?>
		          </span></div></td>
        </tr>
<? 
} 
?>
			    <tr>
			      <td colspan="3">&nbsp;</td>
			      <td>&nbsp;</td>
        </tr>
			    <tr>
			      <td colspan="3"><strong><div align="right"><span class="Estilo12">TOTAL</span></div></strong></td>
			      <td><div align="right"><span class="Estilo12">
			        <?=$gran_total?>
		          </span></div></td>
			      </tr>
		      </table>
		        <div align="center"></div>
	          <p>&nbsp;</p></TD>
		  </TR>
</TABLE>
