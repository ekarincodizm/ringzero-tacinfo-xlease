<?php 
include("../config/config.php"); 
$date_check = pg_escape_string($_POST['date_check']);

if(empty($date_check)){
    $show_cdate = date("Y-m-d");   
}else{
    $show_cdate = $date_check;
}
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>AV.LEASING</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $('#tb_search').keyup(function(){
        $("#divshow").empty();
        $("#divshow").text('กำลังค้นหาข้อมูล กรุณารอสักครู่...');
        $("#divshow").load("frm_other_pay_panel.php?w="+ $("#tb_search").val());
    });
});
</script>

<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>      
    
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
	<tr>
		<td>

<div class="header"><h1>ระบบทะเบียนรถ</h1></div>
<div class="wrapper">
 
<fieldset><legend><b>ใส่ข้อมูลการชำระเงินค่าอื่นๆ</b></legend>

<div style="float:left"><b>ค้นหาทะเบียนรถ</b> <input type="text" name="tb_search" id="tb_search" size="30"></div>
<div style="float:right"></div>
<div style="clear:both"></div>

<div id="divshow">

<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">IDNO</td>
        <td align="center">ชื่อ</td>
        <td align="center">ทะเบียน</td>
        <td align="center">วันที่</td>
        <td align="center">จำนวนเงิน</td>
        <td align="center">รูปแบบ</td>
        <td align="center">วันนัด</td>
        <td align="center">สถานะ</td>
    </tr>

<?php
$qry_name=pg_query("select * from carregis.\"CarTaxDue\" where \"BookIn\"='false' AND \"TypeDep\" != '101' AND \"TypeDep\" != '105' ORDER BY \"IDNO\" ASC ");
        $rows = pg_num_rows($qry_name);
        while($res_name=pg_fetch_array($qry_name)){
            $IDCarTax = $res_name["IDCarTax"];
            $IDNO = $res_name["IDNO"];
            $CusAmt = $res_name["CusAmt"];
            $TypeDep = $res_name["TypeDep"];
            $ApointmentDate = $res_name["ApointmentDate"];
            $TaxDueDate = $res_name["TaxDueDate"];
                $TaxDueDate = date("Y-m-d",strtotime($TaxDueDate));
            
        $qry_name2=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
        if($res_name2=pg_fetch_array($qry_name2)){
            $asset_id = $res_name2["asset_id"];
            $full_name = $res_name2["full_name"];
            $asset_type = $res_name2["asset_type"];
            $C_REGIS = $res_name2["C_REGIS"];
            $car_regis = $res_name2["car_regis"];
                if($asset_type == 1){ $show_regis = $C_REGIS; } else { $show_regis = $car_regis; }
        }else{
            $full_name = "ไม่พบข้อมูล";
            $show_regis = "ไม่พบข้อมูล";
        }
        
        
        $qry_name4=pg_query("select * from \"TypePay\" WHERE \"TypeID\"='$TypeDep' ");
        if($res_name4=pg_fetch_array($qry_name4)){
            $TName = $res_name4["TName"];
        }
        
        $in+=1;
        if($in%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="center"><?php echo "$IDNO"; ?></td>
        <td align="left"><?php echo "$full_name"; ?></td>
        <td align="left"><?php echo "$show_regis"; ?></td>
        <td align="center"><?php echo "$TaxDueDate"; ?></td>
        <td align="right"><?php echo number_format($CusAmt,2); ?></td>
        <td align="left"><?php echo "$TName"; ?></td>
        <td align="center"><?php echo "$ApointmentDate"; ?></td>
        <td align="center"><a href="#" onclick="javascript:popU('frm_other_pay_add.php?cid=<?php echo "$IDCarTax";?>','a<?php echo $IDCarTax; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')"><img src="add.png" border="0" width="16" height="16" align="absmiddle" alt="ใส่ข้อมูลการชำระเงิน"></a>
        </td>
    </tr>
 <?php
        }

if($rows > 0){

 ?>
    <tr bgcolor="#ffffff" style="font-size:11px;">
        <td align="left" colspan="10"><b>ทั้งหมด</b> <?php echo $rows; ?> <b>รายการ</b></td>
    </tr>                                                                      
<?php } ?>
</table>
</div>

</fieldset>

</div>
		</td>
	</tr>
</table>

</body>
</html>