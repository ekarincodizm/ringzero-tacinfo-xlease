<?php
include("../../config/config.php");
$rootpath = redirect($_SERVER['PHP_SELF'],''); // rootpath สำหรับเรียกไฟล์ PHP โดยเริ่มต้นที่ root

$option = pg_escape_string($_GET["option"]);
$contype = pg_escape_string($_GET["contype"]);
$contypepdf = pg_escape_string($_GET["contype"]); //สำหรับส่งไปหน้า พิมพ์ PDF หรือ Excel

if($option=="day"){
	$datecon=$_GET["datecon"];
	$condition="AND date(\"doerStamp\") = '$datecon' ";
}else if($option=="my"){
	$month=$_GET["month"];
	$year=$_GET["year"];
	$condition = "AND EXTRACT(MONTH FROM \"doerStamp\") = '$month' AND EXTRACT(YEAR FROM \"doerStamp\") = '$year' ";

}else if($option=="year"){
	$year=$_GET["year"];
	$condition = "AND EXTRACT(YEAR FROM \"doerStamp\") = '$year' ";
}

$contypeqry="";

$contype = explode("@",$contype);
for($con = 0;$con < sizeof($contype) ; $con++){
	if($contype[$con] != ""){	
		if($contypeqry == "" ){
			if($contype[$con] == "1"){ //กรณีที่อนุมัิติและจ่ายแล้ว
				$contypeqry = "(\"dcNoteStatus\" = '1' AND (\"debtStatus\"='2' OR \"debtStatus\"='5')) ";
			}else if($contype[$con] == "2"){ //กรณีที่อนุมัติและยังไม่จ่าย
				$contypeqry = "(\"dcNoteStatus\" = '1' AND \"debtStatus\"='1') ";
			}else{
				$contypeqry = "\"dcNoteStatus\" = '$contype[$con]' ";
			}
		}else{
			if($contype[$con] == "1"){ //กรณีที่อนุมัิติและจ่ายแล้ว
				$contypeqry = $contypeqry."OR (\"dcNoteStatus\" = '1' AND (\"debtStatus\"='2' OR \"debtStatus\"='5')) ";
			}else if($contype[$con] == "2"){ //กรณีที่อนุมัติและยังไม่จ่าย
				$contypeqry = $contypeqry."OR (\"dcNoteStatus\" = '1' AND \"debtStatus\"='1') ";
			}else{
				$contypeqry = $contypeqry."OR \"dcNoteStatus\" = '$contype[$con]' ";
			}
		}		
	}
}

