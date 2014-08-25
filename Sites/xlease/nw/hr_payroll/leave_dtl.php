<?php 
include("../../config/config.php");

include("function_payroll.php");
include("../../core/core_functions.php");
$id_user = $_REQUEST[id_user];

$yy = $_GET['yy'];
$mm = $_GET['mm'];

//$yy = '2012';
//$mm = '01';



$app_date = Date('Y-m-d H:i:s');

?>
<script>
  $(document).ready(function() {
    $("button").button();
/*	
	$("input[name='h_type']").change(function(){
if ($("input[name='h_type']:checked").val() == '0') {
	$("h_show").show();

}
else{
   $("h_show").hide();
}
  });
  */
  
    });
function show_add(id_user){

	 $('body').append('<div id="dialog"></div>');

    $('#dialog').load('leave_popup.php?cmd=add&id_user='+id_user);
    $('#dialog').dialog({
        title: 'เพิ่มวันลา ',
        resizable: false,
        modal: true,  
        width: 600,
        height: 240,
        close: function(ev, ui){
            $('#dialog').remove();
        }
    });	
	}
	
function show_edit(id,id_user){

	 $('body').append('<div id="dialog"></div>');

    $('#dialog').load('leave_popup.php?cmd=edit&id='+id+'&id_user='+id_user);
    $('#dialog').dialog({
        title: 'แก้ไขวันลา ',
        resizable: false,
        modal: true,  
        width: 600,
        height: 240,
        close: function(ev, ui){
            $('#dialog').remove();
        }
    });	
	}
	
	function del(id){
  

	if(confirm('ยืนยันการลบข้อมูล!!')==true)
		{
		$.post("leave_api.php",{
			
			
			id :id,
			cmd :'del'
			
		},
		function(data){
		
				alert("ลบรายการเรียบร้อยแล้ว!!");
				$("#panel").load("leave_dtl.php?datepicker="+ $("#datepicker").val() +"&mm="+ $("#mm").val() +"&yy="+ $("#yy").val() +"&ty="+ $("#ty").val()+"&id_user=<?php echo $id_user ?>");
				 
			//window.location.reload() ;
		});
		}
	}
  </script><br />
  <button onclick="show_add('<?php echo $id_user ?>')"  class="ui-button-text-only ui-state-default ui-corner-all">
   เพิ่มวันลา
</button> 
<table class="t2" width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
            <td align="center">ลำดับ</td>
				<td align="center">วันที่ลา</td>
                <td align="center">ประเภท</td>
          
                <td align="center">จำนวนวันที่ลา</td>
				<td align="center">เหตุผล</td>
				<td align="center">จัดการ</td>
			</tr>
            			<?php
if($mm=='0')$leave_date_s ="$yy-";
else $leave_date_s ="$yy-$mm-";
				
			$qry_fr=pg_query("SELECT id,leave_date, leave_type, memo, leave_time_type, time1, time2,h_amt,type_name 
  FROM hr_user_leave left join hr_leave_type on type_id=leave_type where id_user='$id_user' and leave_date::character varying LIKE '$leave_date_s%' and deleted='0' order by leave_date ");	
			 

				
			$nub=pg_num_rows($qry_fr);
			while($sql_row4=pg_fetch_array($qry_fr)){
				$leave_date = $sql_row4['leave_date'];
					$leave_type = $sql_row4['leave_type'];
					$memo = $sql_row4['memo'];
					$leave_time_type =$sql_row4['leave_time_type']; 
					$time1 =$sql_row4['time1']; 
					$time2 = $sql_row4['time2'];
					$h_amt =$sql_row4['h_amt']; 
					$id = $sql_row4['id'];
					$type_name = $sql_row4['type_name'];

				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
			?>
<td align="center"><?php echo $i; ?></td>
				<td align="center"><?php echo $leave_date; ?></td>
                <td align="center">
<?php
//แสดง Combo ให้เลือก >=  สัญญาเก่า
//$qry = pg_query("select type_name from \"hr_leave_type\" where type_id='$leave_type'  ");
//while( $res = pg_fetch_array($qry) ){

	echo  $type_name;
//}
?>
			
                </td><td align="left"><?Php if($leave_time_type=='1'){ echo "1 วัน" ;}
            else if($leave_time_type=='0'){ echo "$h_amt ชั่วโมง ตั้งแต่ $time1 ถึง $time2" ;} ?>
           </strong></td>
     

					<td align="left"><?php echo $memo; ?></td>
                    <td align="center"><button onclick="show_edit('<?php echo $id ?>','<?php echo $id_user ?>')" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all">
  แก้ไข
</button> <button onclick="del('<?php echo $id ?>')"  class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all">
   ลบ
</button> </td>
			</tr>
			<?php
			}
			if($nub == 0){
				echo "<tr><td colspan=6 align=center ><b>- ไม่มีการลา -</b></td></tr>";
			}
			?>
			</table>