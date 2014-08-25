<?php
session_start();
include("../config/config.php");
$add_user=$_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$idno = pg_escape_string($_POST['gidno']);
$cusid = pg_escape_string($_POST['cus_id']);
$asset_id = pg_escape_string($_POST['asset_id']);
$company = pg_escape_string($_POST['company']);
$code = pg_escape_string($_POST['code']);
$date_start = pg_escape_string($_POST['date_start']);
$date_end = pg_escape_string($_POST['date_end']);
$discount = pg_escape_string($_POST['discount']);
$capa = pg_escape_string($_POST['capa']);
$nowdate = date("Y/m/d");

pg_query("BEGIN WORK");
$status=0;

$oins=pg_query("select \"insure\".gen_co_insid('$nowdate',1,1)");
$res_oins=pg_fetch_result($oins,0);

$crif=pg_query("select \"insure\".cal_rate_insforce('$code','$date_start','$date_end')");
$res_crif=pg_fetch_result($crif,0);
$res_crif = preg_replace('/[^a-z0-9,.]/i', '', $res_crif);
$pieces = explode(",", $res_crif);

$gpremium = $pieces[0]+$pieces[1]+$pieces[2];
$col_cus = $gpremium-$discount; 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
    <tr>
        <td background=><img src="../images/bg_01.jpg" height="15" width="700"></td>
    </tr>
    <tr>
        <td align="center" valign="top" background="../images/bg_02.jpg" style="background-repeat:repeat-y">

<div class="header"><h1>ระบบประกันภัย</h1></div>
<div class="wrapper">
<br>
<?php
$taxstampint = (int) $pieces[1];
$in_sql="insert into \"insure\".\"InsureForce\" (\"InsFIDNO\",\"IDNO\",\"CusID\",\"CarID\",\"Company\",\"StartDate\",\"EndDate\",\"Code\",\"Capacity\",\"Premium\",\"NetPremium\",\"Vat\",\"TaxStamp\",\"Discount\",\"CollectCus\") values  ('$res_oins','$idno','$cusid','$asset_id','$company','$date_start','$date_end','$code','$capa','$gpremium','$pieces[0]','$pieces[2]','$taxstampint','$discount','$col_cus')";
if($result=pg_query($in_sql)){ 
}else{
	$status++;
    
} 

//เนื่องจากระบบเก่ามีการอัพเดท Fc ระบบใหม่จึงต้องตรวจสอบก่อนว่าเลขที่สัญญานี้เป็นเลขที่สัญญาที่ใช้รถปัจจุบันหรือไม่ถ้าใช่ให้ update Fc เพราะตาราง Fc นั้นจะเก็บข้อมูลรถปัจจุบัน
$qrycarnow=pg_query("select \"C_REGIS\",\"IDNO\" from \"Fp\" a
left join \"Fc\" b on a.asset_id=b.\"CarID\" where \"CarID\"='$asset_id' order by \"P_STDATE\" DESC limit 1");
$rescarnow=pg_fetch_array($qrycarnow);
list($C_REGISnow,$idnonow)=$rescarnow;

if($idnonow==$idno){ //ถ้าเท่าักันแสดงว่าเป็นรถปัจจุบันให้ update Fc
	$up_fc="update \"Fc\" set \"C_CAR_CC\" = '$capa' where \"CarID\"='$asset_id'";
	if($result_fc=pg_query($up_fc)){
	}else{
		$status++;
	}
}

if(trim($C_CAR_CC)!=trim($capa)){ //ถ้าไม่เท่ากันแสดงว่ามีการเปลี่ยนแปลง cc รถ ให้ insert เป็นประวัติ
	$in_carregis="insert into \"Carregis_temp\" (\"IDNO\", \"C_REGIS\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\", 
		\"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\", 
		\"C_TAX_MON\", \"C_StartDate\", \"CarID\", \"keyUser\", \"keyStamp\", \"C_CAR_CC\", 
		\"RadioID\", \"CarType\",fc_type,fc_brand,fc_model,fc_category,fc_newcar,fc_gas,type_in_act) 
	select 
		\"IDNO\",\"C_REGIS\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\",
		\"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\",
		\"C_TAX_MON\", \"C_StartDate\", '$asset_id', '$add_user', '$add_date', '$capa', 
		\"RadioID\", \"CarType\",fc_type,fc_brand,fc_model,fc_category,fc_newcar,fc_gas,type_in_act from \"Carregis_temp\" where \"IDNO\"='$idno' order by auto_id DESC limit 1";

	if($result_carregis=pg_query($in_carregis)){
	}else{
		$status++;
	}
}
 if($status==0){
	
    pg_query("COMMIT");
	//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$add_user','(TAL) เพิ่มข้อมูลประกันภัย พรบ.', '$add_date')");
	//ACTIONLOG---
    echo "เพิ่มข้อมูลเรียบร้อยแล้ว";
}else{
	pg_query("ROLLBACK");
    //echo "ไม่สามารถเพิ่มข้อมูลได้<br /><br />$in_sql<br><br><INPUT TYPE=\"BUTTON\" VALUE=\"Back\" ONCLICK=\"history.go(-1)\">";    
	echo "ไม่สามารถเพิ่มข้อมูลได้";
}	
?>
<br>
</div>
<!--<div align="center"><br><INPUT TYPE="BUTTON" VALUE="Back" ONCLICK="history.go(-1)"></div>-->
<div align="center"><br><INPUT TYPE="BUTTON" VALUE="Back" ONCLICK="history.go(-2)"></div>
        </td>
    </tr>
    <tr>
        <td><img src="../images/bg_03.jpg" width="700" height="15"></td>
    </tr>
</table>

</body>
</html>