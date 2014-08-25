<?php
include("../config/config.php");

$add_user = $_SESSION["av_iduser"];
$add_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$nowdate = date("Y-m-d");

$IDNO=pg_escape_string($_POST["IDNO"]);
$CusID=pg_escape_string($_POST["CusID"]);
$CusState=pg_escape_string($_POST["CusState"]);
$f_no=pg_escape_string($_POST["f_no"]); if($f_no==""){ $f_no2="null";}else{ $f_no2="'".$f_no."'";}
$f_subno=pg_escape_string($_POST["f_subno"]); if($f_subno=="" || $f_subno=="-" || $f_subno=="--"){ $f_subno2="null";}else{ $f_subno2="'".$f_subno."'";}
$f_soi=pg_escape_string($_POST["f_soi"]); if($f_soi=="" || $f_soi=="-" || $f_soi=="--"){ $f_soi2="null";}else{ $f_soi2="'".$f_soi."'";}
$f_rd=pg_escape_string($_POST["f_rd"]); if($f_rd=="" || $f_rd=="-" || $f_rd=="--"){ $f_rd2="null";}else{ $f_rd2="'".$f_rd."'";}
$f_tum=pg_escape_string($_POST["f_tum"]); if($f_tum==""){ $f_tum2="null";}else{ $f_tum2="'".$f_tum."'";}
$f_aum=pg_escape_string($_POST["f_aum"]); if($f_aum==""){ $f_aum2="null";}else{ $f_aum2="'".$f_aum."'";}
$f_province=pg_escape_string($_POST["A_PRO"]);
$f_post=pg_escape_string($_POST["f_post"]); if($f_post=="" || $f_post=="-" || $f_post=="--"){ $f_post2="null";}else{ $f_post2="'".$f_post."'";}
$f_room=pg_escape_string($_POST["f_room"]); if($f_room=="" || $f_room=="-" || $f_room=="--"){ $f_room2="null";}else{ $f_room2="'".$f_room."'";}
$f_floor=pg_escape_string($_POST["f_floor"]); if($f_floor=="" || $f_floor=="-" || $f_floor=="--"){ $f_floor2="null";}else{ $f_floor2="'".$f_floor."'";}
$f_building=pg_escape_string($_POST["f_building"]); if($f_building==""){ $f_building2="null";}else{ $f_building2="'".$f_building."'";}
$f_ban=pg_escape_string($_POST["f_ban"]); if($f_ban=="" || $f_ban=="-" || $f_ban=="--"){ $f_ban2="null";}else{ $f_ban2="'".$f_ban."'";}
$addreach=pg_escape_string($_POST["addreach"]);
$editidno=pg_escape_string($_POST["editidno"]); //แก้เฉพาะสัญญานี้||แก้ทุกเลขที่สัญญา
$editcus=pg_escape_string($_POST["editcus"]); //เฉพาะผู้เช่าซื้อ||ผู้ค้ำ||ทั้งหมด
$editfa1=pg_escape_string($_POST["editfa1"]); //แก้ไขในข้อมูลลูกค้าด้วยหรือไม่
$statusedit=pg_escape_string($_POST['statusedit']); //สถานะกรณีที่แก้ไขจากหน้าส่งจดหมาย


if($f_room=="" || $f_room=="-" || $f_room=="--"){ //ห้อง
	//ไม่ต้องทำอะไร
}else{
	$room="ห้อง $f_room";
}
			
if($f_floor=="" || $f_floor=="-" || $f_floor=="--"){ //ชั้น
	//ไม่ต้องทำอะไร
}else{
	$floor="ชั้น $f_floor";
}
			
if($f_ban=="" || $f_ban=="-" || $f_ban=="--"){ //หมู่บ้าน
	//ไม่ต้องทำอะไร
}else{
	$ban="หมู่บ้าน$f_ban";
}
			
if($f_subno=="" || $f_subno=="-" || $f_subno=="--"){
	//ไม่ต้องทำอะไร
}else{
	$subno1="ม.$f_subno";
}
if($f_soi=="" || $f_soi=="-" || $f_soi=="--"){
	//ไม่ต้องทำอะไร
}else{
	$soi1="ซ.$f_soi";
}
if($f_rd=="" || $f_rd=="-" || $f_rd=="--"){
	//ไม่ต้องทำอะไร
}else{
	$road1="ถ.$f_rd";
}
if($f_post=="" || $f_post=="-" || $f_post=="--"){
	$f_post="";
}
			
