<?php

require_once("../../sys_setup.php");
include("../../../../../config/config.php");
$app = pg_escape_string($_REQUEST[app]);
$sp = pg_escape_string($_REQUEST[sp]);
$new_sp = pg_escape_string($_REQUEST[new_sp]);
$config =1;// config =1 คือ Update ทุกครั้ง ที่เปิดหน้านี้

?>

   <script type="text/javascript">
$("#span1").hide();
$("#xx1").hide();
$("#xx2").hide();
function show(){
	
	document.getElementById('saveForm').style.display='';
}
 
$(document).ready(
     function() {
		 
		  $("#cpro_name").autocomplete({
        source: "cus_data.php",
        minLength:1,
		  close: function(event, ui) { change_cus();}
		  
	

    });

		 
		 /* $("#idno").autocomplete({
        source: "cus_data.php",
        minLength:1,
		  close: function(event, ui) { change_cus();}

    });*/
var d = new Date();
var s = d.getDate();
var m = d.getMonth()+1;
var y = d.getFullYear();
    

	  $("#start_pay_date,#cancel_datetime").datepicker({ dateFormat: 'dd/mm/yy',

     defaultDate: s+'/'+m+'/'+y,
     dayNames: ['อาทิตย์','จันทร์','อังคาร',
                        'พุธ','พฤหัสบดี','ศุกร์','เสาร์'],
     dayNamesMin: ['อา.','จ.','อ.','พ.','พฤ.','ศ.','ส.'],
     monthNames: ['มกราคม','กุมภาพันธ์','มีนาคม',
                        'เมษายน','พฤษภาคม','มิถุนายน',
                        'กรกฎาคม','สิงหาคม','กันยายน',
                        'ตุลาคม','พฤศจิกายน','ธันวาคม'],
     monthNamesShort: ['ม.ค.','ก.พ.','มี.ค.','เม.ย.',
                         'พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.',
                         'พ.ย.','ธ.ค.']
    });

     }
);
      function sv(){
 $.post("cal/number_format.php", { number_format: document.getElementById('service_value').value
  },
  function(data){
    document.getElementById('service_value').value=data;
  });
 }
  function ch_idno(obj,carNo){

	
	 $.post("change_idno.php", { idno: obj.value,car_no: carNo
	 
  },
  function(data){
	  
		var y = data.split('#');
	
		$("#start_contract_date").val(y[0]);
		$("#car_month").val(y[1]);
		$("#car_brand").val(y[2]);
		$("#id_body").val(y[3]);
		//$("#f_type").val('0'); // ดึงชื่อลูกค้าจาก Vjoin
		
		

  });

}
function check_new_idno(){

	if(document.getElementById("cb1").checked){
		$("#span1").show();
		
		
	}else{
		
		$("#span1").hide();
	}
	
	
}
 function change_cus(){
	var x = $("#cpro_name").val().split('#');
	
	 $.post("change_cus.php", { cus_id: x[1]
  },
  function(data){
	  
		var y = data.split('#');
	
		$("#type_card").val(y[0]);
		$("#id_card").val(y[1]);
		$("#cus_id").val(x[1]);
		$("#cpro_name").val(x[0]);
		$("#f_type").val('0'); // ดึงชื่อลูกค้าจาก Vjoin

  });

}
 function add_addr(){
	 if(document.getElementById("cancel0").checked){ 
$("#xx1").hide();
$("#xx2").hide();
	 }else{
		$("#xx1").show();
$("#xx2").show(); 
		 
	 }
	 $("#join_addr").val('--------------------');
 }
 
