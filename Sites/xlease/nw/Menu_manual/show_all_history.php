<?php
session_start();
include("../../config/config.php");

$strSort = $_GET["sort"];
if($strSort == ""){
	$strSort = "app_date";
}
	$strOrder = $_GET["order"];
if($strOrder == ""){
	$strOrder = "DESC";
}




$sql = pg_query("select a.* FROM f_menu_manual a where appstatus='0' order by \"$strSort\" $strOrder");
$strNewOrder = $strOrder == 'DESC' ? 'ASC' : 'DESC';
//Query ประวัติการอนุมัติ 30 รายการล่าสุด
$sql2 = pg_query("select * FROM f_menu_manual where appstatus<>'0'and appstatus<>'3' order by \"$strSort\" $strOrder " );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ประวัติการอนุมัติคำแนะนำใช้งานเมนู</title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script> 
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
    #color_hr
	{
	color:#999999;
	}  
</style>
</head>

<body style="background-color:#DDDDDD;">
  
  
  <div style="margin-top:25px" align="center" ></div>
<form id="myform2" name="myform2" method="post">	
<table width="95%"  cellspacing="0" cellpadding="0"  align="center">	
<tr>
		<td>

</td>
</tr>
<tr>
<td>		
<table width="100%" border="1" cellspacing="0" cellpadding="0"  align="center">	
	
<?php 
$row = pg_num_rows($sql2);
if($row != 0){			?>		
		
		<tr>	
			<td width="100%">			
				<table width="100%" frame="box" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
					<tr>
						<td colspan="9" align="center" >
							<font color="black"><h1><b>ประวัติการอนุมัติคำแนะนำใช้งานเมนู </h1></b></font>
						</td>
					</tr>
					<tr bgcolor="#9FB6CD" height="25px">
						<th width="5%"><font color="black">รายการที่</font></a></th>
						<th width="15%"><a href='show_all_history.php?sort=id_menu&order=<?php echo $strNewOrder ?>'><font color="black"><u>ชื่อเมนู</u></font></th>
						<th width="3%"><a href='show_all_history.php?sort=revision_num&order=<?php echo $strNewOrder ?>'><font color="black"><u>ครั้งที่</u></font></a></th>
						<th width="10%"><a href='show_all_history.php?sort=id_user&order=<?php echo $strNewOrder ?>'><font color="black"><u>ผู้ตั้ง</u></font></a></th>
						<th width="13%"><a href='show_all_history.php?sort=rec_date&order=<?php echo $strNewOrder ?>'><font color="black"><u>วันที่/เวลาที่ตั้ง</u></font></a></th>
						<th width="13%"><a href='show_all_history.php?sort=recheader&order=<?php echo $strNewOrder ?>'><font color="black"><u>ชื่อเรื่อง</u></font></a></th>
						<th width="10%"><a href='show_all_history.php?sort=appuser&order=<?php echo $strNewOrder ?>'><font color="black"><u>ผู้ทำรายการอนุมัติ</u></font></th>
						<th width="13%"><a href='show_all_history.php?sort=app_date&order=<?php echo $strNewOrder ?>'><font color="black"><u>วันเวลาที่ทำรายการอนุมัติ</u></font></th>
						<th width="10%"><font color="black">รายละเอียด</font></th>
						<th width="10%"><a href='show_all_history.php?sort=appstatus&order=<?php echo $strNewOrder ?>'><font color="black"><u>ผลการอนุมัติ</u></font></th>
					</tr>	
			<?php 
				$i =0;
				$num=0;
				
				
					
				while($result = pg_fetch_array($sql2)){
				$num++;
				$recmenuid2 = $result['recmenuid'];
				$id_menu2 = $result['id_menu'];
				$iduser2 = $result['id_user'];
				$revision_num2 = $result['revision_num'];
				$rec_date2 = $result['rec_date'];
				$recheader2 = $result['recheader'];
				$appuser_id = $result['appuser'];
				$appstatus = $result['appstatus'];
				$app_date = $result['app_date'];
				
				$sqluser2 = pg_query("SELECT fullname FROM \"Vfuser\" where id_user = '$iduser2' ");
				$userresult2 = pg_fetch_array($sqluser2);
				
				$sqlmenu2= pg_query("SELECT name_menu FROM f_menu where id_menu = '$id_menu2'");
				$menuresult2 = pg_fetch_array($sqlmenu2);
				
				$appuser= pg_query("SELECT fullname FROM \"Vfuser\" where id_user = '$appuser_id'");
				$appuserResult = pg_fetch_array($appuser);
				
				//เปลี่ยนตัวเลขผลการอนุมัติให้เป็น String
				if($appstatus==1){
					$appstatusResult="อนุมัติ";
				} else {
						$appstatusResult="ไม่อนุมัติ";
					}
						
					$i++;
					if($i%2==0){
						echo "<tr bgcolor=#B9D3EE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#B9D3EE';\" align=center>";
					}else{
						echo "<tr bgcolor=#C6E2FF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#C6E2FF';\" align=center>";
					}
					echo "		<td>".$num."</td>
								<td align=\"left\">".$menuresult2['name_menu']."</td>
								<td>".$revision_num2."</td>
								<td align=\"left\">".$userresult2['fullname']."</td>		
								<td>".$rec_date2."</td>
								<td align=\"left\">".$recheader2."</td>
								<td align=\"left\">".$appuserResult['fullname']."</td>
								<td>".$app_date."</td>									
								<td><a href=\"#\" onclick=\"javascript:popU('Manage_rec.php?recid=$recmenuid2&appstate=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1330,height=650')\" style=\"cursor: pointer\"><font color=\"#0000FF\"><u>ดูรายละเอียด</u></font></a></td>
								<td>".$appstatusResult."</td>
						</tr>";
					} echo "<tr bgcolor=\"#68BEFF\"><td colspan=\"11\" align=\"left\"><strong> รวม $num รายการ</strong></td></tr>";

?>					
			
<?php }else{ echo "<tr><td align=\"center\" colspan=\"6\"><h1>ไม่มีรายการรออนุมัติ</h1><hr width=\"450\"></td></tr>";} ?>					
				</table>	
			</td>			
		</tr>			
	</table>
</td>		
</tr>
</table>	
  </div>
</body>
</form>
<script type="text/javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
function app(frm)
{

var con = $("#chkchoise").val();
var numchk;
numchk = 0;

	for(var num = 1;num<=con;num++){	
		if(document.getElementById("idapp"+num).checked){
			numchk+=1;			
		}		
	}
	if(numchk == 0){
		alert("กรุณาเลือกรายการก่อน");
	}else{
		if(confirm('ยืนยัน การอนุมัติ')==true){
			frm.action="Process_approve.php";
			frm.submit();
			document.myform.submit.disabled='true';
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
		if(document.getElementById("idapp"+num).checked){
			numchk+=1;			
		}		
	}
	if(numchk == 0){
		alert("กรุณาเลือกรายการก่อน");
	}else{
		if(confirm('ยืนยัน ปฎิเสธการอนุมัติ')==true){
			frm.action="frm_approve_reason.php";
			frm.submit();
			document.myform.submit.disabled='true';
			return true;
		}else{ 
			return false;
		}
	}	
}
</script>
</html>