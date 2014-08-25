<?php
include("../config/config.php");

$nowdate = date("Y/m/d");
$next_year_date = date("Y/m/d", mktime(0, 0, 0, date("m"), date("d"), date("Y")+1)); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title></title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <script type="text/javascript" src="autocomplete.js"></script>  
    <link rel="stylesheet" href="autocomplete.css"  type="text/css"/>    

<script language="javascript">
function fncChangeMoney()
{   
    var a = 0;
    var b = 0;
    var c;
    
    a = document.insureforce.premium.value;
    b = document.insureforce.discount.value;
    c = a-b;

    document.insureforce.summary.value = c;
}

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

<script language="javascript">
function checkdata()
{
    var f = document.insureforce;
    var errMsg = "";
    var objFocus="";
    var achars="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    var bchars="0123456789.";

    if (f.company.value == "")
    {
        errMsg += "- บริษัทประกัน\n";
        if (!objFocus)
            objFocus = f.company;
    }
    
    if (f.code.value == "")
    {
        errMsg += "- รหัสประเภทรถ\n";
        if (!objFocus)
            objFocus = f.code;
    }

    if (f.invest.value == "")
    {
        errMsg += "- ทุนประกัน\n";
        if (!objFocus)
            objFocus = f.invest;
    }else if(f.invest.value.length != 0){ 
        for (var i=0;i<f.invest.value.length;i++){ 
            temp=f.invest.value.substring(i,i+1)
            if(bchars.indexOf(temp)==-1){ 
                errMsg += "ทุนประกัน ไม่ถูกต้อง โปรดกรอก (0-9)\n"; 
                if (!objFocus)
                    objFocus = f.invest; break;
            }
        }
    }

    if (f.premium.value == "")
    {
        errMsg += "- ค่าเบี้ยประกัน\n";
        if (!objFocus)
            objFocus = f.premium;
    }else if(f.premium.value.length != 0){ 
        for (var i=0;i<f.premium.value.length;i++){ 
            temp=f.premium.value.substring(i,i+1)
            if(bchars.indexOf(temp)==-1){ 
                errMsg += "ค่าเบี้ยประกัน ไม่ถูกต้อง โปรดกรอก (0-9)\n"; 
                if (!objFocus)
                    objFocus = f.premium; break;
            }
        }
    }

    if (f.discount.value == "")
    {
        errMsg += "- ส่วนลด\n";
        if (!objFocus)
            objFocus = f.discount;
    }else if(f.discount.value.length != 0){ 
        for (var i=0;i<f.discount.value.length;i++){ 
            temp=f.discount.value.substring(i,i+1)
            if(bchars.indexOf(temp)==-1){ 
                errMsg += "ส่วนลด ไม่ถูกต้อง โปรดกรอก (0-9)\n"; 
                if (!objFocus)
                    objFocus = f.discount; break;
            }
        }
    }

    if (f.summary.value == "")
    {
        errMsg += "- เบี้ยที่เก็บลูกค้า\n";
        if (!objFocus)
            objFocus = f.summary;
    }

    if (f.tempinsid.value == "")
    {
        errMsg += "- เลขรับแจ้ง\n";
        if (!objFocus)
            objFocus = f.tempinsid;
    }
    
    if (f.insuser.value == "")
    {
        errMsg += "- ผู้รับแจ้ง\n";
        if (!objFocus)
            objFocus = f.insuser;
    }
    
    if (errMsg == "")
    {
        f.btnsubmit.disabled = 1;
        return true;
    }
    else
    {
        errMsg = "กรุณากรอก:\n" + errMsg;
        alert(errMsg);
        objFocus.focus();
        return false;
    }
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
if(isset($_POST['h_arti_id'])){
    $qry_ct=pg_query("select \"full_name\",\"C_CARNUM\",\"C_REGIS\",\"CusID\",\"asset_id\",\"C_COLOR\",\"C_CARNAME\"    from \"UNContact\" WHERE (\"IDNO\"='".pg_escape_string($_POST[h_arti_id])."')");
    if($res_ct=pg_fetch_array($qry_ct)){
        $full_name = $res_ct["full_name"];      if($full_name == ""){ $full_name = "-"; }
        $car_num = $res_ct["C_CARNUM"];         if($car_num == ""){ $car_num = "-"; }
        $c_regis = $res_ct["C_REGIS"];          if($c_regis == ""){ $c_regis = "-"; }
        $cus_id = $res_ct["CusID"]; //
        $asset_id = $res_ct["asset_id"]; //
        $C_COLOR = $res_ct["C_COLOR"];
        $C_CARNAME = $res_ct["C_CARNAME"];
    }
}
?>

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

<fieldset><legend><B>เพิ่มข้ิอมูลประกันภัยภาคสมัครใจ - ลูกค้านอก</B></legend>

<form name="search" method="post" action="" onsubmit="return validate2(this)">
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
    <tr align="center">
      <td width="20%"><b>เลขตัวถัง , ชื่อผู้เช่า</b>
        <input name="h_arti_id" type="hidden" id="h_arti_id" value="<?php echo pg_escape_string($_POST['h_arti_id']); ?>" />
        <input type="text" id="car_id" name="car_id" size="50" value="<?php echo pg_escape_string($_POST['h_arti_id']); ?>">
        <input type="submit" name="submit" value="   ค้นหา   ">
      </td>
   </tr>
</table>
</form>
 <?php
if(isset($_POST['h_arti_id'])){
?>

<form name="insureforce" method="post" action="frm_insure_unforce_add.php" onsubmit="return checkdata();">
<table width="100%" border="0" cellSpacing="0" cellPadding="8" align="left">
   <tr align="left">
      <td width="20%"><b>ชื่อ</b></td>
      <td width="80%" colspan="3" class="text_gray"><?php echo $full_name." (".pg_escape_string($_POST['h_arti_id']).")" ?>
      <input type="hidden" name="gidno" value="<?php echo pg_escape_string($_POST['h_arti_id']); ?>">
      <input type="hidden" name="cus_id" value="<?php echo $cus_id; ?>">
      <input type="hidden" name="asset_id" value="<?php echo $asset_id; ?>">
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
<?php 
$qry_inf=pg_query("select \"InsCompany\",\"InsFullName\" from \"insure\".\"InsureInfo\" ORDER BY \"InsCompany\" ASC");
while($res_inf=pg_fetch_array($qry_inf)){
    $aaaaa++;
    $InsCompany = $res_inf["InsCompany"];
    $InsFullName = $res_inf["InsFullName"];
    
    if($aaaaa==1){
        $tmp_com_id = $InsCompany;
    }
?>          
    <option value="<?php echo "$InsCompany"; ?>"><?php echo "$InsFullName"; ?></option>
<?php
    }
?>
      </select>
      </td>
   </tr>
   <tr align="left">
      <td><b>รหัสประเภทรถ</b></td>
      <td colspan="3"><input type="text" id="code" name="code" size="15" maxlength="50">      
      </td>
   </tr>
   <tr align="left">
      <td><b>ประเภทประกัน</b></td>
      <td colspan="3">
      
<span id="myShow">
<select name="kind" id="kind">
<?php
$qry_inf1=pg_query("select \"CommCode\" from \"insure\".\"Commision\" WHERE \"InsCompany\" = '$tmp_com_id'  ORDER BY \"CommCode\" ASC");
while($res_inf1=pg_fetch_array($qry_inf1)){
    $CommCode = $res_inf1["CommCode"];
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
<input onchange="JavaScript:fncChange();" name="date_start" id="date_start" type="text" readonly="true" size="15" value="<?php echo $nowdate; ?>"/ style="text-align:center;"><input name="button" type="button" onclick="displayCalendar(document.insureforce.date_start,'yyyy/mm/dd',this)" value="ปฏิทิน" />
      </td>
   </tr>
   
   <tr align="left">
      <td><b>วันสิ้นสุด</b></td>
      <td colspan="3">
<input onchange="JavaScript:fncChangeStop();" name="date_end" id="date_end" type="text" readonly="true" size="15" value="<?php echo $next_year_date; ?>"/ style="text-align:center;"><input name="button" type="button" onclick="displayCalendar(document.insureforce.date_end,'yyyy/mm/dd',this)" value="ปฏิทิน" />
      </td>
   </tr>
   
   <tr align="left">
      <td><b>ทุนประกัน</b></td>
      <td colspan="3"><input type="text" id="invest" name="invest" size="15" maxlength="10" style="text-align:right;"> <span class="text_gray">บาท.</span></td>
   </tr>
   <tr align="left">
      <td><b>ค่าเบี้ยประกัน</b></td>
      <td colspan="3"><input type="text" id="premium" name="premium" size="15" maxlength="10" style="text-align:right;" onkeyup="JavaScript:fncChangeMoney();"> <span class="text_gray">บาท.</span></td>
   </tr>
   <tr align="left">
      <td><b>ส่วนลด</b></td>
      <td colspan="3"><input type="text" id="discount" name="discount" size="15" maxlength="10" style="text-align:right;" onkeyup="JavaScript:fncChangeMoney();"> <span class="text_gray">บาท.</span></td>
   </tr>
   <tr align="left">
      <td><b>เบี้ยที่เก็บลูกค้า</b></td>
      <td colspan="3"><input type="text" readonly="true" id="summary" name="collectcus" size="15" style="text-align:right; BACKGROUND-COLOR: #ffffff; BORDER: #ffffff 1px solid;"> <span class="text_gray">บาท.</span></td>
   </tr>
   <tr align="left">
      <td><b>เลขรับแจ้ง</b></td>
      <td colspan="3"><input type="text" id="tempinsid" name="tempinsid" size="15" maxlength="25"></td>
   </tr>
   <tr align="left">
      <td><b>ผู้รับแจ้ง</b></td>
      <td colspan="3"><input type="text" id="insuser" name="insuser" size="30" maxlength="20"></td>
   </tr>
</table>
</fieldset>
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="left">
   <tr align="center">
      <td><br><input type="submit" id="btnsubmit" name="btnsubmit" value="   บันทึก   "></td>
   </tr>
</table>
</form>
 <?php } ?>
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
        return "gdata_outcus.php?q=" + this.value;
    });    
}
 
make_autocom("car_id","h_arti_id");
</script>

</body>
</html>