<?php
session_start();
include("../../config/config.php");
$add_date = nowDateTime(); // วันเวลาปัจจุบัน ที่ได้จาก postgres
$user_id = $_SESSION["av_iduser"];

$click = $_POST["click"];
$save = $_POST["save"];
$savefile = $_POST["savefile"];

set_time_limit(60);

if($click == "yes")
{
	$month = $_POST["month"];
	$year = $_POST["year"];
	
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) สร้างไฟล์ NCB Consumer', '$add_date')");
	//ACTIONLOG---
	
	$loopT = 0;
	$myWhere = "and (";
	$qry_typeContract = pg_query("select \"conType\" as \"typecontract\" from \"thcap_contract_type\" order by \"conType\" ASC");
	while($loop_typeContract = pg_fetch_array($qry_typeContract))
	{
		$loopT++;
		$typecontract = $loop_typeContract["typecontract"];
		
		$looptypecontract[$loopT] = $_POST["$typecontract"];
		
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

function monthname($month) // function สำหรับหาชื่อเดือน
{
	if($month == "01"){$ans_monthname = "JAN";}
	elseif($month == "02"){$ans_monthname = "FEB";}
	elseif($month == "03"){$ans_monthname = "MAR";}
	elseif($month == "04"){$ans_monthname = "APR";}
	elseif($month == "05"){$ans_monthname = "MAY";}
	elseif($month == "06"){$ans_monthname = "JUN";}
	elseif($month == "07"){$ans_monthname = "JUL";}
	elseif($month == "08"){$ans_monthname = "AUG";}
	elseif($month == "09"){$ans_monthname = "SEP";}
	elseif($month == "10"){$ans_monthname = "OCT";}
	elseif($month == "11"){$ans_monthname = "NOV";}
	elseif($month == "12"){$ans_monthname = "DEC";}
	
	return $ans_monthname;
}

function search_day($month , $year) // function ในการจำนวนวันในเดือนนั้นๆ
{
	$select_day = pg_query("select \"gen_numdaysinmonth\"('$month' , '$year')");
	$this_day = pg_fetch_array($select_day);
	list($ans_day) = $this_day; // นำวันที่สิ้นเดือนของเดือนนั้นๆมาเก็บไว้ในตัวแปร $ans_day
	
	return $ans_day;
}

function TUDF($month , $year , $row) // function สำหรับการหา หัวของ NCB (TUDF Header Segment)
{
	$day = search_day($month , $year);
	
	$monthname = monthname($month); // ชื่อย่อของเดือนนั้นๆเป็นภาษาอังกฤษ
	
	$ans_TUDF = "TUDF13CC11030000THCAP             ".$year.$month.$day."962HN65300"."REPLACEMENT OF ".$monthname." ".$year." UPDATE FILE"; // header หลักๆก่อนเติมช่องว่างให้ครบ 92 ตัว
	
	$size_before_head = strlen($ans_TUDF);
	
	// เติมช่องว่างจนข้อมูลมี 92 ตัว
	for($i=$size_before_head;$i<92;$i++)
	{
		$ans_TUDF = $ans_TUDF." ";
	}
	// จบการเติมช่องว่าง
	
	$Tracing_Number = $row; // จำนวนข้อมูล
	// เติม 0 ด้านให้เพื่อให้ครบ 8 ตัว
	if(strlen($Tracing_Number) != 8)
	{
		do{
			$Tracing_Number = "0".$Tracing_Number;
		}while(strlen($Tracing_Number) < 8);
	}
	
	$ans_TUDF = $ans_TUDF.$Tracing_Number;
	
	return $ans_TUDF;
}

function utf8_strlen($s) // function สำหรับหาจำนวนตัวอักษร ใช้ได้ดีกับภาษาไทยด้วย
{
	$c = strlen($s); $l = 0;
    for($i = 0; $i < $c; ++$i) 
	{
		if((ord($s[$i]) & 0xC0) != 0x80) ++$l;
	}
	
	return $l;
}

function substr_utf8( $str, $start_p , $len_p) // function สำหรับตัดข้อความ ใช้ได้ดีกับภาษาไทยด้วย
{
	return preg_replace( '#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$start_p.'}'.
						'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len_p.'}).*#s',
						'$1' , $str );
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) สร้างไฟล์ NCB Consumer</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8; scrollbars=no" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
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
	
