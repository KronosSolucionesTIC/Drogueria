<link href="../css/styles.css" rel="stylesheet" type="text/css" />
 <link href="../css/styles1.css" rel="stylesheet" type="text/css" />
 <link href="../css/styles2.css" rel="stylesheet" type="text/css" />

<style type="text/css">
<!--
.Estilo12 {
	font-size: 14px;
	font-family: "Lucida Console";
}
-->
</style>
<table width="300" border="0">
  <tr>
    <td width="23%"><span class="Estilo12">Vendido a : </span></td>
    <td width="77%"><span class="Estilo12">
      <?
		  	$db_cliente = new Database();
			$db_fecha = new Database();
			$sql ='select * from bodega1 
			INNER JOIN ciudad ON (ciudad.cod_ciudad = bodega1.ciu_bod)
			where cod_bod = '.$codigo_salida;
			$db_cliente->query($sql);
			if($db_cliente->next_row()){ 
				
				$sql_fecha="select date_add('$fac_fecha',INTERVAL $db_cliente->dias_credito DAY ) as fecha_vencimineto_factura";
				$db_fecha->query($sql_fecha);
				if($db_fecha->next_row()){ 
					$fecha_vencimineto_factura=$db_fecha->fecha_vencimineto_factura;
				}	
				
			?>
      <? $nombre = $db_cliente->nom_bod.$db_cliente->apel_bod?>
      <? echo $nombre; ?> </span></td>
  </tr>
  <tr>
    <td><span class="Estilo12">Nit/C.C.:</span></td>
    <td><span class="Estilo12"><? echo $db_cliente->iden_bod; ?></span></td>
  </tr>
  <tr>
    <td><span class="Estilo12">Direccion:</span></td>
    <td><span class="Estilo12"><? echo $db_cliente->dir_bod;?></span></td>
  </tr>
  <tr>
    <td><span class="Estilo12">Telefono</span></td>
    <td><span class="Estilo12"><? echo $db_cliente->tel_bod;?></span></td>
  </tr>
  <tr>
    <td><span class="Estilo12">Ciudad</span></td>
    <td class="textoproductos1"><span class="Estilo12"><? echo $db_cliente->desc_ciudad;?></span></td>
    <? } ?>
  </tr>
</table>
