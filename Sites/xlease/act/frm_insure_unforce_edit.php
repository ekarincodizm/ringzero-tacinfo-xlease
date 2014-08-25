<?php include("../config/config.php"); ?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>AV.LEASING</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Expires" CONTENT="Tue, 01 Jan 1980 1:00:00 GMT">
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<link type="text/css" rel="stylesheet" href="act.css"></link>
    <script type="text/javascript" src="autocomplete.js"></script>
    <link rel="stylesheet" href="autocomplete.css"  type="text/css"/>

<script language="javascript">
$(document).ready(function(){
	$("#kind").change(function(){
		var src = $('#kind option:selected').attr('value');
		if ( src == "T3" ){
			$("#invest").val('0');
		}
	});
});
function fncChange()
{   
    var a = 0;
    var b = 0;
    var c;
    
    a = parseFloat(document.insureforce.premium.value);
    b = parseFloat(document.insureforce.discount.value);
    c = a-b;

    document.insureforce.summary.value = c;
}
</script>
 
 <script language="JavaScript">
       var HttPRequest = false;

       function doCallAjax() {
          HttPRequest = false;
          if (window.XMLHttpRequest) { // Mozilla, Safari,...
             HttPRequest = new XMLHttpRequest();
             if (HttPRequest.overrideMimeType) {
                HttPRequest.overrideMimeType('text/html');
             }
          } else if (window.ActiveXObject) { // IE
             try {
                HttPRequest = new ActiveXObject("Msxml2.XMLHTTP");
             } catch (e) {
                try {
                   HttPRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {}
             }
          } 
          
          if (!HttPRequest) {
             alert('Cannot create XMLHTTP instance');
             return false;
          }
    
            var url = 'ajax_query.php';
            //var pmeters = 'code='+document.getElementById("code").value;
            //var pmeters = 'code='+document.getElementById("code").value+'&date_start='+document.getElementById("date_start").value; // 2 Parameters
            var pmeters = 'company='+document.getElementById("company").value;
            HttPRequest.open('POST',url,true);

            HttPRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            HttPRequest.setRequestHeader("Content-length", pmeters.length);
            HttPRequest.setRequestHeader("Connection", "close");
            HttPRequest.send(pmeters);
            
            
            HttPRequest.onreadystatechange = function()
            {

                 if(HttPRequest.readyState == 3)  // Loading Request
                  {
                   document.getElementById("myShow").innerHTML = "Now is Loading...";
                  }

                 if(HttPRequest.readyState == 4) // Return Request
                  {
                   document.getElementById("myShow").innerHTML = HttPRequest.responseText;
                  }
                
            }

            /*
            HttPRequest.onreadystatechange = call function .... // Call other function
            */

       }
    </script>
    
</head>
<body>

<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
	<tr>
		<td background=><img src="../images/bg_01.jpg" height="15" width="700"></td>
	</tr>
	<tr>
		<td align="center" valign="top" background="../images/bg_02.jpg" style="background-repeat:repeat-y">

<div class="header"><h1>ระบบประกันภัย</h1></div>

<div class="wrapper">
<?php

if(isset($_POST[h_arti_id])){

$qry_in=pg_query("select * from \"insure\".\"InsureUnforce\" WHERE (\"InsUFIDNO\"='".pg_escape_string($_POST[h_arti_id])."')");
if($res_in=pg_fetch_array($qry_in)){
    $InsUFIDNO = $res_in["InsUFIDNO"];
    $IDNO = $res_in["IDNO"];
    $InsID = $res_in["InsID"];
    $TempInsID = $res_in["TempInsID"];      
    $Company = $res_in["Company"];
    $StartDate = $res_in["StartDate"];
    $EndDate = $res_in["EndDate"];
    $Code = $res_in["Code"];
    $Kind = $res_in["Kind"];
    $Invest = $res_in["Invest"];
    $Premium = $res_in["Premium"]; 
    $Discount = $res_in["Discount"];
    $CollectCus = $res_in["CollectCus"];
    $InsUser = $res_in["InsUser"];
    $Discount = $res_in["Discount"];
    $CoPayInsReady = $res_in["CoPayInsReady"];
    
        $strYear = date("Y",strtotime($StartDate));
        $strMonth = date("m",strtotime($StartDate));
        $strDate = date("d",strtotime($StartDate));
        $StartDate = $strYear."-".$strMonth."-".$strDate;
        
        $endstrYear = date("Y",strtotime($EndDate));
        $endstrMonth = date("m",strtotime($EndDate));
        $endstrDate = date("d",strtotime($EndDate));
        $EndDate = $endstrYear."-".$endstrMonth."-".$endstrDate;

    $qry_ct=pg_query("select * from insure.\"VInsUnforceDetail\" WHERE (\"InsUFIDNO\"='$InsUFIDNO')");
    if($res_ct=pg_fetch_array($qry_ct)){
        $full_name = $res_ct["full_name"];      if($full_name == ""){ $full_name = "-"; }
        $car_num = $res_ct["C_CARNUM"];         if($car_num == ""){ $car_num = "-"; }
        //$gas_number = $res_ct["gas_number"];    if($gas_number == ""){ $gas_number = "-"; }
        $c_regis = $res_ct["C_REGIS"];          if($c_regis == ""){ $c_regis = "-"; }
        //$car_regis = $res_ct["car_regis"];      if($c_regis == ""){ $c_regis = "-"; }
        $cus_id = $res_ct["CusID"];
        //$asset_type = $res_ct["asset_type"];    if($asset_type == 1){ $regis = $c_regis; }else{ $regis = $car_regis; }
        $C_COLOR = $res_ct["C_COLOR"];      
        $C_CARNAME = $res_ct["C_CARNAME"]; 
    }
    
}else{
    $error = 1;
}

}
?>

<script>
function validate(){

    var theMessage = "";
    var noErrors = theMessage;

    if (document.insureforce.company.value == "") {
        theMessage = theMessage + "\n - กรุณาเลือกบริษัทประกัน";       
    }
    if (document.insureforce.code.value == "") {
        theMessage = theMessage + "\n - กรุณาป้อนรหัสประเภทรถ";       
    }
    if (document.insureforce.kind.value == "") {
        theMessage = theMessage + "\n - กรุณาป้อนประเภทประกัน";       
    }
    if (document.insureforce.invest.value == "") {
        theMessage = theMessage + "\n - กรุณาืุป้อนทุนประกัน";
    }
    if (document.insureforce.premium.value == "") {
        theMessage = theMessage + "\n - กรุณาป้อนค่าเบี้ยประกัน";
    }
    if (document.insureforce.tempinsid.value == "") {
        theMessage = theMessage + "\n - กรุณาป้อนเลขรับแจ้ง";
    }
    if (document.insureforce.insuser.value == "") {
        theMessage = theMessage + "\n - กรุณาป้อนผู้รับแจ้ง";
    }
    

    // If no errors, submit the form
    if (theMessage == noErrors) {
        return true;
    } else {
        // If errors were found, show alert message
        alert(theMessage);
        return false;
    }
}
</script>

<script>
function validate2(){

    var theMessage = "";
    var noErrors = theMessage;

    if (document.search.car_id.value == "") {
        theMessage = "กรุณาใส่คำที่ต้องการค้นหา";
    }

    // If no errors, submit the form
    if (theMessage == noErrors) {
        return true;
    } else {
        // If errors were found, show alert message
        alert(theMessage);
        return false;
    }
}
</script>

<fieldset><legend><B>แก้ไขประกันภัยภาคสมัครใจ</B></legend>
<?php
if($error == 1){
    echo "<br>ไม่พบข้อมูล<br><br>";
}else{
?>

<form name="search" method="post" action="" onsubmit="return validate2(this)">
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
    <tr align="center">
      <td width="20%"><b>เลขรับแจ้ง, รหัสประกัน, ชื่อผู้เช่า</b>
        <input name="h_arti_id" type="hidden" id="h_arti_id" value="<?php echo pg_escape_string($_POST['h_arti_id']); ?>" />
        <input type="text" id="car_id" name="car_id" size="50" value="<?php echo pg_escape_string($_POST['h_arti_id']); ?>">
        <input type="submit" name="submit" value="   ค้นหา   ">
      </td>
   </tr>
</table>
</form>
<?php
if(isset($_POST[h_arti_id])){
?>
<form name="insureforce" method="post" action="frm_insure_unforce_edits.php" onsubmit="return validate(this)">
<table width="100%" border="0" cellSpacing="0" cellPadding="8" align="left">
   <tr align="left">
      <td width="20%"><b>รหัสประกัน</b></td>
      <td width="80%" colspan="3" class="text_gray"><a href="../up/frm_show.php?id=<?php echo $InsUFIDNO; ?>&type=insuf&mode=1" target="_blank"><u><?php echo $InsUFIDNO; ?></u></a></td>
   </tr>
   <tr align="left">
      <td width="20%"><b>ชื่อ</b></td>
      <td width="80%" colspan="3" class="text_gray"><?php echo $full_name." (".$IDNO.")" ?>
      <input type="hidden" name="gidno" value="<?php echo $IDNO; ?>">
      <input type="hidden" name="cus_id" value="<?php echo $cus_id; ?>">
      <input type="hidden" name="InsUFIDNO" value="<?php echo "$InsUFIDNO"; ?>"> 
      </td>
   </tr>
    <tr align="left">
      <td><b>เลขถัง</b></td>
      <td class="text_gray"><a href="../up/frm_show.php?id=<?php echo $car_num; ?>&type=reg&mode=2" target="_blank"><u><?php echo $car_num; ?></u></a></td>
      <td><b>ประเภทรถ</b></td>
      <td class="text_gray"><?php echo $C_CARNAME; ?></td>
   </tr>
   <tr align="left">
      <td><b>ทะเบียนรถ</b></td>
      <td class="text_gray"><?php echo $c_regis; ?></td>
      <td><b>สีรถ</b></td>
      <td class="text_gray"><?php echo $C_COLOR; ?></td>
   </tr>
   <tr align="left">
      <td><b>บริษัทประกัน</b></td>
      <td colspan="3">
      <select id="company" name="company" onchange="JavaScript:doCallAjax();">
          <option value="">เลือก</option>
<?php 
$qry_inf=pg_query("select * from \"insure\".\"InsureInfo\" ORDER BY \"InsCompany\" ASC");
while($res_inf=pg_fetch_array($qry_inf)){
    $InsCompany = $res_inf["InsCompany"];
    $InsFullName = $res_inf["InsFullName"];
    
    if($InsCompany == $Company){    $select_InsCompany = $InsCompany;
?>          
        <option value="<?php echo "$InsCompany"; ?>" selected><?php echo "$InsFullName"; ?></option>
<?php
    }else{
?>
        <option value="<?php echo "$InsCompany"; ?>"><?php echo "$InsFullName"; ?></option>
<?php 
    }
} 
?>   
      </select>
      </td>
   </tr>
   <tr align="left">
      <td><b>รหัสประเภทรถ</b></td>
      <td colspan="3"><input type="text" name="code" size="15" maxlength="50" value="<?php echo $Code; ?>">      
      </td>
   </tr>
   <tr align="left">
      <td><b>ประเภทประกัน</b></td>
      <td colspan="3">
     
<span id="myShow">
<select name="kind" id="kind">
<?php
$qry_inf1=pg_query("select \"CommCode\" from \"insure\".\"Commision\" WHERE \"TypeUnForce\" = 'TRUE' AND \"InsCompany\" = '$select_InsCompany'  ORDER BY \"CommCode\" ASC");
while($res_inf1=pg_fetch_array($qry_inf1)){
    $CommCode = $res_inf1["CommCode"];
    if($CommCode == $Kind)
        echo "<option value=\"$CommCode\" selected>$CommCode</option>";
    else
        echo "<option value=\"$CommCode\">$CommCode</option>";
}
?>
</select>
</span>      
      
      </td>
   </tr>
   <tr align="left">
      <td><b>วันที่เริ่ม</b></td>
      <td colspan="3">
<input name="date_start" type="text" readonly="true" size="15" value="<?php echo "$StartDate"; ?>" style="text-align:center;"><input name="button" type="button" onclick="displayCalendar(document.insureforce.date_start,'yyyy-mm-dd',this)" value="ปฏิทิน" />      
      </td>
   </tr>

   <tr align="left">
      <td><b>วันสิ้นสุด</b></td>
      <td colspan="3">
<input name="date_end" type="text" readonly="true" size="15" value="<?php echo "$EndDate"; ?>" style="text-align:center;"><input name="button" type="button" onclick="displayCalendar(document.insureforce.date_end,'yyyy-mm-dd',this)" value="ปฏิทิน" />      
      </td>
   </tr>

   <tr align="left">
      <td><b>ทุนประกัน</b></td>
      <td colspan="3"><input type="text" name="invest" id="invest" size="15" maxlength="10" style="text-align:right;" value="<?php echo "$Invest"; ?>"> <span class="text_gray">บาท.</span></td>
   </tr>
   <tr align="left">
      <td><b>ค่าเบี้ยประกัน</b></td>
      <td colspan="3"><input type="text" id="premium" name="premium" size="15" maxlength="10" style="text-align:right;" value="<?php echo "$Premium"; ?>" onkeyup="JavaScript:fncChange();"> <span class="text_gray">บาท.</span></td>
   </tr>
   <tr align="left">
      <td><b>ส่วนลด</b></td>
      <td colspan="3"><input type="text" id="discount" name="discount" size="15" maxlength="10" style="text-align:right;" value="<?php echo "$Discount"; ?>" onkeyup="JavaScript:fncChange();"> <span class="text_gray">บาท.</span></td>
   </tr>
   <tr align="left">
      <td><b>เบี้ยที่เก็บลูกค้า</b></td>
      <td colspan="3"><input type="text" readonly="true" id="summary" name="collectcus" size="15" style="text-align:right; BACKGROUND-COLOR: #ffffff; BORDER: #ffffff 1px solid;" value="<?php echo "$CollectCus"; ?>"> บาท.</td>
   </tr>
   <tr align="left">
      <td><b>เลขรับแจ้ง</b></td>
      <td colspan="3"><input type="text" name="tempinsid" size="15" value="<?php echo "$TempInsID"; ?>" maxlength="25"></td>
   </tr>
   <tr align="left">
      <td><b>ผู้รับแจ้ง</b></td>
      <td colspan="3"><input type="text" name="insuser" size="30" value="<?php echo "$InsUser"; ?>" maxlength="20"></td>
   </tr>
</table>
</fieldset>
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="left">
   <tr align="center">
      <td>
<?php if($CoPayInsReady == 't'){ ?>      
       <br><font color="#ff0000"><b>ไม่สามารถแก้ไขได้ กรมธรรม์รายการนี้จ่ายแล้ว</b></font>
<?php }else{ ?>
    <br><input type="submit" name="submit" value="   บันทึก   ">
<?php } ?>      
      </td>
   </tr>
</table>
</form>
 <?php } } ?>
</div>
		</td>
	</tr>
	<tr>
		<td><img src="../images/bg_03.jpg" width="700" height="15"></td>
	</tr>
</table>

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
        return "gdata_unforce.php?q=" + this.value;
    });    
}    
 
// การใช้งาน
// make_autocom(" id ของ input ตัวที่ต้องการกำหนด "," id ของ input ตัวที่ต้องการรับค่า");
make_autocom("car_id","h_arti_id");
</script> 

</body>
</html>