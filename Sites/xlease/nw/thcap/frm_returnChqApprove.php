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
    <title>(THCAP) อนุมัติคืนเช็คให้กับลูกค้า</title>
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

</head>
<body>
<form name="frm" method="POST" action="api.php">
<table width="1000" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="header"><h1>(THCAP) อนุมัติคืนเช็คให้กับลูกค้า</h1></div>
		<div class="wrapper">		
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" height="25"><u><b>หมายเหตุ</b></u><font color="red"> <span style="background-color:#e5cdf9;">&nbsp;&nbsp;&nbsp;</span> รายการสีม่วง คือ เช็คค้ำประกันหนี้ FACTORING ในกรณีที่ ลูกหนี้ไม่จ่าย จะนำเช็คผู้ขายบิลเข้า ถ้าลูกหนี้จ่ายมาปกติ ก็จะคืนเช็คให้ลูกค้า</font></td>
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
				<td>ยืนยันรายการ<br>(<a onclick="javascript:selectAll('revchqid');" style="cursor:pointer;"><u>ทั้งหมด</u></a>)</td>
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
				<td>
					<input type="checkbox" name="revchqid[]" id="revchqid<?php echo $i ?>" value="<?php echo $revChqID; ?>">
				</td>				
			</tr>
			
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=11 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}else{
			?>
			<tr>
				<td align="right" colspan="11">
					<input type="submit" name="btnsub" value="อนุมัติ" onclick="return app(this.form,'app');">
					<input type="submit" name="btnnot" value="ไม่อนุมัติ" onclick="return app(this.form,'notapp');">
				</td>
			</tr>
			<?php
			}
			?>
			</table>
			<input type="hidden" name="cmd" id="cmd" value="approvereturn" >
			<input type="hidden" name="chkchoise" id="chkchoise" value="<?php echo $i ?>" >
			<input type="hidden" name="statusapp" id="statusapp" >
		</div>
	</td>
</tr>
</table>
</form>
<div>
	<?php 
	//ประวัติการอนุมัติ
	include"frm_returnChqHistory.php";
	?>
</div>
<script language=javascript>
function app(frm,app)
{
var con = $("#chkchoise").val();
var numchk;
numchk = 0;
	for(var num = 1;num<=con;num++){	
		if(document.getElementById("revchqid"+num).checked){
			numchk+=1;			
		}		
	}
	if(numchk == 0){
		alert("กรุณาเลือกรายการก่อน");
		return false;
	}else{
		if(app == 'notapp'){ 
			document.getElementById("statusapp").value = '0';
		}
		
		
		if(app == 'app'){
			if(confirm("ยืนยันอนุมัติคืนเช็ค!!")==true){ return true;}
			else{return false;}			
		}else{
			if(confirm("ยืนยันไม่อนุมัติคืนเช็ค!!")==true){ return true;}
			else{return false;}		
		}
		
	}	
}
</script>
</body>
</html>