if($f_province=="กรุงเทพมหานคร" || $f_province=="กรุงเทพ" || $f_province=="กรุงเทพฯ" || $f_province=="กทม."){
	$txttum1="แขวง".$f_tum;
	$txtaum1="เขต".$f_aum;
	$txtpro1="$f_province"; //จังหวัด
}else{
	$txttum1="ต.".$f_tum;
	$txtaum1="อ.".$f_aum;
	$txtpro1="จ.".$f_province; //จังหวัด
}	
			
$address = "$f_no $subno1 $ban $f_building $room $floor
$soi1 $road1 $txttum1
$txtaum1 $txtpro1 $f_post";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">  
    <link type="text/css" rel="stylesheet" href="act.css"></link>
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
// เพิ่ม function สั่งให้  ปุ่ม  "click" ทำ even คลิก 
function updateaddress() {
window.opener.document.forms[0].updateaddress.click();
self.close();
}
</script> 
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
<?php
	if(($statusedit!="1")and($statusedit!="2")){  //เพิ่มเงื่อนไข ตรวจสอบ โดย จากการ link ทาจาก เมนู นัดตรวจรถ โดยให้ statusedit =="2
	?>
	<div style="float:left"><input type="button" value="กลับ" onclick="window.location='frm_lt_edit.php'"></div>
	<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
	<div style="clear:both; padding-bottom: 10px;"></div>        
<?php } ?>

<?php if(($_GET['FrmState']="t") and ($statusedit=="2")) //เพิ่มเงื่อนไข ตรวจสอบ โดย จากการ link ทาจาก เมนู นัดตรวจรถ 
			{?>
			<!--โดยไม่ให้แสดง ปุ่ม "กลับ"	ในกรณี ที่ มีการ link มาจาก เมนู นัดตรวจรถ -->
			<div style="float:right"><input type="button" value="  Close  " onclick="javascript:updateaddress();"></div>
			<div style="clear:both; padding-bottom: 10px;"></div>   
		<?php } 
		?>

<fieldset><legend><B>แก้ไขที่อยู่ส่งจดหมาย</B></legend>

<div class="ui-widget" align="center">
<?php
pg_query("BEGIN WORK");
$status = 0;

//ดึงข้อมูลเก่าขึ้นมาเพื่อตรวสอบ
$qryaddrnow=pg_query("select \"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\",\"A_ROOM\",\"A_FLOOR\",\"A_BUILDING\",\"A_BAN\"
,\"addEach\",\"edittime\" from \"Fp_Fa1\" where \"IDNO\"='$IDNO' and \"CusState\"='$CusState' order by \"edittime\" DESC limit 1");
$numrows=pg_num_rows($qryaddrnow);
if($res_addrnow=pg_fetch_array($qryaddrnow)){
	$A_NO=trim($res_addrnow["A_NO"]);
	$A_SUBNO=trim($res_addrnow["A_SUBNO"]);
	$A_SOI=trim($res_addrnow["A_SOI"]);
	$A_RD=trim($res_addrnow["A_RD"]);
	$A_TUM=trim($res_addrnow["A_TUM"]);
	$A_AUM=trim($res_addrnow["A_AUM"]);
	$A_PRO=trim($res_addrnow["A_PRO"]);
	$A_POST=trim($res_addrnow["A_POST"]);
	$A_ROOM=trim($res_addrnow["A_ROOM"]);
	$A_FLOOR=trim($res_addrnow["A_FLOOR"]);
	$A_BUILDING=trim($res_addrnow["A_BUILDING"]);
	$A_BAN=trim($res_addrnow["A_BAN"]);
	$addEach=trim($res_addrnow["addEach"]);	
	$edittime1=trim($res_addrnow["edittime"]);	
	$edittime=$edittime1+1;
}

