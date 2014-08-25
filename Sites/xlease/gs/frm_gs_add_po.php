<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>AV.LEASING</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

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
            var pmeters = 'company='+document.getElementById("g_name").value;
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
    
<script language="JavaScript">
       var HttPRequest = false;

       function doCallAjax2() {
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
    
            var url = 'ajax_query2.php';
            //var pmeters = 'code='+document.getElementById("code").value;
            //var pmeters = 'code='+document.getElementById("code").value+'&date_start='+document.getElementById("date_start").value; // 2 Parameters
            var pmeters = 'type='+document.getElementById("g_type").value;
            HttPRequest.open('POST',url,true);

            HttPRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            HttPRequest.setRequestHeader("Content-length", pmeters.length);
            HttPRequest.setRequestHeader("Connection", "close");
            HttPRequest.send(pmeters);
            
            
            HttPRequest.onreadystatechange = function()
            {

                 if(HttPRequest.readyState == 3)  // Loading Request
                  {
                   document.getElementById("myShow2").innerHTML = "Now is Loading...";
                  }

                 if(HttPRequest.readyState == 4) // Return Request
                  {
                   document.getElementById("myShow2").innerHTML = HttPRequest.responseText;
                  }
                
            }

            /*
            HttPRequest.onreadystatechange = call function .... // Call other function
            */

       }
    </script>    
    
</head>
<body>    
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div align="right"><br><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
        
<fieldset><legend><B>ออก PO</B></legend>

<form name="frm_1" id="frm_1" method="post" action="frm_gs_add_po_send.php">

<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center">
    <tr>
        <td width="15%"><b>เลือกบริษัท</b></td>
        <td width="35%">
        
<select id="g_name" name="g_name" onchange="JavaScript:doCallAjax();">
    <option value="">เลือก</option>
<?php 
$qry_inf=pg_query("select distinct coid,coname from gas.\"Company\" ORDER BY \"coid\" ASC");
while($res_inf=pg_fetch_array($qry_inf)){
    $id = $res_inf["coid"];
    $name = $res_inf["coname"];
?>
    <option value="<?php echo "$id"; ?>"><?php echo "$name"; ?></option>
<?php 
    } 
?>      
</select>   
        
        </td>
        <td width="15%"><b>ราคาทุน</b></td>
        <td width="35%"><span id="myShow2">0.00</span> บาท</td>
    </tr>
    <tr>
        <td><b>Model</b></td>
        <td><span id="myShow"><select name="none001" id="none001"><option value="">เลือก</option></select></span> (เลือกบริษัทก่อน)</td>
        <td><b>วันที่ทำรายการ</b></td>
        <td><input name="date_post" type="text" size="15" readonly="true" value="<?php echo date("Y-m-d"); ?>"/><input name="button" type="button" onclick="displayCalendar(document.frm_1.date_post,'yyyy-mm-dd',this)" value="ปฏิทิน" /></td>
    </tr>
    <tr>
        <td><b>เลขตัวถัง</b></td>
        <td><input type="text" name="carnum" size="30"></td>
        <td><b>วันที่ติดตั้ง</b></td>
        <td><input name="date_install" type="text" size="15" readonly="true" value="<?php echo date("Y-m-d"); ?>"/><input name="button" type="button" onclick="displayCalendar(document.frm_1.date_install,'yyyy-mm-dd',this)" value="ปฏิทิน" /></td>
    </tr>
    <tr>
        <td><b>เลขเครื่อง</b></td>
        <td><input type="text" name="marnum" size="30"></td>
        <td><b>หมายเหตุ</b></td>
        <td><input type="text" name="memo" size="60"></td>
    </tr>
    <tr>
        <td colspan="10" align="center"><br><input name="button" id="button" type="submit" value=" บันทึก "></td>
    </tr>
</table>

</form>

</fieldset>

        </td>
    </tr>
</table>

</body>
</html>