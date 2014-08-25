<?php
session_start();
include("../config/config.php");

$strSort = $_GET["sort"];
if($strSort == ""){
	$strSort = "fp_appID";
}
	$strOrder = $_GET["order"];
if($strOrder == ""){
	$strOrder = "ASC";
}
$sql = pg_query("SELECT * FROM \"Fp_cancel_approve\" where appstatus = '0' order by \"$strSort\" $strOrder limit 30");
$strNewOrder = $strOrder == 'DESC' ? 'ASC' : 'DESC';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> อนุมัติ ยกเลิกสัญญาเช่าซื้อ</title>
<script type="text/javascript" src="autocomplete.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script> 
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
<form id="myform" name="myform" method="post">	
<table width="1000"  cellspacing="0" cellpadding="0"  align="center">	
<tr>
		<td>
<table width="300" frame="box" cellspacing="0" bgcolor="#8B864E" cellpadding="0"  align="left">	
<tr>
		<td align="center"><h2><b><font color="#FFF68F">อนุมัติยกเลิกสัญญาเช่าซื้อ</font></b></h2></td>
		
</tr>
</table>
</td>
</tr>
<tr>
<td>		
<table width="1000" border="1" cellspacing="0" cellpadding="0"  align="center">	
	
<?php 
$row = pg_num_rows($sql);
if($row != 0){			?>			
		<tr>	
			<td width="100%">			
				<table width="100%" frame="box" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
					<tr>
						<td colspan="6" align="center" bgcolor="#8B8B7A">
							<font color="white"><h2><b>รายการขอยกเลิกสัญญาเช่าซื้อ</h2></b></font>
						</td>
					</tr>
					<tr bgcolor="#CDCDB4" height="25px">
					
						<th width="15%"><a href='cc_approve.php?sort=IDNO&order=<?php echo $strNewOrder ?>'><font color="black"><u>เลขที่สัญญา</u></font></a></th>	
						<th width="15%"><a href='cc_approve.php?sort=id_user&order=<?php echo $strNewOrder ?>'><font color="black"><u>ผู้ขอยกเลิก</u></font></a></th>
						<th width="20%"><a href='cc_approve.php?sort=cancel_date&order=<?php echo $strNewOrder ?>'><font color="black"><u>วันที่ขอยกเลิก</u></font></a></th>
						<th ><a href='cc_approve.php?sort=reason&order=<?php echo $strNewOrder ?>'><font color="black"><u>เหตุผลที่ขอยกเลิก</u></font></a></th>
						<th width="10%"><a href='cc_approve.php?sort=appstatus&order=<?php echo $strNewOrder ?>'><font color="black"><u>สถานะ</u></font></a></th>
						<th width="10%">เลือก </th>
					</tr>	
			<?php 
				$i =0;
				
				
				
					
				while($result = pg_fetch_array($sql)){
				$fp_appID = $result['fp_appID'];
				$iduser = $result['id_user'];
				$appstate = $result['appstatus'];
				if($appstate == '1'){
					$status =  "อนุมัติ";
					$textcolor = "#00CD00";
				}else if($appstate == '2'){
					$status = 'ไม่อนุมัติ';
					$textcolor = "#CD0000";
				}else{
					$status = 'รออนุมัติ';
					$textcolor = "#8B8B00";
				}
				
				$sqluser = pg_query("SELECT  fullname FROM \"Vfuser\" where id_user = '$iduser' ");
				$userresult = pg_fetch_array($sqluser);
				
				$textreason = $result['reason'];
				$subtextreason = mb_substr($textreason,0,40,'UTF-8');
				$inno = $result['IDNO'];
				
					$i++;
					if($i%2==0){
						echo "<tr bgcolor=#EEEED1 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEEED1';\" align=center>";
					}else{
						echo "<tr bgcolor=#FFFFE0 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFFE0';\" align=center>";
					}
					
					echo "		<td align=\"center\" onclick=\"javascript:popU('frm_viewcuspayment.php?idno_names=$inno','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" style=\"cursor:pointer\"><u>".$inno."</u></td>
								<td>".$userresult['fullname']."</td>		
								<td>".$result['cancel_date']."</td>
								<td><a href=\"#\" onclick=\"javascript:popU('cc_detail_app.php?fp_appID=$fp_appID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=400,height=250')\" style=\"cursor: pointer\">".$subtextreason."...</a></td>
								<td><font color=\"$textcolor\">".$status."</font></td>
								<td><input type=\"checkbox\" name=\"idapp[]\" id=\"idapp$i\" value=\"$fp_appID\"></td>
						</tr>";
					}

?>					
					<tr bgcolor="#CDCDB4">
						<td align="left" colspan="4"  height="18px">
							 <font color="red"><b><?php echo $row; ?> รายการ </b></font> 
						</td>						
						<td align="center"  height="18px">
							 <input type="button" onclick="not(this.form);" value="ไม่อนุมัติ" style="Background-Color:#FF6A6A;">
						</td>	
						<td align="center" height="18px">
							 <input type="button" onclick="app(this.form);" value="อนุมัติ" style="Background-Color:#9ACD32;">
							 <input type="hidden" value="allowcan" name="chkstate">
							 <input type="hidden" value="approve" name="chkapp"> 

							 <input type="hidden" value="<?php echo $i; ?>" id="chkchoise">
						</td>		
					</tr>
<?php }else{ echo "<tr><td align=\"center\" bgcolor=\"#FFFFE0\" colspan=\"6\"><h1>ไม่มีรายการรออนุมัติ</h1></td></tr>";} ?>					
				</table>	
			</td>			
		</tr>			
	</table>
</td>		
</tr>
</table>	
  </div>
 	<div style="margin-top:50px" align="center" ></div>	
	<table width="1000" border="0" cellspacing="0" cellpadding="0"  align="center">	
		<tr>	
			<td>
				<?php 
					$limitshowapp = "true";
					include("frm_history.php"); 
				?>	
			</td>			
		</tr>			
	</table>
  
  
  
  
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
			frm.action="del_idno.php";
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
			frm.action="cc_approve_reason.php";
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