<?php // Start Program
set_time_limit(0);
include("../config/config.php");

if(!empty($_POST['tb_search'])){$tb_search = pg_escape_string($_POST['tb_search']);} // ทะเบียนรถ
if(!empty($_POST['mm'])){$mm = pg_escape_string($_POST['mm']);}
if(!empty($_POST['yy'])){$yy = pg_escape_string($_POST['yy']);}
if(!empty($_POST['Car_Type'])){$Cr_Type = pg_escape_string($_POST['Car_Type']);} 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title><?php echo $_SESSION['session_company_name']; ?></title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		/*$('#tb_search').keyup(function(){ // ยกเลิกการใช้ส่วนนี้
			$("#divshow").empty();
			$("#divshow").text('กำลังค้นหาข้อมูล กรุณารอสักครู่...');
			var tbsearch = encodeURIComponent ( $("#tb_search").val() );
			$("#divshow").load("frm_car_show_panel.php?w="+ tbsearch +"&mm="+ $("#mm").val() +"&yy="+ $("#yy").val());
		});*/
	});
</script>
    
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>   
</head>
<?php include("menu.php"); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><!-- Start Of Row -->
		<td>
			<fieldset><legend><b>แสดงรายการรถที่ถึงเวลาตรวจมิเตอร์/ภาษี</b></legend>
				<form method="post" action="" name="f_list" id="f_list">
					<div style="float:left"><b>ค้นหาทะเบียนรถ</b> <input type="text" name="tb_search" id="tb_search" size="30" value="<?php echo $tb_search; ?>"></div>
					<div style="float:right">
					<b>ประเภทรถ</b>
						<select name = "Car_Type">
							<option value="All"<?php if($Cr_Type == "All"){ echo "Selected"; } ?> >ทั้งหมด</option>
							<option value="Taxi"<?php if($Cr_Type == "Taxi"){ echo "Selected"; } ?> >รถแท๊กซี่</option>
							<option value="Car"<?php if($Cr_Type == "Car"){ echo "Selected"; } ?> >รถบ้าน</option>
						</select>
					<b>เดือน</b>
						<select name="mm" id="mm">
							<?php
								if(empty($mm)){
    								$nowmonth = date("m");
								}else{
    							$nowmonth = $mm;
								}
								$month = array('มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฏาคม', 'สิงหาคม' ,'กันยายน' ,'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม');
								for($i=0; $i<12; $i++){
    								$a+=1;
    								if($a > 0 AND $a <10) $a = "0".$a;
    								if($nowmonth != $a){
 								       echo "<option value=\"$a\">$month[$i]</option>";
   									}else{
        								echo "<option value=\"$a\" selected>$month[$i]</option>";
    								}
    							}
							?>    
						</select>
					<b>ปี</b> 
						<select name="yy" id="yy">
							<?php
								if(empty($yy)){
    								$nowyear = date("Y");
								}else{
    								$nowyear = $yy;
								}
								$year_a = $nowyear + 10; 
								$year_b =  $nowyear - 10;

								$s_b = $year_b+543;

								while($year_b <= $year_a){
    								if($nowyear != $year_b){
        								echo "<option value=\"$year_b\">$s_b</option>";
    								}else{
        								echo "<option value=\"$year_b\" selected>$s_b</option>";
    								}// End Of If
    								$year_b += 1;
    								$s_b +=1;
								}// End Of While Loop
							?>
						</select>	<input type="submit" name="submit" value="ค้นหา">
				</form>
			</div>
			
			<div style="clear:both"></div>
			
			<div id="divshow">
				
				<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    				<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        				<td align="center">IDNO</td>
        				<td align="center">ชื่อ</td>
        				<td align="center">ทะเบียน</td>
        				<td align="center">วันที่เริ่ม</td>
        				<td align="center">วันครบกำหนด</td>
        				<td align="center">รูปแบบ</td>
        				<td align="center">วันนัด</td> 
        				<td></td>
    				</tr>
   
