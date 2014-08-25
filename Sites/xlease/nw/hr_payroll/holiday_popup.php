<?php 
include("../../config/config.php");

$cmd = $_GET['cmd'];
$id = $_GET['id'];
$yy = $_GET['yy'];
if($id=='')$id=0;
$app_date = Date('Y-m-d H:i:s');

?>
<script>
   var d = new Date();
var s = d.getDate();
var m = d.getMonth()+1;
var y = d.getFullYear();
    

	  $("#pub_holiday").datepicker({ dateFormat: 'yy-mm-dd',

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
$("button").button();

  </script>
	<?php
	$pub_holiday = $yy.'-01-01';
			
	if($cmd=="edit"){
				
			$qry_fr=pg_query("SELECT pub_holiday, \"desc\" FROM hr_public_holiday where pub_id = '$id' ");	
			 
//a.datetime::character varying LIKE '$datetime_s%'
				
			$nub=pg_num_rows($qry_fr);
			while($sql_row4=pg_fetch_array($qry_fr)){
					$pub_holiday = $sql_row4['pub_holiday'];
					$desc = $sql_row4['desc'];
				
			

			}
	}
				
			?>
<table class="t2" width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			
			<tr style="font-weight:bold;" valign="middle" align="center">
         
				<td align="right">วันหยุดประจำปี</td>
                <td align="left"><input type="text" name="pub_holiday" id="pub_holiday" size="10" value="<?php echo $pub_holiday; ?>" ></td>
                </tr>
                <tr style="font-weight:bold;" valign="middle" align="center">
				<td align="right">รายละเอียด</td>
                <td align="left"><textarea class="element textarea small" name="desc" style="width:100%" id="desc"><?Php print $desc ?></textarea></td>
                </tr>
			


			</table><div style="float:right"><button onclick="<?Php if($cmd=="add")echo "add();" ; else if($cmd=="edit")echo "edit();"; ?>" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all">
    &nbsp; &nbsp;<?Php if($cmd=="add")echo "  เพิ่ม  " ; else if($cmd=="edit")echo "  แก้ไข  "; ?> &nbsp; &nbsp;
</button> </div>

<script type="text/javascript">

function add(){

	
		$.post("holiday_api.php",{
			
			pub_holiday :$("#pub_holiday").val(),
			desc :$("#desc").val(),
			cmd :'add'
			
		},
		function(data){
			//alert(data);
			if(data==''){
				alert("บันทึกรายการเรียบร้อย");
				$("#panel").load("holiday_dtl.php?yy="+ $("#yy").val());
				 $('#dialog').remove();
			}else alert(data);
			//window.location.reload() ;
		});
	};
	
	function edit(){

	
	
		$.post("holiday_api.php",{
			
			pub_holiday :$("#pub_holiday").val(),
			desc :$("#desc").val(),
			id :<?php echo $id ?>,
			cmd :'edit'
			
		},
		function(data){
		
				alert("แก้ไขรายการเรียบร้อย");
				$("#panel").load("holiday_dtl.php?yy="+ $("#yy").val());
				 $('#dialog').remove();
			//window.location.reload() ;
		});
	};
	

  
</script>