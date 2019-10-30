<? 
class Export2ExcelClass{ 
    var $FileName   = "export"; #Nombre del archivo 
    var $xls        = "";       #Contenido del archivo 
    var $row        = 1;        #Fila 
    var $col        = 1;        #Columna 

    public function Export2ExcelClass($file_name = "export"){ 
        //Inicio de clase 
        $this->FileName = $file_name; 
    } 

    private function Head($file_name = ""){ 
        //Escribe cabeceras 
        $this->FileName = ($file_name == "") ? $this->FileName : $file_name; 
        $f = $this->FileName; 
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT"); 
        header("Cache-Control: no-cache, must-revalidate"); 
        header("Pragma: no-cache"); 
        header("Content-type: application/x-msexcel"); 
        header("Content-Disposition: attachment; filename=$f.xls" ); 
        header("Content-Description: PHP/INTERBASE Generated Data" ); 
        header("Expires: 0"); 
    } 

    private function BOF(){ 
        //Inicio de archivo 
        return pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0); 
    } 

    private function EOF(){ 
        //Fin de archivo 
        return pack("ss", 0x0A, 0x00); 
    } 

    public function Number($Row, $Col, $Value){ 
        //Escribe un número (double) en la $Row/$Col 
        $this->xls .= pack("sssss", 0x203, 14, $Row, $Col, 0x0); 
        $this->xls .= pack("d", $Value); 
    } 

    public function Text($Row, $Col, $Value){ 
        //Escribe texto en $Row/$Col (UTF8) 
        $Value2UTF8 = utf8_decode($Value); 
        $L = strlen($Value2UTF8); 
        $this->xls .= pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L); 
        $this->xls .= $Value2UTF8; 
    } 

    public function Write($Row, $Col, $Value){ 
        //Escribir texto o numeros en $Row/$Col 
        if (is_numeric($Value)) $this->Number($Row, $Col, $Value); 
        else $this->Text($Row, $Col, $Value); 
    } 

    public function WriteMatriz($Matriz){ 
        //Convierte una matriz en una planilla 
        //NOTA: Elimina el contenido que haya hasta ahora almacenado! 
        /* 
         * Ejemplo: 
         * $Matriz = array( 
         *      array('Nombre', 'Apellido', 'Edad'), 
         *      array('Luciana', 'Camila', 1), 
         *      array('Eduardo, 'Cuomo', 24), 
         *      array('Vanesa', 'Chavez', 21) 
         * ); 
         * 
         * Devuelve un EXCEL como: 
         * _| A     | B      | C  | 
         * 1|Nombre |Apellido|Edad| 
         * 2|Luciana|Camila  |1   | 
         * 3|Eduardo|Cuomo   |24  | 
         * 4|Vanesa |Chavez  |21  | 
         * 
        */ 
        $this->xls = ""; 
        $nRow = 0; 
        $nCol = 0; 
        foreach($Matriz as $Row){ 
            foreach($Row as $Value){ 
                $this->Write($nRow, $nCol, $Value); 
                $nCol++; 
            } 
            $nCol = 0; 
            $nRow++; 
        } 
    } 

    public function Download($file_name = ""){ 
        //Escribe el archivo y agrega las cabeceras para generar la descarga 
        $this->Head($file_name); 
        echo $this->BOF(); 
        echo $this->xls; 
        echo $this->EOF(); 
    } 

    public function Archivo($loc_file){ 
        //Crea archivo, borrando el que existe si ya existia 
        //$loc_file : Ruta del archivo. Ej: "./downloads/archivo.xls" 
        $f = fopen($loc_file, 'w'); 
        fwrite($f, $this->BOF()); 
        fwrite($f, $this->xls); 
        fwrite($f, $this->EOF()); 
        fclose($f); 
    } 

} 

