<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
include("../config/config.php");
$_SESSION["av_iduser"];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $_SESSION["session_company_name"]; ?> co.,ltd</title>
<style type="text/css">
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
<!-- InstanceBeginEditable name="head" -->
<script type="text/javascript">
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
</script>
<!-- InstanceEndEditable -->
</head>

<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"> <?php echo $_SESSION["session_company_name"]; ?></h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="warppage" style="width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  แสดงรายการชำระเงิน <hr />
  
  <div class="style5" style="width:auto; padding-left:0px;">
  <table width="800" border="0" cellpadding="1" cellspacing="1" style="background-color:#CCCCCC;">
  <tr>
    <td colspan="7">view postlog date <?php echo date("d/m/Y"); ?><input type="button" value="CLOSE" onclick="window.close()"  /></td>
    </tr>
  <tr style="background-color:#EEF2DB;">
    <td width="109"  style="padding:0px 3px 0px 3px;">PostID</td>
    <td width="268"  style="padding:0px 3px 0px 3px;">ชื่อ-นามสกุล</td>
    <td width="148"  style="padding:0px 3px 0px 3px;">Name Post</td>
    <td width="59"  style="padding:0px 3px 0px 3px;">paytype</td>
    <td colspan="2"  style="padding:0px 3px 0px 3px;"><div align="center">Amt</div></td>
    <td width=""  style="padding:0px 3px 0px 3px;">&nbsp;</td>
  </tr>
  <?php
  $ymd=date("Y-m-d");
  $qry_plog=pg_query("select \"PostID\", \"paytype\", \"UserIDPost\"
					from \"PostLog\" where (\"PostDate\"='$ymd') and ((paytype='CA') or (paytype='CH')  or (paytype='TC') or (paytype='TT')) order by paytype ");
  while($reslog=pg_fetch_array($qry_plog)) 
    {
	 $list_pid=$reslog["PostID"];
	 $tmp_type="";
	 $m_paytype=$reslog["paytype"];
	 
	// Query ข้อมูลของพนักงานเพื่อนำมาใช้ในการแสดงผลในหน้าจอว่า รับเงิน คือใคร
	$Uid = $_SESSION["av_iduser"];
	$qry_user=pg_query("select * from \"Vfuser\" WHERE id_user='$Uid' ");
	$res_user=pg_fetch_array($qry_user);
	$emplevel=$res_user["emplevel"];
		
	if($reslog["paytype"]=="CA")
	 {
	    $sty_border="style=\"background-color:#BCE4F6;\" ";
		 $qry_name=pg_query("select A.\"UserIDAccept\",A.\"UserIDPost\",A.\"PostID\",B.\"PostID\",B.\"CusID\",C.\"CusID\",C.\"A_NAME\",C.\"A_SIRNAME\",D.id_user,D.username
		  from \"PostLog\"  A
         LEFT OUTER JOIN \"FCash\" B ON B.\"PostID\"=A.\"PostID\" 
		 LEFT OUTER JOIN \"Fa1\" C ON B.\"CusID\"=C.\"CusID\" 
		 LEFT OUTER JOIN  \"Vfuser\" D ON D.id_user=A.\"UserIDPost\" 
		 WHERE (A.\"PostID\"='$list_pid') limit(1) "); 
		 $res_cus=pg_fetch_array($qry_name);
		 
		 $s_name=$res_cus["A_NAME"]." ".$res_cus["A_SIRNAME"];
		 $s_postID=$res_cus["username"];
		
		// ห้ามคนเดียวกันรับเงินที่ตนเองตั้ง เว้นแต่เป็น Admin
		if($res_cus["UserIDAccept"]=="" && ($_SESSION["av_iduser"]!=$reslog["UserIDPost"] || $emplevel<="7"))
		{
			$pptype=$m_paytype;
			$bt_rec="<input type=\"button\" value=\"Receipt\" onclick=\"window.location='frm_recfunction.php?pID=$reslog[PostID]&PayType=$pptype'\" >";
		}
		else
		{
			$bt_rec="";
			if($_SESSION["av_iduser"]==$reslog["UserIDPost"] && ($emplevel>"7") && $res_cus["UserIDAccept"]=="")
				$bt_rec="ไม่ให้รับตนเอง";
		}
		
		//amt
		$amt=pg_query("select sum(\"AmtPay\") AS sum_amt from \"FCash\" where \"PostID\"='$list_pid'");
		$res_amt=pg_fetch_array($amt);
		
		$num_amt=number_format($res_amt["sum_amt"],2);
		
		
	 }
	 else if($reslog["paytype"]=="CH")
	 {   
	    $qry_name=pg_query("select A.\"UserIDAccept\",A.\"UserIDPost\",A.\"PostID\",B.\"PostID\",B.\"CusID\",C.\"CusID\",C.\"A_NAME\",C.\"A_SIRNAME\",D.id_user,D.username
		  from \"PostLog\"  A
         LEFT OUTER JOIN \"DetailCheque\" B ON B.\"PostID\"=A.\"PostID\" 
		 LEFT OUTER JOIN \"Fa1\" C ON B.\"CusID\"=C.\"CusID\" 
		 LEFT OUTER JOIN  \"Vfuser\" D ON D.id_user=A.\"UserIDPost\" 
		 WHERE (A.\"PostID\"='$list_pid') limit(1) "); 
		 $res_fchq=pg_fetch_array($qry_name);
	   
	     $s_name=$res_fchq["A_NAME"]." ".$res_fchq["A_SIRNAME"];
		 $s_postID=$res_fchq["username"];
		 
		// ห้ามคนเดียวกันรับเงินที่ตนเองตั้ง เว้นแต่เป็น Admin
		if($res_fchq["UserIDAccept"]=="" && ($_SESSION["av_iduser"]!=$reslog["UserIDPost"] || $emplevel<="7"))	   
		 {
		   $pptype=$m_paytype;
		   $bt_rec="<input type=\"button\" value=\"Receipt\" onclick=\"window.location='frm_recfunction.php?pID=$reslog[PostID]&PayType=$pptype'\" />";
		 }
		 else
		 {
		   $bt_rec="";
			if($_SESSION["av_iduser"]==$reslog["UserIDPost"] && ($emplevel>"7") && $res_fchq["UserIDAccept"]=="")
				$bt_rec="ไม่ให้รับตนเอง";
		 }
	 
	 
	 
	    $amt=pg_query("select sum(\"AmtOnCheque\") AS sum_ch_amt from \"FCheque\" where \"PostID\"='$list_pid'");
		$res_amt=pg_fetch_array($amt);
		
		$num_amt=number_format($res_amt["sum_ch_amt"],2);
		$sty_border="style=\"background-color:#FCEADD;\" "; 
	  }
	  else if($reslog["paytype"]=="TC")
	 {
	    $qrystatus=0;
		$qry_fp=pg_query("select a.\"COID\" from \"FTACCheque\" a
		inner join \"Fp\" b on a.\"COID\"=b.\"IDNO\"
		where a.\"PostID\"='$list_pid'");
		$num_row_fp=pg_num_rows($qry_fp);
		
		if($num_row_fp > 0){		
			$sty_border="style=\"background-color:#BCE4F6;\" ";
			$qry_name=pg_query("select A.\"UserIDAccept\",A.\"UserIDPost\",A.\"PostID\",B.\"PostID\",B.\"fullname\",D.id_user,D.username from \"PostLog\"  A
				LEFT OUTER JOIN \"FTACCheque\" B ON B.\"PostID\"=A.\"PostID\" 
				LEFT OUTER JOIN  \"Vfuser\" D ON D.id_user=A.\"UserIDPost\" 
				WHERE (A.\"PostID\"='$list_pid') limit(1) "); 
			$qrystatus=1;
		 }else{
			$qry_name=pg_query("select A.\"UserIDAccept\",A.\"UserIDPost\",A.\"PostID\",B.\"PostID\",B.\"fullname\",D.id_user,D.username from \"PostLog\"  A
				LEFT OUTER JOIN \"FTACCheque\" B ON B.\"PostID\"=A.\"PostID\" 
				LEFT OUTER JOIN \"RadioContract\" E ON B.\"COID\"=E.\"COID\" 
				LEFT OUTER JOIN  \"Vfuser\" D ON D.id_user=A.\"UserIDPost\" 
				WHERE (A.\"PostID\"='$list_pid') limit(1) "); 
			 $qrystatus=1;
		 }
		 $res_cus=pg_fetch_array($qry_name);
		 if($qrystatus==1){
			$s_name=$res_cus["fullname"];
		 }else{
			$s_name=$res_cus["A_NAME"]." ".$res_cus["A_SIRNAME"];
		 }
		 $s_postID=$res_cus["username"];
		
		// ห้ามคนเดียวกันรับเงินที่ตนเองตั้ง เว้นแต่เป็น Admin
		if($res_cus["UserIDAccept"]=="" && ($_SESSION["av_iduser"]!=$reslog["UserIDPost"] || $emplevel<="7"))
		{
			$pptype=$m_paytype;
			$bt_rec="<input type=\"button\" value=\"Receipt\" onclick=\"window.location='frm_recfunction.php?pID=$reslog[PostID]&PayType=$pptype'\" />";
		}
		else
		{
			$bt_rec="";
			if($_SESSION["av_iduser"]==$reslog["UserIDPost"] && ($emplevel>"7") && $res_cus["UserIDAccept"]=="")
				$bt_rec="ไม่ให้รับตนเอง";
		}
		
		//amt
		$amt=pg_query("select sum(\"AmtPay\") AS sum_amt from \"FTACCheque\" where \"PostID\"='$list_pid'");
		$res_amt=pg_fetch_array($amt);
		
		$num_amt=number_format($res_amt["sum_amt"],2);
		
		
	 }
	 else if($reslog["paytype"]=="TT")
	 {
		$qrystatus=0;
		$qry_fp=pg_query("select a.\"COID\" from \"FTACTran\" a
		inner join \"Fp\" b on a.\"COID\"=b.\"IDNO\"
		where a.\"PostID\"='$list_pid'");
		$num_row_fp=pg_num_rows($qry_fp);
		
		if($num_row_fp > 0){		
			$sty_border="style=\"background-color:#BCE4F6;\" ";
			$qry_name=pg_query("select A.\"UserIDAccept\",A.\"UserIDPost\",A.\"PostID\",B.\"PostID\",B.\"fullname\",D.id_user,D.username from \"PostLog\"  A
				LEFT OUTER JOIN \"FTACTran\" B ON B.\"PostID\"=A.\"PostID\" 
				LEFT OUTER JOIN  \"Vfuser\" D ON D.id_user=A.\"UserIDPost\" 
				WHERE (A.\"PostID\"='$list_pid') limit(1) "); 
			$qrystatus=1;
		 }else{
			$qry_name=pg_query("select A.\"UserIDAccept\",A.\"UserIDPost\",A.\"PostID\",B.\"PostID\",B.\"fullname\",D.id_user,D.username from \"PostLog\"  A
				LEFT OUTER JOIN \"FTACTran\" B ON B.\"PostID\"=A.\"PostID\" 
				LEFT OUTER JOIN \"RadioContract\" E ON B.\"COID\"=E.\"COID\" 
				LEFT OUTER JOIN  \"Vfuser\" D ON D.id_user=A.\"UserIDPost\" 
				WHERE (A.\"PostID\"='$list_pid') limit(1) "); 
			 $qrystatus=1;
		 }
		 $res_cus=pg_fetch_array($qry_name);
		 if($qrystatus==1){
			$s_name=$res_cus["fullname"];
		 }else{
			$s_name=$res_cus["A_NAME"]." ".$res_cus["A_SIRNAME"];
		 }
		 $s_postID=$res_cus["username"];
		
		// ห้ามคนเดียวกันรับเงินที่ตนเองตั้ง เว้นแต่เป็น Admin
		if($res_cus["UserIDAccept"]=="" && ($_SESSION["av_iduser"]!=$reslog["UserIDPost"] || $emplevel<="7"))
		{
			$pptype=$m_paytype;
			$bt_rec="<input type=\"button\" value=\"Receipt\" onclick=\"window.location='frm_recfunction.php?pID=$reslog[PostID]&PayType=$pptype'\" />";
		}
		else
		{
			$bt_rec="";
			if($_SESSION["av_iduser"]==$reslog["UserIDPost"] && ($emplevel>"7") && $res_cus["UserIDAccept"]=="")
				$bt_rec="ไม่ให้รับตนเอง";
		}
		
		//amt
		$amt=pg_query("select sum(\"AmtPay\") AS sum_amt from \"FTACTran\" where \"PostID\"='$list_pid'");
		$res_amt=pg_fetch_array($amt);
		
		$num_amt=number_format($res_amt["sum_amt"],2);
		
		
	 }
	 
	 /*
	 if($reslog["paytype"]=="CA")
	 {
	   
	   $qry_name=pg_query("select A.*,B.* from \"PostLog\"  A
       LEFT OUTER JOIN \"FCash\" B ON B.\"PostID\"=A.\"PostID\" WHERE (A.\"PostID\"='$list_pid') ");
       
	   	$qry_amt=pg_query("select * from \"FCash\" WHERE \"PostID\"='$reslog[PostID]' ");
		$tmp_amt=0;
		while($res_amt=pg_fetch_array($qry_amt))
	    {
		 $tmp_amt=$tmp_amt+$res_amt["AmtPay"];  //ยอดเงินสด
	    }
	    $sumamt=$tmp_amt;
		
		$qry_dtl=pg_query("select * from \"FCash\" WHERE \"PostID\" ='$list_pid' ");
		while($res_dtl=pg_fetch_array($qry_dtl))
		{
		  $pids=$res_dtl["TypePay"]; 
		  $pidno=$res_dtl["IDNO"];
		  //แสดงทะเบียน
		  $qry_reg=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$pidno' "); 
		  while($regis_car=pg_fetch_array($qry_reg))
		  {
		   if($regis_car["C_REGIS"]=="")
			{
			
			$rec_regis="[".$regis_car["car_regis"]."]";
				
			
			}
			else
			{
			
			$rec_regis="[".$regis_car["C_REGIS"]."]";
			}
		  }
		  $qry_type=pg_query("select * from \"TypePay\" WHERE \"TypeID\"=$pids");
		  while($res_type=pg_fetch_array($qry_type))
		  {
		   $tmp_type=$tmp_type.$res_type["TName"].$rec_regis.","."<br>";
		  }
		  
		}
		
		
	 }
	 else
	 {
	   $sumamt=0;
	   $qry_name=pg_query("select A.*,B.*  from \"PostLog\"  A
       LEFT OUTER JOIN \"DetailCheque\" B ON B.\"PostID\"=A.\"PostID\" WHERE (A.\"PostID\"='$list_pid')");
       
	   $qry_amt=pg_query("select * from \"FCheque\" WHERE \"PostID\"='$reslog[PostID]' ");
	   $res_amt=pg_fetch_array($qry_amt);
	   $sumamt=$res_amt["AmtOnCheque"]; // ยอดเช็ค
	   
	    
	    $qry_dtl=pg_query("select * from \"DetailCheque\" WHERE \"PostID\" ='$list_pid' ");
		while($res_dtl=pg_fetch_array($qry_dtl))
		{
		  $pids=$res_dtl["TypePay"]; 
		  $qry_type=pg_query("select * from \"TypePay\" WHERE \"TypeID\"=$pids");
		  while($res_type=pg_fetch_array($qry_type))
		  {
		   $tmp_type=$tmp_type.$res_type["TName"].",";
		  }
		  
		}
		
	   
	 }
	
	 
	
	
	 while($resid=pg_fetch_array($qry_name))
	 {
	 
	 
	    $res_idno=trim($resid["IDNO"]);
   
	    $listname=pg_query("select A.*,B.* from \"Fp\" A 
		                    LEFT OUTER JOIN \"Fa1\" B ON B.\"CusID\" = A.\"CusID\" 
						    WHERE  A.\"IDNO\"='$res_idno' ");
		$resname=pg_fetch_array($listname);
	    $fullname=trim($resname["A_NAME"])."  ".trim($resname["A_SIRNAME"]);
		
		
		
	 }
	   
	
	 if($reslog["UserIDAccept" ]=="")
	 {
	 
	   $pptype=$reslog["paytype"];
	   $bt_rec="<input type=\"button\" value=\"Receipt\" onclick=\"window.location='frm_recfunction.php?pID=$reslog[PostID]&PayType=$pptype'\" />";
	 }
	 else
	 {
	   $bt_rec="";
	 }
	   $qry_post=pg_query("select * from \"Vfuser\" WHERE id_user='$reslog[UserIDPost]' ");
	   $res_post=pg_fetch_array($qry_post);
	   
	   $qry_acp=pg_query("select * from \"Vfuser\" WHERE id_user='$reslog[UserIDAccept]' ");
	   $res_acpt=pg_fetch_array($qry_acp);
	   $numr_acp=pg_num_rows($qry_acp);
	   if($numr_acp==0)
	   {
	    $res_aname="";
	   }
	   else
	   {
	    $res_aname=$res_acpt["fullname"]."(".$res_acpt["user_group"].")";
	   }
	 */
  ?>
  <tr <?php echo $sty_border; ?>>
    <td style="padding:0px 3px 0px 3px;"> <a href="" onclick="MM_openBrWindow('detail_pay.php?p_id=<?php echo $reslog["PostID"]; ?>&p_type=<?php echo $m_paytype; ?>','รายละเอียดการชำระ','scrollbars=yes,width=530,height=250')"><?php echo $reslog["PostID"]; ?></a></td>
    <td style="padding:0px 3px 0px 3px;"><?php echo $s_name; ?></td>
    <td   style="padding:0px 3px 0px 3px;"><?php echo $s_postID; ?></td>
    <td   style="padding:0px 3px 0px 3px;"><?php echo $reslog["paytype"]; ?></td>
    <td colspan="2"  style="padding:0px 3px 0px 3px; text-align:right;"><?php echo $num_amt; ?></td>
    <td><?php echo $bt_rec; ?></td>
  </tr>
  <?php
   }
  ?>
  <tr>
    <td colspan="7" >&nbsp;</td>
    </tr>
</table>
  </div>
</div>
<?php
 pg_close();
?>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
