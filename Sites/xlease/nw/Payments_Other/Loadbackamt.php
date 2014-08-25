<?php
include("../../config/config.php"); 
?>
<?php
$idno  = $_GET['idno'];//เลขที่สัญญา
$backAmt ="";
$currentDate= $_GET['datepicker'];//วันที่ วันที่จ่าย
$cutidno = substr($idno ,0,2);
$qrybackamt = pg_query("select \"thcap_get_all_backamt\"('$idno','$currentDate')");
list($backAmt) = pg_fetch_array($qrybackamt);
$numrows_backamt = pg_num_rows($qrybackamt);

$qry_name=pg_query("select * from public.\"thcap_v_otherpay_debt_realother_current\" where \"contractID\"='$idno' and \"debtStatus\"='1' order by \"typePayRefDate\" ");
$numrows_other = pg_num_rows($qry_name);


?>
<!--ตรวจสอบว่า ยอดค่างวดค้างชำระปัจจุบัน  มีหรือไม่-->
<?php if(($numrows_backamt<=0)or ($backAmt==""))
		{  echo ""; }
	  else {	
	?> 

	<table>
		<tr> 
			<td  style=" font-family:Arial, Helvetica, sans-serif; font-size:16px;" ><b>ยอดค่างวดค้างชำระ::</b></td>
			<td><b><FONT COLOR=red>
			<?php echo number_format($backAmt,2);?> 
			</FONT>
			</b>
			</td>
			<td style=" font-family:Arial, Helvetica, sans-serif; font-size:16px;" ><b> บาท</b></td>
		</tr>
	</table>
<?php }?>
<!--ตรวจสอบค่าใช้จ่ายอื่น ๆ  -->
	
<br><br>
<div align="left">
	<table >
		<tr >
			<td style="color:#3366CC; font-family:Arial, Helvetica, sans-serif; font-size:18px;">
			<b>ค่าใช้จ่ายอื่น ๆ ::</b>
			</td>
		</tr>
	</table>
</div>

