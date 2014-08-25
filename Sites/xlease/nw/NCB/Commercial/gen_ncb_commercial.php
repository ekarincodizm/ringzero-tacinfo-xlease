<?php
session_start();
include("../../../config/config.php");
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

set_time_limit(150);


function search_day($month , $year) // function ในการจำนวนวันในเดือนนั้นๆ
{
	$select_day = pg_query("select \"gen_numdaysinmonth\"('$month' , '$year')");
	$this_day = pg_fetch_array($select_day);
	list($ans_day) = $this_day; // นำวันที่สิ้นเดือนของเดือนนั้นๆมาเก็บไว้ในตัวแปร $ans_day
	
	return $ans_day;
}

$save = $_POST["save"];

if($save == "yes")
{
	$click = $_POST["clickSave"];
}
else
{
	$click = $_POST["click"];
}

if($click == "yes")
{
	if($save == "yes")
	{
		$month = $_POST["monthSave"];
		$year = $_POST["yearSave"];
	}
	else
	{
		$month = $_POST["month"];
		$year = $_POST["year"];
	}
	
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) สร้างไฟล์ NCB Commercial', '$add_date')");
	//ACTIONLOG---
	
	$loopT = 0;
	$myWhere = "and (";
	$qry_typeContract = pg_query("select \"conType\" as \"typecontract\" from \"thcap_contract_type\" order by \"conType\" ASC");
	while($loop_typeContract = pg_fetch_array($qry_typeContract))
	{
		$loopT++;
		$typecontract = $loop_typeContract["typecontract"];
		
		if($save == "yes")
		{
			$typecontractSave = $typecontract."Save";
			$looptypecontract[$loopT] = $_POST["$typecontractSave"];
		}
		else
		{
			$looptypecontract[$loopT] = $_POST["$typecontract"];
		}
		
		if($looptypecontract[$loopT] == "on")
		{
			if($myWhere == "and (")
			{
				$myWhere .= "\"thcap_get_contractType\"(\"contractID\") = '$typecontract' ";
			}
			else
			{
				$myWhere .= "or \"thcap_get_contractType\"(\"contractID\") = '$typecontract' ";
			}
		}
	}
	$myWhere .= ")";
	
	if($myWhere == "and ()")
	{
		$myWhere = "";
	}
}
else
{
	if(date(m) == 01)
	{
		$month = 12;
		$year = date(Y)-1;
	}
	else
	{
		$month = date(m)-1;
		$year = date(Y);
	}
}

