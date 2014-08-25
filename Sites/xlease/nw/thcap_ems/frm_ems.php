<?php 
include("../../config/config.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$id =pg_escape_string($_GET['id']);
$id0 =pg_escape_string($_GET['id0']);
$type =pg_escape_string($_GET['type']);

if($type=='0'){//ข้อมูลหลัก
	$qry=pg_query("select auto_id as \"id\",\"contractID\",\"cusName\" as \"cusname\",\"sendDate\",\"addressCon\", \"id_user\" from \"thcap_letter_send\" where auto_id in (select auto_id from \"thcap_letter_send\" where type_send='E' and \"emsnumber\"  is null) and type_send='E' and \"emsnumber\"  is  null  and auto_id ='$id' ");
}
else if($type=='1'){
	$qry=pg_query("select \"sendID\" as \"id\",\"contractID\",\"receiveName\" as \"cusname\",\"sendDate\",a.\"addrCus\" as \"addressCon\", b.\"id_user\" from \"thcap_letter_detail\" a left join \"thcap_letter_send\" b on b.auto_id=a.\"sendID\"
	 where \"sendID\" in (select \"sendID\" from \"thcap_letter_detail\"  where type_send='E' and \"emsnumber\"  is  null ) 
	 and a.type_send='E' and a.\"emsnumber\"  is  null  and  a.\"auto_id\" ='$id0'");
}
$res=pg_fetch_array($qry);
$contractID=$res["contractID"];
$address=$res["addressCon"];
$cusname=$res["cusname"];
?>
<script type="text/javascript">
function checkdata(){
	if(document.getElementById('ems_back').value == ""){
			alert("กรุณากรอกเลขทะเบียน");
			document.getElementById('ems_back').focus();
			return false;
		}
}
function KeyCode(objId)
{
	   var key;
	   key = objId.which; // Firefox 
	   if ((key >= 0  && key<= 127) && key != 13) //48-57(ตัวเลข) ,65-90(Eng ตัวพิมพ์ใหญ่ ) ,97-122(Eng ตัวพิมพ์เล็ก) , 95(_)
	   {
		  
	   }else if(key == 13){ //Enter
			key = objId.preventDefault();   
	   }
	   else
	   {
		  key = objId.preventDefault();
	   }	  
}
</script>
<body>
<center><h2>(THCAP)ใส่เลขที่ EMS</h2></center>	
<form method="post" name="form1" action="process_ems.php">
<table align="center">
	<tr>
		<td>
			<b>เลขที่สัญญา :</b>
		</td>
		<td>
			<b><font color="red"><?php echo $contractID;?></font> </b>
		</td>
	</tr>
	<tr>
		<td>
			<b>ชื่อ - นามสกุล:</b>
		</td>
		<td>
			<b><font color="red"><?php echo $cusname;?></font> </b>
		</td>
	</tr>
	<tr>
		<td>
			<b>ที่อยู่ที่ติดต่อส่งเอกสาร :</b>
		</td>
		<td>
			<textarea cols="80" rows="3" name="addrresscon" readonly><?php echo $address;?></textarea>
			
		</td>
	</tr>
	<tr>
		<td>
			<b>เลข EMS:</b><font color="red">*</font>
		</td>
		<td>
			<input onkeypress="KeyCode(event);"  type="text" name="ems_back" id="ems_back" size="25" maxlength="13">
		</td>
	</tr>
	<tr>	 
	<input type="hidden" name="type" value="<?php echo $type;?>">
	<input type="hidden" name="id" value="<?php echo $id;?>">
	<input type="hidden" name="id0" value="<?php echo $id0;?>">
	<td align="center" colspan="2">
	<input type="submit" value="บันทึก" onclick="return checkdata();">
	<input type="button" value="ปิด" onclick="window.close()">
 	</td>
	</tr>
</table>
</form>
</body>
