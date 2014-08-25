<?php
include("../../config/config.php");

$nowdate=nowDate();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>คืนหลักทรัพย์ค้ำประกัน</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}

$(document).ready(function(){

    $("#numid").autocomplete({
        source: "s_link.php",
        minLength:2
    });
});
</script>
    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}

.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
</style>
    
</head>
<body>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div style="clear:both; padding: 10px;text-align:center;"><h2>คืนหลักทรัพย์ค้ำประกัน</h2></div>
			<div style="text-align:right;"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
			<fieldset><legend><B>ค้นหา</B></legend>
				<form method="post" action="frm_DetailReturn.php">
				<div class="ui-widget" align="center">
					<div style="margin:0;padding-bottom:10px;">
						<b>ค้นจาก รหัสเชื่อมโยง, เลขที่โฉนด, เลขที่สัญญา:</b>&nbsp;
						<input id="numid" name="numid" size="60" />&nbsp;
						<input type="submit" value="NEXT" />
						<input name="button" type="button" onclick="window.close()" value="CLOSE" />
					</div>
				</div>
				</form>
			</fieldset>	
        </td>
    </tr>
</table>

<div style="padding-top:10px;">
<table width="900" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div style="padding-left:5px;"><b>รายการที่รออนุมัติและอนุมัติในวันนี้</b></div>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#8B8989">
			<tr style="font-weight:bold;" valign="middle" bgcolor="#CDC9C9" align="center">
				<td>เลขที่โฉนด</td>
				<td>วันที่คืน</td>
				<td>ผู้รับคืน</td>
				<td>ผู้ทำรายการ</td>
				<td>วันเวลาทำรายการ</td>
				<td>ผู้อนุมัติ</td>
				<td>วันเวลาที่อนุมัติ</td>
				<td>สถานะการอนุมัติ</td>
			</tr>
			<?php
			//แสดงข้อมูลที่รออนุมัติ
			$qry_fr=pg_query("select a.\"securID\",\"numDeed\",b.\"fullname\" as \"userRequest\",\"full_name\" as \"cusReceive\",\"dateRequest\",a.\"returnDate\",
			e.\"fullname\" as \"appUser\",a.\"appDate\",\"statusApp\"
			from \"temp_securities_reqreturns\" a
			left join \"Vfuser\" b on a.\"userRequest\"=b.\"id_user\"
			left join \"VSearchCusCorp\" c on a.\"CusIDReceiveReturn\"=c.\"CusID\"
			left join \"nw_securities\" d on a.\"securID\"=d.\"securID\"
			left join \"Vfuser\" e on a.\"appUser\"=e.\"id_user\"
			where \"statusApp\"='2'
			order by \"dateRequest\" DESC");
			$num1=pg_num_rows($qry_fr);
			
			while($res_fr=pg_fetch_array($qry_fr)){
				$securID=$res_fr["securID"];
				$numDeed=$res_fr["numDeed"];
				$userRequest = $res_fr["userRequest"]; 
				$cusReceive = $res_fr["cusReceive"];
				$dateRequest = $res_fr["dateRequest"];
				$returnDate = $res_fr["returnDate"];
				$statusApp = $res_fr["statusApp"];
				
				$i+=1;
				if($i%2==0){
					echo "<tr bgcolor=#EEE9E9 align=center>";
				}else{
					echo "<tr bgcolor=#FFFAFA align=center>";
				}
			?>
				<td><a style="cursor:pointer;" onclick="javascipt:popU('frm_IndexDetail.php?numdeed=<?php echo $numDeed; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750');"><font color="#FF1493"><u>
				<?php echo $numDeed; ?></u></font></a></td>
				<td><?php echo $returnDate; ?></td>
				<td align="left"><?php echo $cusReceive; ?></td>
				<td align="left"><?php echo $userRequest; ?></td>
				<td><?php echo $dateRequest; ?></td>
				<td align="left"><?php echo $res_fr["appUser"]; ?></td>
				<td><?php echo $res_fr["appDate"]; ?></td>
				<td>
				<?php
				if($statusApp=="1"){
					echo "อนุมัติ";
				}else if($statusApp=="0"){
					echo "ไม่อนุมัติ";
				}else{
					echo "<font color=red>รออนุมัติ</font>";
				}
				?>
				</td>
				
			</tr>
			<?php
			} //end while
			
			//แสดงข้อมูลที่อนุมติแล้ว
			$qry_approve=pg_query("select a.\"securID\",\"numDeed\",b.\"fullname\" as \"userRequest\",\"full_name\" as \"cusReceive\",\"dateRequest\",a.\"returnDate\",
			e.\"fullname\" as \"appUser\",a.\"appDate\",\"statusApp\"
			from \"temp_securities_reqreturns\" a
			left join \"Vfuser\" b on a.\"userRequest\"=b.\"id_user\"
			left join \"VSearchCusCorp\" c on a.\"CusIDReceiveReturn\"=c.\"CusID\"
			left join \"nw_securities\" d on a.\"securID\"=d.\"securID\"
			left join \"Vfuser\" e on a.\"appUser\"=e.\"id_user\"
			where \"statusApp\"<>'2' and date(\"appDate\")='$nowdate'
			order by \"appDate\" DESC");
			$num2=pg_num_rows($qry_approve);
			
			while($res_fr=pg_fetch_array($qry_approve)){
				$securID=$res_fr["securID"];
				$numDeed=$res_fr["numDeed"];
				$userRequest = $res_fr["userRequest"]; 
				$cusReceive = $res_fr["cusReceive"];
				$dateRequest = $res_fr["dateRequest"];
				$returnDate = $res_fr["returnDate"];
				$statusApp = $res_fr["statusApp"];
				
				$i+=1;
				if($i%2==0){
					echo "<tr bgcolor=#EEE9E9 align=center>";
				}else{
					echo "<tr bgcolor=#FFFAFA align=center>";
				}
			?>
				<td><a style="cursor:pointer;" onclick="javascipt:popU('frm_IndexDetail.php?numdeed=<?php echo $numDeed; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750');"><font color="#FF1493"><u>
				<?php echo $numDeed; ?></u></font></a></td>
				<td><?php echo $returnDate; ?></td>
				<td align="left"><?php echo $cusReceive; ?></td>
				<td align="left"><?php echo $userRequest; ?></td>
				<td><?php echo $dateRequest; ?></td>
				<td align="left"><?php echo $res_fr["appUser"]; ?></td>
				<td><?php echo $res_fr["appDate"]; ?></td>
				<td>
				<?php
				if($statusApp=="1"){
					echo "อนุมัติ";
				}else if($statusApp=="0"){
					echo "ไม่อนุมัติ";
				}else{
					echo "<font color=red>รออนุมัติ</font>";
				}
				?>
				</td>
				
			</tr>
			<?php
			} //end while
			$nub=$num1+$num2;
			if($nub == 0){
				echo "<tr><td colspan=8 align=center height=50 bgcolor=#FFFAFA><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>
</div>
</body>
</html>