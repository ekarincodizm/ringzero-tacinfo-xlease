<?php
ob_start();
session_start();


require_once("../../sys_setup.php");
include("../../../../../config/config.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ข้อมูลประวัติการชำระเงินค่าเข้าร่วม</title>
<script src="../<?php echo $lo_ext_current_temp ?>scripts/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="../<?php echo $lo_ext_current_temp ?>scripts/js/jquery-1.7.1.min.js" type="text/javascript"></script>

<script src="../<?php echo $lo_ext_current_temp ?>scripts/js/jquery-ui-1.8.19.custom.min.js" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="../<?php echo $lo_ext_current_temp ?>scripts/css/ui-lightness/jquery-ui-1.8.1.custom.css" />
<script type="text/javascript">  
function popup(url,name,windowWidth,windowHeight){       
    myleft=(screen.width)?(screen.width-windowWidth)/2:100;    
    mytop=(screen.height)?(screen.height-windowHeight)/2:100;      
    properties = "width="+windowWidth+",height="+windowHeight;   
    properties +=",scrollbars=yes, top="+mytop+",left="+myleft;      
    window.open(url,name,properties);   
}   


</script> 
<?php echo "

<link rel=\"stylesheet\" type=\"text/css\" href=\"../".$lo_ext_current_temp."css/view.css\" media=\"all\">
<script type=\"text/javascript\" src=\"../".$lo_ext_current_temp."scripts/view.js\"></script>
<script type=\"text/javascript\" src=\"../".$lo_ext_current_temp."scripts/calendar.js\"></script>
</head>
<body id=\"main_body\" >
	
	<img id=\"top\" src=\"../".$lo_ext_current_temp."pictures/top.png\" alt=\"\">
	
	<div id=\"form_container\">
		<div id=\"form_logon\">
			</br>
			<font color=white>
				ผู้ทำรายการ: [".$_SESSION["av_iduser"]."] - ".$_SESSION["fullname_user"]." 
			</font>
			</br><br>
		</div>

	 <link rel=\"stylesheet\" type=\"text/css\" href=\"../".$lo_ext_current_temp."scripts/epoch_styles.css\" /> 
	<script type=\"text/javascript\" src=\"../".$lo_ext_current_temp."scripts/epoch_classes.js\"></script>
	"; ?>
   <script type="text/javascript">


function show(){
	
	document.getElementById('saveForm').style.display='';
}
 function update_mm(){
	
	document.getElementById('cash').value='0.00';
	document.getElementById('transfer').value='0.00';
	document.getElementById('cheque').value='0.00';
	document.getElementById('cs_cheque').value='0.00';
	document.getElementById('discount').value='0.00';
}
	

 function cal_amount(){
	
	var cash = Math.round(document.getElementById('cash').value); 
	var transfer = Math.round(document.getElementById('transfer').value); 
	var cheque = Math.round(document.getElementById('cheque').value); 
	var cs_cheque = Math.round(document.getElementById('cs_cheque').value); 
	var update_money = Math.round(document.getElementById('update_money').value); 
	
	var cal_a = (cash + transfer + cheque + cs_cheque + update_money ) 
	
	if(cal_a>=0){
	 document.getElementById('amount').value=cal_a;
	}else {
		alert(' จำนวนเงินรวมต้องมากกว่า 0 !!');
	 document.getElementById('amount').value='0';	
	}
}

function cal_amount_net(){
	
	var cash = Math.round(document.getElementById('cash').value); 
	var transfer = Math.round(document.getElementById('transfer').value); 
	var cheque = Math.round(document.getElementById('cheque').value); 
	var cs_cheque = Math.round(document.getElementById('cs_cheque').value); 
	var update_money = Math.round(document.getElementById('update_money').value); 
	var discount = Math.round(document.getElementById('discount').value); 
	var cal_a = (cash + transfer + cheque + cs_cheque + update_money ) - discount 
	
	if(cal_a>=0){
	 document.getElementById('amount_net').value=cal_a;
	}else {
		alert(' ส่วนลดต้องน้อยกว่าจำนวนเงิน !!');
	 document.getElementById('amount_net').value='0';	
	}
}    </script>  
     
<style type="text/css">
        body center form table tr td div {
	color: #00F;
}
        </style>
        <?php 
		$id =$_REQUEST[id];
			$ck_bin=$_REQUEST[ck_bin];
			$update_datetime=$_REQUEST[update_datetime];
			$create_datetime=$_REQUEST[create_datetime];
		if($ck_bin==1){
			if($update_datetime!=""){
			$query = "SELECT * FROM ta_join_payment_bin WHERE id='$id' and update_datetime = '$update_datetime' ";
			}else{
			$query = "SELECT * FROM ta_join_payment_bin WHERE id='$id' and create_datetime = '$create_datetime' ";	
				
			}
			
		}else {
			$query = "SELECT * FROM ta_join_payment WHERE id='$id' ";
		}
				
				
				$sql_query = pg_query($query);

				while($sql_row = pg_fetch_array($sql_query))
				{	
				$cpro_name = $sql_row['cpro_name'];
					//$_SESSION["cpro_name"] = $cpro_name;
					$IDNO = $sql_row['idno'];
					$car_license = $sql_row['car_license'];
				$ta_join_payment_id=	$sql_row['ta_join_payment_id'];
				$pay_date =	$sql_row['pay_date'];
					$pay_date = date_ch_form_c($pay_date);
					$pay =	$sql_row['pay'];
					$amount =	$sql_row['amount'];
					$amount_net =	$sql_row['amount_net']; 
					$payment_image = $sql_row['payment_image'];
					$note =	$sql_row['note'];
				$discount = $sql_row['amount_discount'];
	/*				
	$cash =$sql_row['amount_cash'];
	$cheque = $sql_row['amount_cheque'];
	$cs_cheque = $sql_row['amount_cs_cheque'];
	$transfer = $sql_row['amount_transfer'];
	$update_money = $sql_row['amount_update_m'];
	*/
	
	$cash_note = $sql_row['cash_note'];
	$cheque_note = $sql_row['cheque_note'];
	$cs_cheque_note = $sql_row['cs_cheque_note'];
	$transfer_note = $sql_row['transfer_note'];
	$update_m_note = $sql_row['update_m_note'];
	$discount_note = $sql_row['discount_note'];
	$note = $note.$cash_note.$cheque_note.$cs_cheque_note.$transfer_not.$update_m_note.$discount_note ;
		$deduct_fin= $sql_row['deduct_fin'];			
					$status_tax_wh =$sql_row['status_tax_wh'];
					$tax_wh_note =$sql_row['tax_wh_note'];
					
					$datetime  =$sql_row['create_datetime'];
					if($datetime!=""){
					list($aa,$bb)=split(" ",$datetime);
						list($yy,$mm,$dd)=split("-",$aa);
		            //$aa  = $dd."/".$mm."/".($yy+543) ;
					$aa  = $dd."/".$mm."/".($yy) ;
					}
					
					$update_datetime =$sql_row['update_datetime'];
					if($update_datetime!=""){
					list($xx,$uu)=split(" ",$update_datetime);
						list($yy,$mm,$dd)=split("-",$xx);
		            //$xx  = $dd."/".$mm."/".($yy+543) ;
					$xx  = $dd."/".$mm."/".($yy) ;
					}
					$users =$sql_row['update_by'];
					$create_by =$sql_row['create_by'];
					$tax_wh_note =$sql_row['tax_wh_note'];
					
					
					
				}
			
			
				//$car_license = $_REQUEST[car_license];
				
				//$car_license = iconv("TIS-620", "UTF-8", $car_license);
				//$car_license	= $_SESSION['car_license'] ;
				/*	$query5 = "SELECT * FROM $dbtb_ta_join_main WHERE car_license='$car_license' ";
				
				$sql_query5 = pg_query($query5);
	
				while($sql_row5 = pg_fetch_array($sql_query5))
				{			
					//$cpro_id = $sql_row5['cpro_id'];
						
					
					$start_pay_date = $sql_row5['start_pay_date'];
					$car_month = $sql_row5['car_month'];
					$start_pay_date = date_ch_form_m($start_pay_date);	
					//$arrears = $sql_row5['arrears'];
					//$pay_type = $sql_row5['pay_type'];
					//$expire_date = $sql_row5['expire_date'];
					//$expire_date = date_ch_form_m($expire_date);	
					//$pay_ar = 	$sql_row5['pay_ar'];
					$start_contract_date =$sql_row5['start_contract_date'];
					
				}
			
				$update_datetime =$_REQUEST[update_datetime];
				
	$query5 = "SELECT * FROM $dbtb_ta_join_main WHERE update_datetime ='$update_datetime ' ";
	
				$sql_query5 = pg_query($query5);
	
				while($sql_row5 = pg_fetch_array($sql_query5)){
					$ta_join_payment_id=	$sql_row['ta_join_payment_id'];
				$pay_date =	$sql_row['pay_date'];
					$pay_date = date_ch_form_c($pay_date);
					$pay =	$sql_row['pay'];
					$amount =	$sql_row['amount'];
					$amount_net =	$sql_row['amount_net']; 
					$payment_image = $sql_row['payment_image'];
					$note =	$sql_row['note'];
					
	$cash =$sql_row['amount_cash'];
	$cheque = $sql_row['amount_cheque'];
	$cs_cheque = $sql_row['amount_cs_cheque'];
	$transfer = $sql_row['amount_transfer'];
	$update_money = $sql_row['amount_update_m'];
	$discount = $sql_row['amount_discount'];
	
	$cash_note = $sql_row['cash_note'];
	$cheque_note = $sql_row['cheque_note'];
	$cs_cheque_note = $sql_row['cs_cheque_note'];
	$transfer_note = $sql_row['transfer_note'];
	$update_m_note = $sql_row['update_m_note'];
	$discount_note = $sql_row['discount_note'];
					
					$status_tax_wh =$sql_row['status_tax_wh'];
					$tax_wh_note =$sql_row['tax_wh_note'];
					
					$datetime  =$sql_row['datetime'];
					list($aa,$bb)=split(" ",$datetime);
						list($yy,$mm,$dd)=split("-",$aa);
		            $aa  = $dd."/".$mm."/".($yy+543) ;
					
					$update_datetime =$sql_row['update_datetime'];
					list($xx,$yy)=split(" ",$update_datetime);
						list($yy,$mm,$dd)=split("-",$xx);
		            $xx  = $dd."/".$mm."/".($yy+543) ;
					$users =$sql_row['users'];
					$tax_wh_note =$sql_row['tax_wh_note'];
					
					
					
				}
					
				
				$period_date = $_REQUEST[period_date];
				$expire_date = $_REQUEST[expire_date];
				$pay_type = $_REQUEST[pay_type];
				$ck_amount =$_REQUEST[ck_amount];
				if($pay_type==100){
					$pay_type=1;}
					else if($pay_type==300){
						$pay_type=0;}
			$car_month =$car_month;
			if($amount==5000 && $ck_amount!=1){
				$pay_type=0;
				
			}else {
			list($mm,$yy)=split("/",$period_date);
				 $yy=$yy-543;
				 
				 	$period_date =  MKTIME(0,0,0,$mm-1,'01',$yy) ;
						$period_date = date("m/Y", $period_date);
						
						list($mm,$yy)=split("/",$period_date);
							 $period_date   =$mm."/".($yy+543) ;
		}
		list($dd,$mm,$yy)=split("/",$start_pay_date);		
			 $yy=$yy-543;
//echo $dd." ".$mm.$yy;
			
//echo $_POST['start_pay_date'];

//$start_ta_join_date =  MKTIME(0,0,0,$mm+$car_month,$dd,$yy) ;
//$start_ta_join_date = date_ch_form_m(date("Y-m-d", $start_ta_join_date)); 
	if($start_contract_date=='0000-00-00'){
					$start_contract_date = "ไม่มีข้อมูล";
				}else{
					$start_contract_date = date_ch_form_c($start_contract_date);
				}

				// $period_date =  MKTIME(0,0,0,$mm+1,'01',$yy) ;
				 //$period_date = date_ch_form_m(date("Y-m-d", $period_date)); 
				*/
			
		?>

<center>


<form action="../processor_ta_join_payment.php" method="post" class="appnitro" enctype="multipart/form-data">
<div class="form_description">
				<h2>ข้อมูลประวัติการชำระเงินค่าเข้าร่วม</h2>
					</div>		
		<table border="1" align="center" cellspacing="0">
		  <tr>
		    <td colspan="3" bgcolor="#66CCFF"><strong><font color="blue">ข้อมูลการชำระเงิน</font></strong></td>
	      </tr>
		  <tr>
		    <td bgcolor="#EEFBFA"><label class="description" for="element_4">
		      <div align="right"><font color="green">เลขที่ใบเสร็จ : </font>
		        </label>
	          </div></td>
		    <td colspan="2"><div align="left">
		      <input name="ta_join_payment_id" type="text" id="ta_join_payment_id" readonly="readonly" value="<?Php print $ta_join_payment_id ?>" size="25" />
		    </div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#EEFBFA"><label class="description" for="element_4">
		      <div align="right"><font color="green">รายละเอียดชุดลูกค้า : </font>
		        </label>
	          </div></td>
		    <td colspan="2"><div align="left">
		      <input name="cpro_name" type="text" id="cpro_name" readonly="readonly" value="<?Php print $cpro_name ?>" size="25" />
		    </div></td>
	      </tr>
		  <tr>
		    <td width="192" bgcolor="#EEFBFA"><label class="description" for="element_4">
		      <div align="right">ทะเบียนรถยนต์ : 
		      </label>
		      </div></td>
		    <td colspan="2"><div align="left">
		      <input name="car_license" type="text" id="car_license" readonly="readonly" value="<?Php print $car_license ?>" onchange="nValue()" size="25"/>
		    </div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#EEFBFA"><label class="description" for="element_4">
		      <div align="right">เลขสัญญา : 
		      </label>
		      </div></td>
		    <td colspan="2"><div align="left">
		      <input name="IDNO" type="text" id="IDNO" readonly="readonly" value="<?Php print $IDNO ?>" size="25"/>
		    </div></td>
	      </tr>
		 	  <tr>
		    <td bgcolor="#EEFBFA"><label class="description" for="element_6">
		      <div align="right">รูปแบบการชำระเงิน : 
		      </label>
		      </div></td>
		    <td colspan="2"><div align="left">
		    <input name="tt" type="text" id="tt" value="<?php 	
$query7 = "SELECT \"PayTypeName\" FROM ta_join_pay_type WHERE \"PayTypeID\" ='".$pay."' ";

		$sql_query7 = pg_query($query7);
			if($sql_row = pg_fetch_array($sql_query7))
				{	
				echo $sql_row["PayTypeName"];
				
				}

$query7 = "SELECT * FROM ta_join_payment WHERE ta_join_payment_id ='".$ta_join_payment_id."' ";
	//echo $query7;
		$sql_query7 = pg_query($query7);
		$Num_Rows7 = pg_num_rows($sql_query7); 
		
		if($Num_Rows7>1){
		echo " - ใบเสร็จแบบเป็นชุด";	
		}
		?>" size="25" readonly="readonly"/>

            </div></td>
	      </tr>
           <td bgcolor="#EEFBFA"><label class="description" for="element_4">
		      <div align="right">วันที่ชำระ : 
		      </label>
		      </div></td>
		    <td colspan="2"><div align="left">
		      <input name="pay_date" type="text" id="pay_date" readonly="readonly" value="<?Php print $pay_date ?>" size="25"/>
		    </div></td>
	      </tr>
          <tr>
                <td bgcolor="#EEFBFA"><label class="description" for="element_6">
                  <div align="right">จำนวนเงิน(บาท) : 
                  </label>
                  </div></td>
                <td colspan="2"><div align="left">
                  <input name="amount" type="text" id="amount" value="<?Php print number_format($amount,2) ?>" size="25" readonly="readonly"/>
                  
                  <?php if($deduct_fin==1){ ?><input name="deduct_fin" disabled="disabled" type="checkbox" id="deduct_fin" <?php if($deduct_fin==1){ echo "checked";} ?>  />
หักเป็นเงินสดจากยอดจัดไฟแนนซ์ <?php } ?>
                </div>
                  <div id="cal_arrears" align="left"></div></td>
          </tr>
          <?php if($discount!=0){ ?>
<tr>
	            <td bgcolor="#EEFBFA"><label class="description" for="element_6">
	              <div align="right">จำนวนเงินรวมหลังหักส่วนลด(บาท) : </div></td>
	            <td colspan="2"><div align="left">
	              <input name="amount_net" type="text" id="amount_net" value="<?php print number_format($amount_net,2) ?>" size="25" readonly="readonly"/>
</div></td>
          </tr>
	<?php } ?>
    <?php if($datetime!=""){ ?>
		  <tr>
		    <td bgcolor="#EEFBFA"><label class="description" for="element_6">
		      <div align="right">วันที่สร้างรายการ : </div></td>
		    <td colspan="2"><div align="left">
		      <input name="amount_net2" type="text" id="amount_net2" value="<?php print $aa." ".$bb ?>" size="25" readonly="readonly"/>
		    </div></td>
	      </tr>
          <?php } ?>
          <?php if($create_by!=""){ 
		  $res_profile=pg_query("select fullname,id_user from \"Vfuser\" where id_user='$create_by'");
   $res_userprofile=pg_fetch_array($res_profile);
   $create_by=  $create_by."-".$res_userprofile["fullname"];
		  ?>
            <tr>
		    <td bgcolor="#EEFBFA"><label class="description" for="element_6">
		      <div align="right">ผู้ที่สร้างรายการ : </div></td>
		    <td colspan="2"><div align="left">
		      <input name="amount_net2" type="text" id="amount_net3" value="<?php print $create_by  ?>" size="25" readonly="readonly"/>
		    </div></td>
	      </tr>
          <?php } ?>
          <?php if($update_datetime!=""){ ?>
		  <tr>
		    <td bgcolor="#EEFBFA"><label class="description" for="element_6">
		      <div align="right">วันที่ปรับปรุงข้อมูล : </div></td>
		    <td colspan="2"><div align="left">
		      <input name="amount_net3" type="text" id="amount_net4" value="<?php print $xx." ".$uu ?>" size="25" readonly="readonly"/>
		    </div></td>
	      </tr>
          <?php } ?>
          <?php if($status_tax_wh!=0){ ?>
		  <tr>
		    <td height="26" bgcolor="#EEFBFA"><label class="description" for="element_6">
	        <div align="right">หักภาษี ณ ที่จ่าย</div></td>
		    <td width="134"><div align="left">
		      <input name="status_tax_wh" disabled="disabled" type="checkbox" id="status_tax_wh" <?php if($status_tax_wh!=0) {echo "checked";} ?>  value="3"  />
		      หักภาษี ณ ที่จ่าย </div></td>
		    <td width="353"><?php if($tax_wh_note!=""){ ?><div align="left">
		      <input name="tax_wh_note" type="text" id="tax_wh_note" value="<?Php print $tax_wh_note ?>" size="20" readonly="readonly"/>
	        เลขอ้างอิง/เลขที่ใบรับรอง</div> <?php } ?></td>
	      </tr>
          <?php } ?>
           <?php if($note!=""){ ?>
		  <tr>
		    <td bgcolor="#EEFBFA"><label class="description" for="element_4">
		      <div align="right">หมายเหตุ :
		        </label>
	          </div></td>
		    <td colspan="2"><div align="left">
		      <textarea name="note" cols="60" rows="2" id="note" readonly="readonly"><?Php print $note ?></textarea>
		      </div></td>
	      </tr>
          <?php } ?>
          <?php if($users!=""){ 
		    $res_profile=pg_query("select fullname,id_user from \"Vfuser\" where id_user='$users'");
   $res_userprofile=pg_fetch_array($res_profile);
   $users=  $users."-".$res_userprofile["fullname"];
		  ?>
		  <tr>
		    <td bgcolor="#EEFBFA"><label class="description" for="element_6">
		      <div align="right">ผู้ที่ปรับปรุงข้อมูลล่าสุด : </div></td>
		    <td colspan="2"><div align="left">
		      <input name="amount_net2" type="text" id="amount_net3" value="<?php print $users  ?>" size="25" readonly="readonly"/>
		    </div></td>
	      </tr>
          <?php }
		  				if($sql_row[create_by]==""){ //ใบเสร็จแบบใหม่  แบบเก่า create_by จะไม่มี
						
						?>
                         <tr>
		    <td bgcolor="#EEFBFA"><label class="description" for="element_6">
		      <div align="right">ผู้ที่ยกเลิกรายการ </div></td>
		    <td colspan="2"><div align="left">
		      <input name="amount_net2" type="text" id="amount_net3" value="<?php print $users  ?>" size="25" readonly="readonly"/>
		    </div></td>
	      </tr>
          <?php }else { ?>
					
				$res_profile=pg_query("select postuser,approveuser,c_date from \"CancelReceipt\" where admin_approve='true' and ref_receipt = '$ta_join_payment_id' ");
			$Num_Rows88 = pg_num_rows($res_profile); 	
			if($Num_Rows88>0){
   $res_userprofile=pg_fetch_array($res_profile);
   
   $postuser=  $res_userprofile["postuser"];
   $approveuser = $res_userprofile["approveuser"];
   $c_date = $res_userprofile["c_date"];

$res_profile=pg_query("select fullname,id_user from \"Vfuser\" where id_user='$approveuser'");
   $res_userprofile=pg_fetch_array($res_profile);
   $approveuser=  $approveuser."-".$res_userprofile["fullname"];
   
   $res_profile=pg_query("select fullname,id_user from \"Vfuser\" where id_user='$postuser'");
   $res_userprofile=pg_fetch_array($res_profile);
   $postuser=  $postuser."-".$res_userprofile["fullname"];
				}
		   ?>
           <tr>
		    <td bgcolor="#EEFBFA"><label class="description" for="element_6">
		      <div align="right">ผู้ที่ยกเลิกรายการ </div></td>
		    <td colspan="2"><div align="left">
		      <input name="amount_net2" type="text" id="amount_net3" value="<?php print $postuser  ?>" size="25" readonly="readonly"/>
		    </div></td>
	      </tr>
          <tr>
		    <td bgcolor="#EEFBFA"><label class="description" for="element_6">
		      <div align="right">ผู้ที่อนุมัติยกเลิกรายการ </div></td>
		    <td colspan="2"><div align="left">
		      <input name="amount_net2" type="text" id="amount_net3" value="<?php print $approveuser  ?>" size="25" readonly="readonly"/>
		    </div></td>
	      </tr>
          <tr>
		    <td bgcolor="#EEFBFA"><label class="description" for="element_6">
		      <div align="right">วันที่ยกเลิก </div></td>
		    <td colspan="2"><div align="left">
		      <input name="amount_net2" type="text" id="amount_net3" value="<?php print date_ch_form_c($c_date)  ?>" size="25" readonly="readonly"/>
		    </div></td>
	      </tr>
          <?php } ?>
		  </table>
          	          <?Php 
		 	$query5 = "SELECT update_datetime,create_datetime FROM ta_join_payment_bin WHERE id='$id' "; //ORDER BY update_datetime ASC
				//echo $query5;
				$sql_query5 = pg_query($query5);
				$num_rows = pg_num_rows($sql_query5);
				  if($num_rows!='0'  && $_REQUEST[ck_bin]!=1){ ?>
<p><font color="red"><b>ประวัติการปรับปรุงข้อมูล</b></font></p>
<table width="264" border="1">
           		          <?Php 
		 /*	$query5 = "SELECT update_datetime,create_datetime FROM ta_join_payment_bin WHERE ta_join_payment_id='$ta_join_payment_id' "; //ORDER BY update_datetime ASC
				//echo $query5;
				$sql_query5 = pg_query($query5);
				$num_rows = pg_num_rows($sql_query5);*/
				if($num_rows=='0' ){ ?>
                 <tr>
		    <td width="142"><div align="center">
            <?php
					echo "ไม่มีประวัติการปรับปรุงข้อมูล";
					
					?>
                    </div></td></tr>
                    <?php
				}else{
					$i=0;
		while($sql_row5 = pg_fetch_array($sql_query5))
				{	
				$update_datetime = $sql_row5['update_datetime'];
				$create_datetime = $sql_row5['create_datetime'];
	$j=1+$i ;
	//$k=1+$j ;
   if($i==0){
	   $aa = 'ข้อมูลต้นฉบับ';
   }else {
	   $aa = "แก้ไขครั้งที่ $i";
   }

	 ?>
		  <tr>
		    <td><div align="left"><label class="description" for="element_4"><font color="#0000FF"><?Php print $aa ?> : </font></label></div></td>
		    <td width="106"><div align="left">
		      <a href="ta_join_payment_detail.php?update_datetime=<?Php print $update_datetime ?>&id=<?Php print $id ?>&ck_bin=1&create_datetime=<?Php print $create_datetime ?>" target="_blank">ดูข้อมูล</a></div></td></tr>
		    <?Php
	$i++;
} 
				}
		   
		  ?>
</table> <?php } ?>
 <br />
  <input id="saveForm" class="button_text" type="button" value="ปิด" onclick="window.close()" style='width:100px; height:50px'/>

        </center>

<?Php
echo "

			</ul>
	  </form>
	<div id=\"footer\"></div>
</div>
	<img id=\"bottom\" src=\"../".$lo_ext_current_temp."pictures/bottom.png\" alt=\"\">
</body>
</html>

";
ob_end_flush();
?>