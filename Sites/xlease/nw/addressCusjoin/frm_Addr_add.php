<?php
include("../../config/config.php");
$carid = $_GET['carid'];
?>

<div class="ui-widget" align="left">
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
$(document).ready(function(){
	$('#rename').click(function(){
		$("#cusco").val('');
		$("#cusco").focus();
    });
	
	$("#cusco").autocomplete({
        source: "s_cusco.php",
        minLength:2
    });
});

function checkdata(){
	if(document.form1.cusco.value==""){
		alert("กรุณาระบุชื่อลูกค้าโอนสิทธิ์เข้าร่วม");
		document.form1.cusco.focus();
		return false;
	}else if(document.form1.address.value==""){
		alert("กรุณากรอกที่อยู่ลูกค้า");
		document.form1.address.focus();
		return false;
	}else{
		return true;
	}
}
</script>
<?php
if($carid != ""){
//ค้นหาชื่อ-นามสกุล
$qry_name=pg_query("SELECT \"cpro_name\", \"address\" FROM \"VJoinMain\"  
where car_license_seq='0' and deleted='0' and cancel='0' and carid='$carid' order by id DESC limit 1");
list($cusname,$address)=pg_fetch_array($qry_name);
$cusname=trim($cusname);
$address=trim($address);


				$sql_query5=pg_query("select \"P_ACCLOSE\",\"full_name\",\"CusID\" from \"VJoin\" v WHERE v.\"asset_id\" = '$carid' order by v.\"P_STDATE\" desc limit 1 ");// ข้อมูลล่าสุด

				if($sql_row5 = pg_fetch_array($sql_query5))
				{	
				  
   					
					 
    				$P_ACCLOSE = $sql_row5["P_ACCLOSE"];
					

					if($P_ACCLOSE=='f'){//ถ้าค่าเข้าร่วมเปิด xlease เปิด ให้ดึงล่าสุดมา
						$cusname = $sql_row5['full_name'];
					}
					
				}
?>
<hr width="850">
<form method="post" name="form1" action="process_cusjoin.php">
<table width="600" cellSpacing="1" cellPadding="3" border="0" bgcolor="#D7F0FD" align="center">
<tr bgcolor="#BCE6FC">
    <td width="150" align="right"><b>ชื่อ-นามสุกลลูกค้า :</b></td>
    <td bgcolor="#FFFFFF"><input type="text" name="cusco" id="cusco" value="<?php echo $cusname; ?>" size="50" readonly></td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>ที่อยู่ :</b></td>
    <td bgcolor="#FFFFFF"><textarea cols="50" rows="5" name="address"><?php echo $address;?></textarea></td>
</tr>
</table>
<div style="padding:15px;text-align:center;"><input type="hidden" name="carid" value="<?php echo $carid;?>"><input type="submit" value="บันทึก" onclick="return checkdata();"><input type="reset" value="ยกเลิก"></div>
</form>
<?php }else{
	echo "<hr width=850>";
	echo "<center><h1>ไม่พบข้อมูล</h1></center>";
}?>
