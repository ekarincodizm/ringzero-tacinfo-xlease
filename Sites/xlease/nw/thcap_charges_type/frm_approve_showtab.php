<?php
session_start();  
include("../../config/config.php"); 
$get_id_user = $_SESSION["av_iduser"];
$id = pg_escape_string($_GET["id"]);
$view = pg_escape_string($_GET["view"]);
if($id=="")
{
	$id = pg_escape_string($_POST["id"]);
}
$page_title = "(THCAP) จัดการประเภทค่าใช้จ่าย";

// หาผู้อนุมัติคนที่หนึ่ง
$qry_name = pg_query("SELECT \"appvID1\" FROM account.\"thcap_typePay_temp\" where \"tpAutoID\" = '$id' ");
$appvID1 = pg_result($qry_name,0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
	<title><?php echo $page_title; ?></title>
    <LINK href="../images/styles.css" type=text/css rel=stylesheet>
    <link type="text/css" href="../images/jqueryui/css/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../images/jqueryui/js/jquery-1.6.2.min.js"></script>
    <script type="text/javascript" src="../images/jqueryui/js/jquery-ui-1.8.16.custom.min.js"></script>
	
	<script>
		function appv(no)
		{
			var str_appv;
			if(no=='1'){
				str_appv='อนุมัติ';
			}
			else if(no=='0'){
				str_appv='ไม่อนุมัติ';
			}
			
			if(confirm('คุณต้องการ'+str_appv+'รายการนี้หรือไม่'))
			{
				$.post('process_approve.php',{
					id:'<?php echo $id; ?>',
					stapp:no
				},function(data){
					if(data == 1){
						alert('บันทึกรายการเรียบร้อย');
					}else{
						alert('ผิดผลาด ไม่สามารถบันทึกได้! '+data);
					}
					window.opener.location.reload();
					window.close();	
				});
			}
		}
	</script>

</head>

<body>

<div class="roundedcornr_box" style="width:900px">
	<div class="roundedcornr_top"><div></div></div>
	<div class="roundedcornr_content">
		<h1>(THCAP) จัดการประเภทค่าใช้จ่าย </h1>
		<div id="maintabs">
			<ul>
				 <li><a href="frm_detail_approve.php?id=<?php echo "$id";?>">รายละเอียดประเภทค่าใช้จ่าย</a> </li>
				 <li><a href="frm_detail_relationship_approve.php?id=<?php echo "$id";?>">รายละเอียดความสัมพันธ์ทางบัญชี</a> </li>	 
			</ul>
		</div>
		<br>
		<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
			<tr align="center">
				<td><input type="button" value="อนุมัติ" onclick="appv('1');" <?php if($view == "v"){echo "hidden";} if($appvID1 == $get_id_user){echo " disabled title=\"คุณเคยอนุมัติรายการไปแล้ว\" ";} ?>></td>
				<td><input type="button" value="ไม่อนุมัติ" onClick="appv('0');" <?php if($view == "v"){echo "hidden";} ?>></td>
				<td><input type="button" name="back" value="ปิด" onClick="window.close();"></td>
			</tr>
		</table>
	</div>
	<div class="roundedcornr_bottom"><div></div></div>
</div>

<script>
$(function(){
    $( "#maintabs" ).tabs({
        ajaxOptions: {
            error: function( xhr, status, index, anchor ) {
                $( anchor.hash ).html("");
            }
        }
    });
});
</script>

</body>
</html>