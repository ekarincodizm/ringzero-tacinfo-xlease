<?php
include("../config/config.php"); 

$term = pg_escape_string($_GET['term']);

//ตรวจสอบว่าค่าที่ส่งมาเป็นตัวเลขหรือไม่
// if(is_numeric($term) ) {
	// $order='order by a.\"IDNO\",a.\"C_REGIS\"';
// } else {
	// $order='order by a.\"C_REGIS\"';
// }

$sql_select=pg_query("SELECT a.\"IDNO\",a.\"asset_type\",a.\"full_name\",a.\"C_CARNUM\",a.\"TranIDRef1\",a.\"TranIDRef2\",a.\"C_REGIS\",
a.\"car_regis\",a.\"P_ACCLOSE\",b.\"P_CLDATE\",b.\"P_STDATE\",b.asset_id, b.\"PayType\"
FROM \"VContact\" a join \"Fp\" b on a.\"IDNO\" = b.\"IDNO\"
WHERE (a.\"IDNO\" like '%$term%') OR (a.\"C_REGIS\" like '%$term%') OR (a.\"car_regis\" like '%$term%') OR (a.\"C_CARNUM\" like '%$term%') 
OR (a.\"full_name\" like '%$term%') OR (a.\"TranIDRef1\" like '%$term%') OR (a.\"TranIDRef2\" like '%$term%') 
order by CASE WHEN  ISNUMERIC('$term') THEN a.\"IDNO\" ELSE a.\"C_REGIS\" END;");

$numrows = pg_num_rows($sql_select);

while($res_cn=pg_fetch_array($sql_select)){
    $IDNO = trim($res_cn["IDNO"]);
    $asset_type = trim($res_cn["asset_type"]);
    $full_name = trim($res_cn["full_name"]);
    $C_CARNUM = trim($res_cn["C_CARNUM"]);
    $TranIDRef1 = trim($res_cn["TranIDRef1"]);
    $TranIDRef2 = trim($res_cn["TranIDRef2"]);
	$P_ACCLOSE = trim($res_cn["P_ACCLOSE"]);
	$P_CLDATE = trim($res_cn["P_CLDATE"]);
	$P_STDATE = trim($res_cn["P_STDATE"]);
	$PayType = trim($res_cn["PayType"]);
    $asset_id = trim($res_cn["asset_id"]);
    if($asset_type == 1){
        $regis = trim($res_cn["C_REGIS"]);
    }else{
        $regis = trim($res_cn["car_regis"]);
    }
	
	//นำ IDNO ที่ได้ มาตรวจสอบดูว่าค้างกี่เดือน
	$qry_fr=pg_query("select \"IDNO\",COUNT(\"DueNo\") as \"SumDueNo\" from \"VRemainPayment\" where \"IDNO\"='$IDNO' GROUP BY \"IDNO\" ");
	if($res_fr=pg_fetch_array($qry_fr)){
		$SumDueNo = $res_fr["SumDueNo"]; //จำนวนงวดที่ค้างชำระ
	}
	
	if($PayType == "CC"){
		$txtclose="<b>(สัญญานี้ยกเลิกแล้ว)</b>";
		$color='#FF00FF';
	}else if($P_ACCLOSE=='t' AND ($P_CLDATE != $P_STDATE)){
		$txtclose="<b>(ปิดบัญชีแล้ว)</b>";
		$color='#D6D6D6';
	}else if($P_ACCLOSE=='t' AND ($P_CLDATE == $P_STDATE)){
		$txtclose="<b>(ซื้อสด)</b>";
		$color='GREEN';
	}else{
		if($SumDueNo=="1"){
			$txtclose="<b>(ค้าง 1 งวด)</b>";
			$color='#9933FF';
		}else if($SumDueNo=="2"){
			$txtclose="<b>(ค้าง 2 งวด)</b>";
			$color='ORANGE';
		}else if($SumDueNo=="3"){
			$txtclose="<b>(ค้าง 3 งวด)</b>";
			$color='RED';
		}else{
			$txtclose="";
			$color="#000000";
		}
	}
	
	$name = str_replace("'", "\'"," ".$IDNO.""." / ".$regis.""." / ".$full_name.""." / ".$C_CARNUM.""." / ".$TranIDRef1.""." / ".$TranIDRef2.""." / ".$txtclose);
	// if($P_ACCLOSE=='t'){
		$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", "<div><font color=$color> $name</font></div>");
	// }else{
		// $display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", "$name");
	// }

	$dt['value'] = "$IDNO : $regis - $full_name - $C_CARNUM - $TranIDRef1 - $TranIDRef2 - $asset_id";
	$dt['label'] = $display_name;
    $matches[] = $dt;
	
	$SumDueNo="";
}
if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0);
print json_encode($matches);
?>