if($contypeqry != ""){
	$contypeqry = "AND (".$contypeqry.")";
	$condition = $condition.$contypeqry;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title><?php echo $Menu_title; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
		<link type="text/css" rel="stylesheet" href="<?php echo $rootpath; ?>/nw/thcap/act.css"></link>
		<link type="text/css" href="<?php echo $rootpath; ?>/jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
			<script type="text/javascript" src="<?php echo $rootpath; ?>/jqueryui/js/jquery-1.4.2.min.js"></script>
			<script type="text/javascript" src="<?php echo $rootpath; ?>/jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
<div style="float:left;padding-left:5px">
	<font color="blue">
		<span style="background-color:#DDDDDD;">&nbsp;&nbsp;&nbsp;</span> สีเทา : แสดงรายการที่ไม่อนุมัติ
		<span style="background-color:#FFCC99;">&nbsp;&nbsp;&nbsp;</span> สีส้ม : แสดงรายการระหว่างรออนุมัติ
		<span style="background-color:#CCFF99;">&nbsp;&nbsp;&nbsp;</span> สีเขียว : แสดงรายการที่อนุมัติและลูกค้ามีการจ่ายแล้ว
		<span style="background-color:#CCFFFF;">&nbsp;&nbsp;&nbsp;</span> สีฟ้า : แสดงรายการที่อนุมัติและลูกค้ายังไม่ได้จ่าย
	</font>
</div>
<div style="float:right;">
<img src="images/print.gif" height="20px"><a href="javascript:popU('frm_print_pdf.php?contype=<?php echo $contypepdf; ?>&option=<?php echo $option; ?>&datecon=<?php echo $datecon;?>&month=<?php echo $month; ?>&year=<?php echo $year; ?>')"><b><u>พิมพ์ (PDF)</u></b></a>
<img src="../thcap/thcap_capital_interest_lastweek/images/excel.png" height="20px"><a href="javascript:popU('frm_print_excel.php?contype=<?php echo $contypepdf; ?>&option=<?php echo $option; ?>&datecon=<?php echo $datecon;?>&month=<?php echo $month; ?>&year=<?php echo $year; ?>')"><b><u>พิมพ์(Excel)</u></b></a>
</div>
<div style="clear:both;"></div>
	<table align="center" bgcolor="#FFE1EE" frame="box" width="99%" cellspacing="1" cellpadding="1">					
	<tr bgcolor="#FFCCCC" height="25">
		<th>เลขที่ CN/DN</th>
		<th>เลขที่สัญญา</th>
		<th>ชื่อผู้กู้หลัก/ผู้เช่าซื้อ</th>
		<th>รหัสประเภทค่าใช้จ่าย</th>
		<th>รายละเอียดหนี้</th>
		<th>เลขอ้างอิง</th>
		<th>จำนวนหนี้แรกเริ่ม</th>
		<th>จำนวนหนี้เดิมล่าสุด</th>
		<th>จำนวนหนี้ใหม่</th>
		<th>ผู้ทำรายการ</th>
		<th>วันเวลาทำรายการ</th>
		<th>ผู้อนุมัติ</th>
		<th>วันเวลาอนุมัติ</th>
		<th>สถานะ</th>
	</tr>
	<?php
	$qry = pg_query("SELECT  * FROM account.thcap_dncn_discount_report where \"dcType\" = '2' $condition ");
	$row = pg_num_rows($qry);
	//-- หากมีข้อมูล
	if($row != 0){	
	$i = 0; //--== กำหนดตัวแปรไว้วนจำนวนแถวเพื่อสลับสีแถวข้อมูล ( ไม่จำเป็นต้องเปลี่ยน )
	while($res = pg_fetch_array($qry))
	{
		$dcNoteID = $res["dcNoteID"]; // รหัส CreditNote หรือ DebitNote
		$conid = $res["contractID"];	//เลขที่สัญญา		
		$maincus_fullname = $res["maincus_fullname"]; //-- หาชื่อผู้กู้หลัก
		$typePayID = $res["typePayID"]; // รหัสประเภทค่าใช้จ่าย
		$tpdetail = $res["tpdetail"]; // รายละเอียดประเภทค่าใช้จ่าย
		$typePayRefValue = $res["typePayRefValue"];// หา Ref
		$netstart=number_format($res["netstart"],2)."(".number_format($res["vatstart"],2).")"; //จำนวนหนี้แรกเริ่ม
		$netbefore=number_format($res["netbefore"],2)."(".number_format($res["vatbefore"],2).")"; //จำนวนหนี้เดิืมล่าสุด
		$netnow=number_format($res["netnow"],2)."(".number_format($res["vatnow"],2).")"; //จำนวนหนี้ใหม่
		$doer_fullname=$res["doerName"]; //ชื่อผู้ขอ
		$doerStamp = $res["doerStamp"]; //วันที่ขอ
		$appv_fullname=$res["appvName"]; //ชื่อผู้อนุมัติ
		$appvStamp=$res["appvStamp"]; //วันเวลาที่อนุมัติ
		$status = $res["statusname"];//ชื่อสถานะการอนุมัติ		
		$dcNoteStatus = $res["dcNoteStatus"];//สถานะการอนุมัติ
		$debtStatus = $res["debtStatus"];//สถานะการจ่าย
		
		if($debtStatus == 5)
		{
			$status = "อนุัมัติและลดหนี้เป็น 0.00";
		}
		
		// -- บวกตัวแปร i เพื่อนำมาคำนวณเพื่อกำหนดการสลับสีของแถว
			$i++;									
			if($i%2==0){
				$bgcolor_TR2 = "#FFEEEE"; // สีพื้นหลังข้อมูล
				
				if($dcNoteStatus==0){
					$bgcolor_TR2 = "#DDDDDD"; // แสดงรายการที่ไม่อนุมัติ
				}else if($dcNoteStatus==8){
					$bgcolor_TR2 = "#FFCC99"; // แสดงรายการระหว่างรออนุมัติ
				}else if($dcNoteStatus==1 and ($debtStatus=2 OR $debtStatus=5)){ 
					$bgcolor_TR2 = "#CCFF99"; //แสดงรายการที่อนุัมัติและลูกค้ามีการจ่ายแล้ว
				}else if($dcNoteStatus==1 and $debtStatus==1){ 
					$bgcolor_TR2 = "#CCFFFF"; //แสดงรายการที่อนุัมัติและลูกค้ายังไม่ไ่ด้จ่าย
				}
			}else{
				$bgcolor_TR2 = "#FFFFFF"; // สีพื้นหลังข้อมูล
				
				if($dcNoteStatus==0){
					$bgcolor_TR2 = "#DDDDDD"; // แสดงรายการที่ไม่อนุมัติ
				}else if($dcNoteStatus==8){
					$bgcolor_TR2 = "#FFCC99"; // แสดงรายการระหว่างรออนุมัติ
				}else if($dcNoteStatus==1 and ($debtStatus=2 OR $debtStatus=5)){ 
					$bgcolor_TR2 = "#CCFF99"; //แสดงรายการที่อนุัมัติและลูกค้ามีการจ่ายแล้ว
				}else if($dcNoteStatus==1 and $debtStatus==1){ 
					$bgcolor_TR2 = "#CCFFFF"; //แสดงรายการที่อนุัมัติและลูกค้ายังไม่ไ่ด้จ่าย
				}
			} 
	?>
	<tr bgcolor="<?php echo $bgcolor_TR2; ?>" onmouseover="javascript:this.bgColor='<?php echo $bgcolor_HL2; ?>'" onmouseout="javascript:this.bgColor='<?php echo $bgcolor_TR2; ?>'" align="center" height="25">
		<td align="center">
			<span onclick="javascript:popU('<?php echo $rootpath; ?>/nw/thcap_dncn/popup_dncn.php?idapp=<?php echo $dcNoteID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=700')" style="cursor:pointer;"  >
			<font color="blue"><u><?php echo "$dcNoteID"?><u></font></span>
		</td>
		<td align="center">
			<span onclick="javascript:popU('<?php echo $rootpath; ?>/nw/thcap_installments/frm_Index.php?show=1&idno=<?php echo $conid?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"  >
			<font color="red"><u><?php echo "$conid"?><u></font></span>
		</td>
		<td align="left"><?php echo "$maincus_fullname"; ?></td>
		<td><?php echo "$typePayID"; ?></td>
		<td align="left"><?php echo "$tpdetail"; ?></td>
		<td><?php echo "$typePayRefValue"; ?></td>
		<td align="right"><?php echo $netstart; ?></td>
		<td align="right"><?php echo $netbefore; ?></td>
		<td align="right"><?php echo $netnow; ?></td>		
		<td align="left"><?php echo "$doer_fullname"; ?></td>
		<td><?php echo "$doerStamp"; ?></td>	
		<td align="left"><?php echo "$appv_fullname"; ?></td>
		<td><?php echo "$appvStamp" ?></td>
		<td><?php echo "$status";?></td>		
	</tr>
	<?php		
	unset($namecoopall);
	}
	}else{  echo "<tr bgcolor=\"\"><td align=\"center\" colspan=\"13\"><h2> ไม่พบรายการ  </h2></td></tr>"; }?>			
	</table>
</body>
</html>