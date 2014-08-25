<?php
include("../../config/config.php");
$CusID = $_GET["CusID"];
$type = $_GET["type"]; //1 เบอร์บ้าน, 2 เบอร์มือถือ

if($type==1){
	$img="<img src=\"images/tel.gif\" width=30 height=27>";
	$txthead="เพิ่มเบอร์บ้าน";
}elseif($type==2){
	$img="<img src=\"images/mobile.gif\" width=25 height=25>";
	$txthead="เพิ่มเบอร์มือถือ";
}
elseif($type==3){
	$img="<img src=\"images/fax.gif\" width=30 height=30>";
	$txthead="เพิ่มเบอร์โทรสาร";
}
//หาชื่อลูกค้า
$qryname=pg_query("select \"full_name\" from \"VSearchCusCorp\" where \"CusID\"='$CusID'");
list($cusname)=pg_fetch_array($qryname);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>:: เบอร์โทรติดต่อ ::</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<body>
<?php
echo "<div align=center><h2>$txthead $img</h2></div>";
echo "<table width=\"80%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" bgcolor=\"#F0FFF0\"  align=\"center\">";
echo "
	<tr><td colspan=4 bgcolor=#FFFFFF><b>$cusname</b></td></tr>
	<tr bgcolor=\"#838B83\" style=\"color:#FFFFFF;\">
		<th>ที่</th>
		<th>เบอร์โทร</th>
		<th>ผู้ที่ทำหน้าที่เปลี่ยน</th>
		<th>วันเวลาที่เพิ่มเบอร์</th>
	</tr>

";
//ดึงเบอร์ทั้งหมดในตาราง "ta_phonenumber"
$qryphone=pg_query("select phonenum,\"fullname\",\"doerStamp\" from \"ta_phonenumber\" a
left join \"Vfuser\" b on a.\"doerID\"=b.\"id_user\"
where \"CusID\"='$CusID' and phonetype='$type' order by \"doerStamp\" DESC");
$numrows=pg_num_rows($qryphone);

if($numrows>0){
	$i=0;
	while($resnum=pg_fetch_array($qryphone)){
		list($phonenum,$user,$doertime)=$resnum;
		
		$i++;
		if($i%2==0){
			echo "<tr align=center bgcolor=#F5FFFA>";
		}else{
			echo "<tr align=center bgcolor=#E0EEE0>";
		}
		echo "
			<td>$i</td>
			<td>$phonenum</td>
			<td align=left>$user</td>
			<td>$doertime</td>
		</tr>";
	}
}else{
	echo "<tr><td colspan=4 align=center bgcolor=\"#E0EEE0\">--ยังไม่มีข้อมูล--</td></tr>";
}
echo "</table>";

echo "
<div style=\"padding-top:15px;\">
	<fieldset><legend><img src=\"images/add.png\">เพิ่ม</legend>
		<div id=\"TextBoxesGroup\">
		<div id=\"TextBoxDiv1\" style=\"margin:0 auto;padding:10px;text-align:center;width:500px;background-color:#FFECEC;border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px\">		
			เบอร์โทร :<input type=\"text\" name=\"phone[]\" id=\"phone1\">
		</div>
		</div>
		<div style=\"margin:0 auto;padding:10px;width:500px;\">
			<div style=\"float:left\"><input type=\"button\" value=\"บันทึกรายการ\" id=\"submitButton\"></div>
			<div style=\"float:right\"><input type=\"button\" value=\"+ เพิ่มรายการ\" id=\"addButton\"></div>
			<div style=\"clear:both\"></div>
		</div>
	</fieldset>
</div>";
echo "<div align=center style=\"padding-top:20px;\"><input type=\"button\" value=\"ปิดหน้าต่าง\" onclick=\"window.close();\"></div>";
?>
<script type="text/javascript">

var counter=1;
$(document).ready(function(){
	$('#addButton').click(function(){
		counter++;	
		console.log(counter);
		var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);
		
		table = '<div style=\"margin:0 auto;padding:10px;text-align:center;width:500px;background-color:#FFECEC;border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px\">'
		+ ' เบอร์โทร :<input type="text" name="phone[]" id="phone'+ counter +'">'
		+ '<input type="button" value="ลบรายการนี้" onclick="removerow('+counter+')" id="del'+ counter +'">'
		+ ' </div>';

		newTextBoxDiv.html(table);

		newTextBoxDiv.appendTo("#TextBoxesGroup");
	});
	
	$("#submitButton").click(function(){
		var phonenum = [];
		var ele=$('input[name="phone[]"]');
		for(i=0; i<ele.length; i++){
			if( $(ele[i]).val() == "" ){
				alert('กรุณากรอกเบอร์ !');
				$(ele[i]).focus();
				return false;
			}
			phonenum[i] ={phone:$(ele[i]).val()};
		}
		$.post("process_phone.php",{
			method:'add',
			CusID:'<?php echo $CusID; ?>', //รหัสพนักงาน
			type:'<?php echo $type; ?>', //ประเภทเบอร์โทร 1 เบอร์บ้าน 2 เบอร์มือถือ 3 เบอร์โทรสาร
			phonenum : JSON.stringify(phonenum) //เบอร์โทรที่กรอก
		},
		function(data){
			if(data == "1"){
                alert("มีบางรายการข้อมูลซ้ำ กรุณาตรวจสอบ");
			}else if(data == "2"){
                alert("บันทึกรายการเรียบร้อย");
                location.reload(true);
				opener.location.reload(true);
            }else{
                alert(data);
				alert("ผิดพลาดไม่สามารถ บันทึกข้อมูลได้");
            }
		});
	});
});
function removerow(count){
	$("#TextBoxDiv" + count).remove();
}
</script>
</body>
</html>