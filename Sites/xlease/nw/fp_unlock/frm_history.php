<?php
if($limit==""){
	include("../../config/config.php");
	$txthead="ประวัติการอนุมัติอนุมัติปลดล็อกสัญญาเช่าซื้อทั้งหมด";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ประวัติการอนุมัติอนุมัติปลดล็อกสัญญาเช่าซื้อ</title>
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
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="8" align="left" style="font-weight:bold;"><?php echo $txthead;?> <?php if($limit=="limit 30"){?>(<a style="color:#0099FF;cursor:pointer;" onclick="javascript:popU('frm_history.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=650')"><u>ทั้งหมด</u></a>)<?php } ?></td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#CCCCCC" align="center">
				<td>เลขที่สัญญา</td>
				<td>ผู้ทำรายการ</td>
				<td>วันเวลาที่ทำรายการ</td>
				<td>หมายเหตุการขอปลดล็อก</td>
				<td>ผู้อนุมัติ</td>
				<td>วันเวลาที่อนุมัติ</td>
				<td>หมายเตุการอนุมัติ</td>
				<td>สถานะอนุมัติ</td>
			</tr>
			<?php
			$qry_app=pg_query("select * from \"Fp_unlock\" where \"appvStatus\" in('0','1') order by \"appvStamp\" DESC $limit");
			$nub=pg_num_rows($qry_app);
			while($res_fr=pg_fetch_array($qry_app))
			{
				$autoID = $res_fr["autoID"];
				$IDNO = $res_fr["IDNO"];
				$doerID = $res_fr["doerID"];
				$doerStamp = $res_fr["doerStamp"];
				$doerRemark = $res_fr["doerRemark"];
				$appvID = $res_fr["appvID"];
				$appvStamp = $res_fr["appvStamp"];
				$appvStatus = $res_fr["appvStatus"];
				$appvRemark = $res_fr["appvRemark"];
				
				// หาชื่อพนักงาน
				$qry_doerfullname = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$doerID' ");
				$doerfullname = pg_result($qry_doerfullname,0);
				
				// หาชื่อผู้อนุมัติ
				$qry_appvfullname = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$appvID' ");
				$appvfullname = pg_result($qry_appvfullname,0);
				
				$i+=1;
				if($i%2==0){
					echo "<tr bgcolor=#DDDDDD align=center onmouseover=\"javascript:this.bgColor = '#EECBAD';\" onmouseout=\"javascript:this.bgColor = '#DDDDDD';\">";
				}else{
					echo "<tr bgcolor=#EEEEEE align=center onmouseover=\"javascript:this.bgColor = '#EECBAD';\" onmouseout=\"javascript:this.bgColor = '#EEEEEE';\">";
				}
				
			?>
				<td><span onclick="javascript:popU('../../post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO; ?>&type=outstanding','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1300,height=800')" style="cursor: pointer;" title="ดูตารางการชำระ"><font color="red"><u><?php echo $IDNO;?></u></font></span></td>
				<td align="left"><?php echo $doerfullname; ?></td>
				<td align="center"><?php echo $doerStamp; ?></td>		
				<td align="left"><?php echo $doerRemark; ?></td>
				<td align="left"><?php echo $appvfullname; ?></td>			
				<td align="center"><?php echo $appvStamp; ?></td>
				<td align="left"><?php echo $appvRemark; ?></td>
				<td align="center">	
					<?php 
					if($appvStatus==0){
						echo "<font color=\"#FF0000\">ไม่อนุมัติ</font>";
					}elseif($appvStatus==1){
						echo "<font color=\"#0000FF\">อนุมัติ</font>";
					}else{
						echo "";
					}
					?>
				</td>
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=8 align=center height=50><b>- ไม่พบประวัติ -</b></td></tr>";
			}else{
				echo "<tr bgcolor=\"#CCCCCC\"><td colspan=8><b><b>มีทั้งหมด $nub รายการ</b></b></td></tr>";			
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>

</body>
</html>