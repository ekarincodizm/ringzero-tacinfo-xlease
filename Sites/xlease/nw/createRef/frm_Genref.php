<?php
include("../../config/config.php");
$IDNO = $_GET['IDNO'];


$qry_name=pg_query("select * from \"VContact\" WHERE \"IDNO\" = '$IDNO'");
$result=pg_fetch_array($qry_name);

$name=trim($result["full_name"]);
$C_REGIS=trim($result["C_REGIS"]);
$car_regis=trim($result["car_regis"]);
$carnum=trim($result["carnum"]);
$C_CARNUM=trim($result["C_CARNUM"]);

if($C_CARNUM==""){
	$c_carnum=$carnum;
}else{
	$c_carnum=$C_CARNUM;
}

if($C_REGIS==""){
	$c_regis=$car_regis;
}else{
	$c_regis=$C_REGIS;
}
?>

<div class="ui-widget" align="left">
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
$(document).ready(function(){
	$("#submitbutton").click(function(){
		$.post("process_genref.php",{
			IDNO : '<?php echo $IDNO;?>',
			c_carnum : '<?php echo $c_carnum;?>'
		},
		function(data){
			if(data == "999999999"){
				alert("ไม่สามารถสร้างรหัสโอนเงินได้");
			}else{
				alert(data);
			}
		});
	});
});
</script>
<?php
if($IDNO != ""){
?>

<hr width="850">
<table width="850" cellSpacing="1" cellPadding="3" border="0" bgcolor="#D7F0FD" align="center">
<tr bgcolor="#FFFFFF"><td colspan="4"><b>เลขที่สัญญา : <font color="red"><?php echo $IDNO;?></font></b></td></tr>
<tr bgcolor="#BCE6FC">
    <td width="150" align="right"><b>ชื่อ/สกุล :</b></td>
    <td bgcolor="#FFFFFF"><?php echo $name; ?></td>
    <td valign="top" align="right"><b>ทะเบียนรถยนต์ :</b></td>
    <td bgcolor="#FFFFFF"><?php echo $c_regis;?></td>
</tr>
</table>
<div style="text-align:center;padding-top:20px;"><input type="button" name="btn1" id="submitbutton" value="สร้างรหัสโอนเงิน" style="width:150px;height:30px;"></div>
<?php }else{
	echo "<hr width=850>";
	echo "<center><h1>ไม่พบข้อมูล</h1></center>";
}?>
