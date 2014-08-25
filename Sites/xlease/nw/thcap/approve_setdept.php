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
<script language="javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>

<table width="1100" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="header"><h1><?php echo $_SESSION["session_company_name_thcap"]; ?></h1></div>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" style="font-weight:bold;">(THCAP) อนุมัติการตั้งหนี้เงินกู้</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<td>เลขที่สัญญา</td>
				<td>รหัสประเภท<br>ค่าใช้จ่าย</td>
				<td>รายละเอียดค่าใช้จ่าย</td>
				<td>รายละเอียดค่าอ้างอิง<br>ของค่าใช้จ่าย</td>
				<td>ค่าอ้างอิง<br>ของค่าใช้จ่าย</td>
				<td>วันที่ตั้งหนี้</td>
				<td>วันที่ครบกำหนดชำระ</td>
				<td>จำนวนหนี้</td>
				<td>ผู้ตั้งหนี้</td>
				<td>วันเวลาที่ตั้งหนี้</td>
				<td>ทำรายการอนุมัติ</td>
			</tr>
			<?php
			$qry_fr=pg_query("select * from \"thcap_temp_otherpay_debt\" a
				left join \"Vfuser\" b on a.\"doerID\"=b.\"id_user\"
				where \"debtStatus\" = '9' and \"ShowAppvStatus\"='1' order by \"debtID\" ");
			$nub=pg_num_rows($qry_fr);
			while($res_fr=pg_fetch_array($qry_fr)){
				$debtID=$res_fr["debtID"];
				$contractID=$res_fr["contractID"];
				$typePayID=$res_fr["typePayID"];
				$typePayRefValue=$res_fr["typePayRefValue"];
				$typePayRefDate=$res_fr["typePayRefDate"];
				$typePayAmt=$res_fr["typePayAmt"];
				$fullname=$res_fr["fullname"];
				$doerStamp=$res_fr["doerStamp"];
				$debtDueDate=$res_fr["debtDueDate"]; // วันที่ครบกำหนดชำระ
				
				// หารายละเอียดค่าใช้จ่ายนั้นๆ
				$qry_tpDesc = pg_query("select \"tpDesc\",\"tpFullDesc\" from account.\"thcap_typePay\" where \"tpID\" = '$typePayID' ");
				while($res_tpDesc = pg_fetch_array($qry_tpDesc))
				{
					$tpDesc = $res_tpDesc["tpDesc"];
					$tpFullDesc=$res_tpDesc["tpFullDesc"];
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
				<td><?php echo $tpDesc; ?></td>
				<td><?php echo $tpFullDesc; ?></td>
				<td><?php echo $typePayRefValue; ?></td>
				<td><?php echo $typePayRefDate; ?></td>
				<td><?php if($debtDueDate != ""){echo $debtDueDate;}else{echo "ไม่มีวันครบกำหนดชำระ";} ?></td>
				<td align="right"><?php echo number_format($typePayAmt,2); ?></td>
				<td align="left"><?php echo $fullname; ?></td>
				<td><?php echo $doerStamp; ?></td>
				<?php
					//กำหนดขนาด popup 
					$qry_cancel_note_chk = pg_query("SELECT * FROM \"thcap_temp_otherpay_debt\" WHERE \"typePayID\" = '$typePayID' AND \"typePayRefValue\" = '$typePayRefValue' AND \"contractID\" = '$contractID' AND \"debtID\" != '$debtID'");
					$row_cancel_note_chk = pg_num_rows($qry_cancel_note_chk);
					IF($row_cancel_note_chk > 0){ $pop_h = '650'; }else{ $pop_h = '550';  }
				
				?>
				<td><span onclick="javascript:popU('show_remark.php?debtID=<?php echo $debtID; ?>&show=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=<?php echo $pop_h; ?>')" style="cursor: pointer;"><img src="images/detail.gif" height="19" width="19" border="0"></span></td>
				<!--td>
					<a href="process_approve.php?debtID=<?php echo $debtID; ?>&stsapp=1&typeapp=setdept" title="อนุมัติรายการนี้"><u>อนุมัติ</u></a>
				</td>
				<td><a href="process_approve.php?debtID=<?php echo $debtID; ?>&stsapp=0&typeapp=setdept" title="ไม่อนุมัติรายการนี้"><u>ไม่อนุมัติ</u></a></td-->
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=11 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</td>
</tr>	
</table>
<table width="1100" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="wrapper">
		<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#FFFFFF">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" style="font-weight:bold;">การตั้งหนี้เงินกู้ที่ได้ทำการอนุมัติ 30 รายการล่าสุด<input type="button" value="แสดงประวัติทั้งหมด" onclick="javascript:popU('frm_history.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')" style="cursor:pointer;"></td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#D6D6D6" align="center">
				<td>เลขที่สัญญา</td>
				<td>รหัสประเภท<br>ค่าใช้จ่าย</td>
				<td>รายละเอียด<br>ค่าใช้จ่าย</td>
				<td>ค่าอ้างอิงของ<br>ค่าใช้จ่าย</td>
				<td>วันที่ตั้งหนี้</td>
				<td>วันที่ครบกำหนดชำระ</td>
				<td>จำนวนหนี้</td>
				<td>ผู้ตั้งหนี้</td>
				<td>วันเวลาตั้งหนี้ </td>
				<td>ผู้อนุมัติหนี้ </td>
				<td>วันเวลาทำรายการอนุมัติ </td>
				<td>เหตุผล</td>
				<td>ผลการอนุมัติ</td>				
			</tr>
			<?php
			$qry_fr1=pg_query("select * from \"thcap_temp_otherpay_debt\" a
				left join \"Vfuser\" b on a.\"doerID\"=b.\"id_user\"
				where \"debtStatus\" != '9' and \"appvID\" != '000' order by \"appvStamp\" DESC limit 30 ");
			$nub=pg_num_rows($qry_fr1);
			while($res_fr=pg_fetch_array($qry_fr1)){
				$debtIDshow = $res_fr["debtID"];
				$doerID=$res_fr["doerID"];
				$doerStamp=$res_fr["doerStamp"];
				$appvID=$res_fr["appvID"];
				$appvStamp=$res_fr["appvStamp"];
				$debtStatus=$res_fr["debtStatus"];
				$fullname=$res_fr["fullname"];
				$contractID=$res_fr["contractID"];
				$typePayID=$res_fr["typePayID"];
				$typePayRefValue=$res_fr["typePayRefValue"];
				$typePayRefDate=$res_fr["typePayRefDate"];
				$typePayAmt=$res_fr["typePayAmt"];
				$debtDueDate=$res_fr["debtDueDate"]; // วันที่ครบกำหนดชำระ
				
				// หารายละเอียดค่าใช้จ่ายนั้นๆ
				$qry_tpDesc = pg_query("select * from account.\"thcap_typePay\" where \"tpID\" = '$typePayID' ");
				while($res_tpDesc = pg_fetch_array($qry_tpDesc))
				{
					$tpDesc = $res_tpDesc["tpDesc"];
				}
				
				$i+=1;
				if($i%2==0){
					echo "<tr bgcolor=#EEEEEE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEEEEE';\" align=center>";
				}else{
					echo "<tr bgcolor=#F5F5F5 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#F5F5F5';\" align=center>";
				}
				
			if($debtStatus == '0'){
				$appstatus = 'ยกเลิก';
			}else if($debtStatus == '1'){
				$appstatus = 'อนุมัติ';
			}else if($debtStatus == '2'){
				$appstatus = 'อนุมัติ (จ่ายครบแล้ว)';
			}else if($debtStatus == '3'){
				$appstatus = 'waive รายการ (ยกเว้นหนี้)';
			}else if($debtStatus == '4'){
				$appstatus = 'ยกเลิกใบเสร็จ';
			}else if($debtStatus == '5'){
				$appstatus = 'ลดหนี้เป็น 0.00';
			}else{
				$appstatus = 'ไม่สามารถระบุได้';
			}		
	
			$sqlappuser = pg_query("SELECT  fullname  FROM \"Vfuser\" where id_user = '$appvID'");
			$reappuser = pg_fetch_result($sqlappuser,0);
	
				
			?>	
				<td><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID;?></u></font></span></td>
				<td><?php echo $typePayID; ?></td>
				<td><?php echo $tpDesc; ?></td>
				<td><?php echo $typePayRefValue; ?></td>
				<td><?php echo $typePayRefDate; ?></td>
				<td><?php if($debtDueDate != ""){echo $debtDueDate;}else{echo "ไม่มีวันครบกำหนดชำระ";} ?></td>
				<td align="right"><?php echo number_format($typePayAmt,2); ?></td>
				<td align="left"><?php echo $fullname; ?></td>
				<td align="center"><?php echo $doerStamp; ?></td>
				<td align="left"><?php echo $reappuser; ?></td>
				<td align="center"><?php echo $appvStamp; ?></td>
				<td><span onclick="javascript:popU('show_remark.php?debtID=<?php echo $debtIDshow; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=650')" style="cursor: pointer;"><img src="images/detail.gif" height="19" width="19" border="0"></span></td>
				<td align="center"><?php echo $appstatus; ?></td>
			</tr>
			<?php } ?>
			<tr bgcolor="#D6D6D6">
				<td colspan="13" align="right" >จำนวนแสดง : <?php echo $nub; ?>  รายการ</td>
			</tr>
			</table><br>
		</div>
	</td>
</tr>	
</table>

</body>
</html>