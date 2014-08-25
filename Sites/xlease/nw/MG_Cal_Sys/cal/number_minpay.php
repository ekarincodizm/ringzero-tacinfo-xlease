<?php






$minpay = $_POST["cmort_minpay"];
$minpay = str_replace(',','',$minpay);
if(!is_numeric($minpay))
{
echo "กรุณากรอกตัวเลข" ;	
}
else{
 echo number_format($minpay) ;
}

?>