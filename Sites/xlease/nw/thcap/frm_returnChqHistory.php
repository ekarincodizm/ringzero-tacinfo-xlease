<?php
$show=$_GET["show"];
if($show=="1"){
	include("../../config/config.php");
	if( empty($_SESSION["av_iduser"]) ){
		header("Location:../../index.php");
		exit;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>
<?php
}
?>
<table width="1000" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="13" align="left" style="font-weight:bold;"><?php if($show=="1"){ echo "แสดงประวัติการอนุมัติทั้งหมด"; }else{ ?>แสดงประวัติการอนุมัติ 30 รายการล่าสุด <input type="button" value="แสดงประวัติทั้งหมด" onclick="javascript:popU('frm_returnChqHistory.php?show=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1010,height=650')" style="cursor:pointer;"><?php } ?></td>
			</tr>
			<?php 
			if($show=="1"){
			?>
			<tr bgcolor="#FFFFFF">
				<td colspan="13" align="left" height="25"><u><b>หมายเหตุ</b></u><font color="red"> <span style="background-color:#e5cdf9;">&nbsp;&nbsp;&nbsp;</span> รายการสีม่วง คือ เช็คค้ำประกันหนี้ FACTORING ในกรณีที่ ลูกหนี้ไม่จ่าย จะนำเช็คผู้ขายบิลเข้า ถ้าลูกหนี้จ่ายมาปกติ ก็จะคืนเช็คให้ลูกค้า</font></td>
			</tr>
			<?php
			}
			?>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#D6D6D6" align="center">	
				<td>เลขที่เช็ค</td>
				<td>วันที่บนเช็ค</td>
				<td>เลขที่สัญญา</td>
				<td>ธนาคารที่ออกเช็ค</td>
				<td>สาขา</td>
				<td>จ่ายบริษัท</td>
				<td>ยอดเช็ค(บาท)</td>
				<td>ประเภทเช็ค</td>
				<td>ผู้ทำรายการ</td>
				<td>วันเวลาที่ทำรายการ</td>
				<td>ผู้อนุมัติ</td>
				<td>วันเวลาที่อนุมัติ</td>
				<td>สถานะอนุมัติ</td>
			</tr>
			<?php
			if($show=="1"){
				$con="";
			}else{
				$con="limit 30";
			}
			$qry_fr=pg_query("select *,d.\"fullname\" as add_user,e.\"fullname\" as app_user from finance.thcap_receive_cheque_return a
			left join finance.\"V_thcap_receive_cheque_chqManage\" b on a.\"revChqID\"=b.\"revChqID\"
			left join \"BankProfile\" c on b.\"bankOutID\"=c.\"bankID\"
			left join \"Vfuser\" d on a.\"add_user\"=d.\"id_user\"
			left join \"Vfuser\" e on a.\"app_user\"=e.\"id_user\"
			WHERE \"statusChq\" <> '2' order by a.\"app_stamp\" DESC $con");
			$nub=pg_num_rows($qry_fr);
			$i=0;
			while($res_fr=pg_fetch_array($qry_fr)){
				$revChqID = $res_fr["revChqID"];
				$bankChqNo=$res_fr["bankChqNo"];
				$bankChqDate = $res_fr["bankChqDate"]; 
				$bankName = $res_fr["bankName"]; 
				$bankOutBranch = $res_fr["bankOutBranch"]; 
				$bankChqToCompID = $res_fr["bankChqToCompID"]; 
				$bankChqAmt = $res_fr["bankChqAmt"]; 
				$revChqStatus=$res_fr["revChqStatus"];
				$revChqToCCID = $res_fr["revChqToCCID"];
				$add_stamp=$res_fr["add_stamp"];
				$add_user=$res_fr["add_user"];
				$app_stamp=$res_fr["app_stamp"];
				$app_user=$res_fr["app_user"];
				
				//ตรวจสอบว่าเป็นเช็คประเภทใด
				if($res_fr["isInsurChq"]=="0"){
					if($res_fr["isPostChq"]=="1"){
						$txtchq="เช็คชำระล่วงหน้า";
					}else{
						$txtchq="เช็คปกติ";
					}
				}else{
					$txtchq="เช็คค้ำประกัน";
				}
				
				if($res_fr["statusChq"]=="0"){
					$txtapp="ไม่อนุมัติ";
				}else if($res_fr["statusChq"]=="1"){
					$txtapp="อนุมัติ";
				}
				
				$i+=1;
				if($i%2==0){
					if($res_fr["isInsurChq"]==1){
						echo "<tr bgcolor=\"#e5cdf9\" align=center>";
					}else{
						echo "<tr bgcolor=\"#EEEEEE\" align=center>";
					}
					
				}else{
					if($res_fr["isInsurChq"]==1){
						echo "<tr bgcolor=\"#e5cdf9\" align=center>";
					}else{
						echo "<tr bgcolor=\"#F5F5F5\" align=center>";
					}
				}
			?>
				
				<td><?php echo $bankChqNo; ?></td>
				<td><?php echo $bankChqDate; ?></td>
				<td><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $revChqToCCID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"  >				
				<font color="red"><u><?php echo $revChqToCCID; ?></u></font></span></td>
				<td align="left"><?php echo $bankName; ?></td>
				<td><?php echo $bankOutBranch; ?></td>
				<td><?php echo $bankChqToCompID; ?></td>
				<td align="right"><?php echo number_format($bankChqAmt,2); ?></td>
				<td><?php echo $txtchq; ?></td>
				<td align="left"><?php echo $add_user;?></td>	
				<td><?php echo $add_stamp;?></td>	
				<td align="left"><?php echo $app_user;?></td>	
				<td><?php echo $app_stamp;?></td>
				<td><?php echo $txtapp;?></td>	
			</tr>
			
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=13 align=center height=50 bgcolor=\"#FFFFFF\"><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			
			
			?>
			</table>
			</div>
	</td>
</tr>
</table>
<?php
if($show=="1"){
?>
<div align="center" style="padding-top:50px;"><input type="button" value="ปิดหน้านี้" onclick="window.close();"></div>
</body>
</html>
<?php
}
?>

