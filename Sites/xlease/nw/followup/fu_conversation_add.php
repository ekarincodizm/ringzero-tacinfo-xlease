<?php
include("../../config/config.php");
session_start();
$id_user1 = $_SESSION["av_iduser"];

$CONTID = pg_escape_string($_GET['CONTID']);
$comid = pg_escape_string($_GET['comid']);

?>

<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){ 
	
	$("#empdep").hide();
	$("#tb1").hide();
	$("#chkanalyze").click(function(){ 
		if($('#chkanalyze') .attr( 'checked')==true){
			$("#tb1").show();
		}else{
			$("#tb1").hide();

		
		}
	});

});
$(document).ready(function(){	
	$("#alDate").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });

	$("#tagDate").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
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
alert('กรุณากรอก ชื่อเรื่องการสนทนา ');
return false;
}
if(document.getElementById("tb_condetail").value=="")
{
alert('กรุณากรอก รายละเอียดการสนทนา หากไม่่มีข้อมูลให้ใช้เครื่องหมาย -');
return false;
}
if(document.getElementById("company").value=="")
{
alert('กรุณากรอก รายละเอียดการสนทนา หากไม่่มีข้อมูลให้ใช้เครื่องหมาย -');
return false;
}
if(document.getElementById("empname1").value=="")
{
alert('กรุณากรอก รายละเอียดการสนทนา หากไม่่มีข้อมูลให้ใช้เครื่องหมาย -');
return false;
}
else
{
return true;
}
}

$(document).ready(function(){

   
    $('#btncontadd').click(function(){
        $("#panel3").load("fu_tag_edit.php?TAGID=i");
    });
	

});
</script>


<div class="ui-widget" align="left">
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<?php

$tagdate = nowDate();
$aldate = nowDate();
$date = nowDate();

?>

<body>
<center><legend><h2>เพิ่มการสนทนากับบริษัทลูกค้า</h2></legend></center>
<form name="frm" method="post" action="fu_conversation_query.php">

<hr width="850">
<table width="850" cellSpacing="1" cellPadding="3" border="0" bgcolor="#D7F0FD" align="center">
<tr bgcolor="#BCE6FC">
    <td width="150" height="25" align="right"><b>ชื่อการสนทนา :</b></td>
    <td bgcolor="#FFFFFF"><input type="text" name="tb_conname" id="tb_conname" value="">*</td>
</tr>

<input type="hidden" name="hdcheck" id="hdcheck">

<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>รายละเอียดการสนทนา:</b></td>
    <td bgcolor="#FFFFFF"><textarea name="tb_condetail" id="tb_condetail" rows="10" cols="80"></textarea>*
	</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>บริษัทที่สนทนา :</b></td>
  
			<?php
			$objQuery = pg_query("SELECT * FROM \"fu_company\" where \"comID\" = '$comid' order by \"comID\"");
			$objResuut = pg_fetch_array($objQuery);
			$commidd=$objResuut["comID"];
			$commname=$objResuut["com_name"];
			
			?>
			<td bgcolor="#FFFFFF" onclick="javascript:popU('fu_company_data.php?COMID=<?php echo $objResuut["comID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">
			 <?php echo $commname ?></td>
					
		
</tr>
<input type="hidden" id="hdcomid" name="hdcomid" value="<?php echo $objResuut["comID"]; ?>">
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>ชื่อผู้ติดต่อ:</b></td>
    <td bgcolor="#FFFFFF">
	<?php
			$objQuery1 = pg_query("SELECT * FROM \"fu_empcontact\" where \"comID\" = '$commidd' order by \"empconID\"");
			
			
			?>
	<select id="empname1" name="empname1">
		<?php while($objResuut1 = pg_fetch_array($objQuery1)){ ?>
	<option value="<?php echo $objResuut1["empconID"]; ?>"><?php echo $objResuut1["empcon_name"];?></option>
	
 <?php	} ?>
	</select>*</td>
	
		  </tr>
<tr bgcolor="#BCE6FC">
<?php 

$qry_name=pg_query("select * from \"Vfuser\" WHERE \"id_user\" = '$id_user1'");
$result=pg_fetch_array($qry_name); 
$thaiacename1 = $result["fullname"];

