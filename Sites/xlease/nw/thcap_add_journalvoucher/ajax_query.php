<?php
@ini_set('display_errors', '1');
include("../../config/config.php");

header ("Content-type: text/html; charset=utf-8");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0

$formula = trim(pg_escape_string($_POST["formula"]));

if(!empty($formula)){
	if(is_numeric($formula)){
		$sql=pg_query("SELECT af_fmid FROM account.\"all_accFormula\" where \"af_useformula\" ='0' and \"af_fmid\" = '$formula'");
		$numrows = pg_num_rows($sql);
		if($numrows>0)
		{
			$i = 0;
			
			$qry_name2=pg_query("SELECT afd_accno,afd_drcr FROM account.\"all_accFormulaDetails\" WHERE afd_fmid = '$formula' ;");
			while($res_name2=pg_fetch_array($qry_name2))
			{
				$drcr = "";
				$accno = "";
				$accno = $res_name2["afd_accno"];
				$drcr = $res_name2["afd_drcr"];
				
				$show .= "<div id=\"file1-$i\">";
				$show .= "<div align=\"left\">เลือกบัญชี <select name=\"text_accno[]\" id=\"text_accno\" onchange=\"getValueArray1(); \"><option value=\"\">- เลือก -</option>";
				
				$qry_name=pg_query("SELECT * FROM account.\"V_all_accBook\" ORDER BY \"accBookID\" ASC");
				while($res_name=pg_fetch_array($qry_name))
				{
					$AcSerial = $res_name["accBookserial"]; // รหัสบัญชี
					$AcID = $res_name["accBookID"]; // เลขที่บัญชี
					$AcName = $res_name["accBookName"]; // ชื่อบัญชี
					
					if($AcSerial == $accno)
					{
						$show .= "<option value=\"$AcSerial\" selected >$AcID : $AcName</option>";
					}
					else
					{
						$show .= "<option value=\"$AcSerial\">$AcID : $AcName</option>";
					}
				}
				
				$show .= "</select> สถานะ <select name=\"text_drcr[]\" id=\"text_drcr\" onchange=\"getValueArray1(); \"><option value=\"\">- เลือก -</option>";
				
				if($drcr == 1)
				{
					$show .= "<option value=\"1\" selected>Dr</option><option value=\"2\">Cr</option></select> ";
				}
				elseif($drcr == 2)
				{
					$show .= "<option value=\"1\">Dr</option><option value=\"2\" selected>Cr</option></select> ";
				}
				else
				{
					$show .= "<option value=\"1\">Dr</option><option value=\"2\">Cr</option></select> ";
				}
				
				$show .= "ยอดเงิน <input type=\"text\" id=\"text_money2\" name=\"text_money2[]\" size=\"10\" OnKeyUp=\"JavaScript:getValueArray1();\"\"> <span onClick=\"removeFile('file1-$i'), getValueArray1();\" style=\"cursor:pointer;\"><i>- ลบรายการนี้ -</i></span></div>";
				$show .= "</div>";
				
				$i++;
			}
			
			echo $show;
		}
		else{
			echo "ไม่พบสูตรที่ต้องการใช้ในระบบ";
		}
	}
	else{
		echo "ไม่พบสูตรที่ต้องการ กรุณาเลือกสูตรที่ต้องการใช้ใหม่";
	}
}
?>