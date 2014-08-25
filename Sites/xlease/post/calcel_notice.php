<?php
session_start();
set_time_limit(0);
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}

include("../config/config.php");
$nowdate = Date('Y-m-d');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ยกเลิก 	NT</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
<script language=javascript>
<!--
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
//-->
</script>

    </head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>

<div class="header"><h1><?php echo $_SESSION['session_company_name']; ?></h1></div>
<div class="wrapper">

<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
    <tr bgcolor="#FFFFFF">
        <td colspan="11" align="left" style="font-weight:bold;">ยกเลิก NT</td>
    </tr>
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">เลขที่สัญญา</td>
        <td align="center">ชื่อ</td>
        <td align="center">ทะเบียน</td>
        <td align="center">ยอด NT</td>
        <td align="center">เงินรับฝาก</td>
    </tr>

<?php

$qry_fr=pg_query("select * from \"NTHead\" WHERE cancel='false' AND \"CusState\"='0' AND \"cancelid\" IS NULL
				AND \"NTID\" not in(select \"NTID\" from \"nw_statusNT\" WHERE \"statusNT\" in('0','2'))
				ORDER BY \"IDNO\" ASC;");

while($res_fr=pg_fetch_array($qry_fr)){
    $NTID = $res_fr["NTID"];
    $IDNO = $res_fr["IDNO"];
	
	//ตรวจสอบว่ามีข้อมูลในตาราง nw_statusNT หรือไม่
	$qrychk=pg_query("select \"user_approve\" from \"nw_statusNT\" where \"NTID\"='$NTID'");
	$numchk=pg_num_rows($qrychk);
	if($numchk>0){ //กรณีมีข้อมูลในนี้แสดงว่าเป็นข้อมูลที่สร้างขึ้นหลังจากใช้ระบบใหม่ที่มีการอนุมัติ NT ก่อน
		list($user_approve)=pg_fetch_array($qrychk);
		if($user_approve==""){ //ถ้ายังไม่พบผู้อนุมัติรายการ แสดงว่า NT นี้ยังไม่ได้รับการอนุมัติ จะไม่สามารถยกเลิกได้
			$statuscancel=1;
		}
	}
    
	if($statuscancel!=1){
		$nub+=1;
    
		$qry_vc=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
		if($res_vc=pg_fetch_array($qry_vc)){
			if($res_vc["asset_type"] == 1) $show_regis = $res_vc["C_REGIS"]; else $show_regis = $res_vc["car_regis"];
			$dp_balance = $res_vc["dp_balance"]; $dp_balance = round($dp_balance,2);
		}
		
		$qry_amt=pg_query("select SUM(\"Amount\") as amtmoney from \"NTDetail\" WHERE \"NTID\"='$NTID' ");
		if($res_amt=pg_fetch_array($qry_amt)){
			$amtmoney = $res_amt["amtmoney"]; $amtmoney = round($amtmoney,2);
		}
				
			$i+=1;
			if($i%2==0){
				echo "<tr class=\"odd\">";
			}else{
				echo "<tr class=\"even\">";
			}
		?>
			<td align="center">
				<span onclick="javascript:popU('cancel_add_detail.php?idno=<?php echo "$IDNO"; ?>','<?php echo "$IDNO_cc_shownt"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=650,height=250')" style="cursor: pointer;" title="ยกเลิกรายการนี้"><u><?php echo $IDNO; ?></u></span>
			</td>
			<td align="left"><?php echo $res_vc["full_name"]; ?></td>
			<td align="left"><?php echo $show_regis; ?></td>
			<td align="right"><?php echo number_format($amtmoney,2); ?></td>
			<td align="right"><?php echo number_format($dp_balance,2); ?></td>
		</tr>
		<?php
	}
	unset($statuscancel);
}
?>

    <tr>
        <td align="left" colspan="5">เลือกรายการที่ต้องการยกเลิก NT</td>
    </tr>

<?php 
if($nub > 0){
?>
    <tr>
        <td align="right" colspan="5">ทั้งหมด <?php echo $nub; ?> รายการ <a href="cancel_notice_pdf.php" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" alt=""> สั่งพิมพ์</a></td>
    </tr>
<?php
}
?>
<?php 
if($nub == 0){   
?>
    <tr><td colspan="10" align="center">- ไม่พบข้อมูล -</td></tr>        
<?php
}
?>
</table>

<div align="center"><br><input type="button" value="  Close  " onclick="javascript:window.close();"></div>

        </td>
    </tr>
</table>

</body>
</html>