?>

    <td valign="top" height="35" align="right"><b>พนักงานที่สนาทนา ของ Thaiace  :</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$thaiacename1"." "; ?>(<?php echo "$id_user1"; ?>)</td>
	
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>วันที่สนทนา:</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$date"; ?></td>
</tr>
<tr bgcolor="#BCE6FC">
	<td valign="top" align="right" name="5553"><b>มีการติดตามหรือไม่:</b></td>
     <td bgcolor="#FFFFFF"><input type="checkbox" name="chkanalyze" id="chkanalyze" value="1" <?php if($chkanalyze=="1") echo "checked";?>/>
: มีการติดตาม</td></tr>



</table>




<table width="850" id="tb1" cellSpacing="1" cellPadding="3" border="0" bgcolor="#D7F0FD" align="center">
<tr bgcolor="#BCE6FC">
    <td width="150" height="25" align="right"><b>ชื่อการติดตาม :</b></td>
    <td bgcolor="#FFFFFF"><input type="text" name="tb_tagname" id="tb_tagname" value="<?php echo "$tagname"; ?>"></td>
</tr>  <input type="hidden" name="hdcheck" id="hdcheck" >
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>รายละเอียดการติดตาม:</b></td>
    <td bgcolor="#FFFFFF"><textarea id="tb_tagdetail" name="tb_tagdetail" rows="10" cols="80"><?php echo "$tagdetail"; ?></textarea>
	</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>วันที่ให้แจ้งเตือน:</b></td>
    <td bgcolor="#FFFFFF">
	<input type="text" size="12" readonly="true" style="text-align:center;" id="alDate" name="alDate" value="<?php echo $aldate; ?>" onchange="chkdate()"/> &nbsp
	
	</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>เวลาให้แจ้งเตือน:</b></td>
    <td bgcolor="#FFFFFF">
	<select name="alerthours" id="alh">
			<?php
			for($i=0;$i<25;$i++){
			if($i<10){ $i="0".$i;}			?>
			<option value="<?php echo $i; ?>"><?php echo "$i";?></option>
			
			<?php } ?>
			
				
		  </select> :
	<select name="alertmin">
			<?php
			for($z=0;$z<60;$z+=5){
			if($z<10){ $z="0".$z;} ?>
			<option value="<?php echo $z; ?>"><?php echo "$z";?></option>
			
			<?php } ?>
			
				
		  </select> (ชั่วโมง : นาที)
		  
	</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>วันที่ ติดต่อกลับ:</b></td>
    <td bgcolor="#FFFFFF">
	<input type="text" size="12" readonly="true" style="text-align:center;" id="tagDate" name="tagDate" value="<?php echo $tagdate; ?>" onchange="chkdate()"/> &nbsp
	</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>เวลาที่ ติดต่อกลับ:</b></td>
    <td bgcolor="#FFFFFF">
	<select name="taghours">
			<?php
			for($i=0;$i<25;$i++){
			if($i<10){ $i="0".$i;}			?>
			<option value="<?php echo $i; ?>"><?php echo "$i";?></option>
			
			<?php } ?>
			
				
		  </select> :
	<select name="tagmin">
			<?php
			for($z=0;$z<60;$z+=5){
			if($z<10){ $z="0".$z;} ?>
			<option value="<?php echo $z; ?>"><?php echo "$z";?></option>
			
			<?php } ?>
			
				
		  </select> (ชั่วโมง : นาที)
		  
	</td>
</tr>
</table>

<table width="850" id="tb1" cellSpacing="1" cellPadding="3" border="0" bgcolor="#D7F0FD" align="center">
<tr bgcolor="#BCE6FC">
    <td width="150" height="25" align="right"><b>ต้องการให้ใครเห็นบ้าง</b></td>
    <td bgcolor="#FFFFFF">
		<select name="dep" id="dep" onchange="javascript:checkdep()">
										<option  selected value="allemp">---ทั้งหมด ---</option>
							
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
				
<tr bgcolor="#BCE6FC">
    <td width="150" height="25" align="right"><b></b></td>
    <td bgcolor="#FFFFFF"><input height="35" type="submit" value=" บันทึก " style="width:100px; height:30px;" onclick="return checkList();"></td>
</tr>


</table>

</body>
</form>

