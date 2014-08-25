<?php
include("../../config/config.php");
set_time_limit(0);
$CusID = pg_escape_string($_GET['CusID']);




///function ---------------

function detailcar($cusid){ //หาข้อมูลรถยนต์

	unset($data);
	//ค้นหารายละเอียดรถ
			$qry_cardetail = pg_query("SELECT \"C_YEAR\", \"C_REGIS\", \"C_CARNUM\", \"C_CARNAME\", \"C_COLOR\" FROM \"VCarregistemp\" where \"IDNO\" = '$cusid'");
			$resulq_cardetail = pg_fetch_array($qry_cardetail);
			$row_cardetail = pg_num_rows($qry_cardetail);
						
	//ระบุรายละเอียดรถลงตัวแปร
	if($row_cardetail > 0){
		$data[0] = $resulq_cardetail['C_YEAR']; // ปีรถ
		$data[1] = $resulq_cardetail['C_REGIS']; //เลขทะเบียน
		$data[2] = $resulq_cardetail['C_CARNUM']; //รหัสตัวถัง			
		$data[3] = $resulq_cardetail['C_CARNAME']; //ประเภทรถ
		$data[4] = $resulq_cardetail['C_COLOR']; //สี	
	}else{	
		$qry_cargas = pg_query("SELECT b.car_regis,b.gas_type,b.car_year,b.carnum FROM \"Fp\" a left join \"FGas\" b on b.\"GasID\" = a.asset_id where a.\"IDNO\" = '$cusid'");
		$resulq_cargas = pg_fetch_array($qry_cargas);
		
		$data[0] = $resulq_cargas['car_year']; // ปีรถ
		$data[1] = $resulq_cargas['car_regis']; //เลขทะเบียน
		$data[2] = $resulq_cargas['carnum']; //รหัสตัวถัง
		$data[3] = "Gas"; //ประเภทรถ							
	}
	
	if($data[0] != ""){ $data[0] = 'ปีรถ:'."<b>".$data[0]."</b>";}
	if($data[1] != ""){ $data[1] = 'ทะเบียนรถ:'."<b>".$data[1]."</b>";}
	if($data[2] != ""){ $data[2] = 'เลขตัวถัง:'."<b>".$data[2]."</b>";}
	if($data[3] != ""){ $data[3] = 'ประเภทรถ:'."<b>".$data[3]."</b>";}
	if($data[4] != ""){ $data[4] = 'สีรถ:'."<b>".$data[4]."</b>";}
	
	return $data;

}

function  behindhand($IDNO){ //หาข้อมูลการค้างชำระ
	unset($code);
			//นำ IDNO ที่ได้ มาตรวจสอบดูว่าค้างกี่เดือน
			$qry_fr=pg_query("SELECT xls_get_backduenum('$IDNO','1')");
				list($code[0]) = pg_fetch_array($qry_fr); //จำนวนงวดที่ค้างชำระภาษาไทย
			$qry_fr=pg_query("SELECT xls_get_backduenum('$IDNO')");
				list($SumDueNo) = pg_fetch_array($qry_fr); //จำนวนงวดที่ค้างชำระ

				//กำหนดสีของสถานะการค้างชำระ
				if($SumDueNo=="00"){ //สัญญาปิด
					$code[1]='#000000';
					$code[2]='#E0E0E0';
				}else if($SumDueNo=="1"){ //ค้าง 1 งวด
					$code[1]='#9933FF';
				}else if($SumDueNo=="2"){ // ค้าง 2 งวด
					$code[1]='ORANGE';
				}else if($SumDueNo>="3"){ // ค้าง 3 งวดขึ้นไป
					$code[1]='RED';
				}else{ // สัญญาปกติ
					$code[1]="#00DDDD";
				}
			
		return $code;	
}

function securities($cusid){ //หาหลักทรัพย์ค้ำประกัน
	unset($count);
	$sql = pg_query("SELECT count(\"securID\") FROM nw_securities_customer where \"CusID\" = '$cusid' ");
	list($count) = pg_fetch_array($sql);
	if($count == 0){
		$count = " ไม่มีหลักทรัพย์ค้ำประกันกับบริษัท ";
	}else{
		$count = "<font color=\"red\">"."  <u><a style=\"cursor:pointer;\" onclick=\"javascript:popU('Data_securities.php?cusid=$cusid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=650');\">".$count." หลักทรัพย์ค้ำประกันกับบริษัท</a></u></font>";
	}
		return $count;
}

function paymentlatebox($idno){ //หาวันที่จ่ายล่าช้าของแต่ละสัญญา สามารถดูได้ที่ไฟล์ แสดงตารางผ่อนชำระ

$search_under_idno = $idno; 
$ssdate = nowDate();
$qry_VCusPayment=pg_query("select \"DueDate\" from \"VCusPayment\" WHERE (\"IDNO\"='$idno') AND (\"R_Receipt\" IS NULL) ORDER BY \"DueDate\" LIMIT(1)");
$res_VCusPayment=pg_fetch_array($qry_VCusPayment);
$stdate=$res_VCusPayment["DueDate"];
	do{
		$qry_underlv=pg_query("select \"P_TransferIDNO\",\"asset_id\",\"asset_type\" from \"Fp\" WHERE \"IDNO\"='$search_under_idno'");
		if($res_underlv=pg_fetch_array($qry_underlv)){
			$P_TransferIDNO=$res_underlv["P_TransferIDNO"];
			if(!empty($P_TransferIDNO)){
				$list_idno[]=$P_TransferIDNO;
				$search_under_idno = $P_TransferIDNO;
			}else{
				$search_under_idno = "";
			}
		}
	}while(!empty($search_under_idno)); 
$list_idno = @array_reverse($list_idno);
$list_idno[] = $idno;
$search_top_idno = $idno; 
	do{
		$qry_toplv=pg_query("select \"IDNO\",\"asset_id\",\"asset_type\" from \"Fp\" WHERE \"P_TransferIDNO\"='$search_top_idno'");
		if($res_toplv=pg_fetch_array($qry_toplv)){
			$list_idno[]=$res_toplv["IDNO"];
			$search_top_idno=$res_toplv["IDNO"];
		}else{
			$search_top_idno = "";
		}
	}while(!empty($search_top_idno)); 
$list_idno = @array_reverse($list_idno);
$count_idno = count($list_idno);

	for($b=0; $b<$count_idno; $b++){	
		$qry_FpFa1=pg_query("select A.\"P_MONTH\",A.\"P_VAT\",B.* from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$list_idno[$b]'");
		$res_FpFa1=pg_fetch_array($qry_FpFa1);
		$s_payment_all = $res_FpFa1["P_MONTH"]+$res_FpFa1["P_VAT"];

					$qry_before=pg_query("select \"daydelay\" from \"VCusPayment\" WHERE  (\"IDNO\"='$list_idno[$b]') AND (\"R_Date\" is not null)"); //หารายการที่ชำระแล้ว
					while($resbf=pg_fetch_array($qry_before)){
						$colorlist[] = $resbf["daydelay"];						
					}		
					$qry_amt=@pg_query("select '$ssdate'- \"DueDate\" AS \"dateA\"  from  \"VCusPayment\" WHERE  (\"IDNO\"='$list_idno[$b]')  AND (\"DueDate\" BETWEEN '$stdate' AND '$ssdate') "); //รายการที่คำนวณ
					while($res_amt=@pg_fetch_array($qry_amt)){					
						//$colorlist[] = $res_amt["dateA"];
						$colorlist[] = 'nothing';
					}	
	}

	return $colorlist;	
}
///end function --------------


