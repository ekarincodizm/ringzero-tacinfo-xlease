<?php
session_start();
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
if($_SESSION["session_company_code"]=="AVL")
{
 $file_namepic="logo_av.jpg";
}
else
{
 $file_namepic="logo_thaiace.jpg";
}
include("../../config/config.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>- แจ้งเตือนการติดต่อกลับบริษัทลูกค้า -</title>
<link type="text/css" rel="stylesheet" href="../manageCustomer/act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};
</script>
<style type="text/css">
    #warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(240, 240, 240);
	padding: 5px;
	border: rgb(128, 128, 128) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
	}
.style1 {
	font-size: small;
	font-weight: bold;
}
.style2 {
	font-size: medium;
	font-weight: bold;
}
</style>
</head>

<body>
<form id="myform" name="myform" method="post" action="fu_tag_change_query.php">
<table width="1250" align="center">
    <tr>
        <td>       
			<div style="float:left">&nbsp;</div>
			<div class="style2" id="super_head" style="padding-left:10px; height:90px; width:800px;"><span class="style2" style="padding-left:10px; height:60px; width:800px; "><div style="width:90px; float:left;"><img src="../../images/<?php echo $file_namepic; ?>" width="80" height="80" /></div><div style="padding-top:20px;">
			<span><?php echo $_SESSION["session_company_name"]; ?></span><br /><?php echo $_SESSION["session_company_thainame"]; ?></div></div>
			<div style="clear:both;"></div>
		<td>
	</tr>
	
	<tr><td><legend><B>ตารางการแจ้งเตือน</B></legend></td></tr>	
	<tr>
		<td>
			<?php
			$date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
			$strSQL = "SELECT * FROM \"ContactHistory\" where \"datetime_alert\" <= '$date' and \"status_alert\" = 0";
			$objQuery = pg_query($strSQL);


			$nrows=pg_num_rows($objQuery);
			
			?>

			<div style="width: 100%; height: 600px; overflow: auto;">
				<table width="99%" frame="box" style="margin-top:1px">

				  <tr bgcolor="#79BCFF" height="25" >
					<th width="50"> <div align="center">รหัส </div></th>
					<th width="190"> <div align="center">ชื่อการติดตาม </div></th>
					<th width="150"> <div align="center">ชื่อการสนทนา</div></th>
					<th width="150"> <div align="center">ชื่อบริษัท </div></th>
					<th width="120"> <div align="center">เบอร์โทรศัพท์ </div></th>
					<th width="150"> <div align="center">ผู้ติดต่อ </div></th>
					 <th width="190"> <div align="center">พนักงานของ Thaiace </div></th>
					<th width="150"> <div align="center">วันเวลาที่ให้ติดต่อกลับ</div></th>	
					<th width="59"> <div align="center">เลือก</div></th>
					<th width="59"> <div align="center">แก้ไข</div></th>
				  </tr>
				<?php
			if($nrows != 0){		
				$i = 0;
				while($results = pg_fetch_array($objQuery))
				{
				$tagid=$results["tagID"];
				$id_user = $results["id_user"];
				$tag_datetime = $results["tag_datetime"];
				$i++;
				
				if($tag_datetime < $date){ 
					echo "<tr bgcolor=\"#FF9999\">";
				}else{	
					echo "<tr>";
				} ?>
						<td align="center" onclick="javascript:popU('fu_tag_data.php?TAGID=<?php echo $tagid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">	
							<u><?php echo $tagid;?></u></td>
						<td align="center" ><?php echo $results["tag_name"];?></td>
						<td align="center" onclick="javascript:popU('fu_conversation_data.php?CONTID=<?php echo $results["conID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">	
							<u><?php echo $results["con_name"];?></u></td>
						<td align="center" onclick="javascript:popU('fu_company_data.php?COMID=<?php echo $results["comID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">	
							<u><?php echo $results["com_name"];?></u></td>
						<td align="center"><?php echo $results["com_phone"];?></td>
						<td align="center" onclick="javascript:popU('fu_empcontact_data.php?empID=<?php echo $results["empconID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด">	
							<u><?php echo $results["full_name"];?></u></td>
					
			<?php $strSQL3 = "SELECT * FROM \"Vfuser\" where \"id_user\" = '$id_user'";
					$objQuery3 = pg_query($strSQL3);
					$results3 = pg_fetch_array($objQuery3);
					
					$fullname = $results3["fullname"];
					
					
					echo "<td align=\"center\">$fullname</td> ";
					echo "<td align=\"center\">$tag_datetime</td>";
					echo "<td align=\"center\"><input type=\"checkbox\" name=\"idapp[]\" id=\"idapp$i\" value=\"$tagid\"></td>";
					echo "<td align=\"center\"><img src=\"images/onebit_20.png\" style=\"cursor:pointer\" width=\"20px\" height=\"20px;\" Title=\"แก้ไข\" onclick=\"javascript:popU('fu_tag_edit.php?TAGID=$tagid','','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=850,height=560')\"></td>";
					echo "</tr>";
				
				}
		}else{ 
			echo "<tr><td align=\"center\" colspan=\"12\">--- ไม่พบข้อมูล ---</td></tr>";
		}				
			?>
					<input type="hidden" value="<?php echo $i; ?>" id="chkchoise">
					<tr bgcolor="#79BCFF">
						<td  colspan="8"><div align="center"></div> มีทั้งหมด <?php echo $nrows; ?> รายการ</td>
						<!--td><input type="button" name="bt_ok" id="bt_ok" value="รับทราบ" onclick="app(this.form);"></td>
						<td><input type="button" name="bt_pass" id="bt_pass" value="ยกเลิก" onclick="not(this.form);"></td-->
						
						<td><input name="bt_ok"  type="submit" value="รับทราบ"  onclick="return app();"/></td>
						<td><input name="bt_pass"  type="submit" value="ยกเลิก" onclick="return not();" /></td>
					</tr>
				</table>
			</div>
        </td>
    </tr>
</table>
</form>
</body>
<script type="text/javascript">
function app()
{
var con = $("#chkchoise").val();
var numchk;
numchk = 0;
	for(var num = 1;num<=con;num++){	
		if(document.getElementById('idapp'+num).checked == true){
			numchk+=1;			
		}		
	}
	if(numchk == 0){
		alert("กรุณาเลือกรายการก่อน");
	}else{
		if(confirm('ยืนยัน การอนุมัติ')==true){
			//parent.location.href='fu_tag_change_query.php?Check=agree&TAGID=<?php echo $tagid ?>';
		return true;
		}else{ 
			return false;
		}
	}	
}

function not(frm)
{
var con = $("#chkchoise").val();
var numchk;
numchk = 0;
	for(var num = 1;num<=con;num++){	
		if(document.getElementById('idapp'+num).checked == true){
			numchk+=1;			
		}		
	}
	if(numchk == 0){
		alert("กรุณาเลือกรายการก่อน");
	}else{
		if(confirm('ยืนยัน การอนุมัติ')==true){
			//parent.location.href='fu_tag_change_query.php?Check=del&TAGID=<?php echo $tagid ?>';
			return true;
		}else{ 
			return false;
		}
	}	
}
</script>
</html>
