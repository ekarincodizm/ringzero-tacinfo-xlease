<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>(THCAP) จัดการประเภทค่าใช้จ่าย</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>  
</head>
<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
<body>

<table width="95%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
	<!--<tr>
		<td background=><img src="images/bg_01.jpg" height="15" width="95%"></td>
	</tr>-->
	<tr>
		<td align="center" valign="top" style="background-repeat:repeat-y">

<div class="header"><h1>(THCAP) จัดการประเภทค่าใช้จ่าย</h1></div>
<!--<div class="wrapper">-->

<div align="right">
	<a onclick="popU('frm_thcap_acc.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1200,height=800');" style="cursor:pointer;"><img src="images/detail.gif" width="25px;" height="25px;" style="cursor:pointer"/><u>รายละเอียดความสัมพันธ์ทางบัญชี</u></a> &nbsp
	<a onclick="popU('frm_thcap_print_pdf.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800');" style="cursor:pointer;"><img src="images/pdf.png" width="25px;" height="25px;" style="cursor:pointer"/><u>พิมพ์</u></a> &nbsp
	<a href="frm_thcap_add.php"><img src="images/add.png" border="0" width="16" height="16" align="absmiddle"> เพิ่มรายการใหม่</a>
</div>
<fieldset><legend><b>แสดงรายการ</b></legend>
<!--<div style="width: 1000px; height: 500px; overflow: auto;">-->
<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center"  valign="top" width="5%">รหัสประเภทค่าใช้จ่าย</td>
        <td align="center"  valign="top" width="3%">รหัสประเภทบริษัท</td>
        <td align="center"  valign="top" width="3%">รหัสประเภทสัญญา</td>
        <td align="center"  valign="top" width="10%">ชื่อประเภทค่าใช้จ่าย</td>
        <td align="center"  valign="top" width="8%">คำอธิบายประเภทค่าใช้จ่าย</td>
        <td align="center"  valign="top" width="2%">Edit</td>
    </tr>
   
<?php
$qry_name=pg_query("SELECT * FROM account.\"thcap_typePay\" ORDER BY \"tpID\",\"tpRanking\" ");
$rows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $tpID = $res_name["tpID"];
    $tpCompanyID = $res_name["tpCompanyID"];
    $tpConType = $res_name["tpConType"];  
    $tpDesc = $res_name["tpDesc"];
    $tpFullDesc = $res_name["tpFullDesc"];
	$ableB = $res_name["ableB"];
    $ableDiscount = $res_name["ableDiscount"];
    $ableWaive = $res_name["ableWaive"];  
    $ableVAT = $res_name["ableVAT"];
    $ableWHT = $res_name["ableWHT"];
	
	//By Por
	$ableSkip = $res_name["ableSkip"];
	$ablePartial = $res_name["ablePartial"];
	$curWHTRate = $res_name["curWHTRate"];
	$isServices = $res_name["isServices"];
	$tpSort = $res_name["tpSort"]; //ลำดับการแสดงผล
	$tpRanking = $res_name["tpRanking"]; //อันดับการจ่าย
	
	$curSBTRate= $res_name["curSBTRate"];
	$isLockedVat= $res_name["isLockedVat"];
	$ableInvoice= $res_name["ableInvoice"];
	$curLTRate= $res_name["curLTRate"];
	
	if($isServices=="0"){
		$txtservice="ไม่เข้าข่ายทั้งสอง";
	}else if($isServices=="1"){
		$txtservice="บริการ";
	}else if($isServices=="2"){
		$txtservice="สินค้า";
	}

	//End By Por
	
	//By Boz (เลียนแบบข้างบน)
	$tpType = trim($res_name["tpType"]); // เงื่อนไขในการเก็บ
	$whoSeen = $res_name["whoSeen"]; //ALL-เปิดให้เห็นทุกส่วนงาน
	$tpRefType = trim($res_name["tpRefType"]); //รูปแบบ Ref
	$isSubsti = $res_name["isSubsti"]; //substitutional - รับแทน เช่น รับแทนค่าประกัน
	$isLeasing = $res_name["isLeasing"];
	
	//ตรวจสอบเงิ่อนไข
	if($tpType == 'NONE'){
		$Typedesc = 'ไม่มีเงื่อนไขในการเก็บ';
	}else if($tpType == 'LOCKED'){
		$Typedesc = 'ไม่มีเงื่อนไขในการเก็บ แต่ว่าไม่ให้เพิ่มหนี้เข้าไปได้โดยทั่วไป';
	}else if($tpType == 'FIXED'){
		$Typedesc = 'เก็บค่าตายตัวทุกสัญญาเหมือนกันหมด';
	}else if($tpType == 'VAR'){
		$Typedesc = 'เก็บค่าไม่เหมือนกันแปรผันตามสัญญา';
	}else if($tpType == 'PER'){
		$Typedesc = 'เก็บค่าเป็น percent จากยอดที่สนใจ';
	}
	
	if($whoSeen == 'ALL'){
		$whoSeendesc = 'เปิดให้เห็นทุกส่วนงาน';
	}
	
	if($tpRefType == 'D'){
		$tpRefTypedesc = 'วันที่';
	}else if($tpRefType == 'W'){
		$tpRefTypedesc = 'สัปดาห์';
	}else if($tpRefType == 'M'){
		$tpRefTypedesc = 'รายเดือน';
	}else if($tpRefType == 'Y'){
		$tpRefTypedesc = 'รายปี';
	}else if($tpRefType == 'L'){
		$tpRefTypedesc = 'ช่วงใดๆ';
	}else if($tpRefType == 'RUNNING'){
		$tpRefTypedesc = 'ครั้งที่';
	}else if($tpRefType == 'ID'){
		$tpRefTypedesc = 'ตามหนังสือหรือรหัสใบ';
	}else if($tpRefType == 'DUE'){
		$tpRefTypedesc = 'Due หรือ งวดที่กำหนด ';
	}
	
	if($isSubsti == '0'){
		$isSubstidesc = 'ทั่วไป';
	}else if($isSubsti == '1'){
		$isSubstidesc = 'รับแทน';
	}
	//End By Boz 
	

    $in+=1;
    if($in%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
        <td align="center"><?php echo "$tpID"; ?></td>
        <td align="center"><?php echo "$tpCompanyID"; ?></td>
        <td align="center"><?php echo "$tpConType"; ?></td>
        <td align="left"><?php echo "$tpDesc"; ?></td>
        <td align="left"><?php echo "$tpFullDesc"; ?></td>
        <td align="center"><a href="frm_thcap_edit2.php?id=<?php echo "$tpID";?>"><img src="images/edit.png" border="0" width="16" height="16" align="absmiddle"></a></td>
    </tr>
 <?php
        }
?>
</table>
<!--</div>-->
<!--</div>-->

<div align="center"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>

		</td>
	</tr>
</table>

</body>
</html>