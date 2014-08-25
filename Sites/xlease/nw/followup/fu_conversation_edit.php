<?php
include("../../config/config.php");
session_start();
$id_userses = $_SESSION["av_iduser"];

$CONTID = pg_escape_string($_GET['CONTID']);


?>
 <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />   
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){ 
	
$("#empdep").hide();

 $('#btncontadd').click(function(){
        $("#panel3").load("fu_tag_edit.php?TAGID=i");
    });
	

});

function checkdep()
{
	$("#empdep").show();
	$("#empdep1").load("empdep_data.php?depID="+$("#dep").val());
	$("#empdep2").hide();
}

function checkList()
{
if(document.getElementById("tb_conname").value=="")
{
alert('กรุณากรอก ชื่อเรื่องการสนทนา  หากไม่่มีข้อมูลให้ใช้เครื่องหมาย -');
return false;
}
if(document.getElementById("tb_condetail").value=="")
{
alert('กรุณากรอก รายละเอียดการสนทนา หากไม่่มีข้อมูลให้ใช้เครื่องหมาย -');
return false;
}
if(document.getElementById("empname").value=="")
{
alert('กรุณากรอก รายละเอียดการสนทนา หากไม่่มีข้อมูลให้ใช้เครื่องหมาย -');
return false;
}
else
{
return true;
}
}

</script>


<div class="ui-widget" align="left">
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<?php
if($CONTID != "" && $CONTID !="i"){

$qry_name=pg_query("select *,fcon.\"id_user\" as user from \"fu_conversation\" fcon
inner join \"fu_company\" fc on fcon.\"comID\" = fc.\"comID\"
inner join \"fu_empcontact\" fe on fcon.\"empconID\" = fe.\"empconID\"
WHERE fcon.\"conID\" = '$CONTID'");
$result=pg_fetch_array($qry_name);
$nrows=pg_num_rows($qry_name);
if($nrows == 0){
	echo "<script type='text/javascript'>alert('ขออภัย ไม่พบข้อมูลที่ท่านค้นหา')</script>";
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php\">";
	
	exit();
}
$contID=$CONTID;
$name=trim($result["con_name"]);
$comname=trim($result["com_name"]);
$empname=trim($result["empcon_name"]);
$emplname=trim($result["empcon_lname"]);
$empID=trim($result["empconID"]);
$comID=trim($result["comID"]);
$detail=trim($result["con_detail"]);
$date=trim($result["con_date"]);
$id_user=trim($result["user"]);
$thaiacename=trim($result["fullname"]);


}else if($CONTID == "i"){
$name="";
$contID="";
$comname="";
$empname="";
$emplname="";
$empID="";
$comID="";
$detail="";
$date="";
$id_user="";
$thaiacename="";
$date= date("Y-m-d H:m:s");


}else{
	echo "<hr width=850>";
	echo "<center><h1>ไม่พบข้อมูล</h1></center>";
	exit();
}?>
<body>
<form name="frm" method="post" action="fu_conversation_edit_query.php">
<center><legend><h2> แก้ไขการสนทนา </h2></legend></center>
<hr width="850">
<table width="850" cellSpacing="1" cellPadding="3" border="0" bgcolor="#D7F0FD" align="center">
<tr bgcolor="#BCE6FC">
    <td width="150" height="25" align="right"><b>รหัสการสนทนา:</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$contID"; ?></td>
	<input type="hidden" name="hd_conid" id="hd_conid" value="<?php echo "$contID"; ?>">
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top"  align="right"><b>ชื่อการสนทนา :</b></td>
    <td bgcolor="#FFFFFF"><input type="text" name="tb_conname" id="tb_conname" value="<?php echo "$name"; ?>">*</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>รายละเอียดการสนทนา:</b></td>
    <td bgcolor="#FFFFFF"><textarea rows="15" cols="100" id="tb_condetail" name="tb_condetail"><?php echo "$detail"; ?></textarea>*
	</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>บริษัทที่สนทนา :</b></td>
	<td bgcolor="#FFFFFF" onclick="javascript:popU('fu_company_data.php?COMID=<?php echo "$comID"; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
    <?php echo "$comname"; ?></td>

		  </td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>ชื่อพนักงานที่สนทนา:</b></td>
    <td bgcolor="#FFFFFF">
	<select name="empname">
			<option value="<?php echo "$empID"; ?>"><?php echo "$empname"." "."$emplname"; ?></option>
			<?php
			$objQuery2 = pg_query("SELECT * FROM \"fu_empcontact\" where \"empconID\" != '$empID' and \"comID\" = '$comID' order by \"empconID\"");
			while($objResuut2 = pg_fetch_array($objQuery2))
			{ 
			?>
			<option value="<?php echo $objResuut2["empconID"]; ?>"><?php echo $objResuut2["empcon_name"];?></option>
			
			<?php } ?>		
		  </select>*</td>
		  </tr>
