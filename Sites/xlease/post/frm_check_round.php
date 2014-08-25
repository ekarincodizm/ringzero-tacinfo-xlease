<?php
session_start();
include("../config/config.php");	
$IDNO=pg_escape_string($_GET["idno"]);
$startdate=pg_escape_string($_GET["startdate"]);
$nowdate=nowDate();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title><?php echo $_SESSION["session_company_name"]; ?></title>
<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<div id="5g" style="margin-top:10px;" align="center"><h2>แสดงรอบตรวจภาษีรถยนต์/มิเตอร์ สัญญา <?php echo $IDNO; ?></h2></div>
<body>
<div><span style="background-color:#FFCCCC">&nbsp;&nbsp;&nbsp;&nbsp;</span><b> คือ รอบตรวจที่จะถึงกำหนดในครั้งถัดไป </b></div>
<table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFF">
<tr height="25">
	<th bgcolor="#C1CDC1"  width="50%">รอบตรวจภาษีรถยนต์</th>
	<th bgcolor="#CDC8B1">รอบตรวจมิเตอร์</th>	
</tr>
<tr>
	<?php
	for($i=1;$i<=2;$i++){
		if($i==1){
			$val=1;
			$color="#C1CDC1";
			$color2="#EEE0E5";
			$color3="#E0EEE0";
			$color4="#F0FFF0";
		}else{
			$val=2;
			$color="#CDC8B1";
			$color2="#EEE8CD";
			$color3="#FFF8DC";
			$color4="#FFFFF0";
		}
	?>
	<td valign="top">
		<table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="<?php echo $color2;?>">
		<tr bgcolor="<?php echo $color;?>"><th>รอบที่</th><th>วันที่</th></tr>
		<?php
			//นับว่ามีทั้งหมดกี่รายการ
			$qrynub=pg_query("SELECT ta_array1d_count(carregis.\"CreateCheckRound\"('$startdate','$val'))");
			list($nub)=pg_fetch_array($qrynub);

			//ดึงข้อมูลออกมาแสดง
			$p=1;
			$l=0;
			for($j=0;$j<$nub;$j++){
				$qrydata=pg_query("SELECT ta_array1d_get(carregis.\"CreateCheckRound\"( '$startdate','$val'),'$j')");
				list($enddate)=pg_fetch_array($qrydata);
				
				if($p%2==0){
					$color=$color3;
				}else{
					$color=$color4;
				}
				
				//ถ้าจะถึงรอบแ้ล้ว หรือรอบถัดไป ให้แถวเป็นสีแดง
				if($enddate >= $nowdate and $l==0){
					$color="#FFCCCC";
					$l++;
				}
				echo "<tr align=center height=25 bgcolor=$color><td>$p</td><td>$enddate</td></tr>";
				$p++;
			}
		?>
		</table>
	</td>
	<?php
	}
	?>
</tr>
</table>	
</body>
</html>

