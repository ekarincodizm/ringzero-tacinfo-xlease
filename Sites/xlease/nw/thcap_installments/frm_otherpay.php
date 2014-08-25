<?php
session_start();
include("../../config/config.php");

$idno = pg_escape_string($_GET["idno"]);

//ตรวจสอบว่ามีเลขที่สัญญานี้ในระบบจริงหรือไม่
$qrychk = pg_query("select \"contractID\" from \"thcap_contract\" where \"contractID\" = '$idno'");
if(pg_num_rows($qrychk)==0){
	echo "<div align=center><h2>กรุณาระบุเลขที่สัญญาให้ถูกต้อง</h2></div>";
	exit;
}
	
//-- หา minPayType
if($idno != "")
{
	$qry_minPayType = pg_query("select account.\"thcap_mg_getMinPayType\"('$idno')");
	$res_minPayType = pg_fetch_array($qry_minPayType);
	list($minPayType) = $res_minPayType;
}
//-- จบการหา minPayType

//ค้นหาชื่อผู้กู้หลัก
$qry_namemain=pg_query("select \"thcap_fullname\" from  \"vthcap_ContactCus_detail\"
where \"contractID\"='$idno' and \"CusState\" ='0'");
$nummain=pg_num_rows($qry_namemain);
// if($resnamemain=pg_fetch_array($qry_namemain)){
	// $name3=trim($resnamemain["thcap_fullname"]);
// }
if($nummain > 0)
{
	$i=1;
	while($resnamemain=pg_fetch_array($qry_namemain))
	{
		$name1=trim($resnamemain["thcap_fullname"]);
		if($i > 1)
		{
			$name3 = $name3." , ";
		}
		$name3 = $name3.$name1;
		$i++;
	}
}

//ค้นหาชื่อผู้กู้ร่วม
$qry_name=pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\"
where \"contractID\"='$idno' and \"CusState\" = '1'");
$numco=pg_num_rows($qry_name);
$i=1;
$nameco="";
if($numco!=0){
	while($resco=pg_fetch_array($qry_name)){
		$name2=trim($resco["thcap_fullname"]);
		if($numco==1){ //กรณีมีชื่อเดียวไม่ต้องใส่ comma
			$nameco=$name2;
		}else{
			if($i==$numco){
				$nameco=$nameco.$name2;
			}else{
				$nameco=$nameco.$name2.", ";
			}
		}
	$i++;
	}
}

$qry_top=pg_query("select \"CusID\", \"P_TransferIDNO\" from \"Fp\" WHERE \"IDNO\"='$idno'");
$res_top=pg_fetch_array($qry_top);
$CusID=$res_top["CusID"];
$P_TransferIDNO=$res_top["P_TransferIDNO"];
$arr_idno[$idno]=$CusID;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(function(){
    $("#tabs").tabs();
    $("#tabs").tabs('select', '<?php echo $_SESSION["ses_idno"]; ?>');
});

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

<style type="text/css">
.ui-tabs{
    font-family:tahoma;
    font-size:12px
}
</style>


<style type="text/css">
body {
    font-family:tahoma;
    color : #333333;
    font-size:12px;
}
.title_top{
    font-family: tahoma;
    font-size:19px;
    font-weight: bold;
    margin: 0;
    padding: 0 0 3px 0;
    text-align: right;
}
.odd{
    background-color:#EDF8FE;
    font-size:11px
}
.even{
    background-color:#D5EFFD;
    font-size:11px
}
.red{
    background-color:#FFD9EC;
    font-size:11px
}
</style>

</head>

<body>

<div class="title_top">รายการรับชำระทั้งหมด</div>

<div id="tabs"> <!-- เริ่ม tabs -->
<ul>
<?php
//สร้าง list รายการ โอนสิทธิ์
foreach($arr_idno as $i => $v){
    if(empty($i)){
        continue;
    }
    echo "<li><a href=\"#tabs-$i\">$i</a></li>";
}
?>
</ul>

