<?php
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

$app_date = Date('Y-m-d H:i:s');
$relpaths_all = redirect($_SERVER['PHP_SELF'],'nw/approve_except_debt');
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

<table width="990" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
<tr>
	<td>
		<div class="wrapper">
		<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#FFFFFF">
			<tr bgcolor="#FFFFFF">
				<td colspan="12" align="center" style="font-weight:bold;"><h2>ประวัติการอนุมัติยกเว้นหนี้ทั้งหมด</h2></td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#D6D6D6" align="center">
				<td>เลขที่สัญญา</td>
				<td>รหัสประเภท<br>ค่าใช้จ่าย</td>
				<td>รายละเอียด<br>ค่าใช้จ่าย</td>
				<td>ค่าอ้างอิงของ<br>ค่าใช้จ่าย</td>
				<td>วันที่ตั้งหนี้</td>
				<td>จำนวนหนี้</td>
				<td>ผู้ขอยกเว้นหนี้</td>
				<td>วันเวลาขอยกเว้นหนี้  </td>
				<td>ผู้อนุมัติยกเว้นหนี้  </td>
				<td>วันเวลาทำรายการ<br>อนุมัติยกเว้นหนี้  </td>
				<td>ผลการ<br>อนุมัติ</td>	
                <td>เหตุผล</td>				
			</tr>
			<?php
			$sqlappuser = pg_query("SELECT  username  FROM \"Vfuser\" where id_user = '000'");
			$reappuser = pg_fetch_result($sqlappuser,0);
			$qry_fr=pg_query("select * from \"thcap_temp_except_debt\" where \"Approve\" is not null and \"appvUser\" != '$reappuser' order by \"appvStamp\" DESC ");
			$nub=pg_num_rows($qry_fr);
			while($res_fr=pg_fetch_array($qry_fr)){
				$debtID=$res_fr["debtID"];
				$doerUser=$res_fr["doerUser"];
				$doerStamp=$res_fr["doerStamp"];
				$appvUser=$res_fr["appvUser"];
				$appvStamp=$res_fr["appvStamp"];
				$Approve=$res_fr["Approve"];
				
				$qry_detail=pg_query("select * from \"thcap_v_otherpay_debt_realother\" where \"debtID\" = '$debtID' ");
				while($res_detail=pg_fetch_array($qry_detail))
				{
					$typePayID = $res_detail["typePayID"];
					$typePayRefValue = $res_detail["typePayRefValue"];
					$typePayRefDate = $res_detail["typePayRefDate"];
					$typePayAmt = $res_detail["typePayAmt"];
					$contractID = $res_detail["contractID"];
				}
				
				// หารายละเอียดค่าใช้จ่ายนั้นๆ
				$qry_tpDesc = pg_query("select * from account.\"thcap_typePay\" where \"tpID\" = '$typePayID' ");
				while($res_tpDesc = pg_fetch_array($qry_tpDesc))
				{
					$tpDescShow = $res_tpDesc["tpDesc"];
				}
			
				$i+=1;
				if($i%2==0){
					echo "<tr bgcolor=#EEEEEE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEEEEE';\" align=center >";
				}else{
					echo "<tr bgcolor=#F5F5F5 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#F5F5F5';\"  align=center>";
				}
				
			if($Approve == "t"){
				$appstatus = 'อนุมัติ';
			}else if($Approve == "f"){
				$appstatus = 'ไม่อนุมัติ';
			}else{
				$appstatus = '';
			}
	
			
			$sqlnameuser = pg_query("SELECT  fullname  FROM \"Vfuser\" where username = '$doerUser'");
			$renameuser = pg_fetch_result($sqlnameuser,0);
			
			$sqlnameqppuser = pg_query("SELECT  fullname  FROM \"Vfuser\" where username = '$appvUser'");
			$renameqppuser = pg_fetch_result($sqlnameqppuser,0);
				
			?>
				<td><span   onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID;?></u></font></span></td>
				<td><?php echo $typePayID; ?></td>
				<td align="left"><?php echo $tpDescShow; ?></td>
				<td><?php echo $typePayRefValue; ?></td>
				<td><?php echo $typePayRefDate; ?></td>
				<td align="right"><?php echo number_format($typePayAmt,2); ?></td>
				<td align="left"><?php echo $renameuser; ?></td>
				<td align="center"><?php echo $doerStamp; ?></td>
				<td align="left"><?php echo $renameqppuser; ?></td>
				<td align="center"><?php echo $appvStamp; ?></td>
				<td align="center"><?php echo $appstatus; ?></td>
                <td align="center">
                	<img src="images/detail.gif" width="19" height="19" onclick="javascript:popU('<?php echo $relpaths_all; ?>/detail_debt.php?debtID=<?php echo $debtID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=600')" style="cursor:pointer;" />
                </td>
			</tr>
			<?php } ?>
			<tr bgcolor="#D6D6D6">
				<td colspan="12" align="right" >จำนวนแสดง : <?php echo $aprows = pg_num_rows($qry_fr); ?>  รายการ</td>
			</tr>
			</table><br>
		</div>
	</td>
</tr>	
</table>
<div style="text-align:center;"><input type="button" value="ปิด" onclick="window.close();" style="width:100px;height:50px;"></div>
</body>
</html>