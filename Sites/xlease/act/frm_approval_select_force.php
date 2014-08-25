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
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    
<SCRIPT LANGUAGE="JavaScript">
function checkAll(field)
{
for (i = 0; i < field.length; i++)
    field[i].checked = true ;
}

function uncheckAll(field)
{
for (i = 0; i < field.length; i++)
    field[i].checked = false ;
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

<fieldset><legend><B>อนุมัติ > ประกันภัยภาคบังคับ (พรบ.)</B></legend>

<div align="right">
<form name="frm_fuc1" method="post" action="frm_approval_select_force.php">
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
<br>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">InsID</td>
        <td align="center">เลขที่สัญญา</td>
        <td align="center">ชื่อ</td>
        <td align="center">ทะเบียน</td>
        <td align="center">วันเริ่ม</td>
        <td align="right">ค่าเบิ้ย</td>
        <td align="right">ยอดที่ต้องชำระ</td>
    </tr>  
   
<?php
if( isset($_POST['company']) ){
    
$qry_dc=pg_query("select A.*,B.* from \"insure\".\"InsureForce\" A 
                     LEFT OUTER JOIN \"insure\".\"PayToInsure\" B ON A.\"CoPayInsID\"=B.\"PayID\"
                     WHERE A.\"Company\"='".pg_escape_string($_POST[company])."' AND A.\"CoPayInsID\" is not null AND A.\"CoPayInsReady\"='FALSE' AND B.\"Company\" IS NOT NULL ORDER BY A.\"InsID\" ASC
                     ");
    $rows = pg_num_rows($qry_dc);
    while($res_if=pg_fetch_array($qry_dc)){
        $InsFIDNO = $res_if["InsFIDNO"];
        $InsID = $res_if["InsID"];
        $IDNO = $res_if["IDNO"];
        $StartDate = $res_if["StartDate"];
        $Premium = round($res_if["Premium"],2);
        $NetPremium = round($res_if["NetPremium"],2);
        $sCompany = $res_if["Company"];
        $CoPayInsID = $res_if["CoPayInsID"];
        $Remark = $res_if["Remark"];
        
        
        $qry_name=pg_query("select * from insure.\"VInsForceDetail\" WHERE \"InsFIDNO\"='$InsFIDNO'");
        if($res_name=pg_fetch_array($qry_name)){
            $full_name = $res_name["full_name"];
            //$asset_type = $res_name["asset_type"];
            $C_REGIS = $res_name["C_REGIS"];
            //$car_regis = $res_name["car_regis"];
            //if($asset_type == 1){ $show_carid = $C_REGIS; }
            //else{ $show_carid = $car_regis; }   
        }
        
        $c_com=pg_query("select \"insure\".cal_comm('PRB','$sCompany','$NetPremium')");
        $res_comms=pg_fetch_result($c_com,0);
        $showcom = $Premium - round($res_comms,2);
        $summary += $showcom;
        
        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="left"><?php echo "$InsID"; ?></td>
        <td align="center"><?php echo "$IDNO"; ?></td>
        <td align="left"><?php echo "$full_name"; ?></td>
        <td align="center"><?php echo "$C_REGIS"; ?></td>
        <td align="center"><?php echo "$StartDate"; ?></td>
        <td align="right"><?php echo  number_format($Premium,2); ?></td>
        <td align="right"><?php echo number_format($showcom,2); ?></td>
    </tr>
<?php        
    }
?>
    <tr bgcolor="#FFFFFF" style="font-size:12px;">
        <td align="right" colspan=6><b>รวม</b></td>
        <td align="right"><b><?php echo number_format($summary,2); ?></b></td>
    </tr>
<?php
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
if( $rows > 0 ){
?>

<script language="javascript">
function enableField(){
    document.approval.cash_money.disabled=true;
    document.approval.cheque_bank.disabled=false;
    document.approval.cheque_number.disabled=false;
    document.approval.cheque_date.disabled=false;
    document.approval.select_date.disabled=false;
    document.approval.cheque_money.disabled=false;
    document.approval.cash_money.value=0;
    document.approval.cheque_money.value=document.approval.hidden_money.value; 
}
function disableField(){
    document.approval.cash_money.disabled=false;
    document.approval.cheque_bank.disabled=true;
    document.approval.cheque_number.disabled=true;
    document.approval.cheque_date.disabled=true;
    document.approval.select_date.disabled=true;
    document.approval.cheque_money.disabled=true;
    document.approval.cheque_money.value=0;
    document.approval.cash_money.value=document.approval.hidden_money.value;
    
}
</script>

<form name="approval" method="post" action="frm_approval_select_force_update.php">
<input type="hidden" name="CoPayInsID" value="<?php echo $CoPayInsID; ?>">
<fieldset><legend><B>การชำระ</B></legend>
<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="left">
    <tr align="left">
        <td width="10%"><input type="radio" value="1" name="select_money" id="select_money" checked onclick="javascript:disableField();"><b>เงินสด</b></td>
        <td width="15%">ยอดเงิน</td>
        <td width="75%"><input type="text" name="cash_money" id="cash_money" value="<?php echo $summary; ?>" style="text-align:right;"> บาท.</td>
    </tr>
    <tr align="left">
        <td><input type="radio" value="2" name="select_money" id="select_money" onclick="javascript:enableField();"><b>เช็ค</b></td>
        <td>ธนาคาร</td>
        <td>
            <select name="cheque_bank" id="cheque_bank" disabled="true">
<?php 
$qry_bank=pg_query("select * from \"BankCheque\" ORDER BY \"BankCode\" ASC");
while($res_bank=pg_fetch_array($qry_bank)){
    $BankCode = $res_bank["BankCode"];
    $BankName = $res_bank["BankName"];
?>          
    <option value="<?php echo "$BankCode"; ?>"><?php echo "$BankName"; ?></option>
<?php 
    } 
?>      
          </select>
        </td>
    </tr>
    <tr align="left">
        <td></td>
        <td>เลขที่เช็ค</td>
        <td><input type="text" name="cheque_number" id="cheque_number" size="10" disabled="true"></td>
    </tr>
    <tr align="left">
    <td></td>
    <td>วันที่ออกเช็ค</td>
    <td><input disabled="true" name="cheque_date" id="cheque_date" type="text" readonly="true" size="11" value="<?php echo date('Y/m/d'); ?>" style="text-align:center;"/><input disabled="true" id="select_date" name="button" type="button" onclick="displayCalendar(document.approval.cheque_date,'yyyy/mm/dd',this)" value="ปฏิทิน" /></td>
    </tr>
    <tr align="left">
    <td></td>
    <td>จำนวนเงิน</td>
    <td><input type="text" name="cheque_money" id="cheque_money" disabled="true" value="0" style="text-align:right;"> บาท.</td>
    </tr>
    <tr align="left">
    <td></td>
    <td>หมายเหตุ</td>
    <td><textarea name="cheque_remark" id="cheque_remark" rows="3" cols="30"><?php echo $Remark; ?></textarea></td>
    </tr>
    <tr align="center">
    <td colspan="3"><br><input type="submit" name="submit" value="  อนุมัติ  "><br><br></td>
    </tr>
</table>
</fieldset>
<input type="hidden" name="company" value="<?php echo pg_escape_string($_POST[company]); ?>"> 
<input type="hidden" name="hidden_money" id="hidden_money" value="<?php echo $summary; ?>">
</form>
<?php
}
?>
</div>
		</td>
	</tr>
	<tr>
		<td><img src="../images/bg_03.jpg" width="700" height="15"></td>
	</tr>
</table>

</body>
</html>