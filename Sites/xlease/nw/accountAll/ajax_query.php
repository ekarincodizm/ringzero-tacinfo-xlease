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
		if($numrows>0){
			$show .= "<table>";
			$show .= "<tr style=\"font-weight:bold; background-color:#C4E1FF;\"><td width=\"15%\">รหัสบัญชี</td><td width=\"35%\">ชื่อบัญชี</td><td width=\"25%\">สถานะ</td><td width=\"25%\">ยอดเงิน</td></tr>";
	
			$qry_name2=pg_query("SELECT afd_accno,afd_drcr FROM account.\"all_accFormulaDetails\" WHERE afd_fmid = '$formula' ;");
			while($res_name2=pg_fetch_array($qry_name2)){
	
				$drcr = "";
				$accno = "";
				$accno = $res_name2["afd_accno"];
				$drcr = $res_name2["afd_drcr"]; if($drcr==1) $s_drcr = "Dr"; elseif($drcr==2) $s_drcr = "Cr";
    
				if(!empty($accno)){
					$qry_name3=pg_query("SELECT \"accBookID\", \"accBookName\" FROM account.\"V_all_accBook\" WHERE \"accBookserial\" = '$accno';");
					if($res_name3=pg_fetch_array($qry_name3)){
						$ac_BookID = $res_name3["accBookID"];
						$ac_name = $res_name3["accBookName"];
					}
        
					$show .= "<tr><td width=\"15%\">$ac_BookID</td>
					<td width=\"35%\">$ac_name</td>
					<td width=\"25%\">$s_drcr</td><td width=\"25%\">
					<input type=\"text\" id=\"text_money\" name=\"text_money[]\" OnKeyUp=\"JavaScript:getValueArray();\">
					<input type=\"hidden\" id=\"text_drcr\" name=\"text_drcr[]\" value=\"$drcr\">
					<input type=\"hidden\" name=\"text_accno[]\" value=\"$accno\">
					<input type=\"hidden\" name=\"text_ac_name[]\" value=\"$ac_name\">
					<input type=\"hidden\" name=\"text_ac_BookID[]\" value=\"$ac_BookID\">
					</td></tr>";
				}    
			}
			$show .= "</table>";
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