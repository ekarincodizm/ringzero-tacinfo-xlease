<?php
include("../../config/config.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
    <title>(THCAP) อนุมัติประเภทต้นทุนสัญญา</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
</head>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
function checkhitorityappv(no){
	var checkhitorityappv = $.ajax({    
	  url: "frm_checkhitorityappv.php?idno="+no, 
	  async: false  
	}).responseText;
	popU('../thcap_cost_type_appv/frm_appv.php?autoid='+no+'&last_autoid='+checkhitorityappv,
	'','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1200,height=900');
	
}
</script>

<body>
<center><h2>(THCAP) อนุมัติประเภทต้นทุนสัญญา</h2></center>
<br>
<fieldset>
	<legend><font color="black"><b>ประเภทต้นทุนสัญญาอยู่ระหว่างขออนุมัติ </font></b></font></legend>
<br>
<table  align="center" width="90%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#79BCFF">
		<th>รายการที่</th>
		<th>ชื่อประเภทต้นทุน</th>
		<th>ประเภทต้นทุน</th>
		<th>ผู้ทำรายการ</th>
        <th>วันที่ทำรายการ</th>
		<th>ประเภทสินเชื่อ</th>		
		<th>หมายเหตุ</th>
		<th>สถานะ</th>		
		<th>รายละเอียด</th>
		
	</tr>
	<?php
	$query = pg_query("select * from \"thcap_cost_type_temp\" where \"approved\"='9' order by \"doerstamp\" asc");
	$i=0;
	$numrows = pg_num_rows($query);
	while($result = pg_fetch_array($query))
	{
		$i++;
		$autoid=$result["autoid"];
		$costtype=$result["costtype"];
		$costname= $result["costname"];		
		$doerid = $result["doerid"];
		$doerstamp= $result["doerstamp"];
		$typeloansuse = $result["typeloansuse"];
		$note = $result["note"];
		$resultappv= $result["approved"];		
		$countedit=$result["edit_last_autoid"];
		$status_costtype=$result["status_costtype"];//ประเภทต้นทุน 
		if($status_costtype=='0'){$status_costtype='ไม่ระบุ';}
		elseif($status_costtype=='1'){$status_costtype='ต้นทุนเริ่มแรก';}
		elseif($status_costtype=='2'){$status_costtype='ต้นทุนดำเนินการ';}
		$typeloan = substr($typeloansuse,1,strlen($typeloansuse)-2); 
		if($typeloan==""){$typeloan="ทุกประเภทสินเชื่อ";}
		if($countedit=='0'){ 
			$edit="เพิ่มใหม่";	 }
		else{
			$edit="แก้ไข";}
		//$typeloan = //str_replace(","," ",$rest);
		//ชื่อคนทำรายการ
		$query_fullname = pg_query("select \"fullname\"  from \"Vfuser\" where \"id_user\" = '$doerid' ");
		$nameuser = pg_fetch_array($query_fullname);
		$fullnamedoerid=$nameuser["fullname"];
		if($i%2==0){
					echo "<tr bgcolor=\"#B2DFEE\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#B2DFEE';\">";
				}else{
					echo "<tr bgcolor=\"#BFEFFF\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#BFEFFF';\">";
					}
		echo "<td align=\"center\">$i</td>";
		echo "<td align=\"center\">$costname</td>";	
		echo "<td align=\"center\">$status_costtype</td>";			
		echo "<td align=\"center\">$fullnamedoerid</td>";
		echo "<td align=\"center\">$doerstamp</td>";
		echo "<td align=\"center\">$typeloan</td>";
		echo "<td align=\"center\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" onclick=\"javascript:popU('../thcap_cost_type/frm_note.php?autoid=$autoid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=400');\" style=\"cursor:pointer;\"></td>";
		echo "<td align=\"center\">$edit</td>";
		echo "<td align=\"center\"><a onclick=\"javascript:checkhitorityappv('$autoid');\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>ตรวจสอบ</u></font></a></td>";
		/*echo "<td align=\"center\"><a onclick=\"javascript:popU('../thcap_cost_type_appv/frm_appv.php?autoid=$autoid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1200,height=900');\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>ตรวจสอบ</u></font></a></td>";
	*/}
	if($numrows==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=9 align=center><b>ไม่พบรายการ</b></td><tr>";
		}else{
			echo "<tr bgcolor=\"#79BCFF\" height=25><td colspan=9><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
	
	
</table>
</fieldset>
<?php //ประวัติการอนุมัติ 30 รายการล่าสุด
	$menu="appv";
	include('../thcap_cost_type/frm_approve_limit.php');?>
</div>
</body>
</html>