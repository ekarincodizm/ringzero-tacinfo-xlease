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
	
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}


</script>

</head>
<body>
<div align="center"><h2>ประวัติการอนุมัติ NT ทั้งหมด</h2></div>
<table align="center" width="80%" border="0" cellspacing="1" cellpadding="1" bgcolor="#F0F0F0">
	<tr align="center" bgcolor="#B5B5B5">
		<th height="30">รายการที่</th>
		<th>IDNO</th>
		<th>ผู้เช่าซื้อ</th>
		<th>วันที่ออก NT</th>
		<th>ผู้ออก NT</th>
		<th>วันที่อนุมัติ</th>
		<th>ผู้อนุมัติ</th>
		<th>สถานะ</th>
		<th>รายละเอียด</th>
	</tr>
	<?php 
	$query = pg_query("select a.\"NTID\",a.\"IDNO\",d.\"do_date\",c.\"A_FIRNAME\",c.\"A_NAME\",c.\"A_SIRNAME\",f.\"fullname\",a.\"date_approve\",g.\"fullname\" as \"appfullname\",a.\"statusNT\" from \"nw_statusNT\" a
						left join \"Fp\" b on a.\"IDNO\" = b.\"IDNO\"
						left join \"Fa1\" c on b.\"CusID\" = c.\"CusID\"
						left join \"NTHead\" d on a.\"NTID\" = d.\"NTID\"
						left join \"logs_NTDetail\" e on a.\"NTID\" = e.\"NTID\"
						left join \"Vfuser\" f on e.\"id_user\" = f.\"id_user\"
						left join \"Vfuser\" g on a.\"user_approve\" = g.\"id_user\"
						where d.\"CusState\"='0' and a.\"statusNT\" != 0 group by a.\"NTID\",a.\"IDNO\",d.\"do_date\",c.\"A_FIRNAME\",c.\"A_NAME\",c.\"A_SIRNAME\",f.\"fullname\",a.\"date_approve\",g.\"fullname\",a.\"statusNT\"
						order by a.\"date_approve\" DESC"); 

	$numrows = pg_num_rows($query);
	$i=1;
	while($result = pg_fetch_array($query)){
		$NTID = $result["NTID"];
		$IDNO = $result["IDNO"]; 
		$cusname = trim($result["A_FIRNAME"]).trim($result["A_NAME"])."  ".trim($result["A_SIRNAME"]); 
		$do_date = $result["do_date"];
		$fullname = $result["fullname"];
		$appfullname = $result["appfullname"];
		$date_approve = $result["date_approve"];
		$statusNT = $result["statusNT"];
		if($statusNT == '2'){
			$statusapp = 'ไม่อนุมัติ';
		}else{
			$statusapp = 'อนุมัติ';
		}
		
		if($i%2==0){
			echo "<tr bgcolor=#CFCFCF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#CFCFCF';\" align=center>";
		}else{
			echo "<tr bgcolor=#E8E8E8 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#E8E8E8';\" align=center>";
		}
		
		echo "<td align=center valign= height=25>$i</td>";
		echo "<td valign= align=center><span onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$IDNO&type=outstanding','$IDNO','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" style=\"cursor: pointer;\" title=\"ดูตารางการชำระ\"><u>$IDNO</u></span></td>";
		echo "<td valign=>$cusname</td>";
		echo "<td valign= align=center>$do_date</td>";
		echo "<td valign=>$fullname</td>";
		echo "<td valign= align=center>$date_approve</td>";
		echo "<td valign=>$appfullname</td>";
		echo "<td valign=>$statusapp</td>";
		echo "<td valign= align=center><img src=\"../thcap/images/detail.gif\" style=\"cursor:pointer\" title=\"รายละเอียด\" onclick=\"javascript:popU('compare_nt_detail.php?IDNO=$IDNO&NTID=$NTID&cusname=$cusname','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" \"></td>";
		echo "</tr>";	
		$i++;
	} //end while

	if($numrows==0){
		echo "<tr bgcolor=#B5B5B5 height=50><td colspan=9 align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		$i=$i-1;
		echo "<tr bgcolor=\"#B5B5B5\" height=30><td colspan=9><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
</table>
<div style="text-align:center;"><input type="button" value="ปิด" onclick="window.close();" style="width:100px;height:50px;"></div>
</body>
</html>