<?php
include("../config/config.php");
$term = pg_escape_string($_GET['term']);

$qry_name=pg_query("SELECT
						a.\"IDNO\",
						a.\"full_name\",
						a.\"C_REGIS\",
						a.\"CusID\",
						b.\"PayType\",
						b.\"P_ACCLOSE\",
						b.\"P_CLDATE\",
						b.\"P_STDATE\",
						(select COUNT(\"DueNo\") as \"SumDueNo\" from \"VRemainPayment\" where \"IDNO\" = a.\"IDNO\")
					FROM
						\"UNContact\" a
					LEFT JOIN
						\"Fp\" b on a.\"IDNO\" = b.\"IDNO\"
					WHERE
						a.\"full_name\" LIKE '%$term%' OR
						a.\"IDNO\" LIKE '%$term%' OR
						a.\"C_REGIS\" LIKE '%$term%'
					ORDER BY
						a.\"IDNO\" ASC");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name))
{
	$IDNO = trim($res_name["IDNO"]);
	$full_name = trim($res_name["full_name"]);
	$C_REGIS = trim($res_name["C_REGIS"]);
	$CusID = trim($res_name["CusID"]);
	$PayType = trim($res_name["PayType"]);
	$P_ACCLOSE = trim($res_name["P_ACCLOSE"]);
	$P_CLDATE = trim($res_name["P_CLDATE"]);
	$P_STDATE = trim($res_name["P_STDATE"]);
	$SumDueNo = trim($res_name["SumDueNo"]); // จำนวนงวดที่ค้างชำระ
	
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
	
	$display = "{$IDNO}, {$full_name} {$C_REGIS} {$txtclose}";
	$display_show = preg_replace("/(" . $term . ")/i", "<b>$1</b>", "<div><font color=$color> $display</font></div>");
    
    $dt['value'] = $IDNO."#".$CusID."#".$full_name."#".$C_REGIS;
    $dt['label'] = $display_show;
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
