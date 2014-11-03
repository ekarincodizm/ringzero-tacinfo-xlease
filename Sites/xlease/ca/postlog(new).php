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
<h1 class="style4"> <?php echo $_SESSION["session_company_name"]; ?></h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;"><?php echo $_SESSION["session_company_name"]; ?> </div>
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
  <div class="style5" style="width:auto; height:100px; padding-left:0px;">
  <table width="778" border="0" cellpadding="1" style="background-color:#CCCCCC;">
  <tr>
    <td colspan="8">view postlog date:<?php echo date("Y-m-d"); ?><input type="button" value="CLOSE" onclick="window.close()"  /><iframe src="../acc/digits_clock.php" style="width:400px; height:25px;" frameborder="0"></iframe></td>
    </tr>
  <tr style="background-color:#EEF2DB;">
    <td width="57">PostID</td>
    <td width="135">ชื่อ-นามสกุล</td>
    <td width="148">Name Post</td>
    <td width="152">รายการที่ชำระ</td>
    <td width="62">PostDate</td>
    <td width="55">paytype</td>
    <td width="81"><div align="center">ยอดเงิน</div></td>
    <td width="54">&nbsp;</td>
  </tr>
  <?php
  $ymd=date("Y-m-d");
  // นำรายการ TR ออกจากการแสดงการรับเงินสด
  $qry_plog=pg_query("select * from \"PostLog\" where \"PostDate\"='$ymd' and \"paytype\"<>'TR' ");
  while($reslog=pg_fetch_array($qry_plog)) 
    {
	 $list_pid=$reslog["PostID"];
	 $tmp_type="";
	 
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
	  
	  // Query ข้อมูลของพนักงานเพื่อนำมาใช้ในการแสดงผลในหน้าจอว่า ผู้เจรจา / รับเงิน คือใคร
	   $qry_post=pg_query("select * from \"Vfuser\" WHERE id_user='$reslog[UserIDPost]' ");
	   $res_post=pg_fetch_array($qry_post);
	   
	   $qry_acp=pg_query("select * from \"fuser\" WHERE id_user='$reslog[UserIDAccept]' ");
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
	  
	// ห้ามคนเดียวกันรับเงินที่ตนเองตั้ง
	 if($reslog["UserIDAccept"]=="" && ($_SESSION["av_iduser"]!=$reslog["UserIDPost"] || $res_acpt["isadmin"]=='1'))
	 {
	   $pptype=$reslog["paytype"];
	   $bt_rec="<input type=\"button\" value=\"Receipt\" onclick=\"window.location='frm_recfunction.php?pID=$reslog[PostID]&PayType=$pptype'\" />";
	 }
	 else
	 {
	   $bt_rec="";
	   // ห้ามคนเดียวกัน -> ถ้าคนเเดียวกัน แล้วยังไม่ได้รับเงิน ให้โชว์ห้ามรับเงินตนเอง ถ้ารับเงินไปแล้ว ให้ไม่ต้องขึ้นอะไร เว้นแต่จะเป็น AD
		if($_SESSION["av_iduser"]==$reslog["UserIDPost"] && $res_acpt["isadmin"]!='1' && $reslog["UserIDAccept"]=="")
			$bt_rec="ไม่ให้รับตนเอง";
	 }
	 
  ?>
  <tr style="background-color:#FFFFFF;">
    <td><?php echo $reslog["PostID"]; ?></td>
    <td><?php echo $fullname; ?></td>
    <td><?php echo $res_post["fullname"]."(".$res_post["user_group"].")"; ?></td>
    <td><?php echo $tmp_type; ?></td>
    <td><?php echo $reslog["PostDate"]; ?></td>
    <td><?php echo $reslog["paytype"]; ?></td>

	
    <td style="text-align:right;"><?php echo number_format($sumamt,2); ?></td>
    <td><?php echo $bt_rec; ?></td>
  </tr>
  <?php
   }
  ?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
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
