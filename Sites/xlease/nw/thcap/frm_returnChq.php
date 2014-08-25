<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) คืนเช็คให้กับลูกค้า</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){

    $("#sname").autocomplete({
        source: "s_idallchq.php",
        minLength:2
    });

    $('#btn1').click(function(){
		var aaaa = $("#sname").val();
        var brokenstring=aaaa.split("#");
		
		//ตรวจสอบว่ามีเลขที่สัญญานี้อยู่จริงหรือไม่ ถ้าไม่มีให้แจ้ง alert
		$.post("api.php",{
            cmd : "checkcontract" , 
            contractID : brokenstring[0], 
        },
        function(data){
            if(data == 1){
				// document.location="frm_returnChqSelect.php?contractID="+ brokenstring[0];
			   $("#panel").load('frm_returnChqSelect.php?contractID='+ brokenstring[0]);
            }else{
				alert("ไม่พบเลขที่สัญญา กรุณาตรวจสอบ!");
            }
        });  
    });
	
	

});
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
function selectAll(select){
    with (document.frm)
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
</script>
    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}

.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
</style>
    
</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div style="clear:both; padding: 10px;text-align:center;"><h2>(THCAP) คืนเช็คให้กับลูกค้า</h2></div>
			<div style="text-align:right;"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
			<fieldset><legend><B>ค้นหา</B></legend>
				<div class="ui-widget" align="center">
					<div style="margin:0;padding-bottom:10px;">
						<b>เลขที่สัญญา, ชื่อ-สกุล, บัตรประจำตัว</b>&nbsp;
						<input id="sname" name="sname" size="60" />&nbsp;
						<input type="button" id="btn1" value="ค้นหา"/>
					</div>
				</div>
			</fieldset>
			
        </td>
    </tr>
</table>

<div id="panel" style="padding-top: 10px;">
<div>
	<table width="1000" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
	<tr bgcolor="#FFFFFF">
		<td colspan="10"><b>เช็คที่รออนุมัติคืน</b></td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td colspan="10" align="left" height="25"><u><b>หมายเหตุ</b></u><font color="red"> <span style="background-color:#e5cdf9;">&nbsp;&nbsp;&nbsp;</span> รายการสีม่วง คือ เช็คค้ำประกันหนี้ FACTORING ในกรณีที่ ลูกหนี้ไม่จ่าย จะนำเช็คผู้ขายบิลเข้า ถ้าลูกหนี้จ่ายมาปกติ ก็จะคืนเช็คให้ลูกค้า</font></td>
	</tr>
	<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">	
		<td>เลขที่เช็ค</td>
		<td>วันที่บนเช็ค</td>
		<td>เลขที่สัญญา</td>
		<td>ธนาคารที่ออกเช็ค</td>
		<td>สาขา</td>
		<td>จ่ายบริษัท</td>
		<td>ยอดเช็ค(บาท)</td>	
		<td>ประเภทเช็ค</td>				
		<td>ผู้ทำรายการ</td>
		<td>วันเวลาที่ทำรายการ</td>
	</tr>
	<?php
	
	$qry_fr=pg_query("select * from finance.thcap_receive_cheque_return a
	left join finance.\"V_thcap_receive_cheque_chqManage\" b on a.\"revChqID\"=b.\"revChqID\"
	left join \"BankProfile\" c on b.\"bankOutID\"=c.\"bankID\"
	left join \"Vfuser\" d on a.\"add_user\"=d.\"id_user\"
	WHERE \"statusChq\" ='2' order by a.\"add_stamp\"");
	$nub=pg_num_rows($qry_fr);
	$i=0;
	while($res_fr=pg_fetch_array($qry_fr)){
		$revChqID = $res_fr["revChqID"];
		$bankChqNo=$res_fr["bankChqNo"];
		$bankChqDate = $res_fr["bankChqDate"]; 
		$bankName = $res_fr["bankName"]; 
		$bankOutBranch = $res_fr["bankOutBranch"]; 
		$bankChqToCompID = $res_fr["bankChqToCompID"]; 
		$bankChqAmt = $res_fr["bankChqAmt"]; 
		$revChqStatus=$res_fr["revChqStatus"];
		$revChqToCCID = $res_fr["revChqToCCID"];
		$isInsurChq = $res_fr["isInsurChq"];
		$add_stamp=$res_fr["add_stamp"];
		$fullname=$res_fr["fullname"];
		
		//ตรวจสอบว่าเป็นเช็คประเภทใด
		if($res_fr["isInsurChq"]=="0"){
			if($res_fr["isPostChq"]=="1"){
				$txtchq="เช็คชำระล่วงหน้า";
			}else{
				$txtchq="เช็คปกติ";
			}
		}else{
			$txtchq="เช็คค้ำประกัน";
		}
		
		$i+=1;
		if($i%2==0){
			if($isInsurChq==1){
				echo "<tr bgcolor=\"#e5cdf9\" align=center>";
			}else{
				echo "<tr class=\"odd\" align=center>";
			}
			
		}else{
			if($isInsurChq==1){
				echo "<tr bgcolor=\"#e5cdf9\" align=center>";
			}else{
				echo "<tr class=\"even\" align=center>";
			}
		}
	?>
		
		<td><?php echo $bankChqNo; ?></td>
		<td><?php echo $bankChqDate; ?></td>
		<td><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $revChqToCCID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"  >
		<font color="red"><u><?php echo $revChqToCCID; ?></u></font></span></td>
		<td align="left"><?php echo $bankName; ?></td>
		<td><?php echo $bankOutBranch; ?></td>
		<td><?php echo $bankChqToCompID; ?></td>
		<td align="right"><?php echo number_format($bankChqAmt,2); ?></td>
		<td><?php echo $txtchq; ?></td>
		<td align="left"><?php echo $fullname; ?></td>
		<td><?php echo $add_stamp; ?></td>			
	</tr>
	
	<?php
	} //end while
	if($nub == 0){
		echo "<tr><td colspan=11 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
	}
	?>
	</table>
</div>
<div style="padding-top:20px;">
	<?php 
	//ประวัติการอนุมัติ
	include"frm_returnChqHistory.php";
	?>
</div>
</div>

</body>
</html>