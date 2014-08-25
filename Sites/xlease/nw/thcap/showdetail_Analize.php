<?php
set_time_limit(0);
session_start();
include("../../config/config.php");

$m = $_GET['m2']; //เดือนที่เลือก
$y = $_GET['y2']; //ปีที่เลือก
$tpID=$_GET['tpID']; //ประเภทที่เลือก

$qrytype=pg_query("select \"tpDesc\" from account.\"thcap_typePay\" where \"tpID\"='$tpID'");
list($tpDesc)=pg_fetch_array($qrytype);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title>สรุปรายได้และวิเคราะห์ย้อนหลัง 12 เดือน</title>
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
.sum{
    background-color:#FFC0C0;
    font-size:12px
}
.sumall{
    background-color:#C0FFC0;
    font-size:12px
}
</style>
</head>
<body>
<form method="post" name="form1" action="process_receiptcancel.php">
<table width="100%" cellSpacing="1" cellPadding="3" border="0" align="center">
<tr><td align="right"><span onclick="window.close();" style="cursor:pointer;"><u>X ปิดหน้านี้</u></span></td></tr>
<tr>
    <td height="25" align="center"><h2>(THCAP) สรุปรายได้และวิเคราะห์ 12 เดือนย้อนหลัง</h2></td>
</tr>
<tr>
    <td height="25"><b><?php echo "$tpID - $tpDesc";?></b></td>
</tr>
<tr>
    <td>
		<table width="100%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#F0F0F0" align="center">
			<tr bgcolor="#79BCFF">
				<th width="70">เดือน/ปี</th>
				<th>จำนวนเงิน</th>
				<th>ภาษีมูลค่าเพิ่ม</th>
				<th>ภาษีหัก ณ ที่จ่าย</th>
				<th>จำนวนเงินรับรวม</th>
			</tr>
			
		<?php	
			for($p=0;$p<12;$p++){
				$datenow=$y."-".$m."-"."01"; //วันที่เริ่มแรกที่ต้องการให้แสดง
				
				/*//กำหนดค่าเริ่มต้นให้กับตัวแปร ทุกครั้งที่วนลูปจะคืนค่าเป็น 0 เพื่อรองรับค่าใหม่////*/
					$sumnetAmt=0; //netAmt
					$sumvatAmt=0; //vatAmt
					$sumwhtAmt=0; //whtAmt
					$sumdebtAmt=0; //debtAmt
								
								
					$a[0]=0;
					$a[1]=0;
					$a[2]=0;
					$a[3]=0;
								
				/*////////////*/
				
				//ข้อมูลปัจจุบันของแต่ละเดือน************************
					$qryvalue=pg_query("SELECT unnest(\"thcap_cal_sumTypePay\"('$tpID','$y','$m'))");
					$c=0;
					while($resvalue=pg_fetch_array($qryvalue)){
						$a[$c]=$resvalue["unnest"];
						$c++;
					}	
					$sumnetAmt=$a[0]; //netAmt
					$sumvatAmt=$a[1]; //vatAmt
					$sumwhtAmt=$a[2]; //whtAmt
					$sumdebtAmt=$a[3]; //debtAmt
				//จบข้อมูลปัจจุบัน**********************
								
				//ตรวจสอบว่าเป็นชำระตามสัญญากู้หรือไม่
				$chktype=pg_getminpaytype($tpID);
				$chktypeprinc=pg_getprincipletype($tpID);
				$chktypeint=pg_getinteresttype($tpID);
								
				if($tpID==$chktypeprinc || $tpID==$chktypeint){
					$sumnetAmt=$sumdebtAmt;
				}
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=\"right\">";
				}else{
					echo "<tr class=\"even\" align=\"right\">";
				}
																
				echo "
					<td align=center $color2>$m/$y</td>
					<td $color2>".number_format($sumnetAmt,2)."</td>
					<td $color2>".number_format($sumvatAmt,2)."</td>
					<td $color2>".number_format($sumwhtAmt,2)."</td>
					<td $color2>".number_format($sumdebtAmt,2)."</td>
				</tr>";
				
				
				$sumnet+=$sumnetAmt;
				$sumvat+=$sumvatAmt;
				$sumdebt+=$sumdebtAmt;
				$sumwht+=$sumwhtAmt;	
				
				//หาเดือนก่อนหน้าที่เลือก 12 เดือน
				$qrymonth3=pg_query("SELECT date(date('$datenow') - interval '1 month')");
				list($beformonth2)=pg_fetch_array($qrymonth3);
				list($y,$m,$d)=explode("-",$beformonth2);	
			} //end for
				
			
			echo "<tr>
					<td class=\"sum\" align=right width=\"120\" ><b>รวม</b></td>
					<td align=right class=\"sum\" width=\"100\"><b>".number_format($sumnet,2)."</b></td>
					<td align=right class=\"sum\" width=\"100\"><b>".number_format($sumvat,2)."</b></td>
					<td align=right class=\"sum\" width=\"100\"><b>".number_format($sumwht,2)."</b></td>
					<td align=right class=\"sum\" width=\"100\"><b>".number_format($sumdebt,2)."</b></td>
				</tr>";
		?>
		</table>
	</td>
</tr>

</table>
</form>
</body>
</html>