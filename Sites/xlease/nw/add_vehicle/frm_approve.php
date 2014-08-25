<?php
include("../../config/config.php");
session_start();
$id_user = $_SESSION["av_iduser"];

//รายรออนุมัติ
$strSort = $_GET["sort"];
if($strSort == "")
{$strSort = "date_submit";}
$strOrder = $_GET["order"];
if($strOrder == ""){$strOrder = "DESC";}
$qry_waitapp = pg_query("SELECT * FROM \"Fc_temp\" where \"appstatus\" = '0' order by \"$strSort\" $strOrder");
$row_waitapp = pg_num_rows($qry_waitapp);
$strNewOrder = $strOrder == 'DESC' ? 'ASC' : 'DESC';


//ประวัติการอนุมัติ 30 รายการล่าสุด
$strSort1 = $_GET["sort1"];
if($strSort1 == "")
{$strSort1 = "date_submit";}
$strOrder1 = $_GET["order1"];
if($strOrder1 == ""){$strOrder1 = "DESC";}
$qry_waitapp1 = pg_query("SELECT * FROM \"Fc_temp\" where \"appstatus\" != '0' and \"CarIDtemp\" IN (select \"CarIDtemp\" from \"Fc_temp\" where  \"appstatus\" != '0' order by date_app DESC) 
order by \"$strSort1\" $strOrder1 limit 30");
$row_waitapp1 = pg_num_rows($qry_waitapp1);
$strNewOrder1 = $strOrder1 == 'DESC' ? 'ASC' : 'DESC';

$i = 0;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>อนุมัติเพิ่มรถยนต์</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="../book_car_check/act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="../../post/fancybox/lib/jquery-1.7.2.min.js"></script> 
<script language=javascript>
$(document).ready(function(){
	$(".fancybox-effects-a").fancybox({
					minWidth: 300,
				   maxWidth: 700,
				   'height' : '600',
				   'autoScale' : true,
				   'transitionIn' : 'none',
				   'transitionOut' : 'none',
				   'type' : 'iframe'
	});
});

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

