<?php
include("../config/config.php");
$id = pg_escape_string($_GET['idno']);
$CusID = pg_escape_string($_GET['CusID']);
$CusState = pg_escape_string($_GET['CusState']);
$statusedit = pg_escape_string($_GET['statusedit']); //สถานะกรณีที่แก้ไขจากหน้าส่งจดหมาย
$FrmState= pg_escape_string($_GET['FrmState']); //สถานะ การคลิก Link จากเมนู นัดตรวจรถ
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>แก้ไขที่อยู่ส่งจดหมาย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
$(document).ready(function(){
	$("#a1").hide();
	$("#editidno1").click(function(){
		$("#a1").hide();
	});
	
	$("#editidno2").click(function(){
		$("#a1").show();
	});
	
	$("#cancel").click(function(){
		$("#a1").hide();
	});
});
function checkpass(){
	if(document.form1.pass.value==""){
		alert("กรุณากรอกรหัสผ่านเพื่อบันทึกข้อมูล");
		document.form1.pass.focus();
		return false;
	}else if(document.form1.pass.value != document.form1.IDNO.value){
		alert("รหัสผ่านไม่ถูกต้องกรุณากรอกใหม่อีกครั้ง");
		document.form1.pass.select();
		return false;
	}
}
</script>

</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<?php
		if(($statusedit!="1")and($statusedit!="2")){
		?>
		<div style="float:left"><input type="button" value="กลับ" onclick="window.location='frm_lt_edit.php'"></div>
		<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
		<div style="clear:both; padding-bottom: 10px;"></div>        
		<?php
		}
		//	<!--โดยไม่ให้แสดง ปุ่ม "กลับ" ในกรณี ที่ มีการ link มาจาก เมนู นัดตรวจรถ -->
		if ($statusedit=="2")
		{?>
			<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
		<div style="clear:both; padding-bottom: 10px;"></div>   
		<?php
		}?>
		<fieldset><legend><B>แก้ไขที่อยู่ส่งจดหมาย</B></legend>
			<div class="ui-widget" align="left">
			<?php
			//ตรวจสอบว่าเป็นผู้เช่าซื้อหรือผู้ค้ำ
			if($CusState=="0"){
				$txtcus="ผู้เช่าซื้อ";
			}else{
				$txtcus="ผู้ค้ำคนที่ $CusState";
			}
			//ค้นหาเลขที่สัญญาที่ลูกค้าคนนี้เป็นผู้เช่าซื้อ
			$query_name2 = pg_query("select \"IDNO\" from \"ContactCus\" WHERE \"CusID\"='$CusID' and \"CusState\" ='0'");
			$num_name2 = pg_num_rows($query_name2);

			$nub = 1;
			while($res_name2=pg_fetch_array($query_name2)){
				$IDNO=$res_name2["IDNO"]; 
				if($nub == $num_name2){
					$IDNO = "<a href=\"#\" onclick=\"javascript:popU('../post/frm_viewcuspayment.php?idno_names=$IDNO&type=outstanding','$IDNO_sdasdsadsa; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางการชำระเงิน\"><U>$IDNO</U></a>";
					$sumIDNO= $sumIDNO.$IDNO;
				}else{
					$IDNO = "<a href=\"#\" onclick=\"javascript:popU('../post/frm_viewcuspayment.php?idno_names=$IDNO&type=outstanding','$IDNO_sdasdsadsa; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางการชำระเงิน\"><U>$IDNO</U></a>";
					if($nub%7 == 0){
						$addbr = "<br>";
					}else{
						$addbr = "";
					}
					$sumIDNO= $sumIDNO.$IDNO.",$addbr";
				}
				$nub++;
			}
			//ค้นหาเลขที่สัญญาที่ลูกค้าคนนี้เป็นผู้ค้ำ
			$query_name3 = pg_query("select \"IDNO\" from \"ContactCus\" WHERE \"CusID\"='$CusID' and \"CusState\" != '0'");
			$num_name3 = pg_num_rows($query_name3);

			$nub2 = 1;
			while($res_name3=pg_fetch_array($query_name3)){
				$IDNO2=$res_name3["IDNO"]; 
				if($nub2 == $num_name3){
					$IDNO2 = "<a href=\"#\" onclick=\"javascript:popU('../post/frm_viewcuspayment.php?idno_names=$IDNO2&type=outstanding','$IDNO_sdasdsadsa; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางการชำระเงิน\"><U>$IDNO2</U></a>";
					$sumIDNO2= $sumIDNO2.$IDNO2;
				}else{
					$IDNO2 = "<a href=\"#\" onclick=\"javascript:popU('../post/frm_viewcuspayment.php?idno_names=$IDNO2&type=outstanding','$IDNO_sdasdsadsa; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางการชำระเงิน\"><U>$IDNO2</U></a>";
					if($nub2%7 == 0){
						$addbr = "<br>";
					}else{
						$addbr = "";
					}
					$sumIDNO2= $sumIDNO2.$IDNO2.",$addbr";
				}
				$nub2++;
			}
			
			//หาที่อยู่ในสัญญาล่าสุดเพื่อนำมาแสดง
			$qry_name=pg_query("SELECT C.\"CusID\",C.\"A_FIRNAME\",C.\"A_NAME\",C.\"A_SIRNAME\",B.\"N_ContactAdd\",
				D.\"A_NO\",D.\"A_SUBNO\",D.\"A_SOI\",D.\"A_RD\",D.\"A_TUM\",D.\"A_AUM\",D.\"A_PRO\",D.\"A_POST\",D.\"addEach\",
				D.\"A_ROOM\",D.\"A_FLOOR\",D.\"A_BUILDING\",D.\"A_BAN\"
				FROM  \"Fp\"  A
				LEFT OUTER JOIN \"Fn\" B ON B.\"CusID\"=A.\"CusID\"
				LEFT OUTER JOIN \"Fp_Fa1\" D ON A.\"IDNO\"=D.\"IDNO\" and \"CusState\"='$CusState'
				LEFT OUTER JOIN \"Fa1\" C ON C.\"CusID\"=D.\"CusID\"
				WHERE A.\"IDNO\"='$id' order by \"edittime\" DESC limit 1");

			if($res_name=pg_fetch_array($qry_name)){
				$A_CusID=trim($res_name["CusID"]);
				$A_FIRNAME=trim($res_name["A_FIRNAME"]);
				$A_NAME=trim($res_name["A_NAME"]);
				$A_SIRNAME=trim($res_name["A_SIRNAME"]);
				$A_NO=trim($res_name["A_NO"]);
				$A_SUBNO=trim($res_name["A_SUBNO"]);
				$A_SOI=trim($res_name["A_SOI"]);
				$A_RD=trim($res_name["A_RD"]);
				$A_TUM=trim($res_name["A_TUM"]);
				$A_AUM=trim($res_name["A_AUM"]);
				$A_PRO=trim($res_name["A_PRO"]);
				$A_POST=trim($res_name["A_POST"]);
				$A_ROOM=trim($res_name["A_ROOM"]);
				$A_FLOOR=trim($res_name["A_FLOOR"]);
				$A_BUILDING=trim($res_name["A_BUILDING"]);
				$A_BAN=trim($res_name["A_BAN"]);
				$addEach=trim($res_name["addEach"]);
						
				$name = $A_FIRNAME.$A_NAME." ".$A_SIRNAME;
					
			}
			?>
			<form name="form1" action="frm_lt_edit_detail_update.php" method="post" style="margin:0">
				<table width="60%" cellpadding="5" cellspacing="0" border="0" align="center">
				<tr>
					<td><b>เลขที่สัญญา:</b></td>
					<td><?php echo "$id"; ?></td>
				</tr>
				<tr>
					<td><b>ชื่อ/สกุล :</b></td>
					<td><?php echo "$name ($CusID-$txtcus)"; ?></td>
				</tr>
				<tr>
					<td valign="top"><b>เลขที่สัญญาที่ลูกค้าเป็นผู้เช่าซื้อ :</b></td>
					<td>
					<?php 
						if($sumIDNO == ""){
							echo "-ไม่พบข้อมูล-";
						}else{
							echo "$sumIDNO"; 
						}
					?>
					</td>
				</tr>
				<tr>
					<td valign="top"><b>เลขที่สัญญาที่ลูกค้าเป็นผู้ค้ำ :</b></td>
					<td>
					<?php 
						if($sumIDNO2 == ""){
							echo "-ไม่พบข้อมูล-";
						}else{
							echo "$sumIDNO2"; 
						} 
					?></td>
				</tr>
				<tr valign="top">
					<td colspan="2"><hr color="#FF9999" width="800" align="left"><br>
					<b>ที่อยู่ในเลขที่สัญญาปัจจุบัน</b>
					<table width="600" border="0" cellpadding="1" cellspacing="1" bgcolor="#FF9999" style="font-weight:bold">	
						<tr>
							<td align="right" bgcolor="#FFCCCC">ห้อง :</td>
							<td bgcolor="#FFECEC"><input type="text" name="f_room" value="<?php echo $A_ROOM; ?>"/></td>
						</tr>
						<tr>
							<td align="right" bgcolor="#FFCCCC">ชั้น :</td>
							<td bgcolor="#FFECEC"><input type="text" name="f_floor" value="<?php echo $A_FLOOR; ?>"/></td>
						</tr>
						<tr>
							<td align="right" bgcolor="#FFCCCC">เลขที่ :</td>
							<td bgcolor="#FFECEC"><input type="text" name="f_no" value="<?php echo $A_NO; ?>"/></td>
						</tr>
						<tr>
							<td align="right" bgcolor="#FFCCCC">หมู่ที่ :</td>
							<td bgcolor="#FFECEC"><input type="text" name="f_subno" value="<?php echo $A_SUBNO; ?>"/></td>
						</tr>
						<tr>
							<td align="right" bgcolor="#FFCCCC">หมู่บ้าน :</td>
							<td bgcolor="#FFECEC"><input type="text" name="f_ban" value="<?php echo $A_BAN; ?>" size="50"/></td>
						</tr>
						<tr>
							<td align="right" bgcolor="#FFCCCC">อาคาร/สถานที่ :</td>
							<td bgcolor="#FFECEC"><input type="text" name="f_building" value="<?php echo $A_BUILDING; ?>" size="50"/></td>
						</tr>
						<tr>
							<td align="right" bgcolor="#FFCCCC">ซอย :</td>
							<td bgcolor="#FFECEC"><input type="text" name="f_soi" value="<?php echo $A_SOI; ?>"/></td>
						</tr>
						<tr>
							<td align="right" bgcolor="#FFCCCC">ถนน :</td>
							<td bgcolor="#FFECEC"><input type="text" name="f_rd" value="<?php echo $A_RD; ?>"/></td>
						</tr>
						<tr>
							<td align="right" bgcolor="#FFCCCC">แขวง/ตำบล :</td>
							<td bgcolor="#FFECEC"><input type="text" name="f_tum" value="<?php echo $A_TUM; ?>"/></td>
						</tr>
						<tr>
							<td align="right" bgcolor="#FFCCCC">เขต/อำเภอ :</td>
							<td bgcolor="#FFECEC"><input type="text" name="f_aum" value="<?php echo $A_AUM; ?>"/></td>
						</tr>
						<tr>
							<td align="right" bgcolor="#FFCCCC">จังหวัด :</td>
							<td bgcolor="#FFECEC">	
								<select name="A_PRO" size="1">
									<?php
									if($A_PRO==""){
										echo "<option>---เลือก---</option>";
									}else{
										echo "<option value=$A_PRO>$A_PRO</option>";
									}
									$query_province=pg_query("select \"proName\" from \"nw_province\" where \"proName\" != '$A_PRO' order by \"proID\"");
									while($res_pro = pg_fetch_array($query_province)){
									?>
									<option value="<?php echo $res_pro["proName"];?>" <?php if($res_pro["proName"]==$A_PRO){?>selected<?php }?>><?php echo $res_pro["proName"];?></option>
									<?php
									}
									?>
								</select>	
							</td>
						</tr>
						<tr>
							<td align="right" bgcolor="#FFCCCC">รหัสไปรษณีย์ :</td>
							<td bgcolor="#FFECEC"><input type="text" name="f_post" value="<?php echo $A_POST; ?>" maxlength="5"/></td>
						</tr>
						</table>
					</td>
				</tr>
				<tr valign="top">
					<td colspan="2">
						<b>ที่อยู่้อื่นๆ</b><br>
						<textarea name="addreach" cols="60" rows="6"><?php echo $addEach;?></textarea>
					</td>
				</tr>
				<tr valign="top">
					<td colspan="2">
						<input type="radio" name="editidno" id="editidno1" value="1" checked>แก้ไขที่อยู่เฉพาะเลขที่สัญญานี้ &nbsp;&nbsp;&nbsp;
						<input type="radio" name="editidno" id="editidno2"value="2">แก้ไขที่อยู่ทุกเลขที่สัญญา
					</td>
				</tr>
				<tr valign="top" id="a1"><!--กรณีที่เลือกทุกเลขที่สัญญา ต้องระบุด้วยว่าจะแก้เฉพาะที่เป็นผู้เช่าซื้อหรือผู้ค้ำด้วย-->
					<td colspan="2">
						<table width="600" border="0" cellpadding="1" cellspacing="1" bgcolor="#B0E8FF" style="font-weight:bold">
						<tr><td>
						<?php if($CusState=="0"){?>
						<input type="radio" name="editcus" value="1" checked>เฉพาะที่เป็นผู้เช่าซื้อ &nbsp;&nbsp;&nbsp;
						<?php }else{?>
						<input type="radio" name="editcus" value="2" checked>เฉพาะที่เป็นผู้ค้ำ
						<?php }?>
						<input type="radio" name="editcus" value="3">ทั้งที่เป็นผู้เช่าซื้อและผู้ค้ำ
						</td></tr>
						</table>
					</td>
				</tr>
				<tr valign="top">
					<td colspan="2">
						<input type="checkbox" name="editfa1" value="1">แก้ไขที่อยู่ในข้อมูลลูกค้าด้วย
					</td>
				</tr>
				<tr>
					<td colspan="2" height="50"><hr color="#FF9999" width="800" align="left"><br>
						<b>กรอกรหัสผ่านเพื่อบันทึก</b> <input type="password" name="pass" id="pass">
						<input type="hidden" name="statusedit" id="statusedit" value="<?php echo $statusedit;?>">
						<input type="hidden" name="IDNO" id="idno" value="<?php echo $id;?>">
						<input type="hidden" name="CusID" value="<?php echo $CusID;?>">
						<input type="hidden" name="CusState" value="<?php echo $CusState;?>">
						<input type="submit" value="บันทึก" onclick="return checkpass();">
						<input type="reset" value="ยกเลิก" id="cancel">
					</td>
				</tr>
				</table>
			</form>
			</div>
		</fieldset>
	</td>
</tr>
</table>
</body>
</html>