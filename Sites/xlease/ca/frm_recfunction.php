<?php
session_start();
header('Cache-Control: no-cache');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-cache');
header('Pragma: no-cache');
include("../config/config.php");
$resid=$_SESSION["av_iduser"];
$c_code=$_SESSION["session_company_code"];
$datenow = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
//ACTIONLOG
	$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$resid', '(TAL) ทำการรับชำระเงิน', '$datenow')");
//ACTIONLOG---	

//$c_code="THA";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $_SESSION["session_company_name"]; ?> co.,ltd</title>
<style>
#warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(240, 240, 240);
	padding: 5px;
	border: rgb(128, 128, 128) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
	}
</style>
<script language="javascript" type="text/javascript">
function btn_link_cash() 
{
   var rids = document.getElementById("s_rid").value;
   window.open('link_recprint_<?php echo $c_code; ?>.php?rid='+ rids);  //ส่งไปพิมพ์

  // parent.location.href='postlog.php'; //ไปหน้า postlog
}

function btn_all_rec() 
{
   var pids = document.getElementById("p_id").value;
   window.open('frm_recprint_acc_ca_<?php echo $c_code; ?>.php?pid='+ pids);  //ส่งไปพิมพ์ทั้งหมด

  // parent.location.href='postlog.php'; //ไปหน้า postlog
}




function btn_link_chq() 
{
   var pids = document.getElementById("p_rid").value;
   window.open('link_chqprint_pdf_<?php echo $c_code; ?>.php?pid='+ pids);  //ส่งไปพิมพ์

  // parent.location.href='postlog.php'; //ไปหน้า postlog
}
</script>
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

<body style="background-color:#ffffff; margin-top:0px;" onload="setfocus();">

