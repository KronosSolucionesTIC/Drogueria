<?
include "lib/sesion.php";
include("lib/database.php");


$where_bodegas= " WHERE  ( ";

for ($ii=1 ;  $ii <= $val_inicial + 1 ; $ii++) 
{
	
	if(!empty($_POST["codigo_".$ii]) || $_POST["codigo_".$ii]=="0" ) { //
		//echo $_POST["codigo_".$ii];
		$where_bodegas.= "   kardex.cod_bod_kar=".$_POST["codigo_".$ii]."  or ";
		$ultimo_bodega=$_POST["codigo_".$ii];
		//if($ii>1)
		//	$where_bodegas.=  " or  ";
	}

}

$where_bodegas.="  kardex.cod_bod_kar= $ultimo_bodega ) ";

$where_bodegas.="  and  kardex.cant_ref_kar>0  ";


	
	if(!empty($checkbox_referencia)  &&  !empty($checkbox_talla) )
		$group_by .= " group by  cod_bod_kar , nom_pro, cod_talla    ";
	
	if(empty($checkbox_referencia)  &&  !empty($checkbox_talla) )
		$group_by .= " group by   cod_talla  , nom_pro";
		
	if(!empty($checkbox_referencia)  &&  empty($checkbox_talla) )
		$group_by .= " group by   cod_bod_kar , nom_pro    ";	
		
	if(empty($checkbox_referencia)  &&  empty($checkbox_talla) )
		$group_by .= " group by   cod_bod_kar    ";		
		
		
		




//  nom_pro, cod_talla  , cod_bod_kar




if(!empty($checkbox_referencia) && !empty($checkbox_talla) ) {
	$group_by .= " , ";
	$suma =" sum(cant_ref_kar)  as total_ref"; 
}
else {
	$suma =" sum(cant_ref_kar)  as total_ref";
}



if(!empty($checkbox_talla))
	$group_by.= " cod_talla    ";
	
	




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
.Estilo2 {font-size: 9 }
-->
 </style>
 <link href="../css/styles.css" rel="stylesheet" type="text/css" />
 <link href="../css/stylesforms.css" rel="stylesheet" type="text/css" />
 <title><?=$nombre_aplicacion?> -- SALDOS DE BODEGA --</title>
 <link href="informes/styles1.css" rel="stylesheet" type="text/css" />
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
			  		<td width="14%">&nbsp;</td>
				    <td class="ctablaform"><div align="center"><span class="tituloproductos">Informe &nbsp;&nbsp;Bodega</span></div></td>
		  	    </tr>
				</table>					</TD>
		  </TR>
			
			
			<TR>
			  <TD align="center">
			  <table width="100%" border="1" cellpadding="1" cellspacing="1" bordercolor="#333333" id="select_tablas" >
                
				  <tr >
            <td  class="subtitulosproductos">Bodega </td>
		    <td  class="subtitulosproductos">Categoria </td>
            <td  class="subtitulosproductos">Tipo Producto</td>
            
			<td  class="subtitulosproductos">Referencia</td>
            <td  class="subtitulosproductos">Codigo</td>
			
			
			<td   class="subtitulosproductos">Talla</td>
			
			
            <td   class="subtitulosproductos">Cantidad</td>
			</tr>
				<?
				$total=0;
				$sql = " SELECT *, $suma FROM kardex	
					INNER JOIN bodega ON cod_bod_kar = cod_bod 
					INNER JOIN producto ON kardex.cod_ref_kar=producto.cod_pro
					INNER JOIN referencia ON referencia.cod_ref = producto.nom_pro
					LEFT JOIN 	tipo_producto ON producto.cod_tpro_pro = tipo_producto.cod_tpro 
					LEFT JOIN  marca ON producto.cod_mar_pro = marca.cod_mar
					LEFT JOIN peso ON kardex.cod_talla = peso.cod_pes 
					$where_bodegas   $group_by  order by cod_ref_kar , cod_bod  ";
					//  nom_pro, cod_talla  , cod_bod_kar
					
					
					$db->query($sql);
					$estilo="formsleo";
					while($db->next_row()){ 	
						
						
					if(!empty($checkbox_referencia)  &&  !empty($checkbox_talla) ) 
					{ ?>
						<tr id="fila_0"  >
						 <td  class="Estilo2"><?=$db->nom_bod?></td>
						  <td  class="Estilo2"><?=$db->nom_mar?></td>
						  <td  class="Estilo1"><?=$db->nom_tpro?></td>
						  <td  class="Estilo1"><?=$db->desc_ref?></td>
						  <td  class="Estilo1"><?=$db->cod_fry_pro?></td>				  
						  <td colspan="1" class="Estilo1"><?=$db->nom_pes?></td>
						   <td class="Estilo1"><div align="right"><?=number_format($db->total_ref,0,".",".")?></div></td>
						</tr>	
					
					<? }
					
					if(empty($checkbox_referencia)  &&  !empty($checkbox_talla) )
					{ ?>
						<tr id="fila_0"  >
						 <td  class="Estilo2"><?=$db->nom_bod?></td>
						  <td  class="Estilo2"><?=$db->nom_mar?></td>
						  <td  class="Estilo1"><?=$db->nom_tpro?></td>
						  <td  class="Estilo1"><?=$db->nom_pro?></td>
						  <td  class="Estilo1"><?=$db->cod_fry_pro?></td>				  
						  <td colspan="1" class="Estilo1">&nbsp;</td>
						   <td class="Estilo1"><div align="right"><?=number_format($db->total_ref,0,".",".")?></div></td>
						</tr>	
					
					<? }
				
					
					if(!empty($checkbox_referencia)  &&  empty($checkbox_talla) )
					{ ?>
						<tr id="fila_0"  >
						 <td  class="Estilo2"><?=$db->nom_bod?></td>
						  <td  class="Estilo2"><?=$db->nom_mar?></td>
						  <td  class="Estilo1"><?=$db->nom_tpro?></td>
						  <td  class="Estilo1"><?=$db->nom_pro?></td>
						  <td  class="Estilo1"><?=$db->cod_fry_pro?></td>				  
						  <td colspan="1" class="Estilo1">&nbsp;</td>
						   <td class="Estilo1"><div align="right"><?=number_format($db->total_ref,0,".",".")?></div></td>
						</tr>	
					
					<? }

					
					if(empty($checkbox_referencia)  &&  empty($checkbox_talla) )
					{ ?>
						<tr id="fila_0"  >
						 <td  class="Estilo2"><?=$db->nom_bod?></td>
						  <td  class="Estilo2">&nbsp;</td>
						  <td  class="Estilo1">&nbsp;</td>
						  <td  class="Estilo1">&nbsp;</td>
						  <td  class="Estilo1">&nbsp;</td>				  
						  <td colspan="1" class="Estilo1">&nbsp;</td>
						   <td class="Estilo1"><div align="right"><?=number_format($db->total_ref,0,".",".")?></div></td>
						</tr>	
					
					<? }
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
		<TD width="1%" background="images/bordefondo.jpg" style="background-repeat:repeat-y" rowspan="2"></TD>
		<TD bgcolor="#F4F4F4" class="pag_actual">&nbsp;</TD>
		<TD width="1%" background="images/bordefondo.jpg" style="background-repeat:repeat-y" rowspan="2"></TD>
	</TR>
	<TR>
	  <TD align="center">
	