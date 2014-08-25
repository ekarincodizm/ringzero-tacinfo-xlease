<?php
include("../config/config.php");

$sid = $_SESSION["av_iduser"];
$nowdate = date("Y-m-d");
$idno = pg_escape_string($_POST['idno']);
$CusID = pg_escape_string($_POST['CusID']);
$coname2 = pg_escape_string($_POST['coname2']);
$type_send = pg_escape_string($_POST['type_send']);
$regis_back = pg_escape_string($_POST['regis_back']);

$sentto = pg_escape_string($_POST['sentto']);
if($sentto == 1){
    $address = pg_escape_string($_POST["txt_ads1"]);
	$coname="";
}elseif($sentto == 2){
    $address = pg_escape_string($_POST["txt_ads2"]);
	$coname="";
}elseif($sentto == 3){
    $address = pg_escape_string($_POST["txt_ads3"]);
	$coname="";
}elseif($sentto == 4){
    $address = pg_escape_string($_POST["txt_ads4"]);
	$coname="";
}elseif($sentto == 5){
	$address = pg_escape_string($_POST["txt_ads5"]);
	$coname="";
}elseif($sentto == 6){
	$address = pg_escape_string($_POST["txt_ads6"]);
	$coname=$coname2;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ส่งจดหมาย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">  
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"><input type="button" value="กลับ" onclick="window.location='frm_lt.php'"></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both; padding-bottom: 10px;"></div>        

<fieldset><legend><B>ทำรายการส่งจดหมาย</B></legend>

<div class="ui-widget" align="center">
<?php
pg_query("BEGIN WORK");


$status = 0;

$query_upd = pg_query("select MAX(\"address_id\") AS address from letter.\"cus_address\" where \"CusID\" = '$CusID' and \"Active\" = 'TRUE'");
if($res_name5=pg_fetch_array($query_upd)){
	$address_upd=$res_name5["address"];
}else{
	$status++;
}

if($address_upd!=""){
	$upd = "UPDATE letter.\"cus_address\" SET \"Active\"='FALSE' WHERE \"address_id\"='$address_upd' ";
	if($result=pg_query($upd)){
	}else{
		$status += 1;
	}
}
$ins = "insert into letter.\"cus_address\"(\"CusID\",\"change_date\",\"address\",\"Active\",\"user_id\") values ('$CusID','$nowdate','$address','TRUE','$sid')";

if($result1=pg_query($ins)){
    
}else{
    $status += 1;
}
/*
$qry_name=pg_query("select * from letter.\"cus_address\" WHERE \"CusID\"='$CusID' AND \"Active\" = 'TRUE'");
if($res_name=pg_fetch_array($qry_name)){
    $address_id=$res_name["address_id"];
}else{
    exit;
}
*/
$qry_name=pg_query("select MAX(\"address_id\") AS address from letter.\"cus_address\" where \"CusID\" = '$CusID' and \"Active\" = 'TRUE'");
if($res_name=pg_fetch_array($qry_name)){
	$address_id=$res_name["address"];
}
$nub = 0;
$row_no=pg_escape_string(count($_POST['typeletter']));//�ӹǹ array
for($i=0;$i< $row_no;$i++)
{
	$v = pg_escape_string($_POST['typeletter'][$i]);
    $nub += 1;
    if($nub == 1)
        $add_type .= "$v";
    else
        $add_type .= ",$v";
	//กรณีส่งจดหมายที่มีการออก NT ด้วย ให้ไปอัพเดทสถานะในตาราง nw_statusNT=3 คือส่งจดหมายแล้ว
		if($v == 7){
			//ดูข้อมูลทั้งหมดของ idno นี้กรณีที่ยังไม่ถูกยกเิลิก ว่ามีทั้งหมดกี่ nt
			$query_notice=pg_query("select a.\"statusID\" from \"nw_statusNT\" a
			left join \"NTHead\" b on a.\"NTID\"=b.\"NTID\" 
			left join \"ContactCus\" c on a.\"IDNO\"=c.\"IDNO\" and b.\"CusState\"=c.\"CusState\"
			where a.\"IDNO\" = '$idno' and b.\"cancel\" = 'FALSE' ");
			$num_notice=pg_num_rows($query_notice);
			
			//ตรวจสอบว่า statusNT=4 กี่ตัว กรณีที่ยังไม่ครบสถานะจะเป็น 4
			$query_notice2=pg_query("select a.\"statusID\" from \"nw_statusNT\" a
			left join \"NTHead\" b on a.\"NTID\"=b.\"NTID\" 
			left join \"ContactCus\" c on a.\"IDNO\"=c.\"IDNO\" and b.\"CusState\"=c.\"CusState\"
			where a.\"IDNO\" = '$idno' and b.\"cancel\" = 'FALSE' and a.\"statusNT\"='4'"); //คือยังส่งไม่ครบ
			$num_notice2=pg_num_rows($query_notice2);
			
			
			
			if($num_notice == ($num_notice2+1)){
				$query_insnt=pg_query("select a.\"NTID\" from \"nw_statusNT\" a
					left join \"NTHead\" b on a.\"NTID\"=b.\"NTID\" 
					left join \"ContactCus\" c on a.\"IDNO\"=c.\"IDNO\" and b.\"CusState\"=c.\"CusState\"
					where a.\"IDNO\" = '$idno' and b.\"cancel\" = 'FALSE'");
				while($res_insnt=pg_fetch_array($query_insnt)){
					$NTID = $res_insnt["NTID"];
					
					$up_notice="update \"nw_statusNT\"  set \"statusNT\" = '3' where \"NTID\" = '$NTID'";
					if($res_notice=pg_query($up_notice)){
					}else{
						$status += 1;
					}
				}	
			}else{
				$query_insnt=pg_query("select a.\"NTID\" from \"nw_statusNT\" a
					left join \"NTHead\" b on a.\"NTID\"=b.\"NTID\" 
					left join \"ContactCus\" c on a.\"IDNO\"=c.\"IDNO\" and b.\"CusState\"=c.\"CusState\"
					where a.\"IDNO\" = '$idno' and b.\"cancel\" = 'FALSE' and c.\"CusID\" = '$CusID'");
				if($res_insnt=pg_fetch_array($query_insnt)){
					$NTID = $res_insnt["NTID"];
				}	
				
				$up_notice="update \"nw_statusNT\"  set \"statusNT\" = '4' where \"NTID\" = '$NTID'";
				if($res_notice=pg_query($up_notice)){
				}else{
					$status += 1;
				}
			}	
		}
}

$ins2 = "insert into letter.\"SendDetail\" (\"send_date\",\"IDNO\",\"address_id\",\"detail\",\"userid\",\"type_send\",\"receive_date\",\"coname\") values ('$nowdate','$idno','$address_id','$add_type','$sid','$type_send',NULL,'$coname')";

if($result2=pg_query($ins2)){
    
}else{
    $status += 1;
}

$qry_name=pg_query("select \"auto_id\" from letter.\"cus_address\"  a
		left join letter.\"SendDetail\" b on a.\"address_id\"=b.\"address_id\" 
		WHERE a.\"CusID\"='$CusID' and \"IDNO\"='$idno' AND a.\"Active\" = 'TRUE' order by auto_id DESC limit 1");
		if($res_name=pg_fetch_array($qry_name)){
			$auto_id=$res_name["auto_id"];
		}else{
			exit;
	}

if($regis_back != ""){
		//
		if($type_send=="E"){
			$regis = "insert into letter.\"regis_send\" (\"ems_num\",\"auto_id\") values ('$regis_back','$auto_id')";
		}
		else if($type_send=="A"){
			$regis = "insert into letter.\"regis_send\" (\"reg_num\",\"auto_id\") values ('$regis_back','$auto_id')";
		}
		if($result9=pg_query($regis)){
    
		}else{
			$status += 1;
		}
}

if($status == 0){
    pg_query("COMMIT");
	$post = "คลองจั่น";
    echo "บันทึกข้อมูลเรียบร้อยแล้ว<br /><br /><input type=\"button\" value=\"พิมพ์จดหมาย\" onclick=\"window.open('print_letter.php?cus_lid=$auto_id')\">";
	if($regis_back !=""){
		if($type_send=="E"){
			echo "<input type=\"button\" value=\"พิมพ์ใบฟ้า\" onclick=\"window.open('print_yellow.php?cus_lid=$auto_id&nowdate=$nowdate&post=$post')\">";
		}
		else if($type_send=="A"){
			echo "<input type=\"button\" value=\"พิมพ์ใบเหลือง\" onclick=\"window.open('print_yellow.php?cus_lid=$auto_id&nowdate=$nowdate&post=$post')\">";
		}
		
	}
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง<hr>$ins<hr>$ins2";
}
?>
</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>