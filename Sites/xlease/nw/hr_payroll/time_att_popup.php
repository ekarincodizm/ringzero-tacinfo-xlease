<?php 
include("../../config/config.php");

include("function_payroll.php");
include("../../core/core_functions.php");
$id_user = $_REQUEST[id_user];


$cmd = $_GET['cmd'];
$id = $_GET['id'];
if($id=='')$id=0;

$datetime = $_GET['datepicker'];
$app_date = Date('Y-m-d H:i:s');

?>
<script>
   var d = new Date();
var s = d.getDate();
var m = d.getMonth()+1;
var y = d.getFullYear();
    

	  $("#datetime").datetimepicker({ dateFormat: 'yy-mm-dd',
		timeOnlyTitle: 'ตั้งแต่',
	timeText: 'เวลา',
	hourText: 'ชั่วโมง',
	minuteText: 'นาที',
	currentText: 'วันนี้',
	closeText: 'ปิด',
	hourGrid: 4,
	minuteGrid: 10,
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
	   
    $("button").button();

    });

  </script>
	<?php
	
			
	if($cmd=="edit"){
				
				$qry_fr=pg_query("SELECT datetime, type_id, memo FROM \"LogsTimeAtt2012\" where id='$id' ");	

			$nub=pg_num_rows($qry_fr);
			while($sql_row4=pg_fetch_array($qry_fr)){
					$datetime = $sql_row4['datetime'];
					$type_id = $sql_row4['type_id'];
					$memo = $sql_row4['memo'];

					

			

			}
	}
				
			?>
<table class="t2" width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			
			<tr style="font-weight:bold;" valign="middle" align="center">
         
				<td align="right">วันเวลา</td>
                <td align="left"><input type="text" name="datetime" id="datetime" size="20" value="<?php echo $datetime; ?>" ></td>
                </tr><tr style="font-weight:bold;" valign="middle" align="center">
                <td align="right">ช่วงเวลา</td>
                <td align="left">
                <select name="type_id" id="type_id" >
<?php
//แสดง Combo ให้เลือก >=  สัญญาเก่า
$qry = pg_query("select type_id,type_name from \"LogsTimeAttType\" where type_id <5 ");
while( $res = pg_fetch_array($qry) ){
    $type_id2 = $res['type_id'];
	$type_name = $res['type_name'];
?>
    <option <?Php if($type_id==$type_id2){ echo "selected" ;} ?> value="<?php echo $type_id2; ?>" ><?php echo $type_name; ?></option>
<?php
}
?>
</select></td>
          </tr>
                <tr style="font-weight:bold;" valign="middle" align="center">
				<td align="right">หมายเหตุ</td>
                <td align="left"><textarea class="element textarea small" name="memo" style="width:100%" id="memo"><?Php print $memo ?></textarea></td>
                </tr>
			


			</table><div style="float:right"><button onclick="<?Php if($cmd=="add")echo "add();" ; else if($cmd=="edit")echo "edit();"; ?>" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all">
  <?Php if($cmd=="add")echo "เพิ่ม" ; else if($cmd=="edit")echo "แก้ไข"; ?>
</button> </div>

<script type="text/javascript">

function add(){

	
		$.post("time_att_api.php",{
			
			datetime :$("#datetime").val(),
			type_id :$("#type_id").val(),
			memo :$("#memo").val(),
			id_user :'<?php echo $id_user ?>',
			datetime_old :'<?php echo $datetime ?>',
			type_id_old :'<?php echo $type_id ?>',
			memo_old :'<?php echo $memo ?>',
			cmd :'add'
			
		},
		function(data){
			//alert(data);
			if(data=='0'){
				alert("บันทึกรายการเรียบร้อย");
				$("#panel").load("time_att_dtl.php?datepicker="+ $("#datepicker").val() +"&id_user=<?php echo $id_user ?>");
				 $('#dialog').remove();
			}else{
				if(data!='')
				alert(data);
				else
				alert("ไม่สามารถทำรายการได้");
			}
			//window.location.reload() ;
		});
	};
	
	function edit(){

	
	
		$.post("time_att_api.php",{
			
			datetime :$("#datetime").val(),
			type_id :$("#type_id").val(),
			memo :$("#memo").val(),
			id :<?php echo $id ?>,
			id_user :'<?php echo $id_user ?>',
			datetime_old :'<?php echo $datetime ?>',
			type_id_old :'<?php echo $type_id ?>',
			memo_old :'<?php echo $memo ?>',
			cmd :'edit'
			
		},
		function(data){
		if(data=='0'){
				alert("แก้ไขรายการเรียบร้อย");
				$("#panel").load("time_att_dtl.php?datepicker="+ $("#datepicker").val() +"&id_user=<?php echo $id_user ?>");
				 $('#dialog').remove();
				 
				 }else{
				
				alert("ไม่สามารถทำรายการได้");
			}
			//window.location.reload() ;
		});
	};
	

  
</script>