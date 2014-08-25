<?php
include("../../config/config.php");
//path = nw/Payments_Mortgage_Temporary/frm_Index.php
$ReNew = $_GET["ConID"];
$relpaths = redirect($_SERVER['PHP_SELF'],'nw/approve_except_debt');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ขอยกเว้นหนี้</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
$(document).ready(function(){
    $("#CONID").autocomplete({
       // source: "s_contractID.php",
	   source: "s_idall.php",
        minLength:1
    });

    $('#btn1').click(function(){
        //$("#panel").load("Payments_history.php?ConID="+ $("#CONID").val());
		window.location.href="Payments_history.php?ConID="+ $("#CONID").val();
    });
});

$(document).ready(function(){
    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
    });
});
</script>

<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

<script language="JavaScript">
<!--
function windowOpen() {
var
myWindow=window.open('search2.php','windowRef','width=600,height=400');
if (!myWindow.opener) myWindow.opener = self;
}
//--></script>
    
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
<form name="form1" id="form1" method="post" action="">
<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div style="text-align:center;padding-bottom: 10px;"><h2>(THCAP) ขอยกเว้นหนี้</h2></div>

			<fieldset><legend><B>ค้นหาข้อมูล</B></legend>

			<div class="ui-widget" align="center">

			<div style="margin:0">
			<b>เลขที่สัญญา</b>&nbsp;
			<?php
			echo "<input id=\"CONID\" name=\"CONID\" size=\"60\" value=\"$ReNew\" />&nbsp;";
			?>
			<input type="button" id="btn1" value="ค้นหา"/><!--<input name="openPopup" type="button" id="openPopup" onClick="Javascript:windowOpen();" value="ค้นหาจากชื่อผู้กู้หลัก/ร่วม" /> -->
			</div>

			<div id="panel" style="padding-top: 20px;"></div>

			</div>

			 </fieldset>

        </td>
    </tr>
</table>
</form>

<?php
if($ReNew != "")
{
?>
	<script type="text/javascript">
        $("#panel").load("Payments_history.php?ConID=" + $("#CONID").val());
	</script>
<?php
}
?>

<br><br>
<!-- รายการขอยกเว้นหนี้ที่รออนุมัติ -->
<div>
<table width="900" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="center" style="font-weight:bold;">รายการขอยกเว้นหนี้ที่รออนุมัติ</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<td>เลขที่สัญญา</td>
				<td>รหัสประเภท<br>ค่าใช้จ่าย</td>
				<td>รายละเอียด<br>ค่าใช้จ่าย</td>
				<td>ค่าอ้างอิงของ<br>ค่าใช้จ่าย</td>
				<td>วันที่ตั้งหนี้</td>
				<td>จำนวนหนี้</td>
				<td>ผู้ขอยกเว้นหนี้</td>
				<td>วันเวลาขอยกเว้นหนี้</td>
				<td>เหตุผล</td>
				<td>สถานะ</td>
			</tr>
			<?php
			$nowdate = nowDate();
			$qry_fr=pg_query("select * from \"thcap_temp_except_debt\" where \"Approve\" is null or date(\"appvStamp\") = '$nowdate' order by \"doerStamp\" , \"debtID\" ");
			$nub=pg_num_rows($qry_fr);
			while($res_fr=pg_fetch_array($qry_fr)){
				$debtID=$res_fr["debtID"];
				$doerUser=$res_fr["doerUser"];
				$doerStamp=$res_fr["doerStamp"];
				$remark=$res_fr["remark"];
				$Approve=$res_fr["Approve"];
				
				$qry_fullname = pg_query("select * from \"Vfuser\" where \"username\" = '$doerUser' ");
				while($res_fullname = pg_fetch_array($qry_fullname))
				{
					$fullname = $res_fullname["fullname"];
				}
				
				if($Approve == "")
				{
					$txtAppv = "<font color=\"#000000\">รอการอนุมัติ</font>";
				}
				elseif($Approve == "t")
				{
					$txtAppv = "<font color=\"#0000FF\">อนุมัติแล้ว</font>";
				}
				elseif($Approve == "f")
				{
					$txtAppv = "<font color=\"#FF0000\">ไม่อนุมัติ</font>";
				}
				
				$qry_detail=pg_query("select * from \"thcap_v_otherpay_debt_realother\" where \"debtID\" = '$debtID' ");
				while($res_detail=pg_fetch_array($qry_detail))
				{
					$typePayID = $res_detail["typePayID"];
					$typePayRefValue = $res_detail["typePayRefValue"];
					$typePayRefDate = $res_detail["typePayRefDate"];
					$typePayAmt = $res_detail["typePayAmt"];
					$contractID = $res_detail["contractID"];
				}
				
				// หารายละเอียดค่าใช้จ่ายนั้นๆ
				$qry_tpDesc = pg_query("select * from account.\"thcap_typePay\" where \"tpID\" = '$typePayID' ");
				while($res_tpDesc = pg_fetch_array($qry_tpDesc))
				{
					$tpDescShow = $res_tpDesc["tpDesc"];
				}
				
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
			?>
				<td><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="#0000FF"><u><?php echo $contractID;?></u></font></span></td>
				<td><?php echo $typePayID; ?></td>
				<td><?php echo $tpDescShow; ?></td>
				<td><?php echo $typePayRefValue; ?></td>
				<td><?php echo $typePayRefDate; ?></td>
				<td align="right"><?php echo number_format($typePayAmt,2); ?></td>
				<td align="left"><?php echo $fullname; ?></td>
				<td><?php echo $doerStamp; ?></td>
				<td><?php echo "<a href=\"#\" onclick=\"javascript:popU('detail_debt.php?debtID=$debtID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=400')\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a>"; ?></td>
				<td align="center"><?php echo $txtAppv; ?></td>
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=10 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>
</div>
<?php
include($relpaths."/frm_history_limit.php");
?>
</body>
</html>