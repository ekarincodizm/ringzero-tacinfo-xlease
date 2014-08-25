<?php
include("../../config/config.php");
$s=mssql_select_db("Taxiacc") or die("Can't select database");

$CusID = $_GET['CusID'];
$AddType = $_GET['AddType'];

if($AddType=="")$AddType=3; //Default ที่อยู่ประเภท 3

?>

    
<script type="text/javascript">
$(document).ready(function(){
$("input[name='AddType']").change(function(){
        if( $('input[id=AddType]:checked').val() == "1" ){
			<?php

$qry_name=mssql_query("select a.CusID,b.RadioID,(replace(a.PreName,' ','')+replace(a.Name,' ','')+' '+replace(a.SurName,' ','')) as fullname ,a.* from TacCusDtl as a
left join RadioDoc as b on a.CusID=b.CusID 
where a.CusID = '$CusID' ");
$numrows = mssql_num_rows($qry_name);
while($res_name=mssql_fetch_array($qry_name)){
	$CusID=trim(iconv('WINDOWS-874','UTF-8',$res_name["CusID"])); if(empty($CusID)) $CusID="ไม่พบข้อมูล";
	$RadioID=trim(iconv('WINDOWS-874','UTF-8',$res_name["RadioID"])); if(empty($RadioID)) $RadioID="ไม่พบข้อมูล";
    $fullname=trim(iconv('WINDOWS-874','UTF-8',$res_name["fullname"]));
	
    	if(trim($res_name["Add1No"])!="" && trim($res_name["Add1No"])!="-" && trim($res_name["Add1No"])!="--" && trim($res_name["Add1No"])!="---")$Add1No =trim(iconv('WINDOWS-874','UTF-8',$res_name["Add1No"])); 
	if(trim($res_name["Add1SubNo"])!="" && trim($res_name["Add1SubNo"])!="-" && trim($res_name["Add1SubNo"])!="--" && trim($res_name["Add1SubNo"])!="---")$Add1SubNo ="หมู่ ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add1SubNo"])); 
	if(trim($res_name["Add1Soi"])!="" && trim($res_name["Add1Soi"])!="-" && trim($res_name["Add1Soi"])!="--" && trim($res_name["Add1Soi"])!="---")$Add1Soi ="ซอย ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add1Soi"])); 
	if(trim($res_name["Add1Rd"])!="" && trim($res_name["Add1Rd"])!="-" && trim($res_name["Add1Rd"])!="--" && trim($res_name["Add1Rd"])!="---")$Add1Rd ="ถนน ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add1Rd"]));
	if(trim($res_name["Add1Tum"])!="" && trim($res_name["Add1Tum"])!="-" && trim($res_name["Add1Tum"])!="--" && trim($res_name["Add1Tum"])!="---")$Add1Tum ="แขวง/ตำบล ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add1Tum"]));
	if(trim($res_name["Add1Aum"])!="" && trim($res_name["Add1Aum"])!="-" && trim($res_name["Add1Aum"])!="--" && trim($res_name["Add1Aum"])!="---")$Add1Aum  ="เขต/อำเภอ ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add1Aum"]));
	if(trim($res_name["Add1Prov"])!="" && trim($res_name["Add1Prov"])!="-" && trim($res_name["Add1Prov"])!="--" && trim($res_name["Add1Prov"])!="---")$Add1Prov ="จ. ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add1Prov"]));
	if(trim($res_name["Add1AreaCode"])!="" && trim($res_name["Add1AreaCode"])!="-" && trim($res_name["Add1AreaCode"])!="--" && trim($res_name["Add1AreaCode"])!="---")
	$Add1AreaCode =trim(iconv('WINDOWS-874','UTF-8',$res_name["Add1AreaCode"]));
	
		$address1 = "$Add1No  $Add1SubNo $Add1Soi $Add1Rd $Add1Tum";
		$address2 = " $Add1Aum $Add1Prov";
		$address3 = " $Add1AreaCode";
	

		
	}