function ck()
{
	var action = '<?php echo pg_escape_string($_REQUEST[action]); ?>'; // รูปแบบการทำรายการ
	
	if(document.getElementById("cpro_name").value==''){alert("กรุณาระบุชื่อลูกค้า");return false;}
	if(document.getElementById("idno").value==''){alert("กรุณาระบุเลขที่สัญญา");document.getElementById("idno").focus();return false;}	
	if(document.getElementById("car_license").value==''){alert("กรุณาระบุทะเบียนรถยนต์");return false;}	
	if(document.getElementById("join_addr").value==''){alert("กรุณาระบุที่อยู่");return false;}
	if(document.getElementById("join_addr").value.length<20){alert("กรุณาระบุที่อยู่ให้ถูกต้อง");return false;}
	if(document.getElementById("id_card").value==''){alert("กรุณาระบุเลขที่บัตร");return false;}
	if(document.getElementById("start_pay_date").value==''){alert("กรุณาระบุเดือนที่เริ่มเก็บค่าเข้าร่วม");return false;}
	if(document.getElementById("start_pay_date").value.length!=10){alert("กรุณาระบุเดือนที่เริ่มเก็บค่าเข้าร่วมให้ถูกต้อง");return false;}
	if(document.getElementById("note").value==''){alert("กรุณาระบุหมายเหตุ");return false;}
	
	var showDetail = '';
	showDetail = showDetail+'ชื่อลูกค้า : '+document.getElementById("cpro_name").value;
	showDetail = showDetail+'\r\n'+'ทะเบียนรถยนต์ : '+document.getElementById("car_license").value;
	showDetail = showDetail+'\r\n'+'เลขสัญญา : '+document.getElementById("idno").value;
	showDetail = showDetail+'\r\n'+'ข้อมูลบัตร : '+document.getElementById("type_card").value;
	showDetail = showDetail+'\r\n'+'เลขที่บัตร : '+document.getElementById("id_card").value;
	showDetail = showDetail+'\r\n'+'ที่อยู่ : '+document.getElementById("join_addr").value;
	showDetail = showDetail+'\r\n'+'รายละเอียดรถยนต์ : '+document.getElementById("car_brand").value;
	showDetail = showDetail+'\r\n'+'เลขที่ตัวถัง : '+document.getElementById("id_body").value;
	showDetail = showDetail+'\r\n'+'วันที่เริ่มชำระเงิน(เช่าซื้อรถ) : '+document.getElementById("start_contract_date").value;
	showDetail = showDetail+'\r\n'+'จำนวนงวด : '+document.getElementById("car_month").value;
	showDetail = showDetail+'\r\n'+'เดือนที่เริ่มเก็บค่าเข้าร่วม : '+document.getElementById("start_pay_date").value;

	if(!confirm(showDetail)){return false;}
}

