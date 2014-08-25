<?php 
set_time_limit(0);
include("../config/config.php"); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>AV.LEASING</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <script type="text/javascript" src="autocomplete.js"></script>  
    <link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
<script language="JavaScript" type="text/JavaScript">
<!--
history.forward();

function disableback() {
    if(window.history.forward(1) != null)
        window.history.forward(1);
}
//-->
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
    
            var url = 'ajax_sum.php';
            //var pmeters = 'code='+document.getElementById("code").value;
            //var pmeters = 'code='+document.getElementById("code").value+'&date_start='+document.getElementById("date_start").value; // 2 Parameters
            var pmeters = 'code='+document.getElementById("code").value+'&date_start='+document.getElementById("date_start").value+'&date_end='+document.getElementById("date_end").value;
            HttPRequest.open('POST',url,true);

            HttPRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            HttPRequest.setRequestHeader("Content-length", pmeters.length);
            HttPRequest.setRequestHeader("Connection", "close");
            HttPRequest.send(pmeters);
            
            
            HttPRequest.onreadystatechange = function()
            {

                 if(HttPRequest.readyState == 3)  // Loading Request
                  {
                   document.getElementById("mySum").value = "Now is Loading...";
                  }

                 if(HttPRequest.readyState == 4) // Return Request
                  {
					var codetype=document.getElementById("code").value;
					if(codetype=="1.400" || codetype=="1.401" || codetype=="1.402" || codetype=="1.403" || codetype=="1.420" || codetype=="1.421"){
						document.getElementById('t1').style.display = 'none';
						document.getElementById('t2').style.display = '';
						document.getElementById('t3').style.display = 'none';
						document.getElementById('t4').style.display = 'none';
					}else if(codetype=="1.200" || codetype=="1.201" || codetype=="1.202" || codetype=="1.203"){
						document.getElementById('t1').style.display = 'none';
						document.getElementById('t2').style.display = 'none';
						document.getElementById('t3').style.display = '';
						document.getElementById('t4').style.display = 'none';
					}else{
						document.getElementById('t1').style.display = '';
						document.getElementById('t2').style.display = 'none';
						document.getElementById('t3').style.display = 'none';
						document.getElementById('t4').style.display = 'none';
					}
					document.getElementById("mySum").value = HttPRequest.responseText;
					document.getElementById("summary").value = parseFloat(document.getElementById("mySum").value)-parseFloat(document.getElementById("discount").value);
                  }
                
            }

            /*
            HttPRequest.onreadystatechange = call function .... // Call other function
            */

       }
    </script>    
    
<script language="javascript">
function fncChange()
{
    var StartDateF = new Date(document.insureforce.date_start.value);
    var StopDateF = new Date(document.insureforce.date_end.value);
    if (StopDateF <= StartDateF){
        alert('วันที่เริ่ม ต้องน้อยกว่าวันที่หมดอายุ');
    }else{     
      
        var myDate = new Date(document.insureforce.date_start.value)
        var date = myDate.getDate()
            if (date<10)
                date="0"+date
        var month = myDate.getMonth()+1
            if (month<10)
                month="0"+month
        var year = myDate.getFullYear()+1
            if (year < 1000)
                year+=1900
        document.insureforce.date_end.value = year+"/"+month+"/"+date
        
    }
}

function fncChangeStop()
{
    var StartDateF = new Date(document.insureforce.date_start.value);
    var StopDateF = new Date(document.insureforce.date_end.value);
    if (StopDateF <= StartDateF){
        alert('วันที่เริ่ม ต้องน้อยกว่าวันที่หมดอายุ');
        var myDate = new Date(document.insureforce.date_start.value)
        var date = myDate.getDate()+1
            if (date<10)
                date="0"+date
        var month = myDate.getMonth()+1
            if (month<10)
                month="0"+month
        var year = myDate.getFullYear()
            if (year < 1000)
                year+=1900
        document.insureforce.date_end.value = year+"/"+month+"/"+date
    }
}

function fncChangeMoney()
{
    var x =parseFloat(document.insureforce.mySum.value);
    var y =parseFloat(document.insureforce.discount.value);
    var sum = 0;
    sum=x-y;
    document.insureforce.summary.value=sum;
}
</script>     
    
</head>
<body onLoad="disableback();">

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