?>
//document.form1.cus_h_addr.value='
document.getElementById('AddrType').value='1';
document.getElementById('addr').innerHTML = '<?php echo  trim($address1.$address2.$address3); ?>';

        }else if( $('input[id=AddType]:checked').val() == "2" ){
			
			<?php

$qry_name=mssql_query("select a.CusID,b.RadioID,(replace(a.PreName,' ','')+replace(a.Name,' ','')+' '+replace(a.SurName,' ','')) as fullname ,a.* from TacCusDtl as a
left join RadioDoc as b on a.CusID=b.CusID 
where a.CusID = '$CusID' ");
$numrows = mssql_num_rows($qry_name);
while($res_name=mssql_fetch_array($qry_name)){
	$CusID=trim(iconv('WINDOWS-874','UTF-8',$res_name["CusID"])); if(empty($CusID)) $CusID="ไม่พบข้อมูล";
	$RadioID=trim(iconv('WINDOWS-874','UTF-8',$res_name["RadioID"])); if(empty($RadioID)) $RadioID="ไม่พบข้อมูล";
    $fullname=trim(iconv('WINDOWS-874','UTF-8',$res_name["fullname"]));
	

		if(trim($res_name["Add2No"])!="" && trim($res_name["Add2No"])!="-" && trim($res_name["Add2No"])!="--" && trim($res_name["Add2No"])!="---")$Add2No =trim(iconv('WINDOWS-874','UTF-8',$res_name["Add2No"])); 
	if(trim($res_name["Add2SubNo"])!="" && trim($res_name["Add2SubNo"])!="-" && trim($res_name["Add2SubNo"])!="--" && trim($res_name["Add2SubNo"])!="---")$Add2SubNo ="หมู่ ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add2SubNo"])); 
	if(trim($res_name["Add2Soi"])!="" && trim($res_name["Add2Soi"])!="-" && trim($res_name["Add2Soi"])!="--" && trim($res_name["Add2Soi"])!="---")$Add2Soi ="ซอย ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add2Soi"])); 
	if(trim($res_name["Add2Rd"])!="" && trim($res_name["Add2Rd"])!="-" && trim($res_name["Add2Rd"])!="--" && trim($res_name["Add2Rd"])!="---")$Add2Rd ="ถนน ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add2Rd"]));
	if(trim($res_name["Add2Tum"])!="" && trim($res_name["Add2Tum"])!="-" && trim($res_name["Add2Tum"])!="--" && trim($res_name["Add2Tum"])!="---")$Add2Tum ="แขวง/ตำบล ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add2Tum"]));
	if(trim($res_name["Add2Aum"])!="" && trim($res_name["Add2Aum"])!="-" && trim($res_name["Add2Aum"])!="--" && trim($res_name["Add2Aum"])!="---")$Add2Aum  ="เขต/อำเภอ ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add2Aum"]));
	if(trim($res_name["Add2Prov"])!="" && trim($res_name["Add2Prov"])!="-" && trim($res_name["Add2Prov"])!="--" && trim($res_name["Add2Prov"])!="---")$Add2Prov ="จ. ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add2Prov"]));
	if(trim($res_name["Add2AreaCode"])!="" && trim($res_name["Add2AreaCode"])!="-" && trim($res_name["Add2AreaCode"])!="--" && trim($res_name["Add2AreaCode"])!="---")
	$Add2AreaCode =trim(iconv('WINDOWS-874','UTF-8',$res_name["Add2AreaCode"]));
	
	$address1 = "$Add2No  $Add2SubNo $Add2Soi $Add2Rd $Add2Tum";
	$address2 = " $Add2Aum $Add2Prov";
		$address3 = " $Add2AreaCode";
		

	   
		
	}
