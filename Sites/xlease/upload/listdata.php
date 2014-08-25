<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="file:///C|/wamp/www/av/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>AV. leasing co.,ltd</title>
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
<h1 class="style4"> AV.LEASING</h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;">AV. Leasing </div>
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
  <div class="style5" style="width:auto; height:100px; padding-left:10px;">
  <?php
  include("../config/config.php");

  $strFileName = "string.dat";
  $objFopen = fopen($strFileName, 'r');
  if ($objFopen) 
  {
     //begin head //
	  $file = fgets($objFopen, 4096);
	  $head_text=substr($file,0,256);
	  $t_bankcode=substr($head_text,7,3);
	  $t_namecompany=substr($head_text,20,40);
	  $t_datesentdata=substr($head_text,59,67);
	  echo "code bank =".$t_bankcode."<br>".
	       "company   =".$t_namecompany."<br>".
		   "date send =".substr($t_datesentdata,0,3)."/".substr($t_datesentdata,3,2)."/".substr($t_datesentdata,5,4)."<br>";
	 //end head //		
     fclose($objFopen);
  }
 	
   $datafile=fopen("string.dat","r");
   while (!feof($datafile))
   {
   	$buffer = fgets($datafile, 4096);
	
	$text= explode(" ",$buffer);
   	
    
	  $dst=substr($text[0],0,256);
	   $resd=substr($dst,0,1);
	   if($resd=="D")
	   {
	     //$dtext=explode("H",$buffer);
		 //echo $resdtext=substr($dtext[0],0,256)."<br>";
		 //$dtext=explode(" ",$buffer);
		 
		 //echo  $buffer."###"; 
		
		       $terminal_sq_no=substr($buffer,1,6); 
			    $bank_no=substr($buffer,7,3);
	
		       $tr_date=substr($buffer,20,8);
			   $tr_time=substr($buffer,28,6);
			   $ref_name=substr($buffer,34,50);
			   
			   $ref1=substr($buffer,84,20);
			   $ref2=substr($buffer,104,20);
			   $pay_bank_branch=substr($buffer,145,3);
               $terminal_id=substr($buffer,148,4);
			   $tran_type=substr($buffer,153,3);
			   $pay_cheque_no=substr($buffer,156,7);
			   $amt=substr($buffer,163,13);
		
		
		      
		       $d_tr=substr($tr_date,4,4)."-".$date_tr=substr($tr_date,2,2)."-".substr($tr_date,0,2);
			   $t_tr=substr($tr_time,0,2).":".substr($tr_time,2,2).":".substr($tr_time,4,2);
		
		        
		

		
	
	  echo	  $in_sql="insert into \"TranPay\"(terminal_sq_no, bank_no, tr_date, tr_time, ref_name, ref1, ref2,
				   pay_bank_branch, terminal_id, tran_type, pay_cheque_no, amt) 
                   values  
                  ('$terminal_sq_no','$bank_no','$d_tr','$t_tr','$ref_name','$ref1','$ref2',
				   '$pay_bank_branch','$terminal_id','$tran_type','$pay_cheque_no','$amt'
				  )";
		       if($result=pg_query($in_sql))
 				{
  					$st_fn="OK".$in_sql;
 				}
 				else
 				{
  					$st_fn="error insert Re".$in_sql;
 				}
		        
				
				
	   }
	   else
	   {
	   
	   }  
	
  	// echo  substr($text[0],0,200).substr($text[1],0,200)."<br>";
	// echo $buffer;
   }
   fclose($datafile);	
    
	
	
      
	  
  ?>	
    
	  
  <?php	  
 
?>
  <br /><input type="button" name="TransData" value="Translation" onclick="window.location='trn_process.php' "  />
  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
