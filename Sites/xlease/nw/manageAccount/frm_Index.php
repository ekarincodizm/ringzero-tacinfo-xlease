<?php
include("../../config/config.php");
$iduser = $_SESSION["av_iduser"];
$Strsort=pg_escape_string($_GET['sort']);
if($Strsort==""){$Strsort="accBookID";}
 
$strorder=pg_escape_string($_GET['order']);
if($strorder==""){$strorder="DESC";}


if($strorder=="DESC"){
	$NewStrorder="ASC";
} else {
	$NewStrorder="DESC";
}

//ตรวจสอบ level ของ ผู้ใช้งาน  เนื่องจาก <=1 จะเพิ่ม/แก้ไขได้
$query_leveluser = pg_query("select \"emplevel\" from \"Vfuser\" where \"id_user\" = '$iduser' ");
$leveluser = pg_fetch_array($query_leveluser);
$emplevel=$leveluser["emplevel"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) จัดการสมุดบัญชี</title>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

</head>

<body>
<center><h2>(THCAP) จัดการสมุดบัญชี</h2></center>
<table align="center" width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#113355">
	<?php if($emplevel<=1) { ?>
	<tr bgcolor=#FFFFFF><td colspan="14" align="right"><input type="button" name="add" value=" + เพิ่ม " onclick="javascript:popU('frm_add.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=400')"></td></tr>
	<?php } ?>
	<tr align="center" bgcolor="#79BCFF">
		<th align="center">&nbsp;&nbsp;serial&nbsp;&nbsp;</th>
		<th align="center"><a href="frm_Index.php?sort=accBookID&order=<?php echo $NewStrorder;?>"/><font color="black"/><u>เลขที่สมุดบัญชี</u></font></th>
		<th align="center">&nbsp;&nbsp;รหัสบริษัท&nbsp;&nbsp;</th>		
		<th align="center">&nbsp;&nbsp;ชื่อสมุดบัญชี&nbsp;&nbsp;</th>
		<th align="center">&nbsp;&nbsp;accBookNameFS&nbsp;&nbsp;</th>
		<th align="center">&nbsp;&nbsp;ประเภทสมุดบัญชี&nbsp;&nbsp;</th>
		<th align="center">&nbsp;&nbsp;ประเภทกลุ่ม&nbsp;&nbsp;</th>
		<th align="center">&nbsp;&nbsp;ประเภทชนิด&nbsp;&nbsp;</th>
		<th align="center">&nbsp;&nbsp;ประเภทย่อย&nbsp;&nbsp;</th>
		<th align="center">&nbsp;&nbsp;รูปแบบการรับรู้รายได้&nbsp;&nbsp;</th>
		<th align="center">&nbsp;&nbsp;สถานะสมุดบัญชี&nbsp;&nbsp;</th>
		<th align="center">&nbsp;&nbsp;accBookableFS&nbsp;&nbsp;</th>
		<th align="center">แยกประเภท</th>
		<?php if($emplevel<=1) { ?>
		<th align="center">แก้ไข</th>
		<?php } ?>
	</tr>
	<?php
	$query = pg_query("select * from account.\"all_accBook\" order by \"$Strsort\" $NewStrorder");
	$numrows = pg_num_rows($query);
	$i=0;
	while($result = pg_fetch_array($query))
	{
		$i++;
		$accBookserial = $result["accBookserial"];
		$accBookID = $result["accBookID"]; // เลขที่สมุดบัญชี
		$accBookComp = $result["accBookComp"]; // รหัสบริษัท
		$accBookName = $result["accBookName"]; // ชื่อสมุดบัญชี
		$accBookNameFS = $result["accBookNameFS"];
		$accBookType = $result["accBookType"]; // ประเภทสมุดบัญชี
		$accBookStatus = $result["accBookStatus"]; // สถานะสมุดบัญชี
		$accBookableFS = $result["accBookableFS"];
		
		$accBookGroup = $result["accBookGroup"]; //ประเภทกลุ่ม
		$accBookCustom = $result["accBookCustom"];//ประเภทชนิด
		$accBookUnit = $result["accBookUnit"]; //ประเภทย่อย
		$accBookRealiseType = $result["accBookRealiseType"];//รูปแบบการรับรู้รายได้   1-CASH BASIS / 2-CASH ACCRUAL //null=ไม่ระบุ
		$accBookTypeFS = $result["accBookTypeFS"]; //แยกประเภท
		
		//รูปแบบการรับรู้รายได้ 
		if($accBookRealiseType==""){$accBookRealiseType="ไม่ระบุ";}
		else if($accBookRealiseType=="1"){$accBookRealiseType="CASH BASIS";}
		else if($accBookRealiseType=="2"){$accBookRealiseType="CASH ACCRUAL";}
		// หา ประเภทสมุดบัญชี
		if($accBookType == "1"){$accBookType = "ทรัพย์สิน";}
		if($accBookType == "2"){$accBookType = "หนี้สิน";}
		if($accBookType == "3"){$accBookType = "ทุน";}
		if($accBookType == "4"){$accBookType = "รายได้";}
		if($accBookType == "5"){$accBookType = "รายจ่าย";}
		
		// หา สถานะสมุดบัญชี
		if($accBookStatus == "1"){$accBookStatus = "ใช้งาน";}
		if($accBookStatus == "0"){$accBookStatus = "ไม่ใช้งาน";}
		
		// หา accBookableFS
		if($accBookableFS == "1"){$accBookableFS = "ใช่";}
		if($accBookableFS == "0"){$accBookableFS = "ไม่";}
		// แยกประเภท
		//1 - งบดุล
		//2 - งบกำไรขาดทุนเบ็ดเสร็จ';
		if($accBookTypeFS == "1"){$accBookTypeFS = "งบดุล";}
		if($accBookTypeFS == "2"){$accBookTypeFS = "งบกำไรขาดทุนเบ็ดเสร็จ";}
		
		if($i%2==0){
			echo "<tr class=\"odd\">";
		}else{
			echo "<tr class=\"even\">";
		}
		
		echo "<td align=\"center\">$accBookserial</td>";
		echo "<td align=\"center\">$accBookID</td>";
		echo "<td align=\"center\">$accBookComp</td>";
		echo "<td align=\"left\">$accBookName</td>";
		echo "<td align=\"left\">$accBookNameFS</td>";
		echo "<td align=\"center\">$accBookType</td>";		
		echo "<td align=\"center\">$accBookGroup</td>";
		echo "<td align=\"center\">$accBookCustom</td>";
		echo "<td align=\"center\">$accBookUnit</td>";
		echo "<td align=\"center\">$accBookRealiseType</td>";		
		echo "<td align=\"center\">$accBookStatus</td>";
		echo "<td align=\"center\">$accBookableFS</td>";
		echo "<td align=\"center\">$accBookTypeFS</td>";
		if($emplevel<=1) {		
			echo "<td align=\"center\"><img src=\"images/edit.png\" width=\"19\" height=\"19\" onclick=\"javascript:popU('frm_add.php?accBookserial=$accBookserial','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=400');\" style=\"cursor:pointer;\"></td>";	
		}		
		echo "</tr>";
	}
	if($numrows==0){
		echo "<tr bgcolor=#FFFFFF height=30><td colspan=14 align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=14><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
</table>
</body>
</html>