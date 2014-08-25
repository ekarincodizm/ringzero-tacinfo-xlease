<?php
session_start();
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
// Query ข้อมูลของพนักงาน
	$Uid = $_SESSION["av_iduser"];
	$qry_user=pg_query("select emplevel from \"Vfuser\" WHERE id_user='$Uid' ");
	$res_user=pg_fetch_array($qry_user);
	$emplevel=$res_user["emplevel"];
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

<table width="850" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="header"><h1><?php echo $_SESSION['session_company_name']; ?></h1></div>
		<div><font size="3" color="red"><b>* ผู้อนุมัติจะต้องตรวจสอบข้อมูลที่อนุมัติกับเอกสารต้นฉบับ หรือสำเนาที่เชื่อได้ว่ามาจากเอกสารต้นฉบับจริงเท่านั้น จึงจะทำการอนุมัติ การอนุมัติใดๆจะมีการเก็บข้อมูลทั้งผู้ขออนุมัติและผู้อนุมัติด้วย</b></font></div>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" style="font-weight:bold;">อนุมัติข้อมูลลูกค้า</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<td>รูปแบบ</td>
				<td align="left">ชื่อลูกค้า</td>
				<td>ผู้ทำรายการ</td>
				<td>วันเวลาทำรายการ</td>
				<td>ดูข้อมูลเดิม</td>
				<td>ดูข้อมูลใหม่</td>
			</tr>
			<?php
			$qry_fr=pg_query("select a.\"add_user\" as iduser,a.\"A_FIRNAME\",a.\"A_NAME\",a.\"A_SIRNAME\",a.\"CustempID\",a.\"edittime\",a.\"add_date\",a.\"CusID\",b.\"fullname\" as add_user from \"Customer_Temp\" a 
			left join \"Vfuser\" b on a.\"add_user\"=b.\"id_user\"
			WHERE \"statusapp\" = '2' order by a.\"CustempID\"");
			$nub=pg_num_rows($qry_fr);
			while($res_fr=pg_fetch_array($qry_fr)){
				$iduser=$res_fr["iduser"];
				$CustempID=$res_fr["CustempID"];
				$CusID = $res_fr["CusID"]; 
				if(substr($CusID,0,2)=='CT'){
					$CusID2="";
				}else{
					$CusID2=1;
				}
				$cusname = $res_fr["A_FIRNAME"].$res_fr["A_NAME"]." ".$res_fr["A_SIRNAME"];
				$edittime = $res_fr["edittime"];
				$add_user = $res_fr["add_user"];
				$add_date = $res_fr["add_date"]; 
				
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
				<td align="left"><?php echo $cusname; ?></td>
				<td align="left"><?php echo $add_user; ?></td>
				<td><?php echo $add_date; ?></td>
				<td>
				<?php
					if($CusID2==""){
						if($edittime>0){
							//นำข้อมูลเก่ามาแสดง โดยนำมาจากตาราง Temp ที่มีค่า edittime ก่อนหน้านี้
							$edit=$edittime-1;
							$qrytemp2=pg_query("select \"CustempID\" from \"Customer_Temp\" where \"CusID\" ='$CusID' and \"edittime\"='$edit'");
							list($CustempID2)=pg_fetch_array($qrytemp2);
							echo "<span onclick=\"javascript:popU('showdetail.php?CusID=$CusID&CustempID=$CustempID2&stsCus=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')\" style=\"cursor: pointer;\"><img src=\"images/old.gif\" height=\"11\" width=\"27\" border=\"0\"></span>";
						}else{
							echo "-";
						}
					}else{
				?>
				<span onclick="javascript:popU('showdetail.php?CusID=<?php echo $CusID; ?>&CustempID=<?php echo $CustempID; ?>&stsCus=0&iduser=<?php echo $iduser; ?>&emplevel=<?php $emplevel; ?>&edittime=<?php echo $edittime; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=780')" style="cursor: pointer;"><img src="images/old.gif" height="11" width="27" border="0"></span></td>
				<?php } ?>
				<td><span onclick="javascript:popU('showdetail.php?CustempID=<?php echo $CustempID; ?>&stsCus=1&iduser=<?php echo $iduser; ?>&emplevel=<?php $emplevel; ?>&edittime=<?php echo $edittime; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=780')" style="cursor: pointer;"><img src="images/new.gif" height="11" width="28" border="0"></span></td>
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=10 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
			<br><br><br>
		</div>
		<div>
		<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#FFFFFF">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" style="font-weight:bold;"><b>ลูกค้าล่าสุดที่ได้ทำการอนุมัติ (<font color="blue"><a onclick="javascript:popU('frm_historyapp.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1300,height=650')" style="cursor:pointer;"><u>ทั้งหมด</u></a></font>)</b></td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#D6D6D6" align="center">
				<td>รูปแบบ</td>
				<td align="left">ชื่อลูกค้า</td>
				<td>ผู้ทำรายการ</td>
				<td>วันเวลาทำรายการ</td>
				<td>ผู้อนุมัติ</td>
				<td>วันเวลาอนุมัติ</td>
				<td>ข้อมูลที่ขออนุมัติ</td>
				<td>สถานะอนุมัติ</td>
			</tr>
			<?php
			$qry_fr2=pg_query("SELECT \"CustempID\", \"CusID\", add_date, app_user, app_date, statusapp,
			a.\"A_FIRNAME\",a.\"A_NAME\",a.\"A_SIRNAME\",b.\"fullname\" as add_user,c.\"fullname\" as app_user
			FROM \"Customer_Temp\" a
			left join \"Vfuser\" b on a.\"add_user\"=b.\"id_user\"
			left join \"Vfuser\" c on a.\"app_user\"=c.\"id_user\"
			where \"add_user\"<>'000' and \"app_user\" is not null and statusapp<>'2' order by \"app_date\" DESC limit 10;");
			$nub2=pg_num_rows($qry_fr2);
			while($res_fr2=pg_fetch_array($qry_fr2)){
				$iduser2=$res_fr2["iduser"];
				$CustempID2=$res_fr2["CustempID"];
				$CusIDD = $res_fr2["CusID"]; 
				if(substr($CusIDD,0,2)=='CT'){
					$CusIDD2="";
				}else{
					$CusIDD2=1;
				}
				$cusname2 = $res_fr2["A_FIRNAME"].$res_fr2["A_NAME"]." ".$res_fr2["A_SIRNAME"];
				$edittime2 = $res_fr2["edittime"];
				$add_user2 = $res_fr2["add_user"];
				$add_date2 = $res_fr2["add_date"]; 
				$app_user2 = $res_fr2["app_user"];
				$app_date2 = $res_fr2["app_date"]; 
				$statusapp2 = $res_fr2["statusapp"];
				
				
				if($edittime2=="0"){ //กรณีเป็นการเพิ่มข้อมูล
					$txttype2="เพิ่มข้อมูล";
				}else{
					$txttype2="แก้ไขข้อมูล";
				}
				
				if($statusapp2=="1"){
					$txtapp2="อนุมัติ";
				}else{
					$txtapp2="<font color=red>ไม่อนุมัติ</font>";
				}
				
				$i+=1;
				if($i%2==0){
					echo "<tr bgcolor=#EEEEEE align=center>";
				}else{
					echo "<tr bgcolor=#F5F5F5 align=center>";
				}
			?>
				<td><?php echo $txttype2; ?></td>
				<td align="left"><?php echo $cusname2; ?></td>
				<td align="left"><?php echo $add_user2; ?></td>
				<td><?php echo $add_date2; ?></td>
				<td align="left"><?php echo $app_user2; ?></td>
				<td><?php echo $app_date2; ?></td>
				<td><span onclick="javascript:popU('showalldetail.php?CustempID=<?php echo $CustempID2; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=780')" style="cursor: pointer;"><img src="images/detail.gif" height="19" width="19" border="0"></span></td>
				<td><?php echo $txtapp2;?></td>
			</tr>
			<?php
			} //end while
			if($nub2 == 0){
				echo "<tr><td colspan=10 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			<tr><td colspan="8"><font color="red" size="2"><b>* <U>ผู้อนุมัติและวันเวลาอนุมัติเท่ากับค่าว่าง</U> หมายถึง ความผิดพลาดของระบบในการบันทึกข้อมูล แต่ไม่มีผลกระทบกับข้อมูล</b></font></td></tr>
			</table>
		</div>
	</td>
</tr>
</table>

</body>
</html>