<?php
	if( isset($mm) and isset($yy) ){
    	$qry_name=pg_query("select * from carregis.\"CarTaxDue\"
        where EXTRACT(MONTH FROM \"TaxDueDate\")='$mm' AND EXTRACT(YEAR FROM \"TaxDueDate\")='$yy' 
        AND (\"TypeDep\"='101' OR \"TypeDep\"='105' ) AND \"BookIn\"='false' ORDER BY \"TaxDueDate\" ASC ");
        
        $rows = pg_num_rows($qry_name);
		$Count_Show = 0;
        while($res_name=pg_fetch_array($qry_name)){
			$IDCarTax = $res_name["IDCarTax"];
            $IDNO = $res_name["IDNO"];
            //$TaxValue = $res_name["TaxValue"];
            $ApointmentDate = $res_name["ApointmentDate"];
            if(empty($ApointmentDate)) $ApointmentDate = "-"; else $ApointmentDate=$ApointmentDate;
            $TaxDueDate = $res_name["TaxDueDate"];
            $TaxDueDate = date("Y-m-d",strtotime($TaxDueDate));
            $TypeDep = $res_name["TypeDep"];
            if($TypeDep == '105'){ $show_meter = "มิเตอร์"; } else { $show_meter = "มิเตอร์/ภาษี"; }
            
        	$qry_name2=pg_query("select a.\"CarID\" as asset_id,a.\"C_REGIS\",b.\"asset_type\",c.\"full_name\" from \"Carregis_temp\" a
									left join \"Fp\" b on a.\"IDNO\"=b.\"IDNO\"
									left join \"Fa1_FAST\" c on b.\"CusID\"=c.\"CusID\"
								WHERE a.\"IDNO\"='$IDNO' order by \"auto_id\" DESC limit 1 ");
			$num_cartemp=pg_num_rows($qry_name2);
			if($num_cartemp==0){
				//กรณีเป็น Gas 
				$qry_name2=pg_query("SELECT a.\"asset_id\",b.\"car_regis\",a.\"asset_type\",c.\"full_name\" FROM \"Fp\" a
										LEFT JOIN \"FGas\" b ON a.asset_id = b.\"GasID\"
										LEFT JOIN \"Fa1_FAST\" c ON a.\"CusID\" = c.\"CusID\"
									WHERE \"IDNO\"='$IDNO' ");
			}	
			//$qry_name2=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
        	if($res_name2=pg_fetch_array($qry_name2)){
            	$asset_id = $res_name2["asset_id"];
            	$full_name = $res_name2["full_name"];
            	$asset_type = $res_name2["asset_type"];
            	$C_REGIS = $res_name2["C_REGIS"];
            	$car_regis = $res_name2["car_regis"];
				$C_StartDate = $res_name2["C_StartDate"];
            	$C_StartDate = date("Y-m-d",strtotime($C_StartDate));
                if($asset_type == 1){ $show_regis = $C_REGIS; } else { $show_regis = $car_regis; }
        	}
			
			// ตรวจสอบทะเบียนรถ ว่าต้องการแสดงหรือไม่
			$qry_chk_car = pg_query("SELECT '$show_regis' like '%$tb_search%'");
			$chk_car = pg_fetch_result($qry_chk_car,0);
			if($chk_car == 'f')
			{
				continue; // ถ้าไม่ใช่ทะเบียนที่ต้องการแสดง ให้ข้ามไป
			}
			
        	// แยประเภทรถ
			$Str_Get_Char = " SELECT '$show_regis' like 'ท%' or '$show_regis' like 'ม%' ";
			$Result = pg_query($Str_Get_Char);
			$Data = pg_fetch_result($Result,0); // return boolean ถ้าได้ true จะเป็นรถ taxi
			
			if($Cr_Type == "Taxi" && $Data != 't') // ถ้าเลือกให้แสดงรถ Taxi แต่ ทะเบียนรถที่ได้ไม่ใช้รถ Taxi
			{
				continue; // ข้ามรายการนี้ไป ให้วนรอบต่อไปเลย
			}
        	elseif($Cr_Type == "Car" && $Data == 't') // ถ้าเลือกให้แสดงรถบ้าน แต่ ทะเบียนรถที่ได้เป็นรถ Taxi
			{
				continue; // ข้ามรายการนี้ไป ให้วนรอบต่อไปเลย
			}	
			elseif($Cr_Type == "All") // ถ้าให้แสดงทั้งหมด
			{
				// ปล่อยให้ทำงานต่อไป
			}
			
			$in+=1;
			$Count_Show++;
			
        	if($in%2==0){
            	echo "<tr class=\"odd\">";// Start Of Row
        	}else{
            	echo "<tr class=\"even\">";// Start Of Row 
        	}
?>
        <td align="center"> <a href="#" onclick="javascript:popU('frm_car_show_detail.php?id=<?php echo "$IDNO";?>','a0','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')"><u><?php echo "$IDNO"; ?></u></a></td>
        <td align="left"><?php echo "$full_name"; ?></td>
        <td align="left"><?php echo "$show_regis"; ?></td>
        <td align="center"><?php echo "$C_StartDate"; ?></td>
        <td align="center"><?php echo "$TaxDueDate"; ?></td>
        <td align="left"><?php echo "$show_meter"; ?></td>
        <td align="center"><?php echo "$ApointmentDate"; ?></td>
        <td align="center">
		<?php
			// ตรวจสอบค่าว่ามีการจ่ายเงินหรือยัง
		$qry_ref=pg_query("select * from \"FOtherpay\" where \"IDNO\" = '$IDNO' and \"RefAnyID\" = '$IDCarTax' and \"O_RECEIPT\" is not null");
		$num_ref=pg_num_rows($qry_ref);    //0=ยังไม่จ่าย , >0=จ่ายแล้ว
			
		if($num_ref==0){
			//ตรวจสอบว่าเลขที่สัญญาเป็น VIP หรือไม่ถ้าใช่ให้สามารถนัดได้
			$qry_vip=pg_query("select * from \"nw_createVIP\" where \"IDNO\" = '$IDNO'");
			$num_vip = pg_num_rows($qry_vip);  //0=ไม่ใช่ VIP, >0=VIP
			if($num_vip > 0){
				if($ApointmentDate == "-"){ ?>
					<a href="#" onclick="javascript:popU('frm_car_add.php?cid=<?php echo "$IDCarTax";?>','a1','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')"><img src="add.png" border="0" width="16" height="16" align="absmiddle" title="เพิ่มวันนัด"></a>
					<?php }else{ ?>
						<a href="#" onclick="javascript:popU('frm_car_edit.php?cid=<?php echo "$IDCarTax";?>','a2','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')"><img src="edit.png" border="0" width="16" height="16" align="absmiddle" title="แก้ไขวันนัด"></a>
					<?php 
					} 
				}else{
					if($ApointmentDate == "-"){ ?>
						<img src="add2.png" border="0" width="16" height="16" align="absmiddle" title="ต้องชำระเงินก่อน">
						<?php }else{ ?>
							<a href="#" onclick="javascript:popU('frm_car_edit.php?cid=<?php echo "$IDCarTax";?>','a2','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')"><img src="edit.png" border="0" width="16" height="16" align="absmiddle" title="แก้ไขวันนัด"></a>
						<?php 
						} 
				}
		}else{
			if($ApointmentDate == "-"){ ?>
				<a href="#" onclick="javascript:popU('frm_car_add.php?cid=<?php echo "$IDCarTax";?>','a1','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')"><img src="add.png" border="0" width="16" height="16" align="absmiddle" title="เพิ่มวันนัด"></a>
				<?php }else{ ?>
					<a href="#" onclick="javascript:popU('frm_car_edit.php?cid=<?php echo "$IDCarTax";?>','a2','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')"><img src="edit.png" border="0" width="16" height="16" align="absmiddle" title="แก้ไขวันนัด"></a>
				<?php 
			} 
		}
		?>
        </td>
    </tr>
 <?php  
        } // End Of While Loop Here
}

	if($rows > 0)
	{
 	?>
		<tr bgcolor="#ffffff" style="font-size:11px;">
			<td align="left" colspan="2"><b>ทั้งหมด</b> <?php echo $Count_Show; ?> <b>รายการ</b></td>
			<td align="right" colspan="7"><a href="frm_car_show_print.php?mm=<?php echo "$mm"; ?>&yy=<?php echo "$yy";?>&car_type=<?php echo "$Cr_Type";?>&tb_search=<?php echo $tb_search; ?>" target="_blank"><img src="icoPrint.png" border="0" width="17" height="14" title=""> <b>สั่งพิมพ์</b></a></td>
		</tr>
	<?php
	}
	else
	{
	?>
		<tr bgcolor="#ffffff" style="font-size:11px;">
			<td align="center" colspan="8"><b>--ไม่พบข้อมูล--</b></td>
		</tr>
	<?php
	}
	?>
	</table><!-- End Of Table In Here -->
	</div>
	</fieldset>
	</td>	
		
	</tr>
	
</table><!-- End Of Table Out Here  -->	


