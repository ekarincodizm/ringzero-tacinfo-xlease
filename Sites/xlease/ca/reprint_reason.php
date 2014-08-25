<?php
session_start();
include("../config/config.php");


$c_code=$_SESSION["session_company_code"];
$rec_id=pg_escape_string($_REQUEST["rec_id"]);
$idno=pg_escape_string($_REQUEST["idno"]);
$t=pg_escape_string($_REQUEST["t"]);
 ?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
function checkdata(){

	if(document.getElementById('reason_rep').value==""){
		alert("กรุณาระบุเหตุผล");
		document.getElementById('reason_rep').focus();
		return false;
	}else{
		ins_log();
	}
}
 function ins_log(){


 $cs = '1';
 $.post("reprint_log_api.php", { reason: document.getElementById('reason_rep').value,
 receipt_id: '<?php echo $rec_id; ?>'
 
  },
  function(data){
	  if(data==0){
   alert("บันทึกเรียบร้อยแล้ว");
   
   <?php if($t==1){ ?>
   window.location='frm_recid_confirm.php?id=<?php echo $rec_id; ?>';
  
   <?php }else if($t==2){ ?>
    window.location='frm_recprint_<?php echo $c_code; ?>.php?id=<?php echo $rec_id; ?>&idno=<?php echo $idno; ?>';
   <?php }else if($t==3){ ?>
    window.location='frm_recid_confirm.php?id=<?php echo $rec_id; ?>';
   
   <?php }else if($t==4){ ?>
   window.location='frm_recprint_<?php echo $c_code; ?>.php?id=<?php echo $rec_id; ?>&idno=<?php echo $idno;?>';
    <?php }else if($t==5){ ?>
    window.location='frm_recid_confirm.php?type=vat&id=<?php echo $rec_id; ?>';
    <?php }else if($t==6){ ?>
    window.location='frm_recprint_vat_<?php echo $c_code; ?>.php?id=<?php echo $rec_id; ?>';
   
   <?php } ?>
	 
	  }else{
		  
		alert("บันทึกไม่สำเร็จ กรุณาแจ้งผู้ดูแลระบบ");  
	  }
  });


 }
</script> 
</head>

<body style="background-color:#ffffff; margin-top:0px;">

<table width="100%" border="0" align="center">
<tr >
<td align="center" valign="middle" height="200">


			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr>
				<td align="center"><b>ระบุเหตุผลที่พิมพ์ใบเสร็จใหม่อีกครั้ง (Reprint)</b></td>
			</tr>
			<tr>
				<td align="center"><textarea name="reason_rep" id="reason_rep" cols="55" onKeydown="Javascript: if (event.keyCode==13) document.getElementById('b1').focus();" rows="4"></textarea></td>
			</tr>
			<tr><td align="center">

				<input type="button" id="b1" value="ตกลง" onclick="return checkdata();">
                 <input type="reset" value="ยกเลิก" onclick="document.getElementById('reason_rep').value=''" >
			</td></tr>
			</table>
		
			



</td>
</tr>
</table>

</body>
</html>