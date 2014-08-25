<?php
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

$app_date = Date('Y-m-d H:i:s');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>

</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="header"><h1>อนุมัติการคืนหลักทรัพย์ค้ำประกัน </h1></div>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<td>เลขที่โฉนด</td>
				<td>วันที่คืน</td>
				<td>ผู้รับคืน</td>
				<td>ผู้ทำรายการ</td>
				<td>วันเวลาทำรายการ</td>
				<td></td>
			</tr>
			<?php
			$qry_fr=pg_query("select auto_id,a.\"securID\",\"numDeed\",\"fullname\" as \"userRequest\",\"full_name\" as \"cusReceive\",\"dateRequest\",a.\"returnDate\"
			from \"temp_securities_reqreturns\" a
			left join \"Vfuser\" b on a.\"userRequest\"=b.\"id_user\"
			left join \"VSearchCusCorp\" c on a.\"CusIDReceiveReturn\"=c.\"CusID\"
			left join \"nw_securities\" d on a.\"securID\"=d.\"securID\"
			where \"statusApp\" = '2' order by auto_id");
			$nub=pg_num_rows($qry_fr);
			while($res_fr=pg_fetch_array($qry_fr)){
				$auto_id=$res_fr["auto_id"];
				$securID=$res_fr["securID"];
				$numDeed=$res_fr["numDeed"];
				$userRequest = $res_fr["userRequest"]; 
				$cusReceive = $res_fr["cusReceive"];
				$dateRequest = $res_fr["dateRequest"];
				$returnDate = $res_fr["returnDate"];
				
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
			?>
				<td><a style="cursor:pointer;" onclick="javascipt:popU('frm_IndexDetail.php?numdeed=<?php echo $numDeed; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750');"><font color="#FF1493"><u>
			<?php echo $numDeed; ?></u></font></a></td>
				<td><?php echo $returnDate; ?></td>
				<td align="left"><?php echo $cusReceive; ?></td>
				<td align="left"><?php echo $userRequest; ?></td>
				<td><?php echo $dateRequest; ?></td>
				<td>
					<span onclick="javascript:popU('showDetailReturn.php?auto_id=<?php echo $auto_id; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=600')" style="cursor: pointer;"><u>แสดงรายการ</u></span>
				</td>
				
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=6 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table><br><br>

<!--ประวัติการอนุมัติ 30 รายการล่าสุด-->
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div style="padding-left:5px;"><b>ประวัติการอนุมัติ 30 รายการล่าสุด</b></div>
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
			$qry_fr=pg_query("select a.\"securID\",\"numDeed\",b.\"fullname\" as \"userRequest\",\"full_name\" as \"cusReceive\",\"dateRequest\",a.\"returnDate\",
			e.\"fullname\" as \"appUser\",a.\"appDate\",\"statusApp\"
			from \"temp_securities_reqreturns\" a
			left join \"Vfuser\" b on a.\"userRequest\"=b.\"id_user\"
			left join \"VSearchCusCorp\" c on a.\"CusIDReceiveReturn\"=c.\"CusID\"
			left join \"nw_securities\" d on a.\"securID\"=d.\"securID\"
			left join \"Vfuser\" e on a.\"appUser\"=e.\"id_user\"
			where a.\"statusApp\" <> '2' order by a.\"appDate\" DESC limit 30");
			$nub=pg_num_rows($qry_fr);
			while($res_fr=pg_fetch_array($qry_fr)){
				$securID=$res_fr["securID"];
				$numDeed=$res_fr["numDeed"];
				$userRequest = $res_fr["userRequest"]; 
				$cusReceive = $res_fr["cusReceive"];
				$dateRequest = $res_fr["dateRequest"];
				$returnDate = $res_fr["returnDate"];
				$appUser = $res_fr["appUser"];
				$appDate = $res_fr["appDate"];
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
				<td align="left"><?php echo $appUser; ?></td>
				<td><?php echo $appDate; ?></td>
				<td>
				<?php
				if($statusApp=="1"){
					echo "อนุมัติ";
				}else{
					echo "ไม่อนุมัติ";
				}
				?>
				</td>
				
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=8 align=center height=50 bgcolor=#FFFAFA><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>


</body>
</html>