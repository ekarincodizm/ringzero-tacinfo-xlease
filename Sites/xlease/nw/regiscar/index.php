<?php
include("../../config/config.php"); 
$mm = $_POST['mm'];
$yy = $_POST['yy'];
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title><?php echo $_SESSION['session_company_name']; ?></title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">

$(document).ready(function(){
	$("#show_my").hide();
    $("#condition").change(function(){
        var src = $('#condition option:selected').attr('value');
        if ( src == "1" ){
           $("#show_my").hide();
        }else if( src == "2" ){
           $("#show_my").show();
		}
    });
	
	if(document.getElementById('condition').value=='2'){
		$("#show_my").show();
	}else if(document.getElementById('condition').value=='1'){
		$("#show_my").hide();
	}
	
});
</script>
    
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>    
    
</head>
<body>
<?php include("menu.php"); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
 <fieldset><legend><b>แสดงรายการ ค้างชำระค่าใช้จ่าย</b></legend>
		<form method="post" action="frm_all_show_bill.php" name="form1">
			<table width="100%" border="0" align="center">
			<tr>
				<td align="right" height="30" width="50%">
					<b>ค้นจาก</b> <select name="condition" id="condition">
					<option value="1" <?php if($condition==1){ echo "selected";}?>>ข้อมูลที่ค้างทั้งหมด</option>
					<option value="2" <?php if($condition==2){ echo "selected";}?>>ข้อมูลที่ค้างประจำเดือน/ปี</option>
					</select>
				</td>
				<td align="left">
					<b>Sort By</b> <select name="sortby">
					<option value="IDNO" <?php if($sortby=="IDNO"){ echo "selected";}?>>เลขที่สัญญา</option>
					<option value="full_name" <?php if($sortby=="full_name"){ echo "selected";}?>>ชื่อ-สกุลผู้เช่าซื้อ</option>
					<option value="TaxDueDate" <?php if($sortby=="TaxDueDate"){ echo "selected";}?>>เดือนที่ตั้งหนี้</option>
					</select>
					<input type="hidden" name="sort" value="ASC">
					<input type="submit" value="ค้นหา">
				</td>
			</tr>
			<tr id="show_my">
				<td align="right" height="30">
					<font color="red"><b>เดือน </b></font><select name="month">
						<option value="01" <?php if($month == "01"){ echo "selected";}?> selected>มกราคม</option>
						<option value="02" <?php if($month == "02"){ echo "selected";}?>>กุมภาพันธ์</option>
						<option value="03" <?php if($month == "03"){ echo "selected";}?>>มีนาคม</option>
						<option value="04" <?php if($month == "04"){ echo "selected";}?>>เมษายน</option>
						<option value="05" <?php if($month == "05"){ echo "selected";}?>>พฤษภาคม</option>
						<option value="06" <?php if($month == "06"){ echo "selected";}?>>มิถุนายน</option>
						<option value="07" <?php if($month == "07"){ echo "selected";}?>>กรกฎาคม</option>
						<option value="08" <?php if($month == "08"){ echo "selected";}?>>สิงหาคม</option>
						<option value="09" <?php if($month == "09"){ echo "selected";}?>>กันยายน</option>
						<option value="10" <?php if($month == "10"){ echo "selected";}?>>ตุลาคม</option>
						<option value="11" <?php if($month == "11"){ echo "selected";}?>>พฤศจิกายน</option>
						<option value="12" <?php if($month == "12"){ echo "selected";}?>>ธันวาคม</option>
					</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<font color="red"><b>ปี พ.ศ. :</b></font>
					<select name="year">
						<option value="2011" <?php if($year == "2011"){ echo "selected";}?> selected>2554</option>
						<option value="2012" <?php if($year == "2012"){ echo "selected";}?>>2555</option>
						<option value="2013" <?php if($year == "2013"){ echo "selected";}?>>2556</option>
						<option value="2014" <?php if($year == "2014"){ echo "selected";}?>>2557</option>
						<option value="2015" <?php if($year == "2015"){ echo "selected";}?>>2558</option>
						<option value="2016" <?php if($year == "2016"){ echo "selected";}?>>2559</option>
						<option value="2017" <?php if($year == "2017"){ echo "selected";}?>>2560</option>
						<option value="2018" <?php if($year == "2018"){ echo "selected";}?>>2561</option>
						<option value="2019" <?php if($year == "2019"){ echo "selected";}?>>2562</option>
						<option value="2020" <?php if($year == "2020"){ echo "selected";}?>>2563</option>
						<option value="2021" <?php if($year == "2021"){ echo "selected";}?>>2564</option>
						<option value="2022" <?php if($year == "2022"){ echo "selected";}?>>2565</option>
						<option value="2023" <?php if($year == "2023"){ echo "selected";}?>>2566</option>
						<option value="2024" <?php if($year == "2024"){ echo "selected";}?>>2567</option>
						<option value="2025" <?php if($year == "2025"){ echo "selected";}?>>2568</option>
					</select>
				</td>
				<td>&nbsp;</td>
			</tr>
			</table>
		</form>

		<table width="80%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#D0D0D0" class="sort-table">
		<thead>
		<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" height="25">
			<th align="center" <?php if($sortby2=="IDNO"){ echo "bgcolor=#ff6600";}?>"><a href="frm_all_show_bill.php?type=1&condition=<?php echo $condition?>&month=<?php echo $month?>&year=<?php echo $year?>&sortby=IDNO&sort=<?php echo $sort;?>"><U>IDNO</U></a></th>
			<th align="center" <?php if($sortby2=="full_name"){ echo "bgcolor=#ff6600";}?>"><a href="frm_all_show_bill.php?type=1&condition=<?php echo $condition?>&month=<?php echo $month?>&year=<?php echo $year?>&sortby=full_name&sort=<?php echo $sort;?>"><U>ชื่อผู้เช่าซื้อ</U></a></th>
			<th align="center" <?php if($sortby2=="carregis"){ echo "bgcolor=#ff6600";}?>"><a href="frm_all_show_bill.php?type=1&condition=<?php echo $condition?>&month=<?php echo $month?>&year=<?php echo $year?>&sortby=carregis&sort=<?php echo $sort;?>"><U>ทะเบียนรถยนต์</U></a></th>
			<th align="center" <?php if($sortby2=="nameone"){ echo "bgcolor=#ff6600";}?>"><a href="frm_all_show_bill.php?type=1&condition=<?php echo $condition?>&month=<?php echo $month?>&year=<?php echo $year?>&sortby=nameone&sort=<?php echo $sort;?>"><U>ประเภทค่าใช้จ่าย</U></a></th>
			<th align="center" <?php if($sortby2=="CusAmt"){ echo "bgcolor=#ff6600";}?>"><a href="frm_all_show_bill.php?type=1&condition=<?php echo $condition?>&month=<?php echo $month?>&year=<?php echo $year?>&sortby=CusAmt&sort=<?php echo $sort;?>"><U>ยอดชำระ</U></a></th>
			<th align="center" <?php if($sortby2=="TaxDueDate"){ echo "bgcolor=#ff6600";}?>"><a href="frm_all_show_bill.php?type=1&condition=<?php echo $condition?>&month=<?php echo $month?>&year=<?php echo $year?>&sortby=TaxDueDate&sort=<?php echo $sort;?>"><U>หนี้ประจำเดือน/ปี</U></a></th>		
		</tr>
		</thead>
		<?php
		if($sortby2 !=""){
			$sortby=$sortby2;
		}
		if($condition !=""){	
			//แสดงข้อมูลหลักทั้งหมดที่ยังค้างชำระ
			if($condition==1){ //แสดงข้อมูลที่ค้างทั้งหมด
				$query_main=pg_query("select \"IDNO\",\"IDCarTax\",\"nameone\",\"CusAmt\",\"TaxDueDate\",\"A_FIRNAME\",\"full_name\",\"carregis\",\"gasregis\" from \"VNwBillcar\" 
				group by \"IDNO\",\"IDCarTax\",\"nameone\",\"CusAmt\",\"TaxDueDate\",\"A_FIRNAME\",\"full_name\",\"carregis\",\"gasregis\" order by \"$sortby\" $sort");
			}else{ //แสดงข้อมูลที่ค้างประจำเดือน/ปี
				$query_main=pg_query("select \"IDNO\",\"IDCarTax\",\"nameone\",\"CusAmt\",\"TaxDueDate\",\"A_FIRNAME\",\"full_name\",\"carregis\",\"gasregis\" from \"VNwBillcar\" 
				where (EXTRACT(MONTH FROM \"TaxDueDate\")='$month' AND EXTRACT(YEAR FROM \"TaxDueDate\")='$year')
				group by \"IDNO\",\"IDCarTax\",\"nameone\",\"CusAmt\",\"TaxDueDate\",\"A_FIRNAME\",\"full_name\",\"carregis\",\"gasregis\" order by \"$sortby\" $sort");
			}
			$numrows=pg_num_rows($query_main);
			$TaxValueallsum=0;
			while($result_main=pg_fetch_array($query_main)){
				$IDNO=$result_main["IDNO"]; //เลขที่สัญญา
				$IDCarTax=$result_main["IDCarTax"];
				$nameone=$result_main["nameone"]; //ประเภทค่าใช้จ่ายของรายการหลัก
				$CusAmt=$result_main["CusAmt"]; //ยอดชำระค่าใช้จ่ายหลัก
				$TaxDueDate=$result_main["TaxDueDate"]; //วันที่ตั้งหนี้
				$yearshow=substr($TaxDueDate,0,4) + 543;
				$monthshow=substr($TaxDueDate,5,2);
				if($monthshow == "01"){
					$monthshow2="มกราคม";
				}elseif($monthshow == "02"){
					$monthshow2="กุมภาพันธ์";
				}elseif($monthshow == "03"){
					$monthshow2="มีนาคม";
				}elseif($monthshow == "04"){
					$monthshow2="เมษายน";
				}elseif($monthshow == "05"){
					$monthshow2="พฤษภาคม";
				}elseif($monthshow == "06"){
					$monthshow2="มิถุนายน";
				}elseif($monthshow == "07"){
					$monthshow2="กรกฎาคม";
				}elseif($monthshow == "08"){
					$monthshow2="สิงหาคม";
				}elseif($monthshow == "09"){
					$monthshow2="กันยายน";
				}elseif($monthshow == "10"){
					$monthshow2="ตุลาคม";
				}elseif($monthshow == "11"){
					$monthshow2="พฤศจิกายน";
				}elseif($monthshow == "12"){
					$monthshow2="ธันวาคม";
				}
				$cusname=trim($result_main["A_FIRNAME"]).$result_main["full_name"]; //ชื่อลูกค้า
				$carregis=$result_main["carregis"]; //ทะเบียนรถ
				$gasregis=$result_main["gasregis"]; //ทะเบียนรถแกส
		
				if($carregis==""){
					$car_regis=$gasregis;
				}else{
					$car_regis=$carregis;
				}
				?>
				<tr bgcolor="#CEFFCE" style="font-weight:bold;">
					<td align="center"><?php echo $IDNO; ?></td>
					<td><?php echo $cusname; ?></td>
					<td align="center"><?php echo $car_regis; ?></td>
					<td align="left"><?php echo "$nameone"; ?></td>
					<td align="right"><?php echo number_format($CusAmt,2); ?></td>	
					<td align="center"><?php echo "$monthshow2/$yearshow"; ?></td>		
				</tr>
				<?php
					//แสดงข้อมูลย่อย
				$query=pg_query("select * from \"VNwBillcar\" where \"IDCarTax\"='$IDCarTax'");
				$i=0;
				$TaxValuesum=0;
				while($result=pg_fetch_array($query)){
					$nametwo=$result["nametwo"]; //ประเภทค่าใช้จ่ายของรายการย่อย
					$TaxValue=$result["TaxValue"]; //ยอดชำระของรายการย่อย
					$CoPayDate=$result["CoPayDate"]; //วันที่ของรายการย่อย
					$yearshow=substr($CoPayDate,0,4) + 543;
					$monthshow=substr($CoPayDate,5,2);
					$dayshow=substr($CoPayDate,8,2);
					$dateshow="$dayshow-$monthshow-$yearshow";
					if($i%2==0){
						echo "<tr class=\"odd\">";
					}else{
						echo "<tr class=\"even\">";
					}
					?>
					<td align="center" colspan="3" class="odd"></td>
					<td align="right"><?php echo $nametwo; ?></td>
					<td align="right"><?php echo number_format($TaxValue,2); ?></td>
					<td align="center"><?php echo $dateshow; ?></td>					
				</tr>
				<?php
				$TaxValuesum=$TaxValuesum+$TaxValue; //รวมแต่ละรายการ
				$i++;		
				}	//end while รายการย่อย
				?>
				<tr bgcolor="#FFFFEA"><td colspan="4" align="right"><b>รวมเงิน</b></td><td align="right"><b><?php echo number_format($TaxValuesum,2);?></b></td><td></td></tr>
				<?php
				$TaxValueallsum=$TaxValueallsum+$TaxValuesum; //รวมทั้งหมด
			} //end while รายการหลัก
			?>
			<tr bgcolor="#79BCFF"><td colspan="4" align="right"><b><U>รวมเงินทุกรายการ</U></b></td><td align="right"><b><U><?php echo number_format($TaxValueallsum,2);?></U></b></td><td></td></tr>
			<?php
			if($numrows != ""){
			?>
			<tr height="30" bgcolor="#FFFFFF">
				<td colspan="6" align="right">
					<form method="post" name="form2" action="pdf_show_bill.php" target="_blank">
						<input type="hidden" name="condition" value="<?php echo $condition;?>">
						<input type="hidden" name="sortby" value="<?php echo $sortby;?>">
						<input type="hidden" name="month" value="<?php echo $month;?>">
						<input type="hidden" name="monthshow" value="<?php echo $monthshow2;?>">
						<input type="hidden" name="year" value="<?php echo $year;?>">
						<input type="submit" value="พิมพ์รายงาน">
					</form>
				</td>
			</tr>
			<?php
			}
		} // end if
		?>                                                           
		</table>
		</fieldset>


		</td>
	</tr>
</table>

</body>
</html>