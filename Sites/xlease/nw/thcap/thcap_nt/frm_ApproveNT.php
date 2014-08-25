<?php
include("../../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../../index.php");
    exit;
}

$app_date = Date('Y-m-d H:i:s');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) อนุมัติ Create NT</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../act.css"></link>

    <link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

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
		<div class="header"><h1>(THCAP) อนุมัติ Create NT</h1></div>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<td>เลขที่สัญญา</td>
				<td>ประเภทสินทรัพย์ที่จำนอง</td>
				<td>วันที่ทำสัญญาจำนอง</td>
				<td>ทนายความผู้รับมอบอำนาจ</td>
				<td>ผู้ทำรายการ</td>
				<td>วันเวลาที่ทำรายการ</td>
				<td>รายละเอียด</td>
			</tr>
			<?php
			$qry_app=pg_query("SELECT distinct(\"contractID\"), \"NT_1_guaranID\", \"NT_1_Date\", \"NT_1_Lawyer_Name\",\"fullname\", \"NT_1_AddStamp\"
			FROM \"thcap_NT1_temp\" a
			LEFT JOIN \"Vfuser\" b on a.\"NT_1_AddUser\"=b.\"id_user\"
			WHERE \"NT_1_Status\"='2' order by \"NT_1_AddStamp\"");
			$nub=pg_num_rows($qry_app);
			while($res_app=pg_fetch_array($qry_app)){
				$contractID=$res_app["contractID"]; //เลขที่สัญญา
				$NT_1_guaranID=$res_app["NT_1_guaranID"]; //ประเภทสินทรัพย์ที่จำนอง
				$NT_1_Date=$res_app["NT_1_Date"];//วันที่ทำสัญญาจำนอง
				$NT_1_Lawyer_Name=$res_app["NT_1_Lawyer_Name"];//ทนายความผู้รับมอบอำนาจ
				$fullname=$res_app["fullname"];//พนักงานที่ทำรายการ
				$NT_1_AddStamp=$res_app["NT_1_AddStamp"];//วันเวลาที่ทำรายการ
				
				//หาว่าเป็นสัญญาประเภทใด
				$qrytype=pg_query("select \"thcap_get_creditType\"('$contractID')");
				list($contype)=pg_fetch_array($qrytype);
			
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
				
			?>
				<td><span onclick="javascript:popU('../../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID;?></u></font></span></td>
				<td align="left"><?php echo $NT_1_guaranID; ?></td>
				<td><?php echo $NT_1_Date; ?></td>
				<td align="left"><?php echo $NT_1_Lawyer_Name; ?></td>
				<td align="left"><?php echo $fullname; ?></td>
				<td><?php echo $NT_1_AddStamp; ?></td>			
				<td>
				<?php
				if($contype=='LOAN' || $contype=='JOINT_VENTURE' || $contype=='PERSONAL_LOAN'){
					echo "<span onclick=\"javascript:popU('show_ApproveNT1.php?contractID=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor: pointer;\"><u>แสดงรายการ</u></span>";
				}
				?>
				</td>
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=8 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>
<?php
//include("frm_appvcancel_history_limit.php");
?>
</body>
</html>