<tr bgcolor="#BCE6FC">
<?php 


$qry_name=pg_query("select * from \"Vfuser\" WHERE \"id_user\" = '$id_user'");
$result=pg_fetch_array($qry_name); 
$thaiacename1 = $result["fullname"];

?>

    <td valign="top" height="35" align="right"><b>พนักงานที่สนทนา ของ Thaiace  :</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$thaiacename1"." "; ?>(<?php echo "$id_user"; ?>)</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>วันที่สนทนา:</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$date"; ?></td>
</tr>
<tr bgcolor="#BCE6FC">
<td valign="top" height="35" align="right"><b>สิทธิ์ในการมองเห็น :</b></td>
<td bgcolor="#FFFFFF">
		<?php 

	$qry_name3=pg_query("select * from \"fu_conversation_emp\" WHERE \"conID\" = '$contID'");
	
		while($result3=pg_fetch_array($qry_name3)){
			$iduserr = $result3["id_user"];
			
				$qry_name4=pg_query("select * from \"Vfuser\" WHERE \"id_user\" = '$iduserr'");
				$result4=pg_fetch_array($qry_name4); 
				$thaiacename2 = $result4["fullname"];
				
				if($iduserr == 'allemp'){
					$thaiacename2 = 'ทุกคนในบริษัท';
				}
				
				echo $thaiacename2." ".":"." "; 
				
				}
		?>

    
   </td>
</tr>
<?php if($id_user == $id_userses ){ ?>
		<tr bgcolor="#BCE6FC">
			<td width="150" height="25" align="right"><b>ต้องการให้ใครเห็นบ้าง</b></td>
			<td bgcolor="#FFFFFF">
				<select name="dep" id="dep" onchange="javascript:checkdep()">
												<option  selected value="none">---ไม่มีเปลี่ยนแปลง---</option>
												<option  value="allemp">---ทั้งหมด ---</option>
									
											<?php $objdep= pg_query("SELECT * FROM \"f_department\" order by \"fdep_id\"");
												while($Redep = pg_fetch_array($objdep)){ ?>
					
												<option value="<?php echo $Redep["fdep_id"]; ?>"><?php echo $Redep["fdep_name"];?></option>
					
											<?php } ?>		
									</select></td>
		</tr>
		<tr bgcolor="#BCE6FC" name="empdep" id="empdep">
			<td valign="top" align="right"><b>ชื่อพนักงาน:</b></td>
			<td bgcolor="#FFFFFF"><div style="width: 600px; height: 300px; overflow: auto;">
							<span id="empdep1"></span>
							</div>
							<span id="empdep2">-- ยังไม่มีพนักงาน --</span>	
						</td>
					
		</tr>	
<?php } ?>
<td></td>
    <td bgcolor="#FFFFFF"><input height="35" type="submit" value=" บันทึก " style="width:100px; height:30px;" onclick="return checkList();"></td>
</tr>
</table>
<div id="panel3" style="padding-top: 10px;"></div>
</body>
</form>

