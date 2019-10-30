<? include("../lib/database.php"); ?>

<link href="../styles.css" rel="stylesheet" type="text/css" />
<link href="../css/stylesforms.css" rel="stylesheet" type="text/css" />
<title>Consulta Rotacion</title>
<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo1 {color: #FFFFFF}
-->
</style>
<table width="695" align="center">
  <tr>
    <td width="802" height="21"><div align="center" class="subfongris">INFORME MOVIMIENTOS </div></td>
  </tr>
</table>

<p>
  <?  
 if ($fechas2==0) {

	  $fecha = "AND m_factura.fecha>='$fechas' ";
	  $fecha_entrada = "AND m_entrada.fec_ment>='$fechas' ";
	  $fecha_traslado = "AND m_traslado.fec_tras>='$fechas' ";
	  $fecha_devolucion = "AND m_devolucion.fec_dev>='$fechas' ";



}

else{


	$fecha = "AND m_factura.fecha>='$fechas' AND m_factura.fecha<='$fechas2' ";
	$fecha_entrada = "AND m_entrada.fec_ment>='$fechas' AND m_entrada.fec_ment<='$fechas2' ";
	$fecha_traslado = "AND m_traslado.fec_tras>='$fechas' AND m_traslado.fec_tras<='$fechas2' ";
	$fecha_devolucion = "AND m_devolucion.fec_dev>='$fechas' AND m_devolucion.fec_dev<='$fechas2' ";

}

$arreglo_referencia."<br>";

 $arreglo_talla."<br>";
  $aaa = split("\,",$arreglo_referencia);
  $bbb = split("\,",$arreglo_talla);
  
for($g=0; $g<=$val_inicial; $g++)
{  
  $ttt = $aaa[$g]; 


if($ttt!="") { 



 $sqls="SELECT nom_pro, cod_pro,cod_fry_pro,cod_tpro_pro
	FROM
		producto WHERE cod_pro=$ttt";
$dbs= new  Database();
$dbs->query($sqls);

while ($dbs->next_row()) {
$nombre=$dbs->nom_pro;
$codigo_ref=$dbs->cod_fry_pro;  ?>
</p>

<div id='ref_<?=$dbs->cod_pro?>'>
<table width="695" border="1" align="center" cellpadding="2" cellspacing="1"  bgcolor="#CCCCCC" class="textoproductos1">
  <tr>
    <td width="11%" class="subfongris"><div align="left"><span class="Estilo1 textotabla01"><strong><span class="Estilo1">PERIODO</span></strong></span></div></td>
    <td width="17%" class="subfongris"><div align="left"><span class="Estilo1 textotabla01"><strong><span class="Estilo1">FECHA INICIAL </span></strong></span></div></td>
    <td width="25%" class="subfongris"><div align="left">
      <?=$fechas?>
    </div>
    </td>
    <td width="13%" class="subfongris"><div align="left"><span class="textotabla01 Estilo1">FECHA FINAL </span></div></td>
    <td width="34%" class="subfongris"><div align="left">
      <?=$fechas2?>
    </div>
    </td>
  </tr>
  <tr>
    <td class="subfongris"><div align="left"><span class="Estilo1 textotabla01"><strong><span class="Estilo1">CODIGO</span></strong></span></div></td>
    <td colspan="4" class="subfongris"><div align="left">
      <?=$codigo_ref?>
    </span></div></td>
  </tr>
  <tr>
    <td class="subfongris"><div align="left"><span class="Estilo1 textotabla01"><strong><span class="Estilo1">REFERENCIA</span></strong></span></div></td>
    <td colspan="4" class="subfongris"><div align="left">
      <?=$nombre?>
    </span></div></td>
  </tr>
  
  <tr>
    <td colspan="6" class="boton">
        <table width="531" border="0" align="center">
          
          <tr>
            <td width="121" class="subfongris">FECHA</td>
            <td width="127" class="subfongris">TIPO DE MOVIMIENTO</td>
            <td width="55" class="subfongris">TALLA</td>
            <td width="60" class="subfongris">CANTIDAD</td>
            <td width="146" class="subfongris">RESPONSABLE</td>
          </tr>
		  
		  <!--/************** movimiento factura************************/-->
		
			<?  $sqlt="SELECT *
			
					FROM
					  m_factura
					  INNER JOIN d_factura ON (m_factura.cod_fac = d_factura.cod_mfac)
					  INNER JOIN producto ON (d_factura.cod_pro = producto.cod_pro)
					  INNER JOIN tipo_producto ON (d_factura.cod_tpro = tipo_producto.cod_tpro)
					  INNER JOIN peso ON (d_factura.cod_peso = peso.cod_pes)
                      INNER JOIN usuario ON (m_factura.cod_usu = usuario.cod_usu) 
					 								
					WHERE producto.cod_pro='$ttt' and d_factura.cant_pro>0 $fecha   order by  fecha,  cod_pes desc  "; 
			
			$dbt= new  Database();
			$dbt->query($sqlt);
			$bandera=0;
			$tot1=0;
			
		?>
			 <? while ($dbt->next_row()) { ?> 	
		  
          <tr>
             <td class="textotabla01"><div align="center"><?=$dbt->fecha?></div></td>
             <td class="textotabla01"><div align="center">FACTURA</div></td>
             <td class="textotabla01"><div align="center"><?=$dbt->nom_pes?></div></td>
             <td class="textotabla01"><div align="center">
               <?=$dbt->cant_pro?>
             </div></td>
             <td class="textotabla01"><?=$dbt->nom_usu?></td>
          </tr>
		   
			 <? $tot1+=$dbt->cant_pro;  }   ?>
			 
			
			<!--/************** MOVIMIENTO ENTRADA ************************/-->
			
			<? $sqlt="SELECT *
			FROM
  m_entrada
  INNER JOIN d_entrada ON (m_entrada.cod_ment = d_entrada.cod_ment_dent)
  INNER JOIN tipo_producto ON (d_entrada.cod_tpro_dent = tipo_producto.cod_tpro)
  INNER JOIN peso ON (d_entrada.cod_pes_dent = peso.cod_pes)
  INNER JOIN producto ON (tipo_producto.cod_tpro = producto.cod_tpro_pro)
  INNER JOIN usuario ON (m_entrada.usu_ment = usuario.cod_usu) 

  
  
    		WHERE producto.cod_pro=$dbs->cod_pro and d_entrada.cant_dent>0 $fecha_entrada  order by fec_ment,  cod_pes desc"; 
			
			$dbt= new  Database();
			$dbt->query($sqlt);
			$tot2=0;
			
			
			?>
				<? while ($dbt->next_row()) { ?> 	
			           
          <tr>
             <td class="textotabla01"><div align="center"><?=$dbt->fec_ment?></div></td>
             <td class="textotabla01"><div align="center">ENTRADA</div></td>
             <td class="textotabla01"><div align="center"><?=$dbt->nom_pes?></div></td>
             <td class="textotabla01"><div align="center">
               <?=$dbt->cant_dent?>
             </div></td>
		     <td class="textotabla01"><?=$dbt->nom_usu?></td>
          </tr>
		  
		  <?  $tot2+=$dbt->cant_dent;} ?>
		 	
		  
		   <!--/************** MOVIMIENTO TRASLADO ************************/-->
			 
			<?  $sqlt=" SELECT *
			
			 FROM m_traslado
             INNER JOIN d_traslado ON (m_traslado.cod_tras = d_traslado.cod_mtras_dtra)
             INNER JOIN tipo_producto ON (d_traslado.cod_tpro_dtra = tipo_producto.cod_tpro)
             INNER JOIN producto ON (tipo_producto.cod_tpro = producto.cod_tpro_pro)
             INNER JOIN peso ON (d_traslado.cod_pes_dtra = peso.cod_pes)
			 INNER JOIN usuario ON (m_traslado.cod_usu_tras = usuario.cod_usu)
 
			 
			WHERE  producto.cod_pro=$dbs->cod_pro and d_traslado.cant_dtra>0 $fecha_traslado   order by fec_tras , cod_pes desc"; 
			
			$dbt= new  Database();
			$dbt->query($sqlt);
			$tot3=0;

		 while ($dbt->next_row()) {?> 	
          <tr>
            <td class="textotabla01"><div align="center"><?=$dbt->fec_tras?></div></td>
            <td class="textotabla01"><div align="center">TRASLADO</div></td>
            <td class="textotabla01"><div align="center"><?=$dbt->nom_pes?></div></td>
            <td class="textotabla01"><div align="center">
              <?=$dbt->cant_dtra?>
            </div></td>
			<td class="textotabla01"><?=$dbt->nom_usu?></td>
          </tr>
			
			<? $tot3+=$dbt->cant_dtra; }?>
			
			 <!--/************** MOVIMIENTO DEVOLUCION ************************/-->
			 
			<?  $sqlt=" SELECT *
			
		
			  FROM
			  m_devolucion
			  INNER JOIN d_devolucion ON (m_devolucion.cod_dev = d_devolucion.cod_mdev)
			  INNER JOIN producto ON (d_devolucion.cod_prod_ddev = producto.cod_pro)
			  INNER JOIN tipo_producto ON (producto.cod_tpro_pro = tipo_producto.cod_tpro)
			  INNER JOIN peso ON (d_devolucion.cod_pes_ddev = peso.cod_pes)
			  INNER JOIN usuario ON (m_devolucion.cod_ven_dev = usuario.cod_usu)

			
						 
			WHERE  producto.cod_pro=$dbs->cod_pro and d_devolucion.cant_ddev>0 $fecha_devolucion   order by fec_dev, cod_pes desc"; 
			
			$dbt= new  Database();
			$dbt->query($sqlt);
			$tot4=0;

		 while ($dbt->next_row()) {?> 
          <tr>
            <td class="textotabla01"><div align="center">
              <?=$dbt->fec_dev?>
            </div></td>
            <td class="textotabla01"><div align="center">DEVOLUCION</div></td>
            <td class="textotabla01"><div align="center">
              <?=$dbt->nom_pes?>
            </div></td>
            <td class="textotabla01">
              
              <div align="center">
                <?=$dbt->cant_ddev?>
              </div></td>
            <td class="textotabla01"><?=$dbt->nom_usu?></td>
          </tr>
		  
		 <?  $tot4+=$dbt->cant_ddev;} ?> 
		  
		   <!--/************** movimiento ANULACION FACTURA************************/-->
		
			<?  $sqlt="SELECT *
			
					FROM
					  m_factura
					  INNER JOIN d_factura ON (m_factura.cod_fac = d_factura.cod_mfac)
					  INNER JOIN producto ON (d_factura.cod_pro = producto.cod_pro)
					  INNER JOIN tipo_producto ON (d_factura.cod_tpro = tipo_producto.cod_tpro)
					  INNER JOIN peso ON (d_factura.cod_peso = peso.cod_pes)
                      INNER JOIN usuario ON (m_factura.cod_usu = usuario.cod_usu) 
					 								
					WHERE m_factura.estado='anulado' and producto.cod_pro='$ttt' and d_factura.cant_pro>0 $fecha  order by  fecha,  cod_pes desc  "; 
			
			$dbt= new  Database();
			$dbt->query($sqlt);
			$bandera=0;
			$tot5=0;
			
		?>
			 <? while ($dbt->next_row()) { ?> 	
		  
          <tr>
            <td class="textotabla01"><div align="center">
              <?=$dbt->fecha?>
            </div></td>
            <td class="textotabla01"><div align="center">ANULACI&Oacute;N</div></td>
            <td class="textotabla01"><div align="center">
              <?=$dbt->nom_pes?>
            </div></td>
            <td class="textotabla01"><div align="center">
              <?=$dbt->cant_pro?>
            </div></td>
            <td class="textotabla01"><?=$dbt->nom_usu?></td>
          </tr>
		  
		   <? $tot5+=$dbt->cant_pro;  }  
		  $gran_total=$tot1+$tot2+$tot3+$tot4+$tot5;
		   
		    ?> 
		   
		   <tr>
			 <td class="subfongris"><div align="left">TOTAL FACTURAS </div></td>
             <td class="subfongris" ><div align="left">
               <?=number_format($tot1,0,".",".")?>
             </div></td> 
			
		    <td colspan="2" class="subfongris"><div align="left">TOTAL 
		      TRASLADO</div>
	         <div align="right"></div></td>
		    <td class="subfongris"><?=number_format($tot3,0,".",".")?></td>
	      </tr>
		   <tr>
		     <td class="subfongris"><div align="left">TOTAL ENTRADAS </div></td>
		     <td class="subfongris">
		       <div align="left">
		         <?=number_format($tot2,0,".",".")?>
               </div></td>
		     <td colspan="2" class="subfongris"><div align="left">TOTAL DEVOLUCION </div></td>
		     <td class="subfongris"><?=number_format($tot4,0,".",".")?></td>
	      </tr>
		   <tr>
		     <td class="subfongris"><div align="left">TOTAL ANULACI&Oacute;N </div></td>
		     <td class="subfongris">
		       <div align="left">
		         <?=number_format($tot5,0,".",".")?>
                </div></td>
		     <td colspan="2"class="subfongris">TOTAL MOVIMIENTO </td>
		     <td class="subfongris"><?=number_format($gran_total,0,".",".")?></td>
	      </tr>
        </table>
      
   </td>
  </tr>  
  
</table>
<?  } }  ?>
</div>

<? if($totl==0){ ?>
	<script language="javascript">
	document.getElementById('ref_<?=$dbs->cod_pro?>').style.display="none";
	</script>
<? } ?>
 
 	
<?  } ?>
<p align="center">
  <input type="hidden" name="mapa" value="<?=$mapa?>" />
  <input name="button" type="button" class="botones" onclick="window.print()" value="Imprimir" />
  <input name="button" type="button" class="botones" onclick="window.close()" value="Cerrar" />
</p>
<p>&nbsp;</p>
