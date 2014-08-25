<?php
session_start();
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> ยกเลิกสัญญาเช่าซื้อ <?php echo $_SESSION["session_company_name"]; ?></title>
<script type="text/javascript" src="autocomplete.js"></script>
<script language="JavaScript">
<!--
function setfocus()
{
 document.form1.text.value="";
 document.form1.chars.value=0;
 document.form1.text.focus();
}

function count()
{
	document.form1.chars.value = document.form1.text.value.length;
	if (document.form1.chars.value == 0)
		document.form1.words.value = 0;	
	if (document.form1.text.value.charAt(document.form1.text.value.length-1) != " ")
	{
		var code;
		var e = window.event;
		if (e.keyCode) code = e.keyCode;
		else if (e.which) code = e.which;
		var character = String.fromCharCode(code);
		if (character != ' ' && document.form1.chars.value > 0)
		{
			words = document.form1.text.value.split(' ');
			document.form1.words.value = words.length;
		}
	}
	return;
}

function chklist(){

	if(document.form1.text.value == ""){
		alert("กรุณาระบุเหตุผลที่ขอยกเลิกสัญญานี้ !!");
		document.form1.text.focus();
		return false;
		
	}else{
			if(confirm('ยืนยันขอยกเลิก')==true){
					form1.submit();
					document.form1.submit.disabled='true';
					return true;
			}else{ 
					return false;
			}
	}


}
</script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
    <style type="text/css">

    .mouseOut {
    background: #708090;
    color: #FFFAFA;
    }

    .mouseOver {
    background: #FFFAFA;
    color: #000000;
    }
   


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
    #color_hr
	{
	color:#999999;
	}  
	.style1 {color: #CC6600}
    .style2 {
	font-size: small;
	font-weight: bold;
}
    </style>

	
</head>

<body style="background-color:#999999;" onload="setfocus();">
<div style="width:auto; height:auto;">
<div id="warppage" style="width:610px;height:550px;">
<div style="width:auto; text-align:left; padding-left:15px; color:#666666; text-shadow: -1px -1px white, 0.5px 0.5px #333"><h2><?php echo $_SESSION["session_company_name"]; ?></h2></div>
  <div id="h2" style="height:20px; padding-left:15px; margin-top:20px;">
 
    <b>ยกเลิกสัญญาเช่าซื้อ</b> </div>
  <div id="contentpage" style="padding-left:15px; height:300px;"><hr style="color:#959596; height: 1px;"/>
  <?php
   $id=$_POST["h_id"];
   
   

  //data for detail
   $sql_vc=pg_query("select full_name,\"P_STDATE\",asset_type,\"C_REGIS\",\"C_CARNUM\",
                     \"C_CARNAME\",\"C_COLOR\",\"C_YEAR\" from \"VContact\" where \"IDNO\"='$id'");
   $res_vc=pg_fetch_array($sql_vc);
   $rowchk = pg_num_rows($sql_vc);
   if($rowchk == 0){
		echo "<meta http-equiv=\"refresh\" content=\"0; URL=cc_idno.php\">";
		echo "<script type='text/javascript'>alert('สัญญา  $id ไม่มีในระบบ กรุณาระบุเลขที่สัญญาใหม่')</script>";
		exit();
   
   }
   
   	if($res_vc["asset_type"]==1)
			{
			 $regis=trim($res_vc["C_REGIS"]);
			 $article="รถยนต์";
			}
			else
			{
			  $qry_gas=pg_query("select \"GasID\",car_regis from \"FGas\" where \"GasID\"='$ass_id' ");
			  $resgas=pg_fetch_array($qry_gas);
			  $regis=$resgas["car_regis"];
			   $article="แก๊ส";
			} 
   
  ?>
  <table width="600"  cellpadding="1">
  <tr>
    <td colspan="5">รายละเอียดเลขที่สัญญา &nbsp;&nbsp;<b><?php echo $id; ?></b></td>
  </tr>	
  <tr>
	<td colspan="5"><br></td>
  </tr>
  <tr>
    <td width="109" align="right">ชื่อ - นามสกุล :</td>
    <td width="190" ><b><?php echo $res_vc["full_name"]; ?></b></td>
    <td width="70" align="right">ทะเบียน :</td>
    <td width="174" colspan="2"><b><?php echo $regis; ?></b></td>
    </tr>
  <tr>
    <td align="right">วันที่ทำสัญญา :</td>
    <td><b><?php echo $res_vc["P_STDATE"]; ?></b></td>
    <td align="right">ร่น / ปี  :</td>
    <td colspan="2"><b><?php echo $res_vc["C_CARNAME"]." [".$res_vc["C_YEAR"]."]"; ?></b></td>
  </tr>
  <tr>
    <td align="right">เลขตัวถัง :</td>
    <td><b><?php echo $res_vc["C_CARNUM"]; ?></b></td>
    <td align="right">สี :</td>
    <td colspan="2"><b><?php echo $res_vc["C_COLOR"]; ?></b></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
</table>

  
 <?php
   
   
   
  //check data for cancle idno
  $qry_fr=pg_query("select A.\"IDNO\",B.\"IDNO\",B.\"Cancel\",C.\"IDNO\",C.\"Cancel\" from \"Fr\" A LEFT OUTER JOIN \"FVat\" B on B.\"IDNO\"=A.\"IDNO\" LEFT OUTER JOIN \"FOtherpay\" C on C.\"IDNO\"=A.\"IDNO\"  where A.\"IDNO\"='$id'");
			$numr_fr=pg_num_rows($qry_fr);
			  if($numr_fr!=0)
			  {
			    $res_fr="** ไม่สามารถยกเลิกได้ **";
				echo $res_fr;
			  }
			  else
			  {
			    $res_fr=" สามารถยกเลิกสัญญาเช่าซื้อได้ ";
			    echo $id."  ".$res_fr;
	?>
				<br /><br />
				
				
				
				เหตุผลที่ยกเลิกสัญญา<span class="style1">*  (ไม่เกิน 100 ตัวอักษร)</span><br />
				<form name="form1" id="form1" method="post" action="del_idno.php">
				<input type="hidden" id="h_idno" name="h_idno" value="<?php echo $id; ?>" />
				<input type="hidden" id="chkstate" name="chkstate" value="waitapp" />
  <textarea name="text" id="text" cols="75" rows="4" onKeyUp="count();" style="font-family:Tahoma; font-size:medium;"></textarea><br />
  <span class="style2">พิมพ์ไปแล้ว 
  <input name="chars" type="text" id="chars" size="2" style="border:0px;" value="0" readonly="true"> 
  ตัวอักษร </span><input type="button" value="ล้างข้อมูล" onclick="setfocus();" style="width:70px;height:25px "/><br />
  <br />
 <?php 
	$seappsql = pg_query("SELECT * FROM \"Fp_cancel_approve\" where \"IDNO\" = '$id' and appstatus='0' ");
	$seapprow = pg_num_rows($seappsql);
	
	
 if($seapprow > 0){ 
 ?>
   <div style="width:280px; float:left; text-align:center;">สัญญานี้กำลังอยู่ในกระบวนการอนุมัติ !</div>
 <?php 
 }else{
	$seappsql1 = pg_query("SELECT appstatus,\"appdate\" FROM \"Fp_cancel_approve\" where \"IDNO\" = '$id' and appstatus='1'");
	$seapprow1 = pg_num_rows($seappsql1);
	
	if($seapprow1 > 0){ 
 			$res = pg_fetch_array($seappsql1);?>
	<div style="width:280px; float:left; text-align:center;">สัญญานี้ถูกยกเลิกไปแล้วเมื่อวันที่<br><?php echo $res['appdate'];?> !</div>
<?php }else{ ?>
  <div style="width:280px; float:left; text-align:center;"><input type="submit" value="ตกลง" onclick="return chklist();" style="width:250px;height:50px "/></div> 
</form>
  <?php }
 } 
} 

?>
 	
  <div style="width:280px; float:left; text-align:center;"><input type="button" value="กลับ" onclick="parent.location='cc_idno.php'" style="width:250px;height:50px "/></div>
 	

  
  </div>
 
  
  <div id="footerpage">

  </div>
</div>
</div>
</body>
</html>
