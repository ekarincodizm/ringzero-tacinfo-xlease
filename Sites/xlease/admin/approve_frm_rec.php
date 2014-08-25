<?php
session_start();
include("../config/config.php");
$cdate=date("Y-m-d");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
</head>
<body>
 
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
        
<div class="header"><h1></h1></div>

<div class="wrapper">

<fieldset><legend><B>อนุมัติยกเลิกใบเสร็จ</B></legend>

<form name="frm_app_cc" method="post" action="approve_frm_rec.php">
<p>เลือกวันที่
<input name="CDate" type="text" readonly="true" value="<?php echo date("Y/m/d"); ?>"/>
<input name="button2" type="button" onclick="displayCalendar(document.frm_app_cc.CDate,'yyyy/mm/dd',this)" value="ปฏิทิน" />
<input type="submit" value="NEXT" />
</p>
 
 <table width="786" border="0" style="background-color:#CCCCCC;" cellpadding="0" cellspacing="1" >
      <?php
  $cdate=pg_escape_string($_POST["CDate"]);
  
  $qry_cc=pg_query("select * from \"CancelReceipt\" WHERE c_date='$cdate' ");
  $numrow_cc=pg_num_rows($qry_cc);
  if($numrow_cc==0)
   {
  ?> 
     <tr>
    <td colspan="6" style="background-color:#EEF2DB;">ไม่มีรายการยกเลิก</td>
    </tr>
   <?php
   }
   else
   {
 ?>
  <tr>
    <td colspan="6" style="background-color:#EEF2DB;">รายการขอยกเลิกใบเสร็จ วันที่ <?php echo $cdate; ?></td>
    </tr>
  <tr style="background-color:#FFFFFF">
    <td width="37">no.</td>
    <td width="154">เลขที่ใบเสร็จ</td>
    <td width="140">จำนวนเงิน</td>
    <td width="230">เหตุผล</td>
    <td width="155">สถานะ</td>
    <td width="63">Approve</td>
  </tr>
  <?php
  while($res_cc=pg_fetch_array($qry_cc))
  {
    $n++;
	$cs_memo=$res_cc["c_memo"];
	$cs_recno=$res_cc["ref_receipt"];
	
	$cs_adp=$res_cc["admin_approve"];
	
	
	
	if($res_cc["admin_approve"]=='t')
	{
	  $sta="อนุมัติยกเลิกใบเสร็จแล้ว";
	  $clor="style=\"background-color:#CEFF9D;\"";
	  $bt_cc="";
	  $ft=$res_cc["return_cashno"];
	}
	else
	{
	 $ft=$res_cc["return_cashno"];
	 $sta="รอการอนุมัติ";
	 $clor="style=\"background-color:#FDE2AC;\"";
	 $bt_cc="<input type=\"button\" value=\"Approve\" onclick=\"window.location='approve_cancel_recprocess.php?cid=$res_cc[c_receipt]&memo=$cs_memo&recno=$cs_recno&adp=$ft' \" />";
	}
  ?>
  <tr <?php echo $clor; ?>>
    <td><?php echo $n; ?></td>
    <td><?php echo $res_cc["c_receipt"]; ?></td>
    <td><?php echo $res_cc["c_money"]; ?></td>
    <td><?php echo $res_cc["c_memo"]; ?></td>
    <td><?php echo $sta; ?></td>
    <td><?php echo $bt_cc; ?></td>
  </tr>
  <?php  
   }
   
  ?>
</table>

 
 <?php  
   }

  ?>
     
	</form>

      </fieldset> 

</div>
        </td>
    </tr>
</table>         
 
<div align="center"><input name="button" type="button" onclick="window.location='../list_menu.php'" value="กลับเมนูหลัก" /></div> 

</body>
</html>
