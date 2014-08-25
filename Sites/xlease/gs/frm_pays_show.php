<?php 
include("../config/config.php"); 
$payid = pg_escape_string($_POST['payid']);
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="act.css"></link>

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
    
          var url = 'frm_pays_show_update.php';
          var pmeters = "add_remark=" + encodeURI( document.getElementById("add_remark").value) +
                        "&payid=<?php echo $payid; ?>";

            HttPRequest.open('POST',url,true);

            HttPRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            HttPRequest.setRequestHeader("Content-length", pmeters.length);
            HttPRequest.setRequestHeader("Connection", "close");
            HttPRequest.send(pmeters);
            
            
            HttPRequest.onreadystatechange = function()
            {

                if(HttPRequest.readyState == 3)  // Loading Request
                {
                    document.getElementById("mySpan").innerHTML = "Now is Loading...";
                }

                if(HttPRequest.readyState == 4) // Return Request
                {
                    if(HttPRequest.responseText == 'Y')
                    {
                        //window.location = 'AjaxPHPRegister3.php';
                    }
                    else
                    {
                        document.getElementById("mySpan").innerHTML = HttPRequest.responseText;
                    }
                }
                
            }

       }
    </script>
    
    
</head>
<body>    
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
    <tr>
        <td>

<fieldset><legend><b>รายการชำระให้บริษัทแก๊ส</b></legend>

<div align="right">
<form name="frm_fuc1" method="post" action="">
เลือก PayID 
<SELECT NAME="payid" onchange="document.frm_fuc1.submit()";>
    <option value="">เลือก</option>
<?php
$qry_inf=pg_query("select * from gas.\"PayToGas\" WHERE \"Cancel\"='false' AND idauthority is null ORDER BY \"payid\" ASC");
while($res_inf=pg_fetch_array($qry_inf)){
    $payids = $res_inf["payid"];
    if($_POST['payid'] == $payids){
?>
    <option value="<?php echo "$payids"; ?>" selected><?php echo "$payids"; ?></option>
<?php
    }else{
?>
    <option value="<?php echo "$payids"; ?>"><?php echo "$payids"; ?></option>
<?php
    }
}
?>
</SELECT>
</form>
</div>

<form name="frmMain">
<table width="100%" border="0" cellSpacing="1" cellPadding="1" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">ID</td>
        <td align="center">IDNO</td>
        <td align="center">วันที่ทำรายการ</td>
        <td align="center">วันที่ติดตั้ง</td>
        <td align="center">บริษัท</td>
        <td align="center">Model</td>
        <td align="center">ใบเสร็จ</td>
        <td align="center">ใบกำกับ</td>
        <td align="center">ราคาทุน</td>
        <td align="center">Vat</td>
        <td align="center">ผลรวม</td>
    </tr>
    
<?php
if( isset($payid) ){
    $qry_if=pg_query("select * from gas.\"PoGas\" WHERE \"payid\"='$payid' ORDER BY \"poid\" ASC");
    $rows = pg_num_rows($qry_if);
    while($res=pg_fetch_array($qry_if)){
        $id = $res["poid"];
        $idno = $res["idno"];
        $date = $res["podate"];
        $date_install = $res["date_install"];
        $idcompany = $res["idcompany"];
        $idmodel = $res["idmodel"];
        $costofgas = $res["costofgas"];
        $vatofcost = $res["vatofcost"];
        $bill = $res["bill"];
        $invoice = $res["invoice"];
        $status_pay = $res["status_pay"];
        
        $costofgas = round($costofgas, 2);
        $vatofcost = round($vatofcost, 2);
        
        $s_costofgas += $costofgas;
        $s_vatofcost += $vatofcost;
        $s_all += $costofgas+$vatofcost;
        
        $qry_name=pg_query("SELECT modelname FROM gas.\"Model\" WHERE \"modelid\" = '$idmodel' ");
        if($res_name=pg_fetch_array($qry_name)){
            $modelname = $res_name["modelname"];
        }
        
        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="center"><?php echo "$id"; ?></td>
        <td align="center"><?php echo "$idno"; ?></td>
        <td align="center"><?php echo "$date"; ?></td>
        <td align="center"><?php echo "$date_install"; ?></td>
        <td align="center"><?php echo "$idcompany"; ?></td>
        <td align="center"><?php echo "$modelname"; ?></td>
        <td align="center"><?php echo "$bill"; ?></td>
        <td align="center"><?php echo "$invoice"; ?></td>
        <td align="right"><?php echo number_format($costofgas,2); ?></td>
        <td align="right"><?php echo number_format($vatofcost,2); ?></td>
        <td align="right"><?php echo number_format($costofgas+$vatofcost,2); ?></td>
        
    </tr>
<?php        
    }
    if($rows == 0){
?>
    <tr bgcolor="#FFFFFF" style="font-size:12px;">
        <td align="center" colspan=20>ไม่พบข้อมูล</td>
    </tr>
<?php
    }
    $qry_rm=pg_query("select \"Remark\" from gas.\"PayToGas\" WHERE \"payid\"='$payid'");
    if($res_name=pg_fetch_array($qry_rm)){
        $Remark = $res_name["Remark"];    
    }    
}
?>


<?php
    if($rows > 0){
?>


    <tr bgcolor="#ffffff">
        <td colspan="2"><b>ทั้งหมด</b> <?php echo $rows; ?> <b>รายการ</b></td>
        <td colspan="6" align="right"><b>รวมเงินที่ต้องชำระ</b></td>
        <td align="right"><b><?php echo number_format($s_costofgas,2); ?></b></td>
        <td align="right"><b><?php echo number_format($s_vatofcost,2); ?></b></td>
        <td align="right"><b><?php echo number_format($s_all,2); ?></b></td>
    </tr>
    <tr align="left" bgcolor="#ffffff">
        <td colspan=20><br><b>หมายเหตุ</b><br><textarea name="add_remark" id="add_remark" rows="5" cols="90" style="font-size:11px;"><?php echo $Remark; ?></textarea></td>
    </tr>
    <tr align="left" bgcolor="#ffffff">
        <td colspan=20><input name="btnRegister" type="button" id="btnRegister" OnClick="JavaScript:doCallAjax();" value="บันทึกหมายเหตุ"> <span id="mySpan" style="color:#FF8080;"></span></td>
    </tr>
</table>
   </form>

<?php
        echo "<div align=\"right\"><br><a href=\"frm_pays_show_print.php?payid=$payid\" target=\"_blank\"><img src=\"icoPrint.png\" border=\"0\" width=\"17\" height=\"14\" alt=\"\"> <b>สั่งพิมพ์</b></a></div>";
    }
?>

</fieldset>

        </td>
    </tr>
</table>

</body>
</html>