<?php
session_start();
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
$id_user=$_SESSION["av_iduser"];
$quryuser=pg_query("select \"emplevel\" from \"fuser\" where \"id_user\"='$id_user' ");
list($leveluser)=pg_fetch_array($quryuser);

$app_date = Date('Y-m-d H:i:s');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ยืนยันการเก็บรักษาเช็ค</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
function selectAll(select){
	frm=document.frm;
	var eleselect=$('input[name="result[]"]');
	var ele=$('input[name="revchqid[]"]');

	var num;
	num=0;
	for (i=0; i< ele.length; i++){
		if($(ele[i]).is(':checked')){
			num+=1;
		}
	}
		
	if(num>0 && ele.length!=num){
		for (i=0; i< ele.length; i++){
			$(ele[i]).attr ( "checked" ,"checked" );
			$(eleselect[i]).removeAttr('disabled');

		}
	}else if(num>0 && ele.length==num){
		for (i=0; i< ele.length; i++){
			 $(ele[i]).removeAttr('checked');
			$(eleselect[i]).attr('disabled','disabled');
		}
	}else{
		for (i=0; i< ele.length; i++){
			$(ele[i]).attr ( "checked" ,"checked" );
			$(eleselect[i]).removeAttr('disabled');
		}
	}
}
function processclick(a){
	var ele=$('input[name="revchqid[]"]');
	
	for(i=0;i<ele.length;i++){
		if(document.getElementById("revchqid"+i).checked){	
			document.getElementById("result"+i).disabled=false;
			document.getElementById("result"+i).focus();
		}else{
			document.getElementById("result"+i).disabled=true;	
			document.getElementById("result"+i).value='';			
		}
	}
}
</script>

</head>
<body>
<form name="frm" method="POST">
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="header"><h1>Thaiace Capital</h1></div>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" style="font-weight:bold;">(THCAP) ยืนยันการเก็บรักษาเช็ค</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" height="25"><u><b>หมายเหตุ</b></u><font color="red"> <span style="background-color:#e5cdf9;">&nbsp;&nbsp;&nbsp;</span> รายการสีม่วง คือ เช็คค้ำประกันหนี้ FACTORING ในกรณีที่ ลูกหนี้ไม่จ่าย จะนำเช็คผู้ขายบิลเข้า ถ้าลูกหนี้จ่ายมาปกติ ก็จะคืนเช็คให้ลูกค้า</font></td>
			</tr>
			<tr>
				<td align="center" colspan="11" height="40">วันที่รับเช็ค :<input type="text" name="keepChqDate" id="keepChqDate" value="<?php echo nowDate();?>" size="15" style="text-align: center;"  <?php if($leveluser > '5'){ echo "Readonly"; } ?>>	</td>
			</td>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">	
				<td>เลขที่เช็ค</td>
				<td>วันที่บนเช็ค</td>
				<td>เลขที่สัญญา</td>
				<td>ธนาคารที่ออกเช็ค</td>
				<td>สาขา</td>
				<td>จ่ายบริษัท</td>
				<td>ยอดเช็ค(บาท)</td>
				<td>ยืนยันการรับเช็ค(<a onclick="javascript:selectAll('revchqid');" style="cursor:pointer;"><u>ทั้งหมด</u></a>)</td>
				<td>หมายเหตุ</td>
			</tr>
			<?php
			
			$qry_fr=pg_query("select * from finance.\"V_thcap_receive_cheque_chqManage\" a
			left join \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\"
			WHERE \"revChqStatus\" ='9'  order by a.\"revChqID\"");
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
				<td>
					<input type="checkbox" name="revchqid[]" id="revchqid<?php echo $i ?>" value="<?php echo $revChqID; ?>" onclick="processclick('<?php echo $i; ?>')">
				</td>
				<td>
					<input type="text" id="result<?php echo $i; ?>" name="result[]" size="25" disabled="true">
				</td>
			</tr>
			
			<?php
				$i+=1;
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=9 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
						
			?>
			<tr>
				<td align="right" colspan="9">
					<input type="button" id="btnsub" value="ได้รับ" onclick="app(this.form,'app');">
					<input type="button" id="btnnot" value="ไม่ได้รับ" onclick="app(this.form,'notapp');">
				</td>
			</tr>
			</table>
				
			<input type="hidden" name="chkchoise" id="chkchoise" value="<?php echo $i ?>" >
			<input type="hidden" name="statusapp" id="statusapp" >
		</div>
	</td>
</tr>
</table>
</form>
<script language=javascript>
function app(frm,app)
{
var con = $("#chkchoise").val();
var numchk;
numchk = 0;
	for(var num = 0;num<con;num++){	
		if(document.getElementById("revchqid"+num).checked){
			numchk+=1;			
		}		
	}
	if(numchk == 0){
		alert("กรุณาเลือกรายการก่อน");
	}else{
		if(app == 'notapp'){ 
			document.getElementById("statusapp").value = '0';
		}
		
		
		if(app == 'app'){
			frm.action="addkeepChqDate.php";
			frm.submit();
			document.myform.submit.disabled='true';
		}else{
			alert("คุณกำลังเลือกไม่ได้รับเช็ค");
			if(confirm("ยืนยันอีกครั้ง ว่าไม่ได้รับเช็ค")==true){
				frm.action="process_keepcheque.php";
				frm.submit();
				document.myform.submit.disabled='true';	
			}	
		}
		
	}	
}

$(document).ready(function(){
<?php if($leveluser <= '1'){ ?>
	$("#keepChqDate ").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });	
<?php } ?>	
});
</script>
</body>
</html>