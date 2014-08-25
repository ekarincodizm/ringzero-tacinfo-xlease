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
	
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}


</script>

</head>
<body>
<form name="frm">
<table width="990" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="header"><h1><?php echo $_SESSION['session_company_name']; ?></h1></div>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" style="font-weight:bold;">(THCAP) อนุมัติการยกเว้นหนี้</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<td>เลขที่สัญญา</td>
				<td>รหัสประเภท<br>ค่าใช้จ่าย</td>
				<td>รายละเอียดค่าใช้จ่าย</td>
				<td>ค่าอ้างอิง<br>ของค่าใช้จ่าย</td>
				<td>วันที่ตั้งหนี้</td>
				<td>จำนวนหนี้</td>
				<td>ผู้ขอยกเว้นหนี้</td>
				<td>วันเวลาขอยกเว้นหนี้</td>
				<td>ทำรายการอนุมัติ</td>
			</tr>
			<?php
			$qry_fr=pg_query("select * from \"thcap_temp_except_debt\" where \"Approve\" is null order by \"doerStamp\" , \"debtID\" ");
			$nub=pg_num_rows($qry_fr);
			while($res_fr=pg_fetch_array($qry_fr)){
				$debtID=$res_fr["debtID"];
				$doerUser=$res_fr["doerUser"];
				$doerStamp=$res_fr["doerStamp"];
				$remark=$res_fr["remark"];
				
				$qry_detail=pg_query("select * from \"thcap_v_otherpay_debt_realother_current\" where \"debtID\" = '$debtID' ");
				while($res_detail=pg_fetch_array($qry_detail))
				{
					$typePayID = $res_detail["typePayID"];
					$typePayRefValue = $res_detail["typePayRefValue"];
					$typePayRefDate = $res_detail["typePayRefDate"];
					$typePayAmt = $res_detail["typePayAmt"];
					$typePayLeft = $res_detail["typePayLeft"]; // หนี้ค้างชำระปัจจุบัน
					$contractID = $res_detail["contractID"];
				}
				
				// หาชื่อผู้ทำรายการขอยกเว้นหนี้
				$sqlNameUser = pg_query("SELECT  fullname  FROM \"Vfuser\" where username = '$doerUser'");
				$fullnameUser = pg_fetch_result($sqlNameUser,0);
				
				// หารายละเอียดค่าใช้จ่ายนั้นๆ
				$qry_tpDesc = pg_query("select * from account.\"thcap_typePay\" where \"tpID\" = '$typePayID' ");
				while($res_tpDesc = pg_fetch_array($qry_tpDesc))
				{
					$tpDescShow = $res_tpDesc["tpDesc"];
				}
				
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
			?>
				<td><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID;?></u></font></span></td>
				<td><?php echo $typePayID; ?></td>
				<td align="left"><?php echo $tpDescShow; ?></td>
				<td><?php echo $typePayRefValue; ?></td>
				<td><?php echo $typePayRefDate; ?></td>
				<td align="right"><?php echo number_format($typePayLeft,2); ?></td>
				<td align="left"><?php echo $fullnameUser; ?></td>
				<td><?php echo $doerStamp; ?></td>
				<!-- <td align="left"><?php echo $remark; ?></td> -->
				<td><?php echo "<a href=\"#\" onclick=\"javascript:popU('detail_debt.php?debtID=$debtID&show=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=600')\"><u>ทำรายการ</u></a>"; ?></td>
				<!--td>
					<a href="process_approve.php?debtID=<?php echo $debtID; ?>&appv=TRUE" title="อนุมัติรายการนี้"><u>อนุมัติ</u></a>
				</td>
				<td><a href="process_approve.php?debtID=<?php echo $debtID; ?>&appv=FALSE" title="ไม่อนุมัติรายการนี้"><u>ไม่อนุมัติ</u></a></td-->
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=8 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</td>
</tr>	
</table>
<?php
include("frm_history_limit.php");
?>
</form>
</body>
</html>