<SCRIPT language="JavaScript">
function WriteFile()
{
   var fso  = new ActiveXObject("Scripting.FileSystemObject");
   var fh = fso.CreateTextFile("C:\Program Files\BitNami WAPPStack\apache2\htdocs\xlease-nw\xlease\nw\NCB\\Test.txt", true);
   fh.WriteLine("Some text goes here...");
   fh.Close();
}
</SCRIPT>
<script type="text/javascript">
$(document).ready(function(){   
	//เมื่อกด ข้อความ  "แสดงเฉพาะ :" 
	$("#selectcontype").click(function(){
	
		var ele_contype = $("input[id=con_type]");
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
</head>

<body>

<center>
	<h2>(THCAP) สร้างไฟล์ NCB Consumer</h2>
	<form name="frm1" method="post" action="gen_ncb.php">
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
		<input type="submit" value="GEN NCB">
		<br>
		<input type="hidden" id="clear" value="Y"/>
		<span id="selectcontype" style="cursor:pointer;"><u><font color="#0000CC"><B>ประเภทสัญญา :</B></font></u></span>		
		<?php
			$cloop = 0;
			$qry_typeContract = pg_query("select  \"conType\" as \"typecontract\" from \"thcap_contract_type\" order by \"conType\" ASC");
			while($loop_typeContract = pg_fetch_array($qry_typeContract))
			{
				$typecontract = $loop_typeContract["typecontract"];
				
				$cloop++;
				if($click == "yes")
				{
					if($looptypecontract[$cloop] == "on")
					{
						echo "<input type=\"checkbox\" name=\"$typecontract\" id=\"con_type\" checked> $typecontract &nbsp;&nbsp;&nbsp;";
						$typeForSave .= "<input type=\"hidden\" name=\"$typecontract\" value=\"on\">";
					}
					else
					{
						echo "<input type=\"checkbox\" name=\"$typecontract\" id=\"con_type\" > $typecontract &nbsp;&nbsp;&nbsp;";
						$typeForSave .= "<input type=\"hidden\" name=\"$typecontract\" value=\"\">";
					}
				}
				else
				{
					echo "<input type=\"checkbox\" name=\"$typecontract\" id=\"con_type\" checked> $typecontract &nbsp;&nbsp;&nbsp;";
				}
			}
		?>
	</form>
</center>

<?php

if($save == "yes")
{
	$savefile = iconv("UTF-8","",$savefile);
	
	$strFileName = "file_ncb/TUCRS-THCAP-$year-$month.Dat";
	$objFopen = fopen($strFileName, 'w');
	fwrite($objFopen, $savefile);
	if($objFopen)
	{
		
		echo "<center><h2><font color=#0000FF>บันทึกสำเร็จ</font></h2></center>";
	}
		else
	{
		echo "<center><h2><font color=#FF0000>บันทึกผิดพลาด!!</font></h2></center>";
	}
	fclose($objFopen);
}

?>



<?php

if($click == "yes")
{
	$day = search_day($month , $year);
	$date = "$year-$month-$day";
	
	// หา วันเดือนปี ณ สิ้นเดือน ของเดือนก่อนหน้านี้
	if($month == "01")
	{
		$bmonth = "12";
		$byear = $year - 1;
		$bday = search_day($bmonth , $byear);
		$bdate = "$byear-$bmonth-$bday";
	}
	else
	{
		$bmonth = $month - 1;
		if(strlen($bmonth) == 1){$bmonth = "0$bmonth";}
		$byear = $year;
		$bday = search_day($bmonth , $byear);
		$bdate = "$byear-$bmonth-$bday";
	}
	
	$db1="ta_mortgage_datastore"; // ใช้ฐานข้อมูลจาก mysql

	//$sql_main = mysql_query("select distinct * from $db1.vcontractconsumer WHERE contract_loans_startdate <= '$date' order by contract_loans_code , cus_idnum"); // เลขที่สัญญาทั้งหมด
	//$row_main = mysql_num_rows($sql_main); // จำนวนสัญญาทั้งหมด
	$sql_main = pg_query("select distinct *, \"thcap_get_all_isSue\"(\"contractID\", '$date'), \"thcap_get_all_isRestructure\"(\"contractID\", '$date'), \"thcap_get_all_isSold\"(\"contractID\", '$date')
						from public.\"VNCB\" WHERE \"conDate\" <= '$date'
						and (\"thcap_get_all_isSold\"(\"contractID\", '$bdate') is null or (\"thcap_get_all_isSold\"(\"contractID\", '$bdate') is not null and \"thcap_get_all_isSold\"(\"contractID\", '$bdate') <> '1'))
						$myWhere order by \"contractID\" , \"N_IDCARD\" "); // เลขที่สัญญาทั้งหมด
	$row_main = pg_num_rows($sql_main); // จำนวนสัญญาทั้งหมด

	
//$TUDF = TUDF($month , $year , $row_main); // หัวของ NCB

while($ncb = pg_fetch_array($sql_main))
{
	//******************** PN ********************//
	
	//--- tag PN
		$segment_tag_pn = "PN03N01";
	//---
	
	//--- tag 01 (FamilyName 1)
		$sname = trim($ncb["A_SIRNAME"]); // นามสกุล
		$number_sname = utf8_strlen($sname); // ความยาวของนามสกุล
		// ทำให้จำนวนมี 2 หลัก
		if(strlen($number_sname) < 2)
		{
			$number_sname = "0".$number_sname;
		}
		$tag_pn_01 = "01".$number_sname.$sname;
	//---
	
	//--- tag 04 (First Name)
		$fname = trim($ncb["A_NAME"]); // ชื่อ
		$number_fname = utf8_strlen($fname); // ความยาวของนามสกุล
		// ทำให้จำนวนมี 2 หลัก
		if(strlen($number_fname) < 2)
		{
			$number_fname = "0".$number_fname;
		}
		$tag_pn_04 = "04".$number_fname.$fname;
	//---
	
	//--- tag 06 (Marital Status)
		$Marital_Status = trim($ncb["A_STATUS"]); // รหัสสถานะสมรส
		$tag_pn_06 = "0604".$Marital_Status;
	//---
	
	//--- tag 07 (Date of Birth)
		$Date_of_Birth = trim($ncb["A_BIRTHDAY"]); // วันเกิดลูกค้า
		if($Date_of_Birth != "")
		{ // ถ้ามีข้อมูล
			$Date_of_Birth = str_replace("-","",$Date_of_Birth);
			$tag_pn_07 = "0708".$Date_of_Birth;
		}
		else
		{ // ถ้าไม่มีข้อมูล
			$tag_pn_07 = "070819000101";
		}
	//---
	
	//--- tag 08 (Gender)
		if(trim($ncb["A_SEX"]) != "") // ถ้าเพศมีค่า
		{
			$Gender = trim($ncb["A_SEX"]); // เพศ
			if($Gender == "")
			{
				$tag_pn_08 = "";
			}
			else
			{
				$tag_pn_08 = "0801".$Gender;
			}
		}
		else // ถ้าเพศว่าง ไม่จำเป็นต้องส่งข้อมูล
		{
			$tag_pn_08 = "";
		}
	//---
	
	//--- tag 09 (Title/Prefix)
		$Title = trim($ncb["A_FIRNAME"]); // คำนำหน้าชื่อ
		$number_Title = utf8_strlen($Title); // ความยาวของคำนำหน้าชื่อ
		// ทำให้จำนวนมี 2 หลัก
		if(strlen($number_Title) < 2)
		{
			$number_Title = "0".$number_Title;
		}
		$tag_pn_09 = "09".$number_Title.$Title;
	//---
	
	//--- tag 10 (Nationality)
		$Nationality = trim($ncb["N_SAN"]); // รหัสสัญชาติ
		$tag_pn_10 = "1002".$Nationality;
	//---
	
	//--- tag 12 (Spouse Name) (tag นี้อาจจะเป็นค่าว่างได้)
		$Spouse_Name = trim($ncb["A_PAIR"]); // คู่สมรส
		if($Spouse_Name != "")
		{
			$number_Spouse_Name = utf8_strlen($Spouse_Name); // ความยาวของคู่สมรส
			// ทำให้จำนวนมี 2 หลัก
			if(strlen($number_Spouse_Name) < 2)
			{
				$number_Spouse_Name = "0".$number_Spouse_Name;
			}
			$tag_pn_12 = "12".$number_Spouse_Name.$Spouse_Name;
		}
		else
		{
			$tag_pn_12 = "";
		}
	//---
	
	//--- tag 13 (Occupation)
		$tag_pn_13 = "13010";
	//---
	
	//--- tag 15 (Customer Type Field)
		$tag_pn_15 = "15011";
	//---
	
	$text_PN = $segment_tag_pn.$tag_pn_01.$tag_pn_04.$tag_pn_06.$tag_pn_07.$tag_pn_08.$tag_pn_09.$tag_pn_10.$tag_pn_12.$tag_pn_13.$tag_pn_15;
	
	
	//******************** ID ********************//
	
	//--- tag ID
		$segment_tag_id = "ID03ID1";
	//---
	
	//--- tag 01 (ID Type)
		$ID_Type = trim($ncb["N_CARD"]); // รหัสประเภทบัตร
		if($ID_Type == "00"){$tag_id_01 = "010200";}
		elseif($ID_Type == "01"){$tag_id_01 = "010201";}
		elseif($ID_Type == "02"){$tag_id_01 = "010202";}
		elseif($ID_Type == "03"){$tag_id_01 = "010203";}
		elseif($ID_Type == "04"){$tag_id_01 = "010204";}
		elseif($ID_Type == "05"){$tag_id_01 = "010205";}
		elseif($ID_Type == "06"){$tag_id_01 = "010206";}
		elseif($ID_Type == "07"){$tag_id_01 = "010207";}
		elseif($ID_Type == "09"){$tag_id_01 = "010209";}
	//---
	
	//--- tag 02 (ID Number)
		$ID_Number = trim($ncb["N_IDCARD"]); // เลขที่บัตร
		$number_ID_Number = utf8_strlen($ID_Number); // ความยาวของเลขที่บัตร
		// ทำให้จำนวนมี 2 หลัก
		if(strlen($number_ID_Number) < 2)
		{
			$number_ID_Number = "0".$number_ID_Number;
		}
		$tag_id_02 = "02".$number_ID_Number.$ID_Number;
	//---
	
	$text_ID = $segment_tag_id.$tag_id_01.$tag_id_02;
	
	
	
	//******************** PA ********************//
	
	//--- contractID
		$contractID = trim($ncb["contractID"]); // เลขที่สัญญา
	//---
	
	//--- tag PA
		$segment_tag_pa = "PA03A01";
	//---
	
	//--- tag 01,02,03 (Address_Line_1,Address_Line_2,Address_Line_3)
		$Address = trim($ncb["address"]); // ที่อยู่
		$number_Address = utf8_strlen($Address); // ความยาวของที่อยู่
		
		//--- ถ้าไม่พบข้อมูลให้ไปเอาจาก postgres
		/*if($number_Address == 0) ไม่ใช้ข้อมูลจากตาราง thcap_temp_ncbdata แล้ว เนื่องจากมีข้อมูลในตารางอื่นแล้ว
		{
			$sql_Address = pg_query("SELECT * FROM \"thcap_temp_ncbdata\" WHERE \"contractID\" = '$contractID' AND \"CusIDNum\" = '$ID_Number' ");
			while($resAddress = pg_fetch_array($sql_Address))
			{
				$Address = trim($resAddress["address"]); // ที่อยู่
				$number_Address = utf8_strlen($Address); // ความยาวของที่อยู่
			}
		}*/
		//---
		
		if($number_Address == 0)
		{
			$tag_pa_01 = "0103***";
			$tag_pa_02 = "";
			$tag_pa_03 = "";
		}
		elseif($number_Address <= 45)
		{
			// ทำให้จำนวนมี 2 หลัก
			if(strlen($number_Address) < 2)
			{
				$number_Address = "0".$number_Address;
			}
			
			$tag_pa_01 = "01".$number_Address.$Address;
			$tag_pa_02 = "";
			$tag_pa_03 = "";
		}
		elseif($number_Address > 45 && $number_Address <= 90)
		{
			$number_Address_Line_2 = $number_Address - 45;
			$Address_Line_1 = substr_utf8($Address,0,45);
			$Address_Line_2 = substr_utf8($Address,45,$number_Address_Line_2);
			
			// ทำให้จำนวนมี 2 หลัก
			if(strlen($number_Address_Line_2) < 2)
			{
				$number_Address_Line_2 = "0".$number_Address_Line_2;
			}
			
			$tag_pa_01 = "0145".$Address_Line_1;
			$tag_pa_02 = "02".$number_Address_Line_2.$Address_Line_2;
			$tag_pa_03 = "";
		}
		elseif($number_Address > 90)
		{
			$number_Address_Line_3 = $number_Address - 90;
			if($number_Address_Line_3 > 45){$number_Address_Line_3 = 45;}
			
			$Address_Line_1 = substr_utf8($Address,0,45);
			$Address_Line_2 = substr_utf8($Address,45,45);
			$Address_Line_3 = substr_utf8($Address,90,$number_Address_Line_3);
			
			// ทำให้จำนวนมี 2 หลัก
			if(strlen($number_Address_Line_2) < 2)
			{
				$number_Address_Line_2 = "0".$number_Address_Line_2;
			}
			
			$tag_pa_01 = "0145".$Address_Line_1;
			$tag_pa_02 = "0245".$Address_Line_2;
			$tag_pa_03 = "03".$number_Address_Line_3.$Address_Line_3;
		}
	//---
	
	//--- tag 04 (Subdistrict (Tumbol/Kwaeng))
		$Subdistrict = trim($ncb["A_TUM"]); // ตำบล
		$number_Subdistrict = utf8_strlen($Subdistrict); // ความยาวของตำบล
		
		//--- ถ้าไม่พบข้อมูลให้ไปเอาจาก postgres
		/*if($number_Subdistrict == 0) ไม่ใช้ข้อมูลจากตาราง thcap_temp_ncbdata แล้ว เนื่องจากมีข้อมูลในตารางอื่นแล้ว
		{
			$sql_Subdistrict = pg_query("SELECT * FROM \"thcap_temp_ncbdata\" WHERE \"contractID\" = '$contractID' AND \"CusIDNum\" = '$ID_Number' ");
			while($resSubdistrict = pg_fetch_array($sql_Subdistrict))
			{
				$Subdistrict = trim($resSubdistrict["subdistrict"]); // ตำบล
				$number_Subdistrict = utf8_strlen($Subdistrict); // ความยาวของตำบล
			}
		}*/
		//---
		
		if($number_Subdistrict == 0)
		{
			//$tag_pa_04 = "0403***";
			$tag_pa_04 = "";
		}
		else
		{		
			// ทำให้จำนวนมี 2 หลัก
			if(strlen($number_Subdistrict) < 2)
			{
				$number_Subdistrict = "0".$number_Subdistrict;
			}
				
			$tag_pa_04 = "04".$number_Subdistrict.$Subdistrict;
		}
	//---
	
	//--- tag 05 (District (Amphur/Khet))
		$District = trim($ncb["A_AUM"]); // อำเภอ
		$number_District = utf8_strlen($District); // ความยาวของอำเภอ
		
		//--- ถ้าไม่พบข้อมูลให้ไปเอาจาก postgres
		/*if($number_District == 0) ไม่ใช้ข้อมูลจากตาราง thcap_temp_ncbdata แล้ว เนื่องจากมีข้อมูลในตารางอื่นแล้ว
		{
			$sql_District = pg_query("SELECT * FROM \"thcap_temp_ncbdata\" WHERE \"contractID\" = '$contractID' AND \"CusIDNum\" = '$ID_Number' ");
			while($resDistrict = pg_fetch_array($sql_District))
			{
				$District = trim($resDistrict["district"]); // อำเภอ
				$number_District = utf8_strlen($District); // ความยาวของอำเภอ
			}
		}*/
		//---
		
		if($number_District == 0)
		{
			//$tag_pa_05 = "0503***";
			$tag_pa_05 = "";
		}
		else
		{
			// ทำให้จำนวนมี 2 หลัก
			if(strlen($number_District) < 2)
			{
				$number_District = "0".$number_District;
			}
				
			$tag_pa_05 = "05".$number_District.$District;
		}
	//---
	
	//--- tag 06 (Province)
		$Province = trim($ncb["A_PRO"]); // จังหวัด
		$number_Province = utf8_strlen($Province); // ความยาวของจังหวัด
		
		//--- ถ้าไม่พบข้อมูลให้ไปเอาจาก postgres
		/*if($number_Province == 0) ไม่ใช้ข้อมูลจากตาราง thcap_temp_ncbdata แล้ว เนื่องจากมีข้อมูลในตารางอื่นแล้ว
		{
			$sql_Province = pg_query("SELECT * FROM \"thcap_temp_ncbdata\" WHERE \"contractID\" = '$contractID' AND \"CusIDNum\" = '$ID_Number' ");
			while($resProvince = pg_fetch_array($sql_Province))
			{
				$Province = trim($resProvince["province"]); // จังหวัด
				$number_Province = utf8_strlen($Province); // ความยาวของจังหวัด
			}
		}*/
		//---
		
		$SearchSpaceProvince = strpos($Province," "); // หาว่ามีช่องว่างหรือไม่ เพราะถ้ามีอาจจะเป็นเพราะว่ามีรหัสไปรษณีย์ปนอยู่
		if($SearchSpaceProvince)
		{ // ถ้ามีช่องว่าง
			$SeparateProvince = explode(" ",$SearchSpaceProvince);
			$Province = $SeparateProvince[0];
			$number_Province = utf8_strlen($Province); // ความยาวของจังหวัด
			$Postal_Code_2 = $SeparateProvince[1]; // รหัสไปรษณีย์สำรอง
		}
		else
		{
			$Postal_Code_2 = ""; // รหัสไปรษณีย์สำรอง
		}
		
		if($number_Province == 0)
		{
			//$tag_pa_06 = "0603***";
			$tag_pa_06 = "";
		}
		else
		{
			// ทำให้จำนวนมี 2 หลัก
			if(strlen($number_Province) < 2)
			{
				$number_Province = "0".$number_Province;
			}
				
			$tag_pa_06 = "06".$number_Province.$Province;
		}
	//---
	
	//--- tag 07 (Country)
		$Country = trim($ncb["addr_country"]); // ชื่อย่อประเทศ
		$number_Country = utf8_strlen($Country); // ความยาวของชื่อย่อประเทศ
		
		//--- ถ้าไม่พบข้อมูลให้ไปเอาจาก postgres
		/*if($number_Country == 0) ไม่ใช้ข้อมูลจากตาราง thcap_temp_ncbdata แล้ว เนื่องจากมีข้อมูลในตารางอื่นแล้ว
		{
			$sql_Country = pg_query("SELECT * FROM \"thcap_temp_ncbdata\" WHERE \"contractID\" = '$contractID' AND \"CusIDNum\" = '$ID_Number' ");
			while($resCountry = pg_fetch_array($sql_Country))
			{
				$Country = trim($resCountry["country"]); // ชื่อย่อประเทศ
				$number_Country = utf8_strlen($Country); // ความยาวของชื่อย่อประเทศ
			}
		}*/
		//---
		
		if($number_Country == 0)
		{
			//$tag_pa_07 = "0703***";
			$tag_pa_07 = "";
		}
		else
		{
			// ทำให้จำนวนมี 2 หลัก
			if(strlen($number_Country) < 2)
			{
				$number_Country = "0".$number_Country;
			}
				
			$tag_pa_07 = "07".$number_Country.$Country;
		}
	//---
	
	//--- tag 08 (Postal Code)
		$Postal_Code = trim($ncb["A_POST"]); // รหัสไปรษณีย์
		$number_Postal_Code = utf8_strlen($Postal_Code); // ความยาวของรหัสไปรษณีย์
		
		//--- ถ้าไม่พบข้อมูลให้ไปเอาจาก postgres
		/*if($number_Postal_Code == 0) ไม่ใช้ข้อมูลจากตาราง thcap_temp_ncbdata แล้ว เนื่องจากมีข้อมูลในตารางอื่นแล้ว
		{
			$sql_Postal_Code= pg_query("SELECT * FROM \"thcap_temp_ncbdata\" WHERE \"contractID\" = '$contractID' AND \"CusIDNum\" = '$ID_Number' ");
			while($resPostal_Code= pg_fetch_array($sql_Postal_Code))
			{
				$Postal_Code = trim($resPostal_Code["country"]); // รหัสไปรษณีย์
				$number_Postal_Code = utf8_strlen($Postal_Code); // ความยาวของรหัสไปรษณีย์
			}
		}*/
		//---
		
		if($Postal_Code == "")
		{ // ถ้าไม่เจอรหัสไปรษณีย์
			$Postal_Code = $Postal_Code_2; // ให้ไปเอารหัสไปรษณีย์สำรอง
			$number_Postal_Code = utf8_strlen($Postal_Code); // ความยาวของรหัสไปรษณีย์
		}
		
		if($number_Postal_Code == 0)
		{
			//$tag_pa_08 = "0803***";
			$tag_pa_08 = "";
		}
		else
		{
			// ทำให้จำนวนมี 2 หลัก
			if(strlen($number_Postal_Code) < 2)
			{
				$number_Postal_Code = "0".$number_Postal_Code;
			}
				
			$tag_pa_08 = "08".$number_Postal_Code.$Postal_Code;
		}
	//---
	
	//--- tag 11 (Address Type)
		$tag_pa_11 = "11011";
	//---
	
	$text_PA = $segment_tag_pa.$tag_pa_01.$tag_pa_02.$tag_pa_03.$tag_pa_04.$tag_pa_05.$tag_pa_06.$tag_pa_07.$tag_pa_08.$tag_pa_11;
	
	
	
	//******************** TL ********************//
	
	//--- contractID
		$contractID = trim($ncb["contractID"]); // เลขที่สัญญา
	//---
	
	//--- หาประเภทสัญญา
		$qryConType = pg_query("select \"conType\" from \"thcap_contract\" where \"contractID\" = '$contractID' ");
		$conType = pg_fetch_result($qryConType,0);
	//---
	
	//--- ถ้าเคยปิดบัญชีไปเมื่อเดือนก่อนหน้าแล้ว ไม่ต้องเอามาแสดงอีก คือให้หาคนต่อไปเลย คนนี้ไม่เอาแล้ว
		$sql_chk_close = pg_query("SELECT * FROM \"thcap_ncb_statement\" WHERE \"asOfDate\" < '$date' AND \"contractID\" = '$contractID' AND \"amountOwn\" <= '0' ");
		$row_chk_close = pg_num_rows($sql_chk_close);
		if($row_chk_close > 0)
		{
			$row_main = $row_main - 1;
			continue;
		}
	//---
	
	//--- database postgres
		$amountOwn = "";
		// หาประเภทสินเชื่อ
		$qry_fullconType = pg_query("select \"thcap_get_creditType\"('$contractID')");
		$fullconType = pg_fetch_result($qry_fullconType,0);
		
		if($fullconType == "HIRE_PURCHASE" || $fullconType == "LEASING" || $fullconType == "GUARANTEED_INVESTMENT" || $fullconType == "FACTORING" || $fullconType == "SALE_ON_CONSIGNMENT")
		{
			// tag 09 (วันที่จ่ายครั้งล่าสุด)
			$qry_Date_Of_Last_Payment = pg_query("SELECT thcap_get_lease_lastpaydate('$contractID', '$date')");
			$Date_Of_Last_Payment = pg_fetch_result($qry_Date_Of_Last_Payment,0);
			
			// tag 13 เงินทั้งหมดที่ลูกค้าเป็นหนี้ (เงินต้นรวมดอกเบี้ย ณ As Of Date)
			if($fullconType == "HIRE_PURCHASE" || $fullconType == "LEASING")
			{
				$qry_amount_owed_or_principal_or_credit_use = pg_query("select \"thcap_amountown\"('$contractID', '$date', '3')");
				$amount_owed_or_principal_or_credit_use = pg_fetch_result($qry_amount_owed_or_principal_or_credit_use,0);
				$amountOwn = $amount_owed_or_principal_or_credit_use;
			}
			else
			{
				$qry_amount_owed_or_principal_or_credit_use = pg_query("select thcap_get_lease_totalleft('$contractID', '$date')");
				$amount_owed_or_principal_or_credit_use = pg_fetch_result($qry_amount_owed_or_principal_or_credit_use,0);
				$amountOwn = $amount_owed_or_principal_or_credit_use;
			}
			
			// tag 14 จำนวนยอดค้างผ่อนชำระ
			$qry_amount_past_due = pg_query("select thcap_get_lease_backamt('$contractID', '$date')");
			$amount_past_due = pg_fetch_result($qry_amount_past_due,0);
			
			// tag 19 วันที่ผิดนัดชำระ
			$gry_defaultDate = pg_query("select thcap_get_lease_backdate('$contractID', '$date')");
			$defaultDate = pg_fetch_result($gry_defaultDate,0);
			
			$sql_money = pg_query("SELECT * FROM \"thcap_lease_contract\" WHERE \"contractID\" = '$contractID' ");
			while($resmoney = pg_fetch_array($sql_money))
			{
				$installment_amount = trim($resmoney["conMinPay"]); // tag 21 ยอดจ่ายขั้นต่ำ
				$credit_limit_or_original_loan_amount = trim($resmoney["conLoanAmt"]); // tag 12
				$sendNCB = $resmoney["sendNCB"]; // ต้องส่ง NCB หรือไม่
			}
			if($sendNCB == "0")
			{
				$row_main = $row_main - 1;
				continue;
			}
		}
		else
		{
			$sql_TL = pg_query("SELECT * FROM \"thcap_ncb_statement\" WHERE \"asOfDate\" = '$date' AND \"contractID\" = '$contractID' ");
			while($resTL = pg_fetch_array($sql_TL))
			{
				$Date_Of_Last_Payment = trim($resTL["lastPayDate"]); // tag 09 (วันที่จ่ายครั้งล่าสุด)
				$amount_owed_or_principal_or_credit_use = trim($resTL["amountOwn"]); // tag 13 เงินทั้งหมดที่ลูกค้าเป็นหนี้ (เงินต้นรวมดอกเบี้ย ณ As Of Date)
				$amount_past_due = trim($resTL["amountRemain"]); // tag 14 จำนวนยอดค้างผ่อนชำระ
				$defaultDate = trim($resTL["defaultDate"]); // tag 19
				$amountOwn = trim($resTL["amountOwn"]); // tag 23
			}
			
			$sql_money = pg_query("SELECT * FROM \"thcap_mg_contract\" WHERE \"contractID\" = '$contractID' ");
			while($resmoney = pg_fetch_array($sql_money))
			{
				$installment_amount = trim($resmoney["conMinPay"]); // tag 21 ยอดจ่ายขั้นต่ำ
				$credit_limit_or_original_loan_amount = trim($resmoney["conLoanAmt"]); // tag 12
				$sendNCB = $resmoney["sendNCB"]; // ต้องส่ง NCB หรือไม่
			}
			if($sendNCB == "0")
			{
				$row_main = $row_main - 1;
				continue;
			}
		}
	//---
	
	//--- TL
		$segment_tag_tl = "TL04T001";
	//---
	
	//--- tag 01 (current/new member code)
		$tag_tl_01 = "0110CC11030000";
	//---
	
	//--- tag 02 (current/new member name)
		$tag_tl_02 = "0205THCAP";
	//---
	
	//--- tag 03 (current/new account number)
		$current_or_new_account_number = trim($ncb["contractID"]); // เลขที่สัญญา
		$number_current_or_new_account_number = utf8_strlen($current_or_new_account_number); // ความยาวของเลขที่สัญญา
		
		$tag_tl_03 = "03".$number_current_or_new_account_number.$current_or_new_account_number;
	//---
	
	//--- tag 04 (Account Type)
		if($fullconType == "PERSONAL_LOAN")
		{
			$tag_tl_04 = "040205";
		}
		elseif($fullconType == "HIRE_PURCHASE")
		{
			$tag_tl_04 = "040221";
		}
		elseif($fullconType == "LEASING")
		{
			$tag_tl_04 = "040229";
		}
		else
		{
			$tag_tl_04 = "040299";
		}
	//---
	
	//--- tag 05 (ownership indicator) // ผู้กู้หลักหรือผู้กู้ร่วม
		//--- เช็ควันที่เปิดบัญชี
		$Date_Account_Opened = trim($ncb["conDate"]);
		if(date($Date_Account_Opened) > date('2012-05-31'))
		{ // ถ้าเป็นสัญญาหลังจาก 2012-05-31
			$ownership_indicator = trim($ncb["ownership"]);
		}
		else
		{ // ถ้าเป็นสัญญาเก่ากว่า 2012-06-01
			$sql_ownership_old = pg_query("select * from \"contractconsumer\" where \"contract_loans_code\" = '$current_or_new_account_number' and \"cus_idnum\" = '$ID_Number' ");
			$numrowsold = pg_num_rows($sql_ownership_old);
			if($numrowsold > 0)
			{
				while($ncbold = pg_fetch_array($sql_ownership_old))
				{
					$ownership_indicator = trim($ncbold["ownership_indicator"]);
				}
			}
			else
			{ // ถ้าไม่เจอให้ไปเอาข้อมูลใหม่
				$ownership_indicator = trim($ncb["ownership"]);
			}
		}
		
		$tag_tl_05 = "0501".$ownership_indicator;
	//---
	
	//--- tag 06 (currency code)
		$tag_tl_06 = "0603THB";
	//---
	
	//--- tag 07 (Future Use)
		$tag_tl_07 = "07011";
	//---
	
	//--- tag 08 (Date Account Opened) // วันที่เริ่มสัญญา
		$Date_Account_Opened = trim($ncb["conDate"]);
		$Date_Account_Opened = str_replace("-","",$Date_Account_Opened);
		$Date_Account_Opened = substr($Date_Account_Opened,0,8);
		
		$tag_tl_08 = "0808".$Date_Account_Opened;
	//---
	
	//--- tag 09 (Date Of Last Payment) // วันที่จ่ายล่าสุด
		if($Date_Of_Last_Payment != "")
		{
			$Date_Of_Last_Payment = str_replace("-","",$Date_Of_Last_Payment);
			$tag_tl_09 = "0908".$Date_Of_Last_Payment;
		}
		else
		{
			$tag_tl_09 = "090819000101";
		}
	//---
	
	//--- tag 10 (Date Account Closed) // วันที่ปิดบัญชี
		$Date_Account_Closed = trim($ncb["conStatus"]); // สถาณะสัญญา
		if(is_numeric($amountOwn) && $amountOwn <= 0){$Date_Account_Closed = "11";} // ถ้าใน postgres เป็น 0.00 แสดงว่าปิดบัญชีไปแล้ว
		if($Date_Account_Closed == "11")
		{ // ถ้าสถานะเป็น ปิดบัญชีแล้ว
			// หาวันที่ปิดบัญชีจาก function
			$qry_closeDateFromFunction = pg_query("select \"thcap_checkcontractcloseddate\"('$current_or_new_account_number')");
			$closeDateFromFunction = pg_fetch_result($qry_closeDateFromFunction,0);
			
			if($closeDateFromFunction != "") // ถ้าเจอวันที่ปิดบัญชีจาก function ให้ใช้วันที่นี้เป็นวันที่ปิดบัญชี
			{
				if($closeDateFromFunction <= $bdate)
				{ // ถ้าปิดบัญชีไปก่อนหน้าเดือนนี้แล้ว
					$row_main = $row_main - 1;
					continue;
				}
				
				$closeDateFromFunction = str_replace("-","",$closeDateFromFunction);
				$tag_tl_10 = "1008".$closeDateFromFunction;
			}
			else // ถ้าไม่เจอวันที่ปิดบัญชีจาก function ให้ใช้วันที่จ่ายล่าสุดเป็นวันที่ปิดบัญชี
			{
				if($Date_Of_Last_Payment <= $bdate)
				{ // ถ้าปิดบัญชีไปก่อนหน้าเดือนนี้แล้ว
					$row_main = $row_main - 1;
					continue;
				}
				
				$Date_Of_Last_Payment = str_replace("-","",$Date_Of_Last_Payment);
				$tag_tl_10 = "1008".$Date_Of_Last_Payment;
			}
		}
		else
		{
			$tag_tl_10 = "";
		}
	//---
	
	//--- tag 11 (As Of Date) // วันที่สนใจ
		$As_Of_Date = str_replace("-","",$date);
		$tag_tl_11 = "1108".$As_Of_Date;
	//---
	
	//-- tag 12 (Credit Limit/Original Loan Amount)
		//$credit_limit_or_original_loan_amount = trim($ncb["appv_credit_money"]); // จำนวนเงินขอกู้  // ใช้ pg แทน
		$credit_limit_or_original_loan_amount = substr($credit_limit_or_original_loan_amount,0,strlen($credit_limit_or_original_loan_amount) - 3);
		$number_credit_limit_or_original_loan_amount = utf8_strlen($credit_limit_or_original_loan_amount);
		// ทำให้จำนวนมี 2 หลัก
		if(strlen($number_credit_limit_or_original_loan_amount) < 2)
		{
			$number_credit_limit_or_original_loan_amount = "0".$number_credit_limit_or_original_loan_amount;
		}
		
		$tag_tl_12 = "12".$number_credit_limit_or_original_loan_amount.$credit_limit_or_original_loan_amount;
	//---
	
	//--- tag 13 (Amount Owed/Principal/Credit Use) เงินทั้งหมดที่ลูกค้าเป็นหนี้ (เงินต้นรวมดอกเบี้ย ณ As Of Date)
		$amount_owed_or_principal_or_credit_use = substr($amount_owed_or_principal_or_credit_use,0,strlen($amount_owed_or_principal_or_credit_use) - 3);
		$number_amount_owed_or_principal_or_credit_use = utf8_strlen($amount_owed_or_principal_or_credit_use);
		// ทำให้จำนวนมี 2 หลัก
		if(strlen($number_amount_owed_or_principal_or_credit_use) < 2)
		{
			$number_amount_owed_or_principal_or_credit_use = "0".$number_amount_owed_or_principal_or_credit_use;
		}
		
		$tag_tl_13 = "13".$number_amount_owed_or_principal_or_credit_use.$amount_owed_or_principal_or_credit_use;
	//---
	
	//--- tag 14 (Amount Past Due) จำนวนยอดค้างผ่อนชำระ
		$amount_past_due = substr($amount_past_due,0,strlen($amount_past_due) - 3);
		$number_amount_past_due = utf8_strlen($amount_past_due);
		// ทำให้จำนวนมี 2 หลัก
		if(strlen($number_amount_past_due) < 2)
		{
			$number_amount_past_due = "0".$number_amount_past_due;
		}
		
		$tag_tl_14 = "14".$number_amount_past_due.$amount_past_due;
	//---
	
	//--- tag 15 (Number Of Days Past Due/Delinquency Status)
		/*$sql_Due = pg_query("SELECT (\"asOfDate\" - \"defaultDate\") as daypast FROM \"thcap_ncb_statement\" WHERE \"asOfDate\" = '$date' AND \"contractID\" = '$contractID' ");
		while($resDue = pg_fetch_array($sql_Due))
		{
			$daypast = trim($resDue["daypast"]);
		}*/
		$daypast = ceil((strtotime($date) - strtotime($defaultDate))/(60*60*24));
		if (($daypast >= 0 && $daypast <= 30) || $defaultDate == ""){$tag_tl_15 = "1503000";}
		else if ($daypast >= 31 && $daypast <= 60){$tag_tl_15 = "1503001";}
		else if ($daypast >= 61 && $daypast <= 90){$tag_tl_15 = "1503002";}
		else if ($daypast >= 91 && $daypast <= 120){$tag_tl_15 = "1503003";}
		else if ($daypast >= 121 && $daypast <= 150){$tag_tl_15 = "1503004";}
		else if ($daypast >= 151 && $daypast <= 180){$tag_tl_15 = "1503005";}
		else if ($daypast >= 181 && $daypast <= 210){$tag_tl_15 = "1503006";}
		else if ($daypast >= 211 && $daypast <= 240){$tag_tl_15 = "1503007";}
		else if ($daypast >= 241 && $daypast <= 270){$tag_tl_15 = "1503008";}
		else if ($daypast >= 271 && $daypast <= 300){$tag_tl_15 = "1503009";}
		else if ($daypast >= 301){$tag_tl_15 = "1503  F";}
		else{$tag_tl_15 = "";} // จงใจให้ error เพราะหาข้อมูลไม่เจอ ความจริง NCB Required ข้อมูลนี้
	//---
	
	//--- tag 19 (Default Date)
		if($defaultDate != "")
		{
			$defaultDate = str_replace("-","",$defaultDate);
			$tag_tl_19 = "1908".$defaultDate;
		}
		else
		{
			$tag_tl_19 = "";
		}
	//---
	
	//--- tag 20 (Installment Frequency)
		$tag_tl_20 = "20013";
	//---
	
	//--- tag 21 (Installment Amount)
		//$installment_amount = trim($ncb["contract_loans_minpay"]); // ค่างวดขั้นต่ำ  // ใช้ pg แทน
		$installment_amount = substr($installment_amount,0,strlen($installment_amount) - 3);
		$number_installment_amount = utf8_strlen($installment_amount); // ความยาวของค่างวดขั้นต่ำ
		// ทำให้จำนวนมี 2 หลัก
		if(strlen($number_installment_amount) < 2)
		{
			$number_installment_amount = "0".$number_installment_amount;
		}
		
		$tag_tl_21 = "21".$number_installment_amount.$installment_amount;
	//---
	
	//--- tag 22 (Installment Number Of Payments)
		$installment_number_of_payments = trim($ncb["conTerm"]); // จำนวนงวดผ่อนชำระ
		//$installment_number_of_payments = substr($installment_number_of_payments,0,strlen($installment_number_of_payments) - 3);
		$number_installment_number_of_payments = utf8_strlen($installment_number_of_payments); // ความยาว
		// ทำให้จำนวนมี 2 หลัก
		if(strlen($number_installment_number_of_payments) < 2)
		{
			$number_installment_number_of_payments = "0".$number_installment_number_of_payments;
		}
		
		$tag_tl_22 = "22".$number_installment_number_of_payments.$installment_number_of_payments;
	//---
	
	//--- tag 23 (Account Status)
		$account_status = trim($ncb["conStatus"]); // สถานะบัญชีสัญญาเงินกู้
		
		if($amountOwn > 0)
		{
			if($defaultDate != "")
			{
				$numdaydebt = ceil((strtotime($date) - strtotime($defaultDate))/(60*60*24)); // จำนวนวันค้างชำระ
				if($numdaydebt > 90)
				{
					$account_status = "20"; // ถ้าค้างชำระเกิด 90 วัน
				}
				else
				{
					//$account_status = "10"; // ถ้าเป็นสัญญาปกติ  // ในส่วนนี้ ถ้าไม่เกิน 90 วัน ดึงจาก MySQL ปกติ
				}
			}
			else
			{
				//$account_status = "10"; // ถ้าเป็นสัญญาปกติ  // ในส่วนนี้ ถ้าไม่เกิน 90 วัน ดึงจาก MySQL ปกติ
			}
		}
		
		if($ncb["thcap_get_all_isSold"] == "1")
		{
			$account_status = "42"; // โอนหรือขายหนี้ไปบุคคลอื่น
		}
		elseif($ncb["thcap_get_all_isSue"] == "1" && $ncb["thcap_get_all_isRestructure"] == "1")
		{
			$account_status = "31"; // อยู่ระหว่างชำระหนี้ตามคำพิพากษาตามยอม
		}
		elseif($ncb["thcap_get_all_isSue"] == "1")
		{
			$account_status = "30"; // อยู่ในกระบวนการทางกฎหมาย
		}
		
		if($amountOwn <= 0){$account_status = "11";} // ถ้าใน postgres เป็น 0.00 แสดงว่าปิดบัญชีไปแล้ว
		$tag_tl_23 = "2302".$account_status;
	//---
	
	//--- tag 39 (Number of coborrower)
		$number_of_co_borrower = trim($ncb["coborrow"]);
		// ทำให้จำนวนมี 2 หลัก
		if(strlen($number_of_co_borrower) < 2)
		{
			$number_of_co_borrower = "0".$number_of_co_borrower;
		}
		
		$tag_tl_39 = "3902".$number_of_co_borrower;
	//---
	
	$text_TL = $segment_tag_tl.$tag_tl_01.$tag_tl_02.$tag_tl_03.$tag_tl_04.$tag_tl_05.$tag_tl_06.$tag_tl_07.$tag_tl_08.$tag_tl_09.$tag_tl_10.$tag_tl_11.$tag_tl_12.$tag_tl_13.$tag_tl_14.$tag_tl_15.$tag_tl_19.$tag_tl_20;
	$text_TL = $text_TL.$tag_tl_21.$tag_tl_22.$tag_tl_23.$tag_tl_39;
	
	
	
	//++++++++++ GEN NCB ++++++++++//
	$gen_ncb = $text_PN.$text_ID.$text_PA.$text_TL."ES02**"."<br>";
	$text_ncb = $text_ncb.$gen_ncb;
}




//--------------------------------------- หาข้อมูลส่วนที่เหลือ
/* ปิดในส่วนนี้ไว้เนื่องจากไม่ต้องการให้นำข้อมูลจากตาราง thcap_temp_ncbdataall มาแสดงแล้ว เนื่องจาก migrate ไปหมดแล้ว แต่ถ้าอนาคตต้องการใช้ตารางนี้อีกครั้ง สามารถเอา comment ออกเพื่อใช้งานในส่วนนี้ได้
$sql_rest = pg_query("select * from public.\"thcap_temp_ncbdataall\" where \"Date_Account_Opened\" <= '$date' order by \"current_or_new_account_number\" , \"ID_Number\" ");
$row_rest = pg_num_rows($sql_rest); // จำนวนสัญญาที่เหลือ
if($row_rest > 0)
{
	while($ncb = pg_fetch_array($sql_rest))
	{
		$row_main = $row_main + 1;
	
		//-------------------- PN --------------------//
		
		//--- tag PN
			$segment_tag_pn = "PN03N01";
		//---
		
		//--- tag 01 (FamilyName 1)
			$sname = trim($ncb["sname"]); // นามสกุล
			$number_sname = utf8_strlen($sname); // ความยาวของนามสกุล
			// ทำให้จำนวนมี 2 หลัก
			if(strlen($number_sname) < 2)
			{
				$number_sname = "0".$number_sname;
			}
			$tag_pn_01 = "01".$number_sname.$sname;
		//---
		
		//--- tag 04 (First Name)
			$fname = trim($ncb["fname"]); // ชื่อ
			$number_fname = utf8_strlen($fname); // ความยาวของนามสกุล
			// ทำให้จำนวนมี 2 หลัก
			if(strlen($number_fname) < 2)
			{
				$number_fname = "0".$number_fname;
			}
			$tag_pn_04 = "04".$number_fname.$fname;
		//---
		
		//--- tag 06 (Marital Status)
			$Marital_Status = trim($ncb["Marital_Status"]); // รหัสสถานะสมรส
			$tag_pn_06 = "0604".$Marital_Status;
		//---
		
		//--- tag 07 (Date of Birth)
			$Date_of_Birth = trim($ncb["Date_of_Birth"]); // วันเกิดลูกค้า
			$Date_of_Birth = str_replace("-","",$Date_of_Birth);
			$tag_pn_07 = "0708".$Date_of_Birth;
		//---
		
		//--- tag 08 (Gender)
			$Gender = trim($ncb["Gender"]); // เพศ
			$tag_pn_08 = "0801".$Gender;
		//---
		
		//--- tag 09 (Title/Prefix)
			$Title = trim($ncb["Title"]); // คำนำหน้าชื่อ
			$number_Title = utf8_strlen($Title); // ความยาวของคำนำหน้าชื่อ
			// ทำให้จำนวนมี 2 หลัก
			if(strlen($number_Title) < 2)
			{
				$number_Title = "0".$number_Title;
			}
			$tag_pn_09 = "09".$number_Title.$Title;
		//---
		
		//--- tag 10 (Nationality)
			$Nationality = trim($ncb["Nationality"]); // รหัสสัญชาติ
			$tag_pn_10 = "1002".$Nationality;
		//---
		
		//--- tag 12 (Spouse Name) (tag นี้อาจจะเป็นค่าว่างได้)
			$Spouse_Name = trim($ncb["Spouse_Name"]); // คู่สมรส
			if($Spouse_Name != "")
			{
				$number_Spouse_Name = utf8_strlen($Spouse_Name); // ความยาวของคู่สมรส
				// ทำให้จำนวนมี 2 หลัก
				if(strlen($number_Spouse_Name) < 2)
				{
					$number_Spouse_Name = "0".$number_Spouse_Name;
				}
				$tag_pn_12 = "12".$number_Spouse_Name.$Spouse_Name;
			}
			else
			{
				$tag_pn_12 = "";
			}
		//---
		
		//--- tag 13 (Occupation)
			$tag_pn_13 = "13010";
		//---
		
		//--- tag 15 (Customer Type Field)
			$tag_pn_15 = "15011";
		//---
		
		$text_PN = $segment_tag_pn.$tag_pn_01.$tag_pn_04.$tag_pn_06.$tag_pn_07.$tag_pn_08.$tag_pn_09.$tag_pn_10.$tag_pn_12.$tag_pn_13.$tag_pn_15;
	
	
		//-------------------- ID --------------------//
	
		//--- tag ID
			$segment_tag_id = "ID03ID1";
		//---
		
		//--- tag 01 (ID Type)
			$ID_Type = trim($ncb["ID_Type"]); // รหัสประเภทบัตร
			if($ID_Type == "0"){$tag_id_01 = "010200";}
			elseif($ID_Type == "1"){$tag_id_01 = "010201";}
			elseif($ID_Type == "2"){$tag_id_01 = "010202";}
			elseif($ID_Type == "3"){$tag_id_01 = "010203";}
			elseif($ID_Type == "4"){$tag_id_01 = "010204";}
			elseif($ID_Type == "5"){$tag_id_01 = "010205";}
			elseif($ID_Type == "6"){$tag_id_01 = "010206";}
			elseif($ID_Type == "7"){$tag_id_01 = "010207";}
			elseif($ID_Type == "9"){$tag_id_01 = "010209";}
		//---
		
		//--- tag 02 (ID Number)
			$ID_Number = trim($ncb["ID_Number"]); // เลขที่บัตร
			$number_ID_Number = utf8_strlen($ID_Number); // ความยาวของเลขที่บัตร
			// ทำให้จำนวนมี 2 หลัก
			if(strlen($number_ID_Number) < 2)
			{
				$number_ID_Number = "0".$number_ID_Number;
			}
			$tag_id_02 = "02".$number_ID_Number.$ID_Number;
		//---
		
		$text_ID = $segment_tag_id.$tag_id_01.$tag_id_02;
		
		
		//-------------------- PA --------------------//
	
		//--- contractID
			$contractID = trim($ncb["current_or_new_account_number"]); // เลขที่สัญญา
		//---
		
		//--- tag PA
			$segment_tag_pa = "PA03A01";
		//---
		
		//--- tag 01,02,03 (Address_Line_1,Address_Line_2,Address_Line_3)
			$Address = trim($ncb["Address"]); // ที่อยู่
			$number_Address = utf8_strlen($Address); // ความยาวของที่อยู่
			
			//--- ถ้าไม่พบข้อมูลให้ไปเอาจาก postgres
			if($number_Address == 0)
			{
				$sql_Address = pg_query("SELECT * FROM \"thcap_temp_ncbdata\" WHERE \"contractID\" = '$contractID' AND \"CusIDNum\" = '$ID_Number' ");
				while($resAddress = pg_fetch_array($sql_Address))
				{
					$Address = trim($resAddress["address"]); // ที่อยู่
					$number_Address = utf8_strlen($Address); // ความยาวของที่อยู่
				}
			}
			//---
			
			if($number_Address == 0)
			{
				$tag_pa_01 = "0103***";
				$tag_pa_02 = "";
				$tag_pa_03 = "";
			}
			elseif($number_Address <= 45)
			{
				// ทำให้จำนวนมี 2 หลัก
				if(strlen($number_Address) < 2)
				{
					$number_Address = "0".$number_Address;
				}
				
				$tag_pa_01 = "01".$number_Address.$Address;
				$tag_pa_02 = "";
				$tag_pa_03 = "";
			}
			elseif($number_Address > 45 && $number_Address <= 90)
			{
				$number_Address_Line_2 = $number_Address - 45;
				$Address_Line_1 = substr_utf8($Address,0,45);
				$Address_Line_2 = substr_utf8($Address,45,$number_Address_Line_2);
				
				// ทำให้จำนวนมี 2 หลัก
				if(strlen($number_Address_Line_2) < 2)
				{
					$number_Address_Line_2 = "0".$number_Address_Line_2;
				}
				
				$tag_pa_01 = "0145".$Address_Line_1;
				$tag_pa_02 = "02".$number_Address_Line_2.$Address_Line_2;
				$tag_pa_03 = "";
			}
			elseif($number_Address > 90)
			{
				$number_Address_Line_3 = $number_Address - 90;
				if($number_Address_Line_3 > 45){$number_Address_Line_3 = 45;}
				
				$Address_Line_1 = substr_utf8($Address,0,45);
				$Address_Line_2 = substr_utf8($Address,45,45);
				$Address_Line_3 = substr_utf8($Address,90,$number_Address_Line_3);
				
				// ทำให้จำนวนมี 2 หลัก
				if(strlen($number_Address_Line_2) < 2)
				{
					$number_Address_Line_2 = "0".$number_Address_Line_2;
				}
				
				$tag_pa_01 = "0145".$Address_Line_1;
				$tag_pa_02 = "0245".$Address_Line_2;
				$tag_pa_03 = "03".$number_Address_Line_3.$Address_Line_3;
			}
		//---
		
		//--- tag 04 (Subdistrict (Tumbol/Kwaeng))
			$Subdistrict = trim($ncb["Subdistrict"]); // ตำบล
			$number_Subdistrict = utf8_strlen($Subdistrict); // ความยาวของตำบล
			
			//--- ถ้าไม่พบข้อมูลให้ไปเอาจาก postgres
			if($number_Subdistrict == 0)
			{
				$sql_Subdistrict = pg_query("SELECT * FROM \"thcap_temp_ncbdata\" WHERE \"contractID\" = '$contractID' AND \"CusIDNum\" = '$ID_Number' ");
				while($resSubdistrict = pg_fetch_array($sql_Subdistrict))
				{
					$Subdistrict = trim($resSubdistrict["subdistrict"]); // ตำบล
					$number_Subdistrict = utf8_strlen($Subdistrict); // ความยาวของตำบล
				}
			}
			//---
			
			if($number_Subdistrict == 0)
			{
				$tag_pa_04 = "0403***";
			}
			else
			{		
				// ทำให้จำนวนมี 2 หลัก
				if(strlen($number_Subdistrict) < 2)
				{
					$number_Subdistrict = "0".$number_Subdistrict;
				}
					
				$tag_pa_04 = "04".$number_Subdistrict.$Subdistrict;
			}
		//---
		
		//--- tag 05 (District (Amphur/Khet))
			$District = trim($ncb["District"]); // อำเภอ
			$number_District = utf8_strlen($District); // ความยาวของอำเภอ
			
			//--- ถ้าไม่พบข้อมูลให้ไปเอาจาก postgres
			if($number_District == 0)
			{
				$sql_District = pg_query("SELECT * FROM \"thcap_temp_ncbdata\" WHERE \"contractID\" = '$contractID' AND \"CusIDNum\" = '$ID_Number' ");
				while($resDistrict = pg_fetch_array($sql_District))
				{
					$District = trim($resDistrict["district"]); // อำเภอ
					$number_District = utf8_strlen($District); // ความยาวของอำเภอ
				}
			}
			//---
			
			if($number_District == 0)
			{
				$tag_pa_05 = "0503***";
			}
			else
			{
				// ทำให้จำนวนมี 2 หลัก
				if(strlen($number_District) < 2)
				{
					$number_District = "0".$number_District;
				}
					
				$tag_pa_05 = "05".$number_District.$District;
			}
		//---
		
		//--- tag 06 (Province)
			$Province = trim($ncb["Province"]); // จังหวัด
			$number_Province = utf8_strlen($Province); // ความยาวของจังหวัด
			
			//--- ถ้าไม่พบข้อมูลให้ไปเอาจาก postgres
			if($number_Province == 0)
			{
				$sql_Province = pg_query("SELECT * FROM \"thcap_temp_ncbdata\" WHERE \"contractID\" = '$contractID' AND \"CusIDNum\" = '$ID_Number' ");
				while($resProvince = pg_fetch_array($sql_Province))
				{
					$Province = trim($resProvince["province"]); // จังหวัด
					$number_Province = utf8_strlen($Province); // ความยาวของจังหวัด
				}
			}
			//---
			
			$SearchSpaceProvince = strpos($Province," "); // หาว่ามีช่องว่างหรือไม่ เพราะถ้ามีอาจจะเป็นเพราะว่ามีรหัสไปรษณีย์ปนอยู่
			if($SearchSpaceProvince)
			{ // ถ้ามีช่องว่าง
				$SeparateProvince = explode(" ",$SearchSpaceProvince);
				$Province = $SeparateProvince[0];
				$number_Province = utf8_strlen($Province); // ความยาวของจังหวัด
				$Postal_Code_2 = $SeparateProvince[1]; // รหัสไปรษณีย์สำรอง
			}
			else
			{
				$Postal_Code_2 = ""; // รหัสไปรษณีย์สำรอง
			}
			
			if($number_Province == 0)
			{
				$tag_pa_06 = "0603***";
			}
			else
			{
				// ทำให้จำนวนมี 2 หลัก
				if(strlen($number_Province) < 2)
				{
					$number_Province = "0".$number_Province;
				}
					
				$tag_pa_06 = "06".$number_Province.$Province;
			}
		//---
		
		//--- tag 07 (Country)
			$Country = trim($ncb["Country"]); // ชื่อย่อประเทศ
			$number_Country = utf8_strlen($Country); // ความยาวของชื่อย่อประเทศ
			
			//--- ถ้าไม่พบข้อมูลให้ไปเอาจาก postgres
			if($number_Country == 0)
			{
				$sql_Country = pg_query("SELECT * FROM \"thcap_temp_ncbdata\" WHERE \"contractID\" = '$contractID' AND \"CusIDNum\" = '$ID_Number' ");
				while($resCountry = pg_fetch_array($sql_Country))
				{
					$Country = trim($resCountry["country"]); // ชื่อย่อประเทศ
					$number_Country = utf8_strlen($Country); // ความยาวของชื่อย่อประเทศ
				}
			}
			//---
			
			if($number_Country == 0)
			{
				$tag_pa_07 = "0703***";
			}
			else
			{
				// ทำให้จำนวนมี 2 หลัก
				if(strlen($number_Country) < 2)
				{
					$number_Country = "0".$number_Country;
				}
					
				$tag_pa_07 = "07".$number_Country.$Country;
			}
		//---
		
		//--- tag 08 (Postal Code)
			$Postal_Code = trim($ncb["Postal_Code"]); // รหัสไปรษณีย์
			$number_Postal_Code = utf8_strlen($Postal_Code); // ความยาวของรหัสไปรษณีย์
			
			//--- ถ้าไม่พบข้อมูลให้ไปเอาจาก postgres
			if($number_Postal_Code == 0)
			{
				$sql_Postal_Code= pg_query("SELECT * FROM \"thcap_temp_ncbdata\" WHERE \"contractID\" = '$contractID' AND \"CusIDNum\" = '$ID_Number' ");
				while($resPostal_Code= pg_fetch_array($sql_Postal_Code))
				{
					$Postal_Code = trim($resPostal_Code["country"]); // รหัสไปรษณีย์
					$number_Postal_Code = utf8_strlen($Postal_Code); // ความยาวของรหัสไปรษณีย์
				}
			}
			//---
			
			if($Postal_Code == "")
			{ // ถ้าไม่เจอรหัสไปรษณีย์
				$Postal_Code = $Postal_Code_2; // ให้ไปเอารหัสไปรษณีย์สำรอง
				$number_Postal_Code = utf8_strlen($Postal_Code); // ความยาวของรหัสไปรษณีย์
			}
			
			if($number_Postal_Code == 0)
			{
				$tag_pa_08 = "0803***";
			}
			else
			{
				// ทำให้จำนวนมี 2 หลัก
				if(strlen($number_Postal_Code) < 2)
				{
					$number_Postal_Code = "0".$number_Postal_Code;
				}
					
				$tag_pa_08 = "08".$number_Postal_Code.$Postal_Code;
			}
		//---
		
		//--- tag 11 (Address Type)
			$tag_pa_11 = "11011";
		//---
		
		$text_PA = $segment_tag_pa.$tag_pa_01.$tag_pa_02.$tag_pa_03.$tag_pa_04.$tag_pa_05.$tag_pa_06.$tag_pa_07.$tag_pa_08.$tag_pa_11;
		
		
		
		//-------------------- TL --------------------//
	
		//--- contractID
			$contractID = trim($ncb["current_or_new_account_number"]); // เลขที่สัญญา
		//---
		
		//--- ถ้าเคยปิดบัญชีไปเมื่อเดือนก่อนหน้าแล้ว ไม่ต้องเอามาแสดงอีก คือให้หาคนต่อไปเลย คนนี้ไม่เอาแล้ว
			$sql_chk_close = pg_query("SELECT * FROM \"thcap_ncb_statement\" WHERE \"asOfDate\" < '$date' AND \"contractID\" = '$contractID' AND \"amountOwn\" <= '0' ");
			$row_chk_close = pg_num_rows($sql_chk_close);
			if($row_chk_close > 0)
			{
				$row_main = $row_main - 1;
				continue;
			}
		//---
		
		//--- database postgres
			$sql_TL = pg_query("SELECT * FROM \"thcap_ncb_statement\" WHERE \"asOfDate\" = '$date' AND \"contractID\" = '$contractID' ");
			while($resTL = pg_fetch_array($sql_TL))
			{
				$Date_Of_Last_Payment = trim($resTL["lastPayDate"]); // tag 09 (วันที่จ่ายครั้งล่าสุด)
				$amount_owed_or_principal_or_credit_use = trim($resTL["amountOwn"]); // tag 13 เงินทั้งหมดที่ลูกค้าเป็นหนี้ (เงินต้นรวมดอกเบี้ย ณ As Of Date)
				$amount_past_due = trim($resTL["amountRemain"]); // tag 14 จำนวนยอดค้างผ่อนชำระ
				$defaultDate = trim($resTL["defaultDate"]); // tag 14
				$amountOwn = trim($resTL["amountOwn"]); // tag 23
			}
			
			$sql_money = pg_query("SELECT * FROM \"thcap_mg_contract\" WHERE \"contractID\" = '$contractID' ");
			while($resmoney = pg_fetch_array($sql_money))
			{
				$installment_amount = trim($resmoney["conMinPay"]); // tag 21 ยอดจ่ายขั้นต่ำ
				$credit_limit_or_original_loan_amount = trim($resmoney["conLoanAmt"]); // tag 12
				
				$Date_Account_Opened = trim($resmoney["conDate"]); // tag 08 วันที่เปิดบัญชี
				$installment_number_of_payments = trim($resmoney["conTerm"]); // tag 22 จำนวนงวด
			}
		//---
		
		//--- TL
			$segment_tag_tl = "TL04T001";
		//---
		
		//--- tag 01 (current/new member code)
			$tag_tl_01 = "0110CC11030000";
		//---
		
		//--- tag 02 (current/new member name)
			$tag_tl_02 = "0205THCAP";
		//---
		
		//--- tag 03 (current/new account number)
			$current_or_new_account_number = trim($ncb["current_or_new_account_number"]); // เลขที่สัญญา
			$number_current_or_new_account_number = utf8_strlen($current_or_new_account_number); // ความยาวของเลขที่สัญญา
			
			$tag_tl_03 = "03".$number_current_or_new_account_number.$current_or_new_account_number;
		//---
		
		//--- tag 04 (Account Type)
			$tag_tl_04 = "040299";
		//---
		
		//--- tag 05 (ownership indicator)
			$ownership_indicator = trim($ncb["ownership_indicator"]);
			
			$tag_tl_05 = "0501".$ownership_indicator;
		//---
		
		//--- tag 06 (currency code)
			$tag_tl_06 = "0603THB";
		//---
		
		//--- tag 07 (Future Use)
			$tag_tl_07 = "07011";
		//---
		
		//--- tag 08 (Date Account Opened)
			//$Date_Account_Opened = trim($ncb["contract_loans_startdate"]); // เอาจาก database postgres
			$Date_Account_Opened = str_replace("-","",$Date_Account_Opened);
			$Date_Account_Opened = substr($Date_Account_Opened,0,8);
			
			$tag_tl_08 = "0808".$Date_Account_Opened;
		//---
		
		//--- tag 09 (Date Of Last Payment)
			if($Date_Of_Last_Payment != "")
			{
				$Date_Of_Last_Payment = str_replace("-","",$Date_Of_Last_Payment);
				$tag_tl_09 = "0908".$Date_Of_Last_Payment;
			}
			else
			{
				$tag_tl_09 = "090819000101";
			}
		//---
		
		//--- tag 10 (Date Account Closed)
			//$Date_Account_Closed = trim($ncb["account_status_default_code"]);
			if($amountOwn > 0){$Date_Account_Closed = "10";} // ถ้ามากกว่า 0 แสดงว่ายังจ่ายไม่ครบ
			elseif($amountOwn <= 0){$Date_Account_Closed = "11";} // ถ้าใน postgres เป็น 0.00 แสดงว่าปิดบัญชีไปแล้ว
			
			if($Date_Account_Closed == "11")
			{
				$Date_Of_Last_Payment = str_replace("-","",$Date_Of_Last_Payment);
				$tag_tl_10 = "1008".$Date_Of_Last_Payment;
			}
			else
			{
				$tag_tl_10 = "";
			}
		//---
		
		//--- tag 11 (As Of Date)
			$As_Of_Date = str_replace("-","",$date);
			$tag_tl_11 = "1108".$As_Of_Date;
		//---
		
		//-- tag 12 (Credit Limit/Original Loan Amount)
			//$credit_limit_or_original_loan_amount = trim($ncb["appv_credit_money"]); // จำนวนเงินขอกู้  // ใช้ pg แทน
			$credit_limit_or_original_loan_amount = substr($credit_limit_or_original_loan_amount,0,strlen($credit_limit_or_original_loan_amount) - 3);
			$number_credit_limit_or_original_loan_amount = utf8_strlen($credit_limit_or_original_loan_amount);
			// ทำให้จำนวนมี 2 หลัก
			if(strlen($number_credit_limit_or_original_loan_amount) < 2)
			{
				$number_credit_limit_or_original_loan_amount = "0".$number_credit_limit_or_original_loan_amount;
			}
			
			$tag_tl_12 = "12".$number_credit_limit_or_original_loan_amount.$credit_limit_or_original_loan_amount;
		//---
		
		//--- tag 13 (Amount Owed/Principal/Credit Use) เงินทั้งหมดที่ลูกค้าเป็นหนี้ (เงินต้นรวมดอกเบี้ย ณ As Of Date)
			$amount_owed_or_principal_or_credit_use = substr($amount_owed_or_principal_or_credit_use,0,strlen($amount_owed_or_principal_or_credit_use) - 3);
			$number_amount_owed_or_principal_or_credit_use = utf8_strlen($amount_owed_or_principal_or_credit_use);
			// ทำให้จำนวนมี 2 หลัก
			if(strlen($number_amount_owed_or_principal_or_credit_use) < 2)
			{
				$number_amount_owed_or_principal_or_credit_use = "0".$number_amount_owed_or_principal_or_credit_use;
			}
			
			$tag_tl_13 = "13".$number_amount_owed_or_principal_or_credit_use.$amount_owed_or_principal_or_credit_use;
		//---
		
		//--- tag 14 (Amount Past Due) จำนวนยอดค้างผ่อนชำระ
			$amount_past_due = substr($amount_past_due,0,strlen($amount_past_due) - 3);
			$number_amount_past_due = utf8_strlen($amount_past_due);
			// ทำให้จำนวนมี 2 หลัก
			if(strlen($number_amount_past_due) < 2)
			{
				$number_amount_past_due = "0".$number_amount_past_due;
			}
			
			$tag_tl_14 = "14".$number_amount_past_due.$amount_past_due;
		//---
		
		//--- tag 15 (Number Of Days Past Due/Delinquency Status)
			$sql_Due = pg_query("SELECT (\"asOfDate\" - \"defaultDate\") as daypast FROM \"thcap_ncb_statement\" WHERE \"asOfDate\" = '$date' AND \"contractID\" = '$contractID' ");
			while($resDue = pg_fetch_array($sql_Due))
			{
				$daypast = trim($resDue["daypast"]);
			}
			if ($daypast >= 0 && $daypast <= 30){$tag_tl_15 = "1503000";}
			else if ($daypast >= 31 && $daypast <= 60){$tag_tl_15 = "1503001";}
			else if ($daypast >= 61 && $daypast <= 90){$tag_tl_15 = "1503002";}
			else if ($daypast >= 91 && $daypast <= 120){$tag_tl_15 = "1503003";}
			else if ($daypast >= 121 && $daypast <= 150){$tag_tl_15 = "1503004";}
			else if ($daypast >= 151 && $daypast <= 180){$tag_tl_15 = "1503005";}
			else if ($daypast >= 181 && $daypast <= 210){$tag_tl_15 = "1503006";}
			else if ($daypast >= 211 && $daypast <= 240){$tag_tl_15 = "1503007";}
			else if ($daypast >= 241 && $daypast <= 270){$tag_tl_15 = "1503008";}
			else if ($daypast >= 271 && $daypast <= 300){$tag_tl_15 = "1503009";}
			else{$tag_tl_15 = "1503__F";}
		//---
		
		//--- tag 19 (Default Date)
			if($defaultDate != "")
			{
				$defaultDate = str_replace("-","",$defaultDate);
				$tag_tl_19 = "1908".$defaultDate;
			}
			else
			{
				$tag_tl_19 = "";
			}
		//---
		
		//--- tag 20 (Installment Frequency)
			$tag_tl_20 = "20013";
		//---
		
		//--- tag 21 (Installment Amount)
			//$installment_amount = trim($ncb["contract_loans_minpay"]); // ค่างวดขั้นต่ำ  // ใช้ pg แทน
			$installment_amount = substr($installment_amount,0,strlen($installment_amount) - 3);
			$number_installment_amount = utf8_strlen($installment_amount); // ความยาวของค่างวดขั้นต่ำ
			// ทำให้จำนวนมี 2 หลัก
			if(strlen($number_installment_amount) < 2)
			{
				$number_installment_amount = "0".$number_installment_amount;
			}
			
			$tag_tl_21 = "21".$number_installment_amount.$installment_amount;
		//---
		
		//--- tag 22 (Installment Number Of Payments)
			//$installment_number_of_payments = trim($ncb["appv_month"]); // จำนวนงวดผ่อนชำระ
			//$installment_number_of_payments = substr($installment_number_of_payments,0,strlen($installment_number_of_payments) - 3); // ใช้จาก pg ไม่ต้องตัดจุด เพราะไม่มีอยู่แล้ว
			$number_installment_number_of_payments = utf8_strlen($installment_number_of_payments); // ความยาว
			// ทำให้จำนวนมี 2 หลัก
			if(strlen($number_installment_number_of_payments) < 2)
			{
				$number_installment_number_of_payments = "0".$number_installment_number_of_payments;
			}
			
			$tag_tl_22 = "22".$number_installment_number_of_payments.$installment_number_of_payments;
		//---
		
		//--- tag 23 (Account Status)
			//$account_status = trim($ncb["account_status_default_code"]); // สถานะบัญชีสัญญาเงินกู้
			if($amountOwn > 0)
			{
				if($defaultDate != "")
				{
					$numdaydebt = ceil((strtotime($date) - strtotime($defaultDate))/(60*60*24)); // จำนวนวันค้างชำระ
					if($numdaydebt > 90)
					{
						$account_status = "20"; // ถ้าค้างชำระเกิด 90 วัน
					}
					else
					{
						$account_status = "10"; // ถ้าเป็นสัญญาปกติ
					}
				}
				else
				{
					$account_status = "10";
				}
			}
			elseif($amountOwn <= 0){$account_status = "11";} // ถ้าใน postgres เป็น 0.00 แสดงว่าปิดบัญชีไปแล้ว
			$tag_tl_23 = "2302".$account_status;
		//---
		
		//--- tag 39 (Number of coborrower)
			$number_of_co_borrower = trim($ncb["number_of_co_borrower"]);
			// ทำให้จำนวนมี 2 หลัก
			if(strlen($number_of_co_borrower) < 2)
			{
				$number_of_co_borrower = "0".$number_of_co_borrower;
			}
			
			$tag_tl_39 = "3902".$number_of_co_borrower;
		//---
		
		$text_TL = $segment_tag_tl.$tag_tl_01.$tag_tl_02.$tag_tl_03.$tag_tl_04.$tag_tl_05.$tag_tl_06.$tag_tl_07.$tag_tl_08.$tag_tl_09.$tag_tl_10.$tag_tl_11.$tag_tl_12.$tag_tl_13.$tag_tl_14.$tag_tl_15.$tag_tl_19.$tag_tl_20;
		$text_TL = $text_TL.$tag_tl_21.$tag_tl_22.$tag_tl_23.$tag_tl_39;
	
	
		//++++++++++ GEN NCB ++++++++++//
		$gen_ncb = $text_PN.$text_ID.$text_PA.$text_TL."ES02**"."<br>";
		$text_ncb = $text_ncb.$gen_ncb;
	}
}
*/
//--------------------------------------- จบการหาข้อมูลส่วนที่เหลือ


//---ไฟล์ที่จะนำไปใช้
$TUDF = TUDF($month , $year , $row_main); // หัวของ NCB
$text_ncb = $TUDF."<br>".$text_ncb."TRLR"; // ใช้สำหรับ html
$textarea_ncb = str_replace("<br>","\n",$text_ncb); // ใช้สำหรับ textarea
$save_ncb = str_replace("<br>","\r\n",$text_ncb); // ใช้สำหรับ save file
?>

<textarea cols="550" rows="<?php echo $row_main+2; ?>"><?php echo $textarea_ncb; ?></textarea>

<form name="frm2" method="post" action="gen_ncb.php">
<input type="hidden" name="month" value="<?php echo $month; ?>">
<input type="hidden" name="year" value="<?php echo $year; ?>">
<input type="hidden" name="savefile" value="<?php echo $save_ncb; ?>">
<?php echo $typeForSave; ?>
<input type="hidden" name="click" value="yes">
<input type="hidden" name="save" value="yes">
<input type="submit" value="SAVE">
</form>

<?php
}
?>

</body>
</html>