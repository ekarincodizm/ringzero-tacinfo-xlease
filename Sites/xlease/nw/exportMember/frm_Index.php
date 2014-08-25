<?php
include("../../config/config.php");

if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ออกบัตรสมาชิก</title>
<script type="text/javascript" src="autocomplete.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<style type="text/css">
    #warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(255, 244, 250);
	padding: 5px;
	border: rgb(255, 128, 192) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
	}
.style1 {
	font-weight: bold;
}
.style2 {
	font-weight: bold;
}
</style>

<script language="Javascript">
function selectAll(select){
    with (document.form2)
    {
        var checkval = false;
        var i=0;

        for (i=0; i< elements.length; i++)
            if (elements[i].type == 'checkbox' && !elements[i].disabled)
                if (elements[i].name.substring(0, select.length) == select)
                {
                    checkval = !(elements[i].checked);    break;
                }

        for (i=0; i < elements.length; i++)
            if (elements[i].type == 'checkbox' && !elements[i].disabled)
                if (elements[i].name.substring(0, select.length) == select)
                    elements[i].checked = checkval;
    }
}
</script>
</head>

<body>
	<div id="swarp" style="width:800px; height:auto; margin-left:auto; margin-right:auto;">
		<div id="warppage" style="width:800px; height:auto;">
			<div style="text-align:center;";><h2>:: ออกบัตรสมาชิก ::</h2></div>		
			<div id="contentpage" style="height:auto;">
				<form method="post" name="form2" action="process_member.php">
					<div class="style5" style="width:auto; padding:10px;">
						<div style="float:left"><b>เลือกรายการที่ต้องการออกบัตรสมาชิก</b></div><div style="float:right;"><a href="" onclick="window.close();"><u><b>x ปิดหน้านี้</b></u></a></div>
						<div style="clear:both;"></div>
						<table width="778" border="0" style="background-color:#DBDBDB;" cellspacing="1" cellpadding="1">
						<tr style="background-color:#FF99CC;" align="center">
							<th width="50" height="25">No.</th>
							<th width="150">IDNO</th>
							<th>TRANIDRef1</th>
							<th>TRANIDRef2</th>
							<th>ชื่อ-นามสกุลลูกค้า</th>
							<th>ทะเบียนรถ</th>
							<th><a href="#" onclick="javascript:selectAll('cid');"><u>ทั้งหมด</u></a></th>
						</tr>
						<?php
						$a=0;
						$qry=pg_query("SELECT a.\"IDNO\", a.\"TranIDRef1\", a.\"TranIDRef2\",b.\"A_FIRNAME\",b.\"A_NAME\",b.\"A_SIRNAME\",c.\"C_REGIS\",d.\"car_regis\"
							FROM \"Fp\" a
							left join \"Fa1\" b on a.\"CusID\"=b.\"CusID\"
							left join \"VCarregistemp\" c on a.\"IDNO\"=c.\"IDNO\"
							left join \"FGas\" d on a.asset_id=d.\"GasID\"
							where a.\"IDNO\" NOT IN (SELECT e.\"IDNO\" FROM \"Fp_membercard\" e) order by a.\"P_STDATE\" DESC");
  
						while($res=pg_fetch_array($qry)){
							$a++;
							$a_firname = trim($res["A_FIRNAME"]);
							if($a_firname=="นาย" || $a_firname=="นาง" || $a_firname=="นางสาว" || $a_firname=="น.ส."){
								$txtfirname="คุณ";
							}else{
								$txtfirname=$res["A_FIRNAME"];
							}
							$A_NAME = $res["A_NAME"];
							$A_SIRNAME = $res["A_SIRNAME"];
							$fullname=$txtfirname.$A_NAME." ".$A_SIRNAME;
							$C_REGIS = $res["C_REGIS"];
							if($C_REGIS==""){
								$car_regis = $res["car_regis"];
							}else{
								$car_regis = $C_REGIS;
							}
							
							if($a%2==0){
								$color="#FFDDF0";
							}else{
								$color="#FFF4FF";
							}
							?>
						<tr style="background-color:<?php echo $color;?>" height="25" onMouseOver="this.style.backgroundColor='#FFB9DF';"onmouseout="this.style.backgroundColor='<?php echo $color;?>';" align="center">
							<td><?php echo $a; ?></td>
							<td><?php echo $res["IDNO"]; ?></td>
							<td><?php echo $res["TranIDRef1"]; ?></td>
							<td><?php echo $res["TranIDRef2"]; ?></td>
							<td align="left"><?php echo $fullname; ?></td>
							<td><?php echo $car_regis; ?></td>						
							<td><input type="checkbox" id="cid" name="cid[]" value="<?php echo $res["IDNO"];?>"></td>
						</tr>
						<?php
						}
						?>
						</table>
						<div style="padding:20px;text-align:center;">
							<input type="submit"  value="บันทึก" onclick="return checkdata()"/>
						</div>
					</div>
				</form>
			</div>
</div>
</div>
</body>
</html>
