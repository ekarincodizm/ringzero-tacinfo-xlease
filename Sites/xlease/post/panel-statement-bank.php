<?php
include("../config/config.php");
$datepicker = $_GET['datepicker'];

if( empty($datepicker) ){
    echo "กรุณาระบุวันที่ !";
    exit;
}

$company = $_SESSION['session_company_code'];
?>

<style type="text/css">
.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
.fixcontent{
    font-size:12px
}
</style>

<div align="left" class="fixcontent">

<!-- ============================================= SCB ============================================= -->
<div style="margin:0px; padding-top:0px; font-size: 13px; font-weight:bold">SCB วันที่ <?php echo "$datepicker"; ?></div>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <td>วันที่</td>
    <td>เลขที่</td>
    <td>IDNO</td>
    <td>ชื่อ</td>
    <td>ทะเบียน</td>
    <td>ค่าอะไร</td>
    <td>ยอดเงิน</td>
    <td>ธนาคาร</td>
    <td>สถานะ</td>
	<td>ผู้ออกใบเสร็จ</td>
</tr>
<?php
$i = 0;
$nub = 0;
$sum_sub = 0;
$sum_all_sub = 0;
$num_row = 0;
$query=pg_query("
SELECT \"O_DATE\",\"O_RECEIPT\",\"IDNO\",\"full_name\",\"assetname\",\"regis\",\"TName\",\"PayType\",\"O_PRNDATE\",\"O_BANK\",\"O_MONEY\",\"O_memo\" from \"VFOtherpayEachDay\"
WHERE \"O_DATE\"='$datepicker' 
AND ( \"PayType\" = 'SCB' )
UNION ALL
SELECT \"R_Date\",\"R_Receipt\",\"IDNO\",\"full_name\",\"assetname\",\"regis\",\"typepay_name\",\"PayType\",\"R_Prndate\",\"R_Bank\",\"money\",\"R_memo\" from \"VFrEachDay\"
WHERE \"R_Date\"='$datepicker' 
AND ( \"PayType\" = 'SCB' ) 
UNION ALL
SELECT * from \"VTRAccNotKnow\"
WHERE \"tr_date\"='$datepicker' 
AND ( \"bank_no\" = 'SCB' ) 
ORDER BY \"O_memo\" DESC, \"IDNO\" ASC
");
$num_row1 = pg_num_rows($query);
while($res=pg_fetch_array($query)){
    $nub += 1;
    $O_DATE = $res['O_DATE'];
    $O_RECEIPT = $res['O_RECEIPT'];
    $IDNO = $res['IDNO'];
    $full_name = $res['full_name'];
    $regis = $res['regis'];
    $TName = $res['TName'];
    $O_MONEY = $res['O_MONEY'];
    $PayType = $res['PayType'];
    $O_BANK = $res['O_BANK'];
    $O_memo = $res['O_memo'];
    
    if($O_memo != "TR-ACC"){
        $O_memo = "Bill Payment";
    }
    
    if($O_memo != $old_memo AND $nub != 1){
        echo "<tr style=\"font-weight:bold\"><td colspan=9 align=right><b>รวมย่อย $old_memo</b></td><td align=right>". number_format($sum_sub,2) ."</td></tr>";
        $sum_sub = 0;
    }
    $old_type = $PayType;
    $old_memo = $O_memo;
    
    $sum_sub += $O_MONEY;
    $sum_all_sub += $O_MONEY;
    $sum_all += $O_MONEY;
	
	//หาผู้ออกใบเสร็จ
	$sqluser = pg_query("SELECT fullname
						FROM \"FCash\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailTranpay\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACTran\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'");	
			$reuseracc= pg_fetch_array($sqluser);
			$fullnameuseracc = $reuseracc['fullname'];
		if($fullnameuseracc == ""){
			$fullnameuseracc = 'ไม่พบผู้ออกใบเสร็จ';
		}
	//จบการค้นหาผู้ออกใบเสร็จ

    $i+=1;
    if($i%2==0){
        echo "<tr class=\"even\" align=\"left\">";
    }else{
        echo "<tr class=\"odd\" align=\"left\">";
    }
?>
    <td align="center"><?php echo $O_DATE; ?></td>
    <td align="center"><?php echo $O_RECEIPT; ?></td>
    <td align="center"><?php echo $IDNO; ?></td>
    <td><?php echo $full_name; ?></td>
    <td><?php echo $regis; ?></td>
    <td><?php echo $TName; ?></td>
    <td align="right"><?php echo number_format($O_MONEY,2); ?></td>
    <td align="center"><?php echo $PayType; ?></td>
    <td align="center"><?php echo $O_BANK; ?></td>
	<td align="left"><?php echo $fullnameuseracc; ?></td>
</tr>

<?php
}
if($sum_sub > 0){
    echo "<tr style=\"font-weight:bold\"><td colspan=9 align=right><b>รวมย่อย $old_memo</b></td><td align=right>". number_format($sum_sub,2) ."</td></tr>";
}
// END SCB
?>


<?php

if($company == "AVL"){

//OC - CCA
$i = 0;
$nub = 0;
$sum_sub = 0;
$num_row = 0;
$query=pg_query("
SELECT \"O_DATE\",\"O_RECEIPT\",\"IDNO\",\"full_name\",\"assetname\",\"regis\",\"TName\",\"PayType\",\"O_PRNDATE\",\"O_BANK\",\"O_MONEY\",\"O_memo\" from \"VFOtherpayEachDay\"
WHERE \"O_DATE\"='$datepicker' 
AND (\"PayType\" = 'OC' AND \"O_BANK\"='CCA')
UNION ALL
SELECT \"R_Date\",\"R_Receipt\",\"IDNO\",\"full_name\",\"assetname\",\"regis\",\"typepay_name\",\"PayType\",\"R_Prndate\",\"R_Bank\",\"money\",\"R_memo\" from \"VFrEachDay\"
WHERE \"R_Date\"='$datepicker' 
AND (\"PayType\" = 'OC' AND \"R_Bank\"='CCA')
ORDER BY \"PayType\",\"O_memo\" DESC, \"IDNO\" ASC
");
$num_row2 = pg_num_rows($query);
while($res=pg_fetch_array($query)){
    $nub += 1;
    $O_DATE = $res['O_DATE'];
    $O_RECEIPT = $res['O_RECEIPT'];
    $IDNO = $res['IDNO'];
    $full_name = $res['full_name'];
    $regis = $res['regis'];
    $TName = $res['TName'];
    $O_MONEY = $res['O_MONEY'];
    $PayType = $res['PayType'];
    $O_BANK = $res['O_BANK'];
    $O_memo = $res['O_memo'];
    
    $sum_sub += $O_MONEY;
    $sum_all_sub  += $O_MONEY;
    $sum_all += $O_MONEY;

	//หาผู้ออกใบเสร็จ
	
		
		$sqluser = pg_query("SELECT fullname
						FROM \"FCash\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailTranpay\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACTran\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'");
			$reuseracc= pg_fetch_array($sqluser);
			
			$fullnameuseracc = $reuseracc['fullname'];
		if($fullnameuseracc == ""){
			$fullnameuseracc = 'ไม่พบผู้ออกใบเสร็จ';
		}	
	//จบการค้นหาผู้ออกใบเสร็จ	
		
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"even\" align=\"left\">";
    }else{
        echo "<tr class=\"odd\" align=\"left\">";
    }
?>
    <td align="center"><?php echo $O_DATE; ?></td>
    <td align="center"><?php echo $O_RECEIPT; ?></td>
    <td align="center"><?php echo $IDNO; ?></td>
    <td><?php echo $full_name; ?></td>
    <td><?php echo $regis; ?></td>
    <td><?php echo $TName; ?></td>
    <td align="right"><?php echo number_format($O_MONEY,2); ?></td>
    <td align="center"><?php echo $PayType; ?></td>
    <td align="center"><?php echo $O_BANK; ?></td>
	<td align="left"><?php echo $fullnameuseracc; ?></td>
</tr>

<?php
}

    if($num_row1 == 0 && $num_row2 == 0){
        echo "<tr><td align=center colspan=20>- ไม่พบข้อมูล -</td></tr>";
    }
    if($num_row1 != 0 || $num_row2 != 0){
        echo "<tr style=\"font-weight:bold\"><td colspan=9 align=right><b>รวมย่อย CCA</b></td><td align=right>". number_format($sum_sub,2) ."</td></tr>";
        echo "<tr style=\"font-weight:bold; background-color:#FFC0C0;\"><td colspan=9 align=right><b>ผลรวม SCB</b></td><td align=right>". number_format($sum_all_sub,2) ."</td></tr>";
    }

}else{//end check AVL
    
    if($num_row1 == 0){
        echo "<tr><td align=center colspan=20>- ไม่พบข้อมูล -</td></tr>";
    }
    if($num_row1 != 0){
        echo "<tr style=\"font-weight:bold; background-color:#FFC0C0;\"><td colspan=9 align=right><b>ผลรวม SCB</b></td><td align=right>". number_format($sum_all_sub,2) ."</td></tr>";
    }
}//end else check AVL


// END OC - CCA
?>
</table>

<!-- ============================================= TMB ============================================= -->
<div style="margin:0px; padding-top:20px; font-size: 13px; font-weight:bold">TMB (เงินโอน) วันที่ <?php echo "$datepicker"; ?></div>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <td>วันที่</td>
    <td>เลขที่</td>
    <td>IDNO</td>
    <td>ชื่อ</td>
    <td>ทะเบียน</td>
    <td>ค่าอะไร</td>
    <td>ยอดเงิน</td>
    <td>ธนาคาร</td>
    <td>สถานะ</td>
	<td>ผู้ออกใบเสร็จ</td>
</tr>
<?php
$i = 0;
$nub = 0;
$sum_sub = 0;
$sum_all_sub = 0;
$num_row = 0;
$query=pg_query("
SELECT \"O_DATE\",\"O_RECEIPT\",\"IDNO\",\"full_name\",\"assetname\",\"regis\",\"TName\",\"PayType\",\"O_PRNDATE\",\"O_BANK\",\"O_MONEY\",\"O_memo\" from \"VFOtherpayEachDay\"
WHERE \"O_DATE\"='$datepicker' 
AND ( \"PayType\" = 'TMB' )
UNION ALL
SELECT \"R_Date\",\"R_Receipt\",\"IDNO\",\"full_name\",\"assetname\",\"regis\",\"typepay_name\",\"PayType\",\"R_Prndate\",\"R_Bank\",\"money\",\"R_memo\" from \"VFrEachDay\"
WHERE \"R_Date\"='$datepicker' 
AND ( \"PayType\" = 'TMB' ) 
UNION ALL
SELECT * from \"VTRAccNotKnow\"
WHERE \"tr_date\"='$datepicker' 
AND ( \"bank_no\" = 'TMB' ) 
ORDER BY \"O_memo\" DESC, \"IDNO\" ASC
");
$num_row = pg_num_rows($query);
while($res=pg_fetch_array($query)){
    $nub += 1;
    $O_DATE = $res['O_DATE'];
    $O_RECEIPT = $res['O_RECEIPT'];
    $IDNO = $res['IDNO'];
    $full_name = $res['full_name'];
    $regis = $res['regis'];
    $TName = $res['TName'];
    $O_MONEY = $res['O_MONEY'];
    $PayType = $res['PayType'];
    $O_BANK = $res['O_BANK'];
    $O_memo = $res['O_memo'];
    
    if($O_memo != "TR-ACC"){
        $O_memo = "Bill Payment";
    }
    
    if($O_memo != $old_memo AND $nub != 1){
        echo "<tr style=\"font-weight:bold\"><td colspan=9 align=right><b>รวมย่อย $old_memo</b></td><td align=right>". number_format($sum_sub,2) ."</td></tr>";
        $sum_sub = 0;
    }
    $old_type = $PayType;
    $old_memo = $O_memo;
    
    $sum_sub += $O_MONEY;
    $sum_all_sub  += $O_MONEY;
    $sum_all += $O_MONEY;

	//หาผู้ออกใบเสร็จ
	$sqluser = pg_query("SELECT fullname
						FROM \"FCash\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailTranpay\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACTran\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'");
			$reuseracc= pg_fetch_array($sqluser);
			
			$fullnameuseracc = $reuseracc['fullname'];
		if($fullnameuseracc == ""){
			$fullnameuseracc = 'ไม่พบผู้ออกใบเสร็จ';
		}	
	//จบการค้นหาผู้ออกใบเสร็จ	
		
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"even\" align=\"left\">";
    }else{
        echo "<tr class=\"odd\" align=\"left\">";
    }
?>
    <td align="center"><?php echo $O_DATE; ?></td>
    <td align="center"><?php echo $O_RECEIPT; ?></td>
    <td align="center"><?php echo $IDNO; ?></td>
    <td><?php echo $full_name; ?></td>
    <td><?php echo $regis; ?></td>
    <td><?php echo $TName; ?></td>
    <td align="right"><?php echo number_format($O_MONEY,2); ?></td>
    <td align="center"><?php echo $PayType; ?></td>
    <td align="center"><?php echo $O_BANK; ?></td>
	<td align="left"><?php echo $fullnameuseracc; ?></td>
</tr>

<?php
}

if($num_row == 0){
    echo "<tr><td align=center colspan=20>- ไม่พบข้อมูล -</td></tr>";
}

if($num_row != 0){
    echo "<tr style=\"font-weight:bold\"><td colspan=9 align=right><b>รวมย่อย $old_memo</b></td><td align=right>". number_format($sum_sub,2) ."</td></tr>";

    echo "<tr style=\"font-weight:bold; background-color:#FFC0C0;\"><td colspan=9 align=right><b>ผลรวม TMB</b></td><td align=right>". number_format($sum_all_sub,2) ."</td></tr>";
}
// END TMB
?>
</table>

<!-- ============================================= KTB ============================================= -->
<div style="margin:0px; padding-top:20px; font-size: 13px; font-weight:bold">KTB วันที่ <?php echo "$datepicker"; ?></div>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <td>วันที่</td>
    <td>เลขที่</td>
    <td>IDNO</td>
    <td>ชื่อ</td>
    <td>ทะเบียน</td>
    <td>ค่าอะไร</td>
    <td>ยอดเงิน</td>
    <td>ธนาคาร</td>
    <td>สถานะ</td>
	<td>ผู้ออกใบเสร็จ</td>
</tr>
<?php
$i = 0;
$nub = 0;
$sum_sub = 0;
$sum_all_sub = 0;
$num_row = 0;
$query=pg_query("
SELECT \"O_DATE\",\"O_RECEIPT\",\"IDNO\",\"full_name\",\"assetname\",\"regis\",\"TName\",\"PayType\",\"O_PRNDATE\",\"O_BANK\",\"O_MONEY\",\"O_memo\" from \"VFOtherpayEachDay\"
WHERE \"O_DATE\"='$datepicker' 
AND ( \"PayType\" = 'KTB' )
UNION ALL
SELECT \"R_Date\",\"R_Receipt\",\"IDNO\",\"full_name\",\"assetname\",\"regis\",\"typepay_name\",\"PayType\",\"R_Prndate\",\"R_Bank\",\"money\",\"R_memo\" from \"VFrEachDay\"
WHERE \"R_Date\"='$datepicker' 
AND ( \"PayType\" = 'KTB' ) 
UNION ALL
SELECT * from \"VTRAccNotKnow\"
WHERE \"tr_date\"='$datepicker' 
AND ( \"bank_no\" = 'KTB' ) 
ORDER BY \"O_memo\" DESC, \"IDNO\" ASC
");
$num_row = pg_num_rows($query);
while($res=pg_fetch_array($query)){
    $nub += 1;
    $full_name = "";
    $O_DATE = $res['O_DATE'];
    $O_RECEIPT = $res['O_RECEIPT'];
    $IDNO = $res['IDNO'];
    $full_name = $res['full_name'];
    $regis = $res['regis'];
    $TName = $res['TName'];
    $O_MONEY = $res['O_MONEY'];
    $PayType = $res['PayType'];
    $O_BANK = $res['O_BANK'];
    $O_memo = $res['O_memo'];

    if($O_memo != "TR-ACC"){
        $O_memo = "Bill Payment";
    }
    
    if($O_memo != $old_memo AND $nub != 1){
        echo "<tr style=\"font-weight:bold\"><td colspan=9 align=right><b>รวมย่อย $old_memo</b></td><td align=right>". number_format($sum_sub,2) ."</td></tr>";
        $sum_sub = 0;
    }
    $old_type = $PayType;
    $old_memo = $O_memo;
    
    $sum_sub += $O_MONEY;
    $sum_all_sub  += $O_MONEY;
    $sum_all += $O_MONEY;

	
	//หาผู้ออกใบเสร็จ
	$sqluser = pg_query("SELECT fullname
						FROM \"FCash\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailTranpay\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACTran\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'");
			$reuseracc= pg_fetch_array($sqluser);
			
			$fullnameuseracc = $reuseracc['fullname'];
		if($fullnameuseracc == ""){
			$fullnameuseracc = 'ไม่พบผู้ออกใบเสร็จ';
		}	
	//จบการค้นหาผู้ออกใบเสร็จ
	
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"even\" align=\"left\">";
    }else{
        echo "<tr class=\"odd\" align=\"left\">";
    }
?>
    <td align="center"><?php echo $O_DATE; ?></td>
    <td align="center"><?php echo $O_RECEIPT; ?></td>
    <td align="center"><?php echo $IDNO; ?></td>
    <td><?php echo $full_name; ?></td>
    <td><?php echo $regis; ?></td>
    <td><?php echo $TName; ?></td>
    <td align="right"><?php echo number_format($O_MONEY,2); ?></td>
    <td align="center"><?php echo $PayType; ?></td>
    <td align="center"><?php echo $O_BANK; ?></td>
	 <td align="left"><?php echo $fullnameuseracc; ?></td>
</tr>

<?php
}
/*
if($num_row == 0){
    echo "<tr><td align=center colspan=20>- ไม่พบข้อมูล -</td></tr>";
}
*/
if($sum_sub > 0){
    echo "<tr style=\"font-weight:bold\"><td colspan=9 align=right><b>รวมย่อย $old_memo</b></td><td align=right>". number_format($sum_sub,2) ."</td></tr>";
}


//ACC
if($company == "THA"){

//OC - CCA
$i = 0;
$nub = 0;
$sum_sub = 0;
$num_row = 0;
$query=pg_query("
SELECT \"O_DATE\",\"O_RECEIPT\",\"IDNO\",\"full_name\",\"assetname\",\"regis\",\"TName\",\"PayType\",\"O_PRNDATE\",\"O_BANK\",\"O_MONEY\",\"O_memo\" from \"VFOtherpayEachDay\"
WHERE \"O_DATE\"='$datepicker' 
AND (\"PayType\" = 'OC' AND \"O_BANK\"='CCA')
UNION ALL
SELECT \"R_Date\",\"R_Receipt\",\"IDNO\",\"full_name\",\"assetname\",\"regis\",\"typepay_name\",\"PayType\",\"R_Prndate\",\"R_Bank\",\"money\",\"R_memo\" from \"VFrEachDay\"
WHERE \"R_Date\"='$datepicker' 
AND (\"PayType\" = 'OC' AND \"R_Bank\"='CCA')
ORDER BY \"PayType\",\"O_memo\" DESC, \"IDNO\" ASC
");
$num_row2 = pg_num_rows($query);
while($res=pg_fetch_array($query)){
    $nub += 1;
    $O_DATE = $res['O_DATE'];
    $O_RECEIPT = $res['O_RECEIPT'];
    $IDNO = $res['IDNO'];
    $full_name = $res['full_name'];
    $regis = $res['regis'];
    $TName = $res['TName'];
    $O_MONEY = $res['O_MONEY'];
    $PayType = $res['PayType'];
    $O_BANK = $res['O_BANK'];
    $O_memo = $res['O_memo'];
    
    $sum_sub += $O_MONEY;
    $sum_all_sub  += $O_MONEY;
    $sum_all += $O_MONEY;

	//หาผู้ออกใบเสร็จ
	$sqluser = pg_query("SELECT fullname
						FROM \"FCash\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailTranpay\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACTran\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'");
			$reuseracc= pg_fetch_array($sqluser);
			
			$fullnameuseracc = $reuseracc['fullname'];
		if($fullnameuseracc == ""){
			$fullnameuseracc = 'ไม่พบผู้ออกใบเสร็จ';
		}	
	//จบการค้นหาผู้ออกใบเสร็จ
	
	
	
    $i+=1;
    if($i%2==0){
        echo "<tr class=\"even\" align=\"left\">";
    }else{
        echo "<tr class=\"odd\" align=\"left\">";
    }
?>
    <td align="center"><?php echo $O_DATE; ?></td>
    <td align="center"><?php echo $O_RECEIPT; ?></td>
    <td align="center"><?php echo $IDNO; ?></td>
    <td><?php echo $full_name; ?></td>
    <td><?php echo $regis; ?></td>
    <td><?php echo $TName; ?></td>
    <td align="right"><?php echo number_format($O_MONEY,2); ?></td>
    <td align="center"><?php echo $PayType; ?></td>
    <td align="center"><?php echo $O_BANK; ?></td>
	<td align="left"><?php echo $fullnameuseracc; ?></td>
</tr>

<?php
}

    if($num_row == 0 && $num_row2 == 0){
        echo "<tr><td align=center colspan=20>- ไม่พบข้อมูล -</td></tr>";
    }
    if($num_row != 0 || $num_row2 != 0){
        echo "<tr style=\"font-weight:bold\"><td colspan=9 align=right><b>รวมย่อย CCA</b></td><td align=right>". number_format($sum_sub,2) ."</td></tr>";
        echo "<tr style=\"font-weight:bold; background-color:#FFC0C0;\"><td colspan=9 align=right><b>ผลรวม KTB</b></td><td align=right>". number_format($sum_all_sub,2) ."</td></tr>";
    }

}else{//end check THA
    
    if($num_row == 0){
        echo "<tr><td align=center colspan=20>- ไม่พบข้อมูล -</td></tr>";
    }
    if($num_row != 0){
        echo "<tr style=\"font-weight:bold; background-color:#FFC0C0;\"><td colspan=9 align=right><b>ผลรวม KTB</b></td><td align=right>". number_format($sum_all_sub,2) ."</td></tr>";
    }
}//end else check THA

// END KTB
?>
</table>

<!-- ============================================= OC-CU ============================================= -->
<div style="margin:0px; padding-top:20px; font-size: 13px; font-weight:bold">TMB (เช็คธนาคาร) วันที่ <?php echo "$datepicker"; ?></div>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <td>วันที่</td>
    <td>เลขที่</td>
    <td>IDNO</td>
    <td>ชื่อ</td>
    <td>ทะเบียน</td>
    <td>ค่าอะไร</td>
    <td>ยอดเงิน</td>
    <td>ธนาคาร</td>
    <td>สถานะ</td>
	<td>ผู้ออกใบเสร็จ</td>
	
</tr>
<?php
$i = 0;
$nub = 0;
$sum_sub = 0;
$sum_all_sub = 0;
$num_row = 0;
$query=pg_query("
SELECT \"O_DATE\",\"O_RECEIPT\",\"IDNO\",\"full_name\",\"assetname\",\"regis\",\"TName\",\"PayType\",\"O_PRNDATE\",\"O_BANK\",\"O_MONEY\",\"O_memo\" from \"VFOtherpayEachDay\"
WHERE \"O_DATE\"='$datepicker' 
AND ( \"PayType\" = 'OC' AND \"O_BANK\" = 'CU' )
UNION ALL
SELECT \"R_Date\",\"R_Receipt\",\"IDNO\",\"full_name\",\"assetname\",\"regis\",\"typepay_name\",\"PayType\",\"R_Prndate\",\"R_Bank\",\"money\",\"R_memo\" from \"VFrEachDay\"
WHERE \"R_Date\"='$datepicker' 
AND ( \"PayType\" = 'OC' AND \"R_Bank\" = 'CU' )
ORDER BY \"O_memo\" DESC, \"IDNO\" ASC
");
$num_row = pg_num_rows($query);
while($res=pg_fetch_array($query)){
    $nub += 1;
    $O_DATE = $res['O_DATE'];
    $O_RECEIPT = $res['O_RECEIPT'];
    $IDNO = $res['IDNO'];
    $full_name = $res['full_name'];
    $regis = $res['regis'];
    $TName = $res['TName'];
    $O_MONEY = $res['O_MONEY'];
    $PayType = $res['PayType'];
    $O_BANK = $res['O_BANK'];
    $O_memo = $res['O_memo'];

    $old_type = $PayType;
    $old_memo = $O_memo;
    
    $sum_sub += $O_MONEY;
    $sum_all_sub  += $O_MONEY;
    $sum_all += $O_MONEY;
	
	//หาผู้ออกใบเสร็จ
	$sqluser = pg_query("SELECT fullname
						FROM \"FCash\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT fullname
						FROM \"DetailTranpay\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"ReceiptNo\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACCheque\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'

						union 

						SELECT c.fullname
						FROM \"FTACTran\" a
						left join \"PostLog\" b on a.\"PostID\"=b.\"PostID\"
						left join \"Vfuser\" c on b.\"UserIDAccept\"=c.\"id_user\"
						where \"refreceipt\"='$O_RECEIPT'");
			$reuseracc= pg_fetch_array($sqluser);
			
			$fullnameuseracc = $reuseracc['fullname'];
		if($fullnameuseracc == ""){
			$fullnameuseracc = 'ไม่พบผู้ออกใบเสร็จ';
		}	
	//จบการค้นหาผู้ออกใบเสร็จ

    $i+=1;
    if($i%2==0){
        echo "<tr class=\"even\" align=\"left\">";
    }else{
        echo "<tr class=\"odd\" align=\"left\">";
    }
?>
    <td align="center"><?php echo $O_DATE; ?></td>
    <td align="center"><?php echo $O_RECEIPT; ?></td>
    <td align="center"><?php echo $IDNO; ?></td>
    <td><?php echo $full_name; ?></td>
    <td><?php echo $regis; ?></td>
    <td><?php echo $TName; ?></td>
    <td align="right"><?php echo number_format($O_MONEY,2); ?></td>
    <td align="center"><?php echo $PayType; ?></td>
    <td align="center"><?php echo $O_BANK; ?></td>
	<td align="left"><?php echo $fullnameuseracc; ?></td>
</tr>

<?php
}

if($num_row == 0){
    echo "<tr><td align=center colspan=20>- ไม่พบข้อมูล -</td></tr>";
}

if($num_row != 0){
    echo "<tr style=\"font-weight:bold\"><td colspan=9 align=right><b>รวมย่อย OC CU</b></td><td align=right>". number_format($sum_sub,2) ."</td></tr>";

    echo "<tr style=\"font-weight:bold; background-color:#FFC0C0;\"><td colspan=9 align=right><b>ผลรวม TMB (ตามบัญชี)</b></td><td align=right>". number_format($sum_all_sub,2) ."</td></tr>";
}
// END OC-CU

if($sum_all > 0){
    echo "<tr style=\"font-weight:bold; background-color:#C0FFC0;\">
    <td><img src=\"icoPrint.png\" border=\"0\" width=\"17\" height=\"14\" alt=\"พิมพ์\">&nbsp;<a href=\"statement_bank_pdf.php?date=$datepicker\" target=\"_blank\"><u>พิมพ์รายงาน</u></a></td>
    <td colspan=8 align=right><b>รวมทั้งสิ้น</b></td><td align=right>". number_format($sum_all,2) ."</td></tr>";
}
?>
</table>

</div>