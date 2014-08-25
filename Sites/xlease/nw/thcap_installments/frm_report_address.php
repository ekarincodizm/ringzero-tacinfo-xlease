<?php
session_start();
include("../../config/config.php");

$idno = pg_escape_string($_GET["idno"]);

//ตรวจสอบว่ามีเลขที่สัญญานี้ในระบบจริงหรือไม่
$qrychk=pg_query("select \"contractID\" from \"thcap_contract\" where \"contractID\" = '$idno'");
if(pg_num_rows($qrychk)==0){
	echo "<div align=center><h2>กรุณาระบุเลขที่สัญญาให้ถูกต้อง</h2></div>";
	exit;
}

//ค้นหาชื่อผู้กู้หลัก
$qry_namemain=pg_query("select \"thcap_fullname\", \"type\" from  \"vthcap_ContactCus_detail\"
where \"contractID\"='$idno' and \"CusState\" ='0'");
if($resnamemain=pg_fetch_array($qry_namemain)){
	$name3=trim($resnamemain["thcap_fullname"]);
	$typecus=trim($resnamemain["type"]);
}

//ค้นหาชื่อผู้กู้ร่วม
$qry_name=pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\"
where \"contractID\"='$idno' and \"CusState\" = '1'");
$numco=pg_num_rows($qry_name);
$i=1;
$nameco="";
while($resco=pg_fetch_array($qry_name)){
	$name2=trim($resco["thcap_fullname"]);
	if($numco==1){ //กรณีมีชื่อเดียวไม่ต้องใส่ comma
		$nameco=$name2;
	}else{
		if($i==$numco){
			$nameco=$nameco.$name2;
		}else{
			$nameco=$nameco.$name2.", ";
		}
	}
$i++;
}

$qry_top=pg_query("select \"CusID\", \"P_TransferIDNO\" from \"Fp\" WHERE \"IDNO\"='$idno'");
$res_top=pg_fetch_array($qry_top);
$CusID=$res_top["CusID"];
$P_TransferIDNO=$res_top["P_TransferIDNO"];
$arr_idno[$idno]=$CusID;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<link type="text/css" rel="stylesheet" href="act.css"></link>

<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

<style type="text/css">
.tabs{
	backgruond-color:#FFCC33;
    font-family:tahoma;
    font-size:12px
}
</style>


<style type="text/css">
body {
    font-family:tahoma;
    color : #333333;
    font-size:12px;
}
.title_top{
    font-family: tahoma;
    font-size:19px;
    font-weight: bold;
    margin: 0;
    padding: 0 0 3px 0;
    text-align: right;
}
.odd{
    background-color:#EDF8FE;
    font-size:11px
}
.even{
    background-color:#D5EFFD;
    font-size:11px
}
.red{
    background-color:#FFD9EC;
    font-size:11px
}
</style>

</head>

<body>

<div class="title_top">ประวัติที่อยู่ตามสัญญา</div>
	<div class="wrapper">
		<div align="right" style="font-weight:bold; padding-top:3px; padding-bottom:3px;">ผู้กู้หลัก : <?php echo $name3; if($nameco != ""){?> | ผู้กู้ร่วม : <?php echo $nameco;}?></div>
			<div style="background-color:#FFD700;"><h2>เลขที่สัญญา: <?php echo $idno;?></h2></div>
			<fieldset><legend><b>ประวัติที่อยู่ตามสัญญา</b></legend>
				<table width="85%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
					<tr bgcolor="#FFFFFF">
						<td colspan="10" align="left" height="25"><u><b>หมายเหตุ</b></u>
							<div><font color="red"> <span style="background-color:#C0FF3E;">&nbsp;&nbsp;&nbsp;</span> รายการสีเขียว คือ ข้อมูลที่ใช้งานปัจจุบัน</font></div>
						</td>
					</tr>
				<table>
				<br>
				<table width="90%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F2F5A9">
					<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
						<td>แก้ไขครั้งที่</td>
						<td>ผู้ทำรายการเพิ่ม/แก้ไข</td>
						<td>วันเวลาที่ทำรายการเพิ่ม/แก้ไข</td>
						<td>ผู้อนุมัติเพิ่ม/แก้ไข</td>
						<td>วันเวลาที่อนุมัติเพิ่ม/แก้ไข</td>
						<td>รายละเอียดที่อยู่</td>
					</tr>
					<?php
				//หาข้อมูลทประวัติี่ที่อยู่ทั้งหมด
					$qry_add = pg_query("select \"tempID\", \"addUser\", \"addStamp\", \"appUser\", \"appStamp\", \"updated\"
										from \"thcap_addrContractID_temp\" where \"contractID\"='$idno' and \"statusApp\" in('1','4') and \"addsType\"='3' order by \"appStamp\" DESC ");
					$numrow = pg_num_rows($qry_add);
					$count = $numrow-1;
					$nub=0;
						if($numrow>0){
							while($resaddr = pg_fetch_array($qry_add)){
								$nub++;
								
								$tempID = $resaddr["tempID"];
								$addUser = $resaddr["addUser"];
								$addStamp = $resaddr["addStamp"];
								$appUser = $resaddr["appUser"];
								$appStamp = $resaddr["appStamp"];
								$sentaddress = $resaddr["sentaddress"];
								$updated = $resaddr["updated"];
								
							$qry_addname = pg_query("select fullname from \"Vfuser\" where id_user = '$addUser'");	
							$addName = pg_fetch_result($qry_addname,0);
							
							$qry_appname = pg_query("select fullname from \"Vfuser\" where id_user = '$appUser'");	
							$appName = pg_fetch_result($qry_appname,0);
							
								if($updated == "1" && $setMain == "")
								{
									echo "<tr align=center bgcolor=\"C0FF3E\">"; // รายการที่ใช้อยู่ปัจจุบัน
									$setMain = "seted";
								}
								else
								{		
									if($nub%2==0){
										echo "<tr class=\"odd\" align=center>";
									} else {
										echo "<tr class=\"even\" align=center>";
									}
								}
								
								echo "<td>$count</td>";
								echo "<td>$addName</td>";
								echo "<td>$addStamp</td>";
								echo "<td>$appName</td>";
								echo "<td>$appStamp</td>";
								echo "<td><a onclick=\"javascript:popU('frm_report_address_detail.php?tempID=$tempID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=640,height=480')\"style=\"cursor:pointer\"><img src=\"images/detail.gif\"/></a></td>";
							echo "</tr>";
							
							$count--;
							}//end while
						} else {
							echo "<tr>";
								echo "<td colspan=\"6\" align=center>ไม่พบข้อมูล</td>";
							echo "</tr>";
						}
			?>		
				</table>
			</fieldset>
		</div>
	</div>
</div>


</body>
</html>