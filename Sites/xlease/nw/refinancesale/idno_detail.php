<?php
session_start();
include("../../config/config.php");

$g_cusid = $_GET['CusID'];

$qry_1=pg_query("select * from \"VContact\" WHERE \"CusID\"='$g_cusid'");
if($res_1=pg_fetch_array($qry_1)){
    $IDNO = $res_1['IDNO'];
	$full_name = $res_1['full_name'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รวมสัญญาทั้งหมดที่มีอยู่</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(function(){
    $("#tabs").tabs();
    $("#tabs").tabs('select', '<?php echo $_SESSION["ses_idno"]; ?>');
});
</script>
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<style type="text/css">
.ui-tabs{
    font-family:tahoma;
    font-size:12px
}
</style>

<style type="text/css">
body {
    font-family:tahoma;
    color : #333333;
    font-size:12px;
}
.title_top{
    font-family: tahoma;
    font-size:19px;
    font-weight: bold;
    margin: 0;
    padding-bottom: 3px;
    text-align: right;
}
</style>

</head>
<body>

<div class="title_top">รวมสัญญาทั้งหมดที่มีอยู่</div>

<div id="tabs"> <!-- เริ่ม tabs -->
<ul>
<?php
//สร้าง list รายการ โอนสิทธิ์
    echo "<li>$full_name ($g_cusid)</li>";

?>
</ul>
 
<div id="tabs-<?php echo $g_cusid; ?>">

 <table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
 <tr>
    <td>
	<table cellpadding="3" cellspacing="1" border="0" width="100%" bgcolor="#E8FFE8">
	<tr bgcolor="#A8D3FF"><th>ลำดับที่</th><th>เลขที่สัญญา</th><th>ทะเบียนรถยนต์</th></tr>
	<?php
		$i=1;
		$qry_2=pg_query("select * from \"VContact\" WHERE \"CusID\"='$g_cusid'");
		while($res_2=pg_fetch_array($qry_2)){
			$IDNO2= $res_2['IDNO'];
			if($res_2["C_REGIS"]==""){
				$regis=$res_2["car_regis"];														
			}else{
				$regis=$res_2["C_REGIS"];					
			}
		?>
		<tr bgcolor="#FFCACA" align="center">
			<td ><?php echo $i;?></td>
			<td><a href="#" onclick="javascript:popU('../../post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO2?>&type=outstanding','<?php echo $IDNO_sdasdsadsa?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" title=" ดูตารางการผ่อนชำระ"><u><?php echo $IDNO2;?></u></a></td>
			<td><?php echo $regis;?></td>
		</tr>
		<?php 
		$i++;
		}
		?>
	</table>
	</td>
 </tr>
 </table>

</div>

</div>

</body>
</html>