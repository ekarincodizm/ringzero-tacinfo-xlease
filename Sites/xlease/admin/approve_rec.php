<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
include("../config/config.php");
$select_date=pg_escape_string($_POST['select_date']);
if(empty($select_date)){
    $cdate=date("Y-m-d");
}else{ 
    $cdate = pg_escape_string($_POST['select_date']);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
var wnd = new Array();
function popU(U,N,T){
    wnd[N] = window.open(U, N, T);
}
function confirmappv(no){
	if(no=='1'){
		if(confirm('ยืนยันการอนุมัติ')==true){
			return true;
		}else{return false;}
	}
	else if(no=='0'){
		if(confirm('ยืนยันการไม่อนุมัติ!!')==true){
			return true;
		}else{return false;}
	}else{	
		return false;
	}
} 
$(function(){
    $(window).bind("beforeunload",function(event){
        window.opener.$('div#div_admin_menu').load('list_admin_menu.php');
    });
});
</script>    

</head>
<body>
 
<table width="1100" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>

<div class="wrapper">

<div style="float:left"></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both"></div>
<fieldset><legend><B>อนุมัติยกเลิกใบเสร็จ</B></legend>

<div align="center">
<form name="frm_app_cc" method="post" action="">
<b>เลือกวันที่</b>
<input name="select_date" type="text" readonly="true" value="<?php echo $cdate; ?>"/>
<input name="button2" type="button" onclick="displayCalendar(document.frm_app_cc.select_date,'yyyy-mm-dd',this)" value="ปฏิทิน" /><input type="submit" value="ค้นหา" />
</form>
</div>

<div style="font-weight:bold;">รายการขอยกเลิกใบเสร็จ วันที่ <?php echo $cdate; ?></div>
<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
      <th>เลขที่ใบเสร็จ</th>
      <th>เลขที่ใบเสร็จ</th>
      <th>รหัสยกเลิกใบเสร็จ</th>
      <th>จำนวนเงิน</th>
      <th>เหตุผล</th>
      <th>PostID</th>
      <th>สถานะ</td>
	  <th>สถานะการจ่ายเิงิน</th>
	  <th>ผู้ทำรายการ</th>
	  <th>ผู้อนุมัติ</th>
      <th>อนุมัติ</th>
	  <th>ไม่อนุมัติ</th>
   </tr>

<?php
$vat = 0;
$qry_cc=pg_query("select *,b.\"fullname\" as postuser,c.\"fullname\" as approveuser from \"CancelReceipt\" a
left join \"Vfuser\" b on a.\"postuser\"=b.\"id_user\"
left join \"Vfuser\" c on a.\"approveuser\"=c.\"id_user\"
WHERE c_date = '$cdate' ORDER BY c_receipt ASC");
while($res_cc=pg_fetch_array($qry_cc)){
    $ref_receipt = $res_cc["ref_receipt"];
    $c_receipt = $res_cc["c_receipt"];
    $c_money = $res_cc["c_money"];
    $c_memo = $res_cc["c_memo"];
    $admin_approve = $res_cc["admin_approve"];
	$statusApprove = $res_cc["statusApprove"];
	$postuser = $res_cc["postuser"];
	$approveuser = $res_cc["approveuser"];
    
    if(substr($c_memo,0,2) == "@#"){
        $file = "approve_cancel_fail_recprocess.php";
    }else{
        $file = "approve_cancel_recprocess.php";
    }
	if($admin_approve=='f'){ //ถ้ารายการไหนยังไม่อนุมัติให้หา postid
		$qry_dt=pg_query("select \"PostID\" from \"DetailTranpay\" WHERE \"ReceiptNo\"='$ref_receipt' AND \"Cancel\"='FALSE' ");
		if($res_dt=pg_fetch_array($qry_dt)){
			$PostID = $res_dt["PostID"];
		}else{
			$PostID = "-";
		}
	}else{ //กรณีรายการที่อนุมัติแล้วให้ postid=-
		$PostID = "-";
	}


if($admin_approve == "t"){
    echo "<tr class=\"ered\">";
}else{
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
}
?>
        <td align="center"><?php echo $ref_receipt; ?></td>
        <td align="center" onclick="javascript:popU('../post/frm_viewcuspayment.php?idno_names=<?php echo $res_cc["IDNO"];?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')" style="cursor:pointer"><u><?php echo $res_cc["IDNO"]; ?></u></td>
        <td align="center"><?php echo $c_receipt; ?></td>
        <td align="right"><?php echo number_format($c_money,2); ?></td>
        <td align="left"><?php echo $c_memo; ?></td>
        <td align="left"><?php echo $PostID; ?></td>
        <td align="center">
			<?php
			if($admin_approve == "t" and $statusApprove=="t"){
				echo "อนุมัติ";
			}else if($admin_approve == "t" and $statusApprove=="f"){
				echo "ไม่อนุมัิติ";
			}else{
				echo "รอการอนุมัติ";
			}
			?>
        </td>
		<td align="left">
			<?php 
			$query_return=pg_query("select return_to from \"CancelReceipt\" where c_receipt='$c_receipt'");
			if($res_re=pg_fetch_array($query_return)){
				$return_to = trim($res_re["return_to"]);
			}

			if($return_to==""){
				echo "เข้าเป็นเงินฝาก";
			}else{
				$returnpay=substr($return_to,2,1);
				if($returnpay=="K"){
					echo "เข้าเป็นเงินฝาก";
				}else{
					echo "จ่ายเป็นเงินสด";
				}
			}
			?>
		</td>
		<td><?php echo $postuser;?></td>
		<td><?php echo $approveuser;?></td>
        <td align="center">
		<?php $name="my".$i;?>
		<form name="<?php echo $name; ?>" method="post" action="<?php echo $file; ?>">
			<input type="hidden" name="cid" id="cid" value="<?php echo $c_receipt; ?>">
			<input type="hidden" name="rid" id="rid" value="<?php echo $ref_receipt; ?>">
			<input type="hidden" name="memo" id="memo" value="<?php echo $c_memo; ?>">
			
			<input hidden name="f_appv" value="อนุมัติ" type="submit"/>
			<input hidden name="f_unappv" value="ไม่อนุมัติ" type="submit"/>	
		
<?php

			if($PostID == "-"){
				if($admin_approve == "t"){
				echo "-";
				}else{
				//echo "<a href=\"$file?cid=$c_receipt&rid=$ref_receipt&memo=$c_memo&statusapp=1\" //title=\"อนุมัติรายการนี้ $ref_receipt\"><u>อนุมัติ</u></a>";
					echo "<a href =\"#\" style=\"cursor:pointer;\"  onclick=\" 
					document.forms['$name'].f_appv.click();document.forms['$name'].submit();return false; \">
					<font color=\"#0000FF\"><u>อนุมัติ</u></font></a>";
			}
			}else{
				if($old_postid != $PostID){
					if($admin_approve == "t"){
						echo "-";
					}else{
					//echo "<a href=\"$file?cid=$c_receipt&rid=$ref_receipt&memo=$c_memo&statusapp=1\" //title=\"อนุมัติรายการนี้ $ref_receipt\"><u>อนุมัติ</u></a>";
						echo "<a href =\"#\" style=\"cursor:pointer;\"  onclick=\" 
						document.forms['$name'].f_appv.click();document.forms['$name'].submit();return false; \">
						<font color=\"#0000FF\"><u>อนุมัติ</u></font></a>";
					}
				}else{
					echo "~";
				}
			}
?>
		</td>
		<td align="center">
			<?php

			if($PostID == "-"){
				if($admin_approve == "t"){
					echo "-";
				}else{
					/*echo "<a href=\"$file?cid=$c_receipt&rid=$ref_receipt&memo=$c_memo&statusapp=2\" title=\"ไม่อนุมัติรายการนี้ $ref_receipt\"><u>ไม่อนุมัติ</u></a>";*/
					echo "<a href =\"#\" style=\"cursor:pointer;\"  onclick=\" 
					document.forms['$name'].f_unappv.click();document.forms['$name'].submit();return false; \">
					<font color=\"#0000FF\"><u>ไม่อนุมัติ</u></font></a>";
				}
			}else{
				if($old_postid != $PostID){
					if($admin_approve == "t"){
						echo "-";
					}else{
						/*echo "<a href=\"$file?cid=$c_receipt&rid=$ref_receipt&memo=$c_memo&statusapp=2\" title=\"ไม่อนุมัติรายการนี้ $ref_receipt\"><u>ไม่อนุมัติ</u></a>";*/
						echo "<a href =\"#\" style=\"cursor:pointer;\"  onclick=\" 
						document.forms['$name'].f_unappv.click();document.forms['$name'].submit();return false; \">
						<font color=\"#0000FF\"><u>ไม่อนุมัติ</u></font></a>";
					}
				}else{
					echo "~";
				}
			}
			?>
        </td>
		</form> 
    </tr>
<?php

$old_postid = $PostID;

}
?>
<tr><td colspan="8"><font color="red"><b>* ระวัง : สถานะการจ่ายเงินที่เป็นการจ่ายคืนเงินรับฝาก จะเป็นการลบใบเสร็จ และเงินในใบเสร็จจะไปอยู่ในเงินรับฝากแทน หากเป็นเงินที่ไม่มีจริงสถานะการจ่ายเงินต้องเป็น "จ่ายเป็นเงินสด" เท่านั้น</b></font></td></tr>
</table>

</fieldset>

</div>
        </td>
    </tr>
</table>

</body>
</html>