<table align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#FFFFFF" id="tb1">
			<tr bgcolor="#4399c3" style="color:#FFFFFF; font-family:Arial, Helvetica, sans-serif; font-size:14px;" height="25">
				<th width="150">รหัสประเภทค่าใช้จ่าย</th>
				<th width="200">รายการ</th>
				<th width="150">ค่าอ้างอิงของค่าใช้จ่าย</th>
				<th width="130">วันที่ตั้งหนี้</th>
				<th width="120">จำนวนหนี้</th>
				<th width="170">ผู้ตั้งหนี้</th>
				<th width="180">วันเวลาตั้งหนี้</th>
				</tr>
        <?php  
		if($numrows_other == 0)
        {
            echo "<tr><td colspan=\"8\" style=\"text-align:center;\">ไม่พบรายการหนี้อื่นๆค้างชำระ</td></tr>";
            echo "<input type=\"hidden\" id=\"chk0\" name=\"chk[]\">";
        }
        else
        {		
			$num = 0;
            while($res_name=pg_fetch_array($qry_name))
            {
                $typePayID=trim($res_name["typePayID"]); // รหัสประเภทค่าใช้จ่าย
                $typePayRefValue=trim($res_name["typePayRefValue"]);
                $typePayRefDate=trim($res_name["typePayRefDate"]); // วันที่ตั้งหนี้
                $typePayLeft=trim($res_name["typePayLeft"]);
                $doerID=trim($res_name["doerID"]); 
                $doerStamp=trim($res_name["doerStamp"]);
                $debtID=trim($res_name["debtID"]); // รหัสหนี้
                $contractID=trim($res_name["contractID"]);
                
				$sumdoerStamp+=$typePayLeft;
                //-------------------------------
                if($typePayID == "1003")
                {
                    $search = strpos($typePayRefValue,"-");
                    if($search)
                    {
                        $subtypePayRefValue = explode("-", $typePayRefValue);
                        $typePayRefValue = $subtypePayRefValue[0];
                    }
                }
                //--------------------------------
                
                $doerStamp = substr($doerStamp,0,19); // ทำให้อยู่ในรูปแบบวันเวลาที่สวยงาม
                
                if($doerID == "000")
                {
                    $doerName = "อัตโนมัติโดยระบบ";
                }
                else
                {
                    $doerusername=pg_query("select * from public.\"Vfuser\" where \"id_user\"='$doerID'");
                    while($res_username=pg_fetch_array($doerusername))
                    {
                        $doerName=$res_username["fullname"];
                    }
                }	
                
                $qry_type=pg_query("select * from account.\"thcap_typePay\" where \"tpID\"='$typePayID' ");
                while($res_type=pg_fetch_array($qry_type))
                {
                    $tpDesc=trim($res_type["tpDesc"]); // รายละเอียดประเภทค่าใช้จ่าย
                }
                
                $due = ""; // กำหนดเป็นค่าว่างเพื่อป้องกันการเก็บค่าเก่ามาใช้
                
                if($typePayID == "1003")
                {
                    $qry_due=pg_query("select * from account.\"thcap_mg_payTerm\" where \"contractID\"::text='$idno' and \"ptNum\"::text='$typePayRefValue' ");
                    while($res_due=pg_fetch_array($qry_due))
                    {
                        $ptDate=trim($res_due["ptDate"]); // วันดิว
                        $due = "($ptDate)";
                    }
                }
                else
                {
                    $due = "";
                }
				
				// ตรวจสอบว่าหนี้นั้นๆถูกขอยกเลิกอยู่หรือไม่
				$qry_chk_exceptDebt = pg_query("select * from \"thcap_temp_except_debt\" where \"debtID\" = '$debtID' and \"Approve\" is null ");
				$row_chk_exceptDebt = pg_num_rows($qry_chk_exceptDebt);
				if($row_chk_exceptDebt == 0){$haveExceptDebt = "no";}else{$haveExceptDebt = "yes";}
				// จบการตรวจสอบว่าหนี้นั้นๆถูกขอยกเลิกอยู่หรือไม่
                
                // หาภาษีหัก ณ ที่จ่าย
                $whtAmtfunc = pg_query("SELECT \"thcap_checkdebtwht\"('$debtID','$currentDate')");					
                $whtAmt1 = pg_fetch_array($whtAmtfunc);
                $whtAmt = $whtAmt1['thcap_checkdebtwht'];
                
				if($statusLock == 1)
				{
					if(date($dateContact) < date($typePayRefDate))
					{ // ถ้าวันที่โอนเงินมากกว่าวันที่ตั้งหนี้
						echo "<tr bgcolor=\"#DBF2FD\"style=\"color:#444; background:#FFBBBB; font-family:Arial, Helvetica, sans-serif; font-size:14px;\">";
					}
					else
					{
						echo "<tr bgcolor=\"#DBF2FD\"style=\"color:#444; font-family:Arial, Helvetica, sans-serif; font-size:14px;\">";
					}
				}
				else
				{
					echo "<tr bgcolor=\"#DBF2FD\"style=\"color:#444; font-family:Arial, Helvetica, sans-serif; font-size:14px;\">";
				}
				//แสดงค่า ลง ตาราง 			
				echo "<td align=center>$typePayID</td>";
                echo "<td align=center>$tpDesc</td>";
                echo "<td align=center>$typePayRefValue $due</td>";
                echo "<td align=center>$typePayRefDate</td>";
                echo "<td align=right>".number_format($typePayLeft,2)."</td>";
                echo "<td align=center>$doerName</td>";
                echo "<td align=center>$doerStamp</td>";              
				
            }//จบ while
		}
        ?>
</table>

