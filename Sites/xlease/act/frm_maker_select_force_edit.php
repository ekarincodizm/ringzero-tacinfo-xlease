<?php 
include("../config/config.php"); 
$company = pg_escape_string($_POST['company']);
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>AV.LEASING</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
<SCRIPT LANGUAGE="JavaScript">
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
    for (i = 0; i < field.length; i++)
        if( field[i].checked == true ) temp = temp+1;
    
    if(temp > 0) {
        return true;
    } else {
        alert('กรุณาเลือกข้อมูล');
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

<fieldset><legend><a href="frm_maker.php"><B>Maker</a> > ประกันภัยภาคบังคับ (พรบ.) - ยกเลิกรายการ</B></legend>

<div align="right">
<form name="frm_fuc1" method="post" action="frm_maker_select_force_edit.php">
เลือกบริษัทประกัน 
<SELECT NAME="company" onchange="document.frm_fuc1.submit()";>
    <option value="">เลือก</option>
<?php
$qry_inf=pg_query("select * from \"insure\".\"InsureInfo\" ORDER BY \"InsCompany\" ASC");
while($res_inf=pg_fetch_array($qry_inf)){
    $InsCompany = $res_inf["InsCompany"];
    $InsFullName = $res_inf["InsFullName"];
    if($_POST['company'] == $InsCompany){
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
</SELECT>
</form>
</div>

<table width="100%" border="0" cellSpacing="1" cellPadding="1" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center"><input type="checkbox" name="aaa" onclick="javascript:selectAll('cid');"></td>
        <td align="center">InsID</td>
        <td align="center">IDNO</td>
        <td align="center">ชื่อ-สกุล</td>
        <td align="center">StartDate</td>
        <td align="center">Premium</td>
        <td align="center">NetPremium</td>
        <td align="center">OutStanding</td>
    </tr>

<form name="frm_2" id="frm_2" method="post" action="frm_maker_select_force_updates.php" onsubmit="return CheckSelect(document.frm_2.cid);">    
    
<?php
if( isset($_POST['company']) ){
    $qry_if=pg_query("select * from \"insure\".\"InsureForce\" WHERE \"Company\"='".pg_escape_string($_POST[company])."' AND \"Cancel\"='FALSE' AND \"CoPayInsReady\"='FALSE' AND \"CoPayInsID\" is not null ORDER BY \"InsID\" ASC");
    $rows = pg_num_rows($qry_if);
    while($res_if=pg_fetch_array($qry_if)){
        $InsFIDNO = $res_if["InsFIDNO"];
        $IDNO = $res_if["IDNO"];
        $Premium = $res_if["Premium"];
        $StartDate = $res_if["StartDate"];
        $InsID = $res_if["InsID"];
        $NetPremium = $res_if["NetPremium"];
        
        $rs=pg_query("select insure.\"outstanding_insforce\"('$InsFIDNO')");
        $outstanding=pg_fetch_result($rs,0);
        
        $qry_name=pg_query("select full_name from insure.\"VInsForceDetail\" WHERE \"InsFIDNO\"='$InsFIDNO'");
        if($res_name=pg_fetch_array($qry_name)){
            $full_name = $res_name["full_name"];    
        }
        
        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="center"><input type="checkbox" id="cid" name="cid[]" value="<?php echo "$InsFIDNO"; ?>"></td>
        <td align="left"><?php echo "$InsID"; ?></td>
        <td align="center"><?php echo "$IDNO"; ?></td>
        <td align="left"><?php echo "$full_name"; ?></td>
        <td align="center"><?php echo "$StartDate"; ?></td>
        <td align="right"><?php echo number_format($Premium,2); ?></td>
        <td align="right"><?php echo number_format($NetPremium,2); ?></td>
        <td align="right"><?php echo number_format($outstanding,2); ?></td>
    </tr>
<?php        
    }    
    if($rows == 0){
?>
    <tr bgcolor="#FFFFFF" style="font-size:12px;">
        <td align="center" colspan=10>ไม่พบข้อมูล</td>
    </tr>
<?php
    }    
}
?>
</table>
<?php
if($rows > 0){
?>
<table>
    <tr bgcolor="#FFFFFF">
        <td align="center" colspan="10"><br><input name="button" type="submit" value="ยกเลิกการชำระ"></td>
    </tr>
</table>
<?php
}
?>
</form>



</div>
		</td>
	</tr>
	<tr>
		<td><img src="../images/bg_03.jpg" width="700" height="15"></td>
	</tr>
</table>

</body>
</html>