function ck2()
{
	if(document.getElementById("cpro_name").value==''){alert("กรุณาระบุชื่อลูกค้า");return false;}
	if(document.getElementById("idno").value==''){alert("กรุณาระบุเลขที่สัญญา");document.getElementById("idno").focus();return false;}	
	if(document.getElementById("car_license").value==''){alert("กรุณาระบุทะเบียนรถยนต์");return false;}	
	if(document.getElementById("id_card").value==''){alert("กรุณาระบุเลขที่บัตร");return false;}
	if(document.getElementById("join_addr").value==''){alert("กรุณาระบุที่อยู่");return false;}
	if(document.getElementById("join_addr").value.length<20){alert("กรุณาระบุที่อยู่ให้ถูกต้อง");return false;}
	if(document.getElementById("start_pay_date").value==''){alert("กรุณาระบุเดือนที่เริ่มเก็บค่าเข้าร่วม");return false;}
	if(document.getElementById("start_pay_date").value.length!=10){alert("กรุณาระบุเดือนที่เริ่มเก็บค่าเข้าร่วมให้ถูกต้อง");return false;}
	
	if(!document.getElementById("cancel0").checked) // ถ้าไม่ได้เลือก ยังเป็นลูกค้า
	{
		if(!document.getElementById("cancel0").checked && document.getElementById("note").value=='' ){alert("กรุณาระบุเหตุผลในการยกเลิก");return false;}
		if(!document.getElementById("cancel0").checked && document.getElementById("cancel_datetime").value=='' ){alert("กรุณาระบุวันที่เปลี่ยนสถานะ");return false;}
		if(document.getElementById("cb1").checked && document.getElementById("idno_new").value=='' ){alert("กรุณาระบุเลขที่สัญญาเช่าซื้อล่าสุดที่จะนำมาปิด"); document.getElementById("idno_new").focus(); return false;}
	}
	
	if(document.getElementById("note").value==''){alert("กรุณาระบุเหตุผลในการแก้ไข");return false;}
	
	var showDetail = '';
	var showStatus = '';
	
	if(document.getElementById("cancel0").checked)
	{
		showStatus = 'ยังเป็นลูกค้า';
	}
	else if(document.getElementById("cancel1").checked)
	{
		showStatus = 'ถอดป้าย/เปลี่ยนสี';
	}
	else if(document.getElementById("cancel2").checked)
	{
		showStatus = 'รถยึด';
	}
	else if(document.getElementById("cancel3").checked)
	{
		showStatus = 'ขายคืน';
	}
	else if(document.getElementById("cancel4").checked)
	{
		showStatus = 'โอนสิทธิ์';
	}
	
	showDetail = showDetail+'ชื่อลูกค้า : '+document.getElementById("cpro_name").value;
	showDetail = showDetail+'\r\n'+'ทะเบียนรถยนต์ : '+document.getElementById("car_license").value;
	showDetail = showDetail+'\r\n'+'เลขสัญญา : '+document.getElementById("idno").value;
	showDetail = showDetail+'\r\n'+'ข้อมูลบัตร : '+document.getElementById("type_card").value;
	showDetail = showDetail+'\r\n'+'เลขที่บัตร : '+document.getElementById("id_card").value;
	showDetail = showDetail+'\r\n'+'ที่อยู่ : '+document.getElementById("join_addr").value;
	showDetail = showDetail+'\r\n'+'รายละเอียดรถยนต์ : '+document.getElementById("car_brand").value;
	showDetail = showDetail+'\r\n'+'เลขที่ตัวถัง : '+document.getElementById("id_body").value;
	showDetail = showDetail+'\r\n'+'วันที่เริ่มชำระเงิน(เช่าซื้อรถ) : '+document.getElementById("start_contract_date").value;
	showDetail = showDetail+'\r\n'+'จำนวนงวด : '+document.getElementById("car_month").value;
	showDetail = showDetail+'\r\n'+'เดือนที่เริ่มเก็บค่าเข้าร่วม : '+document.getElementById("start_pay_date").value;
	showDetail = showDetail+'\r\n'+'สถานะ  : '+showStatus;
	showDetail = showDetail+'\r\n'+'เลขที่สัญญาเช่าซื้อล่าสุดที่จะนำมาปิด : '+document.getElementById("idno_new").value;
	showDetail = showDetail+'\r\n'+'วันที่เปลี่ยนสถานะ : '+document.getElementById("cancel_datetime").value;

	if(!confirm(showDetail)){return false;}
}

</script>


        
     
        <style type="text/css">
        body center form table tr td div {
	color: #00F;
}
        </style>