<?php
foreach($arr_idno as $i => $v){
    if(empty($i)){
        continue;
    }
    
    $cusid = $v;
    $idno = $i;
    
    //กำหนดสี ให้กับข้อมูลล่าสุด
    if($_SESSION["ses_idno"] == $idno){
        $bgcolor = "#FFFFFF";
    }else{
        $bgcolor = "#FFFFFF"; // FFD2D2
    }
    //จบ กำหนดสี
?>

<div id="tabs-<?php echo $idno; ?>">
<div style="background-color:<?php echo $bgcolor; ?>">
<div>
	<table width="100%">
		<tr>
			<td align="left">
				<font color="#FF0000">รายการสีเทาหมายถึง รายการจ่ายที่เป็นการผ่อนเงินกู้/ค่างวด และ *** ใน PDF หมายถึงความหมายเดียวกัน</font><br>
				<font color="#FF0000">รายการสีส้มหมายถึง รายการจ่ายที่เป็นการชำระด้วย เงินพักรอตัดฯหรือเงินค้ำประกันฯ และ * ใน PDF หมายถึงความหมายเดียวกัน</font><br>
				<font color="#FF0000">รายการสีม่วงหมายถึง มีการคืนเงินจำนวนดังกล่าวให้ลูกค้า และจำนวนเงินที่อยู่ในวงเล็บคือ จำนวนเงินที่เหลือให้คืนได้ (ก่อนภาษีมูลค่าเพิ่ม)</font>
			</td>
			<td align="right">
				<form method="post" name="frmprint" action="frm_print_otherpay.php">
					<input type="hidden" name="idno" value="<?php echo $idno; ?>">
					<input type="submit" value="พิมพ์">
				</form>
			</td>
		</tr>
	</table>
</div>
<div align="right" style="font-weight:bold; padding-top:3px; padding-bottom:3px;">ผู้กู้หลัก : <?php echo $name3; if($nameco != ""){?> | ผู้กู้ร่วม : <?php echo $nameco;}?></div>

<fieldset><legend><b>รายการรับชำระทั้งหมด</b></legend>

<table width="100%" border="0" cellspacing="1" cellpadding="5" bgcolor="#F0F0F0"  align="center">
    <tr bgcolor="#79BCFF" style="font-size:12px; font-weight:bold;"  align="center" valign="middle">
        <td>วันที่ชำระ</td>
		<td>วันที่ตั้งหนี้</td>
        <td>เลขที่ใบเสร็จ</td>
        <td>รหัสประเภท<br>ค่าใช้จ่าย</td>
        <td>คำอธิบายรายการ</td>
        <td>เลขอ้างอิง</td>
        <td>ยอดเงิน</td>
		<td>ช่องทางการจ่าย</td>
		<td>Ref ช่องทางการจ่าย</td>
		<td>หมายเหตุ</td>
    </tr>
<?php

$qry_vcus=pg_query("
			SELECT	
				\"contractID\", 
				\"byChannel\", 
				\"typePayID\", 
				\"receiptID\", 
				\"receiveDate\", 
				\"typePayRefDate\", 
				\"tpDesc\", 
				\"tpFullDesc\", 
				\"typePayRefValue\", 
				\"netAmt\",
				\"debtAmt\", 
				\"nameChannel\",
				\"amtrefund\"
			FROM 
				\"thcap_v_receipt_otherpay\" 
			WHERE  
				\"contractID\"='$idno' 
			ORDER BY 
				\"receiveDate\", 
				\"receiptID\", 
				\"typePayID\"
");
$rows = pg_num_rows($qry_vcus);
if($rows > 0){
while($resvc=pg_fetch_array($qry_vcus)){

			$contractID = $resvc["contractID"];
			$amtrefund = $resvc["amtrefund"];
        
			$bychannel = $resvc["byChannel"];
			$sqlchannel997 = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$contractID','1')");
			list($rechannel997) = pg_fetch_array($sqlchannel997);
			$sqlchannel998 = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$contractID','1')");
			list($rechannel998) = pg_fetch_array($sqlchannel998);
         
			if($bychannel == $rechannel997 || $bychannel == $rechannel998){$color99x = "#FF9933"; }else{ $color99x = ""; }
			
			$typePayID = $resvc["typePayID"];
			$sqlchannel997 = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$contractID')");
			list($rechannel997) = pg_fetch_array($sqlchannel997);
			$sqlchannel998 = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$contractID')");
			list($rechannel998) = pg_fetch_array($sqlchannel998);
			if($typePayID == $rechannel997 || $typePayID == $rechannel998){$typePayIDcolor99x = "#FF9933"; }else{ $typePayIDcolor99x = ""; }
			
			$i+=1;
			
			if($typePayID == $minPayType)
			{				
				echo "<tr style=\"font-size:11px\" bgcolor=\"#DDDDDD\">";				
			}
			elseif($amtrefund > 0.00)
			{
				echo "<tr style=\"font-size:11px\" bgcolor=\"#F5A9F2\">";
			}
			else
			{				
				if($i%2==0){
					echo "<tr class=\"odd\">";
				}else{
					echo "<tr class=\"even\">";
				}	
			}
			$receiptfindchan = $resvc["receiptID"];
			$qry_channel = pg_query("SELECT \"byChannelRef\" FROM thcap_temp_receipt_channel where \"receiptID\" = '$receiptfindchan' ");
			list($channelref) = pg_fetch_array($qry_channel);
			
			
			$Channelshow = "<a onclick=\"javascript:popU('../thcap/frm_byway_transpay_detail.php?receiptID=$receiptfindchan&bychannel=$bychannel','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=350')\" style=\"cursor:pointer;\" ><u>$channelref</u></a>";

			//หาหมายเหตุของเลขที่ใบเสร็จ
			$qryresult=pg_query("select \"receiptRemark\" from thcap_v_receipt_details where \"receiptID\"='$receiptfindchan'");
			list($receiptRemark)=pg_fetch_array($qryresult);
			
?>     
        <td align="center"><?php echo $resvc["receiveDate"]; ?></td>
        <td align="center"><?php echo $resvc["typePayRefDate"]; ?></td>
        <td align="center" style="color:#0000FF;"><span onclick="javascript:popU('../thcap/Channel_detail.php?receiptID=<?php echo $resvc["receiptID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')" style="cursor: pointer;"><u><?php echo $resvc["receiptID"]; ?></u></span></td>
        <td align="center" bgcolor="<?php echo $typePayIDcolor99x ?>"><?php echo $typePayID; ?></td>
        <td align="center" bgcolor="<?php echo $typePayIDcolor99x ?>"><?php echo $resvc["tpDesc"]." ".$resvc["tpFullDesc"]; ?></td>
		<td align="center"><?php echo $resvc["typePayRefValue"]; ?></td>
        <td align="right">
			<?php
				// กรณีที่มีการคืนเงิน ให้บอกด้วยว่าสามารถคืนได้อีกเท่าไหร่
				if($amtrefund > 0.00)
				{
					echo number_format($resvc["debtAmt"],2)." (".number_format($resvc["netAmt"]-$amtrefund,2).")"; 
				}
				// กรณีที่ไม่มีการคืนเงืน ให้แสดงจำนวนเงินที่รับชำระปกติเหมือนเดิม
				else
				{
					echo number_format($resvc["debtAmt"],2); 
				}
			?>
		</td>
		<td align="center" bgcolor="<?php echo $color99x ?>"><?php echo $resvc["nameChannel"]; ?></td>
		<td align="center"><?php echo $Channelshow; ?></td>
		<td align="center">
			<?php
			$img=redirect($_SERVER['PHP_SELF'],'nw/thcap/images/open.png');
			$realpath=redirect($_SERVER['PHP_SELF'],'nw/thcap/allpay_result.php');
			if($receiptRemark!="" and $receiptRemark!="-" and $receiptRemark!="--"){			
				echo"<img src=\"$img\" width=\"16\" height=\"16\" onclick=\"javascript : popU('$realpath?receiptID=$receiptfindchan','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=550,height=350');\" style=\"cursor:pointer;\" title=\"หมายเหตุ\">";
			}else{
				echo "<input type=\"button\" value=\"เพิ่มหมายเหตุ\" onclick=\"javascript : popU('$realpath?receiptID=$receiptfindchan&method=add','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=550,height=350');\" style=\"cursor:pointer;\" title=\"เพิ่มหมายเหตุ\" style=\"cursor:pointer\">";
			}
			?>
		</td>
    </tr>
        
<?php
    }
}else{
?>
    <tr>
        <td align="center" colspan="18">ไม่พบข้อมูล</td>
    </tr>
<?php
}
?>
</table>

</fieldset>

</div>
</div>

<?php
}
?>

</body>
</html>