function selectAll(select){
    with (document.frm)
    {
        var checkval = false;
        var i=0;

        for (i=0; i< elements.length; i++)
            if (elements[i].type == 'checkbox' && !elements[i].disabled)
                if (elements[i].name.substring(0, select.length) == select)
                {
                    checkval = !(elements[i].checked);    break;
                }

        for (i=0; i < elements.length; i++)
            if (elements[i].type == 'checkbox' && !elements[i].disabled)
                if (elements[i].name.substring(0, select.length) == select)
                    elements[i].checked = checkval;
    }
}
</script>
</head>
<body bgcolor="">
<form name="frm" method="post">
<table width="90%" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td align="center">
				<table align="center" width="100%">
					<tr>
						<td align="left">
							<div style="padding-top:25px;"></div>
							<font color="red" size="5px;">รายการขออนุมัติเพิ่มรถยนต์</font>
						</td>
					</tr>
				</table>
				<table align="center" frame="box" width="100%">
						
						<tr bgcolor="#9AC0CD">
							<th width="150"><a href='frm_approve.php?sort=C_CARNAME&order=<?php echo $strNewOrder ?>'><u>ยี่ห้อ</u></th>
							<th width="150"><a href='frm_approve.php?sort=fc_model&order=<?php echo $strNewOrder ?>'><u>รุ่น</u></th>
							<th width="150"><a href='frm_approve.php?sort=C_REGIS&order=<?php echo $strNewOrder ?>'><u>เลขทะเบียน</u></th>
							<th width="150"><a href='frm_approve.php?sort=C_CARNUM&order=<?php echo $strNewOrder ?>'><u>เลขตัวถังรถ</u></th>
							<th width="110"><a href='frm_approve.php?sort=C_StartDate&order=<?php echo $strNewOrder ?>'><u>วันจดทะเบียน</u></th>
							<th width="150"><a href='frm_approve.php?sort=id_user&order=<?php echo $strNewOrder ?>'><u>ผู้ขออนุมัติ</u></th>
							<th width="150"><a href='frm_approve.php?sort=date_submit&order=<?php echo $strNewOrder ?>'><u>วันที่ขออนุมัติ</u></th>
							<th width="50">เพิ่มเติม</th>
							<th width="100"><a href="#" onclick="javascript:selectAll('chkapp');"><u>เลือกทั้งหมด</u></a></th>
							
						</tr>
		<?php 			if($row_waitapp != 0){	
							while($re_waitapp = pg_fetch_array($qry_waitapp)){
								$iduser = $re_waitapp['id_user'];
								$qry_user = pg_query("select fullname from \"Vfuser\" where \"id_user\" = '$iduser'");
								list($fullname) = pg_fetch_array($qry_user);
								
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
								echo "<tr bgcolor=#BFEFFF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#BFEFFF';\" align=center>";
							}else{
								echo "<tr bgcolor=#B2DFEE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#B2DFEE';\" align=center>";
							} ?>
								
									<td align="left"><?php echo $fp_band; ?></td>
									<td align="left"><?php echo $fp_model; ?></td>
									<td><?php echo $re_waitapp['C_REGIS']."<br>".$re_waitapp['C_REGIS_BY'] ?></td>								
										<td><?php echo $re_waitapp['C_CARNUM'] ?></td>
										<td><?php echo $re_waitapp['C_StartDate'] ?></td>
										<td><?php echo $fullname ?></td>
										<td><?php echo $re_waitapp['date_submit'] ?></td>	
										<td><img src="../manageCustomer/images/detail.gif" style="cursor:pointer;" onclick="javascript:popU('frm_detail.php?cartempid=<?php echo $re_waitapp['CarIDtemp'] ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=750,height=450')"></td>		
										<td><input type="checkbox" name="chkapp[]" id="chkapp<?php echo $i; ?>" value="<?php echo $re_waitapp['CarIDtemp'] ?>"></td>
										</tr>
			<?php			}?>	
							<tr bgcolor="#9AC0CD">
								<td colspan="12" align="right">
									<input type="hidden" value="<?php echo $i; ?>" id="chkchoise">
									 <input type="hidden" value="allow" name="appstate">
									<input type="button" value=" อนุมัติ " onclick="app(this.form);" >
									<input type="button" value=" ไม่อนุมัติ " onclick="not(this.form);" >
								</td>
							</tr>
				<?php }else{  echo "<tr bgcolor=\"#BFEFFF\"><td align=\"center\" colspan=\"12\"><h2> ไม่พบรายการขออนุมัติ </h2></td></tr>"; }?>			
				</table>
			</td>
		</tr>
		<tr>
        <td align="center">
				<table align="center" width="100%">
					<tr>
						<td align="left">
							<div style="padding-top:35px;"></div>
							<font color="red" size="3px;">ประวัติการอนุมัติเพิ่มรถยนต์ 30 รายการล่าสุด(<font color="blue"><a onclick="javascript:popU('frm_history.php','','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=1050,height=550')" style="cursor:pointer" title="ดูประวัติการอนุมัติทั้งหมด"><u><b>ทั้งหมด</b></u></a></font>)</font>
						</td>
					</tr>
				</table>
				<table align="center" frame="box" width="100%">
						
						<tr bgcolor="#CDC9C9">
							<th width="150"><a href='frm_approve.php?sort=C_CARNAME&order1=<?php echo $strNewOrder ?>'><u>ยี่ห้อ</u></th>
							<th width="150"><a href='frm_approve.php?sort=fc_model&order1=<?php echo $strNewOrder ?>'><u>รุ่น</u></th>
							<th width="150"><a href='frm_approve.php?sort=C_REGIS&order=<?php echo $strNewOrder ?>'><u>เลขทะเบียน</u></th>
							<th width="150"><a href='frm_approve.php?sort1=C_CARNUM&order1=<?php echo $strNewOrder1 ?>'><u>เลขตัวถังรถ</u></th>
							<th width="110"><a href='frm_approve.php?sort1=C_StartDate&order1=<?php echo $strNewOrder1 ?>'><u>วันจดทะเบียน</u></th>			
							<th width="150"><a href='frm_approve.php?sort1=id_user&order1=<?php echo $strNewOrder1 ?>'><u>ผู้ขออนุมัติ</u></th>
							<th width="150"><a href='frm_approve.php?sort1=date_submit&order1=<?php echo $strNewOrder1 ?>'><u>วันที่ขออนุมัติ</u></th>
							<th width="150"><a href='frm_approve.php?sort1=app_user&order1=<?php echo $strNewOrder1 ?>'><u>ผู้อนุมัติ</u></th>
							<th width="150"><a href='frm_approve.php?sort1=date_submit&order1=<?php echo $strNewOrder1 ?>'><u>วันที่อนุมัติ</u></th>
							<th width="50">เพิ่มเติม</th>
							<th width="50"><a href='frm_approve.php?sort1=appstatus&order1=<?php echo $strNewOrder1 ?>'><u>สถานะ</u></th>
							
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
				<table align="center" width="100%">
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
</form>
</body>

<script type="text/javascript">
function app(frm)
{

var con = $("#chkchoise").val();
var numchk;
numchk = 0;

	for(var num = 1;num<=con;num++){	
		if(document.getElementById("chkapp"+num).checked){
			numchk+=1;			
		}		
	}
	if(numchk == 0){
		alert("กรุณาเลือกรายการก่อน");
	}else{
		if(confirm('ยืนยัน การอนุมัติ')==true){
			frm.action="process_app.php";
			frm.submit();
			document.myform.submit.disabled='true';
			return true;
		}else{ 
			return false;
		}
	}	
}

function not(frm)
{

var con = $("#chkchoise").val();
var numchk;
numchk = 0;

	for(var num = 1;num<=con;num++){	
		if(document.getElementById("chkapp"+num).checked){
			numchk+=1;			
		}		
	}
	if(numchk == 0){
		alert("กรุณาเลือกรายการก่อน");
	}else{
		if(confirm('ยืนยัน ปฎิเสธการอนุมัติ')==true){
			frm.action="frm_not_approve.php";
			frm.submit();
			document.myform.submit.disabled='true';
			return true;
		}else{ 
			return false;
		}
	}	
}
</script>