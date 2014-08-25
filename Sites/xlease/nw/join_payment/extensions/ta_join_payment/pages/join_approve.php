<?php
require_once("../../sys_setup.php");
include("../../../../../config/config.php");
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
<script src="../<?php echo $lo_ext_current_temp ?>scripts/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="../<?php echo $lo_ext_current_temp ?>scripts/js/jquery-1.7.1.min.js" type="text/javascript"></script>

<script src="../<?php echo $lo_ext_current_temp ?>scripts/js/jquery-ui-1.8.19.custom.min.js" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="../<?php echo $lo_ext_current_temp ?>scripts/css/ui-lightness/jquery-ui-1.8.1.custom.css" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>

</head>
<body>
    <style type="text/css">

table.t2 tr:hover td {
	background-color:pink;
}

</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="header"><h1><?php echo $_SESSION['session_company_name']; ?></h1></div>
		<div class="wrapper">
			<table class="t2" width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="8" align="left" style="font-weight:bold;">อนุมัติการเพิ่ม/แก้ไขข้อมูลเข้าร่วม</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
            <td align="center">ลำดับ</td>
				<td align="center">เลขที่สัญญา</td>
                <td align="center">ชื่อลูกค้า</td>
                <td align="center">เลขทะเบียนรถ</td>
				<td align="center">ผู้ขออนุมัติ</td>
				<td align="center">วันเวลาเพิ่ม/แก้ไขรายการ</td>
                <td align="center">ประเภท</td>
               
				<td>รายละเอียด</td>
			</tr>
			<?php
			$qry_fr=pg_query("SELECT carid,car_license,id,cpro_name,idno,approve_status,create_by,update_by,create_datetime,update_datetime FROM \"VJoinMain\" WHERE staff_check='0' and deleted ='0' 
			and approve_status!=4 order by update_datetime desc ,create_datetime desc ");
			$nub=pg_num_rows($qry_fr);
			while($sql_row4=pg_fetch_array($qry_fr)){
				$cpro_name = $sql_row4['cpro_name'];
					$id = $sql_row4['id'];
					$car_license = $sql_row4['car_license'];
					$carid= $sql_row4['carid'];
					$create_datetime =$sql_row4['create_datetime']; 
					$update_datetime =$sql_row4['update_datetime']; 
					$approve_status = $sql_row4['approve_status'];
					$update_by = $sql_row4['update_by'];
					$create_by = $sql_row4['create_by'];//เอาไปหา id_card ด้วย
					$idno = trim($sql_row4['idno']);
					
					// ถ้า carid ไม่มีค่า
					if($carid == "")
					{
						// ให้ไปเอาที่ asset_id."IDNO" แทน
						$qry_CarID = pg_query("select \"asset_id\" from \"Fp\" where \"IDNO\" = '$idno' ");
						$carid = pg_result($qry_CarID,0);
					}
		
		if($approve_status==1){//รออนุมัติการเพิ่มข้อมูลใหม่
		$app_type = "เพิ่มข้อมูลใหม่";
		$dt = $create_datetime;
			$by = $create_by;
		}else if($approve_status==2){
			$app_type = "แก้ไขข้อมูล";
			$dt = $update_datetime;
			$by = $update_by;
			
		}else{	
			$app_type = "ตรวจสอบข้อมูลเก่า";
			$dt = $update_datetime;
			$by = $update_by;
			if($dt==""){
				
				$dt = $create_datetime;
			$by = $create_by;
				
			}
		}
		
					$res_profile=pg_query("select fullname,id_user from \"Vfuser\" where id_user='$by'");
   $res_userprofile=pg_fetch_array($res_profile);
   $by=  $by."-".$res_userprofile["fullname"];

				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
			?>
            <td align="center"><?php echo $i; ?></td>
				<td><?php echo $idno; ?></td>
				<td align="left"><?php echo $cpro_name; ?></td>
                <td align="left"><?php echo $car_license; ?></td>
                <td align="left"><?php echo $by; ?></td>
                <td align="center"><?php echo $dt; ?></td>
      
                <td align="center"><?php echo $app_type; ?></td>
				<td align="center">
					<span onclick="javascript:popU('frm_main.php?action=view&app=1&car_id_r=<?php echo $carid; ?>&id=<?php echo $id; ?>&idno=<?php echo $idno; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor: pointer;"><u>แสดงรายการ</u></span>
				</td>
				
			</tr>
			<?php
			}
			if($nub == 0){
				echo "<tr><td colspan=8 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>

</body>
</html>