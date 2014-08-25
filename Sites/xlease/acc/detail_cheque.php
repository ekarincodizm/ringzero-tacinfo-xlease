<?php
include("../config/config.php");
$_SESSION["av_iduser"];
$cq_no=pg_escape_string($_GET["ch_no"]);
$p_id=pg_escape_string($_GET["ch_pid"]);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>รายละเอียดเช็ค <?php echo $cq_no; ?></title>
</head>

<body>
<table width="600" border="0" cellpadding="1" cellspacing="1" style="background-color:#999;">
  <tr style="background-color:#FFC;">
    <td colspan="6" style="padding-left:5px;">เลขที่เช็ค <?php echo $cq_no; ?></td>
  </tr>
    <tr style="background-color:#FFC;">
    <td width="35" style="padding-left:5px;">No.</td>
    <td width="67" style="padding-left:5px;">IDNO</td>
    <td width="209" style="padding-left:5px;">ชื่อ - นามสกุล</td>
    <td width="56" style="padding-left:5px;">ทะเบียน</td>
    <td width="122" style="padding-left:5px;">รายการชำระ</td>
    <td width="92" style="padding-left:5px;">ยอดเงิน</td>
  </tr>
  

<?php
 $qry_dc=pg_query("select A.*,B.*,C.* from \"DetailCheque\" A 
                     LEFT OUTER JOIN \"VContact\" B ON A.\"IDNO\"=B.\"IDNO\"
					 LEFT OUTER JOIN \"TypePay\" C ON A.\"TypePay\"=C.\"TypeID\"
                     WHERE  (A.\"ChequeNo\"='$cq_no') AND (A.\"PostID\"='$p_id')   
				    ");
	while($res_dc=pg_fetch_array($qry_dc))
	{
	  $a++;
	  
	    $ptype=$res_dc["TName"];
	  if($res_dc["C_REGIS"]=="")
		{
		
		$rec_regis=$res_dc["car_regis"];
			
		
		}
		else
		{
		
		$rec_regis=$res_dc["C_REGIS"];
		}
	  
	   $view_idno="<a href=\"../post/frm_viewcuspayment.php?idno_names=$res_dc[IDNO]&type=outstanding\" target=\"_blank\">$res_dc[IDNO]</a>";
  	?>
     <tr style="background-color:#FFF;">
    <td style="padding-left:3px;"><?php echo $a; ?></td>
    <td style="padding-left:3px;"><?php echo $view_idno; ?></td>
    <td style="padding-left:3px;"><?php echo $res_dc["full_name"]; ?></td>
    <td style="padding-left:3px;"><?php echo $rec_regis; ?></td>
    <td style="padding-left:3px;"><?php echo $ptype; ?></td>
    <td style="padding-right:3px; text-align:right;"><?php echo number_format($res_dc["CusAmount"],2); ?></td>
  </tr>
    
    <?php
	}   
    ?>
  
 
</table>
<hr />
<center><div><button onclick="javascript:window.close();">close</button></div></center>
  
</body>
</html>