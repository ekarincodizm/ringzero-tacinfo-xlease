<?php
include("../../config/config.php");

$corp_text = $_POST["corp_text"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>แก้ไขข้อมูลลูกค้านิติบุคคล</title>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
$(document).ready(function(){
    $("#corp_text").autocomplete({
        source: "s_corp_name.php",
        minLength:1
    });
});

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

function datacorpold()
{  
	var datacorp = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร datacorp  
		  url: "data_for_corp_old.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
		  //data:"list1="+$(this).val(), // ส่งตัวแปร GET ชื่อ list1 ให้มีค่าเท่ากับ ค่าของ list1  
		  async: false  
	}).responseText;
	$("#datacorp").html(datacorp); // นำค่า datacorp มาแสดงใน div ที่ชื่อ datacorp
	
	document.getElementById("emty").style.visibility = 'visible';
	
	var numberdata = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร numberdata  
		  url: "number_of_data.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
		  //data:"list1="+$(this).val(), // ส่งตัวแปร GET ชื่อ list1 ให้มีค่าเท่ากับ ค่าของ list1  
		  async: false  
	}).responseText;
	$("#numberdata").html(numberdata); // นำค่า numberdata มาแสดงใน font ที่ชื่อ numberdata
}

function emty()
{  
	var datacorp = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร datacorp  
		  url: "emty.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
		  //data:"list1="+$(this).val(), // ส่งตัวแปร GET ชื่อ list1 ให้มีค่าเท่ากับ ค่าของ list1  
		  async: false  
	}).responseText;
	$("#datacorp").html(datacorp); // นำค่า datacorp มาแสดงใน div ที่ชื่อ datacorp
	
	document.getElementById("emty").style.visibility = 'hidden';
	
	var numberdata = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร numberdata  
		  url: "number_of_data.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
		  //data:"list1="+$(this).val(), // ส่งตัวแปร GET ชื่อ list1 ให้มีค่าเท่ากับ ค่าของ list1  
		  async: false  
	}).responseText;
	$("#numberdata").html(numberdata); // นำค่า numberdata มาแสดงใน font ที่ชื่อ numberdata
}
</script>

</head>

<body>
<center><h2>แก้ไขข้อมูลลูกค้านิติบุคคล</h2></center>
<center>
<div style="float:center; width:80%;">
<fieldset>
	<legend><B>ค้นหาข้อมูลลูกค้านิติบุคคล</B></legend>
	<form method="post" name="form1" action="frm_edit_corpdata.php">
		ค้นหาจาก เลขทะเบียน หรือ ชื่อนิติบุคคล : &nbsp;
		<input type="text" name="corp_text" id="corp_text" size="35" value="<?php echo $corp_text; ?>"> &nbsp;
		<input type="submit" id="btnsearch" value="ค้นหา">
	</form>
