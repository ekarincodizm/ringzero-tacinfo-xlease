<?php
session_start();
include("../config/config.php");
header('Cache-Control: no-cache');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-cache');
header('Pragma: no-cache');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<form name="post_acc" method="post" action="process_pass_tr_tranpay.php" >
 <table width="771" border="0" style="background-color:#CCCCCC;" cellpadding="1">
  <tr style="background-color:#DDE6B7">
    <td colspan="4">รายการรับเงินโอน-บัญชี</td>
    </tr>
  <tr style="background-color:#FCF1C5">
    <td>PostID</td>
    <td width="176">typepay</td>
    <td width="132">Amount</td>
    <td width="95">Receipt</td>
  </tr>
  <?php
  $postid=pg_escape_string($_GET["pid"]);
  $qry_tr=pg_query("select * from \"PostLog\" WHERE \"PostID\"='$postid' ");
  while($res_tr=pg_fetch_array($qry_tr))
  {
   $ppid=$res_tr["PostID"];
   $amtid=$res_tr["amt"]; 
   
   
   $qry_amt=pg_query("select \"Amount\",\"PostID\",\"IDNO\"  from \"DetailTranpay\" WHERE \"PostID\"='$postid' ");
   while($res_amt=pg_fetch_array($qry_amt))
   {
     $idnos=$res_amt["IDNO"];
     $samts=$samts+$res_amt["Amount"];
   }  
   
  ?>
    <input type="hidden" name="idno_ss" value="<?php echo $idnos; ?>" />
	<input type="hidden" name="postid" value="<?php echo $ppid; ?>" />
  <tr style="background-color:#FFFFFF;">
    <td><?php echo $res_tr["PostID"]; ?></td>
    <td><?php echo $res_tr["paytype"]; ?></td>
    <td><?php echo $samts; ?></td>
    <td><input type="submit" value="Receipt"  /></td>
  </tr>
  <?php
  }
  ?>
</table>
</form>
</body>
</html>