?>

<div class="ui-widget" align="left">
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<?php
if($CusID != ""){
//ค้นหาชื่อ-นามสกุล
$qry_name=pg_query("select \"full_name\",a.\"CusID\",b.\"N_IDCARD\",b.\"N_CARDREF\" from \"VSearchCus\" a
LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"
WHERE a.\"CusID\" = '$CusID'");
$qry_rows = pg_num_rows($qry_name);
if($qry_rows == 0){ echo "<center>ไม่พบรหัสลูกค้ารายนี้ กรุณาค้นหาใหม่ </center>"; exit();}

$result=pg_fetch_array($qry_name);
$name=trim($result["full_name"]);
$CusID=trim($result["CusID"]);
$N_IDCARD=trim($result["N_IDCARD"]);
if($N_IDCARD == ""){
$N_IDCARD=trim($result["N_CARDREF"]);
}
//ค้นหาว่าเป็นผู้เช่าซื้อเลขที่สัญญาใดบ้าง
$query_name2 = pg_query("select \"CusID\" from \"Fp\" WHERE \"CusID\"='$CusID' order by \"P_STDATE\" DESC");
$num_name2 = pg_num_rows($query_name2);

$nub = 1;


//ค้นหาว่าเป็นผู้ค้ำเลขที่สัญญาใดบ้าง
$query_name3 = pg_query("select a.\"IDNO\",a.\"CusState\",b.\"P_ACCLOSE\",b.\"P_CLDATE\",b.\"P_STDATE\" from \"ContactCus\" a
LEFT JOIN \"Fp\" b on a.\"IDNO\"=b.\"IDNO\" WHERE a.\"CusID\"='$CusID' and \"CusState\" != '0' order by b.\"P_STDATE\" DESC");
$num_name3 = pg_num_rows($query_name3);

$nub2 = 1;

?>

<hr width="1150">
<div align="center" style="padding:5px;"><font size="3px;"><b>---- Xlease ----</b></font></div>
<div style="background-color:#FFFFCC;width:1140px;margin:0px auto;padding:5px;"><b>ความหมายของสี LINK:</b>
<span style="background-color:black;">&nbsp;&nbsp;&nbsp;</span> สัญญาปิดแล้ว&nbsp;
<span style="background-color:#00DDDD;">&nbsp;&nbsp;&nbsp;</span> สัญญาปกติ&nbsp;
<span style="background-color:#9933FF;">&nbsp;&nbsp;&nbsp;</span> ค้าง 1 งวด&nbsp;
<span style="background-color:orange;">&nbsp;&nbsp;&nbsp;</span> ค้าง 2 งวด&nbsp;
<span style="background-color:red;">&nbsp;&nbsp;&nbsp;</span> ค้างตั้งแต่ 3 งวดขึ้นไป&nbsp;
</div>
<table width="1150" cellSpacing="1" cellPadding="3" border="0" bgcolor="#D7F0FD" align="center">
<tr bgcolor="#BCE6FC">
    <td width="150" align="right"><b>ชื่อ/สกุล :</b></td>
    <td bgcolor="#FFFFFF"><font color="#0000FF"><b><?php echo "$name (รหัสลูกค้่า $CusID)"; ?></b></font><?php echo $showsecur = securities($CusID); ?><br>
	<span id="sum1" style="font-size:14px; color:#FF0000;"></span>
	<span id="payment1" style="font-size:14px; color:#CE0000;"></span>
	<span id="avg1" style="font-size:14px; color:#006600;"></span>
    <input type="hidden" name="h_cus_id" id="h_cus_id" value="<?php echo $CusID; ?>" />
    <input type="hidden" name="h_fname" id="h_fname" value="<?php echo $name; ?>" />
	</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>เลขที่สัญญา(ผู้เช่าซื้อ) :</b></td>
    <td bgcolor="#FFFFFF">
		<table width="100%">
	<?php 
	if($num_name2 == 0){
		echo "<tr><td>-- ไม่พบข้อมูล --</td></tr>";
		$avgsum1 = "";
		$summary1 = "";
		$payment1 = "";
		echo "<input type=\"hidden\" id=\"summary1\" name=\"summary1\" value=\"$summary1\">";		
		echo "<input type=\"hidden\" id=\"paymentsum1\" value=\"$payment1\">";
		echo "<input type=\"hidden\" id=\"avgsum1\" value=\"$avgsum1\">";
	}else{
		$numcar1 = 0;
		while($res_name2=pg_fetch_array($query_name2)){
			$IDNO=$res_name2["IDNO"]; 
			$P_ACCLOSE = trim($res_name2["P_ACCLOSE"]);
			$P_CLDATE = trim($res_name2["P_CLDATE"]);
			$P_STDATE = trim($res_name2["P_STDATE"]);
			
			//นำ IDNO ที่ได้ มาตรวจสอบดูว่าค้างกี่เดือน
			$qry_fr=pg_query("select \"IDNO\",COUNT(\"DueNo\") as \"SumDueNo\" from \"VRemainPayment\" where \"IDNO\"='$IDNO' GROUP BY \"IDNO\" ");
			if($res_fr=pg_fetch_array($qry_fr)){
				$SumDueNo = $res_fr["SumDueNo"]; //จำนวนงวดที่ค้างชำระ
			}
			//หาจำนวนวันจ่ายย้อนหลัง
				$colorboxlist = paymentlatebox($IDNO);
				$sizeofrow = sizeof($colorboxlist);
				if($sizeofrow > 36){
					$limit = $sizeofrow - 36;
				}else{
					$limit = 0;
				}
					
			//จบหาจำนวนวันจ่ายย้อนหลัง
		
			list($txtclose,$color,$colorstatefield) = behindhand($IDNO);						
			list($C_YEAR,$C_REGIS,$C_CARNUM,$C_CARNAME,$C_COLOR) =  detailcar($IDNO);
			
			if($P_ACCLOSE=='t' AND ($P_CLDATE != $P_STDATE)){
				$sumpricelast = '-';
			}else{
				$numcar1++;
			//หายอดค้างเช่าซื้อ (งวดที่จ่ายล่าสุด)
					$qry_FpFa1=pg_query("select A.\"P_MONTH\", A.\"P_VAT\", A.\"P_TOTAL\" from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$IDNO'");
					$res_FpFa1=pg_fetch_array($qry_FpFa1);
					$s_payment_all = $res_FpFa1["P_MONTH"]+$res_FpFa1["P_VAT"];
					$s_fp_ptotal = $res_FpFa1["P_TOTAL"];
					$money_all_in_vat = $s_payment_all*$s_fp_ptotal;
					
					$qry_before=pg_query("select MAX(\"DueNo\") as \"DueNo1\" from \"VCusPayment\" WHERE  (\"IDNO\"='$IDNO') AND (\"R_Date\" is not null)");
					$resbf=pg_fetch_array($qry_before);
					$sumpricelast = @number_format($money_all_in_vat-($resbf["DueNo1"]*$s_payment_all),2);
					$sumcal = $money_all_in_vat-($resbf["DueNo1"]*$s_payment_all);
					$s_payment_sumall += $s_payment_all;	
			}		
					
			//ตรวจสอบการมีอยู่จริงของข้อมูลและต่อ string คำอธิบาย 		
	
					if($sumpricelast != ""){ $sumpricelast = 'ยอดเช่าซื้อคงเหลือ :'."<b>".$sumpricelast."</b>";}
				if($color=="#000000"&&$txtclose=="สัญญาปิดแล้ว")
				{
					$IDNO = "<a style=\"cursor:pointer\"  onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$IDNO&type=outstanding','$IDNO_sdasdsadsa; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางการชำระเงิน\"><font color=$color><U><span title=\"$txtclose\"><img src=\"images/paper.png\" />$IDNO</span></U></font></a>";
				}
				else
				{
					$IDNO = "<a style=\"cursor:pointer\"  onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$IDNO&type=outstanding','$IDNO_sdasdsadsa; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางการชำระเงิน\"><font color=$color><U><span title=\"$txtclose\">$IDNO</span></U></font></a>";
				}
				echo "<tr><td><table width=\"100%\" cellspacing=\"1\" bgcolor=\"$colorstatefield\">";
				echo "<tr><td>$IDNO <font color=$color>$C_REGIS $C_CARNUM $C_CARNAME $C_COLOR $C_YEAR $sumpricelast</font></td></tr>"; 
				echo "<tr><td><table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"#E0E0E0\">
					<tr>";
				for($i = $sizeofrow-1;$i>=$limit;$i--){
				 
					 if($colorboxlist[$i] == 'nothing'){ //แดงเข้มปี๊ดดด
							$colorbox = '#CC0000';
							$numshow = 'N';
					}else{
					 
						$numshow = ceil(($colorboxlist[$i] - 7)/30);
						if($numshow <= 0){ $numshow = 0; }
						
						if($colorboxlist[$i]<= 7){ //เขียว
							$colorbox = '#00AA00';
						}else if($colorboxlist[$i] <= 37){ //แดงอ่อน
							$colorbox = '#FF6666';
						}else if($colorboxlist[$i] <= 67){ //แดง
							$colorbox = '#FF3333';
						}else if($colorboxlist[$i] <= 97){ //แดงเข้ม
							$colorbox = '#FF0000';
						}else if($colorboxlist[$i] > 97){ //แดงเข้มปี๊ดดด
							$colorbox = '#CC0000';
						}else{
							$colorbox = '';
						}
					}	
					echo "
						<td>
							<table width=\"20px\" height=\"10px;\" frame=\"box\" bgcolor=\"$colorbox\">
								<tr><td align=\"center\" ><font size=\"2px;\">$numshow</font></td></tr>
							</table>
						</td>	
						";
				}
				echo "</tr></table>
					</td></tr>
					</table></td></tr>";
			
			$nub++;
			$SumDueNo="";
			$summary1 = $summary1 + $sumcal;
			$C_YEAR = "";
			$C_REGIS = "";
			$C_COLOR = "";
			$C_CARNUM = "";
			$C_CARNAME = "";
			$sumpricelast = "";
			$sumcal = "";
			$s_payment_all ="";
			$s_fp_ptotal="";
			$money_all_in_vat="";
			unset($colorbox);
			unset($numshow);
			unset($limit);
			unset($sizeofrow);
			
			
		}
		$avgsum1 = @number_format($summary1/$numcar1,2);
		$summary1 = @number_format($summary1,2);
		$s_payment_sumall =  @number_format($s_payment_sumall,2);
		echo "<input type=\"hidden\" id=\"summary1\" name=\"summary1\" value=\"$summary1\">";
		echo "<input type=\"hidden\" id=\"avgsum1\" value=\"$avgsum1\">";
		echo "<input type=\"hidden\" id=\"paymentsum1\" value=\"$s_payment_sumall\">";

	?>
	
			<tr>
				<td><?php echo "<span style=\"background-color:yellow;\"><font size=2 color=red><b>(รวม $num_name2 สัญญา)</b></font></span>";?></td>
			</tr>
	<?php $summary1 = "";
			$avgsum1="";
			$s_payment_sumall ="";
	} ?>		
		</table>
	</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>เลขที่สัญญา(ผู้ค้ำ) :</b></td>
    <td bgcolor="#FFFFFF">
		<table width="100%">
	<?php 
		if($num_name3 == 0){
			echo "<tr><td>-- ไม่พบข้อมูล --</td></tr>";
		}else{
			$pp = 2;
			echo "<tr><td>
			<span id=\"sumg$pp\" style=\"font-size:14px; color:#FF0000;\"></span>
			<span id=\"paymentg$pp\" style=\"font-size:14px; color:#CE0000;\"></span>
			<span id=\"avg$pp\" style=\"font-size:14px; color:#006600;\"></span>
			</td></tr>";
			$summary3 = "";
			$numcar2=0;	
			while($res_name3=pg_fetch_array($query_name3)){
				$IDNO2=$res_name3["IDNO"]; 
				$CusState = $res_name3["CusState"];
				$P_ACCLOSE2 = trim($res_name3["P_ACCLOSE"]);
				$P_CLDATE2 = trim($res_name3["P_CLDATE"]);
				$P_STDATE2 = trim($res_name3["P_STDATE"]);
				
				//หาจำนวนวันจ่ายย้อนหลัง
				$colorboxlist = paymentlatebox($IDNO2);
				$sizeofrow = sizeof($colorboxlist);
				if($sizeofrow > 36){
					$limit = $sizeofrow - 36;
				}else{
					$limit = 0;
				}
					
				//จบหาจำนวนวันจ่ายย้อนหลัง
				
				
				//นำ IDNO ที่ได้ มาตรวจสอบดูว่าค้างกี่เดือน
				$qry_fr2=pg_query("select \"IDNO\",COUNT(\"DueNo\") as \"SumDueNo\" from \"VRemainPayment\" where \"IDNO\"='$IDNO2' GROUP BY \"IDNO\" ");
				if($res_fr2=pg_fetch_array($qry_fr2)){
					$SumDueNo2 = $res_fr2["SumDueNo"]; //จำนวนงวดที่ค้างชำระ
				}
				
				list($txtclose2,$color2,$colorstatefield) = behindhand($IDNO2);
				list($C_YEAR,$C_REGIS,$C_CARNUM,$C_CARNAME,$C_COLOR) =  detailcar($IDNO2);
							
						if($P_ACCLOSE2=='t' AND ($P_CLDATE2 != $P_STDATE2)){
							$sumpricelast = '-';
						}else{		
							$numcar2++;
							//หายอดค้างเช่าซื้อ (งวดที่จ่ายล่าสุด)
							$qry_FpFa1=pg_query("select A.\"P_MONTH\", A.\"P_VAT\", A.\"P_TOTAL\" from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$IDNO2'");
							$res_FpFa1=pg_fetch_array($qry_FpFa1);
							$s_payment_all = $res_FpFa1["P_MONTH"]+$res_FpFa1["P_VAT"];
							$s_fp_ptotal = $res_FpFa1["P_TOTAL"];
							$money_all_in_vat = $s_payment_all*$s_fp_ptotal;
							
							
							$qry_before=pg_query("select MAX(\"DueNo\") as \"DueNo1\" from \"VCusPayment\" WHERE  (\"IDNO\"='$IDNO2') AND (\"R_Date\" is not null)");
							$resbf=pg_fetch_array($qry_before);
							$sumpricelast = @number_format($money_all_in_vat-($resbf["DueNo1"]*$s_payment_all),2);
							$sumcal3 = $money_all_in_vat-($resbf["DueNo1"]*$s_payment_all);
							
							$s_payment_sumall3 += $s_payment_all;	
						}	
					
						
				
					if($sumpricelast != ""){ $sumpricelast = 'ยอดเช่าซื้อคงเหลือ :'."<b>".$sumpricelast."</b>";}
					
					if($color2=="#000000"&&$txtclose2=="สัญญาปิดแล้ว")
					{
						$IDNO2 = "<a style=\"cursor:pointer\" onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$IDNO2&type=outstanding','$IDNO_sdasdsadsa; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" >
					<font color=$color2><U><span title=\"$txtclose2\"><img src=\"images/paper.png\" style=\"border:none;\" />$IDNO2</span></U></font></a>";
					}
					else
					{
						$IDNO2 = "<a style=\"cursor:pointer\" onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$IDNO2&type=outstanding','$IDNO_sdasdsadsa; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" >
					<font color=$color2><U><span title=\"$txtclose2\">$IDNO2</span></U></font></a>";
					}
					echo "<tr><td><table width=\"100%\" cellspacing=\"1\" bgcolor=\"$colorstatefield\">";
					echo "<tr><td>$IDNO2(คนที่ $CusState) <font color=$color2>$C_REGIS $C_CARNUM $C_CARNAME $C_COLOR $C_YEAR $sumpricelast</font></td></tr>"; 
					echo "<tr><td><table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"#E0E0E0\">
					<tr>";
					for($i = $sizeofrow-1;$i>=$limit;$i--){
						
						if($colorboxlist[$i] == 'nothing'){ //แดงเข้มปี๊ดดด
							$colorbox = '#CC0000';
							$numshow = 'N';
						}else{	
							$numshow = ceil(($colorboxlist[$i] - 7)/30);
							if($numshow <= 0){ $numshow = 0; }
							
							if($colorboxlist[$i]<= 7){ //เขียว
								$colorbox = '#00AA00';
							}else if($colorboxlist[$i] <= 37){ //แดงอ่อน
								$colorbox = '#FF6666';
							}else if($colorboxlist[$i] <= 67){ //แดง
								$colorbox = '#FF3333';
							}else if($colorboxlist[$i] <= 97){ //แดงเข้ม
								$colorbox = '#FF0000';
							}else if($colorboxlist[$i] > 97){ //แดงเข้มปี๊ดดด
								$colorbox = '#CC0000';
							}else{
								$colorbox = '';
							}
						}
					echo "
						<td>
							<table width=\"20px\" height=\"10px;\" frame=\"box\" bgcolor=\"$colorbox\">
								<tr><td align=\"center\" ><font size=\"2px;\">$numshow</font></td></tr>
							</table>
						</td>	
						";
					}
				echo "</tr></table>
					</td></tr>
					</table></td></tr>";
					
					
				$nub2++;
				$SumDueNo2="";				
				$C_YEAR = "";
				$C_REGIS = "";
				$C_COLOR = "";
				$C_CARNUM = "";
				$C_CARNAME = "";
				$sumpricelast = "";
				$summary3= $summary3 + $sumcal3;
				$sumcal3 = "";
				$s_payment_all ="";
				$s_fp_ptotal="";
				$money_all_in_vat="";
				unset($colorbox);
				unset($numshow);
				unset($limit);
				unset($sizeofrow);
			}		
				$avgsum2 = @number_format($summary3/$numcar2,2);
				$summary3 = @number_format($summary3,2);
				$s_payment_sumall3 = @number_format($s_payment_sumall3,2);
					echo "<input type=\"hidden\" id=\"summaryg$pp\" value=\"$summary3\">";
					echo "<input type=\"hidden\" id=\"avgsum$pp\" value=\"$avgsum2\">";
					echo "<input type=\"hidden\" id=\"paymentsum$pp\" value=\"$s_payment_sumall3\">";
					?>
					<script>	
						if($("#summaryg<?php echo $pp ?>").val()!=""){
							$("#sumg<?php echo $pp ?>").html(" ยอดเช่าซื้อคงเหลือรวม  "+$("#summaryg<?php echo $pp ?>").val()+" บาท");
						}else{
							$("#sumg<?php echo $pp ?>").html(" ไม่มียอดเช่าซื้อคงเหลือรวม  ");
						}	

						if($("#avgsum<?php echo $pp ?>").val()!=""){
							$("#avg<?php echo $pp ?>").html(" ยอดเช่าซื้อคงเหลือเฉลี่ย/คัน  "+$("#avgsum<?php echo $pp ?>").val()+" บาท");	
						}else{
							$("#avg<?php echo $pp ?>").html(" ไม่มียอดเช่าซื้อคงเหลือเฉลี่ย/คัน");
						}	

						if($("#paymentsum<?php echo $pp ?>").val()!=""){
							$("#paymentg<?php echo $pp ?>").html(" ยอดภาระการผ่อนต่อเดือนรวม "+$("#paymentsum<?php echo $pp ?>").val()+" บาท");	
						}else{
							$("#paymentg<?php echo $pp ?>").html(" ไม่มียอดภาระการผ่อนต่อเดือนรวม");
						}		
					</script>	
			<tr>
				<td><?php echo "<span style=\"background-color:yellow;\"><font size=2 color=red><b>(รวม $num_name3 สัญญา)</b></font></span>";?></td>
			</tr>
		<?php $summary3 = "";
				$avgsum2 = "";
				$s_payment_sumall3 = "";
				} ?>
		</table>		
	</td>
</tr>
</table><br><br>


<?php 
////*********************ลูกค้าที่อาจจะใช่คนเดียวกัน*********************************/////////////////////////
$SumDueNo="";
$SumDueNo2="";
$addbr="";
$sumIDNO="";
$sumIDNO2="";
$num_name3="";
//ค้นหาลูกค้าที่อาจจะใช่คนเดียวกัน
$N_IDCARD=strtr($N_IDCARD, "-", " "); //แปลงค่าที่คีย์ - ให้เป็นช่องว่าง
$N_IDCARD=ereg_replace('[[:space:]]+', '', trim($N_IDCARD)); //ตัดช่องว่างออก


$qry_check=pg_query("select a.\"CusID\",b.\"full_name\" from \"Fn\" a
LEFT JOIN \"VSearchCus\" b on a.\"CusID\"=b.\"CusID\" 
WHERE (replace(replace(\"N_IDCARD\",' ',''),'-','') = '$N_IDCARD' OR
replace(replace(\"N_CARDREF\",' ',''),'-','') = '$N_IDCARD') and a.\"CusID\" NOT IN (select \"CusID\" from \"Fa1\" where \"CusID\"='$CusID')");
$numcheck=pg_num_rows($qry_check);
if($numcheck>0){
	$zz = 2;
	
	echo "<div style=\"margin:0px auto;width:1140px;background-color:#FFFFCC;padding:5px;\"><b>::ลูกค้าที่อาจจะใช่บุคคลเดียวกัน::</b></div>";
	while($rescheck=pg_fetch_array($qry_check)){
	$numcar3=0;
	$numcar4=0;
	$zz++;	
		list($CusID2,$name)=$rescheck;
		
		//ค้นหาว่าเป็นผู้เช่าซื้อเลขที่สัญญาใดบ้าง
		$query_name2 = pg_query("select \"CusID\" from \"Fp\" WHERE \"CusID\"='$CusID2' order by \"P_STDATE\" DESC");
		$num_name2 = pg_num_rows($query_name2);
		
		$nub = 1;
		

		//ค้นหาว่าเป็นผู้ค้ำเลขที่สัญญาใดบ้าง
		$query_name3 = pg_query("select a.\"IDNO\",a.\"CusState\",b.\"P_ACCLOSE\",b.\"P_CLDATE\",b.\"P_STDATE\" from \"ContactCus\" a
		LEFT JOIN \"Fp\" b on a.\"IDNO\"=b.\"IDNO\" WHERE a.\"CusID\"='$CusID2' and \"CusState\" != '0' order by b.\"P_STDATE\" DESC");
		$num_name3 = pg_num_rows($query_name3);

		$nub2 = 1;
		
		
		?>

		
		<table width="1150" cellSpacing="1" cellPadding="3" border="0" bgcolor="#FFE8E8" align="center">
		<tr bgcolor="#FFDDDD">
			<td width="150" align="right"><b>ชื่อ/สกุล :</b></td>
			<td bgcolor="#FFFFFF"><font color="#0000FF"><b><?php echo "$name (รหัสลูกค้่า $CusID2)"; ?></b></font><?php echo $showsecur = securities($CusID2); ?><br>
			<span id="sum<?php echo $zz ?>" style="font-size:14px; color:#FF0000;"></span>			
			<span id="payment<?php echo $zz ?>" style="font-size:14px; color:#CE0000;"></span>
			<span id="avgg<?php echo $zz ?>" style="font-size:14px; color:#006600;"></span>
			</td>
		</tr>
		<tr bgcolor="#FFDDDD">
			<td valign="top" align="right"><b>เลขที่สัญญา(ผู้เช่าซื้อ) :</b></td>
			<td bgcolor="#FFFFFF">
				<table width="100%">
			<?php 
				if($num_name2 == 0){
					echo "<tr><td>-- ไม่พบข้อมูล --</td></tr>";
				}else{
					while($resname2=pg_fetch_array($query_name2)){
						$IDNO=$resname2["IDNO"]; 
						$P_ACCLOSE = trim($resname2["P_ACCLOSE"]);
						$P_CLDATE = trim($resname2["P_CLDATE"]);
						$P_STDATE = trim($resname2["P_STDATE"]);
					
						
						//หาจำนวนวันจ่ายย้อนหลัง
						$colorboxlist = paymentlatebox($IDNO);
						$sizeofrow = sizeof($colorboxlist);
						if($sizeofrow > 36){
							$limit = $sizeofrow - 36;
						}else{
							$limit = 0;
						}
							
						//จบหาจำนวนวันจ่ายย้อนหลัง
					
						//นำ IDNO ที่ได้ มาตรวจสอบดูว่าค้างกี่เดือน
						$qry_fr=pg_query("select \"IDNO\",COUNT(\"DueNo\") as \"SumDueNo\" from \"VRemainPayment\" where \"IDNO\"='$IDNO' GROUP BY \"IDNO\" ");
						if($res_fr=pg_fetch_array($qry_fr)){
							$SumDueNo = $res_fr["SumDueNo"]; //จำนวนงวดที่ค้างชำระ
						}
					
						list($txtclose,$color,$colorstatefield) = behindhand($IDNO);
						list($C_YEAR,$C_REGIS,$C_CARNUM,$C_CARNAME,$C_COLOR) =  detailcar($IDNO);
							
							if($P_ACCLOSE=='t' AND ($P_CLDATE != $P_STDATE)){
								$sumpricelast = '-';
							}else{		
								$numcar3++;
								//หายอดค้างเช่าซื้อ (งวดที่จ่ายล่าสุด)
								$qry_FpFa1=pg_query("select A.\"P_MONTH\", A.\"P_VAT\", A.\"P_TOTAL\" from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$IDNO'");
								$res_FpFa1=pg_fetch_array($qry_FpFa1);
								$s_payment_all = $res_FpFa1["P_MONTH"]+$res_FpFa1["P_VAT"];
								$s_fp_ptotal = $res_FpFa1["P_TOTAL"];
								$money_all_in_vat = $s_payment_all*$s_fp_ptotal;
								
								$qry_before=pg_query("select MAX(\"DueNo\") as \"DueNo1\" from \"VCusPayment\" WHERE  (\"IDNO\"='$IDNO') AND (\"R_Date\" is not null)");
								$resbf=pg_fetch_array($qry_before);
								$sumpricelast = @number_format($money_all_in_vat-($resbf["DueNo1"]*$s_payment_all),2);
								$sumcal2 = $money_all_in_vat-($resbf["DueNo1"]*$s_payment_all);
								$s_payment_allsum2 +=  $s_payment_all;
							}		
										
							
							
							if($sumpricelast != ""){ $sumpricelast = 'ยอดเช่าซื้อคงเหลือ :'."<b>".$sumpricelast."</b>";}
							
							if($color=="#000000"&&$txtclose=="สัญญาปิดแล้ว")
							{
								$IDNO = "<a style=\"cursor:pointer\"  onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$IDNO&type=outstanding','$IDNO_sdasdsadsa; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางการชำระเงิน\"><font color=$color><U><span title=\"$txtclose\"><img src=\"images/paper.png\" />$IDNO</span></U></font></a>";
							}
							else
							{
								$IDNO = "<a style=\"cursor:pointer\"  onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$IDNO&type=outstanding','$IDNO_sdasdsadsa; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางการชำระเงิน\"><font color=$color><U><span title=\"$txtclose\">$IDNO</span></U></font></a>";
							}
							echo "<tr><td><table width=\"100%\" cellspacing=\"1\" bgcolor=\"$colorstatefield\">";
							echo "<tr><td>$IDNO <font color=$color>$C_REGIS $C_CARNUM $C_CARNAME $C_COLOR $C_YEAR $sumpricelast</font><td></tr>";
							echo "<tr><td><table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"#E0E0E0\">
									<tr>";
									for($i = $sizeofrow-1;$i>=$limit;$i--){
										if($colorboxlist[$i] == 'nothing'){ //แดงเข้มปี๊ดดด
												$colorbox = '#CC0000';
												$numshow = 'N';
										}else{
											$numshow = ceil(($colorboxlist[$i] - 7)/30);
											if($numshow <= 0){ $numshow = 0; }
											
											if($colorboxlist[$i]<= 7){ //เขียว
												$colorbox = '#00AA00';
											}else if($colorboxlist[$i] <= 37){ //แดงอ่อน
												$colorbox = '#FF6666';
											}else if($colorboxlist[$i] <= 67){ //แดง
												$colorbox = '#FF3333';
											}else if($colorboxlist[$i] <= 97){ //แดงเข้ม
												$colorbox = '#FF0000';
											}else if($colorboxlist[$i] > 97){ //แดงเข้มปี๊ดดด
												$colorbox = '#CC0000';
											}else{
												$colorbox = '';
											}
										}
										echo "
											<td>
												<table width=\"20px\" height=\"10px;\" frame=\"box\" bgcolor=\"$colorbox\">
													<tr><td align=\"center\" ><font size=\"2px;\">$numshow</font></td></tr>
												</table>
											</td>	
											";
										}
								echo "</tr></table>
									</td></tr>
									</table></td></tr>";
										
						
						
						$nub++;
						$SumDueNo="";
						$C_YEAR = "";
						$C_REGIS = "";
						$C_COLOR = "";
						$C_CARNUM = "";
						$C_CARNAME = "";
						$sumpricelast = "";
						$summary2 = $summary2 + $sumcal2;
						$sumcal2 = "";
						$s_payment_all ="";
						$s_fp_ptotal="";
						$money_all_in_vat="";
						unset($colorbox);
						unset($numshow);
						unset($limit);
						unset($sizeofrow);
					}
					$avgsum3 = @number_format($summary2/$numcar3,2);
					$summary2 = @number_format($summary2,2);
					$s_payment_allsum2 = @number_format($s_payment_allsum2,2);
					echo "<input type=\"hidden\" id=\"summary$zz\" name=\"summary2\" value=\"$summary2\">";
						echo "<input type=\"hidden\" id=\"avggsum$zz\" value=\"$avgsum3\">";
						echo "<input type=\"hidden\" id=\"paymentsum$zz\" value=\"$s_payment_allsum2\">";
					?>
					<script>	
						if($("#summary<?php echo $zz ?>").val()!=""){
							$("#sum<?php echo $zz ?>").html(" ยอดเช่าซื้อคงเหลือรวม  "+$("#summary<?php echo $zz ?>").val()+" บาท");
						}else{
							$("#sum<?php echo $zz ?>").html(" ไม่มียอดเช่าซื้อคงเหลือรวม  ");
						}
						if($("#avggsum<?php echo $zz ?>").val()!=""){
							$("#avgg<?php echo $zz ?>").html(" ยอดเช่าซื้อคงเหลือเฉลี่ย/คัน  "+$("#avggsum<?php echo $zz ?>").val()+" บาท");	
						}else{
							$("#avgg<?php echo $zz ?>").html(" ไม่มียอดเช่าซื้อคงเหลือเฉลี่ย/คัน  ");	
						}
						if($("#paymentsum<?php echo $zz ?>").val()!=""){
							$("#payment<?php echo $zz ?>").html(" ยอดภาระการผ่อนต่อเดือนรวม  "+$("#paymentsum<?php echo $zz ?>").val()+" บาท");	
						}else{
							$("#payment<?php echo $zz ?>").html(" ไม่มียอดภาระการผ่อนต่อเดือนรวม");	
						}
						
					</script>	
						
			
					<tr>
						<td><?php echo "<span style=\"background-color:yellow;\"><font size=2 color=red><b>(รวม $num_name2 สัญญา)</b></font></span> "; ?></td>
					</tr>
		<?php	$summary2 = "";
				$avgsum3 = "";
				$s_payment_allsum2 = "";
		
		}  ?>			
				</table>
			</td>
		</tr>
		<tr bgcolor="#FFDDDD">
			<td valign="top" align="right"><b>เลขที่สัญญา(ผู้ค้ำ) :</b></td>
			<td bgcolor="#FFFFFF">
				<table width="100%">
			<?php 
				
				if($num_name3 == 0){
					echo "<tr><td>-- ไม่พบข้อมูล --</td></tr>";
				}else{
					echo "<tr><td>
						<span id=\"sumguan$zz\" style=\"font-size:14px; color:#FF0000;\"></span>						
						<span id=\"paymentguan$zz\" style=\"font-size:14px; color:#CE0000;\"></span>
						<span id=\"avgguan$zz\" style=\"font-size:14px; color:#006600;\"></span>
					</td></tr>";
					$summary4 = "";
					while($res_name3=pg_fetch_array($query_name3)){
						$IDNO2=$res_name3["IDNO"]; 
						$CusState = $res_name3["CusState"];
						$P_ACCLOSE2 = trim($res_name3["P_ACCLOSE"]);
						$P_CLDATE2 = trim($res_name3["P_CLDATE"]);
						$P_STDATE2 = trim($res_name3["P_STDATE"]);
						
						//หาจำนวนวันจ่ายย้อนหลัง
						$colorboxlist = paymentlatebox($IDNO2);
						$sizeofrow = sizeof($colorboxlist);
						if($sizeofrow > 36){
							$limit = $sizeofrow - 36;
						}else{
							$limit = 0;
						}
							
						//จบหาจำนวนวันจ่ายย้อนหลัง
						
						//นำ IDNO ที่ได้ มาตรวจสอบดูว่าค้างกี่เดือน
						$qry_fr2=pg_query("select \"IDNO\",COUNT(\"DueNo\") as \"SumDueNo\" from \"VRemainPayment\" where \"IDNO\"='$IDNO2' GROUP BY \"IDNO\" ");
						if($res_fr2=pg_fetch_array($qry_fr2)){
							$SumDueNo2 = $res_fr2["SumDueNo"]; //จำนวนงวดที่ค้างชำระ
						}
						
						list($txtclose2,$color2,$colorstatefield) = behindhand($IDNO2);
						list($C_YEAR,$C_REGIS,$C_CARNUM,$C_CARNAME,$C_COLOR) =  detailcar($IDNO2);
							
							if($P_ACCLOSE2=='t' AND ($P_CLDATE2 != $P_STDATE2)){
								$sumpricelast = '-';
							}else{	
								$numcar4++;
								//หายอดค้างเช่าซื้อ (งวดที่จ่ายล่าสุด)
								$qry_FpFa1=pg_query("select A.\"P_MONTH\", A.\"P_VAT\", A.\"P_TOTAL\" from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$IDNO2'");
								$res_FpFa1=pg_fetch_array($qry_FpFa1);
								$s_payment_all = $res_FpFa1["P_MONTH"]+$res_FpFa1["P_VAT"];
								$s_fp_ptotal = $res_FpFa1["P_TOTAL"];
								$money_all_in_vat = $s_payment_all*$s_fp_ptotal;
								
								$qry_before=pg_query("select MAX(\"DueNo\") as \"DueNo1\" from \"VCusPayment\" WHERE  (\"IDNO\"='$IDNO2') AND (\"R_Date\" is not null)");
								$resbf=pg_fetch_array($qry_before);
								$sumpricelast = @number_format($money_all_in_vat-($resbf["DueNo1"]*$s_payment_all),2);
								$sumcal4 = $money_all_in_vat-($resbf["DueNo1"]*$s_payment_all);
								$s_payment_allsum4 += $s_payment_all;
							}		
							
							
							if($sumpricelast != ""){ $sumpricelast = 'ยอดเช่าซื้อคงเหลือ :'."<b>".$sumpricelast."</b>";}
							
							if($color2=="#000000"&&$txtclose2=="สัญญาปิดแล้ว")
							{
								$IDNO2 = "<a style=\"cursor:pointer\"  onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$IDNO2&type=outstanding','$IDNO_sdasdsadsa; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" ><font color=$color2><U><span title=\"$txtclose2\"><img src=\"images/paper.png\" style=\"border:none;\" />$IDNO2</span></U></font></a>";
							}
							else
							{
								$IDNO2 = "<a style=\"cursor:pointer\"  onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$IDNO2&type=outstanding','$IDNO_sdasdsadsa; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" ><font color=$color2><U><span title=\"$txtclose2\">$IDNO2</span></U></font></a>";
							}
							echo "<tr><td><table width=\"100%\" cellspacing=\"1\" bgcolor=\"$colorstatefield\">";
							echo "<tr><td>$IDNO2(คนที่ $CusState) <font color=$color2>$C_REGIS $C_CARNUM $C_CARNAME $C_COLOR $C_YEAR $sumpricelast</font></td></tr>"; 
							echo "<tr><td><table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"#E0E0E0\">
									<tr>";
									for($i = $sizeofrow-1;$i>=$limit;$i--){
											if($colorboxlist[$i] == 'nothing'){ //แดงเข้มปี๊ดดด
													$colorbox = '#CC0000';
													$numshow = 'N';
											}else{	
												
												$numshow = ceil(($colorboxlist[$i] - 7)/30);
												if($numshow <= 0){ $numshow = 0; }
												
												if($colorboxlist[$i]<= 7){ //เขียว
													$colorbox = '#00AA00';
												}else if($colorboxlist[$i] <= 37){ //แดงอ่อน
													$colorbox = '#FF6666';
												}else if($colorboxlist[$i] <= 67){ //แดง
													$colorbox = '#FF3333';
												}else if($colorboxlist[$i] <= 97){ //แดงเข้ม
													$colorbox = '#FF0000';
												}else if($colorboxlist[$i] > 97){ //แดงเข้มปี๊ดดด
													$colorbox = '#CC0000';
												}else{
													$colorbox = '';
												}
											}
									echo "
										<td>
											<table width=\"20px\" height=\"10px;\" frame=\"box\" bgcolor=\"$colorbox\">
												<tr><td align=\"center\" ><font size=\"2px;\">$numshow</font></td></tr>
											</table>
										</td>	
										";
									}
								echo "</tr></table>
									</td></tr>
									</table></td></tr>";
							
							
						$nub2++;
						$SumDueNo2="";				
						$C_YEAR = "";
						$C_REGIS = "";
						$C_COLOR = "";
						$C_CARNUM = "";
						$C_CARNAME = "";
						$sumpricelast = "";
						$summary4= $summary4 + $sumcal4;
						$sumcal4 = "";
						$s_payment_all ="";
						$s_fp_ptotal="";
						$money_all_in_vat="";
						unset($colorbox);
						unset($numshow);
						unset($limit);
						unset($sizeofrow);
					}
					$avgsum4 = @number_format($summary4/$numcar4,2);
					$summary4 = @number_format($summary4,2);
					$s_payment_allsum4 = @number_format($s_payment_allsum4,2);
					
					echo "<input type=\"hidden\" id=\"summaryguan$zz\" value=\"$summary4\">";
					echo "<input type=\"hidden\" id=\"avgguansum$zz\" value=\"$avgsum4\">";
					echo "<input type=\"hidden\" id=\"paymentguansum$zz\" value=\"$s_payment_allsum4\">";
					?>
					<script>	
						if($("#summaryguan<?php echo $zz ?>").val()!=""){
							$("#sumguan<?php echo $zz ?>").html(" ยอดเช่าซื้อคงเหลือรวม  "+$("#summaryguan<?php echo $zz ?>").val()+" บาท");
						}else{
							$("#sumguan<?php echo $zz ?>").html(" ไม่มียอดเช่าซื้อคงเหลือรวม  ");
						}
						if($("#avgguansum<?php echo $zz ?>").val()!=""){
							$("#avgguan<?php echo $zz ?>").html(" ยอดเช่าซื้อคงเหลือเฉลี่ย/คัน  "+$("#avgguansum<?php echo $zz ?>").val()+" บาท");	
						}else{
							$("#avgguan<?php echo $zz ?>").html(" ไม่มียอดเช่าซื้อคงเหลือเฉลี่ย/คัน  ");	
						}
						if($("#paymentguansum<?php echo $zz ?>").val()!=""){
							$("#paymentguan<?php echo $zz ?>").html(" ยอดภาระการผ่อนต่อเดือนรวม  "+$("#paymentguansum<?php echo $zz ?>").val()+" บาท");	
						}else{
							$("#paymentguan<?php echo $zz ?>").html(" ไม่มียอดภาระการผ่อนต่อเดือนรวม");	
						}						
					</script>	
					
					<tr>
						<td><?php echo "<span style=\"background-color:yellow;\"><font size=2 color=red><b>(รวม $num_name3 สัญญา)</b></font></span>"; ?></td>
					</tr>
				<?php 
					$avgsum4 = "";
					$summary4 = "";
					$s_payment_allsum4="";
				
				} ?>				
				</table>
			
			</td>
		</tr>
		</table><br>
	<?php 
		$SumDueNo="";
		$SumDueNo2="";
		$addbr="";
		$sumIDNO="";
		$sumIDNO2="";
		$num_name3="";
	
	}
}
}else{
	echo "<hr width=850>";
	echo "<center><h1>ไม่พบข้อมูล</h1></center>";
}


?>
<script>
	if($("#summary1").val()!=""){
		$("#sum1").html(" ยอดเช่าซื้อคงเหลือรวม  "+$("#summary1").val()+" บาท");
	}else{
		$("#sum1").html(" ไม่มียอดเช่าซื้อคงเหลือรวม  ");
	}
	if($("#avgsum1").val()!=""){	
		$("#avg1").html(" ยอดเช่าซื้อคงเหลือเฉลี่ย/คัน  "+$("#avgsum1").val()+" บาท");	
	}else{
		$("#avg1").html(" ไม่มียอดเช่าซื้อคงเหลือเฉลี่ย/คัน  ");	
	}	
	if($("#paymentsum1").val()!=""){	
		$("#payment1").html(" ยอดภาระการผ่อนต่อเดือนรวม  "+$("#paymentsum1").val()+" บาท");	
	}else{
		$("#payment1").html(" ไม่มียอดภาระการผ่อนต่อเดือนรวม  ");	
	}
	
</script>