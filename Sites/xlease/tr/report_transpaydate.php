<?php
session_start();
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="file:///C|/wamp/www/av/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
<title><?php echo $_SESSION["session_company_name"]; ?></title>
<!-- InstanceEndEditable -->
<style type="text/css">
<!--
.style1 {
	font-family: Tahoma;
	font-size: medium;
}
.style3 {
    font-family: Tahoma;
	color: #ffffff;
	font-weight: bold;
	font-size: medium;
}
.style4 {
    font-family: Tahoma;
	color: #000000;
  }
  .style5 {
    font-family: Tahoma;
	color: #000000;
	font-size: medium;
  }

-->
</style>
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
</head>

<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4"> <?php echo $_SESSION["session_company_name"]; ?></h1>
	</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
	<div style="width:800px; margin-left:auto; margin-right:auto;text-align:right;"><input type="button" value="กลับ" onclick="window.location='frm_report_transpaydate.php'"></div>
	<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
		<div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;"><?php echo $_SESSION["session_company_name"]; ?></div>
		<div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
		<div class="style5" style="width:auto; height:100px; ">
			<form method="POST" name="form1" action="report_pdf_transpaydate.php" target="_blank"> 
			<table width="800" border="0" cellpadding="1" cellspacing="1" style="background-color:#CCCCCC;">  
			<?php 
			$m_amt=0; 
			if($_POST["s_bank"]==0){ //แสดงทุกธนาคาร
				$sql_bk=pg_query("select * from bankofcompany"); //แสดงรายชื่อธนาคารทุกรายชื่อ
			}else{
				$sql_bk=pg_query("select * from bankofcompany where \"bankno\" = '$_POST[s_bank]'"); //กรณีเลือกธนาคาร
			}
			while($res_bk=pg_fetch_array($sql_bk)){	   
				$re_bk=$res_bk["bankno"];  
				?>		   
				<tr style="background-color:#DDE6B7">
					<td colspan="8"> ธนาคาร <?php echo $res_bk["bankname"]; ?></td>
				</tr>
				<tr style="background-color:#DDE6B7">
					<td width="34">No.</td>
					<td width="96"><div align="center">IDNO</div></td>
					<td width="194">ชื่อ-นามสกุล</td>
					<td width="128"><div align="center">วันที่โอน/เวลา</div></td>
					<td width="53"><div align="center">Post asa</div></td>
					<td width="72"><div align="center">post on date </div></td>
					<td width="77"><div align="center">ยอดโอน</div></td>
					<td><div align="center">PostID</div></td>
				</tr>
				<?php
				$io=1;
				$m_amt=0;
				$srt_sql=pg_query("select A.*,B.* from \"TranPay\" A LEFT OUTER JOIN  bankofcompany B on B.bankno=A.bank_no 
									WHERE (A.tr_date='$_POST[qryDate]') AND (B.bankno='$re_bk') AND A.\"branch_id\" = '$_POST[branch]'");
				while($res_m=pg_fetch_array($srt_sql)){
					if($res_m["post_on_asa_sys"]=='f'){
						$stp="wait";
					}else{
						$stp="finsh";
					}
		
					if(empty($res_m["post_on_date"])){
						$st_s="wait";
					}else{
						$st_s=$res_m["post_on_date"];
					}		   	
					?> 
	 
					<tr style="background-color:#F5F7E1;">
						<td><?php echo $io++; ?></td>
						<td style="text-align:center;"><?php echo $res_m["post_to_idno"]; ?></td>
						<td style="text-align:left; padding:3px;"><?php echo $res_m["ref_name"]; ?></td>
						<td style="text-align:center; padding:3px;"><?php echo $res_m["tr_date"]." / ".$res_m["tr_time"]; ?></td>
						<td style="text-align:center; padding:3px;"><?php echo $stp; ?></td>
						<td style="text-align:center; padding:3px;"><?php echo $st_s; ?></td>
						<td style="text-align:right; padding:3px;"><?php echo number_format($res_m["amt"],2); ?></td>
						<td style="text-align:center; padding:3px;"><?php echo $res_m["PostID"]; ?></td>
					</tr> 
					<?php
					$m_amt=$m_amt+$res_m["amt"];
				} //end while	 
				?>
				<tr style="background-color:#FFFFFF">
					<td colspan="6" style="text-align:right; padding-right:5px;">รวมยอด</td>
					<td style="background-color:#CEFF9D; text-align:right; padding-right:3px;"><?php echo number_format($m_amt,2); ?></td>
					<td>&nbsp;</td>
				</tr>
  
			<?php
			} // end while
			?>
			<tr>
				<td colspan="8"><div align="center">
				<input type="hidden" name="sb" value=<?php echo $_POST["s_bank"];?>>
				<input type="hidden" name="dd" value=<?php echo $_POST["qryDate"];?>>
				<input type="hidden" name="branch" value=<?php echo $_POST[branch];?>>
				<input type="submit" value="Export PDF">
				</td>
			</tr>     
			</table>
			</form>
		</div>
	</div>
</div>
</body>
</html>
