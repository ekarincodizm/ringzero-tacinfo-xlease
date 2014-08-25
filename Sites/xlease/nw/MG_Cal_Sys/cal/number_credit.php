<?php





$credit = $_POST["credit"];
$credit = str_replace(',','',$credit);
//echo $credit;
if(!is_numeric($credit))
{
echo "กรุณากรอกตัวเลข" ;	
}
else{
echo number_format($credit) ;
}

?>