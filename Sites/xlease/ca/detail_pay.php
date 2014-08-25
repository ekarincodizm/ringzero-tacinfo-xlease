<?php
session_start();
include("../config/config.php");
$_SESSION["av_iduser"];

$escape_string_p_id = pg_escape_string($_GET["p_id"]);
$escape_string_p_type = pg_escape_string($_GET["p_type"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
<div style="margin-left:auto; margin-right:auto; width:510px;">
<table width="510" border="0" cellpadding="1" cellspacing="1" style="background-color:#CCC;">
  <tr style="background-color:#CFC; padding-left:10px;">
    <td colspan="4" style="padding-left:5px;"> Post ID <?php echo $escape_string_p_id; ?>
  </td>
  </tr>
  <tr style="background-color:#CCF;">
    <td width="31" style="padding-left:5px;">No.</td>
    <td width="182" style="padding-left:5px;">IDNO / ทะเบียน</td>
    <td width="183" style="padding-left:5px;">รายการชำระ</td>
    <td width="76" style="padding-left:5px;">ยอดเงิน</td>
  </tr>
  <?php
  
  if($escape_string_p_type == "CA")
  {
	  
	  $amt=pg_query("select sum(\"AmtPay\") AS sum_amt from \"FCash\" where \"PostID\" = '$escape_string_p_id'");
		$res_amt=pg_fetch_array($amt);
		
		$num_amt=number_format($res_amt["sum_amt"],2);
	  
	  
	  $a=0;

  $qry_dtl=pg_query("select A.\"AmtPay\",A.\"TypePay\",A.\"IDNO\",B.\"TName\",B.\"TypeID\",C.\"C_REGIS\",C.car_regis,D.\"RadioCar\" from \"FCash\" A   
  LEFT OUTER JOIN \"TypePay\" B  ON B.\"TypeID\" = A.\"TypePay\"
  LEFT OUTER JOIN \"VContact\" C  ON C.\"IDNO\" = A.\"IDNO\"
  LEFT OUTER JOIN \"RadioContract\" D ON D.\"COID\" = A.\"IDNO\"
   WHERE A.\"PostID\" ='$escape_string_p_id' ");
		while($res_dtl=pg_fetch_array($qry_dtl))
		{
		  $a++;	 
		  $pids=$res_dtl["TypePay"]; 
		  $pidno=$res_dtl["IDNO"];
		  //แสดงทะเบียน
		  
		  $s_amt=$res_dtl["AmtPay"];
		  
			if($res_dtl["C_REGIS"] !=""){
				$rec_regis="[".$res_dtl["C_REGIS"]."]";
			}else if($res_dtl["car_regis"] !=""){
				$rec_regis="[".$res_dtl["car_regis"]."]";
			}else if($res_dtl["RadioCar"] !=""){
				$rec_regis="[".$res_dtl["RadioCar"]."]";
			}
			
		?>
        
        <tr style="background-color:#FFF;">
            <td style="padding-left:5px;"><?php echo $a; ?></td>
            <td style="padding-left:5px;"><?php echo $pidno." / ".$rec_regis; ?></td>
            <td style="padding-left:5px;"><?php echo $res_dtl["TName"]; ?></td>
            <td style="text-align:right; padding-right:5px;"><?php echo number_format($res_dtl["AmtPay"],2); ?></td>
       </tr>
     <?php
	  
	  }
	  
	  
	  
   }
   elseif($escape_string_p_type == "TC")
  {
	  
	  $amt=pg_query("select sum(\"AmtPay\") AS sum_amt from \"FTACCheque\" where \"PostID\" = '$escape_string_p_id'");
		$res_amt=pg_fetch_array($amt);
		
		$num_amt=number_format($res_amt["sum_amt"],2);
	  
	  
	  $a=0;

  $qry_dtl=pg_query("select A.\"AmtPay\",A.\"TypePay\",A.\"COID\",B.\"TName\",B.\"TypeID\",C.\"C_REGIS\",C.car_regis,D.\"RadioCar\" from \"FTACCheque\" A   
  LEFT OUTER JOIN \"TypePay\" B  ON B.\"TypeID\" = A.\"TypePay\"
  LEFT OUTER JOIN \"VContact\" C  ON C.\"IDNO\" = A.\"COID\"
  LEFT OUTER JOIN \"RadioContract\" D ON D.\"COID\" = A.\"COID\"
   WHERE A.\"PostID\" ='$escape_string_p_id' ");
		while($res_dtl=pg_fetch_array($qry_dtl))
		{
		  $a++;	 
		  $pids=$res_dtl["TypePay"]; 
		  $pidno=$res_dtl["COID"];
		  //แสดงทะเบียน
		  
		  $s_amt=$res_dtl["AmtPay"];
		  
			$qry_car=pg_query("select \"carregis\" from \"FTACCheque\" where \"PostID\" = '$escape_string_p_id'");
			$res_car=pg_fetch_array($qry_car);
			$carFTA=$res_car["carregis"];
			if($carFTA==""){
				$rec_regis="[".$res_dtl["RadioCar"]."]";
					
				if($res_dtl["C_REGIS"] !=""){
					$rec_regis="[".$res_dtl["C_REGIS"]."]";
				}else if($res_dtl["car_regis"] !=""){
					$rec_regis="[".$res_dtl["car_regis"]."]";
				}
			}else{
				$rec_regis="[".$carFTA."]";
			}		
		?>
        
        <tr style="background-color:#FFF;">
            <td style="padding-left:5px;"><?php echo $a; ?></td>
            <td style="padding-left:5px;"><?php echo $pidno." / ".$rec_regis; ?></td>
            <td style="padding-left:5px;"><?php echo $res_dtl["TName"]; ?></td>
            <td style="text-align:right; padding-right:5px;"><?php echo number_format($res_dtl["AmtPay"],2); ?></td>
       </tr>
     <?php
	  
	  }
	  
	  
	  
   }
    elseif($escape_string_p_type == "TT")
  {
	  
	  $amt=pg_query("select sum(\"AmtPay\") AS sum_amt from \"FTACTran\" where \"PostID\" = '$escape_string_p_id'");
		$res_amt=pg_fetch_array($amt);
		
		$num_amt=number_format($res_amt["sum_amt"],2);
	  
	  
	  $a=0;

  $qry_dtl=pg_query("select A.\"AmtPay\",A.\"TypePay\",A.\"COID\",B.\"TName\",B.\"TypeID\",C.\"C_REGIS\",C.car_regis,D.\"RadioCar\" from \"FTACTran\" A   
  LEFT OUTER JOIN \"TypePay\" B  ON B.\"TypeID\" = A.\"TypePay\"
  LEFT OUTER JOIN \"VContact\" C  ON C.\"IDNO\" = A.\"COID\"
  LEFT OUTER JOIN \"RadioContract\" D ON D.\"COID\" = A.\"COID\"
   WHERE A.\"PostID\" = '$escape_string_p_id' ");
		while($res_dtl=pg_fetch_array($qry_dtl))
		{
		  $a++;	 
		  $pids=$res_dtl["TypePay"]; 
		  $pidno=$res_dtl["COID"];
		  //แสดงทะเบียน
		  
		  $s_amt=$res_dtl["AmtPay"];
		  
			$qry_car=pg_query("select * from \"FTACTran\" where \"PostID\" = '$escape_string_p_id'");
			$res_car=pg_fetch_array($qry_car);
			$carFTA=$res_car["carregis"];
			if($carFTA==""){
				$rec_regis="[".$res_dtl["RadioCar"]."]";
					
				if($res_dtl["C_REGIS"] !=""){
					$rec_regis="[".$res_dtl["C_REGIS"]."]";
				}else if($res_dtl["car_regis"] !=""){
					$rec_regis="[".$res_dtl["car_regis"]."]";
				}
			}else{
				$rec_regis="[".$carFTA."]";
			}		
		?>
        
        <tr style="background-color:#FFF;">
            <td style="padding-left:5px;"><?php echo $a; ?></td>
            <td style="padding-left:5px;"><?php echo $pidno." / ".$rec_regis; ?></td>
            <td style="padding-left:5px;"><?php echo $res_dtl["TName"]; ?></td>
            <td style="text-align:right; padding-right:5px;"><?php echo number_format($res_dtl["AmtPay"],2); ?></td>
       </tr>
     <?php
	  
	  }
	  
   }
  elseif($escape_string_p_type == "CH")
  {
	  $a=0;
  $qry_ch_dtl=pg_query("select A.\"CusAmount\",A.\"TypePay\",A.\"IDNO\",B.\"TName\",B.\"TypeID\",C.\"C_REGIS\",C.car_regis from \"DetailCheque\" A   
  LEFT OUTER JOIN \"TypePay\" B  ON B.\"TypeID\" = A.\"TypePay\"
  LEFT OUTER JOIN \"VContact\" C  ON C.\"IDNO\" = A.\"IDNO\"
   WHERE A.\"PostID\" = '$escape_string_p_id' ");
		while($res_ch_dtl=pg_fetch_array($qry_ch_dtl))
		{
		  $a++;	
		  $pids=$res_ch_dtl["TypePay"]; 
		  $pidno=$res_ch_dtl["IDNO"];
		  
		   $s_amt=$res_ch_dtl["CusAmount"];
		?>
		
		 <tr style="background-color:#FFF;">
            <td style="padding-left:5px;"><?php echo $a; ?></td>
            <td style="padding-left:5px;"><?php echo $pidno." / ".$rec_regis; ?></td>
            <td style="padding-left:5px;"><?php echo $res_ch_dtl["TName"]; ?></td>
            <td style="text-align:right; padding-right:5px;"><?php echo number_format($res_ch_dtl["CusAmount"],2); ?></td>
       </tr>
		<?php
		}
	     $amt=pg_query("select sum(\"AmtOnCheque\") AS sum_ch_amt from \"FCheque\" where \"PostID\" = '$escape_string_p_id'");
		$res_amt=pg_fetch_array($amt);
		
		$num_amt=number_format($res_amt["sum_ch_amt"],2);
  }
 ?>		  
  <tr style="background-color:#FFF;">
    <td colspan="3" style="padding-right:5px; text-align:right;">รวมยอดเงิน</td>
    <td style="text-align:right; padding-right:5px; "><?php echo  $num_amt; ?></td>
  </tr>
</table> 
<br />
<hr />
<div><center><button onclick="javascript:window.close();">close</button></center></div>
</div>
</body>
</html>