$qry_in=pg_query("select * from \"insure\".\"InsureForce\" WHERE (\"InsFIDNO\"='".pg_escape_string($_POST[h_arti_id])."')");
if($res_in=pg_fetch_array($qry_in)){
    $InsFIDNO = $res_in["InsFIDNO"];
    $IDNO = $res_in["IDNO"]; 
    $InsID = $res_in["InsID"];      
    $InsMark = $res_in["InsMark"];
    $Company = $res_in["Company"];
    $StartDate = $res_in["StartDate"];
    $EndDate = $res_in["EndDate"];
    $Code = $res_in["Code"];
    $Discount = $res_in["Discount"]; 
    $Premium = $res_in["Premium"];
    $capa = $res_in["Capacity"];
    $CoPayInsReady = $res_in["CoPayInsReady"];
    
    $sDiscount =  $Premium-$Discount;
    
    $strYear = date("Y",strtotime($StartDate));
    $strMonth = date("m",strtotime($StartDate));
    $strDate = date("d",strtotime($StartDate));
    $StartDate = $strYear."/".$strMonth."/".$strDate;
    
    $strYear2 = date("Y",strtotime($EndDate));
    $strMonth2 = date("m",strtotime($EndDate));
    $strDate2 = date("d",strtotime($EndDate));
    $EndDate = $strYear2."/".$strMonth2."/".$strDate2;
    
    $qry_ct=pg_query("select * from insure.\"VInsForceDetail\" WHERE (\"InsFIDNO\"='$InsFIDNO')");
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
    if (document.insureforce.insid.value == "") {
        theMessage = theMessage + "\n - กรุณาป้อนเลขกรมธรรม์";
    }
    if (document.insureforce.insmark.value == "") {
        theMessage = theMessage + "\n - กรุณาป้อนเลขเครื่องหมาย";
    }
    if (document.insureforce.code.value == "") {
        theMessage = theMessage + "\n - กรุณาเลือกประเภท";       
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

<fieldset><legend><B>แก้ไขประกันภัยภาคบังคับ (พรบ.) </B></legend>

<?php
if($error == 1){
    echo "<br>ไม่พบข้อมูล<br><br>";
}else{
?>

<form name="search" method="post" action="" onsubmit="return validate2(this)">
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
    <tr align="center">
      <td width="20%"><b>เลขกรมธรรม์, รหัสประกัน, ชื่อผู้เช่า</b>
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

<form id="insureforce" name="insureforce" method="post" action="frm_insure_force_edits.php" onsubmit="return validate(this)">
<table width="100%" border="0" cellSpacing="0" cellPadding="8" align="center">
    <tr align="left">
      <td width="20%"><b>รหัสประกัน</b></td>
      <td width="80%" colspan="3" class="text_gray"><a href="../up/frm_show.php?id=<?php echo $InsFIDNO; ?>&type=insfo&mode=1" target="_blank"><u><?php echo $InsFIDNO; ?></u></a></td>
   </tr>
   <tr align="left">
      <td><b>ชื่อ</b></td>
      <td colspan="3" class="text_gray"><?php echo $full_name." (".$IDNO.")" ?>
      <input type="hidden" name="gidno" value="<?php echo $IDNO; ?>">
      <input type="hidden" name="cus_id" value="<?php echo $cus_id; ?>">
      <input type="hidden" name="InsFIDNO" value="<?php echo $InsFIDNO; ?>">
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
      <select name="company">
          <option value="">เลือก</option>
<?php 
$qry_inf=pg_query("select * from \"insure\".\"InsureInfo\" ORDER BY \"InsCompany\" ASC");
while($res_inf=pg_fetch_array($qry_inf)){
    $InsCompany = $res_inf["InsCompany"];
    $InsFullName = $res_inf["InsFullName"];
    
    if($InsCompany == $Company){
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
      <td><b>เลขกรมธรรม์</b></td>
      <td colspan="3"><input type="text" name="insid" size="30" value="<?php echo "$InsID"; ?>"></td>
   </tr>
   <tr align="left">
      <td><b>เลขเครื่องหมาย</b></td>
      <td colspan="3"><input type="text" name="insmark" size="30" value="<?php echo "$InsMark"; ?>"></td>
   </tr>
   <tr align="left">
      <td><b>ประเภท</b></td>
      <td colspan="3">
      <select name="code" id="code" onchange="JavaScript:doCallAjax();">
          <option value="">เลือก</option>
 <?php 
$qry_inf=pg_query("select * from \"insure\".\"RateInsForce\" ORDER BY \"IFCode\" ASC");
while($res_inf=pg_fetch_array($qry_inf)){
    $IFCode = $res_inf["IFCode"];
    $IFDetail = $res_inf["IFDetail"];
    
    if($IFCode == $Code){
?>          
        <option value="<?php echo "$IFCode"; ?>" selected><?php echo "$IFCode"; ?></option>
<?php
    }else{
?>
        <option value="<?php echo "$IFCode"; ?>"><?php echo "$IFCode"; ?></option> 
<?php
    } 
} 
?>
      </select>
      </td>
   </tr>
   <tr align="left">
      <td><b>วันที่เริ่ม</b></td>
      <td colspan="3">
<input name="date_start" id="date_start" type="text" readonly="true" size="15" value="<?php echo "$StartDate"; ?>" onchange="JavaScript:fncChange(); JavaScript:doCallAjax();" style="text-align:center;"/><input name="button" type="button" onclick="displayCalendar(document.insureforce.date_start,'yyyy/mm/dd',this)" value="ปฏิทิน"/>      
      </td>
   </tr>
   <tr align="left">
      <td><b>วันที่หมดอายุ</b></td>
      <td colspan="3">
<input name="date_end" id="date_end" type="text" readonly="true" size="15" value="<?php echo "$EndDate"; ?>" onchange="JavaScript:fncChangeStop(); JavaScript:doCallAjax();" style="text-align:center;"/><input name="button" type="button" onclick="displayCalendar(document.insureforce.date_end,'yyyy/mm/dd',this)" value="ปฏิทิน" />      
      </td>
   </tr>
   <tr align="left">
			<?php
				if($Code=="1.400" || $Code=="1.401" || $Code=="1.402" || $Code=="1.403" || $Code=="1.420" || $Code=="1.421"){
					echo "<td id=\"t4\"><b>น้ำหนักรวม (กก.)</b></td>";
				}else if($Code=="1.200" || $Code=="1.201" || $Code=="1.202" || $Code=="1.203"){
					echo "<td id=\"t4\"><b>จำนวนที่นั่ง</b></td>";
				}else{
					echo "<td id=\"t4\"><b>ขนาดเครื่องยนต์</b></td>";
				}
			?>
			<td id="t1" style="display: none;"><b>ขนาดเครื่องยนต์</b></td>
			<td id="t2" style="display: none;"><b>น้ำหนักรวม (กก.)</b></td>
			<td id="t3" style="display: none;"><b>จำนวนที่นั่ง</b></td>
			<td colspan="3"><input type="text" name="capa" id="capa" size="15" value="<?php echo "$capa"; ?>" style="text-align:right;"></td>
   </tr>
   <tr align="left">
      <td><b>ส่วนลด</b></td>
      <td colspan="3"><input type="text" id="discount" name="discount" size="15" maxlength="7" style="text-align:right;" value="<?php echo "$Discount"; ?>" onkeyup="JavaScript:fncChangeMoney();"> <span class="text_gray">บาท.</span></td>
   </tr>
   
   <tr align="left">
      <td><b>ค่าเบิ้ยประกัน</b></td>
      <td colspan="3" class="text_gray"><input type="text" readonly="true" id="mySum" name="mySum" size="15" value="<?php echo "$Premium"; ?>" style="text-align:right; BACKGROUND-COLOR: #ffffff; BORDER: #ffffff 1px solid;"> บาท. (ข้อมูลเดิม <?php echo $Premium; ?>)</td>
   </tr>
    <tr align="left">
      <td><b>เบี้ยที่เก็บกับลูกค้า</b></td>
      <td colspan="3" class="text_gray"><input type="text" readonly="true" id="summary" name="summary" size="15" value="<?php echo "$sDiscount"; ?>" style="text-align:right; BACKGROUND-COLOR: #ffffff; BORDER: #ffffff 1px solid;"> บาท. (ข้อมูลเดิม <?php echo $sDiscount; ?>)</td>
   </tr>
    <tr align="left">
      <td colspan="4"><font color="red"><b>* กรุณาตรวจสอบยอดเงินให้ละเอียดก่อนนำไปใช้</b></font></td>
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
        return "gdata_force.php?q=" + this.value;
    });    
}    
 
// การใช้งาน
// make_autocom(" id ของ input ตัวที่ต้องการกำหนด "," id ของ input ตัวที่ต้องการรับค่า");
make_autocom("car_id","h_arti_id");
</script> 

</body>
</html>