?>
//document.form1.cus_h_addr.value='
document.getElementById('AddrType').value='2';
document.getElementById('addr').innerHTML = '<?php echo  trim($address1.$address2.$address3); ?>';
        }else if( $('input[id=AddType]:checked').val() == "3" ){
						<?php

$qry_name=mssql_query("select a.CusID,b.RadioID,(replace(a.PreName,' ','')+replace(a.Name,' ','')+' '+replace(a.SurName,' ','')) as fullname ,a.* from TacCusDtl as a
left join RadioDoc as b on a.CusID=b.CusID 
where a.CusID = '$CusID' ");
$numrows = mssql_num_rows($qry_name);
while($res_name=mssql_fetch_array($qry_name)){
	$CusID=trim(iconv('WINDOWS-874','UTF-8',$res_name["CusID"])); if(empty($CusID)) $CusID="ไม่พบข้อมูล";
	$RadioID=trim(iconv('WINDOWS-874','UTF-8',$res_name["RadioID"])); if(empty($RadioID)) $RadioID="ไม่พบข้อมูล";
    $fullname=trim(iconv('WINDOWS-874','UTF-8',$res_name["fullname"]));
	

if(trim($res_name["Add3No"])!="" && trim($res_name["Add3No"])!="-" && trim($res_name["Add3No"])!="--" && trim($res_name["Add3No"])!="---")$Add3No =trim(iconv('WINDOWS-874','UTF-8',$res_name["Add3No"])); 
	if(trim($res_name["Add3SubNo"])!="" && trim($res_name["Add3SubNo"])!="-" && trim($res_name["Add3SubNo"])!="--" && trim($res_name["Add3SubNo"])!="---")$Add3SubNo ="หมู่ ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add3SubNo"])); 
	if(trim($res_name["Add3Soi"])!="" && trim($res_name["Add3Soi"])!="-" && trim($res_name["Add3Soi"])!="--" && trim($res_name["Add3Soi"])!="---")$Add3Soi ="ซอย ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add3Soi"])); 
	if(trim($res_name["Add3Rd"])!="" && trim($res_name["Add3Rd"])!="-" && trim($res_name["Add3Rd"])!="--" && trim($res_name["Add3Rd"])!="---")$Add3Rd = "ถนน ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add3Rd"]));
	if(trim($res_name["Add3Tum"])!="" && trim($res_name["Add3Tum"])!="-" && trim($res_name["Add3Tum"])!="--" && trim($res_name["Add3Tum"])!="---")$Add3Tum ="แขวง/ตำบล ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add3Tum"]));
	if(trim($res_name["Add3Aum"])!="" && trim($res_name["Add3Aum"])!="-" && trim($res_name["Add3Aum"])!="--" && trim($res_name["Add3Aum"])!="---")$Add3Aum  ="เขต/อำเภอ ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add3Aum"]));
	if(trim($res_name["Add3Prov"])!="" && trim($res_name["Add3Prov"])!="-" && trim($res_name["Add3Prov"])!="--" && trim($res_name["Add3Prov"])!="---")$Add3Prov ="จ. ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add3Prov"]));
	if(trim($res_name["Add3AreaCode"])!="" && trim($res_name["Add3AreaCode"])!="-" && trim($res_name["Add3AreaCode"])!="--" && trim($res_name["Add3AreaCode"])!="---")
	$Add3AreaCode =trim(iconv('WINDOWS-874','UTF-8',$res_name["Add3AreaCode"]));
	
	$address1 = "$Add3No  $Add3SubNo $Add3Soi $Add3Rd $Add3Tum";
	$address2 = " $Add3Aum $Add3Prov";
	$address3 = " $Add3AreaCode";
	  
		
	}
?>
//document.form1.cus_h_addr.value='

document.getElementById('AddrType').value='3';
document.getElementById('addr').innerHTML = '<?php echo  trim($address1.$address2.$address3); ?>';
}
    });

});
function checkdata(){

		if(document.getElementById('DocID').value == ""){
			alert("กรุณาใส่ข้อมูลเลขที่เอกสาร");
			document.getElementById('DocID').focus();
			return false;
		}if(document.getElementById('addr').value == ""){
			alert("กรุณาใส่ข้อมูลที่อยู่");
			document.getElementById('addr').focus();
			return false;
		}
	
}
</script>
    
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>


<fieldset><legend><B>ทำรายการส่งจดหมาย</B></legend>

<div class="ui-widget" align="left">

<?php

