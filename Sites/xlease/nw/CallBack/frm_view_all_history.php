<?php
include("../../config/config.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>ประวัติลูกค้าที่รอการติดต่อกลับ</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

$(document).ready(function(){
	$("#sdate1").datepicker({
			showOn: 'button',
			buttonImage: 'images/calendar.gif',
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
			
	});
		
	$("#sdate2").datepicker({
			showOn: 'button',
			buttonImage: 'images/calendar.gif',
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
			
});
	
	
	$("input[type='radio']").change(function(){

			if($(this).val()=="date"){
			
			document.sfrm.sdate1.disabled=false;
			document.sfrm.sdate2.disabled=false;
				
			}else if($(this).val()=="all"){
				
			document.sfrm.sdate1.disabled=true;
			document.sfrm.sdate2.disabled=true;
			document.sfrm.sdate1.value="";
			document.sfrm.sdate2.value="";	
			}
	});
});	
function autochoise(){

	
	document.sfrm.raall1.checked="checked";

}
function check_num(e)
{
    var key;
    if(window.event){
        key = window.event.keyCode; // IE
if (key > 57)
      window.event.returnValue = false;
    }else{
        key = e.which; // Firefox       
if (key > 57)
      key = e.preventDefault();
  }
} 		
</script>

</head>
<body>
<center>
<br>
<h2>ประวัติลูกค้าที่รอการติดต่อกลับทั้งหมด</h2>
<br>
<?php

$raall = $_POST['raall'];

if($raall == "date"){
$checked1 = null; 
$checked2 = 'checked="checked"';

	$date1 = $_POST['sdate1'];
	$date2 = $_POST['sdate2'];
	$depart = $_POST['depart'];
	$sphone = $_POST['sphone'];

	if($depart == '0'){
		$depart = null;
	}
	if($date1 != null && $date2 != null && $depart != null && $sphone != null ){ //หาตามระหว่างวันที่ ถึง วันที่ และ แผนก และเบอร์
	
		$date1 = $date1." "."00".":"."00".":"."00";
		$date2 = $date2." "."00".":"."00".":"."00";
	
			$strSQL = "SELECT * FROM public.\"VCallback\" where (\"doerStamp\" Between '$date1' and '$date2') AND  \"Want_dep_id\" = '$depart' AND \"CusPhone\" LIKE '%$sphone%'";
			
	}else if($date1 != null && $date2 != null && $depart != null && $sphone == null){ //หาตามระหว่างวันที่ ถึง วันที่ และ แผนก 
	
		$date1 = $date1." "."00".":"."00".":"."00";
		$date2 = $date2." "."00".":"."00".":"."00";
	
			$strSQL = "SELECT * FROM public.\"VCallback\" where (\"doerStamp\" Between '$date1' and '$date2') AND  \"Want_dep_id\" = '$depart'";	
	
	}else if($date1 != null && $date2 != null && $depart == null && $sphone == null){ //หาตามระหว่างวันที่ ถึง วันที่ 
	
		$date1 = $date1." "."00".":"."00".":"."00";
		$date2 = $date2." "."00".":"."00".":"."00";
		
			$strSQL = "SELECT * FROM public.\"VCallback\" where \"doerStamp\" Between '$date1' and '$date2'";	
	}else if($date1 == null && $date2 == null && $depart == null && $sphone != null){ //หาตามเบอร์โทรศัพท์
		
			$strSQL = "SELECT * FROM public.\"VCallback\" where  \"CusPhone\" LIKE '%$sphone%'";	
			
	}else if($date1 == null && $date2 == null && $depart != null && $sphone == null){ //หาตามแผนก

			$strSQL = "SELECT * FROM public.\"VCallback\" where \"Want_dep_id\" = '$depart'";	
	}else if($date1 != null && $date2 == null && $depart == null && $sphone == null){ //หาตามวันที่
			
			$strSQL = "SELECT * FROM public.\"VCallback\" where DATE(\"doerStamp\") = '$date1'";
	}else if($date1 == null && $date2 != null && $depart == null && $sphone == null){ //หาตามวันที่
							
			$strSQL = "SELECT * FROM public.\"VCallback\" where DATE(\"doerStamp\") = '$date2'";			
	}else if($date1 != null && $date2 == null && $depart != null && $sphone == null){ //หาตามวันที่และแผนก
			
			$strSQL = "SELECT * FROM public.\"VCallback\" where DATE(\"doerStamp\") = '$date1' and \"Want_dep_id\" = '$depart'";
	}else if($date1 != null && $date2 == null && $depart == null && $sphone != null){ //หาตามวันที่และเบอร์
			
			$strSQL = "SELECT * FROM public.\"VCallback\" where DATE(\"doerStamp\") = '$date1' and \"CusPhone\" LIKE '%$sphone%'";
	}else if($date1 != null && $date2 == null && $depart != null && $sphone != null){ //หาตามวันที่และเบอและแผนก
			
			$strSQL = "SELECT * FROM public.\"VCallback\" where DATE(\"doerStamp\") = '$date1' and \"CusPhone\" LIKE '%$sphone%' and \"Want_dep_id\" = '$depart'";
	}else if($date1 != null && $date2 != null && $depart == null && $sphone != null){ //หาตามระหว่างวันที่ และเบอร์
			
			$strSQL = "SELECT * FROM public.\"VCallback\" where (\"doerStamp\" Between '$date1' and '$date2') and \"CusPhone\" LIKE '%$sphone%'";
	}else if($date1 != null && $date2 != null && $depart != null && $sphone == null){ //หาตามระหว่างวันที่ และแผนก
			
			$strSQL = "SELECT * FROM public.\"VCallback\" where (\"doerStamp\" Between '$date1' and '$date2') and \"Want_dep_id\" = '$depart'";
	}else{
	
		$strSQL = "SELECT * FROM public.\"VCallback\" ";
	}
	
}else{
$checked1 = 'checked=\"checked\"';
$checked2 = null;  

//original
$strSQL = "SELECT * FROM public.\"VCallback\" ";

}





$objQuery = pg_query($strSQL) or die ("Error Query [".$strSQL."]");
$Num_Rows = pg_num_rows($objQuery);

$Per_Page = 30;   // Per Page

$Page = $_GET["Page"];
if(!$_GET["Page"])
{
	$Page=1;
}

$Prev_Page = $Page-1;
$Next_Page = $Page+1;

$Page_Start = (($Per_Page*$Page)-$Per_Page);
if($Num_Rows<=$Per_Page)
{
	$Num_Pages =1;
}
else if(($Num_Rows % $Per_Page)==0)
{
	$Num_Pages =($Num_Rows/$Per_Page);
}
else
{
	$Num_Pages = ($Num_Rows/$Per_Page)+1;
	$Num_Pages = (int)$Num_Pages;
}

$strSQL .=" order  by \"doerStamp\" DESC LIMIT $Per_Page OFFSET $Page_Start";
$objQuery  = pg_query($strSQL);
?>
<?php
$d = $Page_Start;
?>
<form name="sfrm" action="frm_view_all_history.php" method="POST">
<table align="center" width="900" frame="box" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr>
		<td align="center">
			<input type="radio" name="raall" id="raall" value="all" <?php echo $checked1; ?> >
		</td>
		<td align="center">
			ทั้งหมด
		</td>
		<td align="center">
			<input type="radio" name="raall" id="raall1" value="date" <?php echo $checked2; ?> >
		</td>
		<td align="center">
			กรอง
		</td>
		<td align="center">
			วันที่
		</td>
		<td align="center">
			<input type="text" name="sdate1" id="sdate1" size="15" onchange="javascript : autochoise()">
		</td>
		<td align="center">
			ถึง
		</td>
		<td align="center">
			<input type="text" name="sdate2" id="sdate2" size="15" onchange="javascript : autochoise()">
		</td>
		<td align="center">
			แผนก
		</td>
		<td align="center" >
			<select name="depart" style="text-align:center;" onchange="javascript : autochoise()"><option value="0">-- แผนก --</option>
			<?php 
				$depsql = "SELECT * FROM \"department\"";
				$depque = pg_query($depsql);
				while($depre = pg_fetch_array($depque)){ ?>
			<option value="<?php echo $depre['dep_id']; ?>"><?php echo $depre['dep_name']; ?></option>
			
			<?php } ?>					
			</select>
		</td>
		<td align="center">
			เบอร์ติดต่อ
		</td>
		<td align="center">
			<input type="text" name="sphone" id="sphone" onkeypress="javascript : check_num(event)" onchange="javascript : autochoise()">
		</td>
		
		<td align="center">
			<input type="submit" value="ค้นหา">
		</td>
	</tr>	
</table>
</form>
<table align="center" width="900" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
					<tr align="center" bgcolor="#79BCFF">
						<th height="30">รายการที่</th>
						<th>เรื่อง</th>
						<th>ชื่อลูกค้า</th>
						<th>เบอร์ติดต่อกลับ</th>
						<th>วันเวลาที่บันทึก</th>
						<th>บุคคลที่ต้องการจะติดต่อ</th>
						<th>วันเวลาที่สะดวก<br>ให้ติดต่อกลับ</th>
						<th>ประเภทการติดต่อ</th>
						<th>รายละเอียด</th>
						<th>สถานะ</th>
					</tr>
					<?php
					while($result = pg_fetch_array($objQuery))
					{
						$d++;
						$CallBackID=$result["CallBackID"]; // รหัสการติดต่อ
						$CusName=$result["CusName"]; // ชื่อลูกค้า
						$CusPhone=$result["CusPhone"]; // เบอร์ติดต่อกลับไปหาลูกค้า
						$CallTitle=$result["CallTitle"]; // ชื่อเรื่องที่จะติดต่อ
						$doerStamp=$result["doerStamp"]; // เวลาที่ลูกค้าติดต่อเข้ามา
						$Want_dep_id=$result["Want_dep_id"]; // แผนกที่ต้องการจะติดต่อ
						$Want_id_user=$result["Want_name_user"]; // พนักงานที่ต้องการจะติดต่อ
						$TimeCallBack=$result["TimeCallBack"]; // วันเวลาที่สะดวกให้ติดต่อกลับ
						$CallBackStatus=$result["CallBackStatus"]; // สถานะของการติดต่อ
						$TextCallBackStatus=$result["TextCallBackStatus"]; // สถานะของการติดต่อ
						$callTypeName=$result["callTypeName"]; //ชื่อประเภทการติดต่อ
						if($callTypeName==""){
							$callTypeName="ไม่ระบุ";
						}
						if($CallBackStatus == 1 OR $CallBackStatus == 3)
						{
							$txtstatus = "<font color=\"FF0000\">$TextCallBackStatus</font>";
						}
						elseif($CallBackStatus == 2)
						{
							$txtstatus = "<a onclick=\"javascript:popU('detail_final_callback.php?CallBackID=$CallBackID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=500')\"><font color=\"5555CC\" style=\"cursor:pointer;\"><u>$TextCallBackStatus</font></u></a>";
						}
						
						
						
						if($CallBackStatus=="3"){ //แสดงว่ายังมีการสนทนาค้างอยู่ ดังนั้นให้ไปเอาเวลาติดต่อกลับที่ตารางอื่น
							$qrydetail=pg_query("select \"TimeCallBack\" from \"VCallback_detail\" where \"CallBackID\"='$CallBackID' order by \"callback_Stamp\" DESC limit 1");
							list($TimeCallBack)=pg_fetch_array($qrydetail);
							if($TimeCallBack == "") // ถ้าวันเวลาที่สะดวกให้ติดต่อกลับเป็นค่าว่าง
							{
								$TimeCallBack = "สะดวกให้ติดต่อกลับทันที";
							}
						}else{
							if($TimeCallBack == "") // ถ้าวันเวลาที่สะดวกให้ติดต่อกลับเป็นค่าว่าง
							{
								$TimeCallBack = "สะดวกให้ติดต่อกลับทันที";
							}
						}
					
						
						if($Want_dep_id != "") // ถ้ารหัสแผนกไม่ว่าง
						{
							$query_dep = pg_query("select * from public.\"department\" where \"dep_id\" = '$Want_dep_id' ");
							while($result_dep = pg_fetch_array($query_dep))
							{
								$went = "แผนก : ".$result_dep["dep_name"];
							}
						}
						elseif($Want_id_user != "") // ถ้ารหัสพนักงานไม่ว่าง
						{
							$went = $Want_id_user;
						}
						
						if($i%2==0){
							echo "<tr class=\"odd\">";
						}else{
							echo "<tr class=\"even\">";
						}
						
						echo "<td align=center height=25>$d</td>";
						echo "<td>$CallTitle</td>";
						echo "<td>$CusName</td>";
						echo "<td align=center>$CusPhone</td>";
						echo "<td align=center>$doerStamp</td>";
						echo "<td>$went</td>";
						echo "<td align=center>$TimeCallBack</td>";
						echo "<td align=center>$callTypeName</td>";
						echo "<td align=center><a onclick=\"javascript:popU('detail_callback.php?CallBackID=$CallBackID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=400')\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a></td>";
						echo "<td align=center>$txtstatus</td>";
						echo "</tr>";	
						$i++;
						$went="";
					} //end while

					if($Num_Rows==0){
						echo "<tr bgcolor=#FFFFFF height=50><td colspan=9 align=center><b>ไม่พบรายการ</b></td><tr>";
					}else{
						echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=9><b>ทั้งหมด $Num_Rows รายการ  จำนวน $Num_Pages หน้า  (โดยเรียงตามวันเวลาที่บันทึกล่าสุด)</b></td><tr>";
					}
					?>
				</table>
 
<br>
Total <?php echo $Num_Rows;?> Record : <?php echo $Num_Pages;?> Page :
<?php
if($Prev_Page)
{
	echo " <a href='frm_view_all_history.php?Page=$Prev_Page'><< Back</a> ";
}

for($i=1; $i<=$Num_Pages; $i++)
{
	if($i != $Page)
	{
		echo "[ <a href='frm_view_all_history.php?Page=$i'>$i</a> ]";
	}
	else
	{
		echo "<b> $i </b>";
	}
}
if($Page!=$Num_Pages)
{
	echo " <a href ='frm_view_all_history.php?Page=$Next_Page'>Next>></a> ";
}
//pg_close($db_connect);
?>
<br><br><br><br>
</center>
</body>
</html>