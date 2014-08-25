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
		<div class="header"><h1><?php echo $_SESSION['session_company_name']; ?></h1></div>
		<div><font size="3" color="red"><b>* ผู้อนุมัติจะต้องตรวจสอบข้อมูลที่อนุมัติกับเอกสารต้นฉบับ หรือสำเนาที่เชื่อได้ว่ามาจากเอกสารต้นฉบับจริงเท่านั้น จึงจะทำการอนุมัติ การอนุมัติใดๆจะมีการเก็บข้อมูลทั้งผู้ขออนุมัติและผู้อนุมัติด้วย</b></font></div>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" style="font-weight:bold;">ยืนยันการเชื่อมโยงหลักทรัพย์ค้ำประกัน</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<td>รูปแบบ</td>
				<td>รหัสเชื่อมโยง</td>
				<td>ผู้ทำรายการ</td>
				<td>วันเวลาทำรายการ</td>
				<td></td>
			</tr>
			<?php
			$qry_fr=pg_query("select * from \"temp_linksecur\" a
				left join \"Vfuser\" b on a.\"user_add\"=b.\"id_user\"
				where \"statusApp\" = '2' order by auto_id ");
			$nub=pg_num_rows($qry_fr);
			while($res_fr=pg_fetch_array($qry_fr)){
				$auto_id=$res_fr["auto_id"];
				$number_running=$res_fr["number_running"];
				$edittime=$res_fr["edittime"];
				$fullname=$res_fr["fullname"];
				$stampDateAdd=$res_fr["stampDateAdd"];
				
				if($edittime=="0"){ //กรณีเป็นการเพิ่มข้อมูล
					$txttype="เพิ่มข้อมูล";
				}else{
					$txttype="แก้ไขข้อมูล";
				}
				
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
			?>
				<td><?php echo $txttype; ?></td>
				<td><?php echo $number_running; ?></td>
				<td align="left"><?php echo $fullname; ?></td>
				<td><?php echo $stampDateAdd; ?></td>
				<td>
					<span onclick="javascript:popU('showdetaillink.php?auto_id=<?php echo $auto_id; ?>&number_running=<?php echo $number_running; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=600')" style="cursor: pointer;"><u>รายละเอียด</u></span>
				</td>
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=4 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>

</body>
</html>