$qry_name=mssql_query("select a.CusID,b.RadioID,(replace(a.PreName,' ','')+replace(a.Name,' ','')+' '+replace(a.SurName,' ','')) as fullname ,a.* from TacCusDtl as a
left join RadioDoc as b on a.CusID=b.CusID 
where a.CusID = '$CusID' ");
$numrows = mssql_num_rows($qry_name);
while($res_name=mssql_fetch_array($qry_name)){
	$CusID=trim(iconv('WINDOWS-874','UTF-8',$res_name["CusID"])); if(empty($CusID)) $CusID="ไม่พบข้อมูล";
	$RadioID=trim(iconv('WINDOWS-874','UTF-8',$res_name["RadioID"])); if(empty($RadioID)) $RadioID="ไม่พบข้อมูล";
    $fullname=trim(iconv('WINDOWS-874','UTF-8',$res_name["fullname"]));
	
	
		if(trim($res_name["Add3No"])!="" && trim($res_name["Add3No"])!="-" && trim($res_name["Add3No"])!="--" && trim($res_name["Add3No"])!="---")$Add3No =trim(iconv('WINDOWS-874','UTF-8',$res_name["Add3No"])); 
	if(trim($res_name["Add3SubNo"])!="" && trim($res_name["Add3SubNo"])!="-" && trim($res_name["Add3SubNo"])!="--" && trim($res_name["Add3SubNo"])!="---")$Add3SubNo ="หมู่ ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add3SubNo"])); 
	if(trim($res_name["Add3Soi"])!="" && trim($res_name["Add3Soi"])!="-" && trim($res_name["Add3Soi"])!="--" && trim($res_name["Add3Soi"])!="---")$Add3Soi ="ซอย ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add3Soi"])); 
	if(trim($res_name["Add3Rd"])!="" && trim($res_name["Add3Rd"])!="-" && trim($res_name["Add3Rd"])!="--" && trim($res_name["Add3Rd"])!="---")$Add3Rd = "ถนน ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add3Rd"]));
	if(trim($res_name["Add3Tum"])!="" && trim($res_name["Add3Tum"])!="-" && trim($res_name["Add3Tum"])!="--" && trim($res_name["Add3Tum"])!="---")$Add3Tum ="แขวง/ตำบล ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add3Tum"]));
	if(trim($res_name["Add3Aum"])!="" && trim($res_name["Add3Aum"])!="-" && trim($res_name["Add3Aum"])!="--" && trim($res_name["Add3Aum"])!="---")$Add3Aum  ="เขต/อำเภอ ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add3Aum"]));
	if(trim($res_name["Add3Prov"])!="" && trim($res_name["Add3Prov"])!="-" && trim($res_name["Add3Prov"])!="--" && trim($res_name["Add3Prov"])!="---")$Add3Prov ="จ. ".trim(iconv('WINDOWS-874','UTF-8',$res_name["Add3Prov"]));
	if(trim($res_name["Add3AreaCode"])!="" && trim($res_name["Add3AreaCode"])!="-" && trim($res_name["Add3AreaCode"])!="--" && trim($res_name["Add3AreaCode"])!="---")
	$Add3AreaCode =trim(iconv('WINDOWS-874','UTF-8',$res_name["Add3AreaCode"]));
	
	$address1 = "$Add3No  $Add3SubNo $Add3Soi $Add3Rd $Add3Tum";
	$address2 =  "$Add3Aum $Add3Prov";
	$address3 = " $Add3AreaCode";
	   
		
	}
?>

<form name="frm_detail" action="frm_lt_user_detail_add.php" method="post" style="margin:0">

<input type="hidden" name="AddrType" id="AddrType" value="3">
<input type="hidden" name="CusID" value="<?php echo "$CusID"; ?>">
<table width="100%" cellpadding="5" cellspacing="0" border="0" id="panel">
<tr>
    <td width="20%"><b>ชื่อ/สกุลลูกค้า :</b></td>
    <td width="80%"><?php echo "$fullname ($CusID)"; ?></td>
</tr>
<tr>
  <td valign="top"><b>เลขที่เอกสาร :</b></td>
  <td><label for="DocID"></label>
    <input name="DocID" type="text" id="DocID" maxlength="13" /></td>
</tr>
<tr>
  <td valign="top"><b>ประเภทที่อยู่ :</b></td>
  <td><input type="radio" name="AddType" id="AddType" value="1"  <?php if($AddType == 1){ echo "checked"; }?>>ที่อยู่ตามทะเบียน
			<input type="radio" name="AddType" id="AddType" value="2" <?php if($AddType == 2){ echo "checked"; }?>>ที่อยู่ปัจจุบัน
			<input type="radio" name="AddType" id="AddType" value="3" <?php if($AddType == 3){ echo "checked"; }?>>ที่อยู่ส่งเอกสาร
            </td>
</tr>
<tr>
    <td valign="top"><b>ที่อยู่ส่งจดหมาย :</b></td>
	
    <td><textarea rows="5" id="addr" name="addr" cols="80" readonly><?php echo  $address1."\n".$address2."\n".$address3; ?></textarea></td>
</tr>

<tr>
    <td></td>
    <td><input type="submit" value="บันทึก" onClick="return checkdata();"></td>
</tr>
</table>
</form>
</div>

 </fieldset>

        </td>
    </tr>
</table>
