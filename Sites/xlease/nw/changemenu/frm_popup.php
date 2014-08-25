<?php
include("../../config/config.php");

$id_user=$_GET["id_user"];
$method=$_GET["method"];
$add_date2=$_GET["add_date"];
$id_popup=$_GET["idpopup"];
pg_query("BEGIN WORK");
$status = 0;

if($method == 2){
	$upd="update \"nw_changemenu\" set \"statusOKapprove\"='TRUE' where \"id_user\"='$id_user' and \"statusApprove\"='2' and \"add_date\"='$add_date2'";
	if($resup=pg_query($upd)){
	}else{
		$status++;
	}
}else if($method == 3){
	$upd="update \"nw_changemenu\" set \"statusOKapprove\"='TRUE' where \"id_user\"='$id_user' and \"statusApprove\"='3' and \"add_date\"='$add_date2'";
	if($resup=pg_query($upd)){
	}else{
		$status++;
	}
}
if($status == 0){
	pg_query("COMMIT");
}else{
	pg_query("ROLLBACK");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ขอเปลี่ยนแปลงสิทธิ์การทำงาน</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
$(document).ready(function(){
    $("#id_user").autocomplete({
        source: "s_userid.php",
        minLength:1
    });
});
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

function RefreshMe(){
    opener.location.reload(true);
    self.close();
}

</script>
    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}

.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
</style>  
</head>
<body>
<div style="text-align:center;padding-bottom: 10px;"><h2>ขอเปลี่ยนแปลงสิทธิ์การทำงาน</h2></div>
<br>
<!--แสดงรายการที่รออนุมัติ-->
<table width="900" border="0" cellspacing="1" cellpadding="1" align="center" style="background-color:#EEEDCC">
    <tr bgcolor="#FFFFFF">
        <td height="25" colspan="7"><h3>รายการที่รออนุมัติ</h3></td>
    </tr>
	<tr style="background-color:#D0DCA0" height="25" align="center">
		<th width="50">ที่</th>
		<th>ชื่อ-สกุลพนักงาน</th>
		<th width="100">วันที่ส่งเรื่อง</th>
		<th width="150">รายละเอียดการร้องขอ</th>

	</tr>
	<?php
		$qry=pg_query("select distinct(a.\"id_user\"),\"fullname\",\"statusApprove\",\"add_date\" from \"nw_changemenu\" a 
		left join \"Vfuser\" b on a.\"id_user\"=b.\"id_user\" 
		WHERE a.\"statusApprove\" != '1' and \"statusOKapprove\"='FALSE' and b.\"id_user\"='$id_popup' group by a.\"id_user\",\"fullname\",\"statusApprove\",\"add_date\" order by \"add_date\"");
		$numrow=pg_num_rows($qry);
		if($numrow==0){
			echo "<tr><td colspan=7 height=50 align=center><b>ไม่พบรายการ</b></td></tr>";
		}else{
			$i=1;
			while($resqry=pg_fetch_array($qry)){
				$id_user=$resqry["id_user"];
				$add_date=$resqry["add_date"];
				$fullname=$resqry["fullname"];
				$statusApprove=$resqry["statusApprove"];
				if($statusApprove=='0'){
					$txtapp="<font color=red>รออนุมัติ</font>";
				}else if($statusApprove=='2'){
					$txtapp="อนุมัติแล้ว";
				}else if($statusApprove=='3'){
					$txtapp="ไม่อนุมัติ";
				}
				
				echo "<tr height=25 bgcolor=#F4FED6>";
				echo "<td align=center>$i</td>";
				echo "<td>$fullname</td>";
				echo "<td align=center>$add_date</td>";
				echo "<td align=center><a onclick=\"javascript:popU('frm_show_request.php?id_user=$id_user&status=$statusApprove&add_date=$add_date','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=800')\"><img src=\"images/detail.gif\" width=19 height=19 border=0 style=\"cursor:pointer;\"></a></td>";
				
				echo "</tr>";
				$i++;
			}
		}
	?>
</table>
</body>
</html>