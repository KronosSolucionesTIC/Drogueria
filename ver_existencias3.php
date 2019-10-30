<?
include "lib/sesion.php";
include("lib/database.php");


$where_bodegas= " WHERE  ( ";

for ($ii=1 ;  $ii <= $val_inicial + 1 ; $ii++) 
{
	
	if(!empty($_POST["codigo_".$ii]) || $_POST["codigo_".$ii]=="0" ) { 
		$where_bodegas.= "   kardex.cod_bod_kar=".$_POST["codigo_".$ii]."  or ";
		$ultimo_bodega=$_POST["codigo_".$ii];
	}

}

$where_bodegas.="  kardex.cod_bod_kar= $ultimo_bodega ) ";

$where_bodegas.="  and  kardex.cant_ref_kar>0  ";

?>
<script language="javascript">
function imprimir(){
	document.getElementById('imp').style.display="none";
	document.getElementById('cer').style.display="none";
	window.print();
}


</script>
 <link href="styles.css" rel="stylesheet" type="text/css" />
 <link href="../informes/styles.css" rel="stylesheet" type="text/css" />
 <style type="text/css">
<!--
.Estilo1 {font-size: 9px}
.Estilo2 {font-size: 12 }
-->
 </style>
 <link href="../css/styles.css" rel="stylesheet" type="text/css" />
 <link href="../css/stylesforms.css" rel="stylesheet" type="text/css" />
 <title><?=$nombre_aplicacion?> -- SALDOS DE BODEGA --</title>
 <link href="informes/styles1.css" rel="stylesheet" type="text/css" />	
		<TABLE width="100%" border="1">
		
			<INPUT type="hidden" name="mapa" value="<?=$mapa?>">
			<INPUT type="hidden" name="id" value="<?=$id?>">

			  	<tr>
			  		<td><div align="center" class="Estilo2">EXISTENCIAS A LA FECHA <? echo date('d-M-Y');?></div></td>
			    </tr>
				<?
				$total=0;
				$sql = "SELECT cant_ref_kar,cod_bod_kar,cod_mar,nom_mar FROM kardex	
					INNER JOIN bodega ON cod_bod_kar = cod_bod 
					INNER JOIN producto ON kardex.cod_ref_kar=producto.cod_pro
					INNER JOIN referencia ON referencia.cod_ref = producto.nom_pro
					INNER JOIN marca ON producto.cod_mar_pro = marca.cod_mar 
					$where_bodegas
					GROUP BY marca.cod_mar ORDER BY nom_mar";					
					
					$db->query($sql);
					$estilo="formsleo";
					while($db->next_row()){ 
						echo "<br>\n";	
			  			echo "<table border='1'>";
						echo "<tr>";
						echo "<td width='500' class='Estilo2'>";
						echo $db->nom_mar; 
						echo "</td>";
						$dbt = new Database();
						if(($db->cod_mar == 30)or($db->cod_mar == 1021)or($db->cod_mar == 1024)){
							$orden = 'nom_pes';
						}
						else{
							$orden = 'cod_talla';
						}
						$sqlt = "SELECT cant_ref_kar,cod_bod_kar,cod_pes,nom_pes FROM kardex	
						INNER JOIN bodega ON cod_bod_kar = cod_bod 
						INNER JOIN producto ON kardex.cod_ref_kar=producto.cod_pro
						INNER JOIN referencia ON referencia.cod_ref = producto.nom_pro
						INNER JOIN marca ON producto.cod_mar_pro = marca.cod_mar 
						INNER JOIN peso ON peso.cod_pes = kardex.cod_talla
						$where_bodegas AND cod_mar = '$db->cod_mar'
						GROUP BY cod_talla ORDER BY $orden";
						$dbt->query($sqlt);
						while($dbt->next_row()){
							echo "<td width='10' class='Estilo2'>";
							echo $dbt->nom_pes;
							echo "</td>";
						}		
						echo "</tr>";
							$dbr = new Database();
							$sqlr = "SELECT cant_ref_kar,cod_bod_kar,cod_pro,desc_ref FROM kardex	
							INNER JOIN bodega ON cod_bod_kar = cod_bod 
							INNER JOIN producto ON kardex.cod_ref_kar=producto.cod_pro
							INNER JOIN referencia ON referencia.cod_ref = producto.nom_pro
							INNER JOIN marca ON producto.cod_mar_pro = marca.cod_mar 
							INNER JOIN peso ON peso.cod_pes = kardex.cod_talla
							$where_bodegas AND cod_mar = '$db->cod_mar'
							GROUP BY cod_ref ORDER BY desc_ref";
							$dbr->query($sqlr);
							while($dbr->next_row()){
								echo "<tr>";
								echo "<td width='100' class='Estilo2'>";
								echo $dbr->desc_ref;
								echo "</td>";
								$dbc = new Database();
								$sqlc = "SELECT cant_ref_kar,cod_bod_kar,cod_pes,nom_pes FROM kardex	
								INNER JOIN bodega ON cod_bod_kar = cod_bod 
								INNER JOIN producto ON kardex.cod_ref_kar=producto.cod_pro
								INNER JOIN referencia ON referencia.cod_ref = producto.nom_pro
								INNER JOIN marca ON producto.cod_mar_pro = marca.cod_mar 
								INNER JOIN peso ON peso.cod_pes = kardex.cod_talla
								$where_bodegas AND cod_mar = '$db->cod_mar'
								GROUP BY cod_talla ORDER BY cod_talla";
								$dbc->query($sqlc);
								while($dbc->next_row()){
									$dbsum = new Database();
									$sqlsum = "SELECT SUM(`cant_ref_kar`) AS cantidad FROM `kardex`
									$where_bodegas AND cod_talla = '$dbc->cod_pes' AND cod_ref_kar = '$dbr->cod_pro'";
									$dbsum->query($sqlsum);
									$dbsum->next_row();
									echo "<td width='20' class='Estilo2'>";
									echo $dbsum->cantidad;
									echo "</td>";
								}
								echo "</tr>";
							}
						echo "</table>";
				  	} 

				 
				 ?>


 
	
	<TR>
	  <TD colspan="3" align="center"><div align="center">
	    <input name="button" type="button"  class="botones1" id="imp" onClick="imprimir()" value="Imprimr">
	    <input name="button" type="button"  class="botones1"  id="cer" onClick="window.close()" value="Cerrar">
	    </div></TD>
	</TR>

	<TR>
		<TD width="1%" background="images/bordefondo.jpg" style="background-repeat:repeat-y" rowspan="2"></TD>
		<TD bgcolor="#F4F4F4" class="pag_actual">&nbsp;</TD>
		<TD width="1%" background="images/bordefondo.jpg" style="background-repeat:repeat-y" rowspan="2"></TD>
	</TR>
	<TR>
	  <TD align="center">
	