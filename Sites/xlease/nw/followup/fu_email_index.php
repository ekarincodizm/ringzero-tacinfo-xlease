<?php
include("../../config/config.php");
session_start();
$id_user1 = $_SESSION["av_iduser"];
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">

function checkcom()
{
	$("#empname").load("emp_data.php?COMID="+$("#company").val());
	$("#emp").hide();
}
$(document).ready(function(){


    $('#btntem').click(function(){
        $("#panel2").load("fu_email_template_list.php");
    });
	


	$("#choise4").hide();
	$("#empc4").hide();
	$("input[type='radio']").change(function(){

	if($(this).val()=="allcomemp"){
	
		$("#choiseemp1").show();
		$("#choise1").show();
		
		$("#choise4").hide();
		$("#empc4").hide();
		
	}else if($(this).val()=="empupcom"){
		
		$("#choiseemp1").hide();
		$("#choise1").hide();
		
		$("#choise4").show();
		$("#empc4").show();
		
	}else if($(this).val()=="com"){
		
		$("#choise1").show();
		
		$("#choiseemp1").hide();
		$("#choise4").hide();
		$("#empc4").hide();
		
	}else if($(this).val()=="emp"){
		
		$("#choiseemp1").show();
		
		$("#choise1").hide();
		$("#choise4").hide();
		$("#empc4").hide();
				
    }

});
});

function CheckAll(chk)
{
for (i = 0; i < chk.length; i++)
chk[i].checked = true ;
}

function UnCheckAll(chk)
{
for (i = 0; i < chk.length; i++)
chk[i].checked = false ;
}



</script>


<div class="ui-widget" align="left">
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<?php
$date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$i = 1;
?>

