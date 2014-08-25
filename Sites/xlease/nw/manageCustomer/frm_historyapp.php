<?php
session_start();
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
<table width="90%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#FFFFFF">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="center" style="font-weight:bold;"><h1>ลูกค้าล่าสุดที่ได้ทำการอนุมัติ </h1></td>
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
			where \"add_user\"<>'000' and \"app_user\" is not null and statusapp<>'2' order by \"app_date\" DESC");
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
			
</body>
</html>