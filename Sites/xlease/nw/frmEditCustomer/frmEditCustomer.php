<?php
session_start();
include("../../config/config.php");
$_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
if($_SESSION["session_company_code"]=="AVL")
{
 $file_namepic="logo_av.jpg";
}
else
{
 $file_namepic="logo_thaiace.jpg";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>แก้ไขการผูกคนกับสัญญา</title>
<script type="text/javascript" src="autocomplete.js"></script>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
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
.style1 {
	font-size: small;
	font-weight: bold;
}
.style2 {
	font-size: medium;
	font-weight: bold;
}
</style>
<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>

<body>
<div id="swarp" style="width:800px; height:auto; margin-left:auto; margin-right:auto;">
<div class="style2" id="super_head" style="padding-left:10px; height:90px; width:800px;"><span class="style2" style="padding-left:10px; height:60px; width:800px; "><div style="width:90px; float:left;"><img src="../images/<?php echo $file_namepic; ?>" width="80" height="80" /></div><div style="padding-top:20px;"><span><?php echo $_SESSION["session_company_name"]; ?></span><br /><?php echo $_SESSION["session_company_thainame"]; ?></div></div>
<div id="warppage" style="width:800px;">
<div id="headerpage" style="height:10px; text-align:center"></div>
<div class="style1" id="menu" style="height:25px; padding-left:10px; padding-top:10px; padding-right:10px;">แก้ไขผู้เช่าซื้อ/ผู้ค้ำ <hr /></div>
<div id="contentpage" style="height:auto;">
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style5" style="width:auto; height:100px; padding-left:10px;padding-top:10px;">
   <form method="post" action="frm_edit_cus.php">
  ค้นหา IDNO
    <input type="text" size="50" id="idno_names_m" name="idno_names_m" onKeyUp="findNames();" style="height:20;"/>
	<input name="h_id_s" type="hidden" id="h_id_s" value="" />
             <input type="submit" value="NEXT" />
             <input name="button" type="button" onclick="javascript:window.close();" value="CLOSE" />
			 
<script type="text/javascript">
function make_autocom(autoObj,showObj){
	var mkAutoObj=autoObj; 
	var mkSerValObj=showObj; 
	new Autocomplete(mkAutoObj, function() {
		this.setValue = function(id) {		
			document.getElementById(mkSerValObj).value = id;
		}
		if ( this.isModified )
			this.setValue("");
		if ( this.value.length < 1 && this.isNotClick ) 
			return ;	
		return "listdata.php?q=" + this.value;
    });	
}	

make_autocom("idno_names_m","h_id_s");
</script>
	</form>
  </div>
</div>
</div>
<div id="footerpage"></div>
</div>
</div>

<div style="padding-top:20px;">
<table width="800" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
<tr bgcolor="#FFFFFF">
	<td colspan="11" style="font-weight:bold;">รายการรออนุมัติ</td>
</tr>
<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
	<td>เลขที่สัญญา</td>
	<td>ผู้ที่ขอแก้</td>
	<td>วันเวลาที่ขอแก้</td>
	<td>ผลการแ้ก้ไข</td>
	<td>ดูรายละเอียด</td>
</tr>

<?php
$qry_fr=pg_query("select distinct(\"IDNO\") as idnos from \"ContactCus_Temp\" where \"statusApp\"='2'");
$nub=pg_num_rows($qry_fr);
while($res_fr=pg_fetch_array($qry_fr)){
	$IDNO=$res_fr["idnos"];
				
	$qry=pg_query("select \"IDNO\",b.\"fullname\" as \"userRequest\",\"userStamp\",c.\"fullname\" as \"appUser\",\"appStamp\",\"statusApp\",\"contempID\"
	from \"ContactCus_Temp\" a
	left join \"Vfuser\" b on a.\"userRequest\"=b.\"id_user\"
	left join \"Vfuser\" c on a.\"appUser\"=c.\"id_user\"
	where \"statusApp\"='2' and \"IDNO\"='$IDNO' limit(1)");
	if($res=pg_fetch_array($qry)){
		$IDNO=$res["IDNO"];
		$userRequest=$res["userRequest"];
		$userStamp=$res["userStamp"];
		$appUser=$res["appUser"];
		$appStamp=$res["appStamp"];
		$statusApp=$res["statusApp"];
		$contempID=$res["contempID"];
		
		if($statusApp=="0"){
			$txtapp="ไม่อนุมัติ";
		}else if($statusApp=="1"){
			$txtapp="อนุมัติ";
		}else{
			$txtapp="รออนุมัติ";
		}
	}
							
	$i+=1;
	if($i%2==0){
		echo "<tr class=\"odd\" align=center>";
	}else{
		echo "<tr class=\"even\" align=center>";
	}
	?>
	<td><span onclick="javascript:popU('../../post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO; ?>&type=outstanding','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="ดูตารางการชำระ"><font color="red"><u><?php echo $IDNO;?></u></font></span></td>
	<td align="left"><?php echo $userRequest; ?></td>
	<td><?php echo $userStamp; ?></td>
	<td><?php echo $txtapp; ?></td>
	<td><img src="images/detail.gif" width="19" height="19" onclick="javascript:popU('showdetail2.php?IDNO=<?php echo $IDNO;?>&&contempID=<?php echo $contempID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=600')" style="cursor: pointer;"></td>
	</tr>
	<?php
} //end while
if($nub == 0){
	echo "<tr><td colspan=8 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
}
?>
</table>
</div>

<!--ประวัติการอนุมัติ-->
<div style="padding-top:20px;">
	<?php
	$limit="limit 30";
	$txthead="ประวัติการอนุมัติ 30 รายการล่าสุด";
	include("frm_history.php");
	?>
</div>
</body>
</html>