<body>
<center><legend><h2>... E-Mail Bomb ...</h2></legend></center>
<form name="frm" method="post" action="fu_email_sending_query.php">

	<hr width="850">
		<table width="850" cellSpacing="1" cellPadding="3" border="0" bgcolor="#D7F0FD" align="center">
			<tr bgcolor="#BCE6FC">
				<td width="150" height="25" align="right"><b>Template :</b></td>
					<td bgcolor="#FFFFFF">
					<?php
						$objQuery1 = pg_query("SELECT * FROM \"fu_template\" order by \"temID\"");

					?>
							<select id="template" name="template">
									<?php while($objResuut1 = pg_fetch_array($objQuery1)){ ?>
									<option value="<?php echo $objResuut1["temID"]; ?>"><?php echo $objResuut1["tem_name"];?></option>
									
									<?php
									$idcheck = $objResuut1["temID"]; 
									} ?>
							</select>*
						<input type="button" name="btntem" id="btntem" value="จัดการ Template">
					</td>
			</tr>
			<tr>
				<td width="150" height="25" align="right"> ตัวเลือการส่ง </td>
				<td bgcolor="#FFFFFF">
						<input type="radio" name="ra1" id="ra1" value="allcomemp" checked>บริษัทและผู้ติดต่อทั้งหมด <br>
						<input type="radio" name="ra1" id="ra1" value="com">บริษัท <br>
						<input type="radio" name="ra1" id="ra1" value="emp">ผู้ติดต่อ <br>
						<input type="radio" name="ra1" id="ra1" value="empupcom">ผู้ติดต่อขึ้นกับบริษัท<br>
				</td>		
			</tr>
	<!--- เลือกบริษัท  และพนักงาน ตามใจฉัน------>
			   <!-- <input type="button" name="Button" value="เลือกทั้งหมด" onClick="CheckAll(document.frm.com)">
				<input type="button" name="Button" value="ไม่เลือกทั้งหมด" onClick="UnCheckAll(document.frm.com)">-->
				
			<tr bgcolor="#BCE6FC" name="choise1" id="choise1">
					<td valign="top" align="right"><b>เลือกบริษัท :</b></td>
						<td bgcolor="#FFFFFF">
							<div style="width: 700px; height: 200px; overflow: auto;">
							<table width="700" cellSpacing="1" cellPadding="3" border="0"  align="center">
							
									<?php $objQuery1 = pg_query("SELECT * FROM \"fu_company\" where \"com_email\" IS NOT NULL order by \"runnumber\"");
										while($objResuut1 = pg_fetch_array($objQuery1)){ 
			
											$a1=$objResuut1["comID"];
											$b1=$objResuut1["com_name"];
											$c1=$objResuut1["com_email"];
											
											$objQuery3 = pg_query("SELECT count(\"mailID\") as count FROM \"fu_mail_history\" where \"comID\" = '$a1'");
											$countcom = pg_fetch_array($objQuery3);
											if($countcom['count'] <= 0){
												$d1 = "";
											}else{
												$d1 = "มีประวัติการส่งเมลล์ ".$countcom['count']." "."ครั้ง";
											}
									?>	
									<tr>
									<td><input type="checkbox" name="com[]" id="com[$i]" value="<?php echo $a1 ?>"><?php echo $b1." : ".$c1." :: ".$d1?>
									<?php if($d1 != "" || !empty($d1)){?>
									 <div style="float:right;"><input type="button" value="ประวัติ" onclick="javascript:popU('fu_email_history.php?comID=<?php echo $a1 ?>')" style="width:50px; height:18px; font-size:2pt" >
									 <?php } ?>
									</div></td>
									</tr>	
			
									<?php } ?>		
							</table>
							</div>
						</td>
			</tr>
			
			    <!--<input type="button" name="Button" value="เลือกทั้งหมด" onClick="CheckAll(document.frm.emp)">
				<input type="button" name="Button" value="ไม่เลือกทั้งหมด" onClick="UnCheckAll(document.frm.emp)">-->
			<tr bgcolor="#BCE6FC" name="choiseemp1" id="choiseemp1">
					<td valign="top" align="right"><b>เลือกผู้ติดต่อ :</b></td>
						<td bgcolor="#FFFFFF">
						<div style="width: 700px; height: 200px; overflow: auto;">
						<table width="700" cellSpacing="1" cellPadding="3" border="0"  align="center">
									<?php $objQuery1 = pg_query("SELECT * FROM \"fu_empcontact\" fe join \"fu_company\" fc on
										fe.\"comID\" = fc.\"comID\" where fe.\"empcon_email\" IS NOT NULL order by fe.\"comID\"");
										while($objResuut2 = pg_fetch_array($objQuery1)){ 
			
											$a2=$objResuut2["empconID"];
											$b2=$objResuut2["empcon_name"];
											$c2=$objResuut2["empcon_email"];
											$d2=$objResuut2["com_name"];
											$f2=$objResuut2["empcon_lname"];
											
											$objQuery3 = pg_query("SELECT count(\"mailID\") as count FROM \"fu_mail_history\" where \"empconID\" = '$a2'");
											$countcom = pg_fetch_array($objQuery3);
											if($countcom['count'] <= 0){
												$e2 = "";
											}else{
												$e2 = "มีประวัติการส่งเมลล์ ".$countcom['count']." "."ครั้ง";
											}
										
								?>
									<tr>	
									 <td><input type="checkbox" name="emp[]" id="emp[$i]" value="<?php echo $a2 ?>" ><?php echo $b2." ".$f2." : ".$c2." :: ".$d2." ::: ".$e2 ?>
									 <?php if($e2 != "" || !empty($e2)){?>
									 <div style="float:right;"><input type="button" value="ประวัติ" onclick="javascript:popU('fu_email_history.php?empID=<?php echo $a2;?>')" style="width:50px; height:18px; font-size:2pt" >
									 <?php } ?>
									</div> </td>
									</tr>
									 
			
								<?php	} ?>		
						</table>
						</div></td>
			</tr>

			
	<!--- เลือกพนักงานที่อยู่ในบริษัท ------>		
			<tr bgcolor="#BCE6FC" name="choise4" id="choise4">
					<td valign="top" align="right"><b>เลือกบริษัทที่ต้องการส่ง :</b></td>
						<td bgcolor="#FFFFFF">
							<select name="company" id="company" onchange="javascript:checkcom()">
										<option  selected value="">--- บริษัท ---</option>
							
									<?php $objQuery = pg_query("SELECT * FROM \"fu_company\" order by \"runnumber\"");
										while($objResuut = pg_fetch_array($objQuery)){ ?>
			
										<option value="<?php echo $objResuut["comID"]; ?>"><?php echo $objResuut["com_name"];?></option>
			
									<?php } ?>		
							</select>
						</td>
			</tr>
			
				<tr bgcolor="#BCE6FC" name="empc4" id="empc4">
					<td valign="top" align="right"><b>ชื่อผู้ติดต่อที่ต้องการส่ง:</b></td>
						<td bgcolor="#FFFFFF">
							<span id="empname"></span><span id="emp">-- ยังไม่มีผู้ติดต่อ-</span>	
						</td>						
				</tr>	
				
				
				
				
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>วันที่ส่ง:</b></td>
    <td bgcolor="#FFFFFF"><?php echo "$date"; ?></td>
</tr>

<tr bgcolor="#BCE6FC">
    <td width="150" height="25" align="right"><b></b></td>
    <td bgcolor="#FFFFFF"><input height="35" type="submit" value="ส่ง" style="width:150px; height:35px;"></td>
</tr>
</table>
<center>
<table width="850" cellSpacing="0" cellPadding="1" border="0">
<tr>
<td colspan="10"><div id="panel2" style="padding-top: 10px;"></div></td>
</tr>
</table>
</center>
</body>
</form>

