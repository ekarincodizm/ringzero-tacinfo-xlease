<?php
session_start();
include("../../config/config.php");
include("../function/emplevel.php");
$id_user = $_SESSION["av_iduser"];

$prebillIDMaster=pg_escape_string($_GET["prebillIDMaster"]);
$statusApp=pg_escape_string($_GET["statusApp"]); //สถานะการอนุมัติ
$edittime=pg_escape_string($_GET["edittime"]); //ครั้งที่แก้ไข


if($edittime==""){ //กรณีไม่มีค่าแสดงว่าต้องการแสดงข้อมูลปัจจุบัน
	$qryedittime=pg_query("select \"edittime\",\"statusApp\" from vthcap_fa_prebill_edit where \"prebillIDMaster\"='$prebillIDMaster'");
	list($edittime,$statusApp)=pg_fetch_array($qryedittime);
}

$request=1; //กำหนดค่าเพื่อไป check ใน frm_Detail.php

//หาว่า user มี level เท่าไหร่
$emplevel=emplevel($id_user);

//ตรวจสอบว่าบิลนี้ถูกยกเลิกหรือยัง
$qrycheckbill=pg_query("select \"prebillID\" from \"thcap_fa_prebill\" where \"prebillID\"='$prebillIDMaster'");
$numbill=pg_num_rows($qrycheckbill); //ถ้ายังพบอยู่แสดงว่ายังไม่ยกเลิก

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>รายละเอียดบิลขอสินเชื่อ</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
	<link type="text/css" rel="stylesheet" href="act.css"></link>
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
	<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script language="javascript">
