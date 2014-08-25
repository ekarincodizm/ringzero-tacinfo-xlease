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
<script language=javascript>
<!--
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
//-->
</script>   
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
    <tr>
        <td>

<fieldset><legend><B>อนุมัติ</B></legend>

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

<br>

<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">ID</td>
        <td align="center">IDNO</td>
        <td align="center">วันที่</td>
        <td align="center">บริษัท</td>
        <td align="center">รุ่น/ประเภท</td>
        <td align="center">ใบเสร็จ</td>
        <td align="center">ใบกำกับ</td>
        <td align="center">ราคาทุน</td>
        <td align="center">Vat</td>
        <td align="center">ผลรวม</td>
    </tr>

   
<?php
if( isset($_POST['company']) ){
    
$qry=pg_query("SELECT * FROM gas.\"PoGas\" WHERE status_pay = 't' AND status_approve = 'f' AND invoice is not null AND idcompany='$company' ORDER BY \"idno\" ASC ");
$rows = pg_num_rows($qry);  
while($res=pg_fetch_array($qry)){
    $id = $res["poid"];
    $idno = $res["idno"];
    $date = $res["podate"];
    $idcompany = $res["idcompany"];
    $idmodel = $res["idmodel"];
    $costofgas = $res["costofgas"];
    $vatofcost = $res["vatofcost"];
    $payid = $res["payid"];
    $bill = $res["bill"]; if(empty($bill)) $bill = "-";
    $invoice = $res["invoice"]; if(empty($invoice)) $invoice = "-";
    
    $costofgas = round($costofgas, 2);
    $vatofcost = round($vatofcost, 2);
    
    $s_costofgas += $costofgas;
    $s_vatofcost += $vatofcost;
    $s_all += $costofgas+$vatofcost;
    
    $qry2=pg_query("SELECT modelname FROM gas.\"Model\" WHERE modelid = '$idmodel' ");
    if($res2=pg_fetch_array($qry2)){
        $modelname = $res2["modelname"];
    }
        $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="center"><a href="#" onclick="javascript:popU('frm_gs_detail_view.php?id=<?php echo "$id"; ?>','<?php echo "frm_gs_detail_view".$id;?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=200')"><u><?php echo "$id"; ?></u></a></td>
        <td align="center"><?php echo "$idno"; ?></td>
        <td align="center"><?php echo "$date"; ?></td>
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
        <td align="center" colspan=10>ไม่พบข้อมูล</td>
    </tr>
<?php
    }else{
?>
    <tr>
        <td align="right" colspan="7"><b>รวม</b></td>
        <td align="right"><?php echo number_format($s_costofgas,2); ?></td>
        <td align="right"><?php echo number_format($s_vatofcost,2); ?></td>
        <td align="right"><?php echo number_format($s_all,2); ?></td>
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

<form name="approval" method="post" action="frm_approval_select_send.php">
<input type="hidden" name="company" value="<?php echo $idcompany; ?>">
<input type="hidden" name="payid" value="<?php echo $payid; ?>">
<fieldset><legend><B>การชำระ</B></legend>
<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="left">
    <tr align="left">
        <td width="10%"><input type="radio" value="1" name="select_money" id="select_money" checked onclick="javascript:disableField();"><b>เงินสด</b></td>
        <td width="15%">ยอดเงิน</td>
        <td width="75%"><input type="text" name="cash_money" id="cash_money" value="<?php echo $s_all; ?>" style="text-align:right;"> บาท.</td>
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
    
$qry_py=pg_query("select * from gas.\"PayToGas\" where payid='$payid' ");
if($res_py=pg_fetch_array($qry_py)){
    $Remark = $res_py["Remark"];
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
    <td><textarea name="cheque_remark" id="cheque_remark" rows="6" cols="50">

---------------------------------------------
<?php echo $Remark; ?></textarea></td>
    </tr>
    <tr align="center">
    <td colspan="3"><br><input type="submit" name="submit" value="  อนุมัติ  "><br><br></td>
    </tr>
</table>

<input type="hidden" name="hidden_money" id="hidden_money" value="<?php echo $s_all; ?>">
</form>
<?php
}
?>
</fieldset>

        </td>
    </tr>
</table>

</body>
</html>