<?php
set_time_limit(0);
session_start();
include("../../config/config.php");
$db1="ta_mortgage_datastore";
$show="";
$show=$_REQUEST["show"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title>(THCAP) Approve FE</title>
<script language=javascript>
$(document).ready(function(){
	$('#btn1').click(function(){
		$("#btn1").attr('disabled',true);
		$("#panel").text('กำลังตรวจสอบข้อมูลเพื่อดึงเข้ามาในระบบ...ระบบอาจจะใช้เวลาประมวลผลนาน 1-5 นาที');
		$("#panel").load("checkindex.php");
		$("#btn1").attr('disabled',false);
    });	
});
function selectAll(select){
    with (document.form2)
    {
        var checkval = false;
        var i=0;

        for (i=0; i< elements.length; i++)
            if (elements[i].type == 'checkbox' && !elements[i].disabled)
                if (elements[i].name.substring(0, select.length) == select)
                {
                    checkval = !(elements[i].checked);    break;
                }

        for (i=0; i < elements.length; i++)
            if (elements[i].type == 'checkbox' && !elements[i].disabled)
                if (elements[i].name.substring(0, select.length) == select)
                    elements[i].checked = checkval;
    }
}
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>

<div  align="center"><h2>(THCAP) Approve FE</h2></div>
<form method="post" name="form1" action="frm_Index.php">
<div class="title_top" align="center" >
	<input type="hidden" name="show" value="1">
	<input type="submit" value="ตรวจสอบ" name="btn1" style="width: 150px; height:30px">
</div>
</form>
<div id="panel" style="padding-top: 10px;">
<?php
if($show==1){
?>
<form method="post" name="form2" action="process_check.php">
<table align="center" width="60%" border="0" cellspacing="1" cellpadding="1" bgcolor="#F0F0F0">
	<tr align="center" bgcolor="#79BCFF">
		<td height="30">เลขที่สัญญา</td>
		<td>ชื่อผู้เช่าซื้่อ</td>
		<td>วันที่เริ่มสัญญา</td>
		<td>จำนวนเงินกู้</td>
		<td><a href="#" onclick="javascript:selectAll('chk');"><u>ทั้งหมด</u></a></td>
	</tr>
	<?php 
	$query = mysql_query("select a.contract_loans_code,a.contract_loans_startdate,b.cusname,a.appv_credit_money from $db1.loan_data a
	inner join $db1.vcustomerbycontract b on a.contract_loans_code=b.contract_loans_code 
	where b.cus_group_type_code='01'"); 
	$i=0;
	while($result = mysql_fetch_array($query)){
		$contract_loans_code = $result["contract_loans_code"];
		$startdate = $result["contract_loans_startdate"];
		$y=substr($startdate,0,4);
		if($y>="2400"){
			$y=$y-543;
		}else{
			$y=$y;
		}
		$m=substr($startdate,5,2);
		$d=substr($startdate,8,2);
		$contract_loans_startdate=$y."-".$m."-".$d;
		$cusname = $result["cusname"];
		$appv_credit_money = $result["appv_credit_money"];
		
		//นำมาค้นหาใน pg ว่ามีข้อมูลหรือไม่ ถ้าไม่มีให้แสดง
		$qrychk=pg_query("select * from \"thcap_mg_contract\" where \"contractID\"='$contract_loans_code'");
		$numchk=pg_num_rows($qrychk);
		
		if($numchk==0){ //ถ้าไ่ม่มีข้อมูลให้แสดง
			$i+=1;
			if($i%2==0){
				echo "<tr class=\"odd\" align=\"center\">";
			}else{
				echo "<tr class=\"even\" align=\"center\">";
			}
			
			echo "
				<td><span onclick=\"javascript:popU('showdetail.php?contractID=$contract_loans_code','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=500')\" style=\"cursor: pointer;\"><u>$contract_loans_code</u></span></td>
				<td align=left>$cusname</td>
				<td>$contract_loans_startdate</td>
				<td align=right>$appv_credit_money</td>
				<td><input type=\"checkbox\" name=\"chk[]\" value=\"$contract_loans_code\"</td>
			</tr>
			";
		}
	} //end while
	?>
</table>
<div style="text-align:center;padding:20px;"><input type="submit" value="ยืนยัน FE"></div>"
</form>
<?php
}
?>
</div>
</body>
</html>