</fieldset>
</div>
</center>
<div style="margin-top:15px;"></div>
<table align="center" width="80%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#79BCFF">
		<th width="60">รหัสนิติบุคคล</th>
		<th>เลขที่นิติบุคคล</th>
		<th>ชื่อนิติบุคคลภาษาไทย</th>
		<th>ชื่อนิติบุคคลภาษาอังกฤษ</th>
		<th>ชื่อย่อ/เครื่องหมายทางการค้า</th>
		<th width="70">รายละเอียด</th>
		<th width="120">แก้ไขข้อมูลนิติบุคคล</th>
	</tr>
	<?php
	//$query = pg_query("select * from public.\"th_corp\" ");
	$query = pg_query("select \"corpID\", \"corp_regis\", \"corpName_THA\", \"corpName_ENG\", \"trade_name\" from public.\"th_corp\" where \"corp_regis\" like '%$corp_text%' or \"corpName_THA\" like '%$corp_text%' order by \"corpID\" ");
	$numrows = pg_num_rows($query);
	$i=0;
	while($result = pg_fetch_array($query))
	{
		$i++;
		$corpID = $result["corpID"]; // รหัสนิติบุคคล
		$corp_regis = $result["corp_regis"]; // เลขทะเบียนนิติบุคคล
		$corpName_THA = $result["corpName_THA"]; // ชื่อนิติบุคคลภาษาไทย
		$corpName_ENG = $result["corpName_ENG"]; // ชื่อนิติบุคคลภาษาอังกฤษ
		$trade_name = $result["trade_name"]; // ชื่อย่อ/เครื่องหมายทางการค้า
		
		$qry_name = pg_query("select * from public.\"Vfuser\" where \"username\" = '$doerUser' ");
		while($result_name = pg_fetch_array($qry_name))
		{
			$fullname = $result_name["fullname"]; // ชื่อของผู้ที่ทำรายการ
		}
		
		if($i%2==0){
			echo "<tr class=\"odd\">";
		}else{
			echo "<tr class=\"even\">";
		}
		
		echo "<td align=\"center\">$corpID</td>";
		echo "<td align=\"center\">$corp_regis</td>";
		echo "<td>$corpName_THA</td>";
		echo "<td>$corpName_ENG</td>";
		echo "<td>$trade_name</td>";
		echo "<td align=\"center\"><a onclick=\"javascript:popU('frm_viewcorp_detail.php?corp_regis=$corp_regis','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')\" style=\"cursor:pointer;\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a></td>";
		//echo "<td align=\"center\"><a onclick=\"javascript:popU('page_edit_corpdata.php?corpID=$corpID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')\" style=\"cursor:pointer;\"><img src=\"images/edit.png\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a></td>";
		echo "<td align=\"center\"><a onclick=\"javascript:popU('frm_EditCorpAll.php?corpID=$corpID&editcorp=2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=700')\" style=\"cursor:pointer;\"><img src=\"images/edit.png\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a></td>";
		echo "</tr>";
	}
	if($numrows==0){
		echo "<tr bgcolor=#FFFFFF height=50><td colspan=7 align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=7><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
</table>
<div align="center">
	<div style="width:80%;">
        <fieldset style="margin:15px 0px 15px 0px;">
            <legend><b>ข้อมูลการแก้ไขข้อมูลลูกค้านิติบุคคลที่รออนุมัติและไม่อนุมัติ</b></legend>
            <table align="center" width="96%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
                <tr align="center" bgcolor="#79BCFF">
                    <th width="60">รหัสนิติบุคคล</th>
                    <th>เลขที่นิติบุคคล</th>
                    <th>ชื่อนิติบุคคลภาษาไทย</th>
                    <th>ชื่อนิติบุคคลภาษาอังกฤษ</th>
                    <th>ชื่อย่อ/เครื่องหมายทางการค้า</th>
                    <th>สถานะ</th>
                </tr>
				<?php
                    $qr = pg_query("select * from public.\"th_corp_temp\" where (\"Approved\" is null and \"hidden\" = 'false' and \"corpID\" <> '0') or (\"Approved\"='false' and \"hidden\" = 'f' and \"corpID\" <> '0') order by \"doerStamp\" desc");
					$number = 1;
					$row = 0;
					while($rs = pg_fetch_array($qr))
					{
						$corpID = $rs["corpID"]; // รหัสนิติบุคคล
						$corp_regis = $rs["corp_regis"]; // เลขทะเบียนนิติบุคคล
						$corpName_THA = $rs["corpName_THA"]; // ชื่อนิติบุคคลภาษาไทย
						$corpName_ENG = $rs["corpName_ENG"]; // ชื่อนิติบุคคลภาษาอังกฤษ
						$trade_name = $rs["trade_name"]; // ชื่อย่อ/เครื่องหมายทางการค้า
						$Approved = $rs["Approved"];  //สถานะการอนุมัติ
						
						if($Approved == "")
						{
							$appv_status = "<a onclick=\"javascript:popU('frm_viewcorp_detail.php?corp_regis=$corp_regis&view=2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')\" style=\"cursor:pointer;\"><font><u>รออนุมัติ</u></font></a>";
						}
						elseif($Approved == "f")
						{
							$appv_status = "<a onclick=\"javascript:popU('frm_viewcorp_detail.php?corp_regis=$corp_regis&view=3&editable=t','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')\" style=\"cursor:pointer;\"><font color=\"#FF0000\"><u>ไม่อนุมัติ</u></font></a>";
						}
						
						if($row%2==0){
							echo "<tr class=\"odd\">";
						}else{
							echo "<tr class=\"even\">";
						}
						echo "<td height=\"20\">$corpID</td>";
						echo "<td align=\"center\">$corp_regis</td>";
						echo "<td>$corpName_THA</td>";
						echo "<td>$corpName_ENG</td>";
						echo "<td>$trade_name</td>";
						echo "<td align=\"center\">$appv_status</td>";
					}
                ?>
        	</table>
        </fieldset>
        
    </div>
</div>
</body>
</html>