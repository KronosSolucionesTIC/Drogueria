<?
include "lib/sesion.php";
include("lib/database.php");

$where_cli="";
$sql="select distinct punto_venta.cod_bod as valor , nom_bod as nombre from punto_venta  inner join  bodega  on punto_venta.cod_bod=bodega.cod_bod where cod_ven=$global[2]";
		$dbdatos= new  Database();
		$dbdatos->query($sql);	
		$where_cli="";
		while($dbdatos->next_row())
		{
			$where_cli .= "bodega.cod_bod= ".$dbdatos->valor  ;
			$where_cli .= " or ";
			$cod = $dbdatos->valor ;
		}		
		$where_cli .= " bodega.cod_bod < 0 ";

$db = new Database();
$db_ver = new Database();
$sql = "SELECT  *  FROM bodega  WHERE $where_cli";
$db_ver->query($sql);	
if($db_ver->next_row()){ 
	$bodega=$db_ver->nom_bod;

}

?>
<script language="javascript">
function imprimir(){
	document.getElementById('imp').style.display="none";
	document.getElementById('cer').style.display="none";
	window.print();
}


</script>
 <link href="informes/styles.css" rel="stylesheet" type="text/css" />
 <link href="styles.css" rel="stylesheet" type="text/css" />
 <style type="text/css">
<!--
.Estilo1 {font-size: 9px}
.Estilo2 {font-size: 9 }
-->
 </style>
 <link href="css/styles.css" rel="stylesheet" type="text/css" />
 <link href="css/stylesforms.css" rel="stylesheet" type="text/css" />
 <title><?=$nombre_aplicacion?> -- SALDOS DE BODEGA --</title>
<TABLE width="100%" border="0" cellspacing="0" cellpadding="0"   >
	
	<TR>
		<TD align="center">
		<TABLE width="98%" border="1" cellpadding="0" cellspacing="0" bordercolor="#999999" >
		
			<INPUT type="hidden" name="mapa" value="<?=$mapa?>">
			<INPUT type="hidden" name="id" value="<?=$id?>">

			<TR>
			  <TD width="100%" class='txtablas'>
			  <table width="100%" border="1" cellpadding="1" cellspacing="0" bordercolor="#333333">
			  	<tr>
			  		<td><div align="center"><span class="textoproductos1">&nbsp;&nbsp;Bodega:<span class="textotabla01">
		  		    <?=$bodega?>
		  		    </span></span><span class="textoproductos1">&nbsp;&nbsp;</span></div></td>
			    </tr>
				</table>					</TD>
		  </TR>
			
			
			<TR>
			  <TD align="center">
			  <table width="100%" border="1" cellpadding="1" cellspacing="1" bordercolor="#333333" id="select_tablas" >
                
				  <tr >
            <td  class="botones1">Categoria </td>
            <td  class="botones1">Tipo Producto</td>
            
			<td  class="botones1">Referencia</td>
            <td  class="botones1">Codigo</td>
			<td   class="botones1">Talla</td>
            <td   class="botones1">Cantidad</td>
			</tr>
				<?
				$total=0;
				$sql = " SELECT * FROM kardex	
					INNER JOIN producto ON kardex.cod_ref_kar=producto.cod_pro
					INNER JOIN referencia ON referencia.cod_ref = producto.nom_pro
					LEFT JOIN 	tipo_producto ON producto.cod_tpro_pro = tipo_producto.cod_tpro 
					LEFT JOIN  marca ON producto.cod_mar_pro = marca.cod_mar
					LEFT JOIN peso ON kardex.cod_talla = peso.cod_pes 
					WHERE kardex.cod_bod_kar=$cod and kardex.cant_ref_kar>0 order by nom_pro, cod_talla  ";
					$db->query($sql);
					$estilo="formsleo";
					while($db->next_row()){ 	
						
				?>
                <tr id="fila_0"  >
				
                  <td  class="textotabla01"><?=$db->nom_mar?></td>
				  <td  class="textotabla01"><?=$db->nom_tpro?></td>
                  
                  <td  class="textotabla01"><?=$db->desc_ref?></td>
                  <td  class="textotabla01"><?=$db->cod_fry_pro?></td>
				  <td colspan="1" class="textotabla01"><?=$db->nom_pes?></td>
				   <td class="textotabla01"><div align="right"><?=number_format($db->cant_ref_kar,0,".",".")?></div></td>
			    </tr>
				  
				<?
	
				  } 
				 
				 ?>
				 
				  <tr >
			  
                  <td colspan="6" >&nbsp;</td>
				  </tr>
              </table></TD>
		  </TR>
			<TR>
			  <TD align="center">             </TD>
		  </TR>
			<TR>
			  <TD align="center"><p></TD>
		  </TR>
</TABLE>

 
<TABLE width="70%" border="0" cellspacing="0" cellpadding="0">
	
	<TR><TD colspan="3" align="center"><input name="button" type="button"  class="botones1" id="imp" onClick="imprimir()" value="Imprimr">
        <input name="button" type="button"  class="botones1"  id="cer" onClick="window.close()" value="Cerrar"></TD>
	</TR>

	<TR>
		<TD width="1%" background="informes/images/bordefondo.jpg" style="background-repeat:repeat-y" rowspan="2"></TD>
		<TD bgcolor="#F4F4F4" class="pag_actual">&nbsp;</TD>
		<TD width="1%" background="informes/images/bordefondo.jpg" style="background-repeat:repeat-y" rowspan="2"></TD>
	</TR>
	<TR>
	  <TD align="center">
	