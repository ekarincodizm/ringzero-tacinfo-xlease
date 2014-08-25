<?php 
include("../../config/config.php");

include("function_payroll.php");
include("../../core/core_functions.php");
$id_user = $_REQUEST[id_user];


$cmd = $_GET['cmd'];
$id = $_GET['id'];
if($id=='')$id=0;
$yy = $_GET['yy'];
$mm = $_GET['mm'];

//$yy = '2012';
//$mm = '01';


$app_date = Date('Y-m-d H:i:s');

?>
<script>
   var d = new Date();
var s = d.getDate();
var m = d.getMonth()+1;
var y = d.getFullYear();
    

	  $("#leave_date").datepicker({ dateFormat: 'yy-mm-dd',

     defaultDate: y+'-'+m+'-'+s,
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
  $(document).ready(function() {
	    $("h_show").hide();
    $("button").button();
	
	$('#time1').timepicker({
	timeOnlyTitle: 'ตั้งแต่',
	timeText: 'เวลา',
	hourText: 'ชั่วโมง',
	minuteText: 'นาที',
	currentText: 'วันนี้',
	closeText: 'ปิด',
	hourGrid: 4,
	minuteGrid: 10,
	timeFormat: 'hh:mm:ss'

});
$('#time2').timepicker({
	timeOnlyTitle: 'ถึง',
	timeText: 'เวลา',
	hourText: 'ชั่วโมง',
	minuteText: 'นาที',
	currentText: 'วันนี้',
	closeText: 'ปิด',
	hourGrid: 4,
	minuteGrid: 10,
	timeFormat: 'hh:mm:ss'


});
  
    });
function show_p(obj){
	
	if(obj.value==0){
		
		$('#h_show').show()
	}else $('#h_show').hide()
	
}
$("button").button();
  </script>
	<?php
	
			
	if($cmd=="edit"){
				
			$qry_fr=pg_query("SELECT leave_date, leave_type, memo, leave_time_type, time1, time2, 
       h_amt
  FROM hr_user_leave where id = '$id' and deleted='0' ");	
			 

				
			$nub=pg_num_rows($qry_fr);
			while($sql_row4=pg_fetch_array($qry_fr)){
					$leave_date = $sql_row4['leave_date'];
					$leave_type = $sql_row4['leave_type'];
					$memo = $sql_row4['memo'];
					$leave_time_type =$sql_row4['leave_time_type']; 
					$time1 =$sql_row4['time1']; 
					$time2 = $sql_row4['time2'];
					$h_amt =$sql_row4['h_amt']; 

			

			}
	}else {
		
		$leave_time_type =1;
		
	}
				
			?>
<table class="t2" width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			
			<tr style="font-weight:bold;" valign="middle" align="center">
         
				<td align="right">วันลา</td>
                <td align="left"><input type="text" name="leave_date" id="leave_date" size="10" value="<?php echo $leave_date; ?>" ></td>
                </tr><tr style="font-weight:bold;" valign="middle" align="center">
                <td align="right">ประเภท</td>
                <td align="left">
                <select name="leave_type" id="leave_type" >
<?php
//แสดง Combo ให้เลือก >=  สัญญาเก่า
$qry = pg_query("select type_id,type_name from \"hr_leave_type\" ");
while( $res = pg_fetch_array($qry) ){
    $type_id = $res['type_id'];
	$type_name = $res['type_name'];
?>
    <option <?Php if($leave_type==$type_id){ echo "selected" ;} ?> value="<?php echo $type_id; ?>" ><?php echo $type_name; ?></option>
<?php
}
?>
</select></td>
          </tr>
          <tr style="font-weight:bold;" valign="middle" align="center">
                <td align="right">จำนวนวันที่ลา</td>
                 <td align="left">
                	<script type="text/javascript">
<?Php if($leave_time_type=='1'){ ?>
$('#h_show').hide();
<?Php } ?>

</script>
                <input type="radio" name="leave_time_type" id="leave_time_type1" onclick="show_p(this)" value="1" <?Php if($leave_time_type=='1'){ echo "checked" ;} ?>/>
            1 วัน
	          <input type="radio" name="leave_time_type" id="leave_time_type2" onclick="show_p(this)"  value="0" <?Php if($leave_time_type=='0'){ echo "checked" ;} ?>/>
            ชั่วโมง : <span id="h_show"> จำนวนชั่วโมง : <input type="text" name="h_amt" id="h_amt" size="3" value="<?php echo $h_amt; ?>" > ตั้งแต่ <input type="text" name="time1" id="time1" size="10" value="<?php echo $time1; ?>" > ถึง <input type="text" name="time2" id="time2" size="10" value="<?php echo $time2; ?>"></span></strong></td>
                </tr>
                <tr style="font-weight:bold;" valign="middle" align="center">
				<td align="right">เหตุผล</td>
                <td align="left"><textarea class="element textarea small" name="memo" style="width:100%" id="memo"><?Php print $memo ?></textarea></td>
                </tr>
			


			</table><div style="float:right"><button onclick="<?Php if($cmd=="add")echo "add();" ; else if($cmd=="edit")echo "edit();"; ?>" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all">
  <?Php if($cmd=="add")echo "เพิ่ม" ; else if($cmd=="edit")echo "แก้ไข"; ?>
</button> </div>

<script type="text/javascript">

function add(){
  
var leave_time_type ;
	 if(document.getElementById("leave_time_type1").checked==true){
		 leave_time_type = $("#leave_time_type1").val();
	 }else if(document.getElementById("leave_time_type2").checked==true){
		 leave_time_type = $("#leave_time_type2").val();
	 }
	
		$.post("leave_api.php",{
			
			leave_date :$("#leave_date").val(),
			leave_type :$("#leave_type").val(),
			leave_time_type :leave_time_type,
			h_amt :$("#h_amt").val(),
			time1 :$("#time1").val(),
			time2 :$("#time2").val(),
			memo :$("#memo").val(),
			id_user :'<?php echo $id_user ?>',
			cmd :'add'
			
		},
		function(data){
			//alert(data);
				alert("บันทึกรายการเรียบร้อย");
				$("#panel").load("leave_dtl.php?datepicker="+ $("#datepicker").val() +"&mm="+ $("#mm").val() +"&yy="+ $("#yy").val() +"&ty="+ $("#ty").val()+"&id_user=<?php echo $id_user ?>");
				 $('#dialog').remove();
			//window.location.reload() ;
		});
	};
	
	function edit(){
  
var leave_time_type ;
	 if(document.getElementById("leave_time_type1").checked==true){
		 leave_time_type = $("#leave_time_type1").val();
	 }else if(document.getElementById("leave_time_type2").checked==true){
		 leave_time_type = $("#leave_time_type2").val();
	 }
	
	
		$.post("leave_api.php",{
			
			leave_date :$("#leave_date").val(),
			leave_type :$("#leave_type").val(),
			leave_time_type :leave_time_type,
			h_amt :$("#h_amt").val(),
			time1 :$("#time1").val(),
			time2 :$("#time2").val(),
			memo :$("#memo").val(),
			id :<?php echo $id ?>,
			cmd :'edit'
			
		},
		function(data){
		
				alert("แก้ไขรายการเรียบร้อย");
				$("#panel").load("leave_dtl.php?datepicker="+ $("#datepicker").val() +"&mm="+ $("#mm").val() +"&yy="+ $("#yy").val() +"&ty="+ $("#ty").val()+"&id_user=<?php echo $id_user ?>");
				 $('#dialog').remove();
			//window.location.reload() ;
		});
	};
	

  
</script>