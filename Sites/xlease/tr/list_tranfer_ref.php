<?php
session_start();
include("../config/config.php");
include("../company.php");
  
  //chk_company //
  
  if($_SESSION["session_company_code"]=="AVL")
  {
      $con_tr="host=". $company[4]['server'] ." port=5432 dbname=". $company[4]['dbname'] ." user=". $company[4]['dbuser'] ." password=". $company[4]['dbpass'] ."";
     $db_conn_tr = pg_connect($con_tr) or die("Can't Connect ! to thaiace.");
	 
	 $comp_name=$company[4]['name'];
  
  }
  else if($_SESSION["session_company_code"]=="THA")
  {
     $con_tr="host=". $company[0]['server'] ." port=5432 dbname=". $company[0]['dbname'] ." user=". $company[0]['dbuser'] ." password=". $company[0]['dbpass'] ."";
     $db_conn_tr = pg_connect($con_tr) or die("Can't Connect ! to AVL.");
     $comp_name=$company[0]['name'];
  }


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="file:///C|/wamp/www/av/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title><?php echo $_SESSION["session_company_name"]; ?></title>
<script type="text/javascript" src="autocomplete.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>

<script type="text/javascript">
checked=false;
function checkedAll(preview_tr) {
	var aa= document.getElementById('preview_tr');
	 if (checked == false)
          {
           checked = true
          }
        else
          {
          checked = false
          }
	for (var i =0; i < aa.elements.length; i++) 
	{
	 aa.elements[i].checked = checked;
	}
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

<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"><?php echo $_SESSION["session_company_name"]; ?></h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;"><?php echo $_SESSION["session_company_name"]; ?></div>
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
  <div class="style5" style="width:auto; height:100px; padding-left:10px;">
  TRANFER COMPANY 
    <input name="button" type="button" onclick="window.close()" value="CLOSE" />
	<button onclick="window.location='tranfer_ref.php'">BACK</button>
   <form method="post" id="preview_tr" action="process_tranfer_ref.php">
    <table width="776" border="0" cellspacing="1" style="background-color:#999999;">

  <tr style="background-color:#FBFBFB;">
    <td colspan="10">โอน bill payment ข้ามบริษัท : ข้อมูลจากบริษัท <?php echo  $comp_name; ?> : Preview</td>
    </tr> 
	 <tr style="background-color:#DDE6B7">
    <td width="35">id<br />
      tranpay</td>
    <td width="88"><div align="center">tr_date</div></td>
    <td width="88">ref1</td>
    <td width="87">ref2</td>
    <td width="143"><div align="center">ref_name</div></td>
    <td width="39"><div align="center">terminal<br />
      _id</div></td>
    <td width="40"><div align="center">bank</div></td>
    <td width="67"><div align="center">tran_type</div></td>
    <td width="90"><div align="center">amt</div></td>
    <td width="70"><div align="center">select <input type="checkbox" name="checkall" onclick="checkedAll()" /></div></td>
  </tr>
	
   
    <?php 



  

  
  
  //$con_tr = "host=172.16.2.5  port=5432 dbname=thaiace user=postgres	password=postgres";
  //$db_conn_tr = pg_connect($con_tr) or die("can't connect");
  
  $chose_id=$_POST["id_tran"];
  $h_ref1=$_POST["ref1"];
  $h_ref2=$_POST["ref2"];
  for ($i=0;$i<count($chose_id);$i++) 
	{ 
	    
		$tr_id=$_POST["id_tran"][$i];
		
		$qry_flist=pg_query($db_connect,"select A.*,B.* from \"TranPay\" A
		                                 LEFT OUTER JOIN \"BankCheque\" B on B.\"BankNo\" = A.bank_no
		                                 where A.\"PostID\"='$tr_id'");
		
		
		
		$res_flist=pg_fetch_array($qry_flist);
		
		$refid_tran=$res_flist["id_tranpay"];
		$res_plog==$res_flist["PostID"];
		$ref1_id=$res_flist["ref1"];
		$ref2_id=$res_flist["ref2"];
		
		$str_qry="select A.\"TranIDRef1\",A.\"TranIDRef2\",A.\"IDNO\",A.\"CusID\",
	                 B.* 
	          from \"Fp\" A
			  LEFT OUTER JOIN \"Fa1\" B on B.\"CusID\"=A.\"CusID\" 
	          WHERE 
			  (A.\"TranIDRef1\"='$ref1_id') AND                              
			  (A.\"TranIDRef2\"='$ref2_id') ";
    $qry_tr=pg_query($db_conn_tr,$str_qry);
	$res_trn=pg_fetch_array($qry_tr);
	
	
	$select_chk="<input type=\"checkbox\" name=\"ref_idtran[]\" value=\"$tr_id\"  />";
	
	$res_tr_name=$res_trn["A_NAME"]; 
    if($res_tr_name=='')
	{
	$row_color="style=\"background-color:#FCF1C5\" ";
	$ct_name=" fail TranIDRef";
	}
	else
	{
	$row_color="style=\"background-color:#CEFF9D\" ";
	
	$ct_name=$res_trn["A_FIRNAME"]." ".$res_trn["A_NAME"]." ".$res_trn["A_SIRNAME"];
	} 
      
  ?>
  <tr <?php echo $row_color; ?>>
    <td ><?php echo $tr_id; ?></td>
    <td><?php echo $res_flist["tr_date"]; ?></td>
    <td><?php echo $ref1_id; ?></td>
    <td><?php echo $ref2_id; ?></td>
    <td><?php echo $ct_name; ?></td>
    <td><?php echo $res_flist["terminal_id"]; ?></td>
    <td><div align="center"><?php echo $res_flist["BankCode"]; ?></div></td>
    <td ><?php echo $res_flist["tran_type"]; ?></td>
    <td style="text-align:right;"><?php echo number_format($res_flist["amt"],2); ?></td>
    <td><?php echo $select_chk; ?></td>
  </tr>
  <?php
  }
  ?>
  <tr style="background-color:#FFFFFF;">
    <td colspan="8"><div align="center"></div></td>
    <td>&nbsp;</td>
    <td><input type="submit" value="Update" /></td>
  </tr>
  <?php
 
  ?>
</table>
</form>
  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