$(document).ready(function(){
//กรณียกเลิกบิล
	$("#cancelbutton").click(function(){
		if(confirm('ยืนยันการยกเลิกบิลอีกครั้ง')){
			$.post("process_fa.php",{
				method : "cancel" , 
				prebillIDMaster : '<?php echo $prebillIDMaster;?>',
			},
			function(data){
				if(data == "1"){
					alert("ยกเลิกรายการเรียบร้อย");
					opener.location.reload(true);
					self.close();
				}else if(data=="2"){
					alert("ผิดผลาด ไม่สามารถบันทึกได้!");
				}else{
					alert("รายการนี้ได้รับการยกเลิกไปก่อนหน้านี้แล้ว กรุณาตรวจสอบ!");
				}
			});
		}else{
			return false;
		}
	});
});
</script>
</head>
<body>
<?php
if($prebillIDMaster!=""){
	//ค้นหารหัสจาก prebillIDMaster
	$qryprebill=pg_query("select \"prebillID\" from thcap_fa_prebill_temp where \"prebillIDMaster\"='$prebillIDMaster' and \"edittime\"='$edittime' and \"statusApp\"='$statusApp'");
	list($prebillID)=pg_fetch_array($qryprebill);
	$numpre=pg_num_rows($qryprebill);
	if($numpre>0){
		//ถ้ามี level <= 1 จะสามารถยกเลิกบิลได้
		if($emplevel<=1 && $numbill==1 && $statusApp!=0){
			echo "<div style=\"width:740px;margin:0 auto;padding:10px;text-align:right;\"><input type=\"button\" id=\"cancelbutton\" value=\"ยกเลิกบิล\"></div>";
		}
		
		if($numbill==0 || $statusApp==0){
			echo "<div style=\"text-align:center;font-weight:bold;color:red;\"><h2>--บิลนี้ถูกยกเลิกแล้ว--</h2></div>";
		}
		
		//ดึงรายละเอียดบิลมาแสดง
		echo "<div>";		
		include "frm_Detail.php";	
		echo "</div>";

		//ดึงรายละเอียดตรวจสอบบิลมาแสดง
		$qrydetail=pg_query("select \"statusSellBuy\", \"ruleSellBuy\", \"cusContact\", \"cusPost\", 
		\"cusTel\", \"dateContact\", a.note,\"appUserName\",\"appStamp\"
		from thcap_fa_prebill_appdetail a
		left join vthcap_fa_prebill_temp b on a.\"prebillID\"=b.\"prebillID\"
		where \"prebillIDMaster\"='$prebillIDMaster' and a.\"prebillID\" is not null");
		$numdetail=pg_num_rows($qrydetail);
		
		if($numdetail>0){
			$resde=pg_fetch_array($qrydetail);
			?>
			<div style="padding:5px;"></div>
			<div style="width:750px;margin:0px auto;">
				<fieldset><legend><B>รายละเอียดตรวจสอบบิล</B></legend>
					<table width="80%" border="0" cellSpacing="0" cellPadding="0" align="center" bgcolor="#FFE4B5">
						<tr>
							<td bgcolor="#FFE4B5">
								<div style="padding:13px 5px 0px 5px;">
									<div style="float:left;"><font>ผู้บันทึกการติดต่อ: <b><?php echo $resde["appUserName"]; ?></div>
									<div style="float:right;"></b>วัน/เวลา: <b><?php echo $resde["appStamp"]; ?></b></div>														
								</div>	
								<div style="clear:both;"></div>
							</td>
						</tr>
						<tr><td bgcolor="#FFE4B5"><font>&nbsp;</td></tr>
						<tr align="left" bgcolor="#FFEFD5">
							<td>
								<div style="padding-left:10px"><b>บิลนี้ตรวจสอบแล้วมีการซื้อขายจริงหรือไม่?</b></div>
								<div style="padding-left:20px"><input type="radio" name="chksell" id="chksell1" <?php if($resde["statusSellBuy"]==1) echo "checked";?> disabled>ตรวจสอบแล้วมีการซื้อขายจริง</div>
								<div style="padding-left:20px"><input type="radio" name="chksell" id="chksell2" <?php if($resde["statusSellBuy"]==2) echo "checked";?> disabled>ตรวจสอบแล้วไม่มีการซื้อขายจริง</div>
								<div><hr></div>
							</td>
						</tr>
						<?php 
						//กรณีมีการซื้อขายจริงให้แสดงส่วนนี้ด้วย
						if($resde["statusSellBuy"]==1){
						?>
						<tr align="left" bgcolor="#FFEFD5" id="show">
							<td>
								<div style="padding-left:10px"><b>ในกรณีที่มีการซื้อขายจริง แต่ผิดข้อกำหนดบริษัทข้อใดหรือไม่?</b></div>
								<div style="padding-left:20px"><input type="radio" name="rule" id="rule1" value="1" <?php if($resde["ruleSellBuy"]==1) echo "checked";?> disabled>ไม่ผิดข้อกำหนด (ซื้อขายจริง ได้รับสินค้าครบถ้วนแล้ว)</div>
								<div style="padding-left:20px"><input type="radio" name="rule" id="rule2" value="2" <?php if($resde["ruleSellBuy"]==2) echo "checked";?> disabled>ซื้อขายจริง แต่ยังไม่ได้รับสินค้า</div>
								<div style="padding-left:20px"><input type="radio" name="rule" id="rule3" value="3"	<?php if($resde["ruleSellBuy"]==3) echo "checked";?> disabled>มีการคืนสินค้าบางส่วนหรือทั้งหมด</div>
								<div style="padding-left:20px"><input type="radio" name="rule" id="rule4" value="4" <?php if($resde["ruleSellBuy"]==4) echo "checked";?> disabled>ยกเลิกการซื้อแล้วเนื่องจากมีปัญหาสินค้า</div>
								<div style="padding-left:20px"><input type="radio" name="rule" id="rule5" value="5" <?php if($resde["ruleSellBuy"]==5) echo "checked";?> disabled>ซื้อขายจริง แต่ยังได้รับสินค้าไม่ครบ</div>
								<div><hr></div>
							</td>
						</tr>
						<?php }?>
						<tr align="left" bgcolor="#FFEFD5">
							<td><div style="padding:5px;"><b>ผู้ที่ติดต่อในการสอบถามข้อมูล</b> : <input type="text" name="cusContact" id="cusContact" size="40" value="<?php echo $resde["cusContact"];?>" readonly="true"><div></td>
						</tr>
						<tr align="left" bgcolor="#FFEFD5">
							<td><div style="padding:5px;"><b>ตำแหน่งของผู้ที่ติดต่อ</b> : <input type="text" name="cusPost" id="cusPost" size="30" value="<?php echo $resde["cusPost"];?>" readonly="true"></div></td>
						</tr>
						<tr align="left" bgcolor="#FFEFD5">
							<td><div style="padding:5px;"><b>เบอร์ของผู้ที่ติดต่อ</b> : <input type="text" name="cusTel" id="cusTel" size="30" value="<?php echo $resde["cusTel"];?>" readonly="true"></div></td>
						</tr>
						<tr align="left" bgcolor="#FFEFD5">
							<td><div style="padding:5px;"><b>วันและเวลาที่ติดต่อโดยประมาณ</b> : <input type="text" name="dateContact" id="dateContact" size="30" value="<?php echo $resde["dateContact"];?>" readonly="true"></div></td>
						</tr>
						<tr align="left" bgcolor="#FFEFD5">
							<td valign="top"><div style="padding-left:5px;"><b>:::หมายเหตุ:::</b></div></td>
						</tr>
						<tr align="left" bgcolor="#FFEFD5">
							<td valign="top"><div style="padding-left:5px;"><textarea name="note" id="note" cols="40" rows="3" readonly="true"><?php echo $resde["note"];?></textarea></div></td>
						</tr>
						<tr align="center">
							<td colspan=3 height="30"></td>
						</tr>
					</table>
				</fieldset> 
			</div>
	<?php
		}else{
			echo "<div style=\"text-align:center;padding-top:10px;\"><h2>-- รายการนี้ถูกยกเลิกบิล --</h2></div>";
		}
		//ดึงรูปบิลมาแสดง
		echo "<div>";
		include "frm_Picbill.php";
		echo "</div>";
	}else{
		echo "<div style=\"text-align:center;padding:20px;\"><h1>---ไม่พบข้อมูล กรุณาระบุข้อมูลหรือตรวจสอบอีกครั้ง---</h1></div>";
	}
}else{
	echo "<div style=\"text-align:center;padding:20px;\"><h1>---ไม่พบข้อมูล กรุณาระบุข้อมูลหรือตรวจสอบอีกครั้ง---</h1></div>";
}
?>
<script type="text/javascript" src="scripts/jquery-1.8.2.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</body>
</html>
