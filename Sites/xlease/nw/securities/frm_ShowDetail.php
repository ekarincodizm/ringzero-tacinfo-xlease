<?php
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
$securID=$_GET["securID"];


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
		<div class="wrapper">
			<!--ข้อมูลลูกค้า-->
			<?php
			$qry_cussec=pg_query("select \"CusID\"  from \"nw_securities_customer\" where \"securID\" ='$securID'");			
			list($CusID)=pg_fetch_array($qry_cussec);
				$qry_cusname = pg_query("SELECT full_name FROM \"Fa1_FAST\" where \"CusID\" = '$CusID' ");
				list($Cus_fullname)=pg_fetch_array($qry_cusname);	
			?>
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" style="font-weight:bold;">ข้อมูลลูกค้า</td>
			</tr>
			<tr valign="middle" bgcolor="#FFFF99" align="center">
				<td align="left">( <font color="red"><u><b><a style="cursor:pointer;" onclick="javascript:popU('../manageCustomer/frm_ShowDetail.php?CusID=<?php echo $CusID;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=350');"><?php echo $CusID;?></a></b></u></font> )
				&nbsp&nbsp&nbsp
				<?php echo $Cus_fullname;?>
				&nbsp&nbsp&nbsp 
				<font color="#AAAAAA"><u><b><a style="cursor:pointer;" onclick="javascript:popU('../search_cusco/index.php?cusid=<?php echo $CusID;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=550');">ตรวจสอบข้อมูลเครดิต </a></b></u></font></td>
			</tr>			
			</table>
		
			<!--ข้อมูลปัจจุบัน-->
			<?php
			$qry_cus=pg_query("select * from \"nw_securities\" where \"securID\" ='$securID'");
			$num_cus=pg_num_rows($qry_cus);
			if($res_fr=pg_fetch_array($qry_cus)){
				$securID=$res_fr["securID"];
				$numDeed=$res_fr["numDeed"];
				$guaranID=$res_fr["guaranID"];
				$numBook = $res_fr["numBook"]; 
				$numPage = $res_fr["numPage"];
				$numLand = $res_fr["numLand"];
				
				$condoregisnum = trim($res_fr["condoregisnum"]);
				$condobuildingname = trim($res_fr["condobuildingname"]);
				$condoroomnum = trim($res_fr["condoroomnum"]); //ห้องชุดเลขที่
				$condofloor = trim($res_fr["condofloor"]); //ชั้นที่
				$condobuildingnum = trim($res_fr["condobuildingnum"]); //อาคารเลขที่
			}
			?>
            <div style="display:block; width:100%;">
            	<div style="display:block; text-align:left; font-weight:bold; padding-top:10px;">ข้อมูลปัจจุบัน</div>
                <div id="current_data"></div>
            </div>
			
			<!--ข้อมูลที่ถูกแก้ไข-->
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" style="font-weight:bold;">แสดงข้อมูลหลักทรัพย์</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<td>ครั้งที่</td>
				<td>ประเภทการขออนุมัติ</td>
				<td>ผู้ทำรายการ</td>
				<td>วันเวลาทำรายการ</td>
				<td>ผู้อนุมัติรายการ</td>
				<td>วันเวลาอนุมัติรายการ</td>
				<td>ผลการแก้ไข</td>
				<td>ดูข้อมูล</td>
			</tr>
			<?php
			$qry_temp=pg_query("select *,b.\"fullname\" as user_add,c.\"fullname\" as user_app from \"temp_securities\" a
				left join \"Vfuser\" b on a.\"user_add\"=b.\"id_user\"
				left join \"Vfuser\" c on a.\"user_app\"=c.\"id_user\"
				where \"securID\" ='$securID' order by \"edittime\"");
			$num_temp=pg_num_rows($qry_temp);
			while($res_fr=pg_fetch_array($qry_temp)){
				$auto_id=$res_fr["auto_id"];
				$securID=$res_fr["securID"];
				$numDeed=$res_fr["numDeed"];
				$numBook = $res_fr["numBook"]; 
				$numPage = $res_fr["numPage"];
				$numLand = $res_fr["numLand"];
				$user_add = $res_fr["user_add"];
				$user_app = $res_fr["user_app"];
				$stampDateAdd = $res_fr["stampDateAdd"]; 
				$stampDateApp = $res_fr["stampDateApp"];
				$edittime = $res_fr["edittime"];
				$statusApp=$res_fr["statusApp"];
				if($edittime ==0){
					$txttype="ขอเพิ่มข้อมูล";
				}else{
					$txttype="ขอแก้ไขข้อมูล";
				}
				
				
				if($statusApp=="0"){ //กรณีเป็นการเพิ่มข้อมูล
					$txtapp="ไม่อนุมัติ";
				}else if($statusApp=="1"){
					$txtapp="อนุมัติ";
				}else if($statusApp=="2"){
					$txtapp="รออนุมัติ";
				}
				
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
			?>
				<td><?php echo $edittime; ?></td>
				<td><?php echo $txttype; ?></td>
				<td align="left"><?php echo $user_add; ?></td>
				<td><?php echo $stampDateAdd; ?></td>
				<td align="left"><?php echo $user_app;?></td>
				<td><?php echo $stampDateApp;?></td>
				<td><?php echo $txtapp;?></td>
				<td><span onclick="javascript:popU('showalldetail.php?auto_id=<?php echo $auto_id; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;"><img src="images/detail.gif" height="19" width="19" border="0"></span></td>
			</tr>
			<?php
			} //end while
			if($num_temp == 0){
				echo "<tr><td colspan=10 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>

</body>
</html>