<!--ตรวจสอบว่าเป็น ค่าที่ปรึกษา หรือไม่-->
<?php if($cutidno!="JV"){ echo ""; }
	  else {	
	?>
	<br>
	<div id="showDataHP">
	<div align="left">
	<table >
		<tr >
			<td style="color:red; font-family:Arial, Helvetica, sans-serif; font-size:18px;">
			<b>ค่าที่ปรึกษา ::</b>
			</td>
		</tr>
	</table>
	</div>
	<div>
			<table  border="0" cellspacing="1" cellpadding="1" bgcolor="#FFFFFF" id="tb1">
				<tr bgcolor="#4399c3" style="color:#FFFFFF; font-family:Arial, Helvetica, sans-serif; font-size:14px;" height="25">
					<th width="80">งวดที่</th>
					<th width="150">วันที่ครบกำหนดชำระ</th>
					<th width="150">จำนวนเงินก่อน VAT</th>
					<th width="150">จำนวน VAT</th>
					<th width="180">จำนวนเงินรวม</th>
				</tr>
				<?php
					$qry_HP = pg_query("select * from public.\"vthcap_otherpay_debt_current\" where \"contractID\"='$idno' and \"debtStatus\"='1' and \"debtIsOther\" = '0' and \"debtDueDate\" <= '$currentDate' and \"debtDueDate\" < '2014-02-01' order by \"typePayRefValue\"::integer ");
					$numrowsHP = pg_num_rows($qry_HP);
					
					if($numrowsHP == 0)
					{
						$qry_HP = pg_query("select * from public.\"vthcap_otherpay_debt_current\" where \"contractID\"='$idno' and \"debtStatus\"='1' and \"debtIsOther\" = '0' and \"debtDueDate\" < '2014-02-01' order by \"typePayRefValue\"::integer limit 1 ");
						$numrowsHP = pg_num_rows($qry_HP);
						
					}
					if($numrowsHP==0){
					  echo "<tr><td colspan=\"8\" style=\"text-align:center;\">ไม่พบรายการค่าที่ปรึกษา</td></tr>";
					}
					else{
					
					$arrayHP = "{"; // งวดที่จะชำระทั้งหมด
					$sumMoney = 0; // จำนวนค่างวดที่จะชำระรวม
					$numhp = 0;
					while($dueHP = pg_fetch_array($qry_HP))
					{
						$numhp++;
						$debtID = $dueHP["debtID"];
						$typePayRefValue = $dueHP["typePayRefValue"]; // งวดที่
						$debtDueDate = $dueHP["debtDueDate"]; // วันที่ครบกำหนดชำระ
						$debtNet = $dueHP["debtNet"]; // จำนวนเงินก่อน VAT
						$debtVat = $dueHP["debtVat"]; // จำนวน VAT
						$typePayLeft = $dueHP["typePayLeft"]; // จำนวนเงินรวม
						
						$arrayHP = $arrayHP."$typePayRefValue"; // งวดที่จะชำระทั้งหมด
						$sumMoney += $typePayLeft; // จำนวนค่างวดที่จะชำระรวม
						
						echo "<tr bgcolor=\"#DBF2FD\"style=\"color:#444; font-family:Arial, Helvetica, sans-serif; font-size:14px;\">";
						echo "<td align=\"center\">$typePayRefValue</td>";
						echo "<td align=\"center\">$debtDueDate</td>";
						echo "<td align=\"right\">".number_format($debtNet,2)."</td>";
						echo "<td align=\"right\">".number_format($debtVat,2)."</td>";
						echo "<td align=\"right\">".number_format($typePayLeft,2)."</td>";
						echo "</tr>";						
					}
					}
					
				?>
			</table>			
		</div>
<?php }?>

<div align="left">
	<table >
		<tr >
			<td style="color:#3366CC; font-family:Arial, Helvetica, sans-serif; font-size:18px;">
			<b>สรุปยอดค้างชำระ::</b>
			</td>
		</tr>



<?php 
if(($numrows_backamt<=0)or ($backAmt==""))
		{  echo ""; }
	  else { ?>
<br>
<tr>
	<td><font color="#FF0000"><b>รวมยอดค่างวดค้างชำระ :  <b></td>
	<td><font color="#FF0000"> <?php echo number_format($backAmt,2);$Net=$Net+$backAmt; ?></font></td>
	<td><font color="#FF0000"><b> บาท  <b></font></td>	
	</tr>

<?php }?>
	
<?php 
if($numrows_other==0)
		{  echo ""; }
	  else { ?>
<br>
<tr>
	<td><font color="#FF0000"><b>รวมค่าใช้จ่ายอื่น ๆ  : <b></td>
	<td><font color="#FF0000"> <?php echo number_format($sumdoerStamp,2);$Net=$Net+$sumdoerStamp; ?></font></td>	
	<td><font color="#FF0000"><b> บาท  <b></font></td>
	</tr>

<?php }?>
<?php if($cutidno!="JV"){
  echo "";
} else{ ?>
<br>
<tr>
	<td><font color="#FF0000"><b>รวมค่าที่ปรึกษา    :  <b></font></td>
	<td><font color="#FF0000"> <?php echo number_format($sumMoney,2);$Net=$Net+$sumMoney; ?></font></td>	
	<td><font color="#FF0000"><b> บาท  <b></font></td>
	</tr>
<?php }?>
<br>
<br>
<tr>
	<td><font size="4" color="#FF0000"><b>ยอดรวมทั้งหมด  :: <b></font>
	</td>
	<td><font size="4" color="#FF0000">
	 <?php echo number_format($Net,2);?>
	 </font>
	</td>
	<td><font size="4" color="#FF0000"><b> บาท  <b></font></td>

</tr>
	</table>
</div>
	
	