//Antes de esto, debe estar la clase anterior! 
//Generamos el objeto 
$jj=0 ;
for ($ii=0 ;  $ii <= $contador2 - 2 ; $ii++) {
$col1= "FV";
$col2= $_POST["num_factura_".$jj];
$col3= "01";
$col4= $_POST["valor_i_".$jj];
$col5= $_POST["valor_fecha_".$jj];
$col6= $_POST["valor_periodo_".$jj];
$col7= "";
$col8= "41352405";
$col9= $_POST["valor_cliente_".$jj];
$col10= "";
$col11= "";
$col12= "";
$col13= "  /  /    ";
$col14= "";
$col15= $_POST["valor_iden_".$jj];
$col16= "0";
$col17= "0";
$col18= $_POST["valor_base_".$jj];
$col19= "";
$col20= "";
$col21= "0";
$col22= "0";
$col23= "C";
$cadena []= array($col1,$col2,$col3,$col4,$col5,$col6,$col7,$col8,$col9,$col10,$col11,$col12,$col13,$col14,$col15,$col16,$col17,$col18,$col19,$col20,$col21,$col22,$col23);
$fila[] = $cadena [$jj];
$jj++;

$col1= "FV";
$col2= $_POST["num_factura_".$jj];
$col3= "01";
$col4= $_POST["valor_i_".$jj];
$col5= $_POST["valor_fecha_".$jj];
$col6= $_POST["valor_periodo_".$jj];
$col7= "";
$col8= "24080105";
$col9= $_POST["valor_cliente_".$jj];
$col10= "";
$col11= "";
$col12= "";
$col13= "  /  /    ";
$col14= "";
$col15= $_POST["valor_iden_".$jj];
$col16= "0";
$col17= "0";
$col18= $_POST["valor_iva_".$jj];
$col19= "";
$col20= "";
$col21= "0";
$col22= "0";
$col23= "C";
$cadena []= array($col1,$col2,$col3,$col4,$col5,$col6,$col7,$col8,$col9,$col10,$col11,$col12,$col13,$col14,$col15,$col16,$col17,$col18,$col19,$col20,$col21,$col22,$col23);
$fila[] = $cadena [$jj];
$jj++;

$col1= "FV";
$col2= $_POST["num_factura_".$jj];
$col3= "01";
$col4= $_POST["valor_i_".$jj];
$col5= $_POST["valor_fecha_".$jj];
$col6= $_POST["valor_periodo_".$jj];
$col7= "";
$col8= "11050505";
$col9= $_POST["valor_cliente_".$jj];
$col10= "";
$col11= "";
$col12= "";
$col13= "  /  /    ";
$col14= "";
$col15= $_POST["valor_iden_".$jj];
$col16= "0";
$col17= $_POST["valor_total_".$jj];
$col18= "0";
$col19= "";
$col20= "";
$col21= "0";
$col22= "0";
$col23= "C";
$cadena []= array($col1,$col2,$col3,$col4,$col5,$col6,$col7,$col8,$col9,$col10,$col11,$col12,$col13,$col14,$col15,$col16,$col17,$col18,$col19,$col20,$col21,$col22,$col23);
$fila[] = $cadena [$jj];
$jj++;
}
$excel = new Export2ExcelClass; 
//Matriz a convertir:
/*$jj=0 ;
for ($ii=0 ;  $ii <= 5 ; $ii++) {
$array [] = array('FV',$_POST["num_factura_".$jj],'01',$_POST["valor_i_".$jj],$_POST["valor_fecha_".$jj],$_POST["valor_periodo_".$jj],'','41352405',$_POST["valor_cliente_".$jj],'','','','  /  /    ','',$_POST["valor_iden_".$jj],'0','0',$_POST["valor_base_".$jj],'','','0','0','C'), ;
$jj++;
$array [] = array('FV',$_POST["num_factura_".$jj],'01',$_POST["valor_i_".$jj],$_POST["valor_fecha_".$jj],$_POST["valor_periodo_".$jj],'','24080105',$_POST["valor_cliente_".$jj],'','','','  /  /    ','',$_POST["valor_iden_".$jj],'0','0',$_POST["valor_iva_".$jj],'','','0','0','C'), ;
$jj++;
$array [] = array('FV',$_POST["num_factura_".$jj],'01',$_POST["valor_i_".$jj],$_POST["valor_fecha_".$jj],$_POST["valor_periodo_".$jj],'','11050505',$_POST["valor_cliente_".$jj],'','','','  /  /    ','',$_POST["valor_iden_".$jj],'0',$_POST["valor_total_".$jj],'0','','','0','0','C'), ;
$jj++;
}*/
$excel = new Export2ExcelClass; 
$Matriz = $fila; 
//Convertimos la matriz a Excel: 
$excel->WriteMatriz($Matriz);
//Hacemos que sea descargable: 
$excel->Download("ArchivoExcel");

?>
<input type="hidden" name="contador2" id="contador2" value="<?=$contador2?>" />