include("SearchCorporation.php");
include("CT.php");
include("PF.php");
include("ID.php");
include("AD.php");
include("CR.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) สร้างไฟล์ NCB Commercial</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8; scrollbars=no" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="../act.css"></link>
    
    <link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<style type="text/css">
	.odd{
		background-color:#FFFFCF;
		font-size:12px
	}
	.even{
		background-color:#D5EFFD;
		font-size:12px
	}
	.sum{
		background-color:#FFC0C0;
		font-size:12px
	}
	</style>

</head>
<script type="text/javascript">
$(document).ready(function(){   
	//เมื่อกด ข้อความ  "แสดงเฉพาะ :" 
	$("#selectcontype").click(function(){
	
		var ele_contype = $("input[id=contype]");
		if($("#clear").val()== 'Y'){
			$("#clear").val('N');
		}
		else{
			$("#clear").val('Y');
		}
		if($("#clear").val() == 'Y')
		{  	var num=0;
			//ติ้ก ถูกทั้งหมด
			for (i=0; i< ele_contype.length; i++)
			{
				$(ele_contype[i]).attr ( "checked" ,"checked" );
			}
		}
		else
		{ 	//เอาติ้ก ถูก ออก ทั้งหมด
			for (i=0; i< ele_contype.length; i++)
			{
				$(ele_contype[i]).removeAttr('checked');
			}
		}
	
	});

});


</script>
<body>

<center>
	<form name="frm1" method="post" action="gen_ncb_commercial.php">
		<select name="month">
			<option value="01" <?php if($month == "01"){echo "selected";} ?>>มกราคม</option>
			<option value="02" <?php if($month == "02"){echo "selected";} ?>>กุมภาพันธ์</option>
			<option value="03" <?php if($month == "03"){echo "selected";} ?>>มีนาคม</option>
			<option value="04" <?php if($month == "04"){echo "selected";} ?>>เมษายน</option>
			<option value="05" <?php if($month == "05"){echo "selected";} ?>>พฤษภาคม</option>
			<option value="06" <?php if($month == "06"){echo "selected";} ?>>มิถุนายน</option>
			<option value="07" <?php if($month == "07"){echo "selected";} ?>>กรกฎาคม</option>
			<option value="08" <?php if($month == "08"){echo "selected";} ?>>สิงหาคม</option>
			<option value="09" <?php if($month == "09"){echo "selected";} ?>>กันยายน</option>
			<option value="10" <?php if($month == "10"){echo "selected";} ?>>ตุลาคม</option>
			<option value="11" <?php if($month == "11"){echo "selected";} ?>>พฤศจิกายน</option>
			<option value="12" <?php if($month == "12"){echo "selected";} ?>>ธันวาคม</option>
		</select>
		<select name="year">
			<option value="<?php echo date(Y); ?>"><?php echo date(Y); ?></option>
			<option value="<?php echo date(Y)-1; ?>" <?php if($year == date(Y)-1){echo "selected";} ?>><?php echo date(Y)-1; ?></option>
		</select>
		<input type="hidden" name="click" value="yes">
		<input type="submit" value="GEN NCB Commercial">
		<br>
		<input type="hidden" id="clear" value="Y"/>
		<span id="selectcontype" style="cursor:pointer;"><u><font color="#0000CC"><B>ประเภทสัญญา :</B></font></u></span>	
		 
		<?php
			$cloop = 0;
			$qry_typeContract = pg_query("select \"conType\" as \"typecontract\" from \"thcap_contract_type\" order by \"conType\" ASC");
			while($loop_typeContract = pg_fetch_array($qry_typeContract))
			{
				$typecontract = $loop_typeContract["typecontract"];
				
				$cloop++;
				if($click == "yes")
				{
					if($looptypecontract[$cloop] == "on")
					{
						echo "<input type=\"checkbox\" name=\"$typecontract\" id=\"contype\" checked> $typecontract &nbsp;&nbsp;&nbsp;";
					}
					else
					{
						echo "<input type=\"checkbox\" name=\"$typecontract\" id=\"contype\" > $typecontract &nbsp;&nbsp;&nbsp;";
					}
				}
				else
				{
					echo "<input type=\"checkbox\" name=\"$typecontract\" id=\"contype\" checked> $typecontract &nbsp;&nbsp;&nbsp;";
				}
			}
		?>
	</form>
</center>

<?php
if($save == "yes")
{
	$day = search_day($month , $year);
	$CorporationArray2D = CorporationArray2D($day , $month , $year , $myWhere);
	
	$CT_head_save = CT_head($day , $month , $year , $myWhere);
	$CT_text_save = CT_text($CorporationArray2D);
	$PF_head_save = PF_head($day , $month , $year , $myWhere);
	$PF_text_save = PF_text($CorporationArray2D);
	$ID_head_save = ID_head($day , $month , $year , $myWhere);
	$ID_text_save = ID_text($CorporationArray2D);
	$AD_head_save = AD_head($day , $month , $year , $myWhere);
	$AD_text_save = AD_text($CorporationArray2D);
	$CR_head_save = CR_head($day , $month , $year , $myWhere);
	$CR_text_save = CR_text($day , $month , $year , $myWhere);
	
	$CT_text_save = str_replace("<br>","\r\n",$CT_text_save); // ใช้สำหรับ save file
	$CT_text_save = str_replace("<??>","",$CT_text_save); // ซ้อน tag เปิด/ปิด ที่ใช้ในการ gen file
	$PF_text_save = str_replace("<br>","\r\n",$PF_text_save); // ใช้สำหรับ save file
	$ID_text_save = str_replace("<br>","\r\n",$ID_text_save); // ใช้สำหรับ save file
	$AD_text_save = str_replace("<br>","\r\n",$AD_text_save); // ใช้สำหรับ save file
	$CR_text_save = str_replace("<br>","\r\n",$CR_text_save); // ใช้สำหรับ save file
	
	//$CT_text_save = iconv("UTF-8","windows-874",$CT_text_save);
	$PF_text_save = iconv("UTF-8","UTF-8",$PF_text_save);
	$ID_text_save = iconv("UTF-8","UTF-8",$ID_text_save);
	$AD_text_save = iconv("UTF-8","UTF-8",$AD_text_save);
	$CR_text_save = iconv("UTF-8","UTF-8",$CR_text_save);
	
	$strFileName_CT = "file_ncb_commercial/".$CT_head_save;
	$objFopen_CT = fopen($strFileName_CT, 'w');
	fwrite($objFopen_CT, $CT_text_save);
	if($objFopen_CT)
	{
		echo "<center><h3><font color=#0000FF>บันทึกไฟล์ $CT_head_save สำเร็จ</font></h3></center>";
	}
		else
	{
		echo "<center><h3><font color=#FF0000>บันทึก $CT_head_save ผิดพลาด!!</font></h3></center>";
	}
	fclose($objFopen_CT);
	
	$strFileName_PF = "file_ncb_commercial/".$PF_head_save;
	$objFopen_PF = fopen($strFileName_PF, 'w');
	fwrite($objFopen_PF, $PF_text_save);
	if($objFopen_PF)
	{
		echo "<center><h3><font color=#0000FF>บันทึกไฟล์ $PF_head_save สำเร็จ</font></h3></center>";
	}
		else
	{
		echo "<center><h3><font color=#FF0000>บันทึก $PF_head_save ผิดพลาด!!</font></h3></center>";
	}
	fclose($objFopen_PF);
	
	$strFileName_ID = "file_ncb_commercial/".$ID_head_save;
	$objFopen_ID = fopen($strFileName_ID, 'w');
	fwrite($objFopen_ID, $ID_text_save);
	if($objFopen_ID)
	{
		echo "<center><h3><font color=#0000FF>บันทึกไฟล์ $ID_head_save สำเร็จ</font></h3></center>";
	}
		else
	{
		echo "<center><h3><font color=#FF0000>บันทึก $ID_head_save ผิดพลาด!!</font></h3></center>";
	}
	fclose($objFopen_ID);
	
	$strFileName_AD = "file_ncb_commercial/".$AD_head_save;
	$objFopen_AD = fopen($strFileName_AD, 'w');
	fwrite($objFopen_AD, $AD_text_save);
	if($objFopen_AD)
	{
		echo "<center><h3><font color=#0000FF>บันทึกไฟล์ $AD_head_save สำเร็จ</font></h3></center>";
	}
		else
	{
		echo "<center><h3><font color=#FF0000>บันทึก $AD_head_save ผิดพลาด!!</font></h3></center>";
	}
	fclose($objFopen_AD);
	
	$strFileName_CR = "file_ncb_commercial/".$CR_head_save;
	$objFopen_CR = fopen($strFileName_CR, 'w');
	fwrite($objFopen_CR, $CR_text_save);
	if($objFopen_CR)
	{
		echo "<center><h3><font color=#0000FF>บันทึกไฟล์ $CR_head_save สำเร็จ</font></h3></center>";
	}
		else
	{
		echo "<center><h3><font color=#FF0000>บันทึก $CR_head_save ผิดพลาด!!</font></h3></center>";
	}
	fclose($objFopen_CR);
}

if($click == "yes")
{
	$day = search_day($month , $year);
	$CorporationArray2D = CorporationArray2D($day , $month , $year , $myWhere);
	
	$CT_head = CT_head($day , $month , $year , $myWhere);
	$CT_text = CT_text($day , $month , $year , $myWhere);
	$PF_head = PF_head($day , $month , $year , $myWhere);
	$PF_text = PF_text($CorporationArray2D);
	$ID_head = ID_head($day , $month , $year , $myWhere);
	$ID_text = ID_text($CorporationArray2D);
	$AD_head = AD_head($day , $month , $year , $myWhere);
	$AD_text = AD_text($CorporationArray2D);
	$CR_head = CR_head($day , $month , $year , $myWhere);
	$CR_text = CR_text($day , $month , $year , $myWhere);
?>
	<fieldset><legend><font color="#FF0000"></font><?php echo $CT_head; ?></legend>
	<?php
		echo $CT_text;
	?>
	</fieldset>
	<br>
	<fieldset><legend><font color="#FF0000"></font><?php echo $PF_head; ?></legend>
	<?php
		echo $PF_text;
	?>
	</fieldset>
	<br>
	<fieldset><legend><font color="#FF0000"></font><?php echo $ID_head; ?></legend>
	<?php
		echo $ID_text;
	?>
	</fieldset>
	<br>
	<fieldset><legend><font color="#FF0000"></font><?php echo $AD_head; ?></legend>
	<?php
		echo $AD_text;
	?>
	</fieldset>
	<br>
	<fieldset><legend><font color="#FF0000"></font><?php echo $CR_head; ?></legend>
	<?php
		echo $CR_text;
	?>
	</fieldset>
	<br>
	<form name="frm2" method="post" action="gen_ncb_commercial.php">		
		<input type="hidden" name="monthSave" value="<?php echo $month; ?>">
		<input type="hidden" name="yearSave" value="<?php echo $year; ?>">
		<input type="hidden" name="clickSave" value="yes">
		<input type="hidden" name="save" value="yes">
		
		<?php // ประเภทสินเชื่อ
			$cloop = 0;
			$qry_typeContract = pg_query("select  \"conType\" as \"typecontract\" from \"thcap_contract_type\" order by \"conType\" ASC");
			while($loop_typeContract = pg_fetch_array($qry_typeContract))
			{
				$typecontract = $loop_typeContract["typecontract"];
				$cloop++;
				
				if($looptypecontract[$cloop] == "on")
				{
					$tempName = $typecontract."Save";
					echo "<input type=\"checkbox\" name=\"$tempName\" checked hidden>";
				}
				else
				{
					echo "<input type=\"checkbox\" name=\"$tempName\" hidden>";
				}
			}
		?>
		
		<input type="submit" value="SAVE">
	</form>
<?php
}
?>


</body>
</html>