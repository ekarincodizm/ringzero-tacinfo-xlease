<?php 
include("config/config.php"); 

pg_query("BEGIN WORK");

$qry=pg_query("SELECT * FROM carregis.\"CarTaxDue\" ORDER BY \"IDCarTax\" ASC ");
$rows = pg_num_rows($qry);
while($res=pg_fetch_array($qry)){
    
    if( empty($res[TaxValue]) AND empty($res[CoPayDate]) ){
        
        if( empty($res[TypeDep]) AND empty($res[BillNumber]) ){
            $in_sql="INSERT INTO carregis.\"DetailCarTax\" (\"IDCarTax\",\"TypePay\",\"BillNumber\") VALUES ('$res[IDCarTax]',DEFAULT,DEFAULT)";
        }elseif( empty($res[TypeDep]) ){
            $in_sql="INSERT INTO carregis.\"DetailCarTax\" (\"IDCarTax\",\"TypePay\",\"BillNumber\") VALUES ('$res[IDCarTax]',DEFAULT,'$res[BillNumber]')";
        }elseif(empty($res[BillNumber]) ){
            $in_sql="INSERT INTO carregis.\"DetailCarTax\" (\"IDCarTax\",\"TypePay\",\"BillNumber\") VALUES ('$res[IDCarTax]','$res[TypeDep]',DEFAULT)";
        }
        
    }elseif( empty($res[CoPayDate]) ){
        
        if( empty($res[TypeDep]) AND empty($res[BillNumber]) ){
            $in_sql="INSERT INTO carregis.\"DetailCarTax\" (\"IDCarTax\",\"TaxValue\",\"TypePay\",\"BillNumber\") VALUES ('$res[IDCarTax]','$res[TaxValue]',DEFAULT,DEFAULT)";
        }elseif( empty($res[TypeDep]) ){
            $in_sql="INSERT INTO carregis.\"DetailCarTax\" (\"IDCarTax\",\"TaxValue\",\"TypePay\",\"BillNumber\") VALUES ('$res[IDCarTax]','$res[TaxValue]',DEFAULT,'$res[BillNumber]')";
        }elseif(empty($res[BillNumber]) ){
            $in_sql="INSERT INTO carregis.\"DetailCarTax\" (\"IDCarTax\",\"TaxValue\",\"TypePay\",\"BillNumber\") VALUES ('$res[IDCarTax]','$res[TaxValue]','$res[TypeDep]',DEFAULT)";
        }
        
    }elseif( empty($res[TaxValue]) ){
        
        if( empty($res[TypeDep]) AND empty($res[BillNumber]) ){
            $in_sql="INSERT INTO carregis.\"DetailCarTax\" (\"IDCarTax\",\"CoPayDate\",\"TypePay\",\"BillNumber\") VALUES ('$res[IDCarTax]','$res[CoPayDate]',DEFAULT,DEFAULT)";
        }elseif( empty($res[TypeDep]) ){
            $in_sql="INSERT INTO carregis.\"DetailCarTax\" (\"IDCarTax\",\"CoPayDate\",\"TypePay\",\"BillNumber\") VALUES ('$res[IDCarTax]','$res[CoPayDate]',DEFAULT,'$res[BillNumber]')";
        }elseif(empty($res[BillNumber]) ){
            $in_sql="INSERT INTO carregis.\"DetailCarTax\" (\"IDCarTax\",\"CoPayDate\",\"TypePay\",\"BillNumber\") VALUES ('$res[IDCarTax]','$res[CoPayDate]','$res[TypeDep]',DEFAULT)";
        }
        
    }else{
        
        if( empty($res[TypeDep]) AND empty($res[BillNumber]) ){
            $in_sql="INSERT INTO carregis.\"DetailCarTax\" (\"IDCarTax\",\"CoPayDate\",\"TaxValue\",\"TypePay\",\"BillNumber\") VALUES ('$res[IDCarTax]','$res[CoPayDate]','$res[TaxValue]',DEFAULT,DEFAULT)";
        }elseif( empty($res[TypeDep]) ){
            $in_sql="INSERT INTO carregis.\"DetailCarTax\" (\"IDCarTax\",\"CoPayDate\",\"TaxValue\",\"TypePay\",\"BillNumber\") VALUES ('$res[IDCarTax]','$res[CoPayDate]','$res[TaxValue]',DEFAULT,'$res[BillNumber]')";
        }elseif(empty($res[BillNumber]) ){
            $in_sql="INSERT INTO carregis.\"DetailCarTax\" (\"IDCarTax\",\"CoPayDate\",\"TaxValue\",\"TypePay\",\"BillNumber\") VALUES ('$res[IDCarTax]','$res[CoPayDate]','$res[TaxValue]','$res[TypeDep]',DEFAULT)";
        }
        
   }

    if( $result=pg_query($in_sql) ){
        $number+=1;
    }

}

if($rows == $number){
    pg_query("COMMIT");
    echo "OK $rows:$number";
}else{
    pg_query("ROLLBACK");
    echo "ERROR $rows:$number";
}

?>