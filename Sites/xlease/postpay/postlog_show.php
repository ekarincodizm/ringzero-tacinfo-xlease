<?php 
if($typeshow != 'frm_postav'){ include("../config/config.php"); }
$relpath = redirect($_SERVER['PHP_SELF'],''); 
$picpath = redirect($_SERVER['PHP_SELF'],'images');

if($typeshow == 'frm_postav'){
	$hearder = 'รายการชำระเงินที่ฉันเป็นผู้ทำ';
	//$ymd='2011-07-18';
	$ymd=date("Y-m-d");
	$userfind = "and \"UserIDPost\" = '$id_user2'";
	$formaction = "$relpath"."frm_postav.php";
}else{
	$hearder = 'ดูประวัติการทำรายการชำระเงิน';
	if($_POST['datecho']){ $ymd=$_POST['datecho']; }else{  $ymd=date("Y-m-d"); }
	$chkguy = $_POST['choty'];
	if($chkguy == 'someone'){ 
		$iduser= $_POST['guyselect']; 
		list($id_user2,$nameuser)=explode("#",$iduser);
		$userfind = "and \"UserIDPost\" = '$id_user2'";
		$checked2 = "checked";
	}else{  
		$userfind = "";
		$checked1 = "checked";
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<?php
if($typeshow != 'frm_postav'){
?>
<link type="text/css" href="<?php echo $relpath; ?>jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="<?php echo $relpath; ?>jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="<?php echo $relpath; ?>jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<?php
}
?><title>ดูประวัติการทำรายการชำระเงิน</title>
<style type="text/css">
  #warppage
	{
	width:900px;
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
<style type="text/css">

</style>
<script type="text/javascript">
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

$(document).ready(function(){	
	if(document.getElementById("chotysome").checked){
		$("#guyselect").show();
	}else{
		$("#guyselect").hide();
	}
	
	$("#datecho").datepicker({
        showOn: 'button',
        buttonImage: '<?php echo $picpath; ?>/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });
	$("#chotysome").click(function(){
		$("#guyselect").show();
	});
	$("#chotyall").click(function(){
		$("#guyselect").hide();
	});
	
	$("#guyselect").autocomplete({
        source: "s_name.php",
        minLength:2
    });
 });

function submit(frm){
	document.frm.submit();
}

function changeradio(){
	document.getElementById("chotysome").checked=true;
	
}
</script>
<!-- InstanceEndEditable -->
</head>

<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"><?php echo $hearder; ?></h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<form name="frm" action="<?php echo $formaction; ?>" method="post">
<div id="warppage" style="width:900px; text-align:left; margin-left:auto; margin-right:auto;">
  แสดงรายการชำระเงิน 
<?php if($typeshow != 'frm_postav'){ ?>
  <input type="radio" name="choty" id="chotyall" value="all" <?php echo $checked1 ?> >ทุกคน
  <input type="radio" name="choty" id="chotysome" value="someone"<?php echo $checked2 ?> >เฉพาะคน
  <input type="text" name="guyselect" id="guyselect" size="30" value="<?php echo $iduser;?>">
  วันที่ : <input type="text" name="datecho" id="datecho" value="<?php echo $ymd; ?>" size="11">
  &nbsp <input type="button" value=" ค้นหา " onclick="submit(this.form);">
<?php } ?>  
  <hr />
</form>  
  <div class="style5" style="width:auto; padding-left:0px;">
  <table width="900" border="0" cellpadding="1" cellspacing="1" style="background-color:#CCCCCC;">
  <tr>
    <td colspan="7">view postlog date <?php echo  $ymd; ?></td>
    </tr>
  <tr style="background-color:#EEF2DB;">
    <td width="105"  style="padding:0px 3px 0px 3px;">PostID</td>
    <td width="215"  style="padding:0px 3px 0px 3px;">ชื่อ-นามสกุล</td>
    <td width="120"  style="padding:0px 3px 0px 3px;">Name Post</td>
    <td width="59"  style="padding:0px 3px 0px 3px;">paytype</td>
    <td width="90" style="padding:0px 3px 0px 3px;"><div align="center">Amt</div></td>
    <td align="center" width="150"  style="padding:0px 3px 0px 3px;">เลขที่ใบเสร็จ</td>
	<td align="center" width="200"  style="padding:0px 3px 0px 3px;">ผู้กด receipt</td>
  </tr>
  <?php
  $qry_plog=pg_query("select * from \"PostLog\" where (\"PostDate\"='$ymd') $userfind and ((paytype='CA') or (paytype='CH')  or (paytype='TC') or (paytype='TT')) order by paytype ");
  while($reslog=pg_fetch_array($qry_plog)) 
    {
	 $list_pid=$reslog["PostID"];
	 $tmp_type="";
	 $m_paytype=$reslog["paytype"];
	 
	 $UserIDAccept = $reslog["UserIDAccept"];
	 $useracsql = pg_query("SELECT id_user, fname,lname,title FROM \"fuser\" where id_user = '$UserIDAccept'");
	 $reuseracc = pg_fetch_array($useracsql);
	 $useraccfullname = $reuseracc['title']." ".$reuseracc['fname']." ".$reuseracc['lname'];
	 
	// Query ข้อมูลของพนักงานเพื่อนำมาใช้ในการแสดงผลในหน้าจอว่า รับเงิน คือใคร
	$qry_user=pg_query("select * from \"Vfuser\" WHERE id_user='$id_user2' ");
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
		 
		 $qry_recept=pg_query("select \"refreceipt\" from \"FCash\" WHERE (\"PostID\"='$list_pid')"); 
		 while($res_recept=pg_fetch_array($qry_recept)){
		 
			if($res_recept["refreceipt"]!=""){
				$receiptid=$res_recept["refreceipt"]."<br>".$receiptid;
			}	
		 }
		
		// ห้ามคนเดียวกันรับเงินที่ตนเองตั้ง เว้นแต่เป็น Admin
		if($res_cus["UserIDAccept"]=="" && ($_SESSION["av_iduser"]!=$reslog["UserIDPost"] || $emplevel<="7"))
		{
			$pptype=$m_paytype;
			$bt_rec="ยังไม่ได้กด Receipt";
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
		 
		 $qry_recept=pg_query("select \"ReceiptNo\" from \"DetailCheque\" WHERE (\"PostID\"='$list_pid')"); 
		 while($res_recept=pg_fetch_array($qry_recept)){
			if($res_recept["ReceiptNo"]!=""){
				$receiptid=$res_recept["ReceiptNo"]."<br>".$receiptid;
			}	
		 }
		// ห้ามคนเดียวกันรับเงินที่ตนเองตั้ง เว้นแต่เป็น Admin
		if($res_fchq["UserIDAccept"]=="" && ($_SESSION["av_iduser"]!=$reslog["UserIDPost"] || $emplevel<="7"))	   
		 {
		   $pptype=$m_paytype;
		   $bt_rec="ยังไม่ได้กด Receipt";
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
		$qry_fp=pg_query("select * from \"FTACCheque\" a
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
			
			$qry_recept=pg_query("select \"refreceipt\" from \"FTACCheque\" WHERE (\"PostID\"='$list_pid')"); 
		 }else{
			$qry_name=pg_query("select A.\"UserIDAccept\",A.\"UserIDPost\",A.\"PostID\",B.\"PostID\",B.\"fullname\",D.id_user,D.username from \"PostLog\"  A
				LEFT OUTER JOIN \"FTACCheque\" B ON B.\"PostID\"=A.\"PostID\" 
				LEFT OUTER JOIN \"RadioContract\" E ON B.\"COID\"=E.\"COID\" 
				LEFT OUTER JOIN  \"Vfuser\" D ON D.id_user=A.\"UserIDPost\" 
				WHERE (A.\"PostID\"='$list_pid') limit(1) "); 
			 $qrystatus=1;
			 
			 $qry_recept=pg_query("select \"refreceipt\" from \"FTACCheque\" WHERE (\"PostID\"='$list_pid')"); 
		 }
		 $res_cus=pg_fetch_array($qry_name);
		 
		 
		 
		 while($res_recept=pg_fetch_array($qry_recept)){
			if($res_recept["refreceipt"]!=""){
				$receiptid=$res_recept["refreceipt"]."<br>".$receiptid;
			}	
		 }
		 
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
			$bt_rec="ยังไม่ได้กด Receipt";
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
		$qry_fp=pg_query("select * from \"FTACTran\" a
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
			$qry_recept=pg_query("select \"refreceipt\" from \"FTACTran\" WHERE (\"PostID\"='$list_pid')"); 
		 }else{
			$qry_name=pg_query("select A.\"UserIDAccept\",A.\"UserIDPost\",A.\"PostID\",B.\"PostID\",B.\"fullname\",D.id_user,D.username from \"PostLog\"  A
				LEFT OUTER JOIN \"FTACTran\" B ON B.\"PostID\"=A.\"PostID\" 
				LEFT OUTER JOIN \"RadioContract\" E ON B.\"COID\"=E.\"COID\" 
				LEFT OUTER JOIN  \"Vfuser\" D ON D.id_user=A.\"UserIDPost\" 
				WHERE (A.\"PostID\"='$list_pid') limit(1) "); 
			 $qrystatus=1;
			 $qry_recept=pg_query("select \"refreceipt\" from \"FTACTran\" WHERE (\"PostID\"='$list_pid')"); 
		 }
		 
		 
		 while($res_recept=pg_fetch_array($qry_recept)){
			if($res_recept["refreceipt"]!=""){
				$receiptid=$res_recept["refreceipt"]."<br>".$receiptid;
			}	
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
			$bt_rec="ยังไม่ได้กด Receipt";
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
	 
	
  ?>
  <tr <?php echo $sty_border; ?> valign="top">
    <td style="padding:0px 3px 0px 3px;" ><a style="cursor:pointer;" onclick="MM_openBrWindow('<?php echo $relpath ?>ca/detail_pay.php?p_id=<?php echo $reslog["PostID"]; ?>&p_type=<?php echo $m_paytype; ?>','รายละเอียดการชำระ','scrollbars=yes,width=530,height=250')"><u><?php echo $reslog["PostID"]; ?></u></a></td>
    <td style="padding:0px 3px 0px 3px;" ><?php echo $s_name; ?></td>
    <td style="padding:0px 3px 0px 3px;" ><?php echo $s_postID; ?></td>
    <td style="padding:0px 3px 0px 3px;" ><?php echo $reslog["paytype"]; ?></td>
    <td style="padding:0px 3px 0px 3px; text-align:right;" valign="top"><?php echo $num_amt; ?></td>
    <td align="center"><?php if($receiptid != ""){ echo $receiptid; }else{ echo $bt_rec;} ?></td>
	<td ><?php echo $useraccfullname; ?></td>
	
  </tr>
  <?php
  $receiptid="";
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
