<?php 
include("../config/config.php"); 

$company = pg_escape_string($_POST['company']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>AV.LEASING</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="act.css"></link>

<script language="Javascript">
<!--
function selectAll(select)
{
    with (document.frm_2)
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

function CheckSelect(field) {
    var temp=0;
    for (i = 0; i < field.length; i++){
        if( field[i].checked == true ) temp = temp+1;
    }
    if(temp > 0) {
        return true;
    } else {
        alert('กรุณาเลือกข้อมูล');
        return false;
    }
}
// -->
</script>    
    
</head>
<body>    
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
    <tr>
        <td>


<fieldset><legend><B>ยกเลิก Maker</B></legend>

<div align="right">
<form name="frm_fuc1" method="post" action="">
เลือกบริษัท
<SELECT NAME="company" onchange="document.frm_fuc1.submit()";>
    <option value="">เลือก</option>
<?php
$qry_inf=pg_query("select * from gas.\"Company\" ORDER BY \"coname\" ASC");
while($res_inf=pg_fetch_array($qry_inf)){
    $coid = $res_inf["coid"];
    $coname = $res_inf["coname"];
    if($_POST['company'] == $coid){
?>  
    <option value="<?php echo "$coid"; ?>" selected><?php echo "$coname"; ?></option>
<?php
    }else{
?>
    <option value="<?php echo "$coid"; ?>"><?php echo "$coname"; ?></option>        
<?php  
    }
}
?>
</SELECT>
</form>
</div>

<form name="frm_2" id="frm_2" method="post" action="frm_gs_maker_cl_send.php">
<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center"><input type="checkbox" name="aaa" onclick="javascript:selectAll('cid');"></td>
        <td align="center">ID</td>
        <td align="center">IDNO</td>
        <td align="center">วันที่ทำรายการ</td>
        <td align="center">วันที่ติดตั้ง</td>
        <td align="center">บริษัท</td>
        <td align="center">Model</td>
        <td align="center">ใบเสร็จ</td>
        <td align="center">ใบกำกับ</td>
        <td align="center">PayID</td>
        <td align="center">ราคาทุน</td>
        <td align="center">Vat</td>
        <td align="center">ผลรวม</td>
    </tr>
<?php
$qry=pg_query("SELECT * FROM gas.\"PoGas\" where status_approve = 'f' AND status_pay = 't' AND status_po = 't' AND invoice is not null AND idcompany='$company' AND bill is null ORDER BY \"idcompany\",\"idno\",poid ASC ");
$rows = pg_num_rows($qry);
while($res=pg_fetch_array($qry)){
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
    $payid = $res["payid"];
    
    $qry_name=pg_query("SELECT modelname FROM gas.\"Model\" WHERE \"modelid\" = '$idmodel' ");
    if($res_name=pg_fetch_array($qry_name)){
        $modelname = $res_name["modelname"];
    }
    
    $in+=1;
    if($in%2==0){
        echo "<tr class=\"odd\">";
    }else{
        echo "<tr class=\"even\">";
    }
?>
        <td align="center"><input type="checkbox" id="cid" name="cid[]" value="<?php echo "$id"; ?>"></td>
        <td align="center"><?php echo "$id"; ?></td>
        <td align="center"><?php echo "$idno"; ?></td>
        <td align="center"><?php echo "$date"; ?></td>
        <td align="center"><?php echo "$date_install"; ?></td>
        <td align="center"><?php echo "$idcompany"; ?></td>
        <td align="center"><?php echo "$modelname"; ?></td>
        <td align="center"><?php echo "$bill"; ?></td>
        <td align="center"><?php echo "$invoice"; ?></td>
        <td align="center"><?php echo "$payid"; ?></td>
        <td align="right"><?php echo number_format($costofgas,2); ?></td>
        <td align="right"><?php echo number_format($vatofcost,2); ?></td>
        <td align="right"><?php echo number_format($costofgas+$vatofcost,2); ?></td>
    </tr>
<?php
}

if($rows == 0){
?>
    <tr bgcolor="#ffffff">
        <td align="center" colspan=20><br>- ไม่พบข้อมูล -<br><br></td>
    </tr>

<?php
}else{
?>
    <tr bgcolor="#FFFFFF">
        <td align="center" colspan="20"><br><input name="button" id="button" type="submit" value="ยกเลิกรายการที่เลือก"><br><br></td>
    </tr>
<?php    
}
?>
</table>
</form>
</fieldset>


        </td>
    </tr>
</table>

</body>
</html>