<?php 
include("../config/config.php");

$id = pg_escape_string($_GET['id']);

$qry=pg_query("SELECT * FROM gas.\"PoGas\" where poid='$id'");
if($res=pg_fetch_array($qry)){
    $idcompany = $res["idcompany"];
    $idmodel = $res["idmodel"];
    $carnum = $res["carnum"];
    $marnum = $res["marnum"];
    $podate = $res["podate"];
    $date_install = $res["date_install"];
    $memo = $res["memo"];
    
    $costofgas = $res["costofgas"];
    $vatofcost = $res["vatofcost"];
    $summary = $costofgas+$vatofcost;
}

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
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
    <tr>
        <td>


<fieldset><legend><B>แก้ไข PO</B></legend>

<Script language="JavaScript">
<!-- Begin
function check1(){
    if(document.frm_1.g_name.value == ""){ 
        alert("กรุณาเลือก บริษัท"); 
        return false; 
    }
    if(document.frm_1.g_type.value == ""){ 
        alert("กรุณาเลือก Model"); 
        return false; 
    }
    if(document.frm_1.carnum.value == ""){ 
        alert("กรุณากรอก เลขตัวถัง"); 
        return false; 
    }
    if(document.frm_1.marnum.value == ""){ 
        alert("กรุณากรอก เลขเครื่อง"); 
        return false; 
    }
obt('frm_1');
}
// End -->
</Script>

<form name="frm_1" id="frm_1" method="post" action="frm_gs_edit_po_send.php" onsubmit="return check1();">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center">
    <tr>
        <td><b>รหัส PO</b></td>
        <td colspan="3"><?php echo $id; ?></td>
    </tr>
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
    
    if($idcompany == $id){
?>
        <option value="<?php echo "$id"; ?>" selected><?php echo "$name"; ?></option>
<?php
    }else{
?>
        <option value="<?php echo "$id"; ?>"><?php echo "$name"; ?></option>
<?php
    }
 
    } 
?>      
</select>   
        
        </td>
        <td width="15%"><b>ราคาทุน</b></td>
        <td width="35%"><span id="myShow2"><?php echo number_format($summary,0); ?></span> บาท</td>
    </tr>
    <tr>
        <td><b>Model</b></td>
        <td>
<span id="myShow">
<select name="g_type" id="g_type" onchange="JavaScript:doCallAjax2();"><option value="">เลือก</option>
<?php
$qry_inf1=pg_query("select * from gas.\"Model\" WHERE \"coid\" = '$idcompany' ORDER BY \"coid\" ASC");
while($res_inf1=pg_fetch_array($qry_inf1)){
    $modelid = $res_inf1["modelid"];
    $modelname = $res_inf1["modelname"];
    if($idmodel == $modelid)
        echo "<option value=\"$modelid\" selected>$modelname</option>";
    else
        echo "<option value=\"$modelid\">$modelname</option>";
}
?>
</select> 
</span> (เลือกบริษัทก่อน)</td>
        <td><b>วันที่ทำรายการ</b></td>
        <td><input name="date_post" type="text" size="15" readonly="true" value="<?php echo $podate; ?>"/><input name="button" type="button" onclick="displayCalendar(document.frm_1.date_post,'yyyy-mm-dd',this)" value="ปฏิทิน" /></td>
    </tr>
    <tr>
        <td><b>เลขตัวถัง</b></td>
        <td><input type="text" name="carnum" size="30" value="<?php echo $carnum; ?>"></td>
        <td><b>วันที่ติดตั้ง</b></td>
        <td><input name="date_install" type="text" size="15" readonly="true" value="<?php echo $date_install; ?>"/><input name="button" type="button" onclick="displayCalendar(document.frm_1.date_install,'yyyy-mm-dd',this)" value="ปฏิทิน" /></td>
    </tr>
    <tr>
        <td><b>เลขเครื่อง</b></td>
        <td><input type="text" name="marnum" size="30" value="<?php echo $marnum; ?>"></td>
        <td><b>หมายเหตุ</b></td>
        <td><input type="text" name="memo" size="60" value="<?php echo $memo; ?>"></td>
    </tr>
    <tr>
        <td colspan="10" align="center"><br><input name="button" id="button" type="submit" value=" บันทึก "></td>
    </tr>
</table>

</form>

</fieldset>

<div align="center"><br><input type="button" value=" กลับ " onclick="location.href='frm_gs_maker.php'"></div>

        </td>
    </tr>
</table>

</body>
</html>