<?php
		$car_no = pg_escape_string($_REQUEST[car_no]);
		$P_ACCLOSE = pg_escape_string($_REQUEST[c]);
	$f_type = 0; // ดึงชื่อลูกค้าจาก Vjoin
		$idno2 =pg_escape_string($_REQUEST[idno]); // เลขที่สัญญาปัจจุบัน (ตามช่องค้นหา)
			 $action = pg_escape_string($_REQUEST[action]);
			 if($idno2!='undefined'){
	
			
				 
				 	if($action!='add'){
						$id =pg_escape_string($_REQUEST[id]);
			$query4 = "SELECT m.start_pay_date,m.cancel,m.note,m.address,m.id,m.cancel_datetime,m.approve_status,m.staff_check,m.car_license,m.car_license_seq,m.idno,m.cpro_name,m.cusid FROM \"VJoinMain\" m WHERE m.id='$id' 
			and m.deleted ='0' order by id desc limit 1 ";	
	
			$sql_query4 = pg_query($query4);
			if($sql_row4 = pg_fetch_array($sql_query4))
				{
					//$cpro_name = $sql_row4['cpro_name'];
					$app_st = $sql_row4['approve_status'];
					$cusid = $sql_row4['cusid'];
					
				
						$query66 = "SELECT \"N_CARD\",\"N_IDCARD\",\"N_CARDREF\" FROM \"Fn\" WHERE \"CusID\"='$cusid' ";	
	
			$sql_query66 = pg_query($query66);
			if($sql_row66 = pg_fetch_array($sql_query66))
				{
					$type_card = trim($sql_row66['N_CARD']);
					$id_card = trim($sql_row66['N_IDCARD']);
	
					if($id_card!=""){
						$type_card = "บัตรประชาชน";
						
					}else{
						$type_card = trim($sql_row66['N_CARD']);
						$id_card = trim( $sql_row66['N_CARDREF']);
						
					}
					
					
				}
			
					
					$id2= $sql_row4['id'];
					
					
					$staff_check  = $sql_row4['staff_check'];
					$car_license_seq = $sql_row4['car_license_seq'];
					$start_pay_date =date_ch_form_c($sql_row4['start_pay_date']); 
					$start_pay_date2 = $sql_row4['start_pay_date'];
					$cancel=$sql_row4['cancel'];
					$note = $sql_row4['note'];
					$join_addr = $sql_row4['address'];
					//$cus_id = $sql_row4['cusid'];//เอาไปหา id_card ด้วย
					//if($cancel!=0 || $P_ACCLOSE=='t'){
		
		$car_license = trim($sql_row4["car_license"]);
		$idno = trim($sql_row4["idno"]);
		$cpro_name = trim($sql_row4["cpro_name"]);
		
					//	}
					
					$cancel_datetime = $sql_row4['cancel_datetime'];
					if($cancel_datetime!=''){
							list($aa,$bb) = split(" ",$cancel_datetime);
							$cancel_datetime = date_ch_form_c($aa);
					}
					}
				}else{//เช็คว่าเคยคีย์แล้วหรือไม่
				

					$query4 = "
								SELECT
									\"carid\"
								FROM
									\"VJoinMain\"
								WHERE
									\"carid\" = '$car_no' AND
									\"car_license_seq\" = '0' AND
									\"deleted\" = '0'
							";
					$sql_query4 = pg_query($query4);
					$numrows2 = pg_num_rows($sql_query4);
					
			if($numrows2>0){
				echo "<font color=red><h2>รหัสรถยนต์ $car_no ทะเบียน $car_license มีอยู่ในระบบแล้ว!!</h2></font>";
				
			}
					//ถ้า เป็น add ให้ไปดึงข้อมูลจาก main มาก่อน
							$query4 = "SELECT cancel,car_license,idno,cpro_name,id FROM \"VJoinMain\" WHERE carid='$car_no' and \"idno\" = '$idno2' and deleted = '0' ";	
					//echo $query4;
					$sql_query4 = pg_query($query4);
						if($sql_row4 = pg_fetch_array($sql_query4))
				{
							$cancel=$sql_row4['cancel'];
							$car_license = trim($sql_row4["car_license"]);
							$idno = trim($sql_row4["idno"]);
							$cpro_name = trim($sql_row4["cpro_name"]);
						    $f_type = 1; // ดึงชื่อลูกค้าจาก Main
							$id2= $sql_row4['id'];
							
				}
					
				}
				
				
				
			
				//----------------------
				if($cancel=='0' || $new_sp=='1'){//ถ้ายังไม่ยกเลิกสัญญาเข้าร่วมให้เอาอันล่าสุดมา
				$sql_query5=pg_query("select * from \"VJoin\" v WHERE v.\"asset_id\" = '$car_no' order by v.\"P_STDATE\" desc limit 1 ");
				//echo $cancel."1";
				//echo "select * from \"VJoin\" v WHERE v.\"asset_id\" = '$car_no' order by v.\"P_STDATE\" desc limit 1 ";
				}else{//ถ้ายกเลิกสัญญา ให้เอาข้อมูลตาม idno นั้นๆ
					
					$sql_query5=pg_query("select * from \"VJoin\" v WHERE v.\"asset_id\" = '$car_no' and \"IDNO\" = '$idno2'  order by v.\"P_STDATE\" desc limit 1 ");
					//echo "select * from \"VJoin\" v WHERE v.\"asset_id\" = '$car_no' and \"IDNO\" = '$idno2'  order by v.\"P_STDATE\" desc limit 1 ";
				}
//
				$numrows = pg_num_rows($sql_query5);
			

				if($sql_row5 = pg_fetch_array($sql_query5))
				{	
				
   					
					 
    				$P_ACCLOSE = $sql_row5["P_ACCLOSE"];
					
					if($cancel=='0' && $P_ACCLOSE=='t'){//ถ้าค่าเข้าร่วมเปิด xlease ปิด ให้ดึงล่าสุดมา ยกเว้นชื่อ
						$cus_id=$cusid;
   					$car_license=$sql_row5["C_REGIS"];
					$idno=$sql_row5["IDNO"];
					//echo $idno;
					//$cpro_name = $sql_row5['full_name'];
					$f_type = 1; // ดึงชื่อลูกค้าจาก Main
				
					}
					else if($cancel=='0' && $P_ACCLOSE=='f'){//ถ้าค่าเข้าร่วมเปิด xlease เปิด ให้ดึงล่าสุดมา
						 $cus_id=$sql_row5["CusID"];
   					$car_license=$sql_row5["C_REGIS"];
					$idno=$sql_row5["IDNO"];
					$cpro_name = $sql_row5['full_name'];
					
					$f_type = 0; // ดึงชื่อลูกค้าจาก Vjoin
					//$type_card = $sql_row5['N_CARD'];
					$id_card =  trim($sql_row5['N_IDCARD']);
					
					if($id_card!=""){
						$type_card = "บัตรประชาชน";
						
					}else{
						$type_card = trim($sql_row5['N_CARD']);
						$id_card = trim($sql_row5['N_CARDREF']);
						
					}
					
					}else if ($cancel==""){
						
						$car_license=$sql_row5["C_REGIS"];
						$idno=$sql_row5["IDNO"];
						$f_type = 0; 
						
					}
					//else ดึง main มา
					if($new_sp=='1')$cpro_name="";
					$start_contract_date  = $sql_row5['P_FDATE'];
					$car_month = $sql_row5['P_TOTAL'];
			
			
				
					
					$car_brand = $sql_row5['C_CARNAME'];
					$id_body = $sql_row5['C_CARNUM'];
					
					
							//list($hh,$mm,$ss) = split(":",$bb);
							//$cancel_datetime_h = $hh;
							//$cancel_datetime_m = $mm;
			     }
				
				 
				 if($start_contract_date==''){
					$start_contract_date = "ไม่มีข้อมูล";
				}else{
$start_contract_date = date_ch_form_c($start_contract_date);
					}
				
$ck_car = strstr($car_license,'/');
if($ck_car!=''){
	$action = 'view';
}
			?>
<form name="my" method="post" action="update_check.php">	
	<input hidden name="appv" value="อนุมัติ" type="submit"/>
	<input hidden name="unappv" value="ไม่อนุมัติ" type="submit"/>
	<input type="hidden" name="id" id="id" value="<?Php echo $id2 ?>">
</form>
<form action="ta_join_main_api.php" method="post" name="form1" class="appnitro" enctype="multipart/form-data" onSubmit="JavaScript:return  <?php if($action=='add') {?>ck<?php }else { ?>ck2<?php } ?>();">
<div class="form_description"></div>		<center>
		<table width="90%" border="1" align="center" cellspacing="0">
		  <tr>
		    <td colspan="2" bgcolor="#66CCFF"><strong><font color="blue">ข้อมูลเข้าร่วม</font></strong></td>
	      </tr>
		  <tr>
		    <td bgcolor="#F4F4F4"><label class="description" for="element_4">
            <div align="right"><font color="green">ชื่อลูกค้า : </font></div></td>
		    <td ><div align="left">
		      <input name="cpro_name" type="text" id="cpro_name" value="<?Php print $cpro_name ?>" size="30" <?php if($action=='view') {?> readonly="readonly"  <?php } ?>/>
	        <?php if($action!='view') {?><font color="red">* </font><?php } ?>
        <?php if($staff_check=='1'){ ?>
             <img src="../images/staff_check.png" width="25" title="ตรวจสอบแล้ว" height="25" /> <?php 
					}if($app_st=='4'){ ?>  <img src="../images/non_app.png" width="25" height="25" title="ยังไม่ได้รับการตรวจสอบ" /><?php } ?>  
                   <?php if($app!='1') {?> <button name="เพิ่ม" type="button" onClick="window.open('../../../../manageCustomer/frm_Index.php')" title="เพิ่มลูกค้าใหม่ เสร็จแล้วโทรแจ้งผู้อนุมัติ" ><img src="../images/add.png" width="10" height="10" alt="เพิ่มลูกค้าใหม่" />เพิ่ม</button><?php } ?>  </div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#F4F4F4"><div align="right">ทะเบียนรถยนต์ : </div></td>
		    <td><div align="left">
		      <input name="car_license" type="text" id="car_license" readonly="readonly" value="<?Php print $car_license  ?>" size="15"  maxlength="20" /><?php if($cancel!='0' && $action!='add'){ ?>
             <img src="../images/cancel.jpg" width="25" height="25" /> ยกเลิกแล้ว <?php 
					}?>	</div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#F4F4F4"><div align="right">เลขสัญญา : </div></td>
		    <td><div align="left"><?php if($new_sp=='1') {
			//ค้นหา P_stdate ของเลขที่สัญญานั้นๆ
			$qry = pg_query("select \"P_STDATE\" from \"VJoin\" WHERE \"asset_id\" = '$car_no' and \"IDNO\"='$idno2' ORDER BY \"P_STDATE\" desc  ");
while( $res = pg_fetch_array($qry) ){
    $f_P_STDATE = $res['P_STDATE'];

}
?>   <select name="idno" id="idno" onchange="ch_idno(this,'<?Php print $car_no ?>')" >
<?php
//แสดง Combo ให้เลือก >=  สัญญาเก่า
$qry = pg_query("select \"IDNO\" from \"VJoin\" WHERE \"asset_id\" = '$car_no' and \"P_STDATE\" >= '$f_P_STDATE' ORDER BY \"P_STDATE\" desc  ");
while( $res = pg_fetch_array($qry) ){
    $q_ref1 = $res['IDNO'];
?>
    <option value="<?php echo $q_ref1; ?>" ><?php echo $q_ref1; ?></option>
<?php
}
?>
</select>
		      <?php }else {if($sp=='') {?><input name="idno" type="text" id="idno" value="<?Php print $idno2 ?>" size="20" <?php if($action=='view') {?> readonly="readonly" <?php } ?> /> <font color="#FF0000">( เลขที่สัญญาล่าสุดของรถคันนี้ : <?php echo $idno; ?> )</font><?php }else { ?>
              <select name="idno" id="idno" onchange="ch_idno(this,'<?Php print $car_no ?>')" >
    <option value="" >เลือก</option>
<?php

$qry = pg_query("select \"IDNO\" from \"VJoin\" WHERE \"asset_id\" = '$car_no' ORDER BY \"P_STDATE\" desc  ");
while( $res = pg_fetch_array($qry) ){
    $q_ref1 = $res['IDNO'];
?>
    <option value="<?php echo $q_ref1; ?>" ><?php echo $q_ref1; ?></option>
<?php
}
?>
</select><?php } } ?>
		    </div></td>
	      </tr>
          
		  <tr>
		    <td bgcolor="#F4F4F4"><div align="right">ข้อมูลบัตร : </div></td>
		    <td><div align="left">
		      <input name="type_card" type="text" id="type_card" value="<?Php print $type_card ?>" size="25"  readonly="readonly"  />
		    </div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#F4F4F4"><div align="right">เลขที่บัตร : </div></td>
		    <td><div align="left">
		      <input name="id_card" type="text" id="id_card" value="<?Php print $id_card ?>" size="25"  readonly="readonly"  />
		    </div></td>
	      </tr>
          <tr>
		    <td bgcolor="#F4F4F4"><div align="right">ที่อยู่ : </div></td>
		    <td><div align="left">
            <textarea name="join_addr" cols="60" rows="2"  id="join_addr" <?php if($action=='view') {?> readonly="readonly" <?php } ?>><?Php print $join_addr ?></textarea>
	        <?php if($action!='view') {?><font color="red">* </font><?php } ?></div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#F4F4F4"><div align="right">รายละเอียดรถยนต์ : </div></td>
		    <td><div align="left">
		      <input name="car_brand" type="text" id="car_brand" value="<?Php print $car_brand ?>" size="25"  readonly="readonly"  />
		    </div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#F4F4F4"><div align="right">เลขที่ตัวถัง : </div></td>
		    <td><div align="left">
		      <input name="id_body" type="text" id="id_body" value="<?Php print $id_body ?>" size="25"  readonly="readonly"  />
		    </div></td>
	      </tr>
          
		  <tr>
		    <td bgcolor="#F4F4F4"><div align="right">วันที่เริ่มชำระเงิน(เช่าซื้อรถ) : </div></td>
		    <td><div align="left">
		      <input name="start_contract_date" type="text"  id="start_contract_date" value="<?php print $start_contract_date ;?>" size="10"  maxlength="10" readonly="readonly" />
		    </div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#F4F4F4"><div align="right">จำนวนงวด : </div></td>
		    <td><div align="left">
		      <input name="car_month" type="text" id="car_month" value="<?Php print $car_month ?>" size="6"  maxlength="3" readonly="readonly"   />
		      งวด</div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#F4F4F4"><div align="right">เดือนที่เริ่มเก็บค่าเข้าร่วม : </div></td>
		    <td><div align="left">
		      <input name="start_pay_date" type="text" id="start_pay_date" value="<?Php print $start_pay_date ?>"  size="10" maxlength="10"  <?php if($action=='view') {?> disabled="disabled"  <?php } ?> />
	        <?php if($action!='view') {?><font color="red">* </font>(ค.ศ.)<?php } ?></div></td>
	      </tr>
          <?Php if($action!='add'){ ?>
           <tr>
		    <td bgcolor="#F4F4F4"><div align="right">สถานะ : </div></td>
		    <td><div align="left">
		     <input name="cancel" type="radio" id="cancel0" value="0" onclick="add_addr()" <?Php if($cancel=='0'){ echo "checked" ;} ?> <?php if($action=='view' || $cancel!='0') {?> disabled="disabled"  <?php } ?> />
		      ยังเป็นลูกค้า
		      <input type="radio" name="cancel" id="cancel1" value="1" onclick="add_addr()" <?Php if($cancel=='1'){ echo "checked" ;} ?> <?php if($action=='view' || $cancel!='0') {?> disabled="disabled"  <?php } ?>/>
		      ถอดป้าย/เปลี่ยนสี
		      <input type="radio" name="cancel" id="cancel2" value="2" onclick="add_addr()" <?Php if($cancel=='2'){ echo "checked" ;} ?> <?php if($action=='view' || $cancel!='0') {?> disabled="disabled"  <?php } ?>/>
		      รถยึด
		      <input type="radio" name="cancel" id="cancel3" value="3" onclick="add_addr()" <?Php if($cancel=='3'){ echo "checked" ;} ?> <?php if($action=='view' || $cancel!='0') {?> disabled="disabled"  <?php } ?>/>
		      ขายคืน 
               <input type="radio" name="cancel" id="cancel4" value="4" onclick="add_addr()" <?Php if($cancel=='4'){ echo "checked" ;} ?> <?php if($action=='view' || $cancel!='0') {?> disabled="disabled"  <?php } ?>/>
		      โอนสิทธิ์ 
		  </div></td>
	      </tr>
           <?php }if($action=='edit' || ($action=='view' && $cancel!='0') ) {?>
           <tr id="xx1">
             <td bgcolor="#F4F4F4"><div align="right">เปลี่ยนสถานะแล้วเปิดสัญญาเข้าร่วมใหม่ : </div></td>
             <td><div align="left"><input id="cb1" name="cb1" type="checkbox" onclick="check_new_idno()" value="1" />  <span id="span1">เลขที่สัญญาเช่าซื้อล่าสุดที่จะนำมาปิด : <select name="idno_new" id="idno_new" onchange="ch_idno(this,'pleaseSearch')" >
    <option value="" >เลือก</option>
<?php

$qry = pg_query("SELECT \"IDNO\" FROM \"VJoin\" WHERE \"asset_id\" = '$car_no' OR \"C_REGIS\" = '$car_license' ORDER BY \"P_STDATE\" DESC");
while( $res = pg_fetch_array($qry) ){
    $q_ref1 = $res['IDNO'];
?>
    <option value="<?php echo $q_ref1; ?>" ><?php echo $q_ref1; ?></option>
<?php
}
?>
</select></span>
               
               </div></td>
           </tr>
           <tr id="xx2">
             <td bgcolor="#F4F4F4"><div align="right">วันที่เปลี่ยนสถานะ : </div></td>
             <td><div align="left">
               <input name="cancel_datetime" type="text" id="cancel_datetime" value="<?Php print $cancel_datetime ?>"  size="10" maxlength="10"  <?php if($action=='view') {?> disabled="disabled"  <?php } ?> />
               </div></td>
           </tr><?php } ?>
           <tr>
		    <td bgcolor="#F4F4F4"><div align="right">หมายเหตุ : </div></td>
		    <td><div align="left">
            <textarea name="note" cols="60" rows="2"  id="note" <?php if($action=='view') {?> readonly="readonly" <?php } ?>><?Php print $note ?></textarea>
	        <?php if($action!='view') {?><font color="red">* </font><?php } ?></div></td>
	      </tr>
		  </table>
	

<p>

<?php if($action=='view'){ 
	 
include("join_list_payment.php");
 }  ?>
	
<br /><br />
<input type="hidden" id="cus_id" name="cus_id" value="<?php echo $cus_id ?>" />
<input type="hidden" id="car_id" name="car_id" value="<?php echo $car_no ?>" />
<input type="hidden" id="f_type" name="f_type" value="<?php echo $f_type ?>" /><!-- flag บอกว่า จะดึงชื่อลูกค้า จาก main หรือ Vjoin $f_type = 0 ดึงจาก Vjoin $f_type = 1 ดึงจาก main -->
   <input type="hidden" name="id" value="<?php echo $id2 ?>" />   
<?php if($action=='add' && $numrows2==0 && $numrows>0) { ?>

  <input type="hidden" name="form_name" value="add" />
  <input id="saveForm" class="button_text" type="submit" name="submit" value="ตกลง" style='width:100px; height:50px'/>

  <?php } else if($action=='edit' && $numrows>0 && $cancel=='0') { ?>

 <input type="hidden" name="join_addr2" value="<?Php print $join_addr ?>" />
  <input type="hidden" name="form_name" value="edit" />

 <input id="saveForm" class="button_text" type="submit" name="submit" value="แก้ไข" style='width:100px; height:50px'/>

   <?php }  if($app=='1') { ?>

<!--button type="button" class="button_text" name="button" onclick="update_cs1('<?Php print $id2 ?>');" style="cursor:pointer"  id="button" -->
<button type="button" class="button_text" name="button" onclick="document.forms['my'].appv.click();" style="cursor:pointer"  id="button" >
<img src="../images/staff_check.png" width="50" height="50" /> อนุมัติ</button>
<!--button type="button" class="button_text" name="button" onclick="update_cs0('<?Php print $id2 ?>');" style="cursor:pointer"   id="button" -->
<button type="button" class="button_text" name="button" onclick="document.forms['my'].unappv.click();" style="cursor:pointer"   id="button" >
<img src="../images/del.png" width="50" height="50" /> ไม่อนุมัติ</button>
 <?php }else {  ?>
  <input id="saveForm" class="button_text" type="button" value="ปิด" onclick="window.close()" style='width:100px; height:50px'/>
   <?php } } ?>
</p>
	</li></ul>
		</li> 