if($A_NO==$f_no and $A_SUBNO==$f_subno and $A_SOI==$f_soi and $A_RD==$f_rd and $A_TUM==$f_tum
and $A_AUM==$f_aum and $A_PRO==$f_province and $A_POST==$f_post and $A_ROOM==$f_room and $A_FLOOR==$f_floor
and $A_BUILDING==$f_building and $A_BAN==$f_ban and $addEach==$addreach){
	//กรณีค่าเท่ากันทุกค่าแสดงว่าไม่มีการแก้ไข
}else{ //มีการแก้ไขข้อมูล
	if($numrows==0){
		$edittime=0;
	}
	
	if($editidno=="1"){ //แก้เฉพาะสัญญานี้
		$insfpfa1="INSERT INTO \"Fp_Fa1\"(
			\"IDNO\", \"CusID\", \"A_NO\", \"A_SUBNO\", \"A_SOI\", \"A_RD\", 
			\"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",\"addEach\",\"CusState\",\"edittime\",\"addUser\",\"addStamp\",
			\"A_ROOM\", \"A_FLOOR\", \"A_BUILDING\", \"A_BAN\")
		VALUES ('$IDNO','$CusID', $f_no2, $f_subno2, $f_soi2, $f_rd2, 
				$f_tum2, $f_aum2, '$f_province', $f_post2,'$addreach','$CusState','$edittime','$add_user','$add_date',
				$f_room2,$f_floor2,$f_building2,$f_ban2)";	
	
		if($resinfpfa1=pg_query($insfpfa1)){
		}else{
			$status++;
		}
	}else{ //แก้ทุกสัญญา
		if($editcus=="1"){ //แก้ไขเฉพาะที่เป็นผู้เช่าซื้อ
			$qrycus0=pg_query("SELECT a.\"IDNO\",a.\"edittime\" FROM \"Fp_Fa1\" a
			inner  join( SELECT max(\"auto_id\") AS autoid, \"Fp_Fa1\".\"IDNO\"
					   FROM \"Fp_Fa1\" where \"Fp_Fa1\".\"CusID\"='$CusID' and \"Fp_Fa1\".\"CusState\"='0'
			 GROUP BY \"Fp_Fa1\".\"IDNO\") b ON a.\"auto_id\" = b.\"autoid\" and a.\"IDNO\"=b.\"IDNO\"
			 ORDER BY a.auto_id");
			
			 while($rescus0=pg_fetch_array($qrycus0)){
				//หาค่า edittime ครั้งต่อไป
				list($idno0,$edittime0)=$rescus0;
				$edittime=$edittime0+1;
				$insfpfa1="INSERT INTO \"Fp_Fa1\"(
					\"IDNO\", \"CusID\", \"A_NO\", \"A_SUBNO\", \"A_SOI\", \"A_RD\", 
					\"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",\"addEach\",\"CusState\",\"edittime\",\"addUser\",\"addStamp\",
					\"A_ROOM\", \"A_FLOOR\", \"A_BUILDING\", \"A_BAN\")
				VALUES ('$idno0','$CusID', $f_no2, $f_subno2, $f_soi2, $f_rd2, 
						$f_tum2, $f_aum2, '$f_province', $f_post2,'$addreach','$CusState','$edittime','$add_user','$add_date',
						$f_room2,$f_floor2,$f_building2,$f_ban2)";	
				if($resinfpfa1=pg_query($insfpfa1)){
				}else{
					$status++;
				}
			 }
		}else if($editcus=="2"){ //แก้ไขเฉพาะที่เป็นผู้ค้ำ
			$qrycus0=pg_query("SELECT a.\"IDNO\",a.\"edittime\",a.\"CusState\" FROM \"Fp_Fa1\" a
			inner  join( SELECT max(\"auto_id\") AS autoid, \"Fp_Fa1\".\"IDNO\"
					   FROM \"Fp_Fa1\" where \"Fp_Fa1\".\"CusID\"='$CusID' and \"Fp_Fa1\".\"CusState\"<>'0'
			 GROUP BY \"Fp_Fa1\".\"IDNO\") b ON a.\"auto_id\" = b.\"autoid\" and a.\"IDNO\"=b.\"IDNO\"
			 ORDER BY a.auto_id;");
			 while($rescus0=pg_fetch_array($qrycus0)){
				//หาค่า edittime ครั้งต่อไป
				list($idno0,$edittime0,$CusState0)=$rescus0;
				$edittime=$edittime0+1;
				$insfpfa1="INSERT INTO \"Fp_Fa1\"(
					\"IDNO\", \"CusID\", \"A_NO\", \"A_SUBNO\", \"A_SOI\", \"A_RD\", 
					\"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",\"addEach\",\"CusState\",\"edittime\",\"addUser\",\"addStamp\",
					\"A_ROOM\", \"A_FLOOR\", \"A_BUILDING\", \"A_BAN\")
				VALUES ('$idno0','$CusID', $f_no2, $f_subno2, $f_soi2, $f_rd2, 
						$f_tum2, $f_aum2, '$f_province', $f_post2,'$addreach','$CusState0','$edittime','$add_user','$add_date',
						$f_room2,$f_floor2,$f_building2,$f_ban2)";	
				if($resinfpfa1=pg_query($insfpfa1)){
				}else{
					$status++;
				}
			 }
		}else{ //แก้ไขทั้งที่เป็นผู้เช่าซื้อและูผู้ค้ำ
			$qrycus0=pg_query("SELECT a.\"IDNO\",a.\"edittime\",a.\"CusState\" FROM \"Fp_Fa1\" a
			inner  join( SELECT max(\"auto_id\") AS autoid, \"Fp_Fa1\".\"IDNO\"
					   FROM \"Fp_Fa1\" where \"Fp_Fa1\".\"CusID\"='$CusID'
			 GROUP BY \"Fp_Fa1\".\"IDNO\") b ON a.\"auto_id\" = b.\"autoid\" and a.\"IDNO\"=b.\"IDNO\"
			 ORDER BY a.auto_id;");
			 while($rescus0=pg_fetch_array($qrycus0)){
				//หาค่า edittime ครั้งต่อไป
				list($idno0,$edittime0,$CusState0)=$rescus0;
				$edittime=$edittime0+1;
				$insfpfa1="INSERT INTO \"Fp_Fa1\"(
					\"IDNO\", \"CusID\", \"A_NO\", \"A_SUBNO\", \"A_SOI\", \"A_RD\", 
					\"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",\"addEach\",\"CusState\",\"edittime\",\"addUser\",\"addStamp\",
					\"A_ROOM\", \"A_FLOOR\", \"A_BUILDING\", \"A_BAN\")
				VALUES ('$idno0','$CusID', $f_no2, $f_subno2, $f_soi2, $f_rd2, 
						$f_tum2, $f_aum2, '$f_province', $f_post2,'$addreach','$CusState0','$edittime','$add_user','$add_date',
						$f_room2,$f_floor2,$f_building2,$f_ban2)";	
				if($resinfpfa1=pg_query($insfpfa1)){
				}else{
					$status++;
				}
			 }
		}
	}
	//กรณีแก้ไขที่อยู่ในข้อมูลลูกค้าด้วย
	if($editfa1=="1"){
		$update_Fa1="Update \"Fa1\" SET \"A_NO\"='$f_no' ,\"A_SUBNO\"=$f_subno2, \"A_SOI\"=$f_soi2,\"A_RD\"=$f_rd2,\"A_TUM\"='$f_tum',\"A_AUM\"='$f_aum' ,\"A_PRO\"='$f_province',\"A_POST\"='$f_post'
		where \"CusID\"='$CusID' ";

		if($result=pg_query($update_Fa1)){
		}else{
			$status++;
		}
	}
	
	//ถ้ามีการแก้ไขที่อยู่ให้ไปอัพเดทที่อยู่ส่งจดหมายล่าสุดด้วย
	$query_upd = pg_query("select MAX(a.\"address_id\") AS address from letter.\"cus_address\" a
	left join letter.\"SendDetail\" b on a.\"address_id\"=b.\"address_id\"
	where \"CusID\" = '$CusID' and \"IDNO\"='$IDNO' and \"Active\" = 'TRUE'");
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
	$ins = "insert into letter.\"cus_address\"(\"CusID\",\"change_date\",\"address\",\"Active\",\"user_id\") values ('$CusID','$nowdate','$address','TRUE','$add_user')";
	if($result1=pg_query($ins)){
    
	}else{
		$status += 1;
	}
	
	//ดึง address ล่าสุดออกมาเพื่อนำไป insert ใหม่
	$query_upd2 = pg_query("select MAX(\"address_id\") AS address from letter.\"cus_address\" 
	where \"CusID\" = '$CusID' and \"Active\" = 'TRUE'");
	if($res_add=pg_fetch_array($query_upd2)){
		$address_id=$res_add["address"];
	}
	
	$ins2 = "insert into letter.\"SendDetail\"(\"IDNO\",\"address_id\",\"userid\") values ('$IDNO','$address_id','$add_user')";
	if($result2=pg_query($ins2)){
    
	}else{
		$status += 1;
	}
	

}

if($status == 0){
    pg_query("COMMIT");
    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
	if($statusedit=="1"){
	echo "<br><br><input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";	
	}
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง";
}
?>
</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>