<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"> <?php echo $_SESSION["session_company_name"]; ?></h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="warppage"  style="width:800px; text-align:left; margin-left:auto; margin-right:auto;">
รับชำระเงิน
<hr />
<div class="style5" style="width:auto; height:100px; padding-left:0px;">
<?php
	$postid=pg_escape_string($_GET["pID"]);
	$vType=pg_escape_string($_GET["PayType"]);
	if($vType=="CA"){ 
		$cre_fr=pg_query("select accept_cash_postlog('$postid','$resid')");
		$resfr=pg_fetch_result($cre_fr,0); 
		?> 
		<table width="800" border="0" style="background-color:#CCCCCC;" cellpadding="1" cellspacing="1">
		<tr style="background-color:#DDE6B7;">
			<td width="125" style="padding-left:5px;">Rec_id</td>
			<td width="144" style="padding-left:5px;">TypePay</td>
			<td width="187" style="padding-left:5px;">Pay</td>
			<td width="123" style="padding-left:5px; text-align:center;">AmtPay</td>
			<td width="205" style="padding-left:5px;">พิมพ์ทั้งหมด <button onclick="btn_all_rec();">Print ALL</button></td>
		</tr>
		<?php 
		$qry_listfr=pg_query("select B.\"refreceipt\", B.\"TypePay\", B.\"IDNO\", A.\"paytype\", B.\"AmtPay\"
		from \"PostLog\" A 
		LEFT OUTER JOIN \"FCash\" B on B.\"PostID\"=A.\"PostID\" WHERE  B.\"PostID\"='$postid' ");
		while($res_frid=pg_fetch_array($qry_listfr)){
			$rids=$res_frid["refreceipt"];	 
			$typep=$res_frid["TypePay"];
			$idno=$res_frid["IDNO"];
				
			$q_pay=pg_query("select \"TypeID\",\"TName\" from \"TypePay\" WHERE \"TypeID\"=$typep ");
			$res_pay=pg_fetch_array($q_pay);		
			?>
			<tr style="background-color:#FFFFFF;">
				<td style="padding-left:5px;"><?php echo $res_frid["refreceipt"]; ?></td>
				<td style="padding-left:5px;"><?php echo $res_frid["paytype"]; ?></td>
				<td style="padding-left:5px;"><?php echo $res_pay["TName"]; ?></td>
				<td style="text-align:right; padding-right:5px;"><?php echo number_format($res_frid["AmtPay"],2); ?></td>
					<input type="hidden" id="s_rid" name="s_rid" value="<?php echo $rids; ?>"  />
					<input type="hidden" id="p_id" name="p_id" value="<?php echo $postid; ?>"  />
				<td style="text-align:center; padding-top:3px;">
					<span class="style5" style="width:auto; height:100px; padding-left:0px;"><a href="frm_recprint_<?php echo $c_code; ?>.php?id=<?php echo $rids; ?>&idno=<?php echo $idno;?>" target="_blank"><img src="icoPrint.png" border="0" title="
					<?php echo $res_pay["TName"]." ".$rids; ?>" /></a></span>
				</td>
			</tr>
		<?php
        }
		?>
		<tr style="background-color:#DDE6B7;">
			<td colspan="5"><button onclick="window.location='postlog.php'">กลับไปทำรายการรับเงิน</button></td>
		</tr>
		</table> 		 
	<?php
	}else if($vType=="TC"){ 
		$cre_fr=pg_query("select accept_tac_postlog('$postid','$resid')");
		$resfr=pg_fetch_result($cre_fr,0); 
		?> 
		<table width="800" border="0" style="background-color:#CCCCCC;" cellpadding="1" cellspacing="1">
		<tr style="background-color:#DDE6B7;">
			<td width="125" style="padding-left:5px;">Rec_id</td>
			<td width="144" style="padding-left:5px;">TypePay</td>
			<td width="187" style="padding-left:5px;">Pay</td>
			<td width="123" style="padding-left:5px; text-align:center;">AmtPay</td>
			<td width="205" style="padding-left:5px;">พิมพ์ทั้งหมด <button onclick="btn_all_rec();">Print ALL</button></td>
		</tr>
		<?php 
		$qry_listfr=pg_query("select B.\"refreceipt\", B.\"TypePay\", B.\"COID\", A.\"paytype\", B.\"AmtPay\"
		from \"PostLog\" A 
		LEFT OUTER JOIN \"FTACCheque\" B on B.\"PostID\"=A.\"PostID\" WHERE  B.\"PostID\"='$postid' ");
		while($res_frid=pg_fetch_array($qry_listfr)){
		    $rids=$res_frid["refreceipt"];	 
			$typep=$res_frid["TypePay"];
			$idno=$res_frid["COID"];
				
			$q_pay=pg_query("select \"TypeID\",\"TName\" from \"TypePay\" WHERE \"TypeID\"=$typep ");
			$res_pay=pg_fetch_array($q_pay);		
			?>
			<tr style="background-color:#FFFFFF;">
				<td style="padding-left:5px;"><?php echo $res_frid["refreceipt"]; ?></td>
				<td style="padding-left:5px;"><?php echo $res_frid["paytype"]; ?></td>
				<td style="padding-left:5px;"><?php echo $res_pay["TName"]; ?></td>
				<td style="text-align:right; padding-right:5px;"><?php echo number_format($res_frid["AmtPay"],2); ?></td>
					<input type="hidden" id="s_rid" name="s_rid" value="<?php echo $rids; ?>"  />
					<input type="hidden" id="p_id" name="p_id" value="<?php echo $postid; ?>"  />
				<td style="text-align:center; padding-top:3px;"><span class="style5" style="width:auto; height:100px; padding-left:0px;"><a href="frm_recprint_<?php echo $c_code; ?>.php?id=<?php echo $rids; ?>&idno=<?php echo $idno;?>" target="_blank"><img src="icoPrint.png" border="0" title="
				 <?php echo $res_pay["TName"]." ".$rids; ?>" /></a>
				</span></td>
			</tr>
			<?php
		}
		?>
		<tr style="background-color:#DDE6B7;">
				<td colspan="5"><button onclick="window.location='postlog.php'">กลับไปทำรายการรับเงิน</button></td>
			</tr>
		</table> 
		<?php
	}else if($vType=="TT"){
		$cre_fr=pg_query("select accept_tactr_postlog('$postid','$resid')");
		$resfr=pg_fetch_result($cre_fr,0);
		?> 
		<table width="800" border="0" style="background-color:#CCCCCC;" cellpadding="1" cellspacing="1">
			<tr style="background-color:#DDE6B7;">
				<td width="125" style="padding-left:5px;">Rec_id</td>
				<td width="144" style="padding-left:5px;">TypePay</td>
				<td width="187" style="padding-left:5px;">Pay</td>
				<td width="123" style="padding-left:5px; text-align:center;">AmtPay</td>
				<td width="205" style="padding-left:5px;">พิมพ์ทั้งหมด 
					<button onclick="btn_all_rec();">Print ALL</button></td>
			</tr>
			<?php 
			$qry_listfr=pg_query("select B.\"refreceipt\", B.\"TypePay\", B.\"COID\", A.\"paytype\", B.\"AmtPay\"
			from \"PostLog\" A 
	        LEFT OUTER JOIN \"FTACTran\" B on B.\"PostID\"=A.\"PostID\" WHERE  B.\"PostID\"='$postid' ");
			while($res_frid=pg_fetch_array($qry_listfr)){
				$rids=$res_frid["refreceipt"];	 
				$typep=$res_frid["TypePay"];
				$idno=$res_frid["COID"];
				
				$q_pay=pg_query("select \"TypeID\",\"TName\" from \"TypePay\" WHERE \"TypeID\"=$typep ");
				$res_pay=pg_fetch_array($q_pay);		
				?>
				<tr style="background-color:#FFFFFF;">
					<td style="padding-left:5px;"><?php echo $res_frid["refreceipt"]; ?></td>
					<td style="padding-left:5px;"><?php echo $res_frid["paytype"]; ?></td>
					<td style="padding-left:5px;"><?php echo $res_pay["TName"]; ?></td>
					<td style="text-align:right; padding-right:5px;"><?php echo number_format($res_frid["AmtPay"],2); ?></td>
						<input type="hidden" id="s_rid" name="s_rid" value="<?php echo $rids; ?>"  />
						<input type="hidden" id="p_id" name="p_id" value="<?php echo $postid; ?>"  />
					<td style="text-align:center; padding-top:3px;"><span class="style5" style="width:auto; height:100px; padding-left:0px;"><a href="frm_recprint_<?php echo $c_code; ?>.php?id=<?php echo $rids; ?>&idno=<?php echo $idno;?>" target="_blank"><img src="icoPrint.png" border="0" title="
					<?php echo $res_pay["TName"]." ".$rids; ?>" /></a>
					</span></td>
				</tr>
				<?php
			}
			?>
			<tr style="background-color:#DDE6B7;">
				<td colspan="5"><button onclick="window.location='postlog.php'">กลับไปทำรายการรับเงิน</button></td>
			</tr>
		</table> 		 
	<?php
	}else{ 
		$cre_fq=pg_query("select accept_cheque_postlog('$postid','$resid')");
		$resfq=pg_fetch_result($cre_fq,0);		 
		?>
		<table width="800" border="0" cellpadding="1"  cellspacing="1" style="background-color:#999999;">
			<tr>
				<td height="24" colspan="6" style="background-color:#FFFFCC; text-align:right; padding-right:10px;"><input type="hidden" id="p_rid" name="p_rid" value="<?php echo $postid; ?>"  />พิมพ์ใบเสร็จรับเงิน &nbsp;<input name="button" type="button" onclick="btn_link_chq();"   value="PRINT" /></td>
			</tr>
			<tr style="background-color:#DFDA79">
				<td width="52" height="24">No.</td>
				<td width="120" style="padding-left:5px;">ChequeNo</td>
				<td width="175" style="padding-left:5px;">BankName</td>
				<td width="137" style="padding-left:5px;">BankBranch</td>
				<td width="93" style="padding-left:5px;">ReceiptDate</td>
				<td style="padding-left:5px;">AmtOnCheque</td>
			</tr>
			<?php 
			$n=0;
			$qry_listfq=pg_query("select B.\"ChequeNo\", B.\"BankName\", B.\"BankBranch\", B.\"ReceiptDate\", B.\"AmtOnCheque\"
			from \"PostLog\" A 
	        LEFT OUTER JOIN \"FCheque\" B on B.\"PostID\"=A.\"PostID\" WHERE  B.\"PostID\"='$postid' ");
			while($res_fq=pg_fetch_array($qry_listfq)){
				$n++;    	
				?>
				<tr style="background-color:#FFFFFF;">
					<td><?php echo $n; ?></td>
					<td style="padding-left:5px;"><?php echo $res_fq["ChequeNo"]; ?></td>
					<td style="padding-left:5px;"><?php echo $res_fq["BankName"]; ?></td>
					<td style="padding-left:5px;"><?php echo $res_fq["BankBranch"]; ?></td>
					<td style="padding-left:5px;"><?php echo $res_fq["ReceiptDate"]; ?></td>
					<td style="padding-left:5px;"><?php echo number_format($res_fq["AmtOnCheque"],2); ?><span class="style5" style="width:auto; height:100px; padding-left:0px;">   
					</span></td>
				</tr>
				<?php    
			} 
			?>
			<tr style="background-color:#FFFFFF;">
				<td height="24" colspan="6"><button onclick="window.location='postlog.php'">กลับไปทำรายการรับเงิน</button></td>
			</tr>
		</table>
	<?php
	}
	?>
	</div>
</div>
</div>
</body>
</html>
