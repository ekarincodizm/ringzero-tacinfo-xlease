<?php
include("../../config/config.php");
$contractID = pg_escape_string($_GET['contractID']);
$autoapp = pg_escape_string($_GET["autoapp"]);
if(empty($contractID)){
    exit;
}
//ตรวจสอบว่าเลขที่สัญญานี้กำลังรออนุมัีติอยู่หรือไม่
$qry_check=pg_query("select \"contractID\", \"withContractEdit\" from \"thcap_addrContractID_temp\"
					where \"contractID\" = '$contractID' and \"statusApp\" = '2' and \"addsType\"='3'");
$contractID_chk = pg_fetch_result($qry_check,0);
$withContractEdit_chk = pg_fetch_result($qry_check,1);
$num_check=pg_num_rows($qry_check); //ถ้าพบว่าไม่มีค่าแล้ว แสดงว่าได้ถูกอนุมัติไปแล้ว
echo "<hr color=#FF9999>";
if($num_check == 0){ //ยังไม่มีการรออนุมัติ
//แสดงที่อยู่ส่งเอกสารในสัญญา
$qryaddr=pg_query("select * from \"thcap_addrContractID\" WHERE \"contractID\"='$contractID' and \"addsType\"='3'");
if($resaddr=pg_fetch_array($qryaddr)){   
	$A_NO=$resaddr["A_NO"];
	$A_SUBNO=$resaddr["A_SUBNO"];
	$A_BUILDING=$resaddr["A_BUILDING"];
	$A_ROOM=$resaddr["A_ROOM"];
	$A_FLOOR=$resaddr["A_FLOOR"];
	$A_VILLAGE=$resaddr["A_VILLAGE"];
	$A_SOI=$resaddr["A_SOI"];
	$A_RD=$resaddr["A_RD"];
	$A_TUM=$resaddr["A_TUM"];
	$A_AUM=$resaddr["A_AUM"];
	$A_PRO=$resaddr["A_PRO"];
	$A_POST=$resaddr["A_POST"];
	$filerequest=$resaddr["filerequest"];
}
$qry_cus=pg_query("select \"CusID\" from \"thcap_ContactCus\" WHERE \"contractID\"='$contractID' and \"CusState\"='0' limit 1");
if($resaddr1=pg_fetch_array($qry_cus)){   
	$CusID=$resaddr1["CusID"];
	
}

?>
<script type="text/javascript">
$(document).ready(function(){
	// $("#submitadd").click(function(){ 
		// if($("#request_file").val()==""){
			// alert("กรุณาระบุใบคำขอแก้ไขที่อยู่สัญญา!!");
			// return false;
		// }else{
			// return true;
		// }
	// });
	
	$("#effectiveDate").datepicker({
		showOn: 'button',
		buttonImage: 'images/calendar.gif',
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd'
	});
});

function checkempty(){

	var mess_error = "กรุณาระบุข้อมูลให้ครบด้วย \n";
	status = 0;
	if(document.form1.f_no.value == ""){
		mess_error += "\t- บ้านเลขที่ \n";
		status = status + 1;	
	}
	if(document.form1.f_subno.value == ""){
		if($('#f_subnochk').attr( 'checked')==false){	
			mess_error += "\t- หมู่ \n";
			status = status + 1;
		}	
	}
	if(document.form1.f_soi.value == ""){
		if($('#f_soichk').attr( 'checked')==false){	
			mess_error += "\t- ซอย \n";
			status = status + 1;
		}		
	}
	if(document.form1.f_rd.value == ""){
		if($('#f_rdchk').attr( 'checked')==false){	
			mess_error += "\t- ถนน \n";
			status = status + 1;
		}
	}
	if(document.form1.f_tum.value == ""){
		mess_error += "\t- แขวง/ตำบล \n";
		status = status + 1;	
	}
	if(document.form1.f_aum.value == ""){
		mess_error += "\t- เขต/อำเภอ \n";
		status = status + 1;	
	}
	if(document.form1.A_PRO.value == ""){
		mess_error += "\t- จังหวัด \n";
		status = status + 1;	
	}
	if($("#request_file").val()==""){
		mess_error += "\t- ใบคำขอแก้ไขที่อยู่สัญญา \n";
		status = status + 1;	
	}
	if(document.form1.f_post.value == ""){
		if($('#f_postchk').attr( 'checked')==false){	
			mess_error += "\t- รหัสไปรษณีย์ \n";
			status = status + 1;
		}	
	}
	if($("#effectiveDate").val()==""){
		mess_error += "\t- วันที่ที่มีผลบังคับใช้ \n";
		status = status + 1;	
	}
	
	if(status == 0){
		if(confirm("ยืนยันการแก้ไขที่อยู่สัญญา")==true){
			return true;
		}else{
			return false;
		}

	}else{
		alert(mess_error);
		return false;
	}

}

</script>
<form method="post" name="form1" action="process_EditAddress.php" enctype="multipart/form-data">
<div style="width:600px;text-align:left;padding-top:10px;"><b>เลขที่สัญญา : <span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="#0000FF"><u><?php echo $contractID;?></u></font></span></div>
<table width="600" cellSpacing="1" cellPadding="1" border="0">
<tr valign="top">
	<td colspan="2">
		<table width="600" border="0" cellpadding="1" cellspacing="1" bgcolor="#FF9999" align="center">	
		<tr>
			<td align="right" bgcolor="#FFCCCC">ห้อง :</td>
			<td bgcolor="#FFF9F9"><input type="text" name="f_room" value="<?php echo $A_ROOM; ?>"/></td>
		</tr>
		<tr>
			<td align="right" bgcolor="#FFCCCC">ชั้น :</td>
			<td bgcolor="#FFF9F9"><input type="text" name="f_floor" value="<?php echo $A_FLOOR; ?>"/></td>
		</tr>
		<tr>
			<td align="right" bgcolor="#FFCCCC">เลขที่ :</td>
			<td bgcolor="#FFF9F9"><input type="text" name="f_no" value="<?php echo $A_NO; ?>"/><font color="red" >*</font></td>
		</tr>
		<tr>
			<td align="right" bgcolor="#FFCCCC">หมู่ที่ :</td>
			<td bgcolor="#FFF9F9"><input type="text" <?php if(trim($A_SUBNO) == ""){ echo "disabled";} ?> name="f_subno" value="<?php echo $A_SUBNO; ?>"/><font color="red" >*</font>
			<input type="checkbox" id="f_subnochk" <?php if(trim($A_SUBNO) == ""){ echo "checked";} ?>  onClick="javaScript:if(this.checked){document.form1.f_subno.disabled=true;document.form1.f_subno.value='';}else{document.form1.f_subno.disabled=false;}">ไม่มีข้อมูล
			</td>
		</tr>
		<tr>
			<td align="right" bgcolor="#FFCCCC">หมู่บ้าน :</td>
			<td bgcolor="#FFF9F9"><input type="text" name="f_ban" value="<?php echo $A_VILLAGE; ?>" size="50"/></td>
		</tr>
		<tr>
			<td align="right" bgcolor="#FFCCCC">อาคาร/สถานที่ :</td>
			<td bgcolor="#FFF9F9"><input type="text" name="f_building" value="<?php echo $A_BUILDING; ?>" size="50"/></td>
		</tr>
		<tr>
			<td align="right" bgcolor="#FFCCCC">ซอย :</td>
			<td bgcolor="#FFF9F9"><input type="text"  <?php if(trim($A_SOI) == ""){ echo "disabled";} ?> name="f_soi" value="<?php echo $A_SOI; ?>"/><font color="red" >*</font>
			<input type="checkbox" id="f_soichk"  <?php if(trim($A_SOI) == ""){ echo "checked";} ?> onClick="javaScript:if(this.checked){document.form1.f_soi.disabled=true;document.form1.f_soi.value='';}else{document.form1.f_soi.disabled=false;}">ไม่มีข้อมูล
			</td>
		</tr>
		<tr>
			<td align="right" bgcolor="#FFCCCC">ถนน :</td>
			<td bgcolor="#FFF9F9"><input type="text" <?php if(trim($A_RD) == ""){ echo "disabled";} ?> name="f_rd" value="<?php echo $A_RD; ?>"/><font color="red" >*</font>
			<input type="checkbox" id="f_rdchk" <?php if(trim($A_RD) == ""){ echo "checked";} ?> onClick="javaScript:if(this.checked){document.form1.f_rd.disabled=true;document.form1.f_rd.value='';}else{document.form1.f_rd.disabled=false;}">ไม่มีข้อมูล
			</td>
		</tr>
		<tr>
			<td align="right" bgcolor="#FFCCCC">แขวง/ตำบล :</td>
			<td bgcolor="#FFF9F9"><input type="text" name="f_tum" value="<?php echo $A_TUM; ?>"/><font color="red" >*</font></td>
		</tr>
		<tr>
			<td align="right" bgcolor="#FFCCCC">เขต/อำเภอ :</td>
			<td bgcolor="#FFF9F9"><input type="text" name="f_aum" value="<?php echo $A_AUM; ?>"/><font color="red" >*</font></td>
		</tr>
		<tr>
			<td align="right" bgcolor="#FFCCCC">จังหวัด :</td>
			<td bgcolor="#FFF9F9">	
				<select name="A_PRO" size="1">
				<?php
				if($A_PRO==""){
					echo "<option>---เลือก---</option>";
				}else{
					echo "<option value=\"\"></option>";
				}
				$query_province=pg_query("select * from \"nw_province\"  order by \"proID\"");
				while($res_pro = pg_fetch_array($query_province)){
				?>
					<option value="<?php echo $res_pro["proName"];?>" <?php if($res_pro["proName"]==$A_PRO){?>selected<?php }?>><?php echo $res_pro["proName"];?></option>
					<?php
				}
				?>
				</select><font color="red" >*</font>	
			</td>
		</tr>
		<tr>
			<td align="right" bgcolor="#FFCCCC">รหัสไปรษณีย์ :</td>
			<td bgcolor="#FFF9F9"><input type="text" name="f_post" <?php if(trim($A_POST) == ""){ echo "disabled";} ?> value="<?php echo $A_POST; ?>" maxlength="5"/><font color="red" >*</font>
			<input type="checkbox" id="f_postchk" <?php if(trim($A_POST) == ""){ echo "checked";} ?> onClick="javaScript:if(this.checked){document.form1.f_post.disabled=true;document.form1.f_post.value='';}else{document.form1.f_post.disabled=false;}">ไม่มีข้อมูล
			</td>
		</tr>
		<tr>
			<td align="right" bgcolor="#FFCCCC">วันที่ที่มีผลบังคับใช้ :</td>
			<td bgcolor="#FFF9F9"><input type="text" name="effectiveDate" id="effectiveDate" value="" maxlength="5"/>&nbsp;<font color="red"><b>*</b></font>
			</td>
		</tr>
		<tr>
			<td  align="right" bgcolor="#FFCCCC">ใบคำขอแก้ไขที่อยู่สัญญา</td>
			<td bgcolor="#FFF9F9">
				<input type="file" size="32" name="request_file[]" id="request_file"/>&nbsp;<font color="red"><b>*</b></font>
				<?php if($filerequest!=""){ ?>
				<a href="upload_chgcontractadds/<?php echo $filerequest;?>" target="_blank"><u>(ใบคำขอล่าสุด)</u></a>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td  align="right" bgcolor="#FFCCCC">สัญญาที่เกี่ยวข้อง</td>
			<td bgcolor="#FFF9F9">
			<?php
			$qry_cuscontract=pg_query("select * from \"thcap_ContactCus\" where \"CusID\" = '$CusID' and \"CusState\" = '0' and \"contractID\" != '$contractID' order by \"contractID\" ");
			$otherC = 0;
			$contractEditToo = "";
			while($res_pro1 = pg_fetch_array($qry_cuscontract))
			{
				$otherC++;
				$contractOther = $res_pro1["contractID"];
				
				if($otherC > 1)
				{
					echo " | ";
					$contractEditToo .= ",$contractOther";
				}
				else
				{
					$contractEditToo = $contractOther;
				}
			?>
				<span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $res_pro1["contractID"];?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $res_pro1["contractID"];?></u></font></span>
			<?php
			}
			?>
			<input type="hidden" name="contractEditToo" value="<?php echo $contractEditToo; ?>">
			</td>
		</tr>
		<tr>
		<td colspan="2">
		<center>
		<input type="radio" name="radio1" value="1" checked="true"/>แก้ไขที่อยู่เฉพาะสัญญานี้
		<input type="radio" name="radio1" value="2"/>แก้ไขที่อยู่ทุกสัญญาที่เกี่ยวข้อง
		</center>
		</td>
		</tr>
		</table>
	</td>
</tr>
<tr><td colspan="2" align="center" height="50">
<input type="hidden" name="contractID" value="<?php echo $contractID;?>">
<input type="hidden" name="method" value="add">
<!-- หากมีคำสั่งให้อนุมัติเลยโดยไม่ต้องรอ -->
<input type="hidden" name="autoapp" value="<?php echo $autoapp; ?>">

<input type="submit" value="บันทึก" id="submitadd" onclick="return checkempty();"></td></tr>
</table>
</form>
<?php 
}else{ //กรณีมีข้อมูลรออนุมัติ
	// ถ้าเป็นรายการที่เกี่ยวข้อง
	if($withContractEdit_chk != "")
	{
		$qry_withContractEdit_main = pg_query("select \"contractID\" from \"thcap_addrContractID_temp\" where \"tempID\" = '$withContractEdit_chk' ");
		$withContractEdit_main = pg_fetch_result($qry_withContractEdit_main,0);
		
		$conMain = "พร้อมกับเลขที่สัญญา $withContractEdit_main";
	}
	
	echo "<div style=\"text-align:center\"><h2>รายการนี้กำลังรออนุมัติ $conMain กรุณาตรวจสอบ</h2>";
}
?>