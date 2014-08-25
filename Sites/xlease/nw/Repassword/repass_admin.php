<?php
session_start();
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
if($_SESSION["session_company_code"]=="AVL")
{
 $file_namepic="logo_av.jpg";
}
else
{
 $file_namepic="logo_thaiace.jpg";
}
include("../../config/config.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> ยืนยันการขอรหัสผ่านใหม่ </title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};
</script>
<style type="text/css">
    #warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(240, 240, 240);
	padding: 5px;
	border: rgb(128, 128, 128) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
	}
.style1 {
	font-size: small;
	font-weight: bold;
}
.style2 {
	font-size: medium;
	font-weight: bold;
}
</style>
</head>

<body>
<div style="width:75%;margin:0px auto">
	<h1><B>ตารางการขอรหัสผ่านใหม่</B></h1>

	<?php
			$date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
			$strSQL = "		SELECT a.*,b.\"fullname\"
							FROM \"repass_admin\" a 
							LEFT JOIN \"Vfuser\" b ON a.\"id_user\" = b.\"id_user\"
							WHERE a.\"repass_status\" = '0' 
							ORDER BY \"repassID\" DESC";
			$objQuery = pg_query($strSQL);
			$nrows=pg_num_rows($objQuery);
			
	?>
			<table align="center" width="100%" frame="box" cellspacing="1" cellpadding="1">
					<tr align="center" bgcolor="#CDB79E" >
						<th width="70">รายการที่</th>
						<th width="">ชื่อ-นามสกุล ผู้ขอเปลี่ยน</th>
						<th width="">Username</th>
						<th width="">วันที่ขอ</th>
						<th width="">ยืนยัน PIN</th>
						<th width="">ยืนยัน</th>
						<th width="">ปฎิเสธ</th>
					</tr>
	<?php
				if($nrows != 0){	
					$z = 0;
					while($results = pg_fetch_array($objQuery)){
							$repassid = $results["repassID"];
							$z++;
	?>					
						<form name="frm" action="repass_admin_query.php" method="POST">
	<?php
								if($z%2==0){
									echo "<tr bgcolor=\"#EED5B7\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EED5B7';\" align=\"center\">";
								}else{
									echo "<tr bgcolor=\"#FAEBD7\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FAEBD7';\" align=\"center\">";
								}
	?>
									<input type="hidden" name="hdrepassid" id="hdrepassid" value="<?php echo $results["repassID"];?>">
									<td><?php echo $z;?></td>
									<td align="left"><?php echo $results["fullname"];?></td>
									<td><?php echo $results["repass_username"];?></td>
									<td><?php echo $results["repass_date"];?></td>
									<td><input type="Text" maxlength="4" size="4" name="pin" id="pin"></td>							
									<td><input type="submit" name="bt_ok" id="bt_ok" value="รับทราบ" ></td>
									<td><input type="button" name="bt_can" id="bt_can" value="ปฎิเสธ" onclick="parent.location.href='repass_no_query.php?PASSID=<?php echo $repassid;?>'"></td>
							 </tr>
						</form>
	  
	<?php			}
			}else{ 
						echo "<tr><td colspan=\"8\" align=\"center\"><h1>ไม่พบข้อมูล</h1></td></tr>";
			}
	?>
							<tr>
								<td bgcolor="#CDB79E" colspan="10" ></div> มีทั้งหมด <?php echo $nrows; ?> รายการ</td>
							</tr>
						
							
			</table>


	<div style="padding-top:30px;"></div>
		<?php
			$limitshow = "t";
			include("frm_history.php");
		?>
</div>	
</body>
</html>
