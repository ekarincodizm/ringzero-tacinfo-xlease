<?php
include("../../config/config.php");
session_start();
$id_user = $_SESSION["av_iduser"];


//ประวัติการอนุมัติ 30 รายการล่าสุด
$strSort1 = $_GET["sort1"];
if($strSort1 == "")
{$strSort1 = "date_submit";}
$strOrder1 = $_GET["order1"];
if($strOrder1 == ""){$strOrder1 = "DESC";}
$qry_waitapp1 = pg_query("SELECT * FROM \"Fc_temp\" where \"appstatus\" != '0' and \"CarIDtemp\" IN (select \"CarIDtemp\" from \"Fc_temp\" where  \"appstatus\" != '0' order by date_app DESC) 
order by \"$strSort1\" $strOrder1 ");
$row_waitapp1 = pg_num_rows($qry_waitapp1);
$strNewOrder1 = $strOrder1 == 'DESC' ? 'ASC' : 'DESC';

$i = 0;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>ประวัติการอนุมัติเพิ่มรถยนต์</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="../book_car_check/act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="../../post/fancybox/lib/jquery-1.7.2.min.js"></script> 
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body bgcolor="">
<table width="900" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
		<tr>
        <td align="center">
				<table align="center" width="950">
					<tr>
						<td align="left">
							<div style="padding-top:35px;"></div>
							<font color="red" size="3px;">ประวัติการอนุมัติเพิ่มรถยนต์</font>
						</td>
					</tr>
				</table>
				<table align="center" frame="box" width="950">
						
						<tr bgcolor="#CDC9C9">
							<th width="150"><a href='frm_approve.php?sort1=C_CARNAME&order1=<?php echo $strNewOrder ?>'><u>ยี่ห้อ</u></th>
							<th width="150"><a href='frm_approve.php?sort1=fc_model&order1=<?php echo $strNewOrder ?>'><u>รุ่น</u></th>
							<th width="150"><a href='frm_history.php?sort1=C_REGIS&order1=<?php echo $strNewOrder1 ?>'><u>เลขทะเบียน</u></th>
							<th width="150"><a href='frm_history.php?sort1=C_CARNUM&order1=<?php echo $strNewOrder1 ?>'><u>เลขตัวถังรถ</u></th>
							<th width="110"><a href='frm_history.php?sort1=C_StartDate&order1=<?php echo $strNewOrder1 ?>'><u>วันจดทะเบียน</u></th>			
							<th width="150"><a href='frm_history.php?sort1=id_user&order1=<?php echo $strNewOrder1 ?>'><u>ผู้ขออนุมัติ</u></th>
							<th width="150"><a href='frm_history.php?sort1=date_submit&order1=<?php echo $strNewOrder1 ?>'><u>วันที่ขออนุมัติ</u></th>
							<th width="150"><a href='frm_history.php?sort1=app_user&order1=<?php echo $strNewOrder1 ?>'><u>ผู้อนุมัติ</u></th>
							<th width="150"><a href='frm_history.php?sort1=date_submit&order1=<?php echo $strNewOrder1 ?>'><u>วันที่อนุมัติ</u></th>
							<th width="100">เพิ่มเติม</th>
							<th width="50"><a href='frm_history.php?sort1=appstatus&order1=<?php echo $strNewOrder1 ?>'><u>สถานะ</u></th>
							
						</tr>
		<?php 			if($row_waitapp1 != 0){	
							while($re_waitapp = pg_fetch_array($qry_waitapp1)){
								$iduser = $re_waitapp['id_user'];
								$qry_user = pg_query("select fullname from \"Vfuser\" where \"id_user\" = '$iduser'");
								list($fullname) = pg_fetch_array($qry_user);
								
								$appuser = $re_waitapp['app_user'];
								$qry_user = pg_query("select fullname from \"Vfuser\" where \"id_user\" = '$appuser'");
								list($fullnameapp) = pg_fetch_array($qry_user);
								
								if($re_waitapp['appstatus'] == '0'){
									$status = 'รออนุมัติ';
								}else if($re_waitapp['appstatus'] == '1'){
									$status = 'อนุมัติ';
								}else{
									$status = "<a style=\"cursor:pointer;\" onclick=\"javascript:popU('note_popup.php?cartempid=".$re_waitapp['CarIDtemp']."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=300')\" ><u>ไม่อนุมัติ</u></a>";
								}
								$fp_fc_model = $re_waitapp["fc_model"]; //รุ่น
								$fp_fc_brand = $re_waitapp["fc_brand"]; //ยี่ห้อ
								if($fp_fc_brand != ""){
									//หายี่ห้อ
									$qry_sel_brand = pg_query("select \"brand_name\" FROM \"thcap_asset_biz_brand\" WHERE \"brandID\" = '$fp_fc_brand' ");
									list($fp_band) = pg_fetch_array($qry_sel_brand);
									
									//หารุ่น
									$qry_sel_model = pg_query("select \"model_name\" FROM \"thcap_asset_biz_model\" WHERE \"modelID\" = '$fp_fc_model' ");
									list($fp_model) = pg_fetch_array($qry_sel_model);
								}else{
										$fp_band = $re_waitapp['C_CARNAME'];
										$fp_model = "";
								}
							
							
							$i++;
							if($i%2==0){
								echo "<tr bgcolor=#EEE9E9 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\" align=center>";
							}else{
								echo "<tr bgcolor=#FFFAFA onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\" align=center>";
							} ?>
								
									<td align="left"><?php echo $fp_band; ?></td>
									<td align="left"><?php echo $fp_model; ?></td>
									<td><?php echo $re_waitapp['C_REGIS']."<br>".$re_waitapp['C_REGIS_BY'] ?></td>	
										<td><?php echo $re_waitapp['C_CARNUM'] ?></td>
										<td><?php echo $re_waitapp['C_StartDate'] ?></td>
										<td><?php echo $fullname ?></td>
										<td><?php echo $re_waitapp['date_submit'] ?></td>
										<td><?php echo $fullnameapp ?></td>
										<td><?php echo $re_waitapp['date_app'] ?></td>
										<td><img src="../manageCustomer/images/detail.gif" style="cursor:pointer;" onclick="javascript:popU('frm_detail.php?cartempid=<?php echo $re_waitapp['CarIDtemp'] ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=750,height=450')"></td>		
										<td><?php echo $status ?></td>										
										</tr>
			<?php			} ?>
				<table align="center" width="950">
					<tr>
						<td align="right">
							<font color="red" size="2px;">*รายการที่ไม่อนุมัติ สามารถดูเหตุผลได้โดยการคลิกที่คำว่า " ไม่อนุมัติ "</font>
						</td>
					</tr>
				</table>
					
			<?php }else{  echo "<tr bgcolor=\"#BFEFFF\"><td align=\"center\" colspan=\"12\"><h2> ไม่พบรายการขออนุมัติ  </h2></td></tr>"; }?>	
				</table>
			</